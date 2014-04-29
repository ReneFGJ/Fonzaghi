<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_data.php');

require('../_class/_class_produto.php');
$pd = new produto;

require("db_loja.php");
echo $pd->inventario_razao_faltas();

/* Rodape */
echo $hd->foot();
?>