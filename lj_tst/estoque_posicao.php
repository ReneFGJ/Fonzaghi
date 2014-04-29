<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_baixa.php','Baixa de estoque de produto danificado/amostra'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_data.php");
require("db_temp.php");

require("../_classes/_class_estoque.php");
$est = new estoque;
require("db_temp.php");

echo $est->posicao_estoque();

require("../foot.php");
?>
