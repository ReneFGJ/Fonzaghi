<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('estoque_imagens.php','Imagens dos produto'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

require('../_classes/_class_produto.php');
require('../_classes/_class_produto_grupos.php');

require("db_temp.php");

$loja='S'; //da loja sensual

$cp=array();
array_push($cp,array('$S6','','Filtro',False,True,''));
array_push($cp,array('$Q pg_descricao:id_pg:select * from produto_grupos where pg_ativo=1 order by pg_descricao','','Grupo das peças',True,True,''));

echo '<CENTER><font class="lt5">Produtos por Imagens</font></CENTER>';
if ($dd[1]==''){
	echo '<TABLE border="0" align="center" width="30%">';
	echo '<TR><TD>';
	editar();
	echo '</TD></TR>';
	echo '</TABLE>';
	echo $hd->foot();	
	exit;
}
$ct = new produto;
$cg = new produto_grupos;
$cg->le($dd[1]);
echo '<H1>'.$cg->pg_descricao.'</h1>';

$sql = "select * from produto where p_class_1 = '".$cg->pg_codigo."' ";
$sql .= " and p_ativo = 1";
$sql .= " order by p_codigo ";
$rlt = db_query($sql);
$sx = '';
$col = 0;

while ($line = db_read($rlt))
	{
		if (($col == 0) or ($col==4))
			{
			$sx .= '<TR valign="top">';
			$col = 0;
			}
		$sx .= '<TD>';
		$ct->le($line['id_p']);
		$sx .= $ct->mostrar_imagem(0);
		$sx .= '<BR>';
		$sx .= $ct->p_codigo;
		$sx .= '<BR>';
		$sx .= $ct->p_descricao;
		$col++;
	}
echo '<table class="lt0" width="10%" border=0>';
echo $sx;
echo '</table>';
require("../foot.php");
?>
