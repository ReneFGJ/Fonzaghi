<?
ob_start();
$include = '../';
require("../db.php");
require($include."sisdoc_debug.php");
$dr = 'ed_'.$dd[0].'.php';

require("../db_fghi_206_cadastro.php");

if ($dd[0] == 'capacitacao_agenda')
	{$dx1 = "ca_codigo";	$dx2 = "ca"; 	$dx3 = "7"; $dr = "ed_".$dd[0].".php"; }

if (strlen($dx1) > 0)
	{
	$sql = "update ".$dd[0]." set ".$dx1."=lpad(id_".$dx2.",".$dx3.",0) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
	$sql = "update ".$dd[0]." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
	
	//echo $sql;
	$rlt = db_query($sql);
	}
echo $sql;	
header("Location: ".$dr);
echo 'Stoped '.$dr; exit;
?>