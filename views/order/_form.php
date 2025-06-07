<?php

use app\models\Order;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

/** @var yii\web\View $this */
/** @var app\models\Order $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

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

    <?php
    // Используем статический метод из модели User
    $statusOptions = Order::getStatusList();
    ?>
    <?= $form->field($model, 'status')->dropDownList($statusOptions, ['prompt' => 'Выберите статус']) ?>

    <?= $form->field($model, 'total_price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
