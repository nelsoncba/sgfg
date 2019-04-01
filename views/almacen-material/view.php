<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AlmacenMaterial */
?>
<div class="almacen-material-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'almacen_id',
            'material_id',
        ],
    ]) ?>

</div>
