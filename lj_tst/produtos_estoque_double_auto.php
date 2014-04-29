<?
$tblog = "produto_log_201004";
$sql = "select * from (";
$sql .= "select max(id_pl) as pe, count(*) as total, pl_ean13 from ".$tblog." where pl_status = 'H' group by pl_ean13 ";
$sql .= ") as tabela where total > 1 ";

$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$sql = "delete from  ".$tblog." where id_pl = ".$line['pe'];
	$rrr = db_query($sql);
	echo '[D]';
	$red = 1;
	}

?>