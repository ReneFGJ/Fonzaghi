<?
class contabilidade
	{
		var $mes;
		var $ano;
		
		function encerra_ano()
			{
				$mes2 = $this->ano.$this->mes;
				
				$sql = "select count(*) as lanca from pc_".$mes2;
				$sql .= " where (pl_data = ".$mes2.'01)';
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						if ($line['lanca'] > 0)
							{
								echo 'Existe lançamentos, não é possível realizar encerramento';
								return(0);
								exit;
							} 
					}

				$sql = "select * from pc_".$mes2;
				$sql .= " where (pl_data = ".$mes2.'00)';
				$sql .= " and ((pl_conta like '3%') or (pl_conta like '4%'))";
				$rlt = db_query($sql);
				$total = 0;
				while ($line = db_read($rlt))
				{
					$valor = $line['pl_valor'];
					$conta = $line['pl_conta'];
					$sql = "insert into pc_".$mes2." ";
					$sql .= "(pl_data,pl_conta,pl_valor,pl_saldo,pl_historico) ";
					$sql .= " values ";
					$sql .= "(".$mes2."01,'".$conta."','";
					$sql .= (round($valor*100*(-1))/100)."','0','Zeramento das contas de resultado');".chr(13).chr(10);
					$xrlt = db_query($sql);
					$total = $total + $valor;	
				}
					$sql = "insert into pc_".$mes2." ";
					$sql .= "(pl_data,pl_conta,pl_valor,pl_saldo,pl_historico) ";
					$sql .= " values ";
					$sql .= "(".$mes2."01,'2.3.3.1.01','";
					$sql .= (round($total*100)/100)."','0','Zeramento das contas de resultado');".chr(13).chr(10);
					$rlt = db_query($sql);
					
					echo 'Encerramento realizado com sucesso!';
			return(1);
			}
	}
