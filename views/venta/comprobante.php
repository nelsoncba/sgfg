<?php 
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveQuery;
use yii\db\ActiveRecord;
use app\models\Venta;
use app\models\Pedido;
use app\models\DetallePedido;
use app\models\Cliente;


CrudAsset::register($this);
$codigo = Yii::$app->request->get('codigo');

$modelVenta = Venta::findOne(['codigo'=>$codigo]);
$modelPedido = Pedido::findOne(['id'=>$modelVenta->pedido_id]);
$modelCliente = Cliente::findOne(['codigo'=>$modelPedido->cliente_codigo]);
$modelsDetalle = DetallePedido::findAll(['pedido_id'=>$modelPedido->id]);

$fechaArray = explode("-",$modelVenta->fechaGeneracion);

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div style="height: 950px;">
<div style="margin-left: 450px; margin-top: 40px;"><strong><?= '0001 - '.$modelVenta->codigo ?></strong></div>
<div style="margin-left: 450px; margin-top: 18px;">
	<div style="display: inline;">
		<div style="width: 50px;float: left;"><?= $fechaArray[2] ?></div><div style="width: 50px;margin-left: 5px;float: left;"><?= $fechaArray[1] ?></div><div style="width: 50px;margin-left: 5px;float: left;"><?= $fechaArray[0] ?></div>
	</div>
</div>
<div style="margin-left: 100px; margin-top: 80px;"><?= $modelCliente->persona->nombre.' '.$modelCliente->persona->apellido ?></div>
<div style="margin-left: 100px; margin-top: 10px;"><?= $modelCliente->persona->domicilio->calle.' '.$modelCliente->persona->domicilio->numero.', '.$modelCliente->persona->domicilio->localidad->nombre ?></div>
<div style="display: inline;margin-top: 100px;">
<?php foreach ($modelsDetalle as $modelDetalle) {
 ?>
<div style="display: inline;margin-top: 5px;">
	<div style="width: 50px;float: left;"><?= $modelDetalle->cantidad ?></div><div style="width: 470px;float: left;"><?= $modelDetalle->producto->nombre ?></div><div style="width: 70px;float: left;"><?= $modelDetalle->producto->precioUnitario ?></div>
</div>
<?php } ?>
</div>
</div>
<div style="margin-left: 610px; font-size: 15px;min-width: 90px;right:-25px !important;"><?= $modelVenta->montoCobrado ?></div>
</body>
</html>