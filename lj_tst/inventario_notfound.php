<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));

$include = '../';
require("../cab_novo.php");
require($include.'sisdoc_data.php');
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD><td><a class="botao-geral" href="inventario_excel.php">Exportar</a></td></TR>
</table>
<?
require("../_classes/_class_estoque.php");
$est = new estoque;
require("db_temp.php");
echo '<h1>Peças não localizadas</h1>';
echo $est->Inventario_pecas_falta();
echo $hd->foot();	
?>