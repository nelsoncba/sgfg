<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "almacen".
 *
 * @property integer $id
 * @property string $fecha
 * @property integer $cantidad
 * @property string $movimiento
 * @property string $tipo
 * @property string $documento
 *
 * @property AlmacenMaterial[] $almacenMaterials
 * @property AlmacenProducto[] $almacenProductos
 */
class Almacen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'almacen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha','cantidad'], 'required'],
            [['id', 'cantidad'], 'integer'],
            [['fecha','tipo'], 'safe'],
            [['movimiento', 'documento'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fecha' => 'Fecha',
            'cantidad' => 'Cantidad',
            'movimiento' => 'Movimiento',
            'documento' => 'Documento',
            'tipo' => 'Tipo'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenMaterials()
    {
        return $this->hasMany(AlmacenMaterial::className(), ['almacen_id' => 'id'])->inverseOf('almacen');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenProductos()
    {
        return $this->hasMany(AlmacenProducto::className(), ['almacen_id' => 'id'])->inverseOf('almacen');
    }
}
