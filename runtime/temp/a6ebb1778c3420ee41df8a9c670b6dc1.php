<?php if (!defined('THINK_PATH')) exit(); /*a:7:{s:69:"/www/wwwroot/fadmin/public/../application/admin/view/index/index.html";i:1765360515;s:59:"/www/wwwroot/fadmin/application/admin/view/common/meta.html";i:1764946205;s:61:"/www/wwwroot/fadmin/application/admin/view/common/header.html";i:1769820754;s:59:"/www/wwwroot/fadmin/application/admin/view/common/menu.html";i:1764945966;s:62:"/www/wwwroot/fadmin/application/admin/view/common/control.html";i:1764855456;s:61:"/www/wwwroot/fadmin/application/admin/view/common/script.html";i:1764949863;s:61:"/www/wwwroot/fadmin/application/admin/view/index/message.html";i:1765422280;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <!-- 加载样式及META信息 -->
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
    <body class="hold-transition <?php echo (\think\Config::get('fastadmin.adminskin') ?: 'skin-black-blue'); ?> sidebar-mini <?php echo \think\Cookie::get('sidebar_collapse')?'sidebar-collapse':''; ?> fixed <?php echo \think\Config::get('fastadmin.multipletab')?'multipletab':''; ?> <?php echo \think\Config::get('fastadmin.multiplenav')?'multiplenav':''; ?>" id="tabs">

        <div class="wrapper">

            <!-- 头部区域 -->
            <header id="header" class="main-header">
                <?php if(preg_match('/\/admin\/|\/admin\.php|\/admin_d75KABNWt\.php/i', url())): ?>
                <div class="alert alert-danger-light text-center" style="margin-bottom:0;border:none;">
                    <?php echo __('Security tips'); ?>
                </div>
                <?php endif; ?>

                <!-- Logo -->
<a href="/jnBIzEyaWQ.php" class="logo">
    <!-- 迷你模式下Logo的大小为50X50 -->
    <span class="logo-mini"  style="color: #2080f0;"><?php echo htmlentities(mb_strtoupper(mb_substr($site['name'] ?? '',0,4,'utf-8') ?? '','utf-8') ?? ''); ?></span>
    <!-- 普通模式下Logo -->
    <span class="logo-lg" style="color: #2080f0;"><?php echo htmlentities($site['name'] ?? ''); ?></span>
</a>

<!-- 顶部通栏样式 -->
<nav class="navbar navbar-static-top">
    <style>
        /* 通知角标样式，支持较大的数字 */
        .notification-link {
            position: relative;
            display: inline-block;
        }
        .navbar-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 16px;
            height: 16px;
            line-height: 16px !important;
            text-align: center;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 100;
            background-color: red;
            color: #fff;
            white-space: nowrap;
            padding: 0 !important;
        }
    </style>

    <!--第一级菜单-->
    <div id="firstnav">
        <!-- 边栏切换按钮-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?php echo __('Toggle navigation'); ?></span>
        </a>

        <!--如果不想在顶部显示角标,则给ul加上disable-top-badge类即可-->
        <ul class="nav nav-tabs nav-addtabs disable-top-badge hidden-xs" role="tablist">
            <?php echo $navlist; ?>
        </ul>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- <li class="hidden-xs">
                    <a href="<?php echo url('screen/index3'); ?>" target="_blank"><i class="fa fa-home" style="font-size:14px;"></i> <?php echo __('大屏'); ?></a>
                </li> -->

                <!-- 清除缓存 -->
                <li class="hidden-xs">
                    <a href="javascript:;" data-toggle="dropdown" title="<?php echo __('Wipe cache'); ?>">
                        <i class="fa fa-trash"></i> <?php echo __('Wipe cache'); ?>
                    </a>
                    <ul class="dropdown-menu wipecache">
                        <li><a href="javascript:;" data-type="all"><i class="fa fa-trash fa-fw"></i> <?php echo __('Wipe all cache'); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:;" data-type="content"><i class="fa fa-file-text fa-fw"></i> <?php echo __('Wipe content cache'); ?></a></li>
                        <li><a href="javascript:;" data-type="template"><i class="fa fa-file-image-o fa-fw"></i> <?php echo __('Wipe template cache'); ?></a></li>
                        <li><a href="javascript:;" data-type="addons"><i class="fa fa-rocket fa-fw"></i> <?php echo __('Wipe addons cache'); ?></a></li>
                        <li><a href="javascript:;" data-type="browser"><i class="fa fa-chrome fa-fw"></i> <?php echo __('Wipe browser cache'); ?>
                            <span data-toggle="tooltip" data-title="<?php echo __('Wipe browser cache tips'); ?>"><i class="fa fa-info-circle"></i></span>
                        </a></li>
                    </ul>
                </li>

                <!-- 多语言列表 -->
                <?php if(\think\Config::get('lang_switch_on')): ?>
                <li class="hidden-xs">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-language"></i></a>
                    <ul class="dropdown-menu">
                        <li class="<?php echo $config['language']=='zh-cn'?'active':''; ?>">
                            <a href="?ref=addtabs&lang=zh-cn">简体中文</a>
                        </li>
                        <li class="<?php echo $config['language']=='en'?'active':''; ?>">
                            <a href="?ref=addtabs&lang=en">English</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- 全屏按钮 -->
                <li class="hidden-xs">
                    <a href="#" data-toggle="fullscreen"><i class="fa fa-arrows-alt"></i></a>
                </li>

                <!-- 消息通知 -->
                <li class="hidden-xs">
                    <a href="<?php echo url('/message_log'); ?>?ref=addtabs" class="notification-link">
                        <i class="fa fa-bell-o"></i>
                        <?php if($message_count > 0): ?>
                        <span class="label label-danger navbar-badge">
                            <?php echo $message_count > 99 ? '99+' : $message_count; ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- 账号信息下拉框 -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo htmlentities(cdnurl($admin['avatar'] ?? '') ?? ''); ?>" class="user-image" alt="">
                        <span class="hidden-xs"><?php echo htmlentities($admin['nickname'] ?? ''); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo htmlentities(cdnurl($admin['avatar'] ?? '') ?? ''); ?>" class="img-circle" alt="">

                            <p>
                                <?php echo htmlentities($admin['nickname'] ?? ''); ?>
                                <small><?php echo date("Y-m-d H:i:s",$admin['logintime']); ?></small>
                            </p>
                        </li>
                        <li class="user-body">
                            <div class="visible-xs">
                                <div class="pull-left">
                                    <a href="/" target="_blank"><i class="fa fa-home" style="font-size:14px;"></i> <?php echo __('Home'); ?></a>
                                </div>
                                <div class="pull-right">
                                    <a href="javascript:;" data-type="all" class="wipecache"><i class="fa fa-trash fa-fw"></i> <?php echo __('Wipe all cache'); ?></a>
                                </div>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="general/profile" class="btn btn-primary addtabsit"><i class="fa fa-user"></i>
                                    <?php echo __('Profile'); ?></a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo url('index/logout'); ?>" id="logout-btn" class="btn btn-danger"><i class="fa fa-sign-out"></i>
                                    <?php echo __('Logout'); ?></a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- 控制栏切换按钮 -->
                <li class="hidden-xs">
                    <a href="javascript:;" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </div>

    <?php if(\think\Config::get('fastadmin.multiplenav')): ?>
    <!--第二级菜单,只有在multiplenav开启时才显示-->
    <div id="secondnav">
        <ul class="nav nav-tabs nav-addtabs disable-top-badge" role="tablist">
            <?php if($fixedmenu): ?>
            <li role="presentation" id="tab_<?php echo htmlentities($fixedmenu['id'] ?? ''); ?>" class="<?php echo $referermenu?'':'active'; ?>"><a href="#con_<?php echo htmlentities($fixedmenu['id'] ?? ''); ?>" node-id="<?php echo htmlentities($fixedmenu['id'] ?? ''); ?>" aria-controls="<?php echo htmlentities($fixedmenu['id'] ?? ''); ?>" role="tab" data-toggle="tab"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo htmlentities($fixedmenu['title'] ?? ''); ?></span> <span class="pull-right-container"> </span></a></li>
            <?php endif; if($referermenu): ?>
            <li role="presentation" id="tab_<?php echo htmlentities($referermenu['id'] ?? ''); ?>" class="active"><a href="#con_<?php echo htmlentities($referermenu['id'] ?? ''); ?>" node-id="<?php echo htmlentities($referermenu['id'] ?? ''); ?>" aria-controls="<?php echo htmlentities($referermenu['id'] ?? ''); ?>" role="tab" data-toggle="tab"><i class="fa fa-list fa-fw"></i> <span><?php echo htmlentities($referermenu['title'] ?? ''); ?></span> <span class="pull-right-container"> </span></a> <i class="close-tab fa fa-remove"></i></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</nav>

            </header>

            <!-- 左侧菜单栏 -->
            <aside class="main-sidebar">
                <!-- 左侧菜单栏 -->
<section class="sidebar">
    <!-- 管理员信息 -->
    <div class="user-panel hidden-xs">
        <div class="pull-left image">
            <a href="general/profile" class="addtabsit"><img src="<?php echo htmlentities(cdnurl($admin['avatar'] ?? '') ?? ''); ?>" class="img-circle" /></a>
        </div>
        <div class="pull-left info">
            <p><?php echo htmlentities($admin['nickname'] ?? ''); ?></p>
            <i class="fa fa-circle text-success"></i> <?php echo __('Online'); ?>
        </div>
    </div>

    <!-- 菜单搜索 -->
    <form action="" method="get" class="sidebar-form" onsubmit="return false;">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="<?php echo __('Search menu'); ?>">
            <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
            </span>
            <div class="menuresult list-group sidebar-form hide">
            </div>
        </div>
    </form>

    <!-- 移动端一级菜单 -->
    <div class="mobilenav visible-xs">

    </div>

    <!-- 左侧菜单栏 -->
    <ul class="sidebar-menu <?php if(\think\Config::get('fastadmin.show_submenu')): ?>show-submenu<?php endif; ?>">

        <!-- 菜单可以在 后台管理->权限管理->菜单规则 中进行增删改排序 -->
        <?php echo $menulist; ?>

    </ul>
</section>

            </aside>

            <!-- 主体内容区域 -->
            <div class="content-wrapper tab-content tab-addtabs">
                <?php if($fixedmenu): ?>
                <div role="tabpanel" class="tab-pane <?php echo $referermenu?'':'active'; ?>" id="con_<?php echo htmlentities($fixedmenu['id'] ?? ''); ?>">
                    <iframe src="<?php echo htmlentities($fixedmenu['url'] ?? ''); ?><?php echo stripos($fixedmenu['url'], '?') !== false ? '&' : '?'; ?>addtabs=1" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <?php endif; if($referermenu): ?>
                <div role="tabpanel" class="tab-pane active" id="con_<?php echo htmlentities($referermenu['id'] ?? ''); ?>">
                    <iframe src="<?php echo htmlentities($referermenu['url'] ?? ''); ?><?php echo stripos($referermenu['url'], '?') !== false ? '&' : '?'; ?>addtabs=1" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <?php endif; ?>
            </div>

            <!-- 底部链接,默认隐藏 -->
            <footer class="main-footer hide">
                <div class="pull-right hidden-xs">
                </div>
                <strong>Copyright &copy; 2017-<?php echo date("Y"); ?> <a href="/"><?php echo htmlentities($site['name'] ?? ''); ?></a>.</strong> All rights reserved.
            </footer>

            <!-- 右侧控制栏 -->
            <div class="control-sidebar-bg"></div>
            <style>
    .skin-list li{
        float:left; width: 33.33333%; padding: 5px;
    }
    .skin-list li a{
        display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4);
    }
    .skin-list li a span{
        display: block;
        float:left;
    }
    .skin-list li.active a {
        opacity: 1;
        filter: alpha(opacity=100);
    }
    .skin-list li.active p {
        color: #fff;
    }
</style>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-setting-tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-wrench"></i></a></li>
        <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-setting-tab">
            <h4 class="control-sidebar-heading"><?php echo __('Layout Options'); ?></h4>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-config="multiplenav" <?php if(\think\Config::get('fastadmin.multiplenav')): ?>checked<?php endif; ?> class="pull-right"> <?php echo __('Multiple Nav'); ?></label><p><?php echo __("Toggle the top menu state (multiple or single)"); ?></p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-config="multipletab" <?php if(\think\Config::get('fastadmin.multipletab')): ?>checked<?php endif; ?> class="pull-right"> <?php echo __('Multiple Tab'); ?></label><p><?php echo __("Always show multiple tab when multiple nav is set"); ?></p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="sidebar-collapse" class="pull-right"> <?php echo __('Toggle Sidebar'); ?></label><p><?php echo __("Toggle the left sidebar's state (open or collapse)"); ?></p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-enable="expandOnHover" class="pull-right"> <?php echo __('Sidebar Expand on Hover'); ?></label><p><?php echo __('Let the sidebar mini expand on hover'); ?></p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-menu="show-submenu" class="pull-right"> <?php echo __('Show sub menu'); ?></label><p><?php echo __('Always show sub menu'); ?></p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-controlsidebar="control-sidebar-open" class="pull-right"> <?php echo __('Toggle Right Sidebar Slide'); ?></label><p><?php echo __('Toggle between slide over content and push content effects'); ?></p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-sidebarskin="toggle" class="pull-right"> <?php echo __('Toggle Right Sidebar Skin'); ?></label><p><?php echo __('Toggle between dark and light skins for the right sidebar'); ?></p></div>
            <h4 class="control-sidebar-heading"><?php echo __('Skins'); ?></h4>
            <ul class="list-unstyled clearfix skin-list">
                <li><a href="javascript:;" data-skin="skin-blue" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #4e73df;"></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Blue</p></li>
                <li><a href="javascript:;" data-skin="skin-black" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black</p></li>
                <li><a href="javascript:;" data-skin="skin-purple" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #605ca8;"></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Purple</p></li>
                <li><a href="javascript:;" data-skin="skin-green" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="width: 80%; height: 7px;"></span></div><div><span style="width: 20%; height: 20px; background: #000;"></span><span style="width: 80%; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Green</p></li>
                <li><a href="javascript:;" data-skin="skin-red" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="width: 80%; height: 7px;"></span></div><div><span style="width: 20%; height: 20px; background: #000;"></span><span style="width: 80%; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Red</p></li>
                <li><a href="javascript:;" data-skin="skin-yellow" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="width: 80%; height: 7px;"></span></div><div><span style="width: 20%; height: 20px; background: #000;"></span><span style="width: 80%; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Yellow</p></li>

                <li><a href="javascript:;" data-skin="skin-blue-light" class="clearfix full-opacity-hover"><div><span style="width: 100%; height: 7px; background: #4e73df;"></span></div><div><span style="width: 100%; height: 20px; background: #f9fafc;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Blue Light</p></li>
                <li><a href="javascript:;" data-skin="skin-black-light" class="clearfix full-opacity-hover"><div><span style="width: 100%; height: 7px; background: #000;"></span></div><div><span style="width: 100%; height: 20px; background: #f9fafc;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Black Light</p></li>
                <li><a href="javascript:;" data-skin="skin-purple-light" class="clearfix full-opacity-hover"><div><span style="width: 100%; height: 7px; background: #605ca8;"></span></div><div><span style="width: 100%; height: 20px; background: #f9fafc;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Purple Light</p></li>
                <li><a href="javascript:;" data-skin="skin-green-light" class="clearfix full-opacity-hover"><div><span style="width: 100%; height: 7px;" class="bg-green"></span></div><div><span style="width: 100%; height: 20px; background: #f9fafc;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Green Light</p></li>
                <li><a href="javascript:;" data-skin="skin-red-light" class="clearfix full-opacity-hover"><div><span style="width: 100%; height: 7px;" class="bg-red"></span></div><div><span style="width: 100%; height: 20px; background: #f9fafc;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Red Light</p></li>
                <li><a href="javascript:;" data-skin="skin-yellow-light" class="clearfix full-opacity-hover"><div><span style="width: 100%; height: 7px;" class="bg-yellow"></span></div><div><span style="width: 100%; height: 20px; background: #f9fafc;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Yellow Light</p></li>

                <li><a href="javascript:;" data-skin="skin-black-blue" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"><span style="width: 100%; height: 3px; margin-top:10px; background: #4e73df;"></span></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black Blue</p></li>
                <li><a href="javascript:;" data-skin="skin-black-purple" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"><span style="width: 100%; height: 3px; margin-top:10px; background: #605ca8;"></span></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black Purple</p></li>
                <li><a href="javascript:;" data-skin="skin-black-green" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"><span style="width: 100%; height: 3px; margin-top:10px;" class="bg-green"></span></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black Green</p></li>
                <li><a href="javascript:;" data-skin="skin-black-red" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"><span style="width: 100%; height: 3px; margin-top:10px;" class="bg-red"></span></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black Red</p></li>
                <li><a href="javascript:;" data-skin="skin-black-yellow" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"><span style="width: 100%; height: 3px; margin-top:10px;" class="bg-yellow"></span></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black Yellow</p></li>
                <li><a href="javascript:;" data-skin="skin-black-pink" class="clearfix full-opacity-hover"><div><span style="width: 20%; height: 27px; background: #000;"><span style="width: 100%; height: 3px; margin-top:10px; background: #f5549f;"></span></span><span style="width: 80%; height: 27px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Black Pink</p></li>
            </ul>
        </div>
        <!-- /.tab-pane -->
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">
            <h4 class="control-sidebar-heading"><?php echo __('Home'); ?></h4>
        </div>
        <!-- /.tab-pane -->
        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <h4 class="control-sidebar-heading"><?php echo __('Setting'); ?></h4>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->

        </div>

        <!-- 加载JS脚本 -->
        <script src="/assets/js/require.min.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version'] ?? ''); ?>"></script>
<link rel="stylesheet" href="/assets/css/custom.css">
        <style>
    /* 消息通知容器 */
    .notification-container {
        position: fixed;
        top: 50px;
        right: 20px;
        width: 380px;
        max-width: calc(100vw - 40px);
        z-index: 9999;
        pointer-events: none;
    }

    /* 消息卡片 */
    .notification-card {
        position: relative;
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        overflow: hidden;
        border-left: 5px solid #667eea;
    }

    /* 卡片进入动画 - 从右侧滑入 */
    .notification-enter {
        transform: translateX(100%) translateY(0);
        opacity: 0;
    }

    .notification-enter-active {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }

    /* 卡片离开动画 - 向上滑出 */
    .notification-exit {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }

    .notification-exit-active {
        transform: translateX(0) translateY(-100%);
        opacity: 0;
    }

    /* 卡片类型样式 */
    .notification-success {
        border-left-color: #10b981;
        background: linear-gradient(90deg, #f0fdf4 0%, white 20%);
    }

    .notification-warning {
        border-left-color: #f59e0b;
        background: linear-gradient(90deg, #fffbeb 0%, white 20%);
    }

    .notification-error {
        border-left-color: #ef4444;
        background: linear-gradient(90deg, #fef2f2 0%, white 20%);
    }

    .notification-info {
        border-left-color: #3b82f6;
        background: linear-gradient(90deg, #eff6ff 0%, white 20%);
    }

    /* 卡片头部 */
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .notification-title {
        font-weight: 600;
        font-size: 16px;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .notification-icon {
        font-size: 18px;
    }

    .notification-close {
        background: none;
        border: none;
        color: #9ca3af;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .notification-close:hover {
        background: #f3f4f6;
        color: #374151;
    }

    /* 卡片内容 */
    .notification-content {
        color: #4b5563;
        line-height: 1.5;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .notification-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #9ca3af;
    }

    .notification-time {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .notification-sender {
        font-weight: 500;
    }

    /* 进度条 */
    .notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #e5e7eb;
        overflow: hidden;
    }

    .notification-progress-bar {
        height: 100%;
        background: #667eea;
        width: 100%;
        transform-origin: left;
        transition: transform 0.1s linear;
    }

    /* 消息计数 */
    .notification-count {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #ef4444;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        z-index: 10000;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    /* 响应式设计 */
    @media (max-width: 768px) {
        .notification-container {
            width: calc(100vw - 40px);
            right: 20px;
            left: 20px;
        }

        .notification-card {
            padding: 16px;
        }
    }
</style>
<!-- 消息通知容器 -->
<div class="notification-container" id="notificationContainer"></div>
<script>
    class NotificationManager {
        constructor() {
            this.container = document.getElementById('notificationContainer');
            this.notifications = [];
            this.maxVisible = 5; // 最大同时显示数量
            this.visibleCount = 0;
            this.idCounter = 0;

            // 初始化容器
            this.initContainer();
        }

        initContainer() {
            // 确保容器存在
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.className = 'notification-container';
                document.body.appendChild(this.container);
            }

            // 监听窗口变化
            window.addEventListener('resize', () => this.updatePositions());
        }

        /**
         * 显示新消息
         * @param {Object} options 消息配置
         * @param {string} options.title 标题
         * @param {string} options.content 内容
         * @param {string} options.type 类型: info|success|warning|error
         * @param {string} options.sender 发送者
         * @param {number} options.duration 显示时长(ms)，默认30000
         * @param {Function} options.onClose 关闭回调
         * @returns {string} 消息ID
         */
        show(options) {
            const id = `notification_${Date.now()}_${++this.idCounter}`;

            const notification = {
                id,
                title: options.title || '新消息',
                content: options.content || '您有一条新消息',
                type: options.type || 'info',
                sender: options.sender || '系统',
                duration: options.duration || 30000, // 30秒
                createdAt: Date.now(),
                onClose: options.onClose,
                element: null,
                timer: null,
                progressTimer: null,
                remainingTime: options.duration || 30000
            };

            // 添加到队列
            this.notifications.unshift(notification);

            // 创建卡片元素
            this.createNotificationElement(notification);

            // 安排显示
            this.scheduleNotification(notification);

            // 更新布局
            this.updatePositions();

            return id;
        }

        /**
         * 创建消息卡片元素
         */
        createNotificationElement(notification) {
            const element = document.createElement('div');
            element.className = `notification-card notification-${notification.type} notification-enter`;
            element.dataset.id = notification.id;

            // 图标映射
            const icons = {
                info: 'ℹ️',
                success: '✅',
                warning: '⚠️',
                error: '❌'
            };

            // 计算剩余时间百分比
            const progressPercent = (notification.remainingTime / notification.duration) * 100;

            element.innerHTML = `
                    <div class="notification-header">
                        <div class="notification-title">
                            <span class="notification-icon">${icons[notification.type] || '📢'}</span>
                            ${notification.title}
                        </div>
                        <button class="notification-close" onclick="notificationManager.closeNotification('${notification.id}')">
                            ×
                        </button>
                    </div>
                    <div class="notification-content">
                        ${notification.content}
                    </div>
                    <div class="notification-meta">
                        <div class="notification-sender">来自: ${notification.sender}</div>
                        <div class="notification-time">
                            <span>${Math.ceil(notification.remainingTime / 1000)}秒后消失</span>
                        </div>
                    </div>
                    <div class="notification-progress">
                        <div class="notification-progress-bar" style="transform: scaleX(${progressPercent / 100})"></div>
                    </div>
                `;

            notification.element = element;

            // 添加到容器
            this.container.appendChild(element);

            // 触发进入动画
            setTimeout(() => {
                element.classList.remove('notification-enter');
                element.classList.add('notification-enter-active');
            }, 10);

            // 开始进度条动画
            this.startProgressBar(notification);
        }

        /**
         * 开始进度条动画
         */
        startProgressBar(notification) {
            if (notification.progressTimer) {
                clearInterval(notification.progressTimer);
            }

            const progressBar = notification.element.querySelector('.notification-progress-bar');
            if (!progressBar) return;

            const startTime = Date.now();
            const duration = notification.remainingTime;

            notification.progressTimer = setInterval(() => {
                const elapsed = Date.now() - startTime;
                const remaining = Math.max(0, duration - elapsed);
                const percent = (remaining / duration) * 100;

                // 更新进度条
                progressBar.style.transform = `scaleX(${percent / 100})`;

                // 更新时间显示
                const timeElement = notification.element.querySelector('.notification-time span');
                if (timeElement) {
                    timeElement.textContent = `${Math.ceil(remaining / 1000)}秒后消失`;
                }

                // 保存剩余时间用于暂停/恢复
                notification.remainingTime = remaining;

                if (remaining <= 0) {
                    clearInterval(notification.progressTimer);
                }
            }, 100);
        }

        /**
         * 安排消息显示
         */
        scheduleNotification(notification) {
            // 设置自动关闭定时器
            notification.timer = setTimeout(() => {
                this.closeNotification(notification.id);
            }, notification.remainingTime);
        }

        /**
         * 关闭指定消息
         * @param {string} id 消息ID
         */
        closeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index === -1) return;

            const notification = this.notifications[index];

            // 清理定时器
            if (notification.timer) {
                clearTimeout(notification.timer);
            }
            if (notification.progressTimer) {
                clearInterval(notification.progressTimer);
            }

            // 触发离开动画
            if (notification.element) {
                notification.element.classList.remove('notification-enter-active');
                notification.element.classList.add('notification-exit');
                notification.element.classList.add('notification-exit-active');

                // 动画完成后移除元素
                setTimeout(() => {
                    if (notification.element && notification.element.parentNode) {
                        notification.element.parentNode.removeChild(notification.element);
                    }
                }, 500);
            }

            // 从数组中移除
            this.notifications.splice(index, 1);

            // 触发关闭回调
            if (notification.onClose) {
                notification.onClose();
            }

            // 更新布局
            setTimeout(() => this.updatePositions(), 500);
        }

        /**
         * 清空所有消息
         */
        clearAll() {
            // 从后往前关闭，避免数组索引变化
            for (let i = this.notifications.length - 1; i >= 0; i--) {
                this.closeNotification(this.notifications[i].id);
            }
        }

        /**
         * 更新卡片位置（堆叠效果）
         */
        updatePositions() {
            const cards = Array.from(this.container.children);
            const spacing = 15; // 卡片间距
            const maxCards = this.maxVisible;

            // 为每张卡片计算位置
            cards.forEach((card, index) => {
                if (index >= maxCards) {
                    // 超出最大显示数量的卡片直接隐藏
                    card.style.display = 'none';
                    return;
                }

                card.style.display = 'block';

                // 计算偏移量
                let offsetTop = index * (card.offsetHeight + spacing);

                // 应用平滑过渡
                card.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                // card.style.transform = `translateY(${offsetTop}px)`;

                // 更新z-index，最新的在最上面
                card.style.zIndex = 1000 - index;

                // 如果卡片太多，顶部的卡片会逐渐变透明
                // if (cards.length > maxCards) {
                //     const opacity = Math.max(0, 1 - (cards.length - maxCards) * 0.1);
                //     card.style.opacity = opacity;
                // } else {
                //     card.style.opacity = 1;
                // }
            });
        }

        /**
         * 暂停消息计时（当鼠标悬停时）
         */
        pauseNotification(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (!notification) return;

            if (notification.timer) {
                clearTimeout(notification.timer);
                notification.timer = null;
            }

            if (notification.progressTimer) {
                clearInterval(notification.progressTimer);
                notification.progressTimer = null;
            }
        }

        /**
         * 恢复消息计时（当鼠标离开时）
         */
        resumeNotification(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (!notification) return;

            // 重新安排定时器
            notification.timer = setTimeout(() => {
                this.closeNotification(id);
            }, notification.remainingTime);

            // 重新开始进度条
            this.startProgressBar(notification);
        }

        /**
         * 获取当前消息数量
         */
        getCount() {
            return this.notifications.length;
        }
    }

    // 初始化消息管理器
    const notificationManager = new NotificationManager();

    // 暴露到全局
    window.notificationManager = notificationManager;

    // 登录欢迎信息
    let lastlogin = localStorage.getItem("lastlogin");
    lastlogin = JSON.parse(lastlogin);
    if (lastlogin && (!lastlogin.isNotify || lastlogin.isNotify === 0)) {
        notificationManager.show({
            title: '登录成功',
            content: '欢迎您，' + lastlogin.username,
            type: 'success',
            sender: '系统消息',
            duration: 3000
        });
        lastlogin.isNotify = 1;
        window.localStorage.setItem("lastlogin", JSON.stringify(lastlogin));
        for (var i = 0; i < lastlogin.limit5.length; i++) {
            let msg = lastlogin.limit5[i];
            notificationManager.show({
                title: msg.title || '新消息',
                content: msg.content || '您有一条新消息',
                type: 'info',
                sender: '系统',
                duration: 3000
            });
        }
        console.log(lastlogin.limit5);
    }
</script>
<?php if(\think\Config::get('ws.open')): ?>
<script type="module">
    import WebSocketManager from '/assets/js/backend/websocket.js';
    // 创建 WebSocket 管理器实例
    const wsManager = new WebSocketManager({
        url: 'ws://127.0.0.1:8085',
        debug: true,
        heartbeatInterval: 30000, // 30秒心跳
        heartbeatTimeout: 10000,  // 10秒超时
        reconnectInterval: 3000,  // 3秒重试
        maxReconnectAttempts: 10  // 最多重试10次
    });

    // 事件监听
    wsManager.on('open', () => {
        updateStatus();
    });

    wsManager.on('close', (event) => {
        updateStatus();
    });

    wsManager.on('error', (error) => {
        updateStatus();
    });

    wsManager.on('message', (data) => {
        updateStatus();
        if (data.type && data.content) {
            // 显示通知
            notificationManager.show({
                title: data.title || '新消息',
                content: data.content || '您有一条新消息',
                type: data.type || 'info',
                sender: data.sender || '系统',
                duration: data.duration || 3000 // 30秒
            });
        }
        if (data.count != undefined && data.count !== '') {
            // 更新消息角标显示
            var $badge = $('.notification-link .navbar-badge');
            var $link = $('.notification-link');
            if (data.count > 0) {
                var displayCount = data.count > 99 ? '99+' : data.count;

                if ($badge.length > 0) {
                    // 更新现有角标
                    $badge.text(displayCount);
                } else {
                    // 创建新角标
                    $link.append('<span class="label label-danger navbar-badge">' + displayCount + '</span>');
                }
            } else {
                // 移除角标
                if ($badge.length > 0) {
                    $badge.remove();
                }
            }
        }
    });

    wsManager.on('reconnect', ({ attempt }) => {
    });

    wsManager.on('heartbeat', ({ type }) => {
    });

    // 工具函数
    function updateStatus() {
        const status = wsManager.getStatus();
        var className = 'text-danger';
        var statusText = ' 离线';
        if (status.isConnected) {
            className = 'text-success';
            statusText = ' 在线';
        }
        console.log(statusText);
        const indicator = document.querySelector('.main-sidebar .user-panel .info .fa-circle');
        indicator.className = 'fa fa-circle ' + className;
        indicator.nextSibling.nodeValue = statusText;
    }
    // 初始状态更新
    updateStatus();

    // 定时更新状态
    setInterval(updateStatus, 1000);
</script>
<?php endif; ?>
    </body>
</html>
