<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_compra".
 *
 * @property integer $compra_id
 * @property integer $cantidad
 * @property string $precio
 * @property integer $material_id
 *
 * @property Compra $compra
 * @property Material $material
 */
class DetalleCompra extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalle_compra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['material_id'], 'required'],
            [['compra_id', 'cantidad', 'material_id'], 'integer'],
            ['cantidad','validarStock'],
            [['precio'], 'number'],
            // [['compra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Compra::className(), 'targetAttribute' => ['compra_id' => 'id']],
            // [['material_id'], 'exist', 'skipOnError' => true, 'targetClass' => Material::className(), 'targetAttribute' => ['material_id' => 'id']],
        ];
    }

    public function validarStock(){

        $material = Material::find()->where(['id'=>$this->material_id])->andWhere(['>=','cantidadExistencia',$this->cantidad])->one();
        //var_dump($producto);
        //return $producto;
        if($material== null){
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
            'compra_id' => 'Compra',
            'cantidad' => 'Cantidad',
            'precio' => 'Precio',
            'material_id' => 'Material',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompra()
    {
        return $this->hasOne(Compra::className(), ['id' => 'compra_id'])->inverseOf('detalleCompras');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::className(), ['id' => 'material_id'])->inverseOf('detalleCompras');
    }
}
