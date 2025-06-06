<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%products}}';
    }

    public function rules(): array
    {
        return [
            [['name', 'price'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['price'], 'number', 'min' => 0],
            [['description'], 'string'],
        ];
    }

    public function getOrderItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'id']);
    }

    /**
     * Автоформатирование цены перед сохранением (пред-обработка)
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            // Округление цены до двух знаков после запятой
            $this->price = round($this->price, 2);
            return true;
        }
        return false;
    }

    /**
     * Пост-обработка — логирование изменений (пример)
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);
        if (!$insert) {
            Yii::info("Товар #{$this->id} был обновлен. Изменения: " . json_encode($changedAttributes), __METHOD__);
        } else {
            Yii::info("Товар #{$this->id} был создан.", __METHOD__);
        }
    }

    /**
     * Пример геттеров/сеттеров для автоматизации данных (например, форматирование)
     */
    public function setPrice(float $value): void
    {
        $this->price = round($value, 2);
    }
    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2, '.', ' ') . ' ₽'; // Представим, что у нас рубли
    }
}