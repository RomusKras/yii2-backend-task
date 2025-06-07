<?php

use app\models\Order;
use app\models\OrderItem;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
/* @var $products app\models\Product[] */
?>
<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput([
                'maxlength' => true,
                'placeholder' => 'Введите название заказа'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'date')->textInput([
                'id' => 'datetime-field',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'YYYY-MM-DD HH:MM:SS'
            ]) ;
            // Подключаем flatpickr (легковесная альтернатива)
            $this->registerCssFile('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
            $this->registerJsFile('https://cdn.jsdelivr.net/npm/flatpickr', ['depends' => [JqueryAsset::class]]);
            $this->registerJs("
                flatpickr('#datetime-field', {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i:S',
                    time_24hr: true,
                    enableSeconds: true,
                    locale: 'ru'
                });
            ");
            ?>
        </div>
    </div>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->can('updateOrders')): ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList($model::getStatusList(), [
                    'prompt' => '-- Выберите статус --'
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <?= $this->render('_order_items', [
        'model' => $model,
        'products' => $products,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Создать заказ' : 'Сохранить изменения',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>