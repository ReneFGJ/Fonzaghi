<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require($include.'sisdoc_data.php');

require("db_temp.php");
?>
<img src="img/logo_empresa.png" alt="" border="0" align="right">
<h1>Tempo médio de permanência com o Kit</h1>
<h2>Loja - <?=$nloja_nome;?></h2>
<?
require("../_class/_class_consignacoes.php");
$cc = new consignacoes;

require("../_class/_class_consultora.php");
$co = new consultora;

require($include.'_class_form.php');
$form = new form;

$cp = array();
if (strlen($dd[1])==0) { $dd[1] = date("d/m/Y"); }
if (strlen($dd[2])==0) { $dd[2] = date("d/m/Y"); }
array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$D8','','Data inicial',True,True));
array_push($cp,array('$D8','','Data final',True,True));
array_push($cp,array('$O 0:sem preferência&1:Entre 1 e 7 dias&2:Entre 8 e 14 dias&3:Entre 15 e 21 dias&4:Entre 22 e 28 dias&5:Entre 29 e 35 dias&6:Acima de 35 dias','','Permanencia com o Kit',True,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$d1 = brtos($dd[1]);
		$d2 = brtos($dd[2]);
		$tp = $dd[3];
		echo $cc->acertos_tempo_medio($d1,$d2,$tp);	
	} else {
		echo $tela;
	}

echo $hd->foot();
?>