<?php

namespace backend\controllers;

use Yii;
use backend\models\Producto;
use backend\models\TipoProducto;
use backend\models\ProductoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;

/**
 * ProductoController implements the CRUD actions for Producto model.
 */
class ProductoController extends Controller
{
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
                        'actions' => ['logout', 'index','delete','update','view','create','tipo'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Producto models.
     * @return mixed
     */
    public function actionIndex()
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

        $searchModel = new ProductoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Producto model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
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

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Producto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
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

        $model = new Producto();
        $model_tipo = new TipoProducto();

        $tipos = TipoProducto::find()->all();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
            {
                $model->attributes = $_POST['Producto'];// asigna los valores al modelo animal

                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

        if ($model->load(Yii::$app->request->post())) {
            $model->attributes = $_POST['Producto'];
            $model->save();
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Producto Registrado exitosamente.',
                        'title' => '¡Registro exitoso!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tipo_producto'=>$model_tipo,
                'data'=>$tipos,
            ]);
        }
    }

    public function actionTipo()
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

        $model = new TipoProducto();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
            {
                $model->attributes = $_POST['TipoProducto'];// asigna los valores al modelo animal

                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Tipo de producto Registrado exitosamente.',
                        'title' => '¡Registro exitoso!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
            return $this->redirect(['create']);
        }
    }

    /**
     * Updates an existing Producto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
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

        $model = $this->findModel($id);
        $model_tipo = new TipoProducto();
        $tipos = TipoProducto::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Producto Editado exitosamente.',
                        'title' => '¡Actualizacion exitosa!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
            return $this->redirect(['view', 'id' => $model->codigo]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'model_tipo'=>$model_tipo,
                'data'=>$tipos,
            ]);
        }
    }

    /**
     * Deletes an existing Producto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
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

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Producto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Producto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Producto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
