<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'es-ES',
    'modules' => [
    'gridview' =>  [
        'class' => '\kartik\grid\Module'
    ]
    ],
    'components' => [
    'urlManager' => [
            'enablePrettyUrl' => true,
            //'showScriptName' => false,
            'rules' =>  [
                // 'bonoaempleado/<action:\w+>?<id:[^\/]*>&<ano:\d+>/*' => 'bonoaempleado/view',
                'gii'=>'gii',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',

                'dashboard' => 'site/index',

                'POST <controller:\w+>s' => '<controller>/create',
                '<controller:\w+>s' => '<controller>/index',

                'PUT <controller:\w+>/<id:\d+>'    => '<controller>/update',
                'DELETE <controller:\w+>/<id:\d+>' => '<controller>/delete',      
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
