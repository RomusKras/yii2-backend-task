<?php

namespace app\controllers\api;

use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class OrderController extends ActiveController
{
    public $modelClass = 'app\models\Order'; // Связываем с моделью Order

    /**
     * Настройка поведения контроллера
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // Настраиваем возвращение JSON через ContentNegotiator
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        // Ограничение HTTP-методов
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['GET'],       // Список заказов
                'view' => ['GET'],        // Просмотр заказа
                'create' => ['POST'],     // Создание заказа
                'update' => ['PUT'],      // Обновление заказа
                'delete' => ['DELETE'],   // Удаление заказа
            ],
        ];

        // AccessControl для управления доступом
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'], // Только авторизованные пользователи
                ],
            ],
        ];

        return $behaviors;
    }
}