<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "cotizacion_productos".
 *
 * @property integer $cotizacion_id
 * @property integer $producto_id
 * @property integer $paquete_id
 * @property integer $cantidad
 *
 * @property Cotizacion $cotizacion
 * @property Producto $producto
 * @property Paquete $paquete
 */
class CotizacionProductos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $codigo_general;
    public $precio;
    public $precio_D_I;
    public $nom;
    public static function tableName()
    {
        return 'cotizacion_productos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cotizacion_id', 'producto_codigo', 'paquete_codigo', 'cantidad','codigo_general'], 'required'],
            [['producto_codigo', 'paquete_codigo','codigo_general'], 'string'],
            [['cantidad','cotizacion_id'],'integer'],
            ['cantidad','CheckCantidad'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cotizacion_id' => 'Cotización ID',
            'producto_codigo' => 'Código',
            'paquete_codigo' => 'Código',
            'cantidad' => 'Unidades',
            'codigo_general'=>'Código',
            'precio'=>'Precio Und',
            'precio2'=>'Precio Unds',
        ];
    }


    public function CheckCantidad($attribute, $params)
    {
        $cant_pro = Producto::find()->where(['codigo'=>$this->codigo_general])->one();
        $cant_paq = Paquete::find()->where(['codigo'=>$this->codigo_general])->one();
        
        if(!empty($cant_pro)){
        if($cant_pro->disponibilidad<$this->cantidad)
        {
            $this->addError($attribute,'Cantidad insuficiente, Disponibilidad: '.$cant_pro->disponibilidad.' unidades');
        }

        }
        elseif(!empty($cant_paq))
        {
            if($cant_paq->disponibilidad<$this->cantidad)
        {
            $this->addError($attribute,'Cantidad insuficiente, Disponibilidad: '.$cant_paq->disponibilidad.' paquetes');
        }

        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCotizacion()
    {
        return $this->hasOne(Cotizacion::className(), ['id' => 'cotizacion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducto()
    {
        $paquete = paquete::find()->where(['codigo'=>$this->paquete_codigo])->one();
        $producto = producto::find()->where(['codigo'=>$this->producto_codigo])->one();
        $cotizacion = Cotizacion::find()->where(['id'=>$this->cotizacion_id])->one();
        if(!empty($paquete))
        {
            $precio_conDescuento = round($paquete->precio - ($paquete->precio *($cotizacion->descuento/100)),2);
            $precio_conImpuesto = round($precio_conDescuento * (($cotizacion->impuesto/100)+1),2);


            return array('cod'=>$paquete->codigo,'precio'=>$paquete->precio, 'precio_D_I'=>$precio_conImpuesto, 'nombre'=>$paquete->nombre);
        }
        elseif(!empty($producto))
        {
            $precio_conDescuento = round($producto->precio - ($producto->precio *($cotizacion->descuento/100)),2);
            $precio_conImpuesto =  round($precio_conDescuento * (($cotizacion->impuesto/100)+1),2);
            return array('cod'=>$producto->codigo,'precio'=>$producto->precio, 'precio_D_I'=>$precio_conImpuesto, 'nombre'=>$producto->nombre);
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaquete()
    {
        return $this->hasOne(Paquete::className(), ['codigo' => 'paquete_codigo']);
    }
}
