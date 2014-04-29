<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_senha_gerar.php','Geração de senha'));

$include = '../';
require("../cab_novo.php");
require($include."_class_form.php");
$form = new form;

require("estoque_funcoes.php");
require('../_class/sisdoc_lojas.php');

setlj2();

if ($perfil->valid("#GER#ADM#GEG") == 0)
	{	
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
	}

$cp=array();
$ops = ' : ';
for ($r=0;$r < count($setlj[0]);$r++)
	{
		$ops .= '&';
		$ops .= $setlj[0][$r].':'.$setlj[1][$r];
	}
array_push($cp,array('$O '.$ops,'','Código da Loja',True,True,''));
array_push($cp,array('$B8','','Gerar senha >>>',False,True,''));

echo '<h1>Geração de senha</h1>';

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$chave='fghi';
		$loja=strtoupper($dd[0]);
		$senha=estoque_senha_gerar(date('Ymd').$chave.$loja);
		echo '<br><br><CENTER><font class="lt3">Senha:<b>'.$senha.'</b></font></CENTER>';
		$dd[0]=='';
	} else {
		echo $tela;
	}
echo '<BR><BR><BR>';
echo $hd->foot();
?>
