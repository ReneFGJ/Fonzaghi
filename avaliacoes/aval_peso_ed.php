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
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/aval_peso.php','Peso'));
 
require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");
require("../db_fghi.php");
    $cp = array();
    require("../_class/_class_avaliacao_competencia.php");
    $aval = new avaliacao;
    require("../_class/_class_cargos.php");
    $cargo = new cargos;

    $op_car = $cargo->lista_cargos_option();
    $op_comp = $aval->lista_competencia_option();
    $op_loja = $aval->lista_lojas_option();
    $cp = array();
   
    array_push($cp,array('$H8','id_acm','',false,True));
    array_push($cp,array('$O '.$op_loja,'acm_loja','Loja',True,True));
    array_push($cp,array('$O '.$op_car,'acm_cargo','Cargo',True,True));
    array_push($cp,array('$O '.$op_comp,'acm_competencia','Competência',True,True));
    array_push($cp,array('$O 0:Selecione o peso&1:Peso 1&2:Peso 2&5:Peso 5','acm_peso','Peso',True,True));
    array_push($cp,array('$O 0:Selecione o corte&1:Sim&0:Não','acm_corte','Corte',True,True));
    $loja=$dd[1];
    $cargo=$dd[2];
    $competencia=$dd[3];
    $peso=$dd[4];
    $corte=$dd[5];
    $valida='';
    $valida=$aval->valida_aval_competencia_matrix($competencia,$peso,$corte,$cargo,$loja);
    
//    if ($valida==0) 
//    {
        $tabela = 'aval_cargos_matrix';
        $editar = True;
        $http_redirect = '';    
//    }
    
    if ($valida==1) 
    {
        $aval->update_aval_competencia_matrix($competencia,$peso,$corte,$cargo,$loja);
    }
    
    echo '<div id="content">';
    echo '<table width="98%" align="center"><TR><TD>';
    require("../db_206_rh.php");
    editar();   
    echo '</table>';    
    echo '</div>';

    if ($saved > 0)
    {
        require("../db_206_rh.php");
        redirecina("aval_peso.php");
    }

require("foot.php");
?>