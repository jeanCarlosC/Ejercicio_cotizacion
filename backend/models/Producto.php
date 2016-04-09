<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "producto".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property double $precio
 * @property integer $disponibilidad
 * @property integer $tipo_producto_id
 *
 * @property CotizacionProductos[] $cotizacionProductos
 * @property TipoProducto $tipoProducto
 * @property ProductoPaquete[] $productoPaquetes
 * @property Paquete[] $paquetes
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
            [['nombre', 'descripcion', 'precio', 'disponibilidad', 'tipo_producto_id','codigo'], 'required'],
            /*[['precio'], 'number'],*/
            [['disponibilidad', 'tipo_producto_id'], 'integer'],
            /*[['codigo','nombre'],'unique'],*/
            ['codigo', 'unique', 'targetAttribute' => ['codigo'], 'message' => 'Este código ya existe.'],
            [['nombre', 'descripcion','codigo'], 'string', 'max' => 45],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codigo'=>'Código',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
            'precio' => 'Precio',
            'disponibilidad' => 'Disponibilidad',
            'tipo_producto_id' => 'Tipo Producto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCotizacionProductos()
    {
        return $this->hasMany(CotizacionProductos::className(), ['producto_codigo' => 'codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoProducto()
    {
        return $this->hasOne(TipoProducto::className(), ['id' => 'tipo_producto_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductoPaquetes()
    {
        return $this->hasMany(ProductoPaquete::className(), ['producto_codigo' => 'codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaquetes()
    {
        return $this->hasMany(Paquete::className(), ['id' => 'paquete_id'])->viaTable('producto_paquete', ['producto_codigo' => 'codigo']);
    }
}
