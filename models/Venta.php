<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "venta".
 *
 * @property integer $id
 * @property string $codigo
 * @property string $estado
 * @property string $fechaGeneracion
 * @property string $fechaCobro
 * @property string $monto
 * @property string $montoIva
 * @property string $montoCobrado
 * @property integer $pedido_id
 *
 * @property NotaCredito[] $notaCreditos
 * @property Pedido $pedido
 */
class Venta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'venta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'pedido_id'], 'required'],
            [['fechaGeneracion', 'fechaCobro'], 'safe'],
            [['monto', 'montoIva', 'montoCobrado'], 'number'],
            [['pedido_id'], 'integer'],
            [['codigo', 'estado'], 'string', 'max' => 45],
            // [['pedido_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pedido::className(), 'targetAttribute' => ['pedido_id' => 'id']],
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
            'fechaGeneracion' => 'Fecha Generacion',
            'fechaCobro' => 'Fecha Cobro',
            'monto' => 'Monto',
            'montoIva' => 'Monto Iva',
            'montoCobrado' => 'Monto Cobrado',
            'pedido_id' => 'Pedido ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotaCreditos()
    {
        return $this->hasMany(NotaCredito::className(), ['venta_id' => 'id'])->inverseOf('venta');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedido::className(), ['id' => 'pedido_id'])->inverseOf('ventas');
    }
}
