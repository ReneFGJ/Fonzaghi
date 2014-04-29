<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Home'));
    
$include = '../';
require("../cab_novo.php");
//* conteudo */
require($include."sisdoc_data.php");
require($include."sisdoc_tips.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
require("../_class/_class_form.php");
$form = new form;

$cp = array();

array_push($cp,array('$S8','','Usuário',false,True));
array_push($cp,array('$P8','','Senha',false,True));

$menu = $form->editar($cp, '');

if(trim($form->saved)>0)
{
	$vld = $aval->valida_usuario($dd[0],$dd[1]);
	if($vld==1)
	{
		redirecina('aval_individual.php');
	}else{
		echo "<h1>Informe o usuário do sistema para ter acesso a avaliação.</h1>";
		echo $menu;
	}
}else{
	echo "<h1>Informe o usuário do sistema para ter acesso a avaliação.</h1>";
	echo $menu;
}

?>