<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenProducto */
?>
<div class="almacen-producto-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'tipo',
            'descripcion',
            'almacen_id',
            'producto_id',
        ],
    ]) ?>

</div>
