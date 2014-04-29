<?php
    /**
     * Sistema de Avaliações
	 * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package avaliacao
	 * @subpackage cadastro
    */

$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliações'));

require("cab.php");
require("../db_drh.php");
require("../_class/_class_avaliacao_competencia.php");

$clx = new avaliacao;

	$tabela = $clx->tabela;
	$editar = True;
	$http_redirect = 'aval_competencias.php';
	$clx->row_competencias();
	$busca = true;
	$offset = 5;

$tab_max = "100%";
echo '<div id="content">';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	require($include.'sisdoc_colunas.php');
	require($include.'sisdoc_row.php');	
	echo '</table>';	
echo '</div>';

require("foot.php");
?>
