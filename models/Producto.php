<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "producto".
 *
 * @property integer $id
 * @property string $codigo
 * @property string $nombre
 * @property string $cantidadExistencia
 * @property string $cantidadMinima
 * @property string $precioUnitario
 *
 * @property AlmacenProducto[] $almacenProductos
 * @property DetallePedido[] $detallePedidos
 */
class Producto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'producto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cantidadExistencia','nombre','cantidadMinima','precioUnitario'], 'required'],
            [['cantidadExistencia', 'cantidadMinima', 'precioUnitario'], 'number'],
            [['codigo', 'nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'cantidadExistencia' => 'Cantidad Existencia',
            'cantidadMinima' => 'Cantidad Minima',
            'precioUnitario' => 'Precio Unitario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenProductos()
    {
        return $this->hasMany(AlmacenProducto::className(), ['producto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetallePedidos()
    {
        return $this->hasMany(DetallePedido::className(), ['producto_id' => 'id']);
    }
}
