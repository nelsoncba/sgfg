<?php

namespace app\controllers;

use Yii;
use app\models\Cliente;
use app\models\Persona;
use app\models\Domicilio;
use app\models\ContactoPersonal;
use app\models\ContactoPersonalSearch;
use app\models\Localidad;
use app\models\ClienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends Controller
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
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination=['pagesize'=>10];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        $query = Article::find()->where(['status' => 1]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

            Yii::$app->response->format = Response::FORMAT_JSON;
        $query = Article::find()->where(['status' => 1]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
    }


    /**
     * Displays a single Cliente model.
     * @param integer $codigo
     * @param integer $persona_id
     * @return mixed
     */
    public function actionView($codigo=null, $persona_id=null)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Cliente #".$codigo, $persona_id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel('Cliente',$codigo, $persona_id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
                            // .
                            // Html::a('Edit',['Modificar','codigo, $persona_id'=>$codigo, $persona_id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel('Cliente',$codigo, $persona_id),
            ]);
        }
    }

    public function actionBuscador(){
        $queryParams = array('ClienteSearch'=>[
                              'codigo' =>'',
                              'nombre' => '',
                              'apellido' => Yii::$app->request->post('codigo'),
                              'tipoDocu' => '',
                              'documento' => Yii::$app->request->post('codigo'),
                              'tipo' => '']);
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search($queryParams);

        $dataProvider->pagination=['pagesize'=>10];

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
                    'title'=> "Clientes",
                    'content'=>$this->renderAjax('buscador', [
                        'dataProvider'=> $dataProvider
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];
    }
    /**
     * Creates a new Cliente model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model_persona = new Persona();
        $model_domicilio = new Domicilio();
        $model_contacto = new ContactoPersonal();
        $localidad = new Localidad;
        $model = new Cliente();  

        if($request->isAjax){ 


            Yii::$app->response->format = Response::FORMAT_JSON;
            /*
            *   Process for ajax request
            */
            
            if($request->isGet){
                return [
                    'title'=> "Crear Cliente",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model, 'model_persona' => $model_persona,
                        'model_domicilio' => $model_domicilio, 'model_contacto' => $model_contacto
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model_persona->load($request->post()) && $model->load($request->post()) && $model_domicilio->load($request->post()) && $model_contacto->load($request->post()) && $model_persona->validate() && $model->validate()){
                
                if(Persona::findOne(['documento'=>$model_persona->documento])){
                    return [
                    'title'=> "Crear Cliente",
                    'content'=>'<span><p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> El cliente ya existe</strong></p></span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                    ]; 
                }

                $transaction = Yii::$app->db->beginTransaction();
                try{
                        $model_domicilio->save();
                        $model_persona->domicilio_id = $model_domicilio->id;
                        $model_persona->save();
                        $model->persona_id = $model_persona->id;
                        $model_contacto->persona_id = $model_persona->id;
                        $model->save();
                        if($model_contacto->telefono != '' && $model_contacto->celular != '' && $model_contacto->email != '')
                            $model_contacto->save();

                        $transaction->commit();
                        $message = '<p class="text-success text-center"><i class="glyphicon glyphicon-ok-sign"></i><strong> Cliente creado</strong></p>';
                    }catch(Exception $e){
                        $transaction->rollBack();
                        $message = '<p class="text-danger text-center"><strong><i class="glyphicon glyphicon-exclamation-sign"></i> Error al crear cliente</strong></p>';
                    }
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear Cliente",
                    'content'=>'<span>'.$message.'</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear Cliente',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ]; 
            
            }else{         
                return [
                    'title'=> "Create new Cliente",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,'model_persona' => $model_persona,
                        'model_domicilio' => $model_domicilio, 'model_contacto' => $model_contacto
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model_persona->load($request->post()) && $model->load($request->post()) && $model_domicilio->load($request->post()) && $model_contacto->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try{
                        $model_domicilio->save();
                        $model_persona->domicilio_id = $model_domicilio->id;
                        $model_persona->save();
                        $model->persona_id = $model_persona->id;
                        $model_contacto->persona_id = $model_persona->id;
                        $model->save();
                        if($model_contacto->telefono != '' && $model_contacto->celular != '' && $model_contacto->email != '')
                            $model_contacto->save();

                        $transaction->commit();
                        $message = "Cliente creado";
                    }catch(Exception $e){
                        $transaction->rollBack();
                        $message = "Error la crear nuevo cliente";
                    }

                return $this->redirect(['view', 'codigo' => $model->codigo, 'persona_id' => $model->persona_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,'model_persona' => $model_persona,
                    'model_domicilio' => $model_domicilio, 'model_contacto' => $model_contacto
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Cliente model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $codigo
     * @param integer $persona_id
     * @return mixed
     */
    public function actionUpdate($codigo, $persona_id=null)
    {
       // var_dump(Yii::$app->request);die();
        
        $request = Yii::$app->request;
        
        $model = $this->findModel('Cliente',$codigo);
        $model_persona = $this->findModel('Persona',$persona_id);
        $model_domicilio = $this->findModel('Domicilio',$model_persona->domicilio_id);
        $model_contacto = $this->findModel('Contacto',$persona_id);

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Modificar Cliente ", 
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,'model_persona' => $model_persona,'model_domicilio' => $model_domicilio,'model_contacto' => $model_contacto,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];  

                /*
                 * Se cargan lo objetos modelo con los datos desde la vista
                 */     
            }else if($model_persona->load($request->post()) && $model->load($request->post()) && $model_domicilio->load($request->post()) && $model_contacto->load($request->post())){
            
                $contacto = Yii::$app->request->post('ContactoPersonal');
                
                $transaction = Yii::$app->db->beginTransaction();
                try{
                        $model_domicilio->save();
                        $model_persona->save();
                        $model->save();
                        if($contacto['telefono'] != '' or $contacto['celular'] != '' or $contacto['email'] != ''){
                                $model_contacto->telefono = $contacto['telefono'];
                                $model_contacto->celular = $contacto['celular'];
                                $model_contacto->email = $contacto['email'];
                                $model_contacto->persona_id = $persona_id;

                                $model_contacto->save();
                         }

                        $transaction->commit();
                        $message = "Datos Cliente modificado";
                    }catch(Exception $e){
                        $transaction->rollBack();
                        $message = "Error la modificar nuevo cliente";
                    }

                /*
                * renderiza los datos actualizados a la vista mediante ajax 
                */
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Cliente ",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,'model_persona' => $model_persona,'model_domicilio' => $model_domicilio,'model_contacto' => $model_contacto,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-right','data-dismiss'=>"modal"])
                            // .
                            // Html::a('Modificar',['update','codigo, $persona_id'=>$codigo, $persona_id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar Cliente ", 
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,'model_persona' => $model_persona,'model_domicilio' => $model_domicilio,'model_contacto' => $model_contacto,
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
                return $this->redirect(['view', 'codigo' => $model->codigo, 'persona_id' => $model->persona_id]);
            } else {
                return $this->render('update', [
                    'model' => $model,'model_persona' => $model_persona,'model_domicilio' => $model_domicilio,'model_contacto' => $model_contacto,
                ]);
            }
        }
    }

    /**
     * Delete an existing Cliente model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $codigo
     * @param integer $persona_id
     * @return mixed
     */
    public function actionDelete($codigo, $persona_id)
    {
        $request = Yii::$app->request;

        $transaction = Yii::$app->db->beginTransaction();
        try{
            
            $this->findModel('Cliente',$codigo)->delete();
            $this->findModel('Contacto',['persona_id'=>$persona_id])->delete();
            $persona = $this->findModel('Persona',$persona_id);
            $persona->delete();
            $this->findModel('Domicilio',$persona->domicilio_id)->delete();

            $transaction->commit();
        }catch(Exception $e){
            $transaction->rollBack();
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
     * Delete multiple existing Cliente model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $codigo
     * @param integer $persona_id
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
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $codigo
     * @param integer $persona_id
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($model_class=null,$codigo=null,$persona_id=null)
    { 
        switch ($model_class) {
            case 'Cliente':
                if (($model = Cliente::findOne(['codigo' => $codigo])) !== null) {
                    return $model;
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
                break;
            case 'Persona':
                if (($model = Persona::findOne(['id' => $codigo])) !== null) {
                    return $model;
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
                break;
            case 'Domicilio':
                        
                if (($model = Domicilio::findOne(['id' => $codigo])) !== null) {
                    return $model;
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
                break;
            case 'Contacto':
                   if (($model  = ContactoPersonal::findOne(['persona_id'=>$codigo])) !== null){
                        return $model;
                   }else{
                        $model = new ContactoPersonal();
                        return $model;
                   }               
                break;
            default:
                # code...
                break;
        }
        
    }
}
