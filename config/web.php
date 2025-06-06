<?php

use app\components\ExternalDataParser;
use app\components\ExternalDataParserInterface;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5Np5BQWjQwMAwTkx8BUBSwUJPUoeQOpP',
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'authTimeout' => 3600 * 24 * 30,
            'class' => 'yii\web\User',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true, // Строгий парсинг URL
            'rules' => [
                'home' => 'site/index', // Главная страница доступна по /home
                'login' => 'site/login', // Страница входа доступна по /login
                'logout' => 'site/logout', // Выход по /logout
                'contact' => 'site/contact', // Контакты по /contact
                'about' => 'site/about', // О проекте по /about
                '<action:(error|captcha|index)>' => 'site/<action>', // Для действий без параметров
                // REST API
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'api/order',
                    'prefix' => 'api/v1',
                    'patterns' => [
                        'GET' => 'index',
                        'POST' => 'create',
                        'GET {id}' => 'view',
                        'PUT {id}' => 'update',
                        'DELETE {id}' => 'delete',
                        'GET {id}/items' => 'items',
                    ],
                    'extraPatterns' => [
                        'GET {id}/view' => 'view',   // ЧПУ для просмотра заказа по ID
                    ],
                ],
                // Pretty URLs
                'product' => 'product/index',
                'product/<id:\d+>' => 'product/view',
                'order/<id:\d+>' => 'order/view',
                'order' => 'order/index',
                'user/<id:\d+>' => 'user/view',
                'user' => 'user/index',
                'product/<action:(create|update|delete)>/<id:\d*>' => 'product/<action>',
                'order/<action:(create|update|delete)>/<id:\d*>' => 'order/<action>',
                'user/<action:(create|update|delete)>/<id:\d*>' => 'user/<action>',
                'user/<action:(create|update|delete)>/' => 'user/<action>',
                'admin/product/<id:\\d+>/edit' => 'admin/product/edit', // Маршрут для редактирования
            ],
//
//            'externalService' => [
//                'class' => 'app\services\ExternalProductService',
//            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager', // 'yii\rbac\DbManager'
            'defaultRoles' => ['customer'], //'admin', 'manager',
            'itemFile' => '@app/rbac/items.php', // Файл для ролей и разрешений
            'assignmentFile' => '@app/rbac/assignments.php', // Файл для назначений
//            'ruleFile' => '@app/rbac/rules.php', // Файл для правил (опционально)
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            'app\components\ExternalDataParserInterface' => 'app\components\ExternalDataParser',
//            'app\interfaces\ProductServiceInterface' => 'app\services\ProductService',
//            'app\interfaces\OrderServiceInterface' => 'app\services\OrderService',
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\AdminModule',
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
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
