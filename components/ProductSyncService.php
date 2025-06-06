<?php

namespace app\components;

use app\models\Product;
use Exception;
use Yii;

class ProductSyncService
{
    private const API_URL = 'https://dummyjson.com/products';

    private ExternalDataParserInterface $dataParser;

    /**
     * Внедрение ExternalDataParserInterface через DI.
     *
     * @param ExternalDataParserInterface $dataParser
     */
    public function __construct(ExternalDataParserInterface $dataParser)
    {
        $this->dataParser = $dataParser;
    }

    /**
     * Синхронизация товаров с внешним сервисом.
     *
     * @return void
     * @throws Exception
     */
    public function syncProducts()
    {
        $apiData = $this->dataParser->fetchData(self::API_URL);
        $products = $apiData['products'] ?? [];
        foreach ($products as $productData) {
            $product = Product::findOne(['id' => $productData['id']]) ?? new Product();
            $product->id = $productData['id'];
            $product->name = $productData['title'];
            $product->price = $productData['price'];
            $product->description = $productData['description'];
            if (!$product->save()) {
                \Yii::warning('Ошибка сохранения товара: ' . json_encode($product->errors), __METHOD__);
            }
        }
    }
}