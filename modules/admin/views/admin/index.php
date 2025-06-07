<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var string $content Содержимое, если вы используете шаблоны макетов */

$this->title = 'Административная панель'; // Заголовок страницы
$this->params['breadcrumbs'][] = $this->title; // Хлебные крошки
?>

<div class="admin-default-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Добро пожаловать в административную панель!</p>

    <p>Здесь вы можете управлять различными сущностями вашего приложения.</p>

    <div class="row">
        <div class="col-lg-4">
            <h2><a href="<?= Html::encode(Url::to(['/admin/user/index'])) ?>">Управление пользователями</a></h2>
            <p>Создание, редактирование и удаление пользователей. Назначение ролей.</p>
        </div>
        <div class="col-lg-4">
            <h2><a href="<?= Html::encode(Url::to(['/admin/product/index'])) ?>">Управление товарами</a></h2>
            <p>Добавление, изменение и удаление товаров. Синхронизация с внешним сервисом.</p>
        </div>
        <div class="col-lg-4">
            <h2><a href="<?= Html::encode(Url::to(['/admin/order/index'])) ?>">Управление заказами</a></h2>
            <p>Просмотр, редактирование статусов и управление заказами.</p>
        </div>
    </div>

    <?php
    /*
    // Пример вывода какой-то сводной информации, если она передается из контроллера
    if (isset($statistics)) {
        echo '<h3>Статистика:</h3>';
        echo 'Всего пользователей: ' . Html::encode($statistics['totalUsers']) . '<br>';
        echo 'Всего заказов: ' . Html::encode($statistics['totalOrders']) . '<br>';
        // ...
    }
    */
    ?>

</div>