<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Productos';
?>
<div class="producto-index">
<h4>Productos</h4>
<br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'producto_codigo',
            [
            'attribute'=>'nom',
            'value'=>'nombre'
            ],
            'cantidad',

        ],
    ]); ?>

</div>
