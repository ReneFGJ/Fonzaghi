<?
$include = '../';
require("../cab_novo.php");

require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

require("db_temp.php");

$sql = "SELECT kh_acerto, kh_pago, kh_pc_forn, kh_pc_vend, kh_vlr_forn, kh_vlr_vend ";
$sql .= " FROM kits_consignado ";
$sql .= " where kh_status= 'B' and kh_cliente = '2704254' ";
$sql .= " order by kh_acerto desc ";
echo '<br>'.$sql;
echo '<br>';
?>
<img width="125" height="80" src="http://chart.apis.google.com/chart?cht=lc&chs=125x80&&chd=s:GGGHHIIGHGD&chco=676767&chls=4.0,3.0,0.0&chxt=x,y&chf=c,lg,45,ffffff,0,76A4FB,0.75|bg,s,FFFFFF&chl=Julho-2009|Agosto-2009|Setembro-2009|Outubro-2009|Novembro-2009|Dezembro-2009|Janeiro-2010|Fevereiro-2010|Mar%E7o-2010|Abril-2010|Maio-2010">