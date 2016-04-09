<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ProductoPaquete;

/**
 * ProductoPaqueteSearch represents the model behind the search form about `backend\models\ProductoPaquete`.
 */
class ProductoPaqueteSearch extends ProductoPaquete
{
    /**
     * @inheritdoc
     */
    public $nom;
    public function rules()
    {
        return [
            [['cantidad'], 'integer'],
            [['producto_codigo', 'paquete_codigo','nom'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ProductoPaquete::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cantidad' => $this->cantidad,
        ]);

        $query->andFilterWhere(['like', 'producto_codigo', $this->producto_codigo])
            ->andFilterWhere(['like', 'paquete_codigo', $this->paquete_codigo]);

        return $dataProvider;
    }
}