<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fatura_linhas".
 *
 * @property int $id
 * @property int $fatura_id
 * @property int|null $produto_id
 * @property string $descricao
 * @property float $valor_pago
 * @property float $percentual_iva
 * @property float $valor_iva
 *
 * @property Fatura $fatura
 * @property Produto|null $produto
 */
class FaturaLinha extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fatura_linhas}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['produto_id'], 'default', 'value' => null],
            [['valor_iva'], 'default', 'value' => 0.00],
            [['percentual_iva'], 'default', 'value' => 23.00],
            [['fatura_id', 'descricao'], 'required'],
            [['fatura_id', 'produto_id'], 'integer'],
            [['valor_pago', 'percentual_iva', 'valor_iva'], 'number'],
            [['descricao'], 'string', 'max' => 255],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['fatura_id' => 'id']],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'fatura_id' => 'Fatura',
            'produto_id' => 'Produto',
            'descricao' => 'Descrição',
            'valor_pago' => 'Valor Pago',
            'percentual_iva' => 'Percentual IVA (%)',
            'valor_iva' => 'Valor IVA',
        ];
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return ActiveQuery
     */
    public function getFatura(): ActiveQuery
    {
        return $this->hasOne(Fatura::class, ['id' => 'fatura_id']);
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return ActiveQuery
     */
    public function getProduto(): ActiveQuery
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }
}

