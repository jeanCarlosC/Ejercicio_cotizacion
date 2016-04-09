<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Paquete */

$this->title = $model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Paquetes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paquete-view">
<?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Paquetes', ['index'], ['class' => '']) ?>  
  <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->codigo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Estas seguro que deseas eliminar este paquete?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo',
            'nombre',
            'descripcion',
            'disponibilidad',
            'precio',
        ],
    ]) ?>

</div>
