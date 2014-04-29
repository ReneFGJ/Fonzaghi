<?
$include = '../';
$nocab=1;
require("../cab_novo.php");
require("../_classes/_class_estoque.php");
require($include.'sisdoc_data.php');
$est = new estoque;
require("db_temp.php");
header("Content-type: application/vnd.ms-excel; name='excel' ");
header("Content-Disposition: filename=inventario.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo $est->Inventario_pecas_falta();
?>