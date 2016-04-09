<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Paquete */

$this->title = 'Editar Paquete: ' . ' ' . $model->codigo;

?>
<div class="paquete-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update', [
        'model' => $model,
        'model_productos'=> (empty($model_productos)) ? [new ProductoPaquete] : $model_productos,
    ]) ?>

</div>
