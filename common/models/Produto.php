<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "produtos".
 *
 * @property int $id
 * @property string $nome
 * @property string $categoria
 * @property string $tipo_cobranca
 * @property float $preco
 * @property int $ativo
 * @property string|null $criado_em
 *
 * @property FaturaLinha[] $faturaLinhas
 */
class Produto extends ActiveRecord
{
    public const CATEGORIA_CATERING = 'catering';
    public const CATEGORIA_FOTOGRAFIA = 'fotografia';
    public const CATEGORIA_ANIMACAO = 'animacao';
    public const CATEGORIA_DECORACAO = 'decoracao';
    public const CATEGORIA_OUTROS = 'outros';

    public const TIPO_COBRANCA_FIXO = 'fixo';
    public const TIPO_COBRANCA_POR_PESSOA = 'por_pessoa';
    public const TIPO_COBRANCA_POR_HORA = 'por_hora';
    public const TIPO_COBRANCA_POR_UNIDADE = 'por_unidade';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%produtos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['categoria'], 'default', 'value' => self::CATEGORIA_OUTROS],
            [['tipo_cobranca'], 'default', 'value' => self::TIPO_COBRANCA_FIXO],
            [['preco'], 'default', 'value' => 0.00],
            [['ativo'], 'default', 'value' => 1],
            [['nome'], 'required'],
            [['categoria', 'tipo_cobranca'], 'string'],
            [['preco'], 'number'],
            [['ativo'], 'integer'],
            [['criado_em'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            ['categoria', 'in', 'range' => array_keys(self::optsCategoria())],
            ['tipo_cobranca', 'in', 'range' => array_keys(self::optsTipoCobranca())],
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
            'categoria' => 'Categoria',
            'tipo_cobranca' => 'Tipo de Cobrança',
            'preco' => 'Preço (€)',
            'ativo' => 'Ativo',
            'criado_em' => 'Criado Em',
        ];
    }

    /**
     * Gets query for [[FaturaLinhas]].
     *
     * @return ActiveQuery
     */
    public function getFaturaLinhas(): ActiveQuery
    {
        return $this->hasMany(FaturaLinha::class, ['produto_id' => 'id']);
    }

    /**
     * @return array<string, string>
     */
    public static function optsCategoria(): array
    {
        return [
            self::CATEGORIA_CATERING => 'Catering',
            self::CATEGORIA_FOTOGRAFIA => 'Fotografia',
            self::CATEGORIA_ANIMACAO => 'Animação',
            self::CATEGORIA_DECORACAO => 'Decoração',
            self::CATEGORIA_OUTROS => 'Outros',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function optsTipoCobranca(): array
    {
        return [
            self::TIPO_COBRANCA_FIXO => 'Fixo',
            self::TIPO_COBRANCA_POR_PESSOA => 'Por Pessoa',
            self::TIPO_COBRANCA_POR_HORA => 'Por Hora',
            self::TIPO_COBRANCA_POR_UNIDADE => 'Por Unidade',
        ];
    }
}

