<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\components\ProductSyncService;

/**
 * php yii sync/products
 */
class SyncController extends Controller
{
    private ProductSyncService $productSyncService;

    public function __construct($id, $module, ProductSyncService $productSyncService, $config = [])
    {
        $this->productSyncService = $productSyncService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Синхронизация продуктов с внешним API.
     *
     * @return int
     */
    public function actionProducts()
    {
        try {
            $this->productSyncService->syncProducts();
            $this->stdout("Продукты успешно синхронизированы.\n");
            return ExitCode::OK;
        } catch (\Exception $e) {
            $this->stderr("Ошибка синхронизации: " . $e->getMessage() . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}