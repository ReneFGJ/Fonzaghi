<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('fornecimentos.php','Tabela de produtos consignados'));

$include = '../';
require($include."cab_novo.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_data.php");
require("../db_fghi_206_cadastro.php");
$tabela = "cadastro";
$idcp = "cl";
$label = "Clientes";
$http_edit = 'ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = false;

$http_ver = 'tabela_fornecidos2.php';

$http_redirect = 'fornecimentos.php';
$cdf = array($idcp.'_cliente',$idcp.'_nome',$idcp.'_cliente',$idcp.'_dtcadastro',$idcp.'_dtnascimento');
$cdm = array('Código','Nome','Codigo','cadastro','aniversário');
$masc = array('','','','','','');
$busca = true;
$offset = 20;
//$pre_where = " (strlen(trim(cl_nome)) > 2) ";
$order  = $idcp."_nome ";

//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?>