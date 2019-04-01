<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedido".
 *
 * @property integer $id
 * @property string $codigo
 * @property string $estado
 * @property string $tipo
 * @property string $fechaPedido
 * @property string $fechaEntrega
 * @property integer $cliente_codigo
 *
 * @property DetallePedido[] $detallePedidos
 * @property Cliente $clienteCodigo
 * @property Venta[] $ventas
 */
class Pedido extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente_codigo'], 'required'],
            [['cliente_codigo'], 'integer'],
            [['fechaPedido', 'fechaEntrega'], 'safe'],
            [['codigo', 'estado', 'tipo'], 'string', 'max' => 45],
            //[['cliente_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_codigo' => 'codigo']],
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
            'estado' => 'Estado',
            'tipo' => 'Tipo',
            'fechaPedido' => 'Fecha Pedido',
            'fechaEntrega' => 'Fecha Entrega',
            'cliente_codigo' => 'Cliente Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetallePedidos()
    {
        return $this->hasMany(DetallePedido::className(), ['pedido_id' => 'id'])->inverseOf('pedido');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteCodigo()
    {
        return $this->hasOne(Cliente::className(), ['codigo' => 'cliente_codigo'])->inverseOf('pedidos');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVentas()
    {
        return $this->hasMany(Venta::className(), ['pedido_id' => 'id'])->inverseOf('pedido');
    }
}
