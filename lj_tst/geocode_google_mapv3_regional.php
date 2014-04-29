<?
$breadcrumbs=array();

$include = '../';
require("../cab_novo.php");
require("../_class/_class_consultora.php");
require("../_class/_class_regionais.php");
$rg = new regional;
require("../_class/_class_geocode.php");
$geo = new geocode;
require('../_class/_class_botoes.php');
$bot = new form_botoes;

$regionais = array();
$regionais = $rg->option_regionais();
$geo->regional_array = $regionais;
//$lojas = array();
//array_push($lojas,array('Todos','3'));
//array_push($lojas,array('>= 5000','4'));
//array_push($lojas,array('<  5000','5'));

if(strlen(trim($dd[1])==0)){$dd[1]=0;}
//if(strlen(trim($dd[2])==0)){$dd[2]=3;}

echo '<div style="float:left; position:absolute">';
echo $bot->action('bi/geocode_google_mapv3_regional.php',2);
echo $bot->mostrar_botoes($regionais);
//echo $bot->mostrar_botoes($lojas);
echo $bot->submit();
echo '</div><div style="float:right;">';
echo '<center>';
$geo->regional = $dd[1];
//$geo->loja = $dd[2];
echo $geo->gera_google_mapv3(1);
echo 'aqui';

$hd->foot();
?>
