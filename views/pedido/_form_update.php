<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Producto;

CrudAsset::register($this);
$this->title = 'Pedido';
/* @var $this yii\web\View */
/* @var $model app\models\Pedido */
/* @var $form yii\widgets\ActiveForm */

$currentAction = Yii::$app->controller->action->id;
$readonly = ($currentAction == 'view') ? true : false;

$js = '

$("form").on("beforeSubmit", function(e) {
    var form = $(this);
    var formData = form.serialize();
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: formData,
        success: function (data) {
          // if(data.content !== undefined && data.content !== "nomodal"){
                $(".modal-body").html(data.content);
                $(".modal-footer").html(data.footer);

                //inhabilito click fuera de modal 
                $("#responseModal").modal({
                     keyboard: false
                });
                $("#responseModal").modal("show");
          //  }
        },
        error: function (data) {
            $(".modal-body").html(data.responseText);
            $("#responseModal").modal({
                     keyboard: false
                });
            $("#responseModal").modal("toggle");
        }
    });
}).on("submit", function(e){
    e.preventDefault();
});


$("#clienteSearch").on("click", function(e) {
    var clienteData = $(\'[name="cliente"]\').val();
    
    $.ajax({
        url: "../cliente/buscador",
        type: "post",
        data: {codigo: clienteData},
        success: function (data) {
           console.log(data);
            $(".modal-body").html(data.content);
            $("#responseModal").modal("toggle");
        },
        error: function (data) {
            $(".modal-body").html(data.responseText);
            $("#responseModal").modal("toggle");
        }
    });
}).on("submit", function(e){
    e.preventDefault();
});

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Item: " + (index + 1))
        indexDet = index;
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title-address").each(function(index) {
        jQuery(this).html("Item: " + (index + 1))
    });
    calcularTotal();
});

';

$this->registerJs($js);
?>

<div class="pedido-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'enableAjaxValidation' => false]); ?>
    <div class="form-group">

            <?php if($currentAction != 'view'){
                    echo Html::submitButton($modelPedido->isNewRecord ? Yii::t('app', 'Generar Venta') : Yii::t('app', 'Modificar'), ['class' => $modelPedido->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                  }else{ ?>
                    <div class="form-group">
                    <a href="javascript:history.back(1)" class="btn btn-sm btn-primary">Volver</a>
                    <?= Html::a('Registrar Venta',['createventa','idpedido'=>$modelPedido->id,'cliente_codigo'=>$modelPedido->cliente_codigo],['class'=>'btn btn-sm btn-success','target'=>'_blank']) ?>
                    </div>
            <?php } ?>
            
        </div>

<div class="row">
    
    
    <div class="container">
    <div class="row">
    <div class="col-sm-3 left">
    <?= $form->field($modelPedido, 'id')->textInput(['maxlength' => true,'type'=>'hidden', 'readonly'=>true])->label(false) ?>
    <?= $form->field($modelPedido, 'codigo')->textInput(['maxlength' => true, 'readonly'=>true]) ?>
    </div>
    <div class="col-sm-6"> </div>
    <div class="col-sm-2 ">
    <?= $form->field($modelPedido, 'fechaPedido')->textInput(['type'=>'date','readonly'=>true]) ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 left">
    <?= $form->field($modelPedido, 'tipo')->textInput(['maxlength'=>true,'readonly'=>true]) ?>
    </div>
    <div class="col-sm-4 ">
    <?= $form->field($modelPedido, 'estado')->textInput(['maxlength' => true, 'readonly'=>true]) ?>
    </div>
    </div>

    <?= $form->field($modelPedido, 'fechaEntrega')->textInput(array('type'=>"hidden"))->label(false) ?>
    <div class="row">
    <div class="col-sm-2 pull-left">
    <?= $form->field($modelPedido, 'cliente_codigo')->textInput(['maxlength'=>true,'readonly'=>true])->label('Codigo') ?>
    </div>
    <div class="col-sm-6">
        <div class="form-group field-pedido-cliente_codigo">
        <label class="control-label" for="pedido-codigo">Cliente</label>
        <input type="text" name="cliente" value="<?= strtoupper($modelPedido->clienteCodigo['nombre'].' '.$modelPedido->clienteCodigo['apellido'].' '.$modelPedido->clienteCodigo['tipoDocu'].': '.$modelPedido->clienteCodigo['documento'])?>" class="form-control" readonly="<?=$readonly?>">
        </div>
    </div>
    <div class="col-sm-3">
    
    </div>
    </div>
 </div>
    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 4, // the maximum times, an element can be cloned (default 999)
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelsDetalle[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'producto_id',
            'cantidad',
            'descuento',
        ],
    ]); 

    $arrayp2 = ArrayHelper::map(Producto::find()->all(),'id','precioUnitario');
    $arrays = ArrayHelper::map(Producto::find()->all(),'id','cantidadExistencia');


    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-envelope"></i> Productos
            <?php if($currentAction != 'view'){ ?>
            <button type="button" class="pull-right add-item btn btn-info btn-xs"><i class="fa fa-plus"></i> Agregar productos</button>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body container-items"><!-- widgetContainer -->
            <?php foreach ($modelsDetalle as $index => $modelDetalle): 
            ?>

                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <span class="panel-title-address">Productos: <?= ($index + 1) ?></span>
                         <?php if($currentAction != 'view'){ ?>
                        <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                         <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (!$modelDetalle->isNewRecord) {
                                echo Html::activeHiddenInput($modelDetalle, "[{$index}]id");
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-3">
                                <?= $form->field($modelDetalle, "[{$index}]producto_id")->dropDownList(
                                        ArrayHelper::map(Producto::find()->all(),'id','nombre'),
                                        ['prompt' => 'seleccione',
                                         'onchange'=>'
                                         
                                            var id = $(this).val();
                                            var arrayPrecio = '.json_encode($arrayp2).';
                                            var arrayStock = '.json_encode($arrays).';

                                            var str = $(this).attr("id");
                                            indexDet = str.slice(14,15);

                                            $("#producto-"+indexDet+"-preciounitario").val(arrayPrecio[id]);
                                            
                                            $("#producto-"+indexDet+"-cantidadexistencia").val(arrayStock[id]);

                                            var cant = 0;
                                            if($("#detallepedido-"+indexDet+"-cantidad").val()!=""){
                                                cant = $("#detallepedido-"+indexDet+"-cantidad").val();
                                            }
                                            var subtotal = cant * parseFloat(arrayPrecio[id]);

                                            $("#detallepedido-"+indexDet+"-cantidad").attr("data-subtotal",subtotal);

                                            calcularTotal();
                                         ',
                                         'readonly'=>$readonly
                                        ]
                                    ) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelDetalle, "[{$index}]cantidad")->textInput(['maxlength' => true,
                                                'data-subtotal'=>0,
                                               'onkeyup' => '
                                               var str = $(this).attr("id");
                                                indexDet = str.slice(14,15);

                                                var cantidad = $(this).val(); 
                                                var precio = $("#producto-"+indexDet+"-preciounitario").val();
                                                var subtotal = cantidad * precio;

                                                $(this).attr("data-subtotal",subtotal);
                                                calcularTotal();
                                               ',
                                               'readonly'=>$readonly
                                                ]) ?>
                            </div>
                            <div class="col-sm-2">
                            <?= $form->field($modelDetalle->producto, "[{$index}]cantidadExistencia")->textInput(['maxlength' => true,'readonly'=>true])->label('Stock') ?>
                            </div>
                            <div class="col-sm-2">
                            <?= $form->field($modelDetalle->producto, "[{$index}]precioUnitario")->textInput(['maxlength' => true,'readonly'=>true]) ?>
                                <!-- <label class="control-label" for="detallepedido-0-cantidad">Precio U.</label>
                                <input type="text" name="[{$index}]precioUnitario" id="precioUnitario" class="form-control"> -->
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelDetalle, "[{$index}]descuento")->textInput(['maxlength' => true,
                                    'onkeyup' => '
                                         var str = $(this).attr("id");
                                         indexDet = str.slice(14,15);

                                         var descuento = $(this).val(); 
                                         tdescuento = tdescuento + descuento;

                                    ',
                                    'readonly'=>$readonly
                                    ]) ?>
                            </div>
                            <!-- <div class="col-sm-2">
                                <label class="control-label">Total Item</label>
                                <input type="text" name="totalItem" id="totalItem" class="form-control">
                            </div> -->

                        </div><!-- end:row -->

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>
    <?php if ($currentAction != 'view'){ ?>
                            <div class="col-sm-2 pull-right">
                                <label class="control-label">Total</label>
                                <input type="text" name="total" id="total" class="form-control" value="0.00">
                            </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
   
</div>

<?php Modal::begin([
    "id"=>"responseModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
<script type="text/javascript">
var tdescuento = 0;
    function calcularTotal(){
    var total = 0;
                                                $("[data-subtotal]").each(function(){ 
                                                    total = total + parseFloat($(this).attr("data-subtotal"));
                                                     //console.log($(this).attr("data-subtotal")); 
                                                 });
                                                 $("#total").val(parseFloat(total).toFixed(2));
}

</script>