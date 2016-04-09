<?php

namespace backend\controllers;

use Yii;
use backend\models\Cotizacion;
use backend\models\Paquete;
use backend\models\Producto;
use backend\models\CotizacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\CotizacionProductos;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\AccessControl;

/**
 * CotizacionController implements the CRUD actions for Cotizacion model.
 */
class CotizacionController extends Controller
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
                        'actions' => ['logout', 'index','delete','view','create','reporte'],
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
     * Lists all Cotizacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CotizacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        

/*            echo "<pre>";
            print_r($provider);
            echo "</pre>";
            yii::$app->end();*/

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            
        ]);
    }

    /**
     * Displays a single Cotizacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cotizacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cotizacion();
        $model_productos = [new CotizacionProductos];

        $query1 = (new \yii\db\Query())
        ->select("codigo, nombre")
        ->from('producto');

        $query2 = (new \yii\db\Query())
        ->select("codigo, nombre")
        ->from('paquete');
        $unionQuery = (new \yii\db\Query());
        $unionQuery = $query1->union($query2)->all();

        $productos_existentes = Producto::find()->count();
        if($productos_existentes==0)
        {
            Yii::$app->getSession()->setFlash('notificacion-error', [
                        'type' => 'danger',
                        'duration' => 5000,
                        'icon' => 'glyphicon glyphicon-error-sign',
                        'message' => 'Debe existir productos para poder generar una cotización.',
                        'title' => '¡No existen productos!',
                        'positonY' => 'top',
                        'positonX' => 'right'
                        ]);
        return $this->redirect(['index']);
        }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            $model_productos = Model::createMultiple(CotizacionProductos::classname());
            Model::loadMultiple($model_productos, Yii::$app->request->post());
            $model->attributes=$_POST['Cotizacion'];
             Yii::$app->response->format = 'json';
            return ArrayHelper::merge(
                    ActiveForm::validateMultiple($model_productos),
                    ActiveForm::validate($model)
                );

        }

            

        if ($model->load(Yii::$app->request->post())) {

            $model_productos = Model::createMultiple(CotizacionProductos::classname());
            Model::loadMultiple($model_productos, Yii::$app->request->post());

            $model->attributes=$_POST['Cotizacion'];
            $model->impuesto = $_POST['Cotizacion']['impuesto'];
            $model->descuento = $_POST['Cotizacion']['descuento'];
            $model->ruc = $_POST['Cotizacion']['tipo']."-".$model->ruc;
            $model->vendedora_codigo = Yii::$app->user->identity->codigo;
            $model->fecha = date("d/m/Y");

            $ultima_cotizacion = Cotizacion::find()->orderby(['id'=>SORT_DESC])->one();
            if(!empty($ultima_cotizacion)){
            $codigo = explode('-', $ultima_cotizacion->codigo);
            $cod="C-".sprintf("%'.04d", $codigo[1]+1);
            
            }
            else
            {
                $cod="C-0001";
            }
            $model->codigo = $cod;



           $valid = $model->validate();


            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {

                        foreach ($model_productos as $model_productos1) 
                        {


                            $model_productos1->cotizacion_id= $model->id;
                            $paquete = Paquete::find()->where(['codigo'=>$model_productos1->codigo_general])->one();
                            $producto = Producto::find()->where(['codigo'=>$model_productos1->codigo_general])->one();

                           

                            if(!empty($paquete))
                            {
                                $model_productos1->paquete_codigo = $model_productos1->codigo_general;
                            }
                            elseif(!empty($producto))
                            {
                                $model_productos1->producto_codigo = $model_productos1->codigo_general;
                            }
/*
                            echo "<pre>";

                            print_r($model_productos1);
                            echo "</pre>";
                            yii::$app->end();*/



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
                        'message' => 'Cotizacion Registrado exitosamente.',
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
                'model_productos'=> (empty($model_productos)) ? [new CotizacionProductos] : $model_productos,
                'provider'=> $unionQuery,
            ]);
        }
    }

    /**
     * Updates an existing Cotizacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Cotizacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        CotizacionProductos::deleteAll(['cotizacion_id' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionReporte($id)
    {
        $query = CotizacionProductos::find()->where(['cotizacion_id'=>$id]);
        $model = Cotizacion::find()->where(['id'=>$id])->one();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('reporte',[
            'dataProvider'=>$dataProvider,
            'model'=>$model,
            ]);
    }

    /**
     * Finds the Cotizacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cotizacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cotizacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
