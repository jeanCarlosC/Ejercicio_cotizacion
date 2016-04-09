<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Paquete */

$this->title = 'Agregar paquete';
?>
<div class="paquete-create" style="padding-top: 50px">
<?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Paquetes', ['index'], ['class' => '','style'=>'padding-left:15px']) ?>
    <h1 style="padding-left: 15px"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_productos'=> (empty($model_productos)) ? [new ProductoPaquete] : $model_productos,
    ]) ?>

</div>
