<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:73:"/www/wwwroot/fadmin/public/../application/admin/view/dashboard/index.html";i:1765040137;s:62:"/www/wwwroot/fadmin/application/admin/view/layout/default.html";i:1764904031;s:59:"/www/wwwroot/fadmin/application/admin/view/common/meta.html";i:1764946205;s:61:"/www/wwwroot/fadmin/application/admin/view/common/script.html";i:1764949863;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<meta name="referrer" content="never">
<meta name="robots" content="noindex, nofollow">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo htmlentities(\think\Config::get('site.version') ?? ''); ?>" rel="stylesheet">

<?php if(\think\Config::get('fastadmin.adminskin')): ?>
<link href="/assets/css/skins/<?php echo htmlentities(\think\Config::get('fastadmin.adminskin') ?? ''); ?>.css?v=<?php echo htmlentities(\think\Config::get('site.version') ?? ''); ?>" rel="stylesheet">
<?php endif; ?>

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config ?? ''); ?>
    };
</script>

    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG && !\think\Config::get('fastadmin.multiplenav') && \think\Config::get('fastadmin.breadcrumb')): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <?php if($auth->check('dashboard')): ?>
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                    <?php endif; ?>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo htmlentities($vo['url'] ?? ''); ?>"><?php echo htmlentities($vo['title'] ?? ''); ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <style type="text/css">
    /* 现代化控制台样式 */
    :root {
        --primary-color: #409eff;
        --success-color: #67c23a;
        --warning-color: #e6a23c;
        --danger-color: #f56c6c;
        --info-color: #909399;
        --light-bg: #f5f7fa;
        --card-bg: #ffffff;
        --text-primary: #303133;
        --text-regular: #606266;
        --text-secondary: #909399;
        --border-color: #e4e7ed;
        --box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
        --border-radius: 8px;
        --transition: all 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
    }
/* 
    .dashboard-container {
        padding: 20px;
        min-height: calc(100vh - 60px);
    } */

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .dashboard-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .refresh-info {
        display: flex;
        align-items: center;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .refresh-icon {
        margin-right: 8px;
        animation: spin 2s linear infinite;
        display: none;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* 统计卡片样式 */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        border: 1px solid var(--border-color);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.1);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-title {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 0;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .stat-change {
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    .stat-change.positive {
        color: var(--success-color);
    }

    .stat-change.negative {
        color: var(--danger-color);
    }

    /* 图表网格样式 */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    .chart-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--box-shadow);
        border: 1px solid var(--border-color);
    }

    .chart-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0 0 16px 0;
    }

    .chart-container {
        width: 100%;
        height: 350px;
    }

    /* 详细统计卡片样式 */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .detail-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--box-shadow);
        border: 1px solid var(--border-color);
    }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .detail-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
    }

    .detail-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-primary {
        background-color: rgba(64, 158, 255, 0.1);
        color: var(--primary-color);
    }

    .detail-content {
        margin-bottom: 16px;
    }

    .detail-value {
        font-size: 28px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .detail-label {
        font-size: 14px;
        color: var(--text-secondary);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-top: 1px solid var(--border-color);
    }

    .detail-row:first-child {
        border-top: none;
    }

    /* 最近活动样式 */
    .activity-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--box-shadow);
        border: 1px solid var(--border-color);
    }

    .activity-title {
        font-size: 16px;
        font-weight: 500;
        color: var(--text-primary);
        margin: 0 0 16px 0;
    }

    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        margin-right: 12px;
        color: var(--primary-color);
        font-size: 18px;
    }

    .activity-content {
        flex: 1;
    }

    .activity-text {
        font-size: 14px;
        color: var(--text-regular);
        margin-bottom: 4px;
    }

    .activity-time {
        font-size: 12px;
        color: var(--text-secondary);
    }

    /* 快捷操作样式 */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 16px;
    }

    .quick-action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 20px 12px;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        border: 1px solid var(--border-color);
        text-decoration: none;
        color: var(--text-primary);
    }

    .quick-action-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.1);
        color: var(--primary-color);
    }

    .quick-action-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }

    .quick-action-label {
        font-size: 14px;
        font-weight: 500;
    }

    /* 响应式设计 */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 12px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }

        .chart-container {
            height: 300px;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }
    }

    /* 卡片颜色主题 */
    .bg-primary {
        background-color: var(--primary-color) !important;
    }

    .bg-success {
        background-color: var(--success-color) !important;
    }

    .bg-warning {
        background-color: var(--warning-color) !important;
    }

    .bg-danger {
        background-color: var(--danger-color) !important;
    }

    .bg-info {
        background-color: var(--info-color) !important;
    }

    /* 标签页样式优化 */
    .nav-tabs {
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .nav-tabs > li > a {
        color: var(--text-regular);
        border: none;
        border-radius: 0;
        padding: 12px 20px;
        margin-right: 0;
        transition: var(--transition);
    }

    .nav-tabs > li > a:hover {
        color: var(--primary-color);
        background-color: transparent;
        border-bottom: 2px solid var(--primary-color);
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
        color: var(--primary-color);
        background-color: transparent;
        border: none;
        border-bottom: 2px solid var(--primary-color);
    }

    .tab-content {
        background-color: var(--light-bg);
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        padding: 0;
        box-shadow: var(--box-shadow);
        border: 1px solid var(--border-color);
        border-top: none;
    }
</style>
                <!-- <div class="dashboard-container"> -->
                    <!-- 刷新信息 -->
                    <!-- <div class="dashboard-header">
                        <div class="refresh-info">
                            <i class="fa fa-spinner refresh-icon" id="refreshIcon"></i>
                            <span id="lastRefresh">最后刷新: <span id="refreshTime"></span></span>
                        </div>
                    </div> -->

                    <!-- 统计卡片 -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-header">
                                <h3 class="stat-title">总用户数</h3>
                                <div class="stat-icon bg-primary">
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalUser"><?php echo htmlentities($totaluser ?? ''); ?></div>
                            <div class="stat-change positive">
                                <i class="fa fa-arrow-up"></i>
                                <span>较上月增长 5%</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-header">
                                <h3 class="stat-title">插件总数</h3>
                                <div class="stat-icon bg-success">
                                    <i class="fa fa-magic"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalAddon"><?php echo htmlentities($totaladdon ?? ''); ?></div>
                            <div class="stat-change positive">
                                <i class="fa fa-arrow-up"></i>
                                <span>较上月增长 3%</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-header">
                                <h3 class="stat-title">附件总数</h3>
                                <div class="stat-icon bg-warning">
                                    <i class="fa fa-leaf"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalAttachment"><?php echo htmlentities($attachmentnums ?? ''); ?></div>
                            <div class="stat-change positive">
                                <i class="fa fa-arrow-up"></i>
                                <span>较上月增长 8%</span>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-header">
                                <h3 class="stat-title">管理员数</h3>
                                <div class="stat-icon bg-danger">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                            <div class="stat-value" id="totalAdmin"><?php echo htmlentities($totaladmin ?? ''); ?></div>
                            <div class="stat-change positive">
                                <i class="fa fa-arrow-up"></i>
                                <span>较上月增长 2%</span>
                            </div>
                        </div>
                    </div>

                    <!-- 图表区域 -->
                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3 class="chart-title">用户增长趋势</h3>
                            <div id="userGrowthChart" class="chart-container"></div>
                        </div>

                        <div class="chart-card">
                            <h3 class="chart-title">登录统计</h3>
                            <div id="loginChart" class="chart-container"></div>
                        </div>
                    </div>

                    <!-- 详细统计 -->
                    <div class="details-grid">
                        <div class="detail-card">
                            <div class="detail-header">
                                <h3 class="detail-title">今日统计</h3>
                                <span class="detail-badge badge-primary">实时</span>
                            </div>
                            <div class="detail-row">
                                <span>用户注册</span>
                                <span id="todaySignups"><?php echo htmlentities($todayusersignup ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>用户登录</span>
                                <span id="todayLogins"><?php echo htmlentities($todayuserlogin ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>活跃用户</span>
                                <span>2,847</span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-header">
                                <h3 class="detail-title">最近统计</h3>
                                <span class="detail-badge badge-primary">实时</span>
                            </div>
                            <div class="detail-row">
                                <span>3日新增用户</span>
                                <span id="threeDnu"><?php echo htmlentities($threednu ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>7日新增用户</span>
                                <span id="sevenDnu"><?php echo htmlentities($sevendnu ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>7日活跃用户</span>
                                <span id="sevenDau"><?php echo htmlentities($sevendau ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>30日活跃用户</span>
                                <span id="thirtyDau"><?php echo htmlentities($thirtydau ?? ''); ?></span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-header">
                                <h3 class="detail-title">运行插件</h3>
                                <span class="detail-badge badge-primary">实时</span>
                            </div>
                            <div class="detail-content">
                                <div class="detail-value"><?php echo htmlentities($totalworkingaddon ?? ''); ?></div>
                                <div class="detail-label">运行中插件数量</div>
                            </div>
                            <div class="detail-row">
                                <span>总插件数</span>
                                <span id="detailTotalAddon"><?php echo htmlentities($totaladdon ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>运行率</span>
                                <span><strong>98%</strong></span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-header">
                                <h3 class="detail-title">数据库统计</h3>
                                <span class="detail-badge badge-primary">实时</span>
                            </div>
                            <div class="detail-row">
                                <span>数据表数量</span>
                                <span id="dbTables"><?php echo htmlentities($dbtablenums ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>数据库大小</span>
                                <span id="dbSize"><?php echo format_bytes($dbsize,'',0); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>增长率</span>
                                <span class="stat-change positive">+5.2%</span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-header">
                                <h3 class="detail-title">附件统计</h3>
                                <span class="detail-badge badge-primary">实时</span>
                            </div>
                            <div class="detail-row">
                                <span>附件数量</span>
                                <span id="attachmentCount"><?php echo htmlentities($attachmentnums ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>附件大小</span>
                                <span id="attachmentSize"><?php echo format_bytes($attachmentsize,'',0); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>增长率</span>
                                <span class="stat-change positive">+8.7%</span>
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="detail-header">
                                <h3 class="detail-title">图片统计</h3>
                                <span class="detail-badge badge-primary">实时</span>
                            </div>
                            <div class="detail-row">
                                <span>图片数量</span>
                                <span id="pictureCount"><?php echo htmlentities($picturenums ?? ''); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>图片大小</span>
                                <span id="pictureSize"><?php echo format_bytes($picturesize,'',0); ?></span>
                            </div>
                            <div class="detail-row">
                                <span>增长率</span>
                                <span class="stat-change positive">+12.3%</span>
                            </div>
                        </div>
                    </div>

                    <!-- 图表区域 -->
                    <div class="charts-grid">
                        <div class="chart-card">
                            <h3 class="chart-title">插件使用分布</h3>
                            <div id="addonDistributionChart" class="chart-container"></div>
                        </div>

                        <div class="chart-card">
                            <h3 class="chart-title">存储使用情况</h3>
                            <div id="storageChart" class="chart-container"></div>
                        </div>
                    </div>

                    <!-- 最近活动 -->
                    <div class="activity-card">
                        <h3 class="activity-title">最近活动</h3>
                        <ul class="activity-list">
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fa fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">新用户 <strong>张三</strong> 注册成功</div>
                                    <div class="activity-time">5分钟前</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fa fa-magic"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">插件 <strong>内容管理</strong> 已更新</div>
                                    <div class="activity-time">15分钟前</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fa fa-file-upload"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">用户 <strong>李四</strong> 上传了新附件</div>
                                    <div class="activity-time">30分钟前</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">管理员 <strong>王五</strong> 登录系统</div>
                                    <div class="activity-time">1小时前</div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- 快捷操作 -->
                    <div class="activity-card" style="margin-top: 24px;">
                        <h3 class="activity-title">快捷操作</h3>
                        <div class="quick-actions">
                            <a href="#" class="quick-action-item">
                                <i class="fa fa-plus quick-action-icon"></i>
                                <span class="quick-action-label">添加用户</span>
                            </a>
                            <a href="#" class="quick-action-item">
                                <i class="fa fa-plug quick-action-icon"></i>
                                <span class="quick-action-label">管理插件</span>
                            </a>
                            <a href="#" class="quick-action-item">
                                <i class="fa fa-database quick-action-icon"></i>
                                <span class="quick-action-label">数据库管理</span>
                            </a>
                            <a href="#" class="quick-action-item">
                                <i class="fa fa-cog quick-action-icon"></i>
                                <span class="quick-action-label">系统设置</span>
                            </a>
                            <a href="#" class="quick-action-item">
                                <i class="fa fa-file-text quick-action-icon"></i>
                                <span class="quick-action-label">内容管理</span>
                            </a>
                            <a href="#" class="quick-action-item">
                                <i class="fa fa-bar-chart quick-action-icon"></i>
                                <span class="quick-action-label">数据分析</span>
                            </a>
                        </div>
                    </div>
                <!-- </div> -->
            </div>
            <div class="tab-pane fade" id="two">
                <div class="dashboard-container">
                    <div style="text-align: center; padding: 60px 20px; color: var(--text-secondary);">
                        <i class="fa fa-cog" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <h3>自定义区域</h3>
                        <p>在这里可以自定义您的控制台组件</p>
                    </div>
                </div>
           

<!-- 直接加载echarts.min.js文件 -->
<script src="/assets/js/echarts.min.js"></script>
<script>
// 设置初始刷新时间
function updateRefreshTime() {
    const now = new Date();
    const timeString = now.toLocaleString();
    document.getElementById('refreshTime').textContent = timeString;
}
// updateRefreshTime();

// 在DOM加载完成后执行
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboardCharts);
} else {
    initDashboardCharts();
}

// 定义一个函数来初始化图表
function initDashboardCharts() {
    // 确保echarts已经加载
    if (typeof echarts === 'undefined') {
        console.error('ECharts is not loaded');
        return;
    }
    
    let charts = {};

    // 初始化ECharts图表
        function initCharts() {
            // 自定义颜色主题
            const customColors = ['#5470c6', '#91cc75', '#fac858', '#ee6666', '#73c0de', '#3ba272', '#fc8452'];
            
            // 用户增长趋势图
            charts.userGrowth = echarts.init(document.getElementById('userGrowthChart'));
            charts.userGrowth.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross',
                        label: {
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            color: '#fff',
                            padding: [8, 12],
                            borderRadius: 4
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    borderColor: '#5470c6',
                    borderWidth: 1,
                    textStyle: {
                        color: '#fff'
                    },
                    formatter: '{b}<br/>{a}: {c}'
                },
                legend: {
                    data: ['新增用户', '活跃用户'],
                    top: 10,
                    textStyle: {
                        color: '#333'
                    },
                    itemGap: 20
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    top: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                    axisLine: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    axisLabel: {
                        color: '#666'
                    },
                    axisTick: {
                        show: false
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            type: 'dashed',
                            color: '#f0f0f0'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    axisLabel: {
                        color: '#666'
                    },
                    axisTick: {
                        show: false
                    }
                },
                series: [
                    {
                        name: '新增用户',
                        type: 'line',
                        stack: '总量',
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 8,
                        showSymbol: true,
                        lineStyle: {
                            width: 3,
                            color: customColors[0]
                        },
                        emphasis: {
                            focus: 'series',
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(84, 112, 198, 0.5)'
                            }
                        },
                        itemStyle: {
                            color: customColors[0],
                            borderColor: '#fff',
                            borderWidth: 2
                        },
                        areaStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0, color: 'rgba(84, 112, 198, 0.3)' // 起始颜色
                                }, {
                                    offset: 1, color: 'rgba(84, 112, 198, 0.05)' // 结束颜色
                                }]
                            }
                        },
                        data: [120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                        name: '活跃用户',
                        type: 'line',
                        stack: '总量',
                        smooth: true,
                        symbol: 'circle',
                        symbolSize: 8,
                        showSymbol: true,
                        lineStyle: {
                            width: 3,
                            color: customColors[1]
                        },
                        emphasis: {
                            focus: 'series',
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(145, 204, 117, 0.5)'
                            }
                        },
                        itemStyle: {
                            color: customColors[1],
                            borderColor: '#fff',
                            borderWidth: 2
                        },
                        areaStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0, color: 'rgba(145, 204, 117, 0.3)' // 起始颜色
                                }, {
                                    offset: 1, color: 'rgba(145, 204, 117, 0.05)' // 结束颜色
                                }]
                            }
                        },
                        data: [220, 182, 191, 234, 290, 330, 310]
                    }
                ]
            });

            // 登录统计图表
            charts.login = echarts.init(document.getElementById('loginChart'));
            charts.login.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow',
                        shadowStyle: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    borderColor: '#91cc75',
                    borderWidth: 1,
                    textStyle: {
                        color: '#fff'
                    },
                    formatter: '{b}<br/>{a}: {c}'
                },
                legend: {
                    data: ['PC端登录', '移动端登录'],
                    top: 10,
                    textStyle: {
                        color: '#333'
                    },
                    itemGap: 20
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    top: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
                    axisLine: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    axisLabel: {
                        color: '#666'
                    },
                    axisTick: {
                        show: false
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            type: 'dashed',
                            color: '#f0f0f0'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    axisLabel: {
                        color: '#666'
                    },
                    axisTick: {
                        show: false
                    }
                },
                series: [
                    {
                        name: 'PC端登录',
                        type: 'bar',
                        data: [320, 332, 301, 334, 390, 330, 320],
                        itemStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0, color: customColors[2]
                                }, {
                                    offset: 1, color: customColors[4]
                                }]
                            },
                            borderRadius: [4, 4, 0, 0]
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.2)'
                            }
                        },
                        barWidth: '35%',
                        barGap: '20%'
                    },
                    {
                        name: '移动端登录',
                        type: 'bar',
                        data: [220, 182, 191, 234, 290, 330, 310],
                        itemStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 0,
                                y2: 1,
                                colorStops: [{
                                    offset: 0, color: customColors[3]
                                }, {
                                    offset: 1, color: customColors[5]
                                }]
                            },
                            borderRadius: [4, 4, 0, 0]
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.2)'
                            }
                        },
                        barWidth: '35%'
                    }
                ]
            });

            // 插件使用分布图表
            charts.addonDistribution = echarts.init(document.getElementById('addonDistributionChart'));
            charts.addonDistribution.setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: {c} ({d}%)',
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    borderColor: '#5470c6',
                    borderWidth: 1,
                    textStyle: {
                        color: '#fff'
                    }
                },
                legend: {
                    orient: 'vertical',
                    left: 20,
                    top: 'center',
                    textStyle: {
                        color: '#333'
                    },
                    itemGap: 15,
                    formatter: '{name}'
                },
                series: [
                    {
                        name: '插件使用',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        center: ['65%', '50%'],
                        avoidLabelOverlap: false,
                        itemStyle: {
                            borderRadius: 8,
                            borderColor: '#fff',
                            borderWidth: 2,
                            color: function(params) {
                                return customColors[params.dataIndex % customColors.length];
                            }
                        },
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '20',
                                fontWeight: 'bold',
                                color: '#333'
                            },
                            itemStyle: {
                                shadowBlur: 15,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.3)'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            {value: 335, name: '插件A'},
                            {value: 310, name: '插件B'},
                            {value: 234, name: '插件C'},
                            {value: 135, name: '插件D'},
                            {value: 1548, name: '其他'}
                        ]
                    }
                ]
            });

            // 存储使用图表
            charts.storage = echarts.init(document.getElementById('storageChart'));
            charts.storage.setOption({
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow',
                        shadowStyle: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    borderColor: '#91cc75',
                    borderWidth: 1,
                    textStyle: {
                        color: '#fff'
                    },
                    formatter: '{a}: {c}'
                },
                legend: {
                    data: ['已使用', '可用'],
                    top: 10,
                    textStyle: {
                        color: '#333'
                    },
                    itemGap: 20
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    top: '15%',
                    containLabel: true
                },
                xAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            type: 'dashed',
                            color: '#f0f0f0'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    axisLabel: {
                        color: '#666'
                    },
                    axisTick: {
                        show: false
                    }
                },
                yAxis: {
                    type: 'category',
                    data: ['总存储'],
                    axisLine: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    axisLabel: {
                        color: '#666',
                        fontSize: 14
                    },
                    axisTick: {
                        show: false
                    }
                },
                series: [
                    {
                        name: '已使用',
                        type: 'bar',
                        stack: '总量',
                        label: {
                            show: true,
                            position: 'insideRight',
                            color: '#fff',
                            fontWeight: 'bold',
                            fontSize: 14
                        },
                        emphasis: {
                            focus: 'series',
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.2)'
                            }
                        },
                        data: [350],
                        itemStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 1,
                                y2: 0,
                                colorStops: [{
                                    offset: 0, color: customColors[2]
                                }, {
                                    offset: 1, color: customColors[4]
                                }]
                            },
                            borderRadius: [0, 4, 4, 0]
                        },
                        barWidth: '60%'
                    },
                    {
                        name: '可用',
                        type: 'bar',
                        stack: '总量',
                        label: {
                            show: true,
                            position: 'insideRight',
                            color: '#333',
                            fontWeight: 'bold',
                            fontSize: 14
                        },
                        emphasis: {
                            focus: 'series',
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.2)'
                            }
                        },
                        data: [650],
                        itemStyle: {
                            color: {
                                type: 'linear',
                                x: 0,
                                y: 0,
                                x2: 1,
                                y2: 0,
                                colorStops: [{
                                    offset: 0, color: customColors[1]
                                }, {
                                    offset: 1, color: customColors[6]
                                }]
                            },
                            borderRadius: [0, 4, 4, 0]
                        }
                    }
                ]
            });
        }

    // 更新图表数据
    function updateCharts() {
        // 这里可以添加实际的数据更新逻辑
        // 目前只是重新渲染图表以模拟更新
        Object.values(charts).forEach(chart => {
            chart.resize();
        });
    }

    // 模拟数据刷新
    function refreshData() {
        const refreshIcon = document.getElementById('refreshIcon');
        refreshIcon.style.display = 'inline-block';

        // 模拟网络请求延迟
        setTimeout(() => {
            // 更新统计数据（模拟）
            updateCharts();
            updateRefreshTime();
            refreshIcon.style.display = 'none';
        }, 1000);
    }

    // 初始化图表
    initCharts();
    
    // 设置定时刷新（5分钟）
    setInterval(refreshData, 300000);
    
    // 窗口大小变化时重新调整图表大小
    window.addEventListener('resize', function() {
        Object.values(charts).forEach(chart => {
            chart.resize();
        });
    });
}
</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require.min.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version'] ?? ''); ?>"></script>
<link rel="stylesheet" href="/assets/css/custom.css">
    </body>
</html>
