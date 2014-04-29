<?
$nocab=1;
$include='../';
require("../cab_novo.php");
require('../_class/_class_form.php');
$form = new form;
require('../_class/_class_consignado.php');
$consig = new consignado;
$cp=array();
$tabela ='';

array_push($cp,array('$H8','','id',False,True,''));
array_push($cp,array('$I','','Limite de Peças',False,True,''));
array_push($cp,array('$I','','Valor Máximo',False,True,''));
array_push($cp,array('$H8','','loja',False,True,''));

echo '<h2>Alteração de limite de peças</h2>';
echo $form->editar($cp,$tabela);

$cl=$dd[0];
$pc=$dd[1];
$vlr=$dd[2];
$lj=$dd[3];
//$_SESSION['#cons02'];
if($form->saved>0)
{
	$consig->grava_limite($cl,$pc,$vlr,$lj);
	echo '<script>close();</script>';
}else{

}

echo $hd->foot();
?>