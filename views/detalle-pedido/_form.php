<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\builder\TabularForm;
use yii\data\ArrayDataProvider;





$dataProvider = new ArrayDataProvider([
    'allModels'=>[
        // ['id'=>1, 'name'=>'Book Number 1', 'publish_date'=>'25-Dec-2014'],
        // ['id'=>2, 'name'=>'Book Number 2', 'publish_date'=>'02-Jan-2014'],
        // ['id'=>3, 'name'=>'Book Number 3', 'publish_date'=>'11-May-2014'],
        // ['id'=>4, 'name'=>'Book Number 4', 'publish_date'=>'16-Apr-2014'],
        // ['id'=>5, 'name'=>'Book Number 5', 'publish_date'=>'16-Apr-2014']
    ]
]);
/* @var $this yii\web\View */
/* @var $model app\models\DetallePedido */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="detalle-pedido-form">
    <?php $form = ActiveForm::begin(); 

   echo TabularForm::widget([
    'form' => $form,
    'dataProvider' => $dataProvider,
    'attributes' => [
        'name' => ['type' => TabularForm::INPUT_TEXT],
        'buy_amount' => [
            'type' => TabularForm::INPUT_TEXT, 
            'options'=>['class'=>'form-control text-right'], 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
        'sell_amount' => [
            'type' => TabularForm::INPUT_STATIC, 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
    ],
    'gridSettings' => [
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
            'type' => GridView::TYPE_PRIMARY,
            'after'=> 
                Html::button(
                    '<i class="glyphicon glyphicon-plus"></i> Nuevo Item',
                    ['title'=> 'Create new Almacen Productos','id'=>'addRow','class'=>'btn btn-success']
                ) . '&nbsp;' . 
                Html::a(
                    '<i class="glyphicon glyphicon-remove"></i> Delete', 
                    'delete', 
                    ['class'=>'btn btn-danger']
                ) . '&nbsp;' .
                Html::submitButton(
                    '<i class="glyphicon glyphicon-floppy-disk"></i> Save', 
                    ['class'=>'btn btn-primary']
                )
        ]
    ]     
]); 


?>
    <?php ActiveForm::end(); ?>
</div>

 

