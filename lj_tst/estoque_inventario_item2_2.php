<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item2.php','Lista de �tens inventariados'));

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


if ($user_nivel < 0){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

$corpo = "Favor verificar a solicita��o de baixa de novos invent�rios."; 
?>
<script>
	window.location = 'mailto:marcelo@fonzaghi.com.br;koutton@fonzaghi.com.br?body=<?=$corpo;?>&subject=Notifica��o: Invent�rio';
	window.location = 'estoque_inventario_item2.php';
</script>
<?
echo $hd->foot();
?>
