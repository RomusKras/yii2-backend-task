<?php
use yii\helpers\Html;
use app\models\OrderItem;
/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $products app\models\Product[] */
// Получаем существующие товары заказа для редактирования
$existingItems = [];
if (!$model->isNewRecord) {
    $existingItems = OrderItem::find()
        ->where(['order_id' => $model->id])
        ->all();
}
// Определяем начальный индекс
$startIndex = !empty($existingItems) ? count($existingItems) : 1;
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
    }
');
$this->registerJs('
    var itemIndex = ' . $startIndex . ';
    
    $("#add-item").click(function() {
        var template = $("#item-template").html();
        template = template.replace(/\{index\}/g, itemIndex);
        $("#order-items-container").append(template);
        itemIndex++;
        updateTotalPrice();
    });
    
    $(document).on("click", ".remove-item", function() {
        var container = $("#order-items-container");
        if (container.find(".order-item").length > 1) {
            $(this).closest(".order-item").remove();
            updateTotalPrice();
        } else {
            alert("Необходимо выбрать хотя бы один товар");
        }
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
                total += parseFloat(price) * parseInt(quantity);
            }
        });
        
        $("#total-price").text(total.toFixed(2) + " руб.");
    }
    
    // Вызываем расчет при загрузке страницы
    $(document).ready(function() {
        updateTotalPrice();
    });
');

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Товары заказа</h3>
    </div>
    <div class="panel-body">
        <div id="order-items-container">
            <?php if (!empty($existingItems)): ?>
                <?php foreach ($existingItems as $index => $item): ?>
                    <div class="order-item" data-index="<?= $index ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="OrderItem[<?= $index ?>][product_id]" class="form-control product-select">
                                    <option value="">-- Выберите товар --</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product->id ?>"
                                                data-price="<?= $product->price ?>"
                                            <?= $item->product_id == $product->id ? 'selected' : '' ?>>
                                            <?= Html::encode($product->name . ' (' . $product->price . ' руб.)') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <?= Html::input('number', "OrderItem[$index][quantity]", $item->count, [
                                    'class' => 'form-control quantity-input',
                                    'min' => 1,
                                    'placeholder' => 'Количество'
                                ]) ?>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                    <span class="glyphicon glyphicon-trash"></span> Удалить
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="order-item" data-index="0">
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
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <span class="glyphicon glyphicon-trash"></span> Удалить
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <button type="button" id="add-item" class="btn btn-success btn-sm">
            <span class="glyphicon glyphicon-plus"></span> Добавить товар
        </button>

        <div style="margin-top: 20px; font-size: 16px;">
            <strong>Общая стоимость: <span id="total-price" class="text-primary">0.00 руб.</span></strong>
        </div>
    </div>
</div>
<!-- Шаблон для новых товаров -->
<script type="text/template" id="item-template">
    <div class="order-item" data-index="{index}">
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
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <span class="glyphicon glyphicon-trash"></span> Удалить
                </button>
            </div>
        </div>
    </div>
</script>