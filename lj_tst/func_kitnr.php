<?
function markup($prc)
	{
	$mark = 4;
	if ($prc > 10) { $mark = 3.8; }
	if ($prc > 20) { $mark = 3.6; }
	if ($prc > 30) { $mark = 3.5; }
	if ($prc > 40) { $mark = 3.4; }
	return($mark);	
	}
function kitnr($nr)
	{
	$nr=sonumero($nr);
	$vl=0;
	$pos = 7;
	for ($r=0;$r < strlen($nr);$r++)
		{
		$vl = $vl + intval(substr($nr,$r,1))*$pos;
		$pos++;
		}
	while ($vl > 23) { $vl = $vl - 23; }
	return(chr($vl+65));
	}
	
function kit_atualiza_quantidate($nrk)
	{
	$asql = "select count(*) as items, sum(p_preco) as valor from kits_pecas ";
	$asql .= "inner join produto on kp_codigo = p_codigo ";
	$asql .= "where kp_status = 'A' and kp_nrkit = '".strzero(intval(substr($nrk,0,4)),4)."' ";
	$arlt = db_query($asql);
	if ($aline = db_read($arlt))
		{
		$asql = "update kits set kits_valor_total = 0".$aline['valor'];
		$asql .= ", kits_pecas = 0".$aline['items'];
		$asql .= " where substr(kits_nr,1,4)='".strzero(intval(substr($nrk,0,4)),4)."' ";
		$arlt = db_query($asql);
		}
	}	
?>