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
require("../_class/_class_cargos.php");

$cargo = new cargos;

	$tabela = $cargo->tabela;
	$editar = True;
	$http_redirect = 'aval_cargos.php';
	$cargo->row_cargo();
	$busca = true;
	$offset = 8;

$tab_max = "100%";
echo '<div id="content">';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	require($include.'sisdoc_colunas.php');
	require($include.'sisdoc_row.php');	
	echo '</table>';	
echo '</div>';

require("foot.php");
?>
