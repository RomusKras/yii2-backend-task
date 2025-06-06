<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ .'/../vendor/autoload.php';

// Регистрируем псевдоним для корневой директории тестов
Yii::setAlias('@tests', dirname(__DIR__).'/tests'); // dirname(__DIR__) - это путь к директории 'tests'
