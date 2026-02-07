<?php

return [
    'autoload' => false,
    'hooks' => [
        'response_send' => [
            'apilog',
        ],
        'module_init' => [
            'apilog',
        ],
        'view_filter' => [
            'betterform',
        ],
        'config_init' => [
            'betterform',
            'encryptpwd',
            'summernote',
        ],
        'action_begin' => [
            'encryptpwd',
        ],
        'app_init' => [
            'qrcode',
        ],
    ],
    'route' => [
        '/qrcode$' => 'qrcode/index/index',
        '/qrcode/build$' => 'qrcode/index/build',
    ],
    'priority' => [],
    'domain' => '',
];
