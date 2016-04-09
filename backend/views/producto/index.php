<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
?>
<div class="producto-index">


    <p align="right" style="margin-top: 100px;">
        <?= Html::a('Registrar Producto', ['create'], ['class' => 'btn btn-success']) ?>
        
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export'=>false,
        'pjax'=>true,
        'panel'=>[
        'type'=>'primary',
        'heading'=>'Productos'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'codigo',
            'nombre',
            'descripcion',
            [
            'attribute'=>'precio',
            'format'=>['decimal',2],
            ],
            'disponibilidad',
            // 'tipo_producto_id',

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '10'],

                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['producto/view', 'id' => $model['codigo']]);
                    }
                    else if ($action === 'update') {

                    return  Url::toRoute(['producto/update', 'id' => $model['codigo']]);

                    }  
                    else if ($action === 'delete') {

                    return \Yii::$app->getUrlManager()->createUrl(['producto/delete', 'id' => $model['codigo']]);

                    }
                }

            ],
        ],
    ]); ?>

</div>
