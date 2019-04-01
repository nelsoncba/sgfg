<?php

namespace app\controllers;

use Yii;
use app\models\AlmacenProducto;
use app\models\AlmacenProductoSearch;
use app\models\Producto;
use app\models\Almacen;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * AlmacenProductoController implements the CRUD actions for AlmacenProducto model.
 */
class AlmacenProductoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'ruleConfig' => [
                    'class' => components\AccessRule::className(),
                ],
                'only' => ['index','create','update','delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'actions'=>['index','create'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ALMACEN,
                            User::ROLE_ADMIN
                        ],
                    ],
                    // everything else is denied
                ],
            ],  
        ];
    }

    /**
     * Lists all AlmacenProducto models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new AlmacenProductoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single AlmacenProducto model.
     * @param integer $id
     * @param integer $almacen_id
     * @param integer $producto_id
     * @return mixed
     */
    public function actionView($id, $almacen_id, $producto_id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Almacen Producto ",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id, $almacen_id, $producto_id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id, $almacen_id, $producto_id),
            ]);
        }
    }

    /**
     * Creates a new AlmacenProducto model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        //var_dump($request->post());die();
        $model = new AlmacenProducto();
        $model_producto = new Producto();
        $model_almacen = new Almacen();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ingresar Producto al Almacén",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model, 'model_producto' => $model_producto,
                        'model_almacen' => $model_almacen
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->validate() && $model_almacen->load($request->post()) && $model_almacen->validate()){
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        
                        $model_almacen->save();
                        $model->almacen_id = $model_almacen->id;
                        $model->save();
                        $producto = Producto::findOne(['id' => $model->producto_id]);

                        $cantidadActual = $producto->attributes['cantidadExistencia'];
                        $cantidadExistencia = $cantidadActual + $model_almacen->cantidad;
                                   
                        $producto->attributes= ['cantidadExistencia'=>$cantidadExistencia];
                        $producto->save();
                        $transaction->commit();
                        $message = '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> Producto ingresado correctamente</strong></p>';
                    } catch (Exception $e) {
                        $transaction->rollback();
                        $message = '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> Error al ingresar producto</strong></p> <br>';
                        $message .= "<pre>".$e."</pre>";
                    }

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Ingreso Almacen Producto",
                    'content'=>'<span class="text-success">'.$message.'</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            
        
                ];         
            }else{           
                return [
                    'title'=> "Create new AlmacenProducto",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model, 'model_producto' => $model_producto,
                        'model_almacen' => $model_almacen
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'almacen_id' => $model->almacen_id, 'producto_id' => $model->producto_id]);
            } else {
                return $this->render('create', [
                    'model' => $model, 'model_producto' => $model_producto,
                        'model_almacen' => $model_almacen
                ]);
            }
        }
       
    }

    /**
     * Updates an existing AlmacenProducto model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $almacen_id
     * @param integer $producto_id
     * @return mixed
     */
    public function actionUpdate($id, $almacen_id, $producto_id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id, $almacen_id, $producto_id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update AlmacenProducto #".$id, $almacen_id, $producto_id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "AlmacenProducto #".$id, $almacen_id, $producto_id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id, $almacen_id, $producto_id'=>$id, $almacen_id, $producto_id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update AlmacenProducto #".$id, $almacen_id, $producto_id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'almacen_id' => $model->almacen_id, 'producto_id' => $model->producto_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing AlmacenProducto model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $almacen_id
     * @param integer $producto_id
     * @return mixed
     */
    public function actionDelete($id, $almacen_id, $producto_id)
    {
        $request = Yii::$app->request;
        $this->findModel($id, $almacen_id, $producto_id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing AlmacenProducto model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $almacen_id
     * @param integer $producto_id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the AlmacenProducto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $almacen_id
     * @param integer $producto_id
     * @return AlmacenProducto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $almacen_id, $producto_id)
    {
        if (($model = AlmacenProducto::findOne(['id' => $id, 'almacen_id' => $almacen_id, 'producto_id' => $producto_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
