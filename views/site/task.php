<?php

/* @var $this View */

use yii\helpers\Html;
use yii\web\View;
$this->title = 'Задание';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <strong>Тестовое задание для бэкенд-программиста (PHP, Yii2, MySQL)</strong>
        <p>Цель: Оценить навыки работы с Yii2, понимание логики/правил фреймворка.</p>

        <strong>CRUD с фильтрами и авторизацией:</strong><br>
        <ul>
            <li>Создать CRUD для сущностей "Товары", "Заказы", "Пользователь" (MySQL, ActiveRecord);</li>
            <li>Реализовать фильтр поведения (RBAC) для ограничения доступа к редактированию (3 роли, администратор (Все права), менеджер(Только просмотр и изменение статуса заказа), покупатель (создать заказ));</li>
            <li>Добавить валидации и пред/пост-обработку данных (например, автоформатирование цены).</li>
        </ul>

        <strong>REST API с ЧПУ и интеграцией:</strong><br>
        <ul>
            <li>Разработать REST API (GET, POST, PUT, DELETE) для сущности "Заказы";</li>
            <li>Настроить urlManager для ЧПУ-ссылок (например, /order/123/view);</li>
            <li>Сделать синхронизацию с внешним сервисом (список товаров https://dummyjson.com/products).</li>
        </ul>

        <strong>Сервис с DI и типизацией:</strong><br>
        <ul>
            <li>Написать компонент для обработки данных от внешнего сервиса (например, парсинг JSON);</li>
            <li>Реализовать интерфейс для сервиса и внедрение через DI-контейнер;</li>
            <li>Покрыть сервис unit-тестами (PHPUnit).</li>
        </ul>

        <strong>Роутинг и контроллеры:</strong><br>
        <ul>
            <li>Создать цепочку контроллеров (например, AdminController → ProductController) для маршрута /admin/product/123/edit;</li>
            <li>Настроить роутинг через urlManager и обработку параметров;</li>
            <li>Добавить фильтр поведения для проверки прав доступа.</li>
        </ul>

        <strong>Критерии оценки:</strong><br>
        <ul>
            <li>Чистота кода, следование PSR;</li>
            <li>Корректность работы с ActiveRecord, валидациями, DI;</li>
            <li>Понимание роутинга и ЧПУ;</li>
            <li>Качество интеграции и тестов;</li>
        </ul>

        <br>
        <pre>
            JSON описание данных
            Товар
            {
                'id':"integer",
                "name":"string",
                "price":"float",
                "description":"string",
            }
            Заказ
            {
                'id':"integer",
                "name":"string",
                "date":"date",
                "status":"string",
                "items":[
                    {
                        'id':"integer",
                        "name":"string",
                        "price":"float",
                        "description":"string",
                        "count":"integer"
                    },
                    ....
                ],
                "total_price":"integer"
            }
            Пользователь
            {
                'id':"integer",
                "username":"string",
                "password":"string",
                "token":"string",
                "role":"string"
            }
        </pre><br>

        Срок выполнения: 4-6 часов.
        <br>

        Формат сдачи: Репозиторий на GitHub/GitLab с README
    </div>
</div>