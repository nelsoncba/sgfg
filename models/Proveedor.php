<?php

namespace app\models;

use Yii;
use app\models\Persona;

/**
 * This is the model class for table "proveedor".
 *
 * @property integer $codigo
 * @property integer $persona_id
 *
 * @property Compra[] $compras
 * @property Persona $persona
 */
class Proveedor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proveedor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['persona_id'], 'required'],
            // [['persona_id'], 'integer'],
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
            'persona_id' => 'Persona ID',
            'tipoDocu' => 'Tipo Documento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompras()
    {
        return $this->hasMany(Compra::className(), ['proveedor_codigo' => 'codigo'])->inverseOf('proveedorCodigo');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['id' => 'persona_id']);
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
    public function getRazonSocial(){
        return $this->persona->razonSocial;
    }
}
