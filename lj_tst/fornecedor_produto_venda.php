<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));

$include = '../';
require("../cab_novo.php");
?>
<table width="98%">
<TR><TD>
<img src="img/logo_empresa.png" align="right" alt="" border="0">
</TD></TR>
</table>
<?

require($include."sisdoc_colunas.php");

$tabela = "fornecedores";
$idcp = "fo";
$label = "Cadastro de Fornecedores";	 
$editar = false;
$http_redirect = 'fornecedor_produto_venda.php';
$cdf = array('id_'.$idcp,$idcp.'_nomefantasia',$idcp.'_razaosocial',$idcp.'_codfor',$idcp.'_status');
$cdm = array('Código','Fantasia','Razão social','Status','Ativo');
$masc = array('','','','','');
$busca = true;
$offset = 20;
$http_ver = 'fornecedor_produto_venda_ver.php';
//if (strlen($dd[1]) == 0)
//	{ $pre_where = " (fo_status = 'S' or fo_status = 'A') "; }
$order  = $idcp."_nomefantasia ";
//exit;
$tab_max = '99%';
echo '<TABLE width="98%" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';

echo $hd->foot();
?>