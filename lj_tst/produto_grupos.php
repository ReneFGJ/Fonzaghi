<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('ed_produto_grupos.php','Cadastro de grupos de produtos'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include."sisdoc_colunas.php");
require("../_classes/_class_produto_grupos.php");
$ct = new produto_grupos;

$tabela = $ct->tabela;
$idcp = "pg";
$label = "Cadastro de Grupos de Produtos";
$http_edit = 'produto_grupos_ed.php'; 
//$http_ver = "produtos_estoque_individual.php";

$editar = true;
$http_redirect = page();
$cdf = array('id_'.$idcp,$idcp.'_codigo',$idcp.'_class',$idcp.'_descricao',$idcp.'_g1',$idcp.'_g2',$idcp.'_g3','pg_ref');
$cdm = array('Código',$idcp.'_codigo','Class','descricao','codigo','G1','G2','G3','Desconto');
$masc = array('','','','','#','#','#','$R','%','');
$busca = true;
$offset = 60;
if (strlen($dd[1]) == 0) { $pre_where = "  (pg_ativo = 1) "; }
$order  = $idcp."_codigo ";
//exit;
$tab_max = '100%';
echo '<TABLE width="99%" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';

echo $hd->foot();
?>