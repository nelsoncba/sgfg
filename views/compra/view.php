<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pedido */
?>
<div class="compra-view">
<?= $this->render('_form_update', [
        'modelCompra' => $modelCompra,
        'modelsDetalle' => $modelsDetalle,
         ]);
?>
</div>