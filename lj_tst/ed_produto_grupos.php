<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('ed_produto_grupos.php','Classificação de produtos'));


$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include."sisdoc_colunas.php");

require("../_class/_class_produto.php");
$pr = new produto;

$tab_max = '98%';
$tabela = "produto_grupos";
$idcp = "pg";

echo '<h1>Classificação de Produtos</h1>';

$http_edit = 'produto_grupos_ed.php'; 
$editar = true;
$http_redirect = page();

$cdf = array('id_'.$idcp,$idcp.'_descricao',$idcp.'_codigo',$idcp.'_ativo');
$cdm = array('Código','descricao','codigo','ativo');
$masc = array('','','','','','$R','SN');
$busca = true;
$offset = 20;
//	$pre_where = " (ch_ativo = 1) ";
$order  = $idcp."_codigo ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?>