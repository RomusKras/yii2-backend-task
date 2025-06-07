<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $products app\models\Product[] */

$this->title = 'Create Order';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'products' => $products,
    ]) ?>

</div>
