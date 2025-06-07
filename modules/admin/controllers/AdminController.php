<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Базовый контроллер для административной части.
 */
class AdminController extends Controller
{
    /**
     * Поведение контроллера.
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['admin'],
                    ],
                    // Правило, которое по умолчанию запрещает доступ ко всем остальным действиям
                    // для всех, кто не соответствует предыдущим правилам.
                    [
                        'allow' => false,
                        'roles' => ['@', '?'], // Запретить всем аутентифицированным и гостям
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}