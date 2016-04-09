<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \yii\widgets\MaskedInput;
use kartik\money\MaskMoney;


/* @var $this yii\web\View */
/* @var $model backend\models\Producto */
/* @var $form yii\widgets\ActiveForm */
?>

<?php 
Modal::begin([
'header'=> '<p class="titulomodal">Registrar tipo de productos</p>',
'id'=> 'modal',
'size'=> 'modal-md',
]); ?>

<div class="index-modal row">
<?php $form = ActiveForm::begin([
            'enableAjaxValidation'=>true,
            'id' => 'form',
            'method'=>'post',
            'action'=>['tipo'],
            'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <div class="col-md-6"><?= $form->field($model_tipo, 'nombre')->textInput() ?></div>

            <div class="col-md-6"><?= $form->field($model_tipo, 'descripcion')->textInput() ?></div>

            <div class="col-md-12">
            <?= Html::submitButton('Aceptar', ['class' =>'btn btn-success','id'=>'bm']) ?>
            </div>

<?php $form = ActiveForm::end(); ?> 
</div>

<?php 
Modal::end();
?>

<div class="producto-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

<div class="row col-md-12">

    <div class="col-md-4">
        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
    </div>

</div>

<div class="row col-md-12">

 
    <div class="col-md-4">
    <?= $form->field($model, 'precio')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-4">
    <?= $form->field($model, 'disponibilidad')->widget(MaskedInput::classname(),[
    'mask' => '9',
    'clientOptions' => ['repeat' => 10, 'greedy' => false]
    ]);?>
    </div>

    <div class="col-md-4">
    <?php echo $form->field($model, 'tipo_producto_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map($data,'id','nombre'),
    'options' => ['placeholder' => 'Seleccina un tipo ...'],
    'pluginOptions' => [
    'allowClear' => true
    ],
    ]);
    ?>
    </div>

</div>
    <div class="form-group col-md-12">
        <?= Html::submitButton('Editar', ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<< JS
$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
});
});
JS;
$this->registerJs($script);
?>