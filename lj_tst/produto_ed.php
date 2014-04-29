<?php
$breadcrumbs = array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produto.php','Cadastro de produtos'));
array_push($breadcrumbs, array('produto_ed.php','Edição de produto'));

$include = '../';
require('../cab_novo.php');

require($include.'sisdoc_debug.php');
require('../_classes/_class_produto.php');
global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
	$cl = new produto;
	$cl->option_fornecedor();
	require("db_temp.php");
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = 'produto_ed.php';
	$http_redirect = '';
	$tit = 'Cadastro de Produto';

	/** Comandos de Edição */
	echo '<CENTER><font class=lt5>'.$tit.'</font></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			$cl->updatex();
			redirecina('produto.php');
		}
		
?>

