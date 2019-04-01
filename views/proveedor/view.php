<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Proveedor */
?>
<div class="proveedor-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo',
            'razonSocial',
            'nombre',
            'apellido',
            'tipoDocu',
            'documento'
            //'persona_id',
        ],
    ]) ?>

</div>
