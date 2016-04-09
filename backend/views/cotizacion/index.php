<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CotizacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cotizaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cotizacion-index">

    <p align="right" style="margin-top: 100px;">
        <?= Html::a('Generar Cotizacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel'=>[
        'type'=>'primary',
        'heading'=>'Cotizaciones',
        'pjax'=>true,
        ],
        'export'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'codigo',
            'descuento',
            'impuesto',
            'cliente',
            'ruc',

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '10'],

                'template' => '{view}{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {

                    if ($action === 'view') 
                    {
                    return \Yii::$app->getUrlManager()->createUrl(['cotizacion/reporte', 'id' => $model['id']]);
                    } 
                    else if ($action === 'delete') {

                    return \Yii::$app->getUrlManager()->createUrl(['cotizacion/delete', 'id' => $model['id']]);

                    }
                }

            ],
        ],
    ]); ?>

</div>
