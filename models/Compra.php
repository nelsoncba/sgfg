<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "compra".
 *
 * @property integer $id
 * @property string $numeroFactura
 * @property string $estado
 * @property string $importe
 * @property string $notaCredito
 * @property string $notaDebito
 * @property integer $proveedor_codigo
 * @property string $fecha
 *
 * @property Proveedor $proveedorCodigo
 * @property DetalleCompra[] $detalleCompras
 * @property Material[] $materials
 */
class Compra extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'compra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['importe', 'notaCredito', 'notaDebito'], 'number'],
            [['proveedor_codigo'], 'required'],
            [['proveedor_codigo'], 'integer'],
            [['fecha'], 'safe'],
            [['numeroFactura', 'estado'], 'string', 'max' => 45],
            // [['proveedor_codigo'], 'exist', 'skipOnError' => true, 'targetClass' => Proveedor::className(), 'targetAttribute' => ['proveedor_codigo' => 'codigo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numeroFactura' => 'Numero Factura',
            'estado' => 'Estado',
            'importe' => 'Importe',
            'notaCredito' => 'Nota Credito',
            'notaDebito' => 'Nota Debito',
            'proveedor_codigo' => 'Proveedor',
            'fecha' => 'Fecha',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor()
    {
        return $this->hasOne(Proveedor::className(), ['codigo' => 'proveedor_codigo'])->inverseOf('compras');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCompras()
    {
        return $this->hasMany(DetalleCompra::className(), ['compra_id' => 'id'])->inverseOf('compra');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials()
    {
        return $this->hasMany(Material::className(), ['id' => 'material_id'])->viaTable('detalle_compra', ['compra_id' => 'id']);
    }

    public function getRazonSocial(){
        return $this->proveedor->razonSocial;
    }
}
