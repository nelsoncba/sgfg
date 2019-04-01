<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AlmacenProducto */

?>
<div class="almacen-producto-create">
    <?= $this->render('_form', [
        'model' => $model, 'model_producto' => $model_producto,
        'model_almacen' => $model_almacen
    ]) ?>
</div>
