<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item2.php','Lista de ítens inventariados'));

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

if ($user_nivel < 9){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

$sql="SELECT i_cod_produto
  FROM inventario
  where i_baixar_reincorporar_autorizada='1'
	and i_status <> 'I'
	group by i_cod_produto 
	order by i_cod_produto";

$rlt=db_query($sql);

$corpo = "Senha para baixa do inventário: "; 
for($i=0; $i < pg_num_rows($rlt); $i++){
	$line=db_read($rlt);
	$corpo.=$line['i_cod_produto'].' ('.inventarioSenhaGerar($line['i_cod_produto']).'); ';
}

?>
<script>
	window.location = 'mailto:edina@fonzaghi.com.br;koutton@fonzaghi.com.br?body=<?=$corpo;?>&subject=Baixa do inventário';
	window.location = 'estoque_inventario_item3.php';
</script>
<?
echo $hd->foot();
?>)
