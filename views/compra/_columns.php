<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    // [
    //     'class' => 'kartik\grid\CheckboxColumn',
    //     'width' => '20px',
    // ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'numeroFactura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'razonSocial',
        'label'=> 'Proveedor'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'importe',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'notaCredito',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'notaDebito',
    ],

    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'proveedor_codigo',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template'=>'{view} {delete}',
        'vAlign'=>'middle',
        'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete','id'=>$model->id,'proveedor_codigo'=>$model->proveedor_codigo,'accion'=>'confirmar'], ['role'=>'modal-remote','data-request-method'=>'post', 'data-toggle'=>'tooltip','data-pjax'=>0, 'data-title'=>'Confirmacion','data-message'=>'¿Confirma anulacion de la compra?',
                            'title' => Yii::t('app', 'Anular'),
                ]);
            }
        ],
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key['id'], 'proveedor_codigo'=>$key['proveedor_codigo']]);
        },
        'viewOptions'=>['title'=>'Ver','data-toggle'=>'tooltip'],
        //'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        // 'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
        //                   'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
        //                   'data-request-method'=>'post',
        //                   'data-toggle'=>'tooltip',
        //                   'data-confirm-title'=>'Are you sure?',
        //                   'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   