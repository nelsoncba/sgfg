<?php

namespace app\models;

use Yii;
use app\models\Producto;
use app\models\Pedido;

/**
 * This is the model class for table "detalle_pedido".
 *
 * @property integer $id
 * @property integer $cantidad
 * @property string $descuento
 * @property integer $producto_id
 * @property integer $pedido_id
 *
 * @property Pedido $pedido
 * @property Producto $producto
 */
class DetallePedido extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalle_pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cantidad', 'producto_id', 'pedido_id'], 'integer'],
            ['cantidad','validarStock','when'=>function($action){
               if($action == 'createPedido')
                return true;
            }],
            [['descuento'], 'number'],
            [['producto_id','cantidad'], 'required'],
            // [['pedido_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pedido::className(), 'targetAttribute' => ['pedido_id' => 'id']],
            [['producto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Producto::className(), 'targetAttribute' => ['producto_id' => 'id']],
        ];
    }

    public function validarStock(){

        $producto = Producto::find()->where(['id'=>$this->producto_id])->andWhere(['>=','cantidadExistencia',$this->cantidad])->one();
        //var_dump($producto);
        //return $producto;
        if($producto== null){
             $this->addError('cantidad', 'La cantidad debe ser menor o igual al stock');
             return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cantidad' => 'Cantidad (paquetes)',
            'descuento' => 'Descuento',
            'producto_id' => 'Producto',
            'pedido_id' => 'Pedido',
            'precioUnitario' =>'Precio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedido::className(), ['id' => 'pedido_id'])->inverseOf('detallePedidos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        return $this->hasOne(Producto::className(), ['id' => 'producto_id'])->inverseOf('detallePedidos');
    }

    public function getPrecioUnitario(){
        return $this->producto->precioUnitario;
    }
}
