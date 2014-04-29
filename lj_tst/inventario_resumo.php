<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));

$include = '../';
require("../cab_novo.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
require("../_classes/_class_estoque.php");
$est = new estoque;
require("db_temp.php");

//$est->inventario_geral();

echo $est->inventario_resumo();


echo $hd->foot();	?>