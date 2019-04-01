<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contacto_personal".
 *
 * @property integer $id
 * @property string $telefono
 * @property string $celular
 * @property string $email
 * @property integer $persona_id
 *
 * @property Persona $persona
 */
class ContactoPersonal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacto_personal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['persona_id'], 'integer'],
            // [['telefono', 'celular', 'email'], 'string', 'max' => 45],
            // [['persona_id'], 'exist', 'skipOnError' => true, 'targetClass' => Persona::className(), 'targetAttribute' => ['persona_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'telefono' => 'Telefono',
            'celular' => 'Celular',
            'email' => 'Email',
            'persona_id' => 'Persona ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['id' => 'persona_id'])->inverseOf('contactoPersonals');
    }

    public function getPersonaId(){
        return $this->persona->id;
    }
}
