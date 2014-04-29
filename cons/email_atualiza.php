<?php
$nocab = 1;

require('cab_novo.php');
require('_class/_class_consultora.php');
$cons = new consultora;

$verb = $dd[60];

/* 
 * Atualiza e-mail 
 **/
if ($verb=='atualizar_email')
	{
	$id=$dd[50];
	$email=$dd[51];
	
	$sx = $cons->atualizar_email_auto($id,$email);
	
	echo '<font color=green >'.$sx.' em '.date("d/m/Y H:i:s").'</font>';
	}
/* 
 * Desativa e-mail 
 **/	
if ($verb=='desativar_email')
	{
	$id=$dd[50];
	$email=$dd[51];
	
	//$con->desativa_email_auto($id,$email);
	
	echo '<font color=red >DESATIVADO</font>';
	}
	
?>

