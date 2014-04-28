<?
class credito
	{
		
		
	function saldo_cliente($cliente)
		{
//			$sql = "select ccard_valor from credito_outros 
//					where ccard_cliente = '$cliente' and ccard_pago=0";
//			$rlt = db_query($sql);
//			$saldo = 0;
//			while ($line = db_read($rlt))
//				{
//					$saldo = $saldo + round($line['ccard_valor'] * 100)/100;
//				}
//			echo '===>'.$saldo;
//			if ($saldo < 0) { $saldo = 0; }

			$sql = "select * from credito_outros where ccard_cliente = '$cliente'  and ccard_pago=0 ";
			$sql .= " order by ccard_data ";	
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
				$vlr = $line['ccard_valor'];
				$sld = $sld + $vlr;
				$ft = '<font color="black">';
				if ($vlr < 0)
					{ $ft = '<font color="red">'; }
				$s .= '<TR '.coluna().'>';
				$s .= '<TD><TT>';
				$s .= $ft;
				$s .= substr(stodbr($line['ccard_data']),0,5);
				$s .= '<TD align="right" width="14%"><TT><noBR>';
				$s .= $ft;
				$s .= number_format($line['ccard_valor'],2);
				$s .= '</TD>';
				$s .= '<TD><TT>';
				$s .= $ft;
				$s .= $line['ccard_historico'];
				$s .= '<TD align="right"><TT>';
				$s .= number_format($sld,2);
				$s .= '<TD align="right"><TT>';
				$s .= $line['ccard_loja'];
				$s .= '</TR>';
				}
			$sld = intval($sld*100)/100;
			if ($sld < 0) { $sld = 0; }
			
			if ($sld == 0)
				{
					$sql = "update credito_outros set ccard_pago_log = ccard_cliente where ccard_cliente = '$cliente' and ccard_pago=0 ";
					$rlt = db_query($sql);
					$sql = "update credito_outros set ccard_cliente = 'XXXXXXX' where ccard_cliente = '$cliente'  and ccard_pago=0 ";
					$rlt = db_query($sql);
				}
			
			return($sld);
		}	
	function lanca_creadito()
		{
						$sql = "insert into credito_outros 
								(
								ccard_tipo, ccard_doc, ccard_auto,
								ccard_cliente, ccard_status, ccard_data,
								ccard_hora, ccard_log, ccard_pago, ccard_pago_hora,
								ccard_loja,ccard_pago_log, ccard_valor,
								ccard_cancelado, ccard_cancelado_hora, ccard_cancelado_log,
								ccard_parcelas, ccard_documento, ccard_historico
								) values (
								'$tipo','$doc_nr','N',
								'$cliente','A',$data,
								'$hora','$user_log',0,'',
								'$loja','',$valor,
								0,'','',
								1,'$doc_nr','Crédido de Cliente'
								)";			
		}
	}
?>
