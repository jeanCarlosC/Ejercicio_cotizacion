<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Producto */

$this->title = 'Editar Producto: ' . ' ' . $model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Productos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->codigo, 'url' => ['view', 'id' => $model->codigo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="producto-update">
<?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Productos', ['index'], ['class' => '','style'=>'padding-left:15px']) ?>
    <h1 style="margin-left: 15px"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update', [
        'model' => $model,
        'model_tipo'=>$model_tipo,
        'data'=>$data,
    ]) ?>

</div>
