<?php
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Home'));
$pREFE = 'MAS001';
$include = '../';
require("../cab_novo.php");

require($include.'sisdoc_menus.php');

$menu = array();
/////////////////////////////////////////////////// MANAGERS
echo '<H1><B>M�dulo de Avalia��o de Compet�ncias </B></h1>';
if ($perfil->valid('#DBI#ADM#DRH'))
    {
array_push($menu,array('Cadastro','Compet�ncias','aval_competencias.php'));
array_push($menu,array('Cadastro','Cargos','aval_cargos.php'));
array_push($menu,array('Cadastro','Gestores por cargo','aval_gestor_cargo.php'));
array_push($menu,array('Cadastro','Gestores por gestor','aval_cargo_gestor.php'));
array_push($menu,array('Cadastro','Cargos por funcion�rios','aval_cargo_func.php'));
array_push($menu,array('Cadastro','Pesos por compet�ncia','aval_peso.php'));

    }
if ($perfil->valid('#MST'))
    {

		array_push($menu,array('Cadastro','Pesos por compet�ncia lista','aval_peso_lista.php'));
	}

	array_push($menu,array('Relat�rios','Individual - detalhado','aval_rel_individual.php'));
	array_push($menu,array('Relat�rios','Individual - simplificado','aval_rel_simplificado.php'));
	//array_push($menu,array('Relat�rios','Por avaliador','aval_rel_avaliador.php'));
	

    
array_push($menu,array('Avalia��es','Auto-avalia��o','aval_individual_acesso.php'));

//if ($perfil->valid('#MST'))
 //   {
		array_push($menu,array('Avalia��es','Grupo','aval_grupo.php'));
//	}

$tela = menus($menu,"3");

echo $hd->foot();
?>
