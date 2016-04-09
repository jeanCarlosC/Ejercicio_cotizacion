<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cotizacion".
 *
 * @property integer $id
 * @property double $descuento
 * @property double $impuesto
 * @property string $cliente
 * @property string $ruc
 *
 * @property CotizacionProductos[] $cotizacionProductos
 */
class Cotizacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $vendedora;
    public $tipo;
    public static function tableName()
    {
        return 'cotizacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cliente', 'ruc','tipo','descuento', 'impuesto'], 'required'],
            [['id'], 'integer'],
            [['descuento', 'impuesto'], 'integer'],
            ['tipo','string','max'=>1],
            [['cliente', 'ruc','vendedora_codigo','vendedora','codigo'], 'string', 'max' => 45],
            ['ruc','unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descuento' => 'Descuento',
            'impuesto' => 'Impuesto',
            'cliente' => 'Cliente',
            'ruc' => 'Documento de Identidad',
            'vendedora_codigo'=>'Codigo vendedora',
            'codigo'=>'Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCotizacionProductos()
    {
        return $this->hasMany(CotizacionProductos::className(), ['cotizacion_id' => 'id']);
    }
}
