<?php

namespace app\models;

use Yii;
use app\models\Persona;

/**
 * This is the model class for table "cliente".
 *
 * @property integer $codigo
 * @property string $tipo
 * @property integer $persona_id
 *
 * @property Persona $persona
 * @property Pedido[] $pedidos
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['persona_id'], 'required'],
            // [['persona_id'], 'integer'],
            [['tipo'], 'string', 'max' => 45],
            [['tipo'], 'required'],
            // [['persona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['persona_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'tipo' => 'Clase',
            'persona_id' => 'Persona ID',
            'tipoDocu' => 'Tipo Documento',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {   
        return $this->hasOne(Persona::className(), ['id' => 'persona_id'])->inverseOf('clientes');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidos()
    {
        return $this->hasMany(Pedido::className(), ['cliente_codigo' => 'codigo'])->inverseOf('clienteCodigo');
    }

    public function getNombre(){
        return $this->persona->nombre;
    }
    public function getApellido(){
        return $this->persona->apellido;
    }
    public function getTipoDocu(){
        return $this->persona->tipoDocu;
    }
    public function getDocumento(){
        return $this->persona->documento;
    }
    public function getPersonaContacto(){
        return $this->persona->contactoPersonals;
    }
}
