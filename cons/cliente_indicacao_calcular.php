<?
//function cliente_indicacao($cliente)
	{
	require("../db_fghi_210.php");

	$sql = "select ci_cliente, ci_indicado from 
		clientes_indicacao 
		where ci_cliente = '".$cliente."' and ";
	$sql .= " ci_status = '1' group by ci_indicado, ci_cliente ";
	$rlt = db_query($sql);
	
	$indi = array();
	while ($line = db_read($rlt))
		{ 
		$indicada = $line['ci_indicado'];
		array_push($indi,$indicada); 
		}
		
	if (strlen($indicada) == 0) { return(0); }
	require("../db_ecaixa.php");

//////////////////////// excluir
$sql = "select id_ccard, trim(ccard_historico) as ccard_historico from credito_outros  ";
$sql .= "where ccard_historico like 'Pg.%' ";
$sql .= " and ccard_data > 20100300 ";
$sql .= "and ccard_cliente = '".$cliente."' ";
$sql .= " order by ccard_historico ";
$rlt = db_query($sql);
$sql = "";
$xx = "X";
while ($line = db_read($rlt))
	{
	$xy = trim($line['ccard_historico']);
	if ($xx == $xy)
		{
		if (strlen($sql) > 0) { $sql .= " or "; }
		$sql .= " id_ccard = ".$line['id_ccard']." ";
		}
	$xx = $xy;
	}
	if (strlen($sql) > 0)
		{ $sql = "delete from credito_outros where ".$sql; $rlt = db_query($sql); }
	
	$y = '';
	$m = date("Ym");
	require("cliente_indicacao_calcular_proc.php");

	$m = substr(dateadd("m",-1,date("Ymd")),0,6);
	
	require("cliente_indicacao_calcular_proc.php");

	$m = substr(dateadd("m",-2,date("Ymd")),0,6);
	require("cliente_indicacao_calcular_proc.php");

	$m = substr(dateadd("m",-3,date("Ymd")),0,6);
	require("cliente_indicacao_calcular_proc.php");
		
	if ($rede == 1)
		{
		redirecina("cons.php?dd0=".$cliente);
		}
	}
?>