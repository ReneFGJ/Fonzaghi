<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require($include.'sisdoc_data.php');
require("db_temp.php");
?>
<img src="img/logo_empresa.png" alt="" border="0" align="right">
<h1>Menu Principal</h1>
<h2>Loja - <?=$nloja_nome;?></h2>
<?
require('../_class/_class_consultora.php');

require('../_class/_class_consignado.php');
$cons = new consignado;

echo $cons->relatorio_novas();

echo $hd->foot();
?>