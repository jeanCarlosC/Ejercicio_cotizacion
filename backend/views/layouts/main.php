<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use yii\web\Session;
use kartik\growl\Growl;


AppAsset::register($this);
$user_id = Yii::$app->user->id;
/*$session = Yii::app()->user->id;*/

/*echo "<pre>";
print_r(Yii::$app->user->id);
echo "</pre>";
yii::$app->end();*/
$this->title= "Valor Technology";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
<?php if($this->context->route == "site/index"): ?>

    <div class="baner">  
    <div align="center" style="color: white;">
  <!--   <br><br><br>
    <p style="font-size: 40px;">COTIZADOR</p>
 -->    </div>
    </div>


<?php endif; ?>
        <?php
    NavBar::begin([
        'brandLabel' => 'VT',
        'brandUrl' => '#',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
       
        [
        'label' => "Inicio",
         'url' => Yii::$app->homeUrl,
         'visible' => $this->context->route != "site/index",
        ],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Iniciar sesión', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Cerrar Sesión (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
                <!-- ***************************** mensajeeeess ********************************** -->


        <?php foreach (Yii::$app->session->getAllFlashes() as $message): ; ?>
            <?php
            echo Growl::widget([
                'type' => (!empty ($message['type'])) ? $message['type']
                    : 'danger',
                'title' => (!empty ($message['title'])) ? Html::encode($message['title'])
                    : 'Title Not Set!',
                'icon' => (!empty ($message['icon'])) ? $message['icon']
                    : 'fa fa-info',
                'body' => (!empty ($message['message'])) ? Html::encode($message['message'])
                    : 'Message Not Set!',
                'showSeparator' => true,
                'delay' => 1, //This delay is how long before the message shows
                'pluginOptions' => [
                    'showProgressbar' => true,
                    'delay' => (!empty ($message['duration'])) ? $message['duration']
                        : 3000, //This delay is how long the message shows for
                    'placement' => [
                        'from' => (!empty ($message['positonY'])) ? $message['positonY']
                            : 'top',
                        'align' => (!empty ($message['positonX'])) ? $message['positonX']
                            : 'right',
                    ]
                ]
            ]);
            ?>
        <?php endforeach; ?>

        <!-- ***************************** Fin mensajes ********************************** -->
        <?= $content ?>
    </div>



</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Cotizador Valor Technology <?= date('Y') ?></p>

         <p class="pull-right"><?= Yii::powered() ?></p> 
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
