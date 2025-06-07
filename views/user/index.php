<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('createUser')): ?>
            <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            [
                'attribute' => 'password',
                'format' => 'raw', // Позволяет выводить HTML
                'value' => function ($model) {
                    if (!empty($model->getAttribute('password'))) {
                        return '<div class="digital-noise-password-cell" title="Пароль зашифрован">' .
                            '</div>';
                    }
                    return '<span style="color: #999;">Не установлен</span>';
                },
                'contentOptions' => ['style' => 'text-align: center; vertical-align: middle;'], // Опционально: центрирование
            ],
            'token',
            'role',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
        'summary' => 'Показано {begin}-{end} из {totalCount} записей',
        'emptyText' => 'Ничего не найдено',
    ]); ?>
</div>
