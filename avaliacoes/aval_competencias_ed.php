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
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/aval_competencias.php','Avaliação'));
 
require("cab.php");
require("../db_drh.php");
require("../_class/_class_avaliacao_competencia.php");
$clx = new avaliacao;

require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");
require("../db_drh.php");

$cp = $clx->cp_competencias();

	$tabela = 'aval_competencias';
	$editar = True;
	$http_redirect = '';

$tab_max = "100%";
echo '<div id="content">';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	editar();	
	echo '</table>';	
echo '</div>';

if ($saved > 0)
	{
		$clx->updatex_competencias();
		redirecina("aval_competencias.php");
	}
require("foot.php");
?>