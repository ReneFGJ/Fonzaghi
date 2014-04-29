<?
	$id = array();
	$usql = "";
	$usql .= " (cx_tipo = 'DIN' or cx_tipo = 'CHQ' or cx_tipo = 'ELC' or ";
	$usql .= " cx_tipo = 'RED' or cx_tipo = 'VIS' or cx_tipo = 'CRD' or ";
	$usql .= " cx_tipo = 'MAS' or cx_tipo = 'HIP' or ";
	$usql .= " cx_tipo = 'TRO' or cx_tipo = 'CRE' ) and (";
	
	$sql = "select 'S' as lj, * from caixa_".$y.$m."_01 ";
	$sql .= " left join clientes on cl_cliente = cx_cliente ";
	$sql .= " where ";
	$sql .= " (cx_tipo = 'DIN' or cx_tipo = 'CHQ' or cx_tipo = 'ELC' or ";
	$sql .= " cx_tipo = 'RED' or cx_tipo = 'VIS' or cx_tipo = 'CRD' or ";
	$sql .= " cx_tipo = 'MAS' or cx_tipo = 'HIP' or ";
	$sql .= " cx_tipo = 'TRO' or cx_tipo = 'CRE' ) ";
	$sql .= " and (";
	for ($k = 0;$k < count($indi);$k++)
		{
		if ($k > 0) { $sql .= ' or '; }
		$sql .= "cx_cliente = '".$indi[$k]."' ";
		}
	$sql .= " )";
	$sql .= " and cx_log = 0 ";
	$sql .= " union ";
	$sql .= "select 'J' as lj, * from caixa_".$y.$m."_02 ";
	$sql .= " left join clientes on cl_cliente = cx_cliente ";
	$sql .= " where ";
	$sql .= " (cx_tipo = 'DIN' or cx_tipo = 'CHQ' or cx_tipo = 'ELC' or ";
	$sql .= " cx_tipo = 'RED' or cx_tipo = 'VIS' or cx_tipo = 'CRD' or ";
	$sql .= " cx_tipo = 'MAS' or cx_tipo = 'HIP' or ";
	$sql .= " cx_tipo = 'TRO' or cx_tipo = 'CRE' ) ";
	$sql .= " and (";
	for ($k = 0;$k < count($indi);$k++)
		{
		if ($k > 0) { $sql .= ' or '; }
		$sql .= "cx_cliente = '".$indi[$k]."' ";
		}
	$sql .= " )";
	$sql .= " and cx_log = 0 ";
	$sql .= " union ";
	$sql .= " select 'M' as lj,* from caixa_".$y.$m."_03 ";
	$sql .= " left join clientes on cl_cliente = cx_cliente ";
	$sql .= " where ";
	$sql .= " (cx_tipo = 'DIN' or cx_tipo = 'CHQ' or cx_tipo = 'ELC' or ";
	$sql .= " cx_tipo = 'RED' or cx_tipo = 'VIS' or cx_tipo = 'CRD' or ";
	$sql .= " cx_tipo = 'MAS' or cx_tipo = 'HIP' or ";
	$sql .= " cx_tipo = 'TRO' or cx_tipo = 'CRE' ) ";
	$sql .= " and (";
	for ($k = 0;$k < count($indi);$k++)
		{
		if ($k > 0) { $sql .= ' or '; }
		$sql .= "cx_cliente = '".$indi[$k]."' ";

		if ($k > 0) { $usql .= ' or '; }
		$usql .= "cx_cliente = '".$indi[$k]."' ";
		}
	$sql .= " )";	
	$usql .= ") ";
	$sql .= " and cx_log = 0 ";

	$rlt = db_query($sql);
	$osql = "insert into credito_outros ";
	
	$osql .= "(ccard_tipo,ccard_doc,ccard_auto,";
	$osql .= "ccard_cliente,ccard_status,ccard_data,";
	$osql .= "ccard_hora,ccard_log,ccard_pago,";
	
	$osql .= "ccard_pago_hora,ccard_loja,ccard_pago_log,";
	$osql .= "ccard_valor,ccard_cancelado,ccard_cancelado_hora,";
	$osql .= "ccard_cancelado_log,ccard_parcelas,ccard_documento,";
	$osql .= "ccard_historico )";
	$xsql = "";
	while ($line = db_read($rlt))
		{
		if (strlen($xsql) > 0) { $xsql .= ', '; }
		$vlr = $line['cx_valor'];
		$nome = $line['cl_nome'];
		if ($vlr > 0)
			{ $tp = 'CRE'; } else { $tp = 'CRD'; }
		$mult = 0.05;
		$vlr = round(10*$vlr * $mult)/10;
		$xsql .= "('".$tp."','".$line['cx_doc']."','S',";
		$xsql .= "'".$cliente."','A','".$line['cx_data']."',";
		$xsql .= "'".$libe['cx_hora']."','','0',";
		
		$xsql .= "'".date("H:i")."','".$line['lj']."','',";
		
		$xsql .= "'".$vlr."',0,'',";
		
		$xsql .= "'',0,'0',";
		$msg = "'Pg. ".$line['cx_tipo']." (".trim(number_format($line['cx_valor'],2)).' ';
		$msg .= stodbr($line['cx_data']).") ".trim($line['cl_nome']);
		$xsql .= substr($msg,0,50)."'";
		$xsql .= ") ";
		}
	if (strlen($xsql) > 0)
		{
		$osql =  $osql . ' values '. $xsql;
		$rlt = db_query($osql);
		$vsql = "update caixa_".$y.$m."_02 set cx_log=1 where ".$usql;
		$rlt = db_query($vsql);
		$vsql = "update caixa_".$y.$m."_03 set cx_log=1 where ".$usql;
		$rlt = db_query($vsql);
		$vsql = "update caixa_".$y.$m."_01 set cx_log=1 where ".$usql;
		$rlt = db_query($vsql);		
		$rede = 1;
		}
?>