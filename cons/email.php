<?
$include = '../';
$nocab=1;
require("../cab_novo.php");
//require($include."db.php");
require("../db_telefones.php");
require($include."sisdoc_data.php");
require("../_class/_class_form.php");
$form = new form;
require("../_class/_class_consultora.php");
$cons = new consultora;
$cons->codigo=$dd[1];
$cp = array();
array_push($cp,array('$H8','id_e','H',False,True,''));
array_push($cp,array('$H8','e_cliente','Cliente',True,True,''));
array_push($cp,array('$H8','','e-mail '.$dd[1],False,True,''));
array_push($cp,array('$S100','e_mail','e-mail',True,True,''));
array_push($cp,array('$O A:Ativo&I:Inativo','e_status','Status',True,True,''));
array_push($cp,array('$U8','e_update','',False,True,''));

$tabela = 'email';
require($include.'sisdoc_colunas.php');

$http_edit = 'email.php';
$http_redirect = '../close.php';
	echo '<h1>Cadastrar e-mail</h1>';
	echo '<TABLE width="100%" align="center"><TR><TD>';
	echo $form->editar($cp, $tabela);
	echo '</TD></TR><tr><td align="center">';
	echo $cons->le_email($cons->codigo,1);
	echo '</TD></TR></TABLE>';	
?>