<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
?>
<div class="cliente-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo',
            //'persona_id',
            'nombre',
            'apellido',
            'tipoDocu',
            'documento',
            'tipo',
        ],
    ]) ?>

</div>
