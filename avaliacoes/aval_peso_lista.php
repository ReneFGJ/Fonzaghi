<?php
    /**
     * Sistema de AvaliaÃ§Ãµes
     * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package avaliacao
     * @subpackage cadastro
    */

$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliação'));
require("cab.php");
require("../db_fghi.php");
require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
require("../_class/_class_cargos.php");
$cargo = new cargos;

echo $aval->peso_competencia_lista();

require("foot.php");
?>
