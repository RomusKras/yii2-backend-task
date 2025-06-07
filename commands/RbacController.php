<?php

namespace app\commands;

use Yii;
use yii\base\Exception;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Очистка всех предыдущих данных RBAC
        $auth->removeAll();

        // Создаем роли
        $admin = $auth->createRole('admin');
        $manager = $auth->createRole('manager');
        $customer = $auth->createRole('customer');

        // Добавляем роли в систему
        $auth->add($admin);
        $auth->add($manager);
        $auth->add($customer);

        // Создаем разрешения
        $viewUsers = $auth->createPermission('viewUsers');
        $viewUsers->description = 'View users';
        $auth->add($viewUsers);

        $updateOrders = $auth->createPermission('updateOrders');
        $updateOrders->description = 'Edit orders';
        $auth->add($updateOrders);

        $viewOrders = $auth->createPermission('viewOrders');
        $viewOrders->description = 'View orders';
        $auth->add($viewOrders);

        $createOrders = $auth->createPermission('createOrders');
        $createOrders->description = 'Create orders';
        $auth->add($createOrders);

        $createUsers = $auth->createPermission('createUser');
        $createUsers->description = 'Create users';
        $auth->add($createUsers);

        // Связываем разрешения с ролями
        $auth->addChild($admin, $viewUsers);
        $auth->addChild($admin, $updateOrders);
        $auth->addChild($admin, $viewOrders);
        $auth->addChild($admin, $createOrders);
        $auth->addChild($admin, $createUsers);

        $auth->addChild($manager, $viewUsers);
        $auth->addChild($manager, $viewOrders);
        $auth->addChild($manager, $updateOrders);

        $auth->addChild($customer, $createOrders);

        echo "RBAC конфигурация теперь настроена\n";
    }

}