<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require($include.'sisdoc_data.php');
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

echo '<h1>Baixa de peças</h1>';


if ($dd[0]=='S')
	{
		echo $est->Inventario_pecas_baixar();
		echo 'BAIXA OK';
	} else {
		echo 'Confirmar baixa de peças não localizadas ? ';
		echo '<A HREF="inventario_down.php?dd0=S">SIM</A>';
	}

echo $hd->foot();
?>