<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localidad".
 *
 * @property integer $id
 * @property string $nombre
 * @property integer $id_provincia
 *
 * @property Domicilio[] $domicilios
 */
class Localidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'localidad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 45],
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
            'id_provincia' => 'Id Provincia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDomicilios()
    {
        return $this->hasMany(Domicilio::className(), ['localidad_id' => 'id'])->inverseOf('localidad');
    }
}
