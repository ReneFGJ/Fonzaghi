<?php
$include = '../';
$nocab=1;
require('../cab_novo.php');   
require("../_class/_class_cargos.php");
$cargo = new cargos;

require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
echo $dd[0].'--'.$dd[1];
if((strlen($dd[0])==0) or(strlen($dd[1])==0))
{
	redirecina('aval_peso_ed_lista.php');
}else{
	echo $aval->combo_competencia();	
}




?>