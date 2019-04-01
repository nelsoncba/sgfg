<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pedido */

?>
<div class="pedido-create">
<?= $this->render('_form', [
        'modelPedido' => $modelPedido,
        'modelsDetalle' => $modelsDetalle,
        'modelProducto' => $modelProducto
         ]);
?>
</div>
