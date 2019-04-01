<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $apellido
 * @property string $razonSocial
 * @property string $tipoDocu
 * @property string $documento
 * @property string $fechaNacimiento
 * @property string $estado
 * @property integer $domicilio_id
 *
 * @property Cliente[] $clientes
 * @property ContactoPersonal[] $contactoPersonals
 * @property Domicilio $domicilio
 * @property Proveedor[] $proveedors
 */
class Persona extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'persona';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['fechaNacimiento'], 'safe'],
            // [['domicilio_id'], 'required'],
            // [['domicilio_id'], 'integer'],
            [['tipoDocu'], 'required'],
            [['nombre'], 'required', 'when'=>function($model_persona){
                return $model_persona->tipoDocu == 'dni';
            }, 'whenClient' => "function (atribute,value){
                return $('#persona-tipodocu').val() == 'dni';
            }"],
            [['apellido'], 'required', 'when'=>function($model_persona){
                return $model_persona->tipoDocu == 'dni';
            }, 'whenClient' => "function (atribute,value){
                return $('#persona-tipodocu').val() == 'dni';
            }"],
            [['razonSocial'], 'required', 'when'=>function($model_persona){
                return $model_persona->tipoDocu == 'cuit';
            }, 'whenClient' => "function (atribute,value){
                return $('#persona-tipodocu').val() == 'cuit';
            }"],
            [['documento'], 'required'],
            [['documento'], 'string','min'=>8],
            [['nombre', 'apellido', 'razonSocial', 'tipoDocu', 'documento', 'estado'], 'string', 'max' => 45],
            // [['domicilio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domicilio::className(), 'targetAttribute' => ['domicilio_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'razonSocial' => 'Razon Social',
            'tipoDocu' => 'Tipo Docu',
            'documento' => 'Documento',
            'fechaNacimiento' => 'Fecha Nacimiento',
            'estado' => 'Estado',
            'domicilio_id' => 'Domicilio ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::className(), ['persona_id' => 'id'])->inverseOf('persona');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactoPersonals()
    {
        return $this->hasMany(ContactoPersonal::className(), ['persona_id' => 'id'])->inverseOf('persona');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicilio()
    {
        return $this->hasOne(Domicilio::className(), ['id' => 'domicilio_id'])->inverseOf('personas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedors()
    {
        return $this->hasMany(Proveedor::className(), ['persona_id' => 'id'])->inverseOf('persona');
    }

}
