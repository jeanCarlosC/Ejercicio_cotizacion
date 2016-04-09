<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">


    <div class="body-content">
    <br>
    <!-- strpos($cadena_de_texto, $cadena_buscada) -->
    <?php $val = strrchr("A",Yii::$app->user->identity->codigo); 
    ?>
     <?php if($val=="A"): ?>
        <div class="row" style=" text-align : center;">
            <div class="btn btn-default " style="width: 28%;" onclick="location.href='<?= Url::toRoute('site/usuarios/')?>'">
                <span class="glyphicon glyphicon-user" style="font-size: 30px; padding: 10px 5px;"></span>
                <p class="letra">Usuarios</p>

              <!--   <p>Lorem </p> -->

                
            </div>
            <div class="btn btn-default" style=" width: 28%; margin-left: 30px;" onclick="location.href='<?= Url::toRoute('/producto/')?>'">
            <span class="glyphicon glyphicon-barcode" style="font-size: 30px; padding: 10px 5px;"></span>
                <p class="letra">Productos</p>
               <!--  <p>Agregar, editar y eliminar </p> -->
            

                
            </div>
            <div class="btn btn-default" style="width: 28%; margin-left: 30px;" onclick="location.href='<?= Url::toRoute('/paquete/')?>'">
            <span class="glyphicon glyphicon-tasks" style="font-size: 30px; padding: 10px 5px;"></span>
                <p class="letra">Paquetes</p>
            <!--     <p>Agregar y Eliminar </p> -->

                
            </div>
        </div>
    <?php endif; ?>


     <?php if($val!=="A"): ?>
        <div class="row" style=" text-align : center;">
            <div class="btn btn-default " style="width: 28%;" onclick="location.href='<?= Url::toRoute('/cotizacion/')?>'">
                <span class="glyphicon glyphicon-list-alt" style="font-size: 30px; padding: 10px 5px;"></span>
                <p class="letra">Cotización</p>
                

                <!-- <p>Lorem </p> -->

                
            </div>
        </div>
    <?php endif; ?>

    </div>
</div>
