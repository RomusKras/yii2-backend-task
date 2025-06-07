<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';

    public static function tableName(): string
    {
        return '{{%orders}}';
    }

    /**
     * Возвращает список возможных статусов заказов в виде массива для выпадающего списка или отображения.
     * Ключи - значения статусов (из констант), значения - текстовые метки.
     *
     * @return array
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'В ожидании',
            self::STATUS_COMPLETED => 'Завершен',
            self::STATUS_CANCELED => 'Отменен',
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'date', 'status', 'total_price'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => array_keys(self::getStatusList())],
            [['date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['total_price'], 'number', 'min' => 0],
        ];
    }

    /**
     * Возвращает метки (лейблы) атрибутов.
     * Этот метод используется виджетами (ActiveForm, DetailView, GridView)
     * для отображения пользовательских меток полей.
     *
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название заказа',
            'date' => 'Дата заказа',
            'status' => 'Статус заказа',
            'total_price' => 'Общая стоимость',
            'user_id' => 'Пользователь',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getOrderItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }
}