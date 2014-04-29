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

require('../_class/_class_funcionario.php');
$func = new funcionario;

require('../_class/_class_avaliacao_competencia.php');
$aval = new avaliacao;

echo '<h1>Relatório individual simplificado.</h1>';
$cp=array();
require("../db_fghi.php");
$op_funcionario = $func->lista_funcionario();

array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$O 01:Janeiro&02:Fevereiro&03:Março&04:Abril&05:Maio&06:Junho&07:Julho&08:Agosto&09:Setembro&10:Outubro&11:Novembro&12:Dezembro&','','Mês',false,True));
array_push($cp,array('$O '.date('Y').':'.date('Y').'&'.(date('Y')-1).':'.(date('Y')-1).'&'.(date('Y')+1).':'.(date('Y')+1),'','Ano',false,True));
array_push($cp,array('$O '.$op_funcionario,'','Funcionário',false,True));

$funcionario=$dd[3];
$aval->mes = $dd[1];
$aval->ano = $dd[2];

/* Mostra resultados */

$tela = $form->editar($cp,'');
if ($form->saved > 0)
    {
	    echo '<table class="noprint" width="98%"><tr>
        		<td width="50%">'.$tela.$aval->relatorio_simplificado($funcionario).'</td>
        		<td align="50%">'.$aval->historico($funcionario).'</td>
        	 </tr></table>';
        
        
        
    }else{
        echo '<table class="noprint" width="98%"><tr>
        		<td align="50%">'.$tela.'</td>
        		<td align="50%">'.$aval->historico($funcionario).'</td>
        	 </tr></table>';
        
    }

/* Rodape */
echo $hd->foot();
?>