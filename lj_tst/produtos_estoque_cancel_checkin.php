<?
$include = "../";
require("../db.php");
require("db_temp.php");
if (strlen($dd[0]) == 6)
	{
	$sql  = "delete from produto_log_201002 where (pl_status = 'H' or pl_status = 'X') and pl_produto = '".$dd[0]."'; ";
	$sql .= "delete from produto_log_201003 where (pl_status = 'H' or pl_status = 'X') and pl_produto = '".$dd[0]."'; ";
	$sql .= "delete from produto_log_201004 where (pl_status = 'H' or pl_status = 'X') and pl_produto = '".$dd[0]."'; ";
	$rlt = db_query($sql);
	}
require("../close.php");
?>