<?php
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Home'));
$pREFE = 'MAS001';
$include = '../';
require("../cab_novo.php");

require($include.'sisdoc_menus.php');

$menu = array();
/////////////////////////////////////////////////// MANAGERS
echo '<H1><B>Módulo de Avaliação de Competências </B></h1>';
if ($perfil->valid('#DBI#ADM#DRH'))
    {
array_push($menu,array('Cadastro','Competências','aval_competencias.php'));
array_push($menu,array('Cadastro','Cargos','aval_cargos.php'));
array_push($menu,array('Cadastro','Gestores por cargo','aval_gestor_cargo.php'));
array_push($menu,array('Cadastro','Gestores por gestor','aval_cargo_gestor.php'));
array_push($menu,array('Cadastro','Cargos por funcionários','aval_cargo_func.php'));
array_push($menu,array('Cadastro','Pesos por competência','aval_peso.php'));

    }
if ($perfil->valid('#MST'))
    {

		array_push($menu,array('Cadastro','Pesos por competência lista','aval_peso_lista.php'));
	}

	array_push($menu,array('Relatórios','Individual - detalhado','aval_rel_individual.php'));
	array_push($menu,array('Relatórios','Individual - simplificado','aval_rel_simplificado.php'));
	//array_push($menu,array('Relatórios','Por avaliador','aval_rel_avaliador.php'));
	

    
array_push($menu,array('Avaliações','Auto-avaliação','aval_individual_acesso.php'));

//if ($perfil->valid('#MST'))
 //   {
		array_push($menu,array('Avaliações','Grupo','aval_grupo.php'));
//	}

$tela = menus($menu,"3");

echo $hd->foot();
?>
