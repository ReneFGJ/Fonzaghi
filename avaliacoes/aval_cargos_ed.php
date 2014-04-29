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
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliação'));
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/aval_cargos.php','Cargos'));
 
require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");
require("../db_fghi.php");
/* classe avaliação*/
//require("../_class/_class_avaliacao_competencia.php");
//$aval = new avaliacao;
 /* classe cargos*/
require("../_class/_class_cargos.php");
$cargo = new cargos;

           
           
            $cp = array();
            $cp = $cargo->cp_cargo();

	$tabela = $cargo->tabela;
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
		$cargo->updatex_cargo();
		redirecina("aval_cargos.php");
	}
require("foot.php");
?>