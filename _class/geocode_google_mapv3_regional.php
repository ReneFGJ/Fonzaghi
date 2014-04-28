<?
$breadcrumbs=array();

$include = '../';
require("../cab_novo.php");
require("../_class/_class_consultora.php");
require("../_class/_class_regionais.php");
require($include."sisdoc_lojas.php");
$rg = new regional;
require("../_class/_class_geocode.php");
$geo = new geocode;
require('../_class/_class_botoes.php');
$bot = new form_botoes;
setlj2();
$regionais = array();
$regionais = $rg->option_regionais();
$lojas = $rg->option_lojas();

$geo->regional_array = $regionais;
$geo->lojas_array = $lojas;
$rg->regional_array = $regionais;
$rg->lojas_array = $lojas;
$geo->width = 800;
$geo->height = 600;

if(strlen(trim($dd[3])==0)){$dd[3]=0;}
if(strlen(trim($dd[4])==0)){$dd[4]=count($setlj[4])+1;}
//data
$d1=substr($dd[1],6,4).substr($dd[1],3,2).substr($dd[1],0,2); //data inicial
$d2=substr($dd[2],6,4).substr($dd[2],3,2).substr($dd[2],0,2); //data final
if (strlen(trim($d1))==0) 
{
	$d1=(date('Y')-1).'0101';
	$dd[1]=substr($d1,6,2).'/'.substr($d1,4,2).'/'.substr($d1,0,4);
}
if (strlen(trim($d2))==0) 
{
	$d2=date('Ymd');
	$dd[2]=substr($d2,6,2).'/'.substr($d2,4,2).'/'.substr($d2,0,4);
}
$rg->dt1 = $d1;
$rg->dt2 = $d2;
$geo->regional = $dd[3];
$geo->loja = $dd[4];
$rg->loja = $dd[4];
$rg->vendas_lojas();

$tela = '<div style="float:center; position:absolute; padding: 5px 5px 5px 5px; width:40%">';
/*Metodo gera_google_mapsv3 tem que ser executado antes dos outros para carregar query de clientes*/
$tela2 .=  $geo->gera_google_mapv3(1);

$tela1 .=  $rg->mostra_vendas_loja();
$tela1 .=  $bot->action('bi/geocode_google_mapv3_regional.php',2);
$tela1 .=  $bot->data('Data inicial');
$tela1 .=  $bot->data('Data final');
$tela1 .=  $bot->mostrar_botoes($regionais);
$tela1 .=  $bot->mostrar_botoes($lojas);
$tela1 .=  $bot->submit();
$tela .= $tela1.$tela2;
$tela .=  '</div><div style="float:right; padding: 5px 50px 5px 5px; width:60%">';
$tela .=  '<center>';


echo $tela;
$hd->foot();
?>
