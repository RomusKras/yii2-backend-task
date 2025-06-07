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

//$productOptions = ArrayHelper::map($products, 'id', function($product) {
//    return $product->name . ' (' . $product->price . ' руб.)';
//});
$this->registerCss('
    .order-item {
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
    }
    .order-item:hover {
        background-color: #f5f5f5;
    }
    #order-items-container {
        margin-bottom: 15px;
    }
    .panel-body {
        padding: 15px;
        margin-bottom: 30px;
    }
');
$this->registerJs('
    var itemIndex = 1;
    
    $("#add-item").click(function() {
        var template = $("#item-template").html();
        template = template.replace(/\{index\}/g, itemIndex);
        $("#order-items-container").append(template);
        itemIndex++;
        updateTotalPrice();
    });
    
    $(document).on("click", ".remove-item", function() {
        $(this).closest(".order-item").remove();
        updateTotalPrice();
    });
    
    $(document).on("change", ".product-select, .quantity-input", function() {
        updateTotalPrice();
    });
    
    function updateTotalPrice() {
        var total = 0;
        $(".order-item").each(function() {
            var productId = $(this).find(".product-select").val();
            var quantity = $(this).find(".quantity-input").val();
            
            if (productId && quantity) {
                var price = $(this).find(".product-select option:selected").data("price");
                total += price * quantity;
            }
        });
        
        $("#total-price").text(total.toFixed(2) + " руб.");
    }
');
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Товары заказа</h3>
        </div>
        <div class="panel-body">
            <div id="order-items-container">
                <div class="order-item">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="OrderItem[0][product_id]" class="form-control product-select">
                                <option value="">-- Выберите товар --</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product->id ?>" data-price="<?= $product->price ?>">
                                        <?= Html::encode($product->name . ' (' . $product->price . ' руб.)') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <?= Html::input('number', 'OrderItem[0][quantity]', 1, [
                                'class' => 'form-control quantity-input',
                                'min' => 1,
                                'placeholder' => 'Количество'
                            ]) ?>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-item">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="add-item" class="btn btn-success btn-sm">Добавить товар</button>

            <div style="margin-top: 20px; font-size: 16px;">
                <strong>Общая стоимость: <span id="total-price">0.00 руб.</span></strong>
            </div>
        </div>
    </div>

<!--    --><?php //= $form->field($model, 'total_price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<!-- Шаблон для новых товаров -->
<script type="text/template" id="item-template">
    <div class="order-item">
        <div class="row">
            <div class="col-md-6">
                <select name="OrderItem[{index}][product_id]" class="form-control product-select">
                    <option value="">-- Выберите товар --</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product->id ?>" data-price="<?= $product->price ?>">
                            <?= Html::encode($product->name . ' (' . $product->price . ' руб.)') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="OrderItem[{index}][quantity]"
                       class="form-control quantity-input"
                       min="1" value="1" placeholder="Количество">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-item">Удалить</button>
            </div>
        </div>
    </div>
</script>