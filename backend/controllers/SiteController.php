<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use common\models\User;
use yii\filters\VerbFilter;
use backend\models\SignupForm;
use backend\models\Cotizacion;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','signup','usuarios','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {   
        $val = strrchr("A",Yii::$app->user->identity->codigo);
        if($val!=="A")
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Acceso denegado.',
                        'title' => '¡Denegado!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
        return $this->redirect(Yii::$app->homeUrl);
        }
        
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                    Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Usuario Registrado exitosamente.',
                        'title' => '¡Registro exitoso!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                    
                    return $this->redirect(['site/usuarios']);
                
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionUsuarios()
    {
        $val = strrchr("A",Yii::$app->user->identity->codigo);
        if($val!=="A")
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Acceso denegado.',
                        'title' => '¡Denegado!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
        return $this->redirect(Yii::$app->homeUrl);
        }

        $query = User::find();
        /*$query->where(['LIKE', 'codigo', 'V']);*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pageSize' => 10,
            ],
        ]);



        return $this->render('usuarios', [
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionDelete($id)
    {
        $model_v = User::find()->where(['id'=>$id])->one(); 
        $cotizaciones = Cotizacion::find()->where(['vendedora_codigo'=>$model_v->codigo])->one();
        $usuario_administrador = User::find()->where(['id'=>$id])->andwhere(['codigo'=>'A-01'])->one();

        if(!empty($usuario_administrador))
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'El Administrador principal no puede ser eliminado.',
                        'title' => '¡Denegado!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect('usuarios');
        }

        if(!empty($cotizaciones))
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Esta vendedora ha registrado cotizaciones y no puede ser eliminada.',
                        'title' => '¡Error!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect('usuarios');
        }
        $model = $this->findModel($id);
        $model->delete();

        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Este usuario se ha eliminado con éxito.',
                        'title' => '¡Eliminado Exitoso!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
        return $this->redirect(['usuarios']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
