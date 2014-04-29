<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Home'));
array_push($breadcrumbs, array('/fonzaghi/avaliacoes/index.php','avaliações'));

$include = '../';
require('../cab_novo.php');
$user->le($_SESSION['nw_cracha']); 
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_debug.php');
require($include.'_class_form.php');
$form = new form;

require('../_class/_class_avaliacao_competencia.php');
$aval = new avaliacao;

echo '<h1>Avaliações funcionário X competência</h1>';
$cp=array();
$op_avaliador = $aval->lista_avaliadores();

array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$O '.$op_avaliador,'','Avaliador',false,True));
$avaliador=$dd[1];

 /* Mostra resultados */
$tela = $form->editar($cp,'');
if ($form->saved > 0)
    {
        echo $tela;
        echo $aval->relatorio_avaliador($avaliador);
    }else{
        echo$tela;
    }

/* Rodape */
echo $hd->foot();
?>