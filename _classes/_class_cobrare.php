<?php
class cobranca
	{
		function envia_nota_cobranca($cliente)
			{
				$ok = 0;
				$sql = "select * from juridico_duplicata 
					where dp_cliente = '".$cliente."' 
					and dp_status = 'A'
					and dp_juridico = 0 ;
					";
				$rlt = db_query($sql);
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$ok = 1;
						$sql = "update juridico_duplicata 
							set dp_cobranca_lote = ".date("Ymd").",
							dp_juridico = 1,
							dp_cobranca_externa = 'S'
							where id_dp = ".round($line['id_dp']);
						$xrlt = db_query($sql);
					}
				echo '<BR><BR><BR><Font class="lt3" color="green">';
				echo 'Nota lançada com sucesso!';
				echo '<BR><BR><BR>';
				return($ok);
			}	
			
		function envia_nota_export($lote)
			{
			global $tab_max;
			$lote = brtos($lote);
			$sql = "select * from juridico_duplicata 
					inner join clientes on dp_cliente = cl_cliente
					where dp_status = 'A'
					and dp_juridico = 1 
					and dp_cobranca_lote = $lote
					";
			$rlt = db_query($sql);
			$tot = 0;
			$vlr = array();
			$wh = '';
			while ($line = db_read($rlt))
				{
					if (strlen($wh) > 0) { $wh .= ' or '; }
					$wh .= " cl_cliente = '".$line['cl_cliente']."' ";
					array_push($vlr,array(
							'CO'.strzero($line['id_dp'],7),
							$line['cl_cliente'],
							$line['cl_nome'],
							'',
							stodbr(sonumero($line['cl_dtnascimento'])),
							stodbr($line['dp_venc']),
							number_format($line['dp_valor'],2,',','.')
							));
					$tot = $tot + $line['dp_valor'];					
				}
			return(array($vlr,$wh,$tot));
			}
		function envia_nota_export_2($vv)
			{
			global $tab_max;
			$wh = $vv[1];
			$tot = $vv[2];
			$lista = $vv[0];
			$sx .= '<table width="'.$tab_max.'" class="lt1">';
			$sx .= '<TR><TH>DOC NR><TH>COD.CLI<TH>NOME<TH>CPF<TH>NASC<TH>VENC<TH>VALOR';
			
			$sql = "select * from cadastro where ".$wh;
			if (strlen($wh) > 0)
				{
					$rlt = db_query($sql);
					while ($line = db_read($rlt))
						{
							$cl = $line['cl_cliente'];
							$cpf = $line['cl_cpf'];
							for ($r=0;$r < count($lista);$r++)
								{
									if ($lista[$r][1] == $cl)
										{ $lista[$r][3] = $cpf; }
								}
						}
				}
			for ($r=0;$r < count($lista);$r++)
				{
					$sx .= '<TR>';
					$sx .= '<TD>\''.$lista[$r][0];
					$sx .= '<TD>\''.$lista[$r][1];
					$sx .= '<TD>\''.$lista[$r][2];
					$sx .= '<TD>\''.$lista[$r][3];
					$sx .= '<TD>\''.$lista[$r][4];
					$sx .= '<TD>\''.$lista[$r][5];
					$sx .= '<TD align="right">'.$lista[$r][6];
				}
			$sx .= '<TR><TD colspan=6>';
			$sx .= '<TD align="right">'.number_format($tot,2,',','.');
			$sx .= '</table>';
			return($sx);
			}
			
	}
?>
