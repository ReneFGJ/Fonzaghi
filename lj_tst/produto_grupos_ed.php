<?php

array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produto_grupos_ed.php','Edição de grupos de produto'));

$include = '../';
require("../cab_novo.php");
require('../_class/_class_produto_grupos.php');
global $acao,$dd,$cp,$tabela;

require($include.'_class_form.php');
$form = new form;

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require("db_temp.php");

	$cl = new produto_grupos;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = 'produto_grupos_ed.php';
	$http_redirect = '';
	$tit = 'Cadastro de Grupos de Produto';
	echo '<h1>'.$tit.'</h1>';
	/** Comandos de Edição */
	$tela = $form->editar($cp,$tabela);
	
	/** Caso o registro seja validado */
	if ($form->saved > 0)
		{
			$cl->updatex();
			redirecina('produto_grupos.php');
		} else {
			echo $tela;
		}
echo $hd->foot();
?>

