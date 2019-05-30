<?php

$localParams = __DIR__ . '/params-local.php';
$localDB = __DIR__ . '/db-local.php';

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

if (file_exists($localParams)) {
    $params = array_merge(
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );
}
if (file_exists($localParams)) {
    $db = array_merge(
        require __DIR__ . '/db.php',
        require __DIR__ . '/db-local.php'
    );
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'formatter' => [
            'dateFormat' => 'Y-MM-dd',
            'timeFormat' => 'HH:mm:ss',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '-V2tqDIgWN4iQ0_Q7poua_XHQSa1BIKh',
        ],

        'errorHandler' => [
            'errorAction' => 'default/error',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'main.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'orderList' => [
            'class' => 'app\modules\orderList\OrderListModule',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
