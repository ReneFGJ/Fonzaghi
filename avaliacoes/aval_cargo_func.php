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
array_push($breadcrumbs, array('/fonzaghi/main.php','Home'));
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliações'));

$include = '../';
require('../cab_novo.php');
$user->le($_SESSION['nw_cracha']); 
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'_class_form.php');
$form = new form;

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
   
            array_push($cp,array('$H8','id_us','',false,True));
            array_push($cp,array('$O '.$op_loja,'us_loja','Loja',false,True));
            array_push($cp,array('$O '.$op_car,'us_cargo','Cargo',false,True));
            array_push($cp,array('$O '.$op_func,'us_cracha','Funcionárior',false,True));

$tela = $form->editar($cp,'');

echo '<h1>Relação de funcionários </h1>';
 if ($form->saved > 0)
   {

        $lj=$dd[1];
        $cargo=$dd[2];
        $func=$dd[3];
        echo $aval->cargo_func($func, $lj, $cargo);
        exit;
    } else {
        echo '<table align="center" class="noprint"><tr><td>';
        echo $tela;
        echo '</td></tr></table>';
    }    
/* Rodape */
echo $hd->foot();
?>
