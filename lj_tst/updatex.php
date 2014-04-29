<?
ob_start();
$include = '../';
require("../db.php");
require("db_temp.php");
require($include."sisdoc_debug.php");
$dr = 'ed_'.$dd[0].'.php';


if ($dd[0] == 'produto')
	{$dx1 = "p_codigo";	$dx2 = "p"; 	$dx3 = "6";}

if (strlen($dx1) > 0)
	{
	$sql = "update ".$dd[0]." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
	
	//echo $sql;
	$rlt = db_query($sql);
	}
echo $sql;	
header("Location: ".$dr);
echo 'Stoped'; exit;
?>