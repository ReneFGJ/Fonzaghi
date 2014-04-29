<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produto.php','Cadastro de produtos'));

$include = '../';
require("../cab_novo.php");

require("db_temp.php");
require($include."sisdoc_colunas.php");
require("../_classes/_class_produto.php");
$ct = new produto;

echo '<h1>Produtos - Cadastro</h1>';

$tabela = $ct->tabela;
$idcp = "p";

$http_edit = 'produto_ed.php'; 
$http_edit_para = '&dd99='.$tabela; 
$http_ver = "produtos_estoque_individual.php";

$editar = true;
$http_redirect = $tabela.'.php';
$cdf = array('id_'.$idcp,$idcp.'_descricao',$idcp.'_codigo',$idcp.'_ean13',$idcp.'_class_1',$idcp.'_preco',$idcp.'_ativo',$idcp.'_custo');
$cdm = array('Código','descricao','codigo','EAN13','Class','preco','ativo','Custo');
$masc = array('','','','','','$R','SN','$R');
$busca = true;
$offset = 20;
$tab_max = '99%';
if (strlen($dd[1]) == 0) { $pre_where = "  (p_ativo = 1) "; }
$order  = $idcp."_descricao ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';

echo $hd->foot();
?>
<div id="focus"></div>
<script>
	$("#focus").focus();
</script>