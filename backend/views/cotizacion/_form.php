<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\touchspin\TouchSpin;
use backend\models\Producto;
use backend\models\Paquete;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Cotizacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cotizacion-form">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'id' => 'dynamic-form',
    'method'=>'post',
    'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="row">

    <div class="col-md-6">
    <?= $form->field($model, 'cliente')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-1">
    <?= $form->field($model, 'tipo')->dropDownList([ 'V' => 'V', 'E' => 'E', 'J' => 'J' ])?>
    </div>
    <div class="col-md-5">
    <?= $form->field($model, 'ruc')->textInput(['maxlength' => true])->label('documento') ?>
    </div>

    </div>

    <div class="row">

    <div class="col-md-4">
    <?= $form->field($model, 'vendedora')->textInput(array('disabled'=>true, 'value'=>Yii::$app->user->identity->nombre)) ?>
    </div>
    <div class="col-md-2">
    <?= $form->field($model, 'vendedora_codigo')->textInput(array('disabled'=>true, 'value'=>Yii::$app->user->identity->codigo))?>
    </div>

    <div class="col-md-3">

    <?= $form->field($model, 'descuento')->widget(TouchSpin::classname(),[
    'pluginOptions' => ['postfix' => '%'],
    ]);?>
    </div>

    <div class="col-md-3">
    <?= $form->field($model, 'impuesto')->widget(TouchSpin::classname(),[
    'pluginOptions' => ['postfix' => '%']
    ]);?>
    </div>

    </div>



                <!-- ****************************Productos*********************************** -->

        <div class="row col-md-12">
        <div class="panel panel-default">
        <div class="panel-heading"><h4>Agregar Items</h4></div>
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
                
                'cantidad',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model_productos as $i => $model_productos1): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Items</h3>
                        <div class="pull-right">
                            <button type="button" class="add-item btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $model_productos1->isNewRecord) {
                                echo Html::activeHiddenInput($model_productos1, "[{$i}]codigo_general");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-6">
                            <?= $form->field($model_productos1, "[{$i}]codigo_general")->dropDownList(ArrayHelper::map($provider,'codigo','nombre'), ['prompt' => 'Seleccione el item..'])?>
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

        <div class="form-group col-md-12">
    <?= Html::submitButton('Generar', ['class' =>'btn btn-success']) ?>
    </div>


    </div>
<?php ActiveForm::end(); ?>




</div>
<?php

$js = <<<JS

$('form#dynamic-form').on('beforeSubmit', function(e) {
   var \$form = $(this);

   var r = confirm("¿Esta seguro de los datos?, luego de creada la cotización no podrá editarla.");
    if (r == true) {
        return true;
    } else {
        return false;
    }

});
JS;
 
$this->registerJs($js);
?>