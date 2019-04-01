<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pedido */
?>
<div class="pedido-view">
<?= $this->render('_form_update', [
        'modelPedido' => $modelPedido,
        'modelsDetalle' => $modelsDetalle,
         ]);
?>
</div>
