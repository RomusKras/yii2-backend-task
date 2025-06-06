<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $product */

$this->title = "Редактировать товар #{$product->id}";
?>

    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($product, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($product, 'price')->textInput(['maxlength' => true]) ?>
<?= $form->field($product, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>