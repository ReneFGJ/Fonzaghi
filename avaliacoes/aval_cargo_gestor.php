<?php
ob_start();
    /**
     * Sistema de Avaliações
	 * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package avaliacao
	 * @subpackage classe
    */



$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliação'));

    
require("cab.php");
//require("../db_drh.php");
require("../_class/_class_avaliacao_competencia.php");

/* Chama banco de funcionario */
require("../db_fghi.php");
$func = $user->lista_funcionarios();

/* Novo modelo */
require("../db_drh.php");

$ges = new avaliacao;
echo $ges->cargo_gestor($func);

require("foot.php");
?>
