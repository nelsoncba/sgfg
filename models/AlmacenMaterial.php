<?php

namespace app\models;

use Yii;
use app\models\Almacen;
use app\models\Material;

/**
 * This is the model class for table "almacen_material".
 *
 * @property integer $id
 * @property integer $almacen_id
 * @property integer $material_id
 *
 * @property Almacen $almacen
 * @property Material $material
 */
class AlmacenMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'almacen_material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['almacen_id', 'material_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'almacen_id' => 'Almacen',
            'material_id' => 'Material',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlmacen()
    {
        return $this->hasOne(Almacen::className(), ['id' => 'almacen_id'])->inverseOf('almacenMaterials');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::className(), ['id' => 'material_id'])->inverseOf('almacenMaterials');
    }

    public function getCodigoMaterial(){
        return $this->material->codigo;
    }

    public function getNombreMaterial(){
        return $this->material->nombre;
    }

    public function getCantidadExistencia(){
        return $this->material->cantidadExistencia;
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
    public function getTipo(){
        return $this->almacen->tipo;
    }
}
