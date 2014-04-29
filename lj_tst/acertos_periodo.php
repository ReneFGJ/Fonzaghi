<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('acertos_periodo.php','Acertos'));

$include = '../';
require("../cab_novo.php");
require($include.'sisdoc_data.php');

echo '<img src="img/logo_empresa.png" height="80" alt="" border="0" align="right">';
echo '<h1>Acertos no Período</h1>';

require("../_class/_class_consignacoes.php");
$cons = new consignacoes;

require($include.'_class_form.php');
$form = new form;

if (strlen($dd[2])==0) { $dd[2] = date("d/m/Y"); }
if (strlen($dd[3])==0) { $dd[3] = date("d/m/Y"); }

$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$D8','','Data Inicial',True,False));
array_push($cp,array('$D8','','Data Final',True,False));
array_push($cp,array('$O N:Não&S:Sim','','Detalhado',True,False));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		require("db_temp.php");		
		$d1 = brtos($dd[2]);
		$d2 = brtos($dd[3]);
		echo $cons->acertos_resumo($d1, $d2);
		if ($dd[4]=='S')
			{
				echo $cons->acertos_detalhe($d1, $d2);
			}
	} else {
		echo $tela;
	}

echo $hd->foot();
?>