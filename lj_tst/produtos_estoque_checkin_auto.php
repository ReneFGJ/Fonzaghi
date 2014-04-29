<?
$red = 0;
if (strlen($dd[0]) == 6)
{
$sql = "select * from produto_estoque  ";
$sql .= "left join ( ";
$sql .= "	select pl_ean13, sum(t) as total from ( ";
$sql .= "	select 1 as t,pl_ean13 from produto_log_201002 where pl_produto = '".$dd[0]."' and pl_status = 'H' ";
$sql .= "	union ";
$sql .= "	select 1 as t,pl_ean13 from produto_log_201003 where pl_produto = '".$dd[0]."' and pl_status = 'H' ";
$sql .= "	union ";
$sql .= "	select 1 as t,pl_ean13 from produto_log_201004 where pl_produto = '".$dd[0]."' and pl_status = 'H' ";
$sql .= "	union ";
$sql .= "	select 1 as t,pl_ean13 from produto_log_201005 where pl_produto = '".$dd[0]."' and pl_status = 'H' ";
$sql .= ") as tabela group by pl_ean13) as tebela02 ";
$sql .= "on pl_ean13 = pe_ean13 ";
$sql .= "where  pe_produto = '".$dd[0]."' and pe_status <> '@' and (pl_ean13 isnull or total > 1) ";
$rlt = db_query($sql);
while ($line = db_read($rlt))
	{
	$sql = "insert into produto_log_".substr($line['pe_data'],0,6);
	$sql .= " (pl_ean13,pl_data,pl_hora,";
	$sql .= "pl_cliente,pl_status,pl_kit,";
	$sql .= "pl_produto,pl_log )";
	$sql .= " values (";
	$sql .= "'".$line['pe_ean13']."','".$line['pe_data']."','".date("H:i")."',";
	$sql .= "'','H','',";
	$sql .= "'".$line['pe_produto']."','AUTO'); ";
	$xrlt = db_query($sql);
	echo '[checkin]';
	$red = 1;
	}
	
require("produtos_estoque_double_auto.php");
}
