<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Команда для назначения ролей пользователям.
 * php yii role/assign-role <userId> <roleName>
 */
class RoleController extends Controller
{
    /**
     * Назначает роль пользователю.
     *
     * @param int $userId ID пользователя.
     * @param string $roleName Название роли (admin, manager, customer).
     * @return int Exit code
     */
    public function actionAssignRole(int $userId, string $roleName): int
    {
        $auth = Yii::$app->authManager;

        // Проверить, существует ли указанная роль
        $role = $auth->getRole($roleName);
        if (!$role) {
            $this->stderr("Роль '{$roleName}' не существует.\n");
            return ExitCode::DATAERR;
        }

        // Проверить, существует ли пользователь с таким ID
        $user = User::findOne($userId);
        if (!$user) {
            $this->stderr("Пользователь с ID {$userId} не найден.\n");
            return ExitCode::DATAERR;
        }

        // Назначить роль пользователю
        try {
            $auth->revokeAll($userId); // Удалить все предыдущие роли
            $auth->assign($role, $userId); // Назначить новую роль
            $this->stdout("Роль '{$roleName}' успешно назначена пользователю с ID {$userId}.\n");
            $user->role = $roleName;
            $user->update();
        } catch (\Exception $e) {
            $this->stderr("Ошибка назначения роли: {$e->getMessage()}.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        } catch (\Throwable $e) {
            $this->stderr("Ошибка назначения роли в БД: {$e->getMessage()}.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}