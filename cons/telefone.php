<?
//session_start();
//ob_start();
$include = '../';
$nocab=1;
require("../cab_novo.php");
//require("../db_telefones.php");
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require("../_class/_class_form.php");
$form = new form;
require("../_class/_class_consultora.php");
$cons = new consultora;
require("../_class/_class_telefone.php");
$ct = new telefone;
require('../db_fghi_206_cadastro.php');
$id = $dd[1];
$cons->id = $id;
$tabela = $ct->tabela;
$cp = $ct->cp(); 



	$http_edit = 'telefone.php';
	$http_redirect = '../close.php';
	echo '<h1>Cadastrar Telefones</h1>';
	echo '<TABLE width="100%" align="center"><TR><TD>';
	echo $form->editar($cp,$tabela);
	echo '</TD></TR>';
	echo '<tr><td>';
	echo $cons->le_telefones($id,0) ;
	echo '</td></tr></TABLE>';	
?>