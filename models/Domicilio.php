<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "domicilio".
 *
 * @property integer $id
 * @property string $calle
 * @property string $numero
 * @property string $codigoPostal
 * @property integer $localidad_id
 *
 * @property Localidad $localidad
 * @property Persona[] $personas
 */
class Domicilio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domicilio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['localidad_id'], 'required'],
            [['localidad_id'], 'integer'],
            [['calle', 'numero', 'codigoPostal'], 'string', 'max' => 45],
            // [['localidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Localidad::className(), 'targetAttribute' => ['localidad_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'calle' => 'Calle',
            'numero' => 'Numero',
            'codigoPostal' => 'Codigo Postal',
            'localidad_id' => 'Localidad ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(Localidad::className(), ['id' => 'localidad_id'])->inverseOf('domicilios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonas()
    {
        return $this->hasMany(Persona::className(), ['domicilio_id' => 'id'])->inverseOf('domicilio');
    }
}
