<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cliente */

?>
<div class="cliente-create">
    <?= $this->render('_form', [
        'model' => $model,'model_persona'=>$model_persona,
        'model_domicilio' => $model_domicilio, 'model_contacto'=>$model_contacto
    ]) ?>
</div>
