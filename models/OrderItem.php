<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class OrderItem extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%order_items}}';
    }

    public function rules(): array
    {
        return [
            [['order_id', 'product_id', 'price', 'count'], 'required'],
            [['order_id', 'product_id', 'count'], 'integer'],
            [['count'], 'integer', 'min' => 1],
            [['price'], 'number', 'min' => 0],
        ];
    }

    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
