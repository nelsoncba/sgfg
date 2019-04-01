<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pedido */

?>
<div class="pedido-createventa">
<?= $this->render('_formventa', [
        'modelPedido' => $modelPedido,
        'modelsDetalle' => $modelsDetalle
         ]);
?>
</div>
