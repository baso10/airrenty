<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'airrenty',
    'name' => 'airrenty.com',
    'version' => '1.1',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
    ],
    'sourceLanguage' => 'en-US',
    'language' => $params['language'],
    'components' => \yii\helpers\ArrayHelper::merge([
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $params['cookieValidationKey'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
//            'class' => 'app\components\BBUser',
            'identityClass' => 'app\models\User',
//            'loginUrl' => ['user/login'],
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => isset($params["log"]) ? $params["log"] : [
                  'traceLevel' => YII_DEBUG ? 3 : 0,
                  'targets' => [
                      [
                          'class' => 'yii\log\FileTarget',
                          'levels' => ['error', 'warning'],
                      ],
                      [
                          'class' => 'yii\log\SyslogTarget',
                          'levels' => ['error', 'warning', 'info'],
                      ],
                  ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'about' => 'site/about',
                'airplanes' => 'airplane/index',
                'impressum' => 'site/legal',
            ]
        ],
        'assetManager' => isset($params["assetManager"]) ? $params["assetManager"] : [
    'class' => yii\web\AssetManager::class,
    'linkAssets' => true,
    'appendTimestamp' => true,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
        'formatter' => [
            'nullDisplay' => '',
        ],
            ], isset($params["components"]) ? $params["components"] : []),
    'params' => $params,
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        // enter optional module parameters below - only if you need to  
        // use your own export download action or custom translation 
        // message source
        // 'downloadAction' => 'gridview/export/download',
        // 'i18n' => []
        ]
    ],
];

if (YII_ENV_DEV) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
      'class' => 'yii\debug\Module',
      'allowedIPs' => ['*']
  ];
}

return $config;