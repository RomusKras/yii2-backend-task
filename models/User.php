<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\rbac\Assignment;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_CUSTOMER = 'customer';

    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function rules(): array
    {
        return [
            [['username'], 'required'],
            ['password', 'required', 'on' => 'create'], // password обязателен только при создании
            ['password', 'string', 'min' => 6, 'max' => 255], // Делает 'password' безопасным и проверяет длину
            [['username'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['password', 'token'], 'string', 'max' => 255],
            [['role'], 'in', 'range' => array_keys(self::getRoleList())],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'email' => 'Электронная почта',
            'token' => 'Токен',
            'role' => 'Роль',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getOrders(): ActiveQuery
    {
        return $this->hasMany(Order::class, ['user_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Проверка правильности пароля.
     *
     * @param string $password Введённый пароль
     * @return bool True, если пароль совпадает
     */
    public function validatePassword(string $password): bool
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * Перед сохранением хэшируем пароль, если он был изменён.
     *
     * @param bool $insert Указывает, создаётся ли новая запись
     * @return bool
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isAttributeChanged('password') && !empty($this->password)) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    public static function getRoleList(): array
    {
        return [
            self::ROLE_ADMIN => 'Администратор', // Или 'Admin'
            self::ROLE_MANAGER => 'Менеджер',     // Или 'Manager'
            self::ROLE_CUSTOMER => 'Покупатель', // Или 'Customer'
        ];
    }

    /**
     * Возвращает присвоенные роли для данного пользователя.
     * Использует поле 'role' из модели.
     *
     * @return Assignment[]
     */
    public function getAssignments(): array
    {
        $assignments = [];
        $authManager = Yii::$app->authManager;
        // Получаем объект роли на основе значения поля 'role' модели
        $role = $authManager->getRole($this->role);
        if ($role) {
            // Создаем объект Assignment для этой роли
            $assignment = new Assignment([
                'userId' => (string)$this->getId(), // ID пользователя как строка
                'roleName' => $role->name,
                'createdAt' => time(), // Текущее время
            ]);
            $assignments[] = $assignment;
        }
        return $assignments;
    }
}
