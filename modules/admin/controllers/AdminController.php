<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;

/**
 * Базовый контроллер для административной части.
 */
class AdminController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }
}