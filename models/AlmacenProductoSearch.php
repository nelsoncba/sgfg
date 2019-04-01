<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AlmacenProducto;

/**
 * AlmacenProductoSearch represents the model behind the search form about `app\models\AlmacenProducto`.
 */
class AlmacenProductoSearch extends AlmacenProducto
{
    public $movimiento;
    public $nombreProducto;
    public $cantidad;
    public $tipo;
    public $fecha;
    public $descripcion;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'almacen_id', 'producto_id'], 'integer'],
            [['tipo', 'descripcion'], 'safe'],
            [['movimiento','cantidad','fecha','nombreProducto'], 'safe']
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
        $query = AlmacenProducto::find()->joinWith(['almacen','producto']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
                'attributes'=>[
                    'movimiento',
                    'cantidad',
                    'fecha',
                    'nombreProducto',
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
            'producto_id' => $this->producto_id,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);
        $query->andFilterWhere(['like', 'almacen.movimiento', $this->movimiento]);
        $query->andFilterWhere(['like', 'almacen.fecha', $this->fecha]);
        $query->andFilterWhere(['like', 'producto.nombre', $this->nombreProducto]);

        return $dataProvider;
    }
}
