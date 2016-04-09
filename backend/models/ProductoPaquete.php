<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "producto_paquete".
 *
 * @property integer $producto_codigo
 * @property integer $paquete_codigo
 * @property integer $cantidad
 *
 * @property Paquete $paquete
 * @property Producto $producto
 */
class ProductoPaquete extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $d;
    public $nom;
    public static function tableName()
    {
        return 'producto_paquete';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[/*'producto_codigo',*/ 'paquete_codigo', 'cantidad'], 'required'],
            [['cantidad','d'], 'integer'],
            [['producto_codigo', 'paquete_codigo'],'string', 'max'=>45],
            ['cantidad','CheckCantidad'],
        ];
    }

    public function CheckCantidad($attribute, $params)
    {
        $cant = Producto::find()->where(['codigo'=>$this->producto_codigo])->one();
        
        if($cant->disponibilidad<$this->cantidad)
        {
            $this->addError($attribute,'Cantidad insuficiente, Disponibilidad: '.$cant->disponibilidad);
        }
        elseif($cant->disponibilidad<$this->cantidad*$this->d)
        {
            $this->addError($attribute,'Cantidad insuficiente para cubrir la cantidad de paquetes, Disponibilidad: '.$cant->disponibilidad);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'producto_codigo' => 'codigo',
            'paquete_codigo' => 'codigo',
            'cantidad' => 'Cantidad',
            'nom'=>'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaquete()
    {
        return $this->hasOne(Paquete::className(), ['codigo' => 'paquete_codigo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNombre()
    {
        $nombre = Producto::find()->where(['codigo'=>$this->producto_codigo])->one();
        return $nombre->nombre;
    }
}
