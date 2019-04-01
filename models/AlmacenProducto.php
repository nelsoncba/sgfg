<?php

namespace app\models;

use Yii;
use app\models\Almacen;
use app\models\Producto;

/**
 * This is the model class for table "almacen_producto".
 *
 * @property integer $id
 * @property string $tipo
 * @property string $descripcion
 * @property integer $almacen_id
 * @property integer $producto_id
 *
 * @property Almacen $almacen
 * @property Producto $producto
 */
class AlmacenProducto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'almacen_producto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['producto_id'], 'required'],
            [['id', 'almacen_id', 'producto_id'], 'integer'],
            [['tipo', 'descripcion'], 'string', 'max' => 45],
            [['almacen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Almacen::className(), 'targetAttribute' => ['almacen_id' => 'id']],
            [['producto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::className(), 'targetAttribute' => ['producto_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipo' => 'Tipo',
            'descripcion' => 'Descripcion',
            ['nombre' => 'Producto'],
            ['almacen_id' => ''],
            ['producto_id' => 'Producto'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacen()
    {
        return $this->hasOne(Almacen::className(), ['id' => 'almacen_id'])->inverseOf('almacenProductos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Producto::className(), ['id' => 'producto_id'])->inverseOf('almacenProductos');
    }

    public function getCodigoProducto(){
        return $this->producto->codigo;
    }

    public function getNombreProducto(){
        return $this->producto->nombre;
    }

    public function getCantidadExistencia(){
        return $this->producto->cantidadExistencia;
    }

    public function getFecha(){
        return $this->almacen->fecha;
    }

    public function getCantidad(){
        return $this->almacen->cantidad;
    }

    public function getMovimiento(){
        return $this->almacen->movimiento;
    }
}
