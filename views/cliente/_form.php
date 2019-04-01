<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Localidad;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin([
          'enableClientValidation' => true,
        'enableAjaxValidation' => false
    ]); ?>
    <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model_persona, 'nombre')->textInput(['maxlength' => true]) ?>  
    </div>
    <div class="col-sm-6">
    <?= $form->field($model_persona, 'apellido')->textInput(['maxlength' => true]) ?>
    </div>
    </div> 
    <?= $form->field($model_persona, 'razonSocial')->textInput(['maxlength' => true]) ?>

    <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model_persona, 'tipoDocu')->dropDownList(['dni' => 'DNI', 'cuit' => 'CUIT'], ['prompt' => 'Seleccione']) ?> 
    </div>
    <div class="col-sm-6">
    <?= $form->field($model_persona, 'documento')->textInput(['maxlength' => true]) ?>
    </div>
    </div> 
    <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model_persona, 'fechaNacimiento')->input('date') ?>
    </div>
    <div class="col-sm-6">
    <?= $form->field($model, 'tipo')->dropDownList(['A'=>'A','B'=>'B','C'=>'C'],['options'=>['C'=>['Selected'=>'selected']]],['prompt','Seleccione']) ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-8">
    <?= $form->field($model_domicilio, 'calle')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-4">
    <?= $form->field($model_domicilio, 'numero')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model_domicilio, 'codigoPostal')->textInput(['maxlength' => true]) ?>
     </div>
    <div class="col-sm-6">
    <?= $form->field($model_domicilio, 'localidad_id')->dropDownList(
    	ArrayHelper::map(Localidad::find()->all(),'id','nombre'),
    	['prompt' => 'seleccione']
    ) ?>
     </div>
    </div>
     <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model_contacto, 'telefono')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">
    <?= $form->field($model_contacto, 'celular')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    <?= $form->field($model_contacto, 'email')->textInput(['maxlength' => true]) ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
