<?php

namespace app\commands;

use app\models\User; // Подключаем модель User
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;
use yii\helpers\BaseConsole;
use yii\helpers\Console; // Для форматированного вывода в консоли

/**
 * Команда для установки или изменения пароля пользователя.
 */
class PasswordController extends Controller
{
    /**
     * Устанавливает новый пароль для пользователя по его ID.
     * Пример использования: php yii password/set <userId> <newPassword>
     *
     * @param int $userId ID пользователя.
     * @param string $newPassword Новый пароль для пользователя.
     * @return int Exit code
     * @throws Exception
     */
    public function actionSet(int $userId, string $newPassword): int
    {
        // Находим пользователя по ID
        $user = User::findOne($userId);

        if (!$user) {
            $this->stderr("Пользователь с ID {$userId} не найден.\n", BaseConsole::FG_RED);
            return ExitCode::DATAERR; // Возвращаем код ошибки
        }

        // Устанавливаем новый пароль.
        // Метод beforeSave в модели User автоматически хэширует его.
        $user->password = $newPassword;

        // Валидируем и сохраняем модель
        if ($user->save()) {
            $this->stdout("Пароль для пользователя '{$user->username}' (ID: {$userId}) успешно установлен.\n", BaseConsole::FG_GREEN);
            return ExitCode::OK; // Возвращаем код успешного выполнения
        } else {
            // Выводим ошибки валидации, если сохранение не удалось
            $this->stderr("Ошибка при сохранении пароля для пользователя '{$user->username}' (ID: {$userId}):\n", BaseConsole::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                $this->stderr(" - {$attribute}: " . implode(', ', $errors) . "\n", BaseConsole::FG_YELLOW);
            }
            return ExitCode::UNSPECIFIED_ERROR; // Возвращаем общий код ошибки
        }
    }

    /**
     * Интерактивная установка пароля (с запросом пароля через консоль).
     * Пример использования: php yii password/set-interactive <userId>
     *
     * @param int $userId ID пользователя.
     * @return int Exit code
     * @throws Exception
     */
    public function actionSetInteractive(int $userId): int
    {
        // Находим пользователя по ID
        $user = User::findOne($userId);

        if (!$user) {
            $this->stderr("Пользователь с ID {$userId} не найден.\n", BaseConsole::FG_RED);
            return ExitCode::DATAERR;
        }

        $this->stdout("Установка пароля для пользователя '{$user->username}' (ID: {$userId})\n");

        // Запрашиваем пароль интерактивно, чтобы он не отображался в истории команд
        $newPassword = $this->prompt("Введите новый пароль:", [
            'pattern' => '/^.{6,}$/', // Пример: пароль должен быть не короче 6 символов
            'error' => 'Пароль должен быть не менее 6 символов.',
            'validate' => function ($input) {
                // Дополнительная валидация, если нужна
                return true; // Или false, если не прошел валидацию
            },
            'requireInput' => true, // Пароль обязателен
        ]);

        if (empty($newPassword)) {
            $this->stderr("Пароль не был введен. Операция отменена.\n", BaseConsole::FG_YELLOW);
            return ExitCode::UNSPECIFIED_ERROR;
        }


        // Устанавливаем новый пароль и сохраняем
        $user->password = $newPassword;

        if ($user->save()) {
            $this->stdout("Пароль для пользователя '{$user->username}' (ID: {$userId}) успешно установлен.\n", BaseConsole::FG_GREEN);
            return ExitCode::OK;
        } else {
            $this->stderr("Ошибка при сохранении пароля для пользователя '{$user->username}' (ID: {$userId}):\n", BaseConsole::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                $this->stderr(" - {$attribute}: " . implode(', ', $errors) . "\n", BaseConsole::FG_YELLOW);
            }
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}