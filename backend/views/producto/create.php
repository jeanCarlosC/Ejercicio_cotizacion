<?php

use yii\helpers\Html;
use yii\bootstrap\Button;



/* @var $this yii\web\View */
/* @var $model backend\models\Producto */

$this->title = 'Registrar Producto';
/*$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/
?>
<div class="producto-create row" style="margin-top: 100px;">

    <div class="col-md-6">
    <?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Productos', ['index'], ['class' => '']) ?>
    <h1><?= Html::encode($this->title) ?></h1>
    </div>

	<div class="col-md-6" align="right" style="padding-top: 25px; padding-right: 45px;">
	<?php echo Button::Widget(['label'=>'Registrar tipo','options'=>['class' => 'btn btn-success'],'id'=>'modalButton']);?>
	</div>


    <?= $this->render('_form', [
        'model' => $model,
        'model_tipo'=>$tipo_producto,
        'data'=>$data,
    ]) ?>

</div>
