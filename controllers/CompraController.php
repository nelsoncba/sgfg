<?php

namespace app\controllers;

use Yii;
use app\models\Compra;
use app\models\CompraSearch;
use app\models\DetalleCompra;
use app\models\Material;
use app\models\Almacen;
use app\models\AlmacenMaterial;
use yii\web\Controller;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;
use \yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * CompraController implements the CRUD actions for Compra model.
 */
class CompraController extends Controller
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
                        'actions'=>['index','create','delete'],
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN,
                            User::ROLE_ALMACEN
                        ],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Compra models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new CompraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Compra model.
     * @param integer $id
     * @param integer $proveedor_codigo
     * @return mixed
     */
    public function actionView($id, $proveedor_codigo)
    {   
        $request = Yii::$app->request;

        $modelCompra = $this->findModel($id, $proveedor_codigo); 
        $modelsDetalle = $modelCompra->detalleCompras;

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
             return $this->render('view', [
 
                    'modelCompra' => $modelCompra,
         
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new DetalleCompra] : $modelsDetalle
         
                ]);  
        }else{
            return $this->render('view', [
 
                    'modelCompra' => $modelCompra,
         
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new DetalleCompra] : $modelsDetalle
         
                ]); 
        }
    }

    /**
     * Creates a new Compra model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $searchModel = new CompraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        
        //die();
        $modelCompra = new Compra;
        $modelMaterial = new Material;

        $modelsCompra = [new DetalleCompra];

        if($request->isAjax){ 
                Yii::$app->response->format = Response::FORMAT_JSON;
                if($request->isGet){
                    return $this->render('create', [
                                'modelCompra' => $modelCompra,
                                'modelsDetalle' => $modelsDetalle,
                                'modelMaterial' => $modelMaterial
                                ]);
                } else {
                                
                    if ($modelCompra->load(Yii::$app->request->post())) {

                        $modelsDetalle = Model::createMultiple(DetalleCompra::classname());
                        Model::loadMultiple($modelsDetalle, Yii::$app->request->post());
                        
                        if(count(Yii::$app->request->post('DetalleCompra'))<1){
                            return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  Debe ingresar por lo menos un material</strong></p>'];
                        }
                        // ajax validation
                        //if (Yii::$app->request->isAjax) {
                            //Yii::$app->response->format = Response::FORMAT_JSON;
                            // return ArrayHelper::merge(
                            //     ActiveForm::validateMultiple($modelsDetalle),
                            //     ActiveForm::validate($modelPedido)
                            // );
                        //}

                        // validate all models
                        $valid = $modelCompra->validate();

                        $valid = Model::validateMultiple($modelsDetalle) && $valid;


                        foreach ($modelsDetalle as $modelDetalle) {
                           $validarStock = $modelDetalle->validarStock();
                          if(!$validarStock) 
                            return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  La cantidad debe ser menor o igual al stock</strong></p>'];
                        }
                        
                        if ($valid) { 
            
                            $transaction = \Yii::$app->db->beginTransaction();
                           
                            try {
                                //registro datos de pedido
                                if ($flag = $modelCompra->save()) {
                                  
                                    foreach ($modelsDetalle as $modelDetalle) {
                                        $modelDetalle->compra_id = $modelCompra->id;

                                        if (! ($flag = $modelDetalle->save())) {
                                            $transaction->rollBack();
                                            break;
                                        }

                                        //actualizo stock
                                        $material = Material::findOne(['id' => $modelDetalle->material_id]);
                                        $cantidadActual = $material->attributes['cantidadExistencia'];
                                        $cantidadExistencia = $cantidadActual + $modelDetalle->cantidad;
                                        $material->attributes= ['cantidadExistencia'=>$cantidadExistencia];

                                        if(!$flag = $material->save()){
                                            $transaction->rollBack();
                                            break;
                                        }
                                        //registro salida producto
                                        $modelAlmacen = new Almacen();
                                        $modelAlmacenMaterial = new AlmacenMaterial();
                                        $modelAlmacen->fecha = $modelCompra->fecha;
                                        $modelAlmacen->cantidad = $modelDetalle->cantidad;
                                        $modelAlmacen->movimiento = 'ENTRADA';
                                        $modelAlmacen->documento = $modelCompra->numeroFactura;
                                       
                                        if(!$flag = $modelAlmacen->save()){
                                            $transaction->rollBack();
                                            break;
                                        }
                                        $modelAlmacenMaterial->almacen_id = $modelAlmacen->id;
                                        $modelAlmacenMaterial->material_id = $modelDetalle->material_id;
                                        if(!$flag = $modelAlmacenMaterial->save()){
                                            $transaction->rollBack();
                                            break;
                                        }

                                    }
                                    
                                }
                               
                            
                            if ($flag) {
                                    $transaction->commit();//$transaction->commit();
                                    $message = 'Se registró compra<br>Comprobante: '.$modelCompra->numeroFactura;
                                    return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p>',
                                        'footer'=> Html::a('Cerrar',['index'],['class'=>'btn btn-default pull-right']));
                              } else {
                                $transaction->rollBack();
                                $message = 'error al registrar compra ';
                                return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                              }
                            } catch (Exception $e) {
                                $transaction->rollBack();
                                $message = 'error al registrar compra ';
                                return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                            }
                        }else{
                            return $this->renderAjax('create', [
                                'modelCompra' => $modelCompra,
                                'modelsDetalle' => $modelsDetalle,
                                'modelMaterial' => $modelMaterial
                                ]);
                        } 
                    } 
                }
        }else{
            return $this->render('create', [
                    'modelCompra' => $modelCompra,
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new DetalleCompra] : $modelsDetalle,
                    'modelMaterial' => $modelMaterial
                ]);
        }
       
    }

    /**
     * Updates an existing Compra model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $proveedor_codigo
     * @return mixed
     */
    public function actionUpdate($id, $proveedor_codigo)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id, $proveedor_codigo);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Compra #".$id, $proveedor_codigo,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Compra #".$id, $proveedor_codigo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id, $proveedor_codigo'=>$id, $proveedor_codigo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Compra #".$id, $proveedor_codigo,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'proveedor_codigo' => $model->proveedor_codigo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Compra model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $proveedor_codigo
     * @return mixed
     */
    public function actionDelete($id, $proveedor_codigo,$accion)
    {
        $request = Yii::$app->request;
        $modelCompra = $this->findModel($id, $proveedor_codigo);

        if($request->isAjax){
            if($modelCompra->estado != 'ANULADO'){
            

             Yii::$app->response->format = Response::FORMAT_JSON;
            if($accion=='confirmar'){

                return  [   
                            'data-confirm-title'=>'Confirmacion',
                            'content'=>'<span class="text-success">¿Confirma la anulacion de la compra?</span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Anular',['delete','id'=>$id,'proveedor_codigo'=>$proveedor_codigo,'accion'=>'anular'],['class'=>'btn btn-primary','role'=>'modal-remote','data-request-method'=>'post','data-title'=>'Confirmacion'])
                        ];

            }
            if($accion=='anular'){

                $modelCompra->load($request->post());

                $transaction = \Yii::$app->db->beginTransaction();
         
                    $modelCompra->estado = 'ANULADO';
                    if($modelCompra->save()){
                        $transaction->commit();
                        $message = 'Se ha anulado la compra ingresada';
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Confirmacion",
                            'content'=>'<span class="text-success"><strong>'.$message.'</strong></span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
                        ];  
                       
                        // return ['forceClose'=>true,
                        //         'forceReload'=>'#crud-datatable-pjax'];
                    }else{
                        $transaction->rollBack();
                        $message = 'Error al anular compra';
                         return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Confirmacion",
                            'content'=>'<span class="text-danger"><strong>'.$message.'</strong></span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];  
                    }
                
            }

            /*
            *   Process for ajax request
            */
            }else{
                Yii::$app->response->format = Response::FORMAT_JSON;
                    $message = 'La compra se encuentra en estado '.$modelCompra->estado;
                    return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Confirmacion",
                    'content'=>'<span class="text-success">'.$message.'</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
        
                    ];  
                    
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Compra model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $proveedor_codigo
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
     * Finds the Compra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $proveedor_codigo
     * @return Compra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $proveedor_codigo)
    {
        if (($model = Compra::findOne(['id' => $id, 'proveedor_codigo' => $proveedor_codigo])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
