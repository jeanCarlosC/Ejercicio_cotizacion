<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
?>
<div class="producto-index">


    <p align="right" style="margin-top: 100px;">
        <?= Html::a('Registrar Usuario', ['signup'], ['class' => 'btn btn-success']) ?>
        
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'export'=>false,
        'pjax'=>true,
        'panel'=>[
        'type'=>'primary',
        'heading'=>'Usuarios'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            'attribute'=>'codigo',
            'format'=>'text',
            'label'=>'Código',
            ],
            [
            'attribute'=>'username',
            'format'=>'text',
            'label'=>'Nombre de Usuario',
            ],
            [
            'attribute'=>'nombre',
            'format'=>'text',
            'label'=>'Nombre y Apellido',
            ],
            [
            'attribute'=>'email',
            'format'=>'text',
            'label'=>'Correo electrónico',
            ],

            [
                'class' => 'yii\grid\ActionColumn',

                'header'=>'Opciones',

                'headerOptions' => ['width' => '10'],

                'template' => '{delete}',
                'urlCreator' => function ($action, $model, $key, $index) 
                {


                    if ($action === 'delete') {

                    return \Yii::$app->getUrlManager()->createUrl(['site/delete', 'id' => $model['id']]);

                    }
                }

            ],
        ],
    ]); ?>

</div>
