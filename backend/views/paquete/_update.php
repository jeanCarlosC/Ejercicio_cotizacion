<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use wbraganca\dynamicform\DynamicFormWidget;
use backend\models\Producto;
use \yii\widgets\MaskedInput;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model backend\models\Paquete */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paquete-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'dynamic-form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>
    <div class="row col-md-12">
    <div class=" col-md-6">
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
	</div>

	<div class=" col-md-6">
    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>
	</div>

	</div>

	<div class="row col-md-12">

	<div class=" col-md-4">
    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
	</div>

    <div class="col-md-4">
    <?= $form->field($model, 'disponibilidad')->widget(MaskedInput::classname(),[
    'mask' => '9',
    'clientOptions' => ['repeat' => 10, 'greedy' => false]
    ]);?>
    </div>

        <div class="col-md-4">
    <?= $form->field($model, 'precio')->textInput(['maxlength' => true]) ?>
    </div>

    </div>


            <div class='row col-md-12'>
        <div class="col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading"><h4>Productos del paquete</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 10, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $model_productos[0],
                'formId' => 'dynamic-form',
                'formFields' => [
				'producto_id',
				'cantidad',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model_productos as $i => $model_productos1): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Productos</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $model_productos1->isNewRecord) {
                                echo Html::activeHiddenInput($model_productos1, "[{$i}]producto_codigo");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                            <?= $form->field($model_productos1, "[{$i}]producto_codigo")->dropDownList(ArrayHelper::map(Producto::find()->all(),'codigo','nombre'), ['prompt' => 'Seleccione el producto..'])?>
							<?php /*echo $form->field($model_productos1, "[{$i}]producto_id")->widget(Select2::classname(), [
							'data' => ArrayHelper::map(Producto::find()->all(),'id','nombre'),
							'options' => ['placeholder' => 'Seleccina un tipo ...'],
							'pluginOptions' => [
							'allowClear' => true
							],
							]);*/
							?>

                               
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model_productos1, "[{$i}]cantidad")->textInput(['maxlength' => true]) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
        </div>
    
    </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Agregar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
