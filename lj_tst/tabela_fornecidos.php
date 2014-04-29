<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/fornecimentos.php','Tabela de produtos consignados'));
array_push($breadcrumbs, array('/fonzaghi/sensual/tabela_fornecidos.php','Visualizar item'));

$include = '../';
require("../cab_novo.php");
require("tabela_fornecidos_a.php");
?>
<A HREF="javascript:newxy2('tabela_fornecidos_popup.php?dd0=<?=$dd[0];?>',760,650);">Imprimir</A>
<?
echo $hd->foot();	?>