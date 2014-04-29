<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla > 47 && tecla < 58)) return true;
    else{
    if (tecla != 8) return false;
    else return true;
    }
}
</script>
<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item.php','Ítens à inventariar'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require($include."biblioteca.php");
require("estoque_funcoes.php");
require("db_temp.php");

$loja='S'; //da loja sensual

$cp=array();
array_push($cp,array('$S6','','Digite a referência do produto',True,True,''));

echo '<CENTER><font class="lt5">Inventário</font></CENTER>';
if ($dd[0]==''){
	echo '<TABLE border="0" align="center" width="30%">';
	echo '<TR><TD>';
	editar();
	echo '</TD></TR>';
	echo '</TABLE>';
	echo $hd->foot();	
	exit;
}
redirecina("estoque_inventario_item1.php?dd0=".$dd[0]);
?>
