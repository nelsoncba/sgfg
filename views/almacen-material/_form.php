<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Material;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenMaterial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="almacen-material-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'almacen_id')->textInput(['type'=>"hidden"])->label(false) ?>

    <?= $form->field($model_almacen, 'movimiento')->textInput(['maxlength' => true, 'value'=> 'SALIDA', 'readonly'=>true]) ?>

    <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model, 'material_id')->dropDownList(
        ArrayHelper::map(Material::find()->all(),'id','nombre'),
        ['prompt' => 'seleccione']
    )->label('Material')  ?>
    </div>
    <div class="col-sm-6">
    <?= $form->field($model_almacen, 'cantidad')->textInput(['maxlength' => true]) ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-6">
    <?= $form->field($model_almacen, 'fecha')->input('date') ?>
    </div>
    <div class="col-sm-6">
    <?= $form->field($model_almacen, 'tipo')->textInput(['maxlength' => true,'value'=>'MATERIAL','readonly'=>true]) ?>
    </div>
    </div>

    <?= $form->field($model, 'almacen_id')->textInput(array('type'=>"hidden",'size'=>2,'maxlength'=>2))->label(false) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
