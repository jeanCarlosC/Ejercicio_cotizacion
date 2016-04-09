<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CotizacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cotizacion: '.$model->codigo;
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<div class="cotizacion-index">
    <?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Cotizaciones', ['index'], ['class' => '']) ?>

    <h1><?= Html::encode($this->title) ?></h1>
    <h4><?php echo "Vendedora: ".$model->vendedora_codigo?></h4>
    <h4 align="right">** Precios con descuento(<?php echo $model->descuento."%";?>) e impuesto(<?php echo $model->impuesto."%";?>) incluido.</h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'kvgrid',
        'autoXlFormat'=>true,
        'export'=>[
        'fontAwesome'=>true,
        'showConfirmAlert'=>false,
        'target'=>GridView::TARGET_BLANK,
        'pjax'=>true,
        ],
        'exportConfig' => [
        GridView::EXCEL  => [
        'label' => 'Excel',
        'icon' => 'file-excel-o',
        'iconOptions' => ['class' => 'text-success'],
        'showHeader' => true,
        'showPageSummary' => true,
        'showFooter' => true,
        'showCaption' => true,
        'filename' => 'Cotizacion '.$model->codigo,
        'options' => ['title' => 'Microsoft Excel 95+'],
        ],
        ],
        'showPageSummary'=>true,
        'pjax'=>true,
        'panel'=>[
        'type'=>'primary',
        'heading'=>'Productos'
        ],
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/
            [
            'attribute' => 'codigo_general',
            'value' => 'producto.cod',
            'format'=>'text',
            'pageSummary'=>'Total',
            ],
            [
            'attribute'=>'nom',
            'value'=>'producto.nombre',
            'format'=>'text',
            'label'=>'Nombre Producto',
            ],
            [
             'attribute' => 'cantidad',
             'format'=>'integer',
            ],
            [
            'attribute' => 'precio',
            'value' => 'producto.precio',
            'format'=>['decimal', 2],
            'label'=>'Precio por Und',

            ],
            [
            'attribute' => 'precio_D_I',
            'value' => 'producto.precio_D_I',
            'format'=>['decimal', 2],
            'label'=>'Precio**',

            ],

            [
            'class'=>'kartik\grid\FormulaColumn',
            'attribute' => 'precio2',
            'label'  => 'Monto',
            'format' =>['decimal', 2],
            'value' =>function ($model, $key, $index, $widget){
                $p = compact('model','key','index');
                return $widget->col(2,$p) * $widget->col(4,$p);
            },
            'pageSummary'=>true,
            ],
        ],
    ]); ?>

</div>
