/**
 * WebSocket 连接管理器
 * 功能：
 * 1. 自动重连
 * 2. 心跳检测
 * 3. 消息队列
 * 4. 状态管理
 * 5. 事件监听
 */
class WebSocketManager {
    constructor(options = {}) {
        // 配置项
        this.config = {
            url: options.url || 'ws://localhost:8080',
            protocols: options.protocols || [],
            reconnectInterval: options.reconnectInterval || 3000, // 重连间隔(ms)
            maxReconnectAttempts: options.maxReconnectAttempts || 5, // 最大重连次数
            heartbeatInterval: options.heartbeatInterval || 30000, // 心跳间隔(ms)
            heartbeatTimeout: options.heartbeatTimeout || 10000, // 心跳超时(ms)
            debug: options.debug || false, // 调试模式
            autoConnect: options.autoConnect !== false, // 是否自动连接
            ...options
        };

        // 状态变量
        this.ws = null;
        this.reconnectAttempts = 0;
        this.heartbeatTimer = null;
        this.reconnectTimer = null;
        this.isConnected = false;
        this.isConnecting = false;
        this.messageQueue = []; // 消息队列
        this.eventListeners = new Map(); // 事件监听器
        this.pendingPong = false; // 是否在等待pong响应
        this.messageIdCounter = 0; // 消息ID计数器
        this.pendingRequests = new Map(); // 等待响应的请求

        // 初始化事件
        this.initEvents();

        // 自动连接
        if (this.config.autoConnect) {
            setTimeout(() => this.connect(), 0);
        }
        let lastlogin = localStorage.getItem("lastlogin");
        lastlogin = JSON.parse(lastlogin);
        this.admin_id = lastlogin.id;
    }

    // 初始化事件
    initEvents() {
        const events = ['open', 'close', 'error', 'message', 'reconnect', 'heartbeat'];
        events.forEach(event => {
            this.eventListeners.set(event, []);
        });
    }

    // 连接WebSocket
    connect() {
        if (this.isConnecting || this.isConnected) {
            this.log('WebSocket 正在连接或已连接');
            return;
        }

        this.isConnecting = true;
        this.log(`正在连接到: ${this.config.url}`);

        try {
            this.ws = new WebSocket(this.config.url, this.config.protocols);

            this.ws.onopen = (event) => this.handleOpen(event);
            this.ws.onclose = (event) => this.handleClose(event);
            this.ws.onerror = (event) => this.handleError(event);
            this.ws.onmessage = (event) => this.handleMessage(event);
        } catch (error) {
            this.handleError(error);
            this.scheduleReconnect();
        }
    }

    // 处理连接打开
    handleOpen(event) {
        this.isConnected = true;
        this.isConnecting = false;
        this.reconnectAttempts = 0;

        this.log('WebSocket 连接已建立');
        this.emit('open', event);

        // 开始心跳检测
        this.startHeartbeat();

        // 发送队列中的消息
        this.flushMessageQueue();
    }

    // 处理连接关闭
    handleClose(event) {
        this.isConnected = false;
        this.isConnecting = false;

        this.log(`连接关闭，代码: ${event.code}, 原因: ${event.reason || '无'}`);
        this.emit('close', event);

        // 清理心跳
        this.stopHeartbeat();

        // 如果不是正常关闭，则尝试重连
        if (event.code !== 1000 && event.code !== 1005) {
            this.scheduleReconnect();
        }
    }

    // 处理错误
    handleError(error) {
        this.isConnecting = false;
        this.log('WebSocket 错误:', error);
        this.emit('error', error);
    }

    // 处理接收消息
    handleMessage(event) {
        try {
            let data = event.data;

            // 尝试解析JSON
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    // 不是JSON，保持原样
                }
            }

            this.log('收到消息:', data);

            // 处理心跳响应
            if (data.code === 0) {
                this.handlePong();
                return;
            }

            // 处理请求响应
            if (data && data.requestId) {
                this.handleResponse(data);
                return;
            }

            // 退出登录
            if (data.code === 6){
                document.getElementById('logout-btn').click()
            }

            // 触发消息事件
            this.emit('message', data);
        } catch (error) {
            this.log('处理消息时出错:', error);
        }
    }

    // 发送消息
    send(data, options = {}) {
        return new Promise((resolve, reject) => {
            // 检查连接状态
            if (!this.isConnected) {
                if (options.queueIfDisconnected !== false) {
                    // 加入队列等待发送
                    this.messageQueue.push({ data, resolve, reject });
                    this.log('消息已加入队列，等待连接恢复');
                    return;
                } else {
                    reject(new Error('WebSocket 未连接'));
                    return;
                }
            }

            // 格式化消息
            let message = data;
            if (typeof data !== 'string') {
                try {
                    data.admin_id = this.admin_id;
                    data.timestamp = Date.now();
                    message = JSON.stringify(data);
                } catch (error) {
                    reject(new Error('消息序列化失败'));
                    return;
                }
            }

            // 发送消息
            try {
                this.ws.send(message);
                resolve();
                this.log('消息发送成功:', message);
            } catch (error) {
                this.log('发送消息失败:', error);
                reject(error);
            }
        });
    }
    

    // 发送带响应的请求
    sendRequest(payload, timeout = 10000) {
        return new Promise((resolve, reject) => {
            if (!this.isConnected) {
                reject(new Error('WebSocket 未连接'));
                return;
            }

            const requestId = `req_${Date.now()}_${++this.messageIdCounter}`;
            const request = {
                ...payload,
                requestId
            };

            // 设置超时
            const timeoutId = setTimeout(() => {
                this.pendingRequests.delete(requestId);
                reject(new Error(`请求超时 (${timeout}ms)`));
            }, timeout);

            // 保存请求
            this.pendingRequests.set(requestId, { resolve, reject, timeoutId });

            // 发送请求
            this.send(request).catch(reject);
        });
    }

    // 处理响应
    handleResponse(data) {
        const { requestId } = data.requestId;
        const pendingRequest = this.pendingRequests.get(requestId);

        if (pendingRequest) {
            clearTimeout(pendingRequest.timeoutId);
            this.pendingRequests.delete(requestId);

            if (data.error) {
                pendingRequest.reject(new Error(data.error));
            } else {
                pendingRequest.resolve(data);
            }
        }
    }

    // 开始心跳检测
    startHeartbeat() {
        this.stopHeartbeat(); // 先停止之前的心跳

        this.heartbeatTimer = setInterval(() => {
            this.sendHeartbeat();
        }, this.config.heartbeatInterval);

        this.log('心跳检测已启动');
        this.sendHeartbeat(); // 立即发送一次心跳
    }

    // 停止心跳检测
    stopHeartbeat() {
        if (this.heartbeatTimer) {
            clearInterval(this.heartbeatTimer);
            this.heartbeatTimer = null;
        }

        if (this.heartbeatTimeoutTimer) {
            clearTimeout(this.heartbeatTimeoutTimer);
            this.heartbeatTimeoutTimer = null;
        }

        this.pendingPong = false;
    }

    // 发送心跳包
    sendHeartbeat() {
        if (!this.isConnected) return;

        if (this.pendingPong) {
            this.log('心跳响应超时，重新连接...');
            this.ws.close(4000, '心跳超时');
            return;
        }

        this.pendingPong = true;

        // 发送ping
        const pingMessage = { code: 0, content: 'ping'};
        this.send(pingMessage).catch(() => {
            this.pendingPong = false;
        });

        // 设置超时
        this.heartbeatTimeoutTimer = setTimeout(() => {
            if (this.pendingPong) {
                this.log('心跳响应超时，重新连接...');
                this.ws.close(4000, '心跳超时');
            }
        }, this.config.heartbeatTimeout);

        this.emit('heartbeat', { type: 'ping' });
    }

    // 处理pong响应
    handlePong() {
        this.pendingPong = false;

        if (this.heartbeatTimeoutTimer) {
            clearTimeout(this.heartbeatTimeoutTimer);
            this.heartbeatTimeoutTimer = null;
        }

        this.emit('heartbeat', { type: 'pong' });
    }

    // 安排重连
    scheduleReconnect() {
        if (this.reconnectTimer || this.isConnected || this.isConnecting) {
            return;
        }

        if (this.reconnectAttempts >= this.config.maxReconnectAttempts) {
            this.log(`已达到最大重连次数: ${this.config.maxReconnectAttempts}`);
            return;
        }

        this.reconnectAttempts++;
        const delay = this.config.reconnectInterval * Math.min(this.reconnectAttempts, 3);

        this.log(`将在 ${delay}ms 后尝试重连 (${this.reconnectAttempts}/${this.config.maxReconnectAttempts})`);

        this.reconnectTimer = setTimeout(() => {
            this.reconnectTimer = null;
            this.emit('reconnect', { attempt: this.reconnectAttempts });
            this.connect();
        }, delay);
    }

    // 刷新消息队列
    flushMessageQueue() {
        if (this.messageQueue.length === 0) return;

        this.log(`开始发送队列中的 ${this.messageQueue.length} 条消息`);

        const queue = [...this.messageQueue];
        this.messageQueue = [];

        queue.forEach(item => {
            this.send(item.data)
                .then(item.resolve)
                .catch(item.reject);
        });
    }

    // 关闭连接
    close(code = 1000, reason = '正常关闭') {
        this.log('正在关闭连接...');

        // 清理定时器
        this.stopHeartbeat();

        if (this.reconnectTimer) {
            clearTimeout(this.reconnectTimer);
            this.reconnectTimer = null;
        }

        // 清理等待的请求
        this.pendingRequests.forEach(request => {
            clearTimeout(request.timeoutId);
            request.reject(new Error('连接已关闭'));
        });
        this.pendingRequests.clear();

        // 关闭WebSocket
        if (this.ws) {
            this.ws.close(code, reason);
        }

        this.isConnected = false;
        this.isConnecting = false;
    }

    // 重新连接
    reconnect() {
        this.log('手动重新连接...');
        this.close(1000, '手动重连');
        this.reconnectAttempts = 0;
        setTimeout(() => this.connect(), 100);
    }

    // 事件监听
    on(event, callback) {
        if (this.eventListeners.has(event)) {
            this.eventListeners.get(event).push(callback);
        } else {
            this.eventListeners.set(event, [callback]);
        }
        return this;
    }

    off(event, callback) {
        if (this.eventListeners.has(event)) {
            if (callback) {
                const listeners = this.eventListeners.get(event);
                const index = listeners.indexOf(callback);
                if (index > -1) {
                    listeners.splice(index, 1);
                }
            } else {
                this.eventListeners.set(event, []);
            }
        }
        return this;
    }

    // 触发事件
    emit(event, data) {
        if (this.eventListeners.has(event)) {
            this.eventListeners.get(event).forEach(callback => {
                try {
                    callback(data);
                } catch (error) {
                    this.log(`事件 ${event} 的回调执行出错:`, error);
                }
            });
        }
    }

    // 日志
    log(...args) {
        if (this.config.debug) {
            console.log(`[WebSocket ${this.config.url}]`, ...args);
        }
    }

    // 获取连接状态
    getStatus() {
        return {
            isConnected: this.isConnected,
            isConnecting: this.isConnecting,
            reconnectAttempts: this.reconnectAttempts,
            queueLength: this.messageQueue.length,
            pendingRequests: this.pendingRequests.size
        };
    }

    
}

// 使用示例
export default WebSocketManager;