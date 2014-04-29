<?
$breadcrumbs=array();

$include = '../';

require($include."cab.php");
require($include."sisdoc_data.php");
require($include."sisdoc_grafico.php");
require($include."sisdoc_debug.php");

require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");


$estilo = '';
$tabela = "";
$cp = array();
array_push($cp,array('$D8','','Data ',True,True,''));
array_push($cp,array('$D8','','Até ',True,True,''));

if (strlen($dd[0]) ==0) { $dd[0] = date("01/m/Y"); }
if (strlen($dd[1]) ==0) { $dd[1] = date("d/m/Y"); }

/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<br>';
	echo '<FONT class="lt5">Fornecimento</FONT>';

	echo '<TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';

if ($saved < 1){
	//echo '<font class="lt0">(Parou '.$saved.')</font> '; 
	echo $hd->foot();
	exit; 
} else {
	$dd1 = brtos($dd[0]);
	$dd2 = brtos($dd[1]);
	require("db_temp.php");
	
	$sqlx = "
	select count(*) as total from kits_consignado
	where kh_fornecimento >= $dd1 and kh_fornecimento <= $dd2 ";
	
	$rlt = db_query($sqlx);
	$line = db_read($rlt);
	echo '<center><h2>Total de '.$line['total'].' fornecimento(s) no periodo</h2>';
}
echo $hd->foot();?>

?>