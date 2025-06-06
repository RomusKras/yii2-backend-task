<?php

namespace app\modules\admin\controllers;

use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\Product;
use yii\web\NotFoundHttpException;

/**
 * Контроллер для управления товарами в админке.
 */
class ProductController extends Controller
{
    /**
     * Поведение контроллера.
     */
    public function behaviors(): array
    {
        return [
            // Ограничение доступа
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // Доступ для админа (все действия)
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    // Доступ для менеджера (просмотр, редактирование)
                    [
                        'allow' => true,
                        'actions' => ['index', 'edit'],
                        'roles' => ['manager'],
                    ],
                    // Запрет для остальных
                    [
                        'allow' => false,
                    ],
                ],
            ],
            // Ограничение HTTP-методов
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'], // Удаление только через POST
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionEdit($id)
    {
        $product = Product::findOne($id);

        if (!$product) {
            throw new NotFoundHttpException("Товар с ID {$id} не найден.");
        }

        if (\Yii::$app->request->isPost) {
            $product->load(\Yii::$app->request->post());
            if ($product->save()) {
                return $this->redirect(['/admin/product/index']);
            }
        }

        return $this->render('edit', [
            'product' => $product,
        ]);
    }

    public function actionIndex(): string
    {
        $products = Product::find()->all();

        return $this->render('index', [
            'products' => $products,
        ]);
    }
}