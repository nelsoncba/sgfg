<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pedido */
?>
<div class="pedido-update">

    <?= $this->render('_form_update', [
        'modelPedido' => $modelPedido,
        'modelsDetalle' => $modelsDetalle
    ]) ?>

</div>
