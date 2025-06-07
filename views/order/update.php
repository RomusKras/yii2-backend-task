<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Order $model */
/* @var $products app\models\Product[] */

$this->title = 'Update Order: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заказаы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'products' => $products,
    ]) ?>

</div>
