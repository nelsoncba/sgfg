<?php

namespace app\controllers;

use Yii;
use app\models\Pedido;
use app\models\VentaSearch;
use app\models\DetallePedido;
use app\models\Producto;
use app\models\Almacen;
use app\models\AlmacenProducto;
use app\models\Venta;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;
use \yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * VentaController implements the CRUD actions for Venta model.
 */
class VentaController extends Controller
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
                            User::ROLE_ADMIN
                        ],
                    ],
                    // everything else is denied
                ],
            ],    
        ];
    }

    /**
     * Lists all Venta models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new VentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Venta model.
     * @param integer $id
     * @param integer $pedido_id
     * @return mixed
     */
    public function actionView($id, $pedido_id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Venta #".$id, $pedido_id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id, $pedido_id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id, $pedido_id'=>$id, $pedido_id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id, $pedido_id),
            ]);
        }
    }

    public function actionImprimir(){
        //var_dump(Yii::$app->request->get('codigo'));die();
    // setup kartik\mpdf\Pdf component
    $pdf = new Pdf([
        // set to use core fonts only
        'mode' => Pdf::MODE_UTF8, 
        // A4 paper format
        'format' => Pdf::FORMAT_A4, 
        // portrait orientation
      //  'orientation' => Pdf::ORIENT_PORTRAIT, 
        // stream to browser inline
        'destination' => Pdf::DEST_BROWSER, 
        // your html content input
        'content' => $this->renderPartial('comprobante',['codigo'=>Yii::$app->request->get('codigo')]),  
        // format content from your own css file if needed or use the
        // enhanced bootstrap css built by Krajee for mPDF formatting 
     //   'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        // any css to be embedded if required
     //   'cssInline' => '.kv-heading-1{font-size:18px}', 
         // set mPDF properties on the fly
        'options' => ['title' => 'Krajee Report Title'],
         // call mPDF methods on the fly
        'methods' => [ 
        ]
    ]);
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/pdf');
        return $pdf->render();
    }
    /**
     * Creates a new Venta model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $searchModel = new VentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        //var_dump($request);
        $modelPedido = new Pedido;
        $modelProducto = new Producto;
        $modelVenta = new Venta;

        $codigo = 0;
        $modelPedido->load(['Pedido'=>[
                                'fechaPedido'=>date("Y-m-d"),
                                'codigo'=> str_pad($codigo, 8, "0", STR_PAD_LEFT)
                                ]
                           ]);

        $modelsDetalle = [new DetallePedido];

        if($request->isAjax){ 
                Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isGet){
            return $this->render('create', [
                        'modelPedido' => $modelPedido,
                        'modelsDetalle' => $modelsDetalle,
                        'modelProducto' => $modelProducto
                        ]);
        } else {
                        
            if ($modelPedido->load(Yii::$app->request->post())) {

                $modelsDetalle = Model::createMultiple(DetallePedido::classname());
                Model::loadMultiple($modelsDetalle, Yii::$app->request->post());
                
                if(count(Yii::$app->request->post('DetallePedido'))<1){
                    return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  Debe ingresar por lo menos un producto</strong></p>'];
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
                $valid = $modelPedido->validate();
                $valid = Model::validateMultiple($modelsDetalle) && $valid;

                foreach ($modelsDetalle as $modelDetalle) {
                   $validarStock = $modelDetalle->validarStock();
                  if(!$validarStock) 
                    return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  La cantidad debe ser menor o igual al stock</strong></p><p class="text-center"> ¿Desea registrar el pedido?</p><p class="text-center"><input type="button" id="createPedido" value="Registrar Pedido" class="btn btn-sm btn-info" onClick="createPedido()"></p>',];
                }
                
                if ($valid) { 
                    //genero codigo de venta
                    $codigoVenta = Venta::find()->select('codigo')->orderBy('codigo DESC')->one();
                    if(isset($codigoVenta->codigo)){
                        $codigoVenta = intval($codigoVenta->codigo) + 1;
                        $codigoVenta = str_pad($codigoVenta, 8, "0", STR_PAD_LEFT);
                    }else{
                        $codigoVenta = 1;
                        $codigoVenta = str_pad($codigoVenta, 8, "0", STR_PAD_LEFT);
                    }
                    //genero codigo de pedido
                    $codigoPedido = Pedido::find()->select('codigo')->orderBy('codigo DESC')->one();
                    if(isset($codigoPedido->codigo)){
                        $codigoPedido = intval($codigoPedido->codigo) + 1;
                        $codigoPedido = str_pad($codigoPedido, 8, "0", STR_PAD_LEFT);
                    }else{
                        $codigoPedido = 1;
                        $codigoPedido = str_pad($codigoPedido, 8, "0", STR_PAD_LEFT);
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                   
                    try {
                        
                        //registro datos de pedido
                        $modelPedido->codigo = $codigoPedido;
                        if ($flag = $modelPedido->save()) {
                             
                            foreach ($modelsDetalle as $modelDetalle) {
                                $modelDetalle->pedido_id = $modelPedido->id;
                                 
                                if (! ($flag = $modelDetalle->save())) {
                                    $transaction->rollBack();
                                    break;
                                }

                                //actualizo stock
                                $producto = Producto::findOne(['id' => $modelDetalle->producto_id]);
                                $cantidadActual = $producto->attributes['cantidadExistencia'];
                                $cantidadExistencia = $cantidadActual - $modelDetalle->cantidad;
                                $producto->attributes= ['cantidadExistencia'=>$cantidadExistencia];
                                if(!$flag = $producto->save()){
                                    $transaction->rollBack();
                                    break;
                                }
                                //registro salida producto
                                $modelAlmacen = new Almacen();
                                $modelAlmacenProducto = new AlmacenProducto();
                                $modelAlmacen->fecha = $modelPedido->fechaPedido;
                                $modelAlmacen->cantidad = $modelDetalle->cantidad;
                                $modelAlmacen->movimiento = 'SALIDA';
                                $modelAlmacen->documento = $codigoVenta;
                               
                                if(!$flag = $modelAlmacen->save()){
                                    $transaction->rollBack();
                                    break;
                                }
                                $modelAlmacenProducto->almacen_id = $modelAlmacen->id;
                                $modelAlmacenProducto->producto_id = $modelDetalle->producto_id;
                                
                                if(!$flag = $modelAlmacenProducto->save()){
                                    $transaction->rollBack();
                                    break;
                                }

                            }
                            
                        }
                        //registro venta
                        $modelVenta->codigo = $codigoVenta;
                        $modelVenta->estado = $modelPedido->estado;
                        $modelVenta->fechaGeneracion = $modelPedido->fechaPedido;
                        $modelVenta->montoCobrado = $request->post('total');
                        $modelVenta->pedido_id = $modelPedido->id;
                        
                        if(!$flag = $modelVenta->save()){
                            $transaction->rollBack();
                            break;
                        }
                       
                    
                        if ($flag) {
                            $transaction->commit();//$transaction->commit();
                            $message = 'Se generó venta<br>Codigo: '.$codigoVenta;
                            return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p><p class="text-center"><strong>¿Desea imprimir comprobante?</strong></p><p class="text-center">'.(Html::a('Imprimir',['imprimir','codigo'=>$codigoVenta],['class'=>'btn btn-sm btn-info','target'=>'_blank'])).'</p>',
                                'footer'=> Html::a('Cerrar',['index'],['class'=>'btn btn-default pull-right']));
                            // return $this->render('create', [
                            //     'content'=>'<span class="text-success">'.$message.'</span>',
                            //     'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            //     Html::a('Ir a Listado',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                            // ]);
                        // }else{  
                        //     $transaction->rollBack();
                        //     $message = 'Error al intentar generar pedido';
                        //     return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                      } else {
                        $transaction->rollBack();
                        $message = 'error al generar pedido';
                        return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                      }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        $message = 'error al generar pedido';
                        return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                        // return $this->render('create', [
                        //    'content'=>'<span class="text-success">'.$message.'</span>',
                        //    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        //         Html::a('Ir a Listado',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        // ]);
                    }
                }else{
                    return $this->renderAjax('create', [
                        'modelPedido' => $modelPedido,
                        'modelsDetalle' => $modelsDetalle,
                        'modelProducto' => $modelProducto
                        ]);
                } 
            } 
        }
}else{
    
    return $this->render('create', [
            'modelPedido' => $modelPedido,
            'modelsDetalle' => (empty($modelsDetalle)) ? [new DetallePedido] : $modelsDetalle,
            'modelProducto' => $modelProducto
        ]);
}
       
    }

    public function actionCreateventaxpedido(){
        $searchModel = new VentaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        $p = $request->post('Pedido');   
        $modelPedido = Pedido::findOne(['id'=> $p['id']]);
        $modelsDetalle = $modelPedido->detallePedidos;
        $modelVenta = new Venta;


        if($request->isAjax){ 
                Yii::$app->response->format = Response::FORMAT_JSON;
                        
            if ($modelPedido->load(Yii::$app->request->post())) {

                $oldIDs = ArrayHelper::map($modelsDetalle, 'id', 'id');

                    $modelsDetalle = Model::createMultiple(DetallePedido::classname(), $modelsDetalle);
         
                    Model::loadMultiple($modelsDetalle, Yii::$app->request->post());
         
                    $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsDetalle, 'id', 'id'))); 

                // validate all models
                $valid = $modelPedido->validate();
                $valid = Model::validateMultiple($modelsDetalle) && $valid;

                foreach ($modelsDetalle as $modelDetalle) {
                   $validarStock = $modelDetalle->validarStock();
                  if(!$validarStock) 
                    return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  La cantidad debe ser menor o igual al stock</strong></p><p class="text-center"> ¿Desea registrar el pedido?</p><p class="text-center"><input type="button" id="createPedido" value="Registrar Pedido" class="btn btn-sm btn-info" onClick="createPedido()"></p>',];
                }
                
                if ($valid) { 
                    //genero codigo de venta
                    $codigoVenta = Venta::find()->select('codigo')->orderBy('codigo DESC')->one();
                    if(isset($codigoVenta->codigo)){
                        $codigoVenta = intval($codigoVenta->codigo) + 1;
                        $codigoVenta = str_pad($codigoVenta, 8, "0", STR_PAD_LEFT);
                    }else{
                        $codigoVenta = 1;
                        $codigoVenta = str_pad($codigoVenta, 8, "0", STR_PAD_LEFT);
                    }
                    
                    $transaction = \Yii::$app->db->beginTransaction();
                   
                    try {
                        //registro datos de pedido
                        if ($flag = $modelPedido->save()) {
                             
                            if (!empty($deletedIDs)) {
                                DetallePedido::deleteAll(['id' => $deletedIDs]);
                            }

                            foreach ($modelsDetalle as $modelDetalle) {
                                $modelDetalle->pedido_id = $modelPedido->id;
                                 
                                if (! ($flag = $modelDetalle->save())) {
                                    $transaction->rollBack();
                                    break;
                                }

                                //actualizo stock
                                $producto = Producto::findOne(['id' => $modelDetalle->producto_id]);
                                $cantidadActual = $producto->attributes['cantidadExistencia'];
                                $cantidadExistencia = $cantidadActual - $modelDetalle->cantidad;
                                $producto->attributes= ['cantidadExistencia'=>$cantidadExistencia];
                                if(!$flag = $producto->save()){
                                    $transaction->rollBack();
                                    break;
                                }
                                //registro salida producto
                                $modelAlmacen = new Almacen();
                                $modelAlmacenProducto = new AlmacenProducto();
                                $modelAlmacen->fecha = $modelPedido->fechaPedido;
                                $modelAlmacen->cantidad = $modelDetalle->cantidad;
                                $modelAlmacen->movimiento = 'SALIDA';
                                $modelAlmacen->documento = $codigoVenta;
                               
                                if(!$flag = $modelAlmacen->save()){
                                    $transaction->rollBack();
                                    break;
                                }
                                $modelAlmacenProducto->almacen_id = $modelAlmacen->id;
                                $modelAlmacenProducto->producto_id = $modelDetalle->producto_id;
                                
                                if(!$flag = $modelAlmacenProducto->save()){
                                    $transaction->rollBack();
                                    break;
                                }

                            }
                            
                        }
                        //registro venta
                        $modelVenta->codigo = $codigoVenta;
                        $modelVenta->estado = $modelPedido->estado;
                        $modelVenta->fechaGeneracion = $modelPedido->fechaPedido;
                        $modelVenta->montoCobrado = $request->post('total');
                        $modelVenta->pedido_id = $modelPedido->id;
                        
                        if(!$flag = $modelVenta->save()){
                            $transaction->rollBack();
                            break;
                        }
                       
                    
                        if ($flag) {
                            $transaction->commit();//$transaction->commit();
                            $message = 'Se generó venta<br>Codigo: '.$codigoVenta;
                            return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p><p class="text-center"><strong>¿Desea imprimir comprobante?</strong></p><p class="text-center">'.(Html::a('Imprimir',['imprimir','codigo'=>$codigoVenta],['class'=>'btn btn-sm btn-info','target'=>'_blank'])).'</p>',
                                'footer'=> Html::a('Cerrar',['index'],['class'=>'btn btn-default pull-right']));
                      } else {
                        $transaction->rollBack();
                        $message = 'error al generar pedido';
                        return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                      }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        $message = 'error al generar pedido';
                        return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                    }
                }else{
                    return $this->renderAjax('create', [
                        'modelPedido' => $modelPedido,
                        'modelsDetalle' => $modelsDetalle,
                        'modelProducto' => $modelProducto
                        ]);
                } 
            } 
       // }
        }
    }

    public function actionCreatepedido()
    {   
        $request = Yii::$app->request;
        //var_dump($request->post());
        $modelPedido = new Pedido;
        $modelProducto = new Producto;
        $modelsDetalle2 = [new DetallePedido];

        if($request->isAjax){ 
                Yii::$app->response->format = Response::FORMAT_JSON;
            //if($request->isGet){
                // return $this->render('create', [
                //             'modelPedido' => $modelPedido,
                //             'modelsDetalle' => $modelsDetalle,
                //             'modelProducto' => $modelProducto
                //             ]);
            //} else {
                //       var_dump(Yii::$app->request->post());die();
                 if ($modelPedido->load(Yii::$app->request->post())) {

                     $modelsDetalle2 = Model::createMultiple(DetallePedido::classname());
                     Model::loadMultiple($modelsDetalle2, Yii::$app->request->post());
                //     if(count(Yii::$app->request->post('DetallePedido'))<1){
                //         return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  Debe ingresar por lo menos un producto</strong></p>'];
                // }
                // ajax validation
                //if (Yii::$app->request->isAjax) {
                    //Yii::$app->response->format = Response::FORMAT_JSON;
                    // return ArrayHelper::merge(
                    //     ActiveForm::validateMultiple($modelsDetalle),
                    //     ActiveForm::validate($modelPedido)
                    // );
                //}

                // validate all models
                $valid = $modelPedido->validate();
                $valid = Model::validateMultiple($modelsDetalle2) && $valid;

                
                   if ($valid) { 
                    
                    //genero codigo de pedido
                    $codigoPedido = Pedido::find()->select('codigo')->orderBy('codigo DESC')->one();
                    if(isset($codigoPedido->codigo)){
                        $codigoPedido = intval($codigoPedido->codigo) + 1;
                        $codigoPedido = str_pad($codigoPedido, 8, "0", STR_PAD_LEFT);
                    }else{
                        $codigoPedido = 1;
                        $codigoPedido = str_pad($codigoPedido, 8, "0", STR_PAD_LEFT);
                    }

                    $transaction = \Yii::$app->db->beginTransaction();
                   
                        try {
                            
                            //registro datos de pedido
                            $modelPedido->codigo = $codigoPedido;
                             $modelPedido->estado = "PENDIENTE";
                             $modelPedido->tipo = "PEDIDO";
                            if ($flag = $modelPedido->save()) {
                                 
                                foreach ($modelsDetalle2 as $modelDetalle) {
                                    $modelDetalle->pedido_id = $modelPedido->id;
                                     
                                    if (! ($flag = $modelDetalle->save())) {
                                        $transaction->rollBack();
                                        break;
                                    }

                                }
                                
                            }
                       
                            if ($flag) {
                                
                                $transaction->commit();//$transaction->commit();
                                $message = 'Se generó Pedido<br>Codigo: '.$codigoPedido;
                                return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p>',
                                    'footer'=> Html::a('Cerrar',['pedido/index'],['class'=>'btn btn-default pull-right']));
                                // return $this->render('create', [
                                //     'content'=>'<span class="text-success">'.$message.'</span>',
                                //     'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                //     Html::a('Ir a Listado',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                                // ]);
                            // }else{  
                            //     $transaction->rollBack();
                            //     $message = 'Error al intentar generar pedido';
                            //     return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                            } else {
                            $transaction->rollBack();
                            $message = 'error al generar pedido';
                            return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                            }
                            } catch (Exception $e) {
                            $transaction->rollBack();
                            $message = 'error al generar pedido';
                            return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                            // return $this->render('create', [
                            //    'content'=>'<span class="text-success">'.$message.'</span>',
                            //    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            //         Html::a('Ir a Listado',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                            // ]);
                            }
                    }else{
                        return $this->renderAjax('create', [
                            'modelPedido' => $modelPedido,
                            'modelsDetalle' => $modelsDetalle2,
                            'modelProducto' => $modelProducto
                            ]);
                    } 
                }
            //}
        }
    }

    /**
     * Updates an existing Venta model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $pedido_id
     * @return mixed
     */
    public function actionUpdate($id, $pedido_id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id, $pedido_id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Venta #".$id, $pedido_id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Venta #".$id, $pedido_id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id, $pedido_id'=>$id, $pedido_id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Venta #".$id, $pedido_id,
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
                return $this->redirect(['view', 'id' => $model->id, 'pedido_id' => $model->pedido_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Venta model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $pedido_id
     * @return mixed
     */
    public function actionDelete($id, $pedido_id)
    {
        $request = Yii::$app->request;
        $this->findModel($id, $pedido_id)->delete();

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
     * Delete multiple existing Venta model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $pedido_id
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
     * Finds the Venta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $pedido_id
     * @return Venta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $pedido_id)
    {
        if (($model = Venta::findOne(['id' => $id, 'pedido_id' => $pedido_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
