<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:65:"D:\share\fadmin\public/../application/admin\view\index\login.html";i:1770385778;s:55:"D:\share\fadmin\application\admin\view\common\meta.html";i:1770385778;s:57:"D:\share\fadmin\application\admin\view\common\script.html";i:1770385778;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
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

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录页面</title>
    <style>
        body {
            font-family: 'Microsoft YaHei', sans-serif;
            background-color: #f5f5f5;
            background: url("/assets/img/background1.jpg");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            background-attachment: fixed; /* 视需求调整 */
            
            /* 优化属性 */
            min-height: 100vh;
            margin: 0;
            padding: 0;
            
            /* 性能优化 */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            -ms-interpolation-mode: bicubic;
            
            /* 后备设置 */
            /* background: linear-gradient(135deg, #1588f4 0%, #e6f0f9 100%); */
            /* overflow: hidden; */
            /* display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative; */
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .background-decor {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            overflow: hidden;
            opacity: 0.7;
        }

        .background-decor::before,
        .background-decor::after,
        .background-decor .circle {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: move 10s infinite ease-in-out;
        }

        .background-decor::before {
            width: 350px;
            height: 350px;
            top: 10%;
            left: 30%;
        }

        .background-decor::after {
            width: 450px;
            height: 450px;
            bottom: 15%;
            right: 20%;
            animation-duration: 15s;
        }

        .background-decor .circle:nth-child(1) {
            width: 250px;
            height: 250px;
            top: 60%;
            left: 10%;
            animation-duration: 8s;
        }

        .background-decor .circle:nth-child(2) {
            width: 300px;
            height: 300px;
            top: 25%;
            right: 15%;
            animation-duration: 12s;
        }

        @keyframes move {
            0% {
                transform: scale(1) translateY(0);
            }
            50% {
                transform: scale(1.15) translateY(-20px);
            }
            100% {
                transform: scale(1) translateY(0);
            }
        }

        .login-container {
            position: absolute;
            top: 50%;
            right: 10%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
            z-index: 1;
        }

        .login-container h1 {
            margin-bottom: 30px;
            font-size: 30px;
            color: #333333;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 16px;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid #cccccc;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #f8f9fa;
            color: #333333;
        }

        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.4);
        }

        .login-container button {
            background-color: #4a90e2;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 25px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
            position: relative;
            overflow: hidden;
        }

        .login-container button::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .login-container button:hover::before {
            width: 300px;
            height: 300px;
        }

        .login-container button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.5);
            background: linear-gradient(135deg, #3862db 0%, #4e73df 100%);
        }

        .login-container button:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(78, 115, 223, 0.4);
        }

        .login-container p {
            margin-top: 25px;
            font-size: 14px;
            color: #718096;
        }

        .login-container p a {
            color: #4e73df;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-container p a:hover {
            color: #357ab7;
        }
    </style>
</head>
<body>
<div class="background-decor">
    <div class="circle"></div>
    <div class="circle"></div>
</div>
<form action="" method="post" id="login-form">
    <?php echo token(); ?>
<div class="login-container">
    <h1><?php echo htmlentities($site['name'] ?? ''); ?></h1>
    <input type="text" name="username" placeholder="请输入用户名" required>
    <input type="password" name="password" placeholder="请输入密码" required>
    <?php if(\think\Config::get('fastadmin.login_captcha')): ?>
    <div style="display: flex;flex-direction: row;align-items: center">
        <input type="text" name="captcha" class="" placeholder="<?php echo __('Captcha'); ?>" data-rule="<?php echo __('Captcha'); ?>:required;length(<?php echo \think\Config::get('captcha.length'); ?>)" autocomplete="off"/>
        <span class="input-group-addon" style="padding:0;border:none;cursor:pointer;width: unset">
                                    <img src="<?php echo rtrim('/', '/'); ?>/index.php?s=/captcha" width="130" height="50" onclick="this.src = '<?php echo rtrim('/', '/'); ?>/index.php?s=/captcha&r=' + Math.random();"/>
                            </span>
    </div>
    <?php endif; ?>
    <div style="width: 100%;display: flex;justify-items: start">
        <label class="inline" for="keeplogin" data-toggle="tooltip" title="<?php echo __('The duration of the session is %s hours', $keeyloginhours); ?>">
            <input type="checkbox" name="keeplogin" id="keeplogin" value="1"/>
            <?php echo __('Keep login'); ?>
        </label>
    </div>
    <button>登录</button>
    <p>   </p>
<!--    <p>技术支持@ <a href="#">仪路无忧</a></p>-->
</div>
</form>

<script src="/assets/js/require.min.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo htmlentities($site['version'] ?? ''); ?>"></script>
<link rel="stylesheet" href="/assets/css/custom.css">
</body>
</html>
