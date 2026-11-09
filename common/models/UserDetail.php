<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_details".
 *
 * @property int $id
 * @property int $user_id
 * @property string $nome_completo
 * @property string $role
 * @property string|null $foto_perfil_url
 * @property string|null $telefone
 * @property string|null $nif
 * @property string|null $morada
 * @property string|null $cidade
 * @property string|null $codigo_postal
 * @property string|null $criado_em
 * @property string|null $atualizado_em
 *
 * @property User $user
 */
class UserDetail extends ActiveRecord
{
    public const ROLE_USER = 'user';
    public const ROLE_GESTOR = 'gestor';
    public const ROLE_ADMIN = 'admin';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_details}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['foto_perfil_url', 'telefone', 'nif', 'morada', 'cidade', 'codigo_postal'], 'default', 'value' => null],
            [['role'], 'default', 'value' => self::ROLE_USER],
            [['user_id', 'nome_completo'], 'required'],
            [['user_id'], 'integer'],
            [['morada', 'role'], 'string'],
            [['criado_em', 'atualizado_em'], 'safe'],
            [['nome_completo'], 'string', 'max' => 255],
            [['foto_perfil_url'], 'string', 'max' => 500],
            [['telefone'], 'string', 'max' => 30],
            [['nif', 'codigo_postal'], 'string', 'max' => 20],
            [['cidade'], 'string', 'max' => 100],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'Utilizador',
            'nome_completo' => 'Nome Completo',
            'role' => 'Função (Role)',
            'foto_perfil_url' => 'Foto de Perfil',
            'telefone' => 'Telefone',
            'nif' => 'NIF',
            'morada' => 'Morada',
            'cidade' => 'Cidade',
            'codigo_postal' => 'Código Postal',
            'criado_em' => 'Criado Em',
            'atualizado_em' => 'Atualizado Em',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return array<string, string>
     */
    public static function optsRole(): array
    {
        return [
            self::ROLE_USER => 'Utilizador',
            self::ROLE_GESTOR => 'Gestor',
            self::ROLE_ADMIN => 'Administrador',
        ];
    }

    public function displayRole(): string
    {
        return self::optsRole()[$this->role] ?? $this->role;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isGestor(): bool
    {
        return $this->role === self::ROLE_GESTOR;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
