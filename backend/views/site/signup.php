<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\widgets\MaskedInput;

$this->title = 'Registrar Usuario';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
<?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Usuarios', ['usuarios'], ['class' => '','style'=>'padding-left:15px']) ?>
    <h1 style="margin-top: 50px;"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'tipo_usuario')->dropDownList([ 'A' => 'Administrador', 'V' => 'Vendedora', ], ['prompt' => 'Seleccione el tipo de usuario..'])?>

                <?= $form->field($model, 'nombre') ?> 

                <?= $form->field($model, 'email')->widget(MaskedInput::classname(),[
                'clientOptions' => [
                'alias' =>  'email'
                ],
                ]);?>

                <?= $form->field($model, 'username') ?> 

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Registrar', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php

$js = <<<JS

$(document).ready(function(){

    $("#signupform-nombre").change( function(){
    var str = $("#signupform-nombre").val();
    var res = str.split(" ");
    if(res[0]!=undefined && res[1]!=undefined){
    var n = res[0].concat(".",res[1]);
    document.getElementById("signupform-username").value = n;
    }
    console.log(n);
    
});

});
JS;
 
$this->registerJs($js);
?>

