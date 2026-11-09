<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Local;

/**
 * LocalSearch represents the model behind the search form of `common\models\Local`.
 */
class LocalSearch extends Local
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'capacidade_total', 'ativo'], 'integer'],
            [['nome', 'descricao', 'foto_capa_url', 'morada', 'cidade', 'codigo_postal', 'criado_em', 'atualizado_em'], 'safe'],
            [['preco_aluguer_base'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Local::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'capacidade_total' => $this->capacidade_total,
            'preco_aluguer_base' => $this->preco_aluguer_base,
            'ativo' => $this->ativo,
            'criado_em' => $this->criado_em,
            'atualizado_em' => $this->atualizado_em,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'foto_capa_url', $this->foto_capa_url])
            ->andFilterWhere(['like', 'morada', $this->morada])
            ->andFilterWhere(['like', 'cidade', $this->cidade])
            ->andFilterWhere(['like', 'codigo_postal', $this->codigo_postal]);

        return $dataProvider;
    }
}
