<?php

namespace app\admin\controller;

use Workerman\Lib\Timer;
use Workerman\Worker;

require_once __DIR__ . '/../../../vendor/workerman/workerman/Autoloader.php';


/**
 * tcp连接启动并处理逻辑
 */
class Start
{
    public $worker;

    // 心跳间隔1800秒
    protected $HEARTBEAT_TIME = 60;

    public function index()
    {
        $this->worker              = new Worker('websocket://0.0.0.0:8085');
        $this->worker->count       = 4;
        $this->worker->connections = [];

        $this->worker->onMessage     = function ($connection, $data) {
            $this->onMessage($connection, $data);
        };
        $this->worker->onWorkerStart = function () {
            $this->onWokerStart();
        };
        $this->worker->onConnect     = function ($connection) {
            $this->onConnect($connection);
        };
        $this->worker->onClose       = function ($connection) {
            $this->onClose($connection);
        };

        $this->worker->onError = function ($connection, $code, $msg) {
            $this->onError($connection, $code, $msg);
        };
        Worker::runAll();
    }

    private function onWokerStart()
    {
        echo date("Y-m-d H:i:s") . PHP_EOL;
        echo '开启8085端口监听外部信息' . PHP_EOL;
        // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
        $inner_text_worker            = new Worker('text://0.0.0.0:8086');
        $inner_text_worker->onMessage = function ($innerConnection, $buffer) {
            $this->onMessage($innerConnection, $buffer);
        };
        // ## 执行监听 ##
        $inner_text_worker->listen();
        echo '开启8086端口监听内部信息' . PHP_EOL;
        // 每秒输出一次
        // Timer::add(1, function () {
        //     echo date('H:i:s') . " 测试输出" . PHP_EOL;
        // });
        Timer::add(25, function () {
            $time_now = time();
            $memory = memory_get_usage(true);
            $memory_mb = round($memory / 1024 / 1024, 2);
            $str      = "totalConnections:" . count($this->worker->connections) . PHP_EOL;
            $str      .= "timeNow:" . date("Y-m-d H:i:s") . PHP_EOL;
            $str      .= "mem:" . $memory_mb . PHP_EOL;

            foreach ($this->worker->connections as $connection) {
                // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                if (empty($connection->lastMessageTime)) {
                    $connection->lastMessageTime = $time_now;
                    continue;
                }
                // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                if ($time_now - $connection->lastMessageTime > $this->HEARTBEAT_TIME) {
                    echo "Close" . PHP_EOL;
                    $connection->close();
                }
            }
            // echo $str;
            // Log::record($str);
            gc_collect_cycles();
        });
        echo '开始检测心跳' . PHP_EOL;
    }

    /**
     * 处理信息
     * @param $connection
     * @param $data {"admin_id":1,"code": 0,"title":"","content":"测试信息","receive_ids":[],"requestId":""} 0心跳包 1通知下发 2请求 3登录广播
     * @param $send {"code":0,"title":"","sender":"","type":"","content":"测试信息","operate":""} 
     * code 0心跳包回应 1普通信息 2请求回应 3登录广播
     * type info|success|warning|error
     * @return void
     */
    private function onMessage($connection, $data)
    {
        try {
            $data = json_decode($data, true);
            //初始化时间
            $connection->lastMessageTime = time();
            if (!isset($data['code'])) {
                return;
            }
            if (isset($data['admin_id'])) {
                $connection->admin_id = $data['admin_id'];
            }
            //心跳包
            if ($data['code'] == 0) {
                $send_data = ['code' => 0];
                $connection->send(json_encode($send_data, JSON_UNESCAPED_UNICODE));
                return;
            }
            //通知下发
            if ($data['code'] == 1) {
                if (!isset($data['receive_ids'])) {
                    return;
                }
                $send_data = [
                    'code' => 1,
                    "sender" => "系统消息",
                    "type" => 'info',
                    "title" => $data['title'] ?? '',
                    'content' => $data['content'],
                    'fj_file' => $data['fj_file'] ?? '',
                ];
                $this->sendToClient($data['receive_ids'], json_encode($send_data, JSON_UNESCAPED_UNICODE));
                return;
            }
            // 用户登录 必须字段： content
            if ($data['code'] == 3) {
                $send_data = [
                    'code' => 3,
                    "sender" => "系统",
                    "type" => 'success',
                    "title" => '用户登录',
                    'content' => $data['content'],
                    'duration' => 3000
                ];
                $this->sendToClient('*', json_encode($send_data, JSON_UNESCAPED_UNICODE));
                return;
            }
            // 更新消息数量角标
            if ($data['code'] == 4) {
                $send_data = [
                    'code' => 4,
                    'count' => $data['count'],
                ];
                $this->sendToClient($data['receive_ids'], json_encode($send_data, JSON_UNESCAPED_UNICODE));
                return;
            }
            // 退出客户端
            if ($data['code'] == 6){
                $send_data = [
                    'code' => 6
                ];
                $this->sendToClient($data['receive_ids'], json_encode($send_data, JSON_UNESCAPED_UNICODE));
            }
            if (!empty($data['requestId'])) {
                //请求回应
                $send_data = [
                    'code' => 2,
                    'requestId' => $data['requestId']
                ];
                $connection->send(json_encode($send_data, JSON_UNESCAPED_UNICODE));
                return;
            }
        } catch (\Throwable $e) {
            echo "处理消息时发生致命错误: " . $e->getMessage() . PHP_EOL;
            echo "堆栈: " . $e->getTraceAsString() . PHP_EOL;
        }
    }

    protected function onConnect($connection)
    {
        echo "connect连接数量" . count($this->worker->connections) . PHP_EOL;
    }

    protected function onClose($connection)
    {
        echo "close连接数量" . count($this->worker->connections) . PHP_EOL;
    }

    protected function onError($connection, $code, $msg)
    {
        echo "[" . date("Y-m-d H:i:s") . "] 连接错误: {$code} - {$msg}\n";
    }

    protected function sendToClient($receive_ids, $message)
    {
        $receive_ids_arr = explode(',', $receive_ids);
        foreach ($this->worker->connections as $connection) {
            if ($receive_ids != '*' && (!isset($connection->admin_id) || !in_array($connection->admin_id, $receive_ids_arr))) {
                continue;
            }
            $connection->send($message);
        }
    }
}
