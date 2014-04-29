<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produto.php','Cadastro de produtos'));

$include = '../';
require("../cab_novo.php");

require("db_temp.php");
require($include."sisdoc_colunas.php");
require("../_class/_class_produto_categoria.php");
$ct = new categoria;

echo '<h1>Atribuitos</h1>';

$tabela = ' (
select t2.id_ct as id_ct, t2.ct_codigo as ct_codigo,
		 t1.ct_descricao as grupo, 
		 t2.ct_descricao as categoria
	from '.$ct->tabela.' as t1
	left join '.$ct->tabela.' as t2 on t2.ct_ref = t1.ct_codigo
	where not t2.ct_codigo isnull and t2.ct_ativo = 1
	order by t1.ct_ordem, t2.ct_ordem
	) as tabela
'
;
$idcp = "ct";

$http_edit = 'categorizacao_grupos_ed.php'; 

$editar = true;
$http_redirect = page();
$cdf = array('id_'.$idcp,'grupo','categoria','ct_codigo');
$cdm = array('Código','descricao','codigo','principal','Ação');
$masc = array('','H','','SN','','','','');
$busca = true;
$offset = 120;
$tab_max = '99%';

$order  = " grupo, categoria ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';

/* echo $hd->foot(); */
?>
