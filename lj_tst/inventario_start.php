<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));

$include = '../';
require("../cab.php");
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

if ($dd[1]=='SIM')
	{
	$est->inventario_geral();
	}

echo '<h1>Inventário Zerado com Sucesso</h1>';
echo $est->inventario_resumo();

echo '<CENTER>';
echo 'CLIQUE <A HREF="'.page().'?dd1=SIM">AQUI</A> PARA INICIAR CONTAGEM DE ESTOQUE';

require($vinclude."foot.php");	?>