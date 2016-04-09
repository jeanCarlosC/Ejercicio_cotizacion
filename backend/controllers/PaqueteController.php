<?php

namespace backend\controllers;

use Yii;
use backend\models\Paquete;
use backend\models\ProductoPaquete;
use backend\models\Producto;
use backend\models\PaqueteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Response;


/**
 * PaqueteController implements the CRUD actions for Paquete model.
 */
class PaqueteController extends Controller
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
                        'actions' => ['logout', 'index','delete','view','create'],
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
     * Lists all Paquete models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaqueteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $productos = Producto::find()->all();

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

        if(empty($productos))
        {
        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Debe agregar productos para Crear paquetes.',
                        'title' => '¡No hay productos!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
        return $this->redirect(Yii::$app->homeUrl);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paquete model.
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
     * Creates a new Paquete model.
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

        $productos = Producto::find()->all();
        if(empty($productos))
        {
        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Debe agregar productos para Crear paquetes.',
                        'title' => '¡No hay productos!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
        return $this->redirect(Yii::$app->homeUrl);
        }

        $model = new Paquete();
        $model_productos=[new ProductoPaquete];

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model_productos = Model::createMultiple(ProductoPaquete::classname());
            Model::loadMultiple($model_productos, Yii::$app->request->post());
            foreach ($model_productos as $key => $value) {

                $model_productos[$key]['d']=$_POST['Paquete']['disponibilidad'];
            }
            $model->attributes=$_POST['Paquete'];
             Yii::$app->response->format = 'json';
            return ArrayHelper::merge(
                    ActiveForm::validateMultiple($model_productos),
                    ActiveForm::validate($model)
                );
        }



        if ($model->load(Yii::$app->request->post())) {

            $model_productos = Model::createMultiple(ProductoPaquete::classname());
            Model::loadMultiple($model_productos, Yii::$app->request->post());
            $model->attributes=$_POST['Paquete'];
            
            
            

            $valid = $model->validate();

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {

                        foreach ($model_productos as $model_productos1) 
                        {
                            $model_productos1->paquete_codigo= $model->codigo;
                            
                            $model_producto_d = $this->findModel_producto($model_productos1->producto_codigo);
                            $model_producto_d->disponibilidad-=$model_productos1->cantidad*$model->disponibilidad;
                            $model_producto_d->save(false);

                            if (! ($flag = $model_productos1->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'success',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Paquete Registrado exitosamente.',
                        'title' => '¡Registro exitoso!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
                        return $this->redirect('index');
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
            

        } else {
            return $this->render('create', [
                'model' => $model,
                'model_productos'=> (empty($model_productos)) ? [new ProductoPaquete] : $model_productos,
            ]);
        }
    }

    /**
     * Updates an existing Paquete model.
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
        $model_productos = $model->productos;


        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model_productos = Model::createMultiple(ProductoPaquete::classname());
            Model::loadMultiple($model_productos, Yii::$app->request->post());
            foreach ($model_productos as $key => $value) {

                $model_productos[$key]['d']=$_POST['Paquete']['disponibilidad'];

            }

            $model->attributes=$_POST['Paquete'];
             Yii::$app->response->format = 'json';
            return ArrayHelper::merge(
                    ActiveForm::validateMultiple($model_productos),
                    ActiveForm::validate($model)
                );
        }

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($model_productos, 'producto_codigo', 'producto_codigo');
            $oldCantidades = ArrayHelper::map($model_productos, 'producto_codigo', 'cantidad');
            $model_productos = Model::createMultiple(ProductoPaquete::classname(), $model_productos);
            Model::loadMultiple($model_productos, Yii::$app->request->post());
/*            echo "<pre>";
            print_r($oldCantidades);
            echo "</pre>";
            yii::$app->end();*/

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($model_productos, 'producto_codigo', 'producto_codigo')));

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($model_productos),
                    ActiveForm::validate($model)
                );
            }

            foreach ($model_productos as $key => $value) {

                $model_productos[$key]['d']=$_POST['Paquete']['disponibilidad'];

            }

            $valid = $model->validate();

                if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                if ($flag = $model->save(false)) {

                if (! empty($deletedIDs)) {

                    
                    foreach ($deletedIDs as $model_p) {
                      
                    $model_producto_sumar = $this->findModel_producto($model_p);
                    $model_paquete_viejo = $this->findModel($id);
                    $cantidad_buscada = ProductoPaquete::find()->where(['paquete_codigo'=>$id])->andwhere(['producto_codigo'=>$model_p])->one();
                    $model_producto_sumar->disponibilidad+=$cantidad_buscada->cantidad*$model_paquete_viejo->disponibilidad;
                    $model_producto_sumar->save(false);

                    }

                    ProductoPaquete::deleteAll(['producto_codigo' => $deletedIDs]);

                }

                foreach ($model_productos as $model_productos1) {
                    $model_productos1->paquete_codigo = $model->codigo;
                    /*$model_paquete_viejo = $this->findModel($id);*/
                    /********************Restar de la disponibilidad del producto********************/
                    foreach ($oldIDs as $value) {
                        if($value!==$model_productos1->producto_codigo)
                        {
                            $model_producto_d = $this->findModel_producto($model_productos1->producto_codigo);
                            $model_producto_d->disponibilidad-=$model_productos1->cantidad * $model->disponibilidad;
                            $model_producto_d->save(false);
                        }
                    }
                    
 
                    /*********************FIN*********************************************************+*/

                    if (! ($flag = $model_productos1->save(false))) {
                        $transaction->rollBack();
                        break;
                    }
                }
                }
                if ($flag) {
                $transaction->commit();
                Yii::$app->getSession()->setFlash('notificacion-error', [
                'type' => 'success',
                'duration' => 5000,
                'icon' => 'glyphicon glyphicon-error-sign',
                'message' => 'El Animal fue Actualizado exitosamente',
                'title' => '¡Actualizacion exitosa!',
                'positonY' => 'top',
                'positonX' => 'right'
                ]);
                return $this->redirect(['index']);
                }
                } catch (Exception $e) {
                $transaction->rollBack();
                }
                }

        } else {
            return $this->render('update', [
                'model' => $model,
                'model_productos'=> (empty($model_productos)) ? [new ProductoPaquete] : $model_productos,
            ]);
        }
    }

    /**
     * Deletes an existing Paquete model.
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

        $productos = ProductoPaquete::find()->where(['paquete_codigo'=>$id])->all();
        $paquete_eliminado = Paquete::find()->where(['codigo'=>$id])->one();
        foreach ($productos as $productos1) {
            $pro = $this->findModel_producto($productos1->producto_codigo);
            $pro->disponibilidad += $productos1->cantidad * $paquete_eliminado->disponibilidad;
            $pro->save(false);
        }
        
        ProductoPaquete::deleteAll(['paquete_codigo' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Paquete model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Paquete the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Paquete::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel_producto($id)
    {
        if (($model = Producto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('el producto no existe.');
        }
    }
}
