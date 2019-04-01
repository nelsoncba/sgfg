<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Venta */
?>
<div class="venta-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'codigo',
            'estado',
            'fechaGeneracion',
            'fechaCobro',
            'monto',
            'montoIva',
            'montoCobrado',
            'pedido_id',
        ],
    ]) ?>

</div>
