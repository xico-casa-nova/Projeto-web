<?php

declare(strict_types=1);

namespace backend\models;

use common\models\User;
use common\models\UserDetail;
use Yii;
use yii\base\Model;
use yii\rbac\Role;

class UserForm extends Model
{
    public ?int $userId = null;
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $fullName = '';
    public string $role = 'user';
    public string $status = '10';

    public function attributeLabels(): array
    {
        return [
            'username' => 'Nome de utilizador',
            'email' => 'Email',
            'password' => 'Palavra-passe',
            'fullName' => 'Nome completo',
            'role' => 'Perfil',
            'status' => 'Estado',
        ];
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'fullName', 'role'], 'trim'],
            [['username', 'email', 'fullName', 'role', 'status'], 'required'],
            [['username'], 'string', 'min' => 2, 'max' => 255],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username',
                'filter' => $this->userId === null ? null : ['<>', 'id', $this->userId]],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email',
                'filter' => $this->userId === null ? null : ['<>', 'id', $this->userId]],
            [['fullName'], 'string', 'max' => 255],
            [['password'], 'required', 'when' => static fn (self $model): bool => $model->userId === null],
            [['password'], 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            [['role'], 'in', 'range' => ['admin', 'gestor', 'user']],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_INACTIVE, User::STATUS_DELETED]],
        ];
    }

    public function loadUser(User $user): void
    {
        $this->userId = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->fullName = $user->userDetail->nome_completo ?? '';
        $this->status = (string) $user->status;

        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = array_key_first($roles) ?? $user->getRole();
    }

    public function save(?User $user = null): bool
    {
        if ($user === null) {
            $user = new User();
        }

        if (!$this->validate()) {
            return false;
        }

        $role = Yii::$app->authManager->getRole($this->role);
        if (!$role instanceof Role) {
            $this->addError('role', 'A role selecionada não existe no RBAC.');
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = (int) $this->status;

            if ($this->password !== '') {
                $user->setPassword($this->password);
                $user->generateAuthKey();
                if ($user->isNewRecord) {
                    $user->generateEmailVerificationToken();
                } else {
                    $user->removePasswordResetToken();
                }
            }

            if (!$user->save()) {
                foreach ($user->getErrors() as $attribute => $errors) {
                    foreach ($errors as $error) {
                        $this->addError($attribute, $error);
                    }
                }
                $transaction->rollBack();
                return false;
            }
            $this->userId = $user->id;

            $detail = $user->userDetail ?? new UserDetail();
            $detail->user_id = $user->id;
            $detail->nome_completo = $this->fullName;
            $detail->role = $this->role;
            if (!$detail->save()) {
                foreach ($detail->getErrors() as $attribute => $errors) {
                    foreach ($errors as $error) {
                        $this->addError($attribute === 'nome_completo' ? 'fullName' : $attribute, $error);
                    }
                }
                $transaction->rollBack();
                return false;
            }

            foreach (['admin', 'gestor', 'user'] as $roleName) {
                $assignment = Yii::$app->authManager->getAssignment($roleName, (string) $user->id);
                if ($roleName !== $this->role && $assignment !== null) {
                    Yii::$app->authManager->revoke(Yii::$app->authManager->getRole($roleName), (string) $user->id);
                }
            }
            if (Yii::$app->authManager->getAssignment($this->role, (string) $user->id) === null) {
                Yii::$app->authManager->assign($role, (string) $user->id);
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $exception) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }
            throw $exception;
        }
    }
}
