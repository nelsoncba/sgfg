<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Compra */

?>
<div class="compra-create">
    <?= $this->render('_form', [
        'modelCompra' => $modelCompra,
        'modelsDetalle' => $modelsDetalle,
        'modelMaterial' => $modelMaterial
    ]) ?>
</div>
