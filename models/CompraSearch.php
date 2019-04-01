<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Compra;
use app\models\Proveedor;
use app\models\Persona;

/**
 * CompraSearch represents the model behind the search form about `app\models\Compra`.
 */
class CompraSearch extends Compra
{   
    public $razonSocial;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'proveedor'], 'integer'],
            [['numeroFactura', 'estado','razonSocial'], 'safe'],
            [['importe', 'notaCredito', 'notaDebito'], 'number'],
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
        $query = Compra::find()->joinWith(['proveedor','proveedor.persona']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'importe' => $this->importe,
            'notaCredito' => $this->notaCredito,
            'notaDebito' => $this->notaDebito,
            'proveedor_codigo' => $this->proveedor_codigo,
        ]);

        $query->andFilterWhere(['like', 'numeroFactura', $this->numeroFactura])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'persona.razonSocial', $this->razonSocial]);

        return $dataProvider;
    }
}
