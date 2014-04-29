<?php
    /**
     * Sistema de Avaliaчѕes
     * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package avaliacao
     * @subpackage cadastro
    */

$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliaчуo'));
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/aval_peso.php','Peso'));
$include = '../'; 
require("../cab_novo.php");
require($include."sisdoc_colunas.php");
require("../_class/_class_form.php");
$form = new form;
require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
require("../_class/_class_cargos.php");
$cargo = new cargos;
require("../db_206_rh.php");
$op_loja = $aval->lista_lojas_option();
require("../db_fghi.php");
$op_car = $cargo->lista_cargos_option();


$cp= array();

array_push($cp,array('$O '.$op_loja,'','Loja',True,True));
array_push($cp,array('$O '.$op_car,'','Cargo',True,True));

echo $form->editar($cp, '');

if($form->saved>0)
{
	redirecina("aval_form_competencia.php?dd0=".$dd[0]."&dd1=".$dd[1]);
}


echo $hd->foot();
?>