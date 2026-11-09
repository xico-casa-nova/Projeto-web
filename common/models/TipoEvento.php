<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tipos_evento".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property float $preco_base_pessoa
 * @property int $ativo
 * @property string|null $criado_em
 *
 * @property Evento[] $eventos
 */
class TipoEvento extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%tipos_evento}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['descricao'], 'default', 'value' => null],
            [['preco_base_pessoa'], 'default', 'value' => 0.00],
            [['ativo'], 'default', 'value' => 1],
            [['nome'], 'required'],
            [['descricao'], 'string'],
            [['preco_base_pessoa'], 'number'],
            [['ativo'], 'integer'],
            [['criado_em'], 'safe'],
            [['nome'], 'string', 'max' => 100],
            [['nome'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'preco_base_pessoa' => 'Preço Base por Pessoa',
            'ativo' => 'Ativo',
            'criado_em' => 'Criado Em',
        ];
    }

    /**
     * Gets query for [[Eventos]].
     *
     * @return ActiveQuery
     */
    public function getEventos(): ActiveQuery
    {
        return $this->hasMany(Evento::class, ['tipo_evento_id' => 'id']);
    }
}

