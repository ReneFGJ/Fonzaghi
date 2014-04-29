<?

    /**
     * Sistema de avaliação de competências
     * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package avaliacao
     * @subpackage operacional
    */
ob_start();
    
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','Avaliação'));
    
$include = '../';
require("../cab_novo.php");

//* conteudo */
require($include."sisdoc_debug.php");
require($include."sisdoc_data.php");
require($include."sisdoc_tips.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");
require($include."sisdoc_colunas.php");

/* Class */
require("../_class/_class_avaliacao_competencia.php");
require("../db_cadastro.php");
require("../db_drh.php");

echo '<h1>Avaliação do grupo</h1>';
echo '<center>';
echo '<div align="center" class="pg_white border padding5 wc80">'.$_SESSION['nw_cracha'].' - '.$_SESSION['nw_user_nome'].'</div>';
echo '<BR>';
$avaliador= $dd[1]=$_SESSION['nw_cracha'];

echo '<div align="center" class="pg_white border padding5 wc80">';

/* Variavies */
$avaliador= $dd[1];
$competencia = round($dd[2]);
$grupo = new avaliacao;

if($competencia==0){ $dd[2]= $competencia=$grupo->ultima_pagina(); }
if($competencia==$grupo->ttcomp) { $dd[2]= $competencia=$grupo->ultima_pagina(); }

/* Pula se já em preenchimento **/

	echo $grupo->avaliacao_grupo($avaliador,$competencia);
	$dd[2]= $competencia=$grupo->ultima_pagina();

echo '</div>';
?>

