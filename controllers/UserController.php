<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
        if ($this->request->isPost) {
            $model->load($this->request->post());
            if ($model->save()) {
                if (!empty($model->role)) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole($model->role);
                    if ($role && !empty($model->getId())) {
                        $auth->revokeAll($model->getId());
                        $auth->assign($role, $model->getId());
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     * @throws \Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $postData = $this->request->post();
            // Проверяем, если в POST есть поле 'password' для модели 'User'
            // И его значение пустое.
            if (isset($postData['User']['password']) && empty($postData['User']['password'])) {
                // Удаляем это поле из POST-данных перед загрузкой в модель.
                // Таким образом, load() не будет видеть и пытаться установить это пустое значение.
                unset($postData['User']['password']);
            }

            if ($model->load($postData)) {
                if (!empty($model->role) && $model->isAttributeChanged('role')) {
                    $auth = Yii::$app->authManager;
                    $role = $auth->getRole($model->role);
                    if ($role) {
                        $auth->revokeAll($model->getId());
                        $auth->assign($role, $model->getId());
                    }
                }
                if ($model->save()) {
                    // Если это текущий авторизованный пользователь, обновляем его сессию (как в afterSave)
                    if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id) {
                        $model->refresh();
                        Yii::$app->user->login($model, 3600 * 24 * 30);
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            // Это гарантирует, что поле passwordInput в форме будет пустым при редактировании.
            $model->password = null;
        }
        // Если это GET запрос или POST запрос не прошел валидацию/сохранение
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
