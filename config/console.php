<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');

$params = require(__DIR__ . '/params.php');
$db = $params['db'];

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => \yii\helpers\ArrayHelper::merge([
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => isset($params["console_log"]) ? $params["console_log"] : [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
                ],
        'db' => $db,
        's3' => [
            'class' => 'frostealth\yii2\aws\s3\Service',
            'defaultAcl' => 'private',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            //'enableAutoLogin' => true,
            'enableSession' => false,
        ],
        'session' => [ // for use session in console application
            'class' => 'yii\web\Session'
        ],
            ], isset($params["components"]) ? $params["components"] : []),
    'params' => $params,
        /*
          'controllerMap' => [
          'fixture' => [ // Fixture generation command line.
          'class' => 'yii\faker\FixtureController',
          ],
          ],
         */
];

if (YII_ENV_DEV) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
      'class' => 'yii\gii\Module',
  ];
}

return $config;
