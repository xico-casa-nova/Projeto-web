<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "faturas".
 *
 * @property int $id
 * @property int $evento_id
 * @property int $cliente_id
 * @property string $estado
 * @property float $valor_liquido
 * @property float $valor_iva
 * @property float $valor_total
 * @property string|null $data_emissao
 * @property string $data_vencimento
 *
 * @property User $cliente
 * @property Evento $evento
 * @property FaturaLinha[] $faturaLinhas
 */
class Fatura extends ActiveRecord
{
    /**
     * ENUM field values
     */
    public const ESTADO_PENDENTE = 'pendente';
    public const ESTADO_PAGA = 'paga';
    public const ESTADO_VENCIDA = 'vencida';
    public const ESTADO_ANULADA = 'anulada';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%faturas}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['estado'], 'default', 'value' => self::ESTADO_PENDENTE],
            [['valor_total'], 'default', 'value' => 0.00],
            [['evento_id', 'cliente_id', 'data_vencimento'], 'required'],
            [['evento_id', 'cliente_id'], 'integer'],
            [['estado'], 'string'],
            [['valor_liquido', 'valor_iva', 'valor_total'], 'number'],
            [['data_emissao', 'data_vencimento'], 'safe'],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cliente_id' => 'id']],
            [['evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Evento::class, 'targetAttribute' => ['evento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'evento_id' => 'Evento',
            'cliente_id' => 'Cliente',
            'estado' => 'Estado',
            'valor_liquido' => 'Valor Líquido',
            'valor_iva' => 'Valor IVA',
            'valor_total' => 'Valor Total',
            'data_emissao' => 'Data de Emissão',
            'data_vencimento' => 'Data de Vencimento',
        ];
    }

    /**
     * Gets query for [[Cliente]].
     *
     * @return ActiveQuery
     */
    public function getCliente(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'cliente_id']);
    }

    /**
     * Gets query for [[Evento]].
     *
     * @return ActiveQuery
     */
    public function getEvento(): ActiveQuery
    {
        return $this->hasOne(Evento::class, ['id' => 'evento_id']);
    }

    /**
     * Gets query for [[FaturaLinhas]].
     *
     * @return ActiveQuery
     */
    public function getFaturaLinhas(): ActiveQuery
    {
        return $this->hasMany(FaturaLinha::class, ['fatura_id' => 'id']);
    }

    /**
     * column estado ENUM value labels
     * @return array<string, string>
     */
    public static function optsEstado(): array
    {
        return [
            self::ESTADO_PENDENTE => 'Pendente',
            self::ESTADO_PAGA => 'Paga',
            self::ESTADO_VENCIDA => 'Vencida',
            self::ESTADO_ANULADA => 'Anulada',
        ];
    }

    public function displayEstado(): string
    {
        return self::optsEstado()[$this->estado] ?? $this->estado;
    }

    public function isEstadoPendente(): bool
    {
        return $this->estado === self::ESTADO_PENDENTE;
    }

    public function setEstadoToPendente(): void
    {
        $this->estado = self::ESTADO_PENDENTE;
    }

    public function isEstadoPaga(): bool
    {
        return $this->estado === self::ESTADO_PAGA;
    }

    public function setEstadoToPaga(): void
    {
        $this->estado = self::ESTADO_PAGA;
    }

    public function isEstadoVencida(): bool
    {
        return $this->estado === self::ESTADO_VENCIDA;
    }

    public function setEstadoToVencida(): void
    {
        $this->estado = self::ESTADO_VENCIDA;
    }

    public function isEstadoAnulada(): bool
    {
        return $this->estado === self::ESTADO_ANULADA;
    }

    public function setEstadoToAnulada(): void
    {
        $this->estado = self::ESTADO_ANULADA;
    }
}

