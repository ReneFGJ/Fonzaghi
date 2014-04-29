<?php
$breadcrumbs = array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produto.php','Cadastro de produtos'));
array_push($breadcrumbs, array('produto_ed.php','Edição de produto'));

$include = '../';
require('../cab_novo.php');

require($include.'sisdoc_debug.php');
require("../_class/_class_produto_categoria.php");

global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'_class_form.php');
$form = new form;

require($include.'sisdoc_data.php');
require("db_temp.php");

	$cl = new categoria;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = page();
	$http_redirect = '';
	$tit = 'Cadastro de Atributos';

	/** Comandos de Edição */
	$tela = $form->editar($cp,$tabela);
	
	/** Caso o registro seja validado */
	if ($form->saved > 0)
		{
			$cl->updatex();
			redirecina('categorizacao_grupos.php');
		} else {
			echo $tela;
		}
		
?>

