<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "local_fotos".
 *
 * @property int $id
 * @property int $local_id
 * @property string $foto_url
 * @property string|null $legenda
 * @property int $ordem
 * @property string|null $criado_em
 *
 * @property Local $local
 */
class LocalFoto extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%local_fotos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['legenda'], 'default', 'value' => null],
            [['ordem'], 'default', 'value' => 0],
            [['local_id', 'foto_url'], 'required'],
            [['local_id', 'ordem'], 'integer'],
            [['criado_em'], 'safe'],
            [['foto_url'], 'string', 'max' => 500],
            [['legenda'], 'string', 'max' => 255],
            [['local_id'], 'exist', 'skipOnError' => true, 'targetClass' => Local::class, 'targetAttribute' => ['local_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'local_id' => 'Local',
            'foto_url' => 'Foto (URL)',
            'legenda' => 'Legenda',
            'ordem' => 'Ordem',
            'criado_em' => 'Criado Em',
        ];
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
}

