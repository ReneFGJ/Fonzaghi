<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Fonzaghi'));
array_push($breadcrumbs, array('/cursos/main.php','Cursos'));

$include = '../';
require("../cab_novo.php");

/* Cabecalho da pagina */
require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';

$menu = array();

/////////////////////////////////////////////////// MANAGERS
echo '<H1>M�dulo de Capacita��o <B>Master</B></h1>';
array_push($menu,array('Agendamento','Agendar uma consultora','master_agendar.php')); 
array_push($menu,array('Agendamento','Relat�rio de Agendamento','master_rel_agendamento.php'));
array_push($menu,array('Agendamento','Mostra agenda','master_agenda.php'));

array_push($menu,array('Gest�o','Relat�rio de participa��o','master_rel_participacao.php')); 

array_push($menu,array('Cadastro','Cursos Master (programa��o)','master_agenda.php'));
array_push($menu,array('Cadastro','Cursos Master (cadastro)','master_cadastro.php'));

$tela = menus($menu,"3");

/* Rodape */
echo $hd->foot();	
?>