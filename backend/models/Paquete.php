<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "paquete".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property integer $disponibilidad
 * @property double $precio
 *
 * @property CotizacionProductos[] $cotizacionProductos
 * @property ProductoPaquete[] $productoPaquetes
 * @property Producto[] $productos
 */
class Paquete extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paquete';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'descripcion', 'disponibilidad', 'precio','codigo'], 'required'],
            [['disponibilidad'], 'integer'],
            [['precio'], 'number'],
            [['nombre', 'descripcion','codigo'], 'string', 'max' => 45],
            [['nombre','codigo'],'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nombre' => 'Nombre',
            'descripcion' => 'Descripción',
            'disponibilidad' => 'Número de paquetes',
            'precio' => 'Precio',
            'codigo'=>'Código',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCotizacionProductos()
    {
        return $this->hasMany(CotizacionProductos::className(), ['paquete_codigo' => 'codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductoPaquetes()
    {
        return $this->hasMany(ProductoPaquete::className(), ['paquete_codigo' => 'codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductos()
    {
        $productos = ProductoPaquete::find()->where(['paquete_codigo'=>$this->codigo])->all();
        return $productos;
    }
}
