<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\ProductoPaqueteSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaqueteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Paquetes';
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
?>
<div class="paquete-index">
    <p align="right" style="margin-top: 100px;">
        <?= Html::a('Agregar Paquete', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'export'=>false,
        'pjax' => true,
        'bordered' => true,
        'hover' => true,
        'panel'=>[
        'type'=>'primary',
        'heading'=>'Paquetes'
        ],

        'columns' => [
            [
            'class'=>'kartik\grid\ExpandRowColumn',
            'value'=> function($model, $key, $index, $column){
                return GridView::ROW_COLLAPSED;
            },
            'detail'=> function($model, $key, $index, $column){
                $searchModel = new ProductoPaqueteSearch();
                $searchModel->paquete_codigo = $model->codigo;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return Yii::$app->controller->renderPartial('_productos',[
                    'searchModel'=> $searchModel,
                    'dataProvider'=>$dataProvider,
                    ]);
            },

            ],
            'codigo',
            'nombre',
            'descripcion',
            'disponibilidad',
            [
            'attribute'=>'precio',
            'format'=>['decimal',2],
            ],

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '10'],

                'template' => '{view}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['paquete/view', 'id' => $model['codigo']]);
                    } 
                    else if ($action === 'delete') {

                    return \Yii::$app->getUrlManager()->createUrl(['paquete/delete', 'id' => $model['codigo']]);

                    }
                }

            ],
        ],
    ]); ?>

</div>
