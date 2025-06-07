<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (YII_DEBUG) {
                $authManager = Yii::$app->authManager;
                $userId = Yii::$app->user->id;

                $debugHtml = '<div style="text-align: left;">';
                $debugHtml .= '<p><strong>User ID:</strong> ' . $userId . '</p>';
                $debugHtml .= '<p><strong>Username:</strong> ' . Yii::$app->user->identity->username . '</p>';

                // Роли
                $debugHtml .= '<h4 style="margin-top: 15px;">Роли:</h4>';
                $roles = $authManager->getRolesByUser($userId);
                if (!empty($roles)) {
                    foreach ($roles as $role) {
                        $debugHtml .= '<span class="badge" style="background: #5cb85c; margin: 2px;">'
                            . Html::encode($role->name) . '</span> ';
                    }
                } else {
                    $debugHtml .= '<em>Нет ролей</em>';
                }

                // Права
                $debugHtml .= '<h4 style="margin-top: 15px;">Права доступа:</h4>';
                $permissions = $authManager->getPermissionsByUser($userId);
                if (!empty($permissions)) {
                    foreach ($permissions as $permission) {
                        $debugHtml .= '<span class="badge" style="background: #337ab7; margin: 2px;">'
                            . Html::encode($permission->name) . '</span> ';
                    }
                } else {
                    $debugHtml .= '<em>Нет прав</em>';
                }

                $debugHtml .= '</div>';

                Yii::$app->session->setFlash('auth-debug-sweetalert', $debugHtml);
            }
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTask() {
        return $this->render('task');
    }
}
