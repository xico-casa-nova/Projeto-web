<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "locais".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property string|null $foto_capa_url
 * @property string $morada
 * @property string $cidade
 * @property string|null $codigo_postal
 * @property int $capacidade_total
 * @property float $preco_aluguer_base
 * @property int $ativo
 * @property string|null $criado_em
 * @property string|null $atualizado_em
 *
 * @property Evento[] $eventos
 * @property LocalFoto[] $localFotos
 */
class Local extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%locais}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['descricao', 'foto_capa_url', 'codigo_postal'], 'default', 'value' => null],
            [['capacidade_total'], 'default', 'value' => 0],
            [['preco_aluguer_base'], 'default', 'value' => 0.00],
            [['ativo'], 'default', 'value' => 1],
            [['nome', 'morada', 'cidade'], 'required'],
            [['descricao', 'morada'], 'string'],
            [['capacidade_total', 'ativo'], 'integer'],
            [['preco_aluguer_base'], 'number'],
            [['criado_em', 'atualizado_em'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['foto_capa_url'], 'string', 'max' => 500],
            [['cidade'], 'string', 'max' => 100],
            [['codigo_postal'], 'string', 'max' => 20],
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
            'foto_capa_url' => 'Foto de Capa',
            'morada' => 'Morada',
            'cidade' => 'Cidade',
            'codigo_postal' => 'Código Postal',
            'capacidade_total' => 'Capacidade Total',
            'preco_aluguer_base' => 'Preço de Aluguer Base',
            'ativo' => 'Ativo',
            'criado_em' => 'Criado Em',
            'atualizado_em' => 'Atualizado Em',
        ];
    }

    /**
     * Gets query for [[Eventos]].
     *
     * @return ActiveQuery
     */
    public function getEventos(): ActiveQuery
    {
        return $this->hasMany(Evento::class, ['local_id' => 'id']);
    }

    /**
     * Gets query for [[LocalFotos]].
     *
     * @return ActiveQuery
     */
    public function getLocalFotos(): ActiveQuery
    {
        return $this->hasMany(LocalFoto::class, ['local_id' => 'id']);
    }
}

