<?
$include = '../';
$nocab=1;
require('../cab_novo.php');
$user->le($_SESSION['nw_cracha']); 

require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');

require($include.'_class_form.php');
$form = new form;

require('../_class/_class_funcionario.php');
$func = new funcionario;

require('../_class/_class_avaliacao_competencia.php');
$aval = new avaliacao;

echo '<h1>Observações.</h1>';
$cp=array();
require("../db_drh.php");
$tabela = 'aval_dados';
/**/array_push($cp,array('$H8','id_avd','',False,False));
/**/array_push($cp,array('$T','avd_obs','',false,True));

 /* Mostra resultados */
$tela = $form->editar($cp,$tabela);
if ($form->saved > 0)
    {
       echo '<script> close(); </script>';
    }else{
        echo$tela;
    }
/* Rodape */
echo $hd->foot();
?>