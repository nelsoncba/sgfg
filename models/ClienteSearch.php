<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

/**
 * ClienteSearch represents the model behind the search form about `app\models\Cliente`.
 */
class ClienteSearch extends Cliente
{
    public $nombre;
    public $apellido;
    public $tipoDocu;
    public $documento;
    public $documento2;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'persona_id'], 'integer'],
            [['tipo'], 'safe'],
            [['nombre'], 'safe'],
            [['apellido'], 'safe'],
            [['tipoDocu'], 'safe'],
            [['documento'], 'safe'],
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
        $query = Cliente::find()->joinWith(['persona']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => [
                'pagesize' => 10
            ]
        ]);

        $dataProvider->setSort([
                'attributes'=>[
                    'codigo',
                    'nombre'=>[
                            'asc'=>['nombre'=>SORT_ASC],
                            'desc'=>['nombre'=>SORT_DESC]
                    ],
                    'apellido',
                    'tipoDocu',
                    'documento'=>[
                            'asc'=>['documento'=>SORT_ASC],
                            'desc'=>['documento'=>SORT_DESC]
                    ],
                    'clase'
                ],
            ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'codigo' => $this->codigo,
            'persona_id' => $this->persona_id,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo]);
        $query->andFilterWhere(['like', 'persona.nombre', $this->nombre]);
        $query->andFilterWhere(['like', 'persona.apellido', $this->apellido]);
        $query->andFilterWhere(['like', 'persona.tipoDocu', $this->tipoDocu]);
        //$query->andFilterWhere(['like', 'persona.documento', $this->documento]);
        $query->orFilterWhere(['like', 'persona.documento', $this->documento]);


        return $dataProvider;
    }
}
