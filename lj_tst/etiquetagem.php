<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';
$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('Produtos','Cadastro de produtos','produto.php')); 
array_push($menu,array('Produtos','Classifica��o de produtos','produto_grupos.php')); 
array_push($menu,array('Produtos','Rastreamento de produto','produtos_rastreio.php')); 

array_push($menu,array('Reposi��o de Etiquetas','Converter em pe�as promocionais','etiquetagem_promocional.php')); 
if($nloja=='J')
{
	array_push($menu,array('Etiquetagem','Impress�o de Etiquetas','etiquetagem_joias.php'));
}else{
	array_push($menu,array('Etiquetagem','Impress�o de Etiquetas','etiquetagem_pr.php'));
}
if($nloja=='J')
{
	if ($perfil->valid('#ADM#COJ'))
	{
		array_push($menu,array('Etiquetagem','Zerar pe�as do mostruario','etiquetagem_zera_peca.php')); 
	}
}
array_push($menu,array('P�s-pedido','Troca de c�digos','etiquetagem_troca.php')); 
array_push($menu,array('P�s-pedido','Consultar c�digos','etiquetagem_consulta.php')); 

///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{
		 if ($dd[1]==CharE($menu[$k][1])) {	header("Location: ".$menu[$k][2]); } 
		}
	}
?>

<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="index.php">
</TD></TR>
</TABLE>
<TABLE width="710" align="center" border="0">
<TR>
<?
	$tela = menus($menu,"3");
?>
<? echo $hd->foot();	?>