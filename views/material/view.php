<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Material */
?>
<div class="material-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'codigo',
            'nombre',
            'estado',
            'cantidadExistencia',
            'cantidadMinitma',
            'precioUnitario',
        ],
    ]) ?>

</div>
