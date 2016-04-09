<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Cotizacion */

$this->title = 'Generar Cotizacion';
$this->params['breadcrumbs'][] = ['label' => 'Cotizacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cotizacion-create">
<?= Html::a('<span class="glyphicon glyphicon-chevron-left"></span> Cotizaciones', ['index'], ['class' => '','style'=>'padding-left:15px']) ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_productos'=> (empty($model_productos)) ? [new CotizacionProductos] : $model_productos,
        'provider'=>$provider,
    ]) ?>

</div>
