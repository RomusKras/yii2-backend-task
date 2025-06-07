<?php

namespace app\controllers;

use app\models\Order;
use app\models\OrderItem;
use app\models\OrderSearch;
use app\models\Product;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // Доступ для админа
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    // Доступ для менеджера (только просмотр и изменение статуса)
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update'], // Только эти действия
                        'roles' => ['manager'],
                    ],
                    // Доступ для покупателя (только создание заказов)
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['customer'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new Order();
        $products = Product::find()->all();
        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->load($this->request->post())) {
                    throw new Exception('Не удалось загрузить данные заказа');
                }

                $model->user_id = Yii::$app->user->id;

                // Получаем данные о товарах из POST
                $orderItemsData = Yii::$app->request->post('OrderItem', []);

                if (empty($orderItemsData)) {
                    throw new Exception('Необходимо выбрать хотя бы один товар');
                }

                // Рассчитываем общую стоимость
                $totalPrice = 0;
                foreach ($orderItemsData as $itemData) {
                    if (!empty($itemData['product_id']) && !empty($itemData['count'])) {
                        $product = Product::findOne($itemData['product_id']);
                        if ($product) {
                            $totalPrice += $product->price * $itemData['count'];
                        }
                    }
                }

                $model->total_price = $totalPrice;

                if (!$model->save()) {
                    throw new Exception('Не удалось сохранить заказ');
                }

                // Сохраняем товары заказа
                foreach ($orderItemsData as $itemData) {
                    if (!empty($itemData['product_id']) && !empty($itemData['count'])) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $model->id;
                        $orderItem->product_id = $itemData['product_id'];
                        $orderItem->count = $itemData['count'];

                        $product = Product::findOne($itemData['product_id']);
                        $orderItem->price = $product->price;

                        if (!$orderItem->save()) {
                            throw new Exception('Не удалось сохранить товар заказа');
                        }
                    }
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Заказ успешно создан');
                return $this->redirect(['view', 'id' => $model->id]);

            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
            'products' => $products,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $products = Product::find()->all();

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->load($this->request->post())) {
                    throw new Exception('Не удалось загрузить данные заказа');
                }

                // Получаем данные о товарах из POST
                $orderItemsData = Yii::$app->request->post('OrderItem', []);

                if (empty($orderItemsData)) {
                    throw new Exception('Необходимо выбрать хотя бы один товар');
                }

                // Рассчитываем общую стоимость
                $totalPrice = 0;
                foreach ($orderItemsData as $itemData) {
                    if (!empty($itemData['product_id']) && !empty($itemData['count'])) {
                        $product = Product::findOne($itemData['product_id']);
                        if ($product) {
                            $totalPrice += $product->price * $itemData['count'];
                        }
                    }
                }

                $model->total_price = $totalPrice;

                if (!$model->save()) {
                    throw new Exception('Не удалось сохранить заказ');
                }

                // Удаляем старые товары заказа
                OrderItem::deleteAll(['order_id' => $model->id]);

                // Сохраняем новые товары заказа
                foreach ($orderItemsData as $itemData) {
                    if (!empty($itemData['product_id']) && !empty($itemData['count'])) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $model->id;
                        $orderItem->product_id = $itemData['product_id'];
                        $orderItem->count = $itemData['count'];

                        $product = Product::findOne($itemData['product_id']);
                        $orderItem->price = $product->price;

                        if (!$orderItem->save()) {
                            throw new Exception('Не удалось сохранить товар заказа');
                        }
                    }
                }

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Заказ успешно обновлен');
                return $this->redirect(['view', 'id' => $model->id]);

            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'products' => $products,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Order
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
