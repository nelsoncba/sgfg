<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DetallePedido */
?>
<div class="detalle-pedido-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cantidad',
            'descuento',
            'producto_id',
            'pedido_id',
        ],
    ]) ?>

</div>
