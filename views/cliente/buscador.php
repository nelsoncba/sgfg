<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$js = '
$("input[type=\'checkbox\'].kv-row-checkbox").on("click",function(){
    if($("input[type=\'checkbox\'].kv-row-checkbox").is(\':checked\')){ 
        var value = JSON.parse($(this).val());
        console.log(value.codigo);
        $("[name=\'Pedido[cliente_codigo]\']").val(value.codigo);
        var cliente = "";
            cliente += $(this).parent().siblings(\'[data-col-seq="2"]\').text() + "   " 
                    + $(this).parent().siblings(\'[data-col-seq="3"]\').text() + ",   "
                    + $(this).parent().siblings(\'[data-col-seq="4"]\').text() + ":   "
                    + $(this).parent().siblings(\'[data-col-seq="5"]\').text();
        
        $("[name=cliente]").val(cliente.toUpperCase());

        $("#responseModal").modal("toggle");
    }
});


';

$this->registerJs($js);

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="cliente-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'export' => false,
            'toggleData'=>false,
            'pjax'=>true,
            'columns' => [
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'width' => '20px',

                ],
                'codigo',
                'nombre',
                'apellido',
                'tipoDocu',
                'documento',
                ],
            // 'toolbar'=> [
            //     ['content'=>
            //         // Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
            //         // ['role'=>'modal-remote','title'=> 'Create new Clientes','class'=>'btn btn-default']).
            //         Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
            //         ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
            //         '{toggleData}'.
            //         '{export}'
            //     ],
            // ],          
            // 'striped' => false,
            // 'condensed' => false,
            // 'responsive' => false,          
            'panel' => [
                'type' => 'primary', 
                'heading' => 'Lista de Clientes',
            ]
        ])?>
    </div>
</div>

