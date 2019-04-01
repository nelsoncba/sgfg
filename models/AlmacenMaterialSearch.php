<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AlmacenMaterial;

/**
 * AlmacenMaterialSearch represents the model behind the search form about `app\models\AlmacenMaterial`.
 */
class AlmacenMaterialSearch extends AlmacenMaterial
{
    public $movimiento;
    public $nombreMaterial;
    public $cantidad;
    public $tipo;
    public $fecha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'almacen_id', 'material_id'], 'integer'],
            [['tipo'], 'safe'],
            [['movimiento','cantidad','fecha','nombreMaterial'], 'safe']
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
        $query = AlmacenMaterial::find()->joinWith(['almacen','material']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
                'attributes'=>[
                    'movimiento',
                    'cantidad',
                    'fecha',
                    'nombreMaterial',
                ]
            ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'almacen_id' => $this->almacen_id,
            'material_id' => $this->material_id,
        ]);

        $query->andFilterWhere(['like', 'almacen.tipo', $this->tipo]);
        $query->andFilterWhere(['like', 'almacen.movimiento', $this->movimiento]);
        $query->andFilterWhere(['like', 'almacen.fecha', $this->fecha]);
        $query->andFilterWhere(['like', 'material.nombre', $this->nombreMaterial]);

        return $dataProvider;
    }
}
