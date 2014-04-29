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
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/aval_cargo_gestor.php','Avaliação'));

require("cab.php");

require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");
/* Chama banco de funcionario */
require("../db_fghi.php");
/* classe avaliação*/
require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
/* classe cargos*/
require("../_class/_class_cargos.php");
$cargo = new cargos;

            $op_func = $user->lista_funcionarios_option();
            $op_car = $cargo->lista_cargos_option();
            $op_loja = $aval->lista_lojas_option();
            $cp = array();
   
            array_push($cp,array('$H8','id_aagc','',false,True));
            array_push($cp,array('$O '.$op_loja,'aagc_loja','Loja',false,True));
            array_push($cp,array('$O '.$op_car,'aagc_cargo','Cargo',false,True));
            array_push($cp,array('$O '.$op_func,'aagc_gestor','Avaliador',false,True));
            array_push($cp,array('$O 1:Ativo&0:Não ativo','aagc_ativo','Status',True,True));

	$tabela = 'aval_gestor_cargo';
	$editar = True;
	$http_redirect = '';

/* Chama banco da avaliacao */
require("../db_drh.php");

$tab_max = "100%";
echo '<div id="content">';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	editar();	
	echo '</table>';	
echo '</div>';

if ($saved > 0)
	{
		redirecina("aval_cargo_gestor.php");
	}
require("foot.php");
?>