<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Persona */
/* @var $form ActiveForm */
?>
<div class="persona">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'fechaNacimiento') ?>
        <?= $form->field($model, 'domicilio_id') ?>
        <?= $form->field($model, 'contacto_personal_id') ?>
        <?= $form->field($model, 'nombre') ?>
        <?= $form->field($model, 'apellido') ?>
        <?= $form->field($model, 'razonSocial') ?>
        <?= $form->field($model, 'tipoDocu') ?>
        <?= $form->field($model, 'documento') ?>
        <?= $form->field($model, 'estado') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- persona -->
