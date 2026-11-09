<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "eventos".
 *
 * @property int $id
 * @property int $cliente_id
 * @property int $local_id
 * @property int $tipo_evento_id
 * @property string $titulo
 * @property string|null $descricao
 * @property int $num_estimado_pessoas
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $estado
 * @property float $taxa_fixa_organizacao
 * @property float $taxa_plataforma_percentual
 * @property string|null $criado_em
 * @property string|null $atualizado_em
 *
 * @property User $cliente
 * @property Fatura[] $faturas
 * @property Local $local
 * @property TipoEvento $tipoEvento
 */
class Evento extends ActiveRecord
{
    /**
     * ENUM field values
     */
    public const ESTADO_RASCUNHO = 'rascunho';
    public const ESTADO_AGENDADO = 'agendado';
    public const ESTADO_EM_EXECUCAO = 'em_execucao';
    public const ESTADO_CONCLUIDO = 'concluido';
    public const ESTADO_CANCELADO = 'cancelado';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%eventos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['descricao'], 'default', 'value' => null],
            [['num_estimado_pessoas'], 'default', 'value' => 0],
            [['estado'], 'default', 'value' => self::ESTADO_RASCUNHO],
            [['taxa_fixa_organizacao'], 'default', 'value' => 100.00],
            [['taxa_plataforma_percentual'], 'default', 'value' => 5.00],
            [['cliente_id', 'local_id', 'tipo_evento_id', 'titulo', 'data_inicio', 'data_fim'], 'required'],
            [['cliente_id', 'local_id', 'tipo_evento_id', 'num_estimado_pessoas'], 'integer'],
            [['descricao', 'estado'], 'string'],
            [['data_inicio', 'data_fim', 'criado_em', 'atualizado_em'], 'safe'],
            [['taxa_fixa_organizacao', 'taxa_plataforma_percentual'], 'number'],
            [['titulo'], 'string', 'max' => 255],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['cliente_id' => 'id']],
            [['local_id'], 'exist', 'skipOnError' => true, 'targetClass' => Local::class, 'targetAttribute' => ['local_id' => 'id']],
            [['tipo_evento_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEvento::class, 'targetAttribute' => ['tipo_evento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'cliente_id' => 'Cliente ID',
            'local_id' => 'Local ID',
            'tipo_evento_id' => 'Tipo Evento ID',
            'titulo' => 'Título',
            'descricao' => 'Descrição',
            'num_estimado_pessoas' => 'Nº Estimado de Pessoas',
            'data_inicio' => 'Data de Início',
            'data_fim' => 'Data de Fim',
            'estado' => 'Estado',
            'taxa_fixa_organizacao' => 'Taxa Fixa Organização',
            'taxa_plataforma_percentual' => 'Taxa Plataforma (%)',
            'criado_em' => 'Criado Em',
            'atualizado_em' => 'Atualizado Em',
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
     * Gets query for [[Faturas]].
     *
     * @return ActiveQuery
     */
    public function getFaturas(): ActiveQuery
    {
        return $this->hasMany(Fatura::class, ['evento_id' => 'id']);
    }

    /**
     * Gets query for [[Local]].
     *
     * @return ActiveQuery
     */
    public function getLocal(): ActiveQuery
    {
        return $this->hasOne(Local::class, ['id' => 'local_id']);
    }

    /**
     * Gets query for [[TipoEvento]].
     *
     * @return ActiveQuery
     */
    public function getTipoEvento(): ActiveQuery
    {
        return $this->hasOne(TipoEvento::class, ['id' => 'tipo_evento_id']);
    }

    /**
     * column estado ENUM value labels
     * @return array<string, string>
     */
    public static function optsEstado(): array
    {
        return [
            self::ESTADO_RASCUNHO => 'Rascunho',
            self::ESTADO_AGENDADO => 'Agendado',
            self::ESTADO_EM_EXECUCAO => 'Em Execução',
            self::ESTADO_CONCLUIDO => 'Concluído',
            self::ESTADO_CANCELADO => 'Cancelado',
        ];
    }

    /**
     * @return string
     */
    public function displayEstado(): string
    {
        return self::optsEstado()[$this->estado] ?? $this->estado;
    }

    public function isEstadoRascunho(): bool
    {
        return $this->estado === self::ESTADO_RASCUNHO;
    }

    public function setEstadoToRascunho(): void
    {
        $this->estado = self::ESTADO_RASCUNHO;
    }

    public function isEstadoAgendado(): bool
    {
        return $this->estado === self::ESTADO_AGENDADO;
    }

    public function setEstadoToAgendado(): void
    {
        $this->estado = self::ESTADO_AGENDADO;
    }

    public function isEstadoEmexecucao(): bool
    {
        return $this->estado === self::ESTADO_EM_EXECUCAO;
    }

    public function setEstadoToEmexecucao(): void
    {
        $this->estado = self::ESTADO_EM_EXECUCAO;
    }

    public function isEstadoConcluido(): bool
    {
        return $this->estado === self::ESTADO_CONCLUIDO;
    }

    public function setEstadoToConcluido(): void
    {
        $this->estado = self::ESTADO_CONCLUIDO;
    }

    public function isEstadoCancelado(): bool
    {
        return $this->estado === self::ESTADO_CANCELADO;
    }

    public function setEstadoToCancelado(): void
    {
        $this->estado = self::ESTADO_CANCELADO;
    }
}

