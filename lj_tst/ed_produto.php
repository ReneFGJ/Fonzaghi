<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('ed_produto.php','Cadastro de produtos'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include."sisdoc_colunas.php");
$tab_max = '95%';
$tabela = "produto";
$idcp = "p";
$label = "";
$http_edit = 'ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$http_ver = "produtos_estoque_individual.php";

$editar = true;
$http_redirect = 'ed_'.$tabela.'.php';
$cdf = array('id_'.$idcp,$idcp.'_descricao',$idcp.'_codigo',$idcp.'_ean13',$idcp.'_class_1',$idcp.'_preco',$idcp.'_ativo');
$cdm = array('Código','descricao','codigo','EAN13','Class','preco','ativo');
$masc = array('','','','','','$R','SN');
$busca = true;
$offset = 20;
if (strlen($dd[1]) == 0) { $pre_where = "  (p_ativo = 1) "; }
$order  = $idcp."_descricao ";
//exit;
echo '<h1>Cadastro de Produtos</h1>';
echo '<center><TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?>