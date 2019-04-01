<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "material".
 *
 * @property integer $id
 * @property string $codigo
 * @property string $nombre
 * @property string $estado
 * @property integer $cantidadExistencia
 * @property integer $cantidadMinitma
 * @property string $precioUnitario
 *
 * @property AlmacenMaterial[] $almacenMaterials
 * @property DetalleCompra[] $detalleCompras
 * @property Compra[] $compras
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'cantidadExistencia', 'cantidadMinitma'], 'integer'],
            [['precioUnitario'], 'number'],
            [['codigo', 'nombre', 'estado'], 'string', 'max' => 45],
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
            'estado' => 'Estado',
            'cantidadExistencia' => 'Cantidad Existencia',
            'cantidadMinitma' => 'Cantidad Minitma',
            'precioUnitario' => 'Precio Unitario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacenMaterials()
    {
        return $this->hasMany(AlmacenMaterial::className(), ['material_id' => 'id'])->inverseOf('material');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCompras()
    {
        return $this->hasMany(DetalleCompra::className(), ['material_id' => 'id'])->inverseOf('material');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompras()
    {
        return $this->hasMany(Compra::className(), ['id' => 'compra_id'])->viaTable('detalle_compra', ['material_id' => 'id']);
    }
}
