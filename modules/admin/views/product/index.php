<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Product[] $products */

$this->title = 'Управление товарами';
?>

<h1><?= Html::encode($this->title) ?></h1>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Цена</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product->id ?></td>
            <td><?= Html::encode($product->name) ?></td>
            <td><?= $product->price ?></td>
            <td>
                <?= Html::a('Редактировать', ['/admin/product/edit', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>