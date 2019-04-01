<?php

namespace app\controllers;

use Yii;
use app\models\Pedido;
use app\models\PedidoSearch;
use app\models\DetallePedido;
use app\models\Producto;
use app\models\Almacen;
use app\models\AlmacenProducto;
use app\models\Venta;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;
use \yii\web\Response;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/**
 * PedidoController implements the CRUD actions for Pedido model.
 */
class PedidoController extends Controller
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
        ];
    }

    /**
     * Lists all Pedido models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new PedidoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Pedido model.
     * @param integer $id
     * @param integer $cliente_codigo
     * @return mixed
     */
    public function actionView($id, $cliente_codigo)
    {   
        $request = Yii::$app->request;

        $modelPedido = $this->findModel($id, $cliente_codigo); 
        $modelsDetalle = $modelPedido->detallePedidos;  
         
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->render('view', [
 
                    'modelPedido' => $modelPedido,
         
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new DetallePedido] : $modelsDetalle
         
                ]);    
        }else{
            return $this->render('view', [
 
                    'modelPedido' => $modelPedido,
         
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new DetallePedido] : $modelsDetalle
         
                ]); 
        }
    }

    /**
     * Creates a new Pedido model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
/*     public function actionCreate()
    {
        $searchModel = new PedidoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $request = Yii::$app->request;

        $modelPedido = new Pedido;
        $modelsDetalle = [new DetallePedido];
        $modelProducto = new Producto;
    if($request->isGet){
        return $this->render('create', [
            'modelPedido' => $modelPedido,
            'modelsDetalle' => (empty($modelsDetalle)) ? [new DetallePedido] : $modelsDetalle,
            'modelProducto' => $modelProducto
        ]);
    } else {
        //var_dump($request->post());
        if ($modelDetalle->load(Yii::$app->request->post())) {

            $modelsDetalle = Model::createMultiple(Address::classname());
            Model::loadMultiple($modelsDetalle, Yii::$app->request->post());

            // ajax validation
            // if (Yii::$app->request->isAjax) {
            //     Yii::$app->response->format = Response::FORMAT_JSON;
            //     return ArrayHelper::merge(
            //         ActiveForm::validateMultiple($modelsDetalle),
            //         ActiveForm::validate($modelPedido)
            //     );
            // }

            // validate all models
            $valid = $modelPedido->validate();
            $valid = Model::validateMultiple($modelsDetalle) && $valid;
            
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelPedido) {
                        foreach ($modelsDetalle as $modelDetalle) {
                            $modelDetalle->pedido_id = $modelPedido->id;
                            if (! ($flag = $modelDetalle)) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelPedido->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'modelPedido' => $modelPedido,
            'modelsDetalle' => (empty($modelsDetalle)) ? [new DetallePedido] : $modelsDetalle,
            'modelProducto' => $modelProducto
        ]);
    }
    }*/


    /**
     * Creates a new Pedido model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    { 
        $searchModel = new PedidoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        //var_dump($request);
        $modelPedido = new Pedido;
        $modelProducto = new Producto;

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


                // foreach ($modelsDetalle as $modelDetalle) {
                //    $validarStock = $modelDetalle->validarStock();
                //   if(!$validarStock) 
                //     return ['content'=>'<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i>  La cantidad debe ser menor o igual al stock</strong></p>'];
                // }
                
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
                            if ($flag = $modelPedido->save()) {
                                 
                                foreach ($modelsDetalle as $modelDetalle) {
                                    $modelDetalle->pedido_id = $modelPedido->id;
                                     
                                    if (! ($flag = $modelDetalle->save())) {
                                        $transaction->rollBack();
                                        break;
                                    }

                                }
                                
                            }
                       
                    
                            if ($flag) {
                                $transaction->commit();//$transaction->commit();
                                $message = 'Se generó Registro<br>Codigo: '.$codigoPedido;
                                return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p>',
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

    public function actionCreateventa($idpedido,$cliente_codigo){
        $request = Yii::$app->request;
        $modelPedido = $this->findModel($idpedido, $cliente_codigo); 
        $modelsDetalle = $modelPedido->detallePedidos;  
         
        // echo '<pre>';
        // var_dump($modelsDetalle); die();
        // echo  '</pre>';
        
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return $this->render('createventa', [
 
                    'modelPedido' => $modelPedido,
         
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new Address] : $modelsDetalle
         
                ]);         
            }else{

                if($modelPedido->load($request->post())){

                    $oldIDs = ArrayHelper::map($modelsDetalle, 'id', 'id');

                    $modelsDetalle = Model::createMultiple(DetallePedido::classname(), $modelsDetalle);
         
                    Model::loadMultiple($modelsDetalle, Yii::$app->request->post());
         
                    $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsDetalle, 'id', 'id')));                   

                    // validate all models
         
                    $valid = $modelPedido->validate();
         
                    $valid = Model::validateMultiple($modelsDetalle) && $valid;
         
         
                    if ($valid) {
         
                        $transaction = \Yii::$app->db->beginTransaction();
         
                        try {
         
                            if ($flag = $modelPedido->save(false)) {
         
                                if (!empty($deletedIDs)) {
         
                                    Address::deleteAll(['id' => $deletedIDs]);
         
                                }
         
                                foreach ($modelsDetalle as $modelDetalle) {
         
                                    $modelDetalle->pedido_id = $modelPedido->id;
         
                                    if (! ($flag = $modelDetalle->save(false))) {
         
                                        $transaction->rollBack();
         
                                        break;
         
                                    }
         
                                }
         
                            }
         
                            if ($flag) {
         
                                $transaction->commit();
         
                                $message = 'Se modificó pedido<br>Codigo: '.$modelPedido->codigo;
                                return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p><p class="text-center"></p><p class="text-center">',
                                    'footer'=> Html::a('Cerrar',['index'],['class'=>'btn btn-default pull-right']));
             
                            }else {
                                $transaction->rollBack();
                                $message = 'error al modificar pedido';
                                return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                            }
         
                        } catch (Exception $e) {
         
                            $transaction->rollBack();
                            $message = 'error al modificar pedido';
                                return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                        }
         
                    }else{
                        return $this->renderAjax('update', [
                            'modelPedido' => $modelPedido,
                            'modelsDetalle' => $modelsDetalle,
                            ]);
                    }  
                }else{
                     return [
                        'title'=> "Update Pedido #".$id, $cliente_codigo,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                    ];        
                }
            } // end else isGet
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($modelPedido->load($request->post()) && $modelPedido->save()) {
                return $this->redirect(['createventa', 'id' => $model->id, 'cliente_codigo' => $model->cliente_codigo]);
            } else {
                return $this->render('createventa', [
                        'modelPedido' => $modelPedido,
                        'modelsDetalle' => $modelsDetalle,
                        ]); 
            }
        }
    }
    /**
     * Updates an existing Pedido model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $cliente_codigo
     * @return mixed
     */
    public function actionUpdate($id, $cliente_codigo)
    {
        $request = Yii::$app->request;
        $modelPedido = $this->findModel($id, $cliente_codigo); 
        $modelsDetalle = $modelPedido->detallePedidos;  
         
        // echo '<pre>';
        // var_dump($modelsDetalle); die();
        // echo  '</pre>';
        
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return $this->render('update', [
 
                    'modelPedido' => $modelPedido,
         
                    'modelsDetalle' => (empty($modelsDetalle)) ? [new Address] : $modelsDetalle
         
                ]);         
            }else{

                if($modelPedido->load($request->post())){

                    $oldIDs = ArrayHelper::map($modelsDetalle, 'id', 'id');

                    $modelsDetalle = Model::createMultiple(DetallePedido::classname(), $modelsDetalle);
         
                    Model::loadMultiple($modelsDetalle, Yii::$app->request->post());
         
                    $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsDetalle, 'id', 'id')));                   

                    // validate all models
         
                    $valid = $modelPedido->validate();
         
                    $valid = Model::validateMultiple($modelsDetalle) && $valid;
         
         
                    if ($valid) {
         
                        $transaction = \Yii::$app->db->beginTransaction();
         
                        try {
         
                            if ($flag = $modelPedido->save(false)) {
         
                                if (!empty($deletedIDs)) {
         
                                    DetallePedido::deleteAll(['id' => $deletedIDs]);
         
                                }
         
                                foreach ($modelsDetalle as $modelDetalle) {
         
                                    $modelDetalle->pedido_id = $modelPedido->id;
         
                                    if (! ($flag = $modelDetalle->save(false))) {
         
                                        $transaction->rollBack();
         
                                        break;
         
                                    }
         
                                }
         
                            }
         
                            if ($flag) {
         
                                $transaction->commit();
         
                                $message = 'Se modificó pedido<br>Codigo: '.$modelPedido->codigo;
                                return array('content' => '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> '.$message.'</strong></p><p class="text-center"></p><p class="text-center">',
                                    'footer'=> Html::a('Cerrar',['index'],['class'=>'btn btn-default pull-right']));
             
                            }else {
                                $transaction->rollBack();
                                $message = 'error al modificar pedido';
                                return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                            }
         
                        } catch (Exception $e) {
         
                            $transaction->rollBack();
                            $message = 'error al modificar pedido';
                                return array('content' => '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> '.$message.'</strong></p>');
                        }
         
                    }else{
                        return $this->renderAjax('update', [
                            'modelPedido' => $modelPedido,
                            'modelsDetalle' => $modelsDetalle,
                            ]);
                    }  
                }else{
                     return [
                        'title'=> "Update Pedido #".$id, $cliente_codigo,
                        'content'=>$this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                    ];        
                }
            } // end else isGet
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($modelPedido->load($request->post()) && $modelPedido->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'cliente_codigo' => $model->cliente_codigo]);
            } else {
                return $this->render('update', [
                        'modelPedido' => $modelPedido,
                        'modelsDetalle' => $modelsDetalle,
                        ]); 
            }
        }
    }

    /**
     * Delete an existing Pedido model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $cliente_codigo
     * @return mixed
     */
    public function actionDelete($id, $cliente_codigo,$accion)
    {
        $request = Yii::$app->request;
        $modelPedido = $this->findModel($id, $cliente_codigo);
        
        if($request->isAjax){
            if($modelPedido->estado != 'CERRADO' and $modelPedido->estado != 'ANULADO'){
            

             Yii::$app->response->format = Response::FORMAT_JSON;
            if($accion=='confirmar'){

                return  [   
                            'data-confirm-title'=>'Confirmacion',
                            'content'=>'<span class="text-success">¿Confirma la anulacion del pedido?</span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Anular',['delete','id'=>$id,'cliente_codigo'=>$cliente_codigo,'accion'=>'anular'],['class'=>'btn btn-primary','role'=>'modal-remote','data-request-method'=>'post','data-title'=>'Confirmacion'])
                        ];

            }
            if($accion=='anular'){

                $modelPedido->load($request->post());

                $transaction = \Yii::$app->db->beginTransaction();
         
                    $modelPedido->estado = 'ANULADO';
                    if($modelPedido->save()){
                        $transaction->commit();
                        $message = 'El pedido se ha anulado';
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Confirmacion",
                            'content'=>'<span class="text-success"><strong>'.$message.'</strong></span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];  
                       
                        // return ['forceClose'=>true,
                        //         'forceReload'=>'#crud-datatable-pjax'];
                    }else{
                        $transaction->rollBack();
                        $message = 'Error al anular pedido';
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
                    $message = 'El pedido se encuentra '.$modelPedido->estado;
                    return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Confirmacion",
                    'content'=>'<span class="text-success">'.$message.'</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
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
     * Delete multiple existing Pedido model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $cliente_codigo
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
     * Finds the Pedido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $cliente_codigo
     * @return Pedido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $cliente_codigo)
    {
        if (($model = Pedido::findOne(['id' => $id, 'cliente_codigo' => $cliente_codigo])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
