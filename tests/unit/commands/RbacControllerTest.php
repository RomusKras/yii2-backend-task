<?php

namespace unit\commands;

use app\commands\RbacController; // Тестируемый класс
use Codeception\Test\Unit;
use UnitTester;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\ExitCode; // Для проверки кодов выхода команды
use yii\console\Request; // Для эмуляции консольного запроса
use yii\rbac\PhpManager; // Или DbManager, в зависимости от конфигурации

/**
 * vendor/bin/codecept run unit commands/RbacControllerTest
 */
class RbacControllerTest extends Unit
{
    protected UnitTester $tester;

    /**
     * Подготовка перед каждым тестом.
     * @throws InvalidConfigException
     */
    protected function _before()
    {
        // Инициализируем тестовый authManager
        // Используем PhpManager для простоты в тесте, если ваш продакшн использует DbManager,
        // возможно, понадобится настроить тестовую базу данных.
        Yii::$app->set('authManager', [
            'class' => PhpManager::class,
            // Указываем временные файлы для хранения RBAC данных во время теста
            'itemFile' => '@tests/_output/rbac_items.php',
            'assignmentFile' => '@tests/_output/rbac_assignments.php',
            'ruleFile' => '@tests/_output/rbac_rules.php', // Если используете правила
        ]);

        // Убеждаемся, что временные файлы RBAC очищены перед каждым тестом
        @unlink(Yii::getAlias('@tests/_output/rbac_items.php'));
        @unlink(Yii::getAlias('@tests/_output/rbac_assignments.php'));
        @unlink(Yii::getAlias('@tests/_output/rbac_rules.php'));

        // Эмулируем консольный запрос, так как контроллер команд ожидает его
        Yii::$app->set('request', new Request());
    }

    /**
     * Очистка после каждого теста.
     */
    protected function _after()
    {
        // Восстанавливаем основной authManager приложения, если он был изменен
        // Yii::$app->set('authManager', ...); // Здесь можно вернуть продакшн AuthManager, если он был заменен в _before

        // Очищаем временные файлы RBAC
        @unlink(Yii::getAlias('@tests/_output/rbac_items.php'));
        @unlink(Yii::getAlias('@tests/_output/rbac_assignments.php'));
        @unlink(Yii::getAlias('@tests/_output/rbac_rules.php'));
    }

    /**
     * Тестируем корректную инициализацию RBAC командой actionInit.
     */
    public function testRbacInit()
    {
        // Создаем экземпляр контроллера
        $controller = new RbacController('rbac', Yii::$app);

        // Запускаем действие actionInit
        // outputCapture - флаг для захвата вывода stdout/stderr
        $result = $controller->run('init', [], true);

        // Проверяем, что команда завершилась успешно (код выхода 0)
        $this->assertEquals(ExitCode::OK, $result, 'Команда rbac/init должна завершиться с кодом OK');

        // Получаем экземпляр authManager, который использовался в тесте
        $auth = Yii::$app->authManager;

        // --- Проверяем, что роли созданы ---
        $adminRole = $auth->getRole('admin');
        $this->assertNotNull($adminRole, 'Роль "admin" должна быть создана');
        $managerRole = $auth->getRole('manager');
        $this->assertNotNull($managerRole, 'Роль "manager" должна быть создана');
        $customerRole = $auth->getRole('customer');
        $this->assertNotNull($customerRole, 'Роль "customer" должна быть создана');

        // --- Проверяем, что разрешения созданы ---
        $updateOrdersPerm = $auth->getPermission('updateOrders');
        $this->assertNotNull($updateOrdersPerm, 'Разрешение "updateOrders" должно быть создано');
        $viewOrdersPerm = $auth->getPermission('viewOrders');
        $this->assertNotNull($viewOrdersPerm, 'Разрешение "viewOrders" должно быть создано');
        $createOrdersPerm = $auth->getPermission('createOrders');
        $this->assertNotNull($createOrdersPerm, 'Разрешение "createOrders" должно быть создано');
        $createUsersPerm = $auth->getPermission('createUser');
        $this->assertNotNull($createUsersPerm, 'Разрешение "createUser" должно быть создано');


        // --- Проверяем, что разрешения правильно присвоены ролям ---

        // Администратор должен иметь все разрешения
        $this->assertTrue($auth->hasChild($adminRole, $updateOrdersPerm), 'Роль "admin" должна иметь разрешение "updateOrders"');
        $this->assertTrue($auth->hasChild($adminRole, $viewOrdersPerm), 'Роль "admin" должна иметь разрешение "viewOrders"');
        $this->assertTrue($auth->hasChild($adminRole, $createOrdersPerm), 'Роль "admin" должна иметь разрешение "createOrders"');
        $this->assertTrue($auth->hasChild($adminRole, $createUsersPerm), 'Роль "admin" должна иметь разрешение "createUser"');

        // Менеджер должен иметь viewOrders и updateOrders
        $this->assertTrue($auth->hasChild($managerRole, $viewOrdersPerm), 'Роль "manager" должна иметь разрешение "viewOrders"');
        $this->assertTrue($auth->hasChild($managerRole, $updateOrdersPerm), 'Роль "manager" должна иметь разрешение "updateOrders"');
        // У менеджера НЕ должно быть createUser
        $this->assertFalse($auth->hasChild($managerRole, $createUsersPerm), 'Роль "manager" НЕ должна иметь разрешение "createUser"');
        // У менеджера НЕ должно быть createOrders
        $this->assertFalse($auth->hasChild($managerRole, $createOrdersPerm), 'Роль "manager" НЕ должна иметь разрешение "createOrders"');


        // Покупатель должен иметь createOrders
        $this->assertTrue($auth->hasChild($customerRole, $createOrdersPerm), 'Роль "customer" должна иметь разрешение "createOrders"');
        // У покупателя НЕ должно быть updateOrders
        $this->assertFalse($auth->hasChild($customerRole, $updateOrdersPerm), 'Роль "customer" НЕ должна иметь разрешение "updateOrders"');
        // У покупателя НЕ должно быть viewOrders
        $this->assertFalse($auth->hasChild($customerRole, $viewOrdersPerm), 'Роль "customer" НЕ должна иметь разрешение "viewOrders"');
        // У покупателя НЕ должно быть createUser
        $this->assertFalse($auth->hasChild($customerRole, $createUsersPerm), 'Роль "customer" НЕ должна иметь разрешение "createUser"');
    }
}