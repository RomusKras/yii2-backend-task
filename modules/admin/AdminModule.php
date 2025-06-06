<?php

namespace app\modules\admin;

use yii\base\Module;

/**
 * Модуль Admin для всех административных действий.
 */
class AdminModule extends Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();

        // Дополнительные настройки для модуля (пока не нужно)
    }
}