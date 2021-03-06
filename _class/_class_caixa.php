<?php
class caixa
	{
	var $cx_data;
	var $cx_hora;
	var $cx_tipo;
	var $cx_descricao;
	var $cx_valor;
	var $cx_log;
	var $cx_terminal;
	var $cx_cliente;
	var $cx_nome;
	var $cx_venc;
	var $cx_doc;
	var $cx_parcela;
	var $cx_status;
	var $cx_lote;
	var $cx_chq_banco;
	var $cx_chq_conta;
	var $cx_chq_agencia;
	var $cx_chq_nrchq;
	var $cx_proc;
	var $dinheiro;
	var $saldo1;
	var $saldo2;
	var $soma;
	var $msg;
	var $total_fat;
	
	var $loja = '';
	var $nloja = '';
	var $tabela = '';
	
	function _operaion_not_finish()
		{
			global $ip;
			$sql = "select * from "; 
		}


	function consulta_credito()
		{
			$vlr = 0;
			$cliente = $_SESSION['cliente'];
		
			$sql = "select * from credito_outros where ccard_cliente = '$cliente' and ccard_pago = 0 ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$vlr = $vlr + $line['ccard_valor'];	
				}
			if ($vlr < 0) { $vlr = 0; }
			return($vlr);
		}	
	function caixa_resumo_faturamento($lote = '0010500')
			{
			$datas = substr($data,6,4).substr($data,3,2).substr($data,0,2);
			$sql = '';

			$dp = array('duplicata_joias','duplicata_modas','duplicata_oculos',
						'duplicata_teste','duplicata_usebrilhe','duplicata_sensual',
						'juridico_duplicata'
						);
			$jj = array('Joias','Modas','Oculos',
						'Teste','Catalogo','Sensual',
						'Juridico');
						
			for ($r=0;$r < count($dp);$r++)
			{
			if ($r > 0) { $sql .= " union "; }
			$lojax = $jj[$r];
			$dupli = $dp[$r];
			
			$sql .= "select 'JUR' as cx_tipo,'Juros Calculado ".$lojax."' as ct_descricao,1 as ct_sinal, 
					sum(dp_juros) as valor,  1 
					from $dupli 
					where dp_lote = '$lote' ";
			$sql .= " union ";
			$sql .= "select 'NOT' as cx_tipo,'Notas Recebidas ".$lojax."' as ct_descricao,1 as ct_sinal, 
					sum(dp_valor) as valor,  1 
					from $dupli 
					where dp_boleto <> '' and dp_lote = '$lote' ";
			$sql .= " union ";
			$sql .= "select 'FAT' as cx_tipo,'Faturamento ".$lojax."' as ct_descricao,1 as ct_sinal, 
					sum(dp_valor) as valor,  1 
					from $dupli 
					where dp_boleto = '' and dp_lote = '$lote' "; 					
			}
			$sql .= "order by cx_tipo, valor desc";
			
			$rlt = db_query($sql);
			$stot1 = 0;
			$stot2 = 0;
			
			while ($line = db_read($rlt))
				{
					if ($line['valor'] > 0)
						{
						$stot2 = $stot2 + $line['valor'];
						$se .= '<TR '.coluna().'>';
						$se .= '<TD>';
						$se .= $line['cx_tipo'];
						$se .= ' - ';
						$se .= $line['ct_descricao'];
						$se .= '<TD align="center"> - ';
						$se .= '<TD align="right">';
						$se .= number_format($line['valor'] * $line['ct_sinal'],2);		
						$te = $te + $line['valor'];
						}
				}
				
				$se .= '<TR><TD align="right"><B>Sub total';
						$se .= '<TD align="right" width=120><B>';
						$se .= number_format($stot1,2,',','.');
						$se .= '<TD align="right" width=120><B>';
						$se .= number_format($stot2,2,',','.');				

				$se .= '<TR><TD align="right"><B>Total';
						$se .= '<TD align="right" width=120><B>';
						$se .= number_format($stot1+$this->saldo1,2,',','.');
						$se .= '<TD align="right" width=120><B>';
						$se .= number_format($stot2+$this->saldo2,2,',','.');				
				$this->saldo1 = $stot1+$this->saldo1;
				$this->saldo2 = $stot2+$this->saldo2;
				$this->total_fat = $te;
				return($se);
				
			}
	
		function valor_em_dinheiro()
			{
				global $ip;
				$sql = "select sum(round(cx_valor*100))/100 as cx_valor					
					from caixa_".date("Ym").'_'.$this->nloja."
					where cx_terminal = '$ip' and cx_status = 'A' 
					and cx_lote = '' and (cx_tipo = 'DIN' or cx_tipo = 'TRO')
					group by cx_tipo
					order by cx_tipo
				";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return(number_format(round($line['cx_valor']*100)/100,2));
			}	
	
		function valor_fundo_abertura()
			{
				global $ip;
				$sql = "select *					
					from caixa_".date("Ym").'_'.$this->nloja."
					where cx_terminal = '$ip' and cx_status = 'A' 
					and cx_lote = '' and cx_tipo = 'ABR'
					order by cx_tipo, cx_nome, cx_valor
				";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return(number_format(round($line['cx_valor']*100)/100,2));
			}
	
		function proximo_lote()
			{
				$sql = "select max(id_cl) as lote from caixa_lote ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
					$lote = strzero($line['lote']+1,7);
					}
				return($lote);
			}
	
		function caixa_notas_abertas($lote='')
			{
				global $ip;
				if (strlen($lote) > 0)
				{
				$sql = "select * from caixa_lote ";
				$sql .= "inner join caixa_autorizado on cl_terminal = ca_ip ";
				$sql .= " where cl_codigo = '".$lote."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
					$sql = "select *					
						from caixa_".date("Ym").'_'.$line['ca_caixa']."
						where cx_terminal = '$ip' and cx_status = 'B' 
						and cx_lote = '$lote' 
						order by cx_tipo, cx_nome, cx_valor
					";
					} else {
						echo 'Lote n�o localizado';
					}
				} else {
				$sql = "select *					
					from caixa_".date("Ym").'_'.$this->nloja."
					where cx_terminal = '$ip' and cx_status = 'A' 
					and cx_lote = '' 
					order by cx_tipo, cx_nome, cx_valor
				";
				}
				$rlt = db_query($sql);
				$sx = '<table class="lt1" width="700">';
				$sx .= '<TR><TH>Nome';
				$xtipo = 'X';
				while ($line = db_read($rlt))
				{
					$cab = 0;
					$tipo = trim($line['cx_tipo']);
					if ($tipo != $xtipo) { $cab = 1; $xtipo = $tipo;	}
					if (($tipo=='NOA') or ($tipo =='NOT'))
						{
							if ($cab==1) { $sn .= '<TR><TD colspan=6 class="lt3"><B>Notas Promiss�rias</b></td>'; }
							$sn .= '<TR '.coluna().'>';
							$sn .= '<TD>';
							$sn .= $line['cx_nome'];
							$sn .= '<TD align="center" colspan=2>';
							$sn .= stodbr($line['cx_venc']);
							$sn .= '<TD align="right">';
							$sn .= number_format($line['cx_valor'],2,',','.');
							$sn .= '[ ][ ]';
						}
					if (($tipo=='VIS') or ($tipo =='MAS') or ($tipo =='RED') or ($tipo =='ELC') or ($tipo =='HIP'))
						{
							if ($cab==1) { $sc .= '<TR><TD colspan=6 class="lt3"><B>Cartao cr�dito/d�bito - '.$tipo.'</b></td>'; }
							$sc .= '<TR '.coluna().'>';
							$sc .= '<TD>';
							$sc .= $line['cx_nome'];
							$sc .= '<TD align="center" colspan=1>';
							$sc .= ($line['cx_chq_conta']);
							$sc .= '<TD align="center" colspan=1>';
							$sc .= ($line['cx_chq_agencia']);
							$sc .= '<TD align="right">';
							$sc .= number_format($line['cx_valor'],2,',','.');
							$sc .= '[ ][ ]';
						}
					if ($tipo=='CHQ')
						{
							if ($cab==1) { $sc .= '<TR><TD colspan=6 class="lt3"><B>Cheque de pagamento - '.$tipo.'</b></td>'; }
							$sc .= '<TR '.coluna().'>';
							$sc .= '<TD>';
							$sc .= $line['cx_nome'];
							$sc .= '<TD align="center" colspan=2>';							
							$sc .= trim($line['cx_chq_banco']);
							$sc .= '/'.trim($line['cx_chq_agencia']);
							//$sc .= '/'.trim($line['cx_chq_conta']);							
							$sc .= '/'.($line['cx_chq_nrchq']);
							$sc .= '<TD align="right">';
							$sc .= number_format($line['cx_valor'],2,',','.');
							$sc .= '[ ][ ]';
						}	

																
				}
				$sx .= $sn . $sc;
				$sx .= '</table>';
				return($sx);
			}
		function caixa_liquidacao()
			{
				$sx = '';			
				return($sx);
			}	
		function caixa_faturamento()
			{
				global $ip;
				$datas = substr($data,6,4).substr($data,3,2).substr($data,0,2);
				$sql = '';
	
				$dp = array('duplicata_joias','duplicata_modas','duplicata_oculos',
							'duplicata_teste','duplicata_usebrilhe','duplicata_sensual',
							'juridico_duplicata'
							);
				$jj = array('Joias','Modas','Oculos',
							'Teste','Catalogo','Sensual',
							'Juridico');
							
				for ($r=0;$r < count($dp);$r++)
				{
					if ($r > 0) { $sql .= " union "; }
					$lojax = $jj[$r];
					$dupli = $dp[$r];
					
					$sql .= "select 'JUR' as cx_tipo,'Juros Calculado ".$lojax."' as ct_descricao,1 as ct_sinal, 
							sum(dp_juros) as valor,  1 
							from $dupli 
							where  dp_lote = '' and dp_status = 'B' and dp_terminal = '$ip' ";
					$sql .= " union ";
					$sql .= "select 'NOT' as cx_tipo,'Notas Recebidas ".$lojax."' as ct_descricao,1 as ct_sinal, 
							sum(dp_valor) as valor,  1 
							from $dupli 
							where dp_boleto <> '' and dp_lote = '' and dp_status = 'B' and dp_terminal = '$ip' ";
					$sql .= " union ";
					$sql .= "select 'FAT' as cx_tipo,'Faturamento ".$lojax."' as ct_descricao,1 as ct_sinal, 
							sum(dp_valor) as valor,  1 
							from $dupli 
							where dp_boleto = '' and dp_lote = '' and dp_status = 'B' and dp_terminal = '$ip' "; 					
					}
					
				$sql .= "order by cx_tipo, valor desc";
				$rlt = db_query($sql);				
				
				$sx = '';
				$fat = 0;
				$jur = 0;
				while ($line = db_read($rlt))
				{
					$vlr = $line['valor'];
					if ($vlr > 0)
					{
					$fat = $fat + $vlr;
					$dec = trim($line['ct_descricao']);
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>';		
					$sx .= $dec;			
					$sx .= '<TD align="center">-';
					$sx .= '<TD align="right">';
					$sx .= number_format($vlr,2,',','.');
					}				
				}
				$sx .= '<TR><TD colspan=1 align="right"><B>Faturamento';
				$sx .= '<TD align="center">-';
				$sx .= '<TD align="right"><B>';
					$sx .= number_format($fat,2,',','.');
				//$this->saldo1 = $stot1+$this->saldo1;
				$this->saldo2 = $fat+$this->saldo2;								
				return($sx);
			}
		function caixa_resumo($lote='')
			{
				global $ip;
				if (strlen($lote) > 0)
				{
				$sql = "select * from caixa_lote ";
				$sql .= "inner join caixa_autorizado on cl_terminal = ca_ip ";
				$sql .= " where cl_codigo = '".$lote."' ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
					$datacx = substr($line['cl_data'],0,6);
					$sql = "select cx_tipo, sum(cx_valor) as cx_valor					
						from caixa_".$datacx.'_'.$line['ca_caixa']."
						where cx_terminal = '$ip' and cx_status = 'B' 
						and cx_lote = '$lote' 
						group by cx_tipo
						order by cx_tipo
					";
					} else {
						echo 'Lote n�o localizado';
					}
				} else {				
					$sql = "select cx_tipo, sum(cx_valor) as cx_valor					
						from caixa_".date("Ym").'_'.$this->nloja."
						where cx_terminal = '$ip' and cx_status = 'A' 
						and cx_lote = ''
						group by cx_tipo order by cx_tipo
					";
				}
				
				$sx .= '<TR><TH>Descri��o<TH width="120">Cr�ditos<TH width="120">D�bitos';
				$rlt = db_query($sql);	
				$sld1 = 0;
				$sld2 = 0;
				$din = 0;
				$fat = 0;
				$stot1 = 0;
				$stot2 = 0;
				while ($line = db_read($rlt))
					{
						if ($line['cx_tipo'] == 'MAN')
							{
								$this->cx_valor = $line['cx_valor'];
							}
						if ($line['cx_tipo'] <> 'MAN')
							{						
							$vlr = $line['cx_valor'];
							$sx .= '<TR '.coluna().'>';
							$sx .= '<TD>';
							$vlr1 = 0;
							$vlr2 = 0;
							$tipo = $line['cx_tipo'];
							if ($tipo == 'ABO') { $tipo = 'Anomalia de Caixa'; $col=0;  }
							if ($tipo == 'ABR') { $tipo = 'Abertura de caixa'; $col=0;  }
							if ($tipo == 'DES') { $tipo = 'Desconto no pagamento'; $col=0; $fat = $fat - $vlr; }
							if ($tipo == 'NOA') { $tipo = 'Nota Promiss�ria emitidas'; $col=0; $fat = $fat + $vlr; }
							if ($tipo == 'NOT') { $tipo = 'Abertura de Nota Promiss�ria (old)'; $col=0;  $fat = $fat + $vlr;}
							if ($tipo == 'DIN') { $tipo = 'Dinheiro recebido'; $col=0; $din = $din + $vlr;  $fat = $fat + $vlr;}
							
							if ($tipo == 'MAS') { $tipo = 'MasterCard'; $col=0;  $fat = $fat + $vlr;}
							if ($tipo == 'VIS') { $tipo = 'Visa'; $col=0;  $fat = $fat + $vlr;}
							if ($tipo == 'RED') { $tipo = 'RedeCard'; $col=0;  $fat = $fat + $vlr;}
							if ($tipo == 'ELC') { $tipo = 'Visa Electron'; $col=0;  $fat = $fat + $vlr;}
							
							if ($tipo == 'CHQ') { $tipo = 'Cheque'; $col=0;  $fat = $fat + $vlr;}
							
							if ($tipo == 'CRE') { $tipo = 'Cr�dito de cliente'; $col=1; $fat = $fat + $vlr;}
							if ($tipo == 'CRD') { $tipo = 'Cr�dito para cliente'; $col=0;  $fat = $fat + $vlr; }
							if ($tipo == 'TRO') { $tipo = 'Devolu��o de troco em dinheiro'; $col=1; $din = $din + $vlr;  $fat = $fat + $vlr; }
							if ($tipo == 'SLD') { $tipo = 'Fechamento de Caixa'; $col=1;   }
							if ($tipo == 'DEP') { $tipo = 'Dep�sito no Banco'; $col=0;   }
							
							if ($tipo == 'FES') { $tipo = 'Sobra/falta de caixa'; $col=0; }
							if ($tipo == 'FEC') { $tipo = 'Falta de caixa'; $col=0; }
							if ($tipo == 'FUN') { $tipo = 'Venda Funcionario'; $col=0; }
							
							if ($tipo == 'AJU') { $tipo = 'Ajustes de caixa'; $col=0; $din = $din + $vlr; }
											
							if ($vlr < 0) 
								{
									if ($col==1) { $vlr = $vlr * (-1); }
								}
	
							if ($col==0)
								{
									$vlr1 = $vlr;
									$stot1 = $stot1 + $vlr;
								} else {
									$vlr2 = $vlr;
									$stot2 = $stot2 + $vlr;
								}
	
								$sx .= $tipo;
								$sx .= '<TD align="right" width=120>';
								$sx .= number_format($vlr1,2,',','.');
								$sx .= '<TD align="right" width=120>';
								$sx .= number_format($vlr2,2,',','.');
						}
					}
				$sx .= '<TR><TD align="right"><B>Sub total';
						$sx .= '<TD align="right" width=120><B>';
						$sx .= number_format($stot1,2,',','.');
						$sx .= '<TD align="right" width=120><B>';
						$sx .= number_format($stot2,2,',','.');
			
				$this->saldo1 = $stot1;
				$this->saldo2 = $stot2;
				$this->dinheiro = $din;
				return($sx);
			}
		function valida_usuario_caixa($login,$pass)
			{
			$sql = "select * from usuario where us_login = '".UpperCase($login)."' ";
			$rlt = db_query($sql);
			$ok = 0;
			if ($line = db_read($rlt))
				{	
					if (trim($line['us_status'])=='A')
						{
							if (UpperCaseSql(trim($pass)) == UpperCaseSql(trim($line['us_senha'])))
							{ $ok = 1; $id_user = $line['us_cracha']; $_SESSION['id_user'] = $line['us_cracha']; } else { $msg = 'err:Senha incorreta'; $_SESSION['id_user'] = '';}
						} else {
							$msg = 'err:Usu�rio Inativo'; 
						}
				} else {
					$msg = 'err:Usu�rio Inativo';
				}
			$this->msg = $msg;
			return($ok);				
			}
		function cx_open_cx($valor)
			{	global $dd,$ip,$id_user;
				$id_user = round($_SESSION['id_user']);
				$tabela = "caixa_".date("Ym").'_'.$this->nloja;
					$sql = "insert into ".$tabela." ";
					$sql .= "(cx_data,cx_hora,cx_tipo,";
					$sql .= "cx_descricao,cx_valor,cx_log,";
					$sql .= "cx_terminal,cx_cliente,cx_nome,";
					$sql .= "cx_venc,cx_doc,cx_parcela,";
					$sql .= "cx_status,cx_lote,cx_chq_banco,";
					$sql .= "cx_chq_conta,cx_chq_agencia,cx_chq_nrchq";
					$sql .= ") values (";
					$sql .= "'".date("Ymd")."','".date("H:i")."','ABR',";
					$sql .= "'Abertura de caixa: ".UpperCase($dd[7])."','".$valor."','".$id_user."',";
					$sql .= "'".$ip."','','',";
					$sql .= "'".date("Ymd")."','ABRE','nt',";
					$sql .= "'A','','',";
					$sql .= "'','',''";
					$sql .= ")";
					$rlt = db_query($sql);
					return(1);			
			}
		function cx_open_cp()
			{
				$cp = array();
				array_push($cp,array('$H4','','id_ac',False,True,''));
				array_push($cp,array('$H8','','',False,True,''));
				array_push($cp,array('$H8','','',False,True,''));
				array_push($cp,array('$H8','','',False,True,''));
				array_push($cp,array('$A8','',$ip,False,True,''));
				array_push($cp,array('$HV','',$ip,True,True,''));
				//array_push($cp,array('$Q ac_descricao:ac_codigo:select * from area_conhecimento where ac_area_root = 1 ','ac_codigo_mae','�rea do conhecimento',False,True,''));
				array_push($cp,array('$N8','','Dinheiro',True,True,''));
				array_push($cp,array('$S20','','Login:',True,True,''));
				array_push($cp,array('$P20','','Senha:',True,True,''));
				array_push($cp,array('$H8','',':',True,True,''));
				return($cp);				
			}
		function open_cx()
			{
				
			}
		function nota_promissoria_abrir($tabela,$nome,$cliente,$valor,$vencimento,$descricao,$doc)
			{
				global $ips,$ip;
				$data = date("Ymd");
				$docx = 'NOT'.$doc;
				$log = $_SESSION['id_user'];
				$sql = "
				insert into $tabela 
				(  
				dp_pedido, dp_doc, dp_sync,
  				dp_historico, dp_cliente, dp_valor,
  				dp_logemite, dp_logpaga, dp_status,
  				
  				dp_horapaga, dp_comissao, dp_boleto,
  				dp_venc, dp_data, dp_datapaga,
  				dp_chq, dp_tipo, dp_lote,
  				
  				dp_terminal, dp_juros, dp_juridico,
  				dp_content, dp_carencia, dp_local,
  				dp_nr, dp_doc_mae, dp_cfiscal,
  				
  				dp_cobranca_externa, dp_cobranca_lote, dp_nrop 
  				) values (
  				'$docx','$doc','$docx',
  				'$nome','$cliente',$valor,
  				'$log','','A',
  				
  				'',0,'EMISSAO',
  				$vencimento,$data,19000101,
  				'','','',
  				
  				'$ip',0,0,
  				'$descricao',0,0,
  				0,'$ips','',
  				  		
  				 '',0,''		
  				 ); 				
				";
				$rlt = db_query($sql);
				return(0);
			}
			
		function abre_notas_promissorias($notas)
			{
				$loja = $this->loja;
				
				for ($r=0;$r < count($notas);$r++)
					{
						$line = $notas[$r];
						//print_r($line);
						//echo '<HR>';
						$nome = $line['cx_nome'];
						$cliente = $line['cx_cliente'];
						$valor = $line['cx_valor'];
						$vencimento = $line['cx_venc'];
						$descricao = $line['cx_descricao'];
						$doc = $line['cx_nrop'];
						
						$tabela = $this->loja_dp($loja);
						$this->nota_promissoria_abrir($tabela,$nome,$cliente,$valor,$vencimento,$descricao,$doc);
					}
			}
			
		function status_transacao($drp)
			{
				global $ip;	
				$data = date("Ymd");	
				$sql = "select * from duplicata_cfop 
						where cfop_docnr = '".$drp."' 
						 ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return($line['cfop_status']);	
			}
		/* Cancelamentos */
		function cancelar_transacao_01($drp)
			{
				$notas = array();
				$sql = "delete from caixa_".date("Ym").'_'.$this->nloja."
					where cx_nrop = '$drp'
				";
				$rlt = db_query($sql);	
				return(1);
			}
		function cancelar_transacao_02($drp)
			{
				$id_user = $_SESSION['id_user'];
				$lj = array('J','M','O','S','T','U','D');
				for ($r=0;$r < count($lj);$r++)
					{
					$sql = "update ".$this->loja_dp($lj[$r])." 
							set dp_status = 'A', 
							dp_datapaga = ".date("Ymd").", 
							dp_logpaga = '', 
							dp_local='' 
							where 
							(dp_status = '@' or dp_status = 'A'  or dp_status = 'B')
							and dp_nrop= '$drp' ";
					$rlt = db_query($sql);
					//echo '<BR>'.$sql;	
					}
			}		
		function cancelar_transacao_03($drp)
			{
				global $ip;	
				$data = date("Ymd");	
				$sql = "update duplicata_cfop set 
						cfop_hora = '".date("H:i:s")."',
						cfop_log = '$id_user',
						cfop_data = '$data',
						cfop_status = 'X',
						cfop_lote = 'XXXXXXX'
						where cfop_docnr = '".$drp."' 
						and cfop_ip = '$ip' 
						 ";
				//echo '<BR>'.$sql;
				$rlt = db_query($sql);
			}
						
		function finaliza_transacao_01($drp)
			{
				$notas = array();
				$sql = "select * from caixa_".date("Ym").'_'.$this->nloja."
					where cx_tipo = 'NOA' and cx_nrop = '$drp'
				";
				$rlt = db_query($sql);	
				/* Carrega notas para abrir no caixa */
				while ($line = db_read($rlt))
					{
						array_push($notas,$line);
					}
				return($notas);
			}
		function finaliza_transacao_02($drp)
			{
				$cliente = $_SESSION['cliente'];
				$notas = array();
				$sql = "update caixa_".date("Ym").'_'.$this->nloja."
					set cx_status = 'A'
					where cx_status = '@' and cx_nrop = '$drp'
					and cx_cliente = '$cliente'
				";
				$rlt = db_query($sql);
				return(1);	
			}			
		function finaliza_transacao_90($drp)
			{
				global $ip;
				$id_user = $_SESSION['id_user'];
				$data = date("Ymd");
				
				$sql = "update duplicata_cfop set 
						cfop_hora = '".date("H:i:s")."',
						cfop_log = '$id_user',
						cfop_data = '$data',
						cfop_status = 'A'
						where cfop_docnr = '".$drp."' 
						and cfop_ip = '$ip' 
						 ";
				//echo '<BR>'.$sql;
				$rlt = db_query($sql);
			}
		function finaliza_transacao_99($drp)
			{
				$id_user = $_SESSION['id_user'];
				$lj = array('J','M','O','S','T','U','D');
				for ($r=0;$r < count($lj);$r++)
					{
					$sql = "update ".$this->loja_dp($lj[$r])." 
						set dp_status = 'B', 
							dp_datapaga = ".date("Ymd").", 
							dp_logpaga = '".$id_user."', 
							dp_logemite = 'CX2',
							dp_local='".$this->loja."' 
							where 
							(dp_status = '@' or dp_status = 'A')
							and (dp_nrop= '$drp' and dp_nrop <> '')
							and (dp_cliente = '".$this->cx_cliente."')";
					$rlt = db_query($sql);
					//echo '<BR>'.$sql;	
					}
			}

		function atualiza_juros($l,$j)
			{
				$dp = '';
				$lj = $l['dp_loja'];
				$id = $l['id_dp'];
				$dp = $this->loja_dp($lj);

				$sql = "update ".$dp." set dp_juros = ".$j." where id_dp = ".round($id);
				$rrr = db_query($sql);
			}
		function rever_transacoes_detalhe_notas($ss)
			{
				global $ip;
				$data = date("Ymd");
				$sql = "select * from duplicata_cfop 
							where cfop_docnr = '$ss'
							order by cfop_docnr desc ";
				$rltx = db_query($sql);
				if ($line = db_read($rltx))
					{
					$cliente = $line['cfop_cliente'];
					$this->cx_cliente = $cliente;
					$cp = 'dp_content, dp_valor, dp_juros, dp_venc, id_dp, dp_terminal ';
					$sql = "select        'J' as loja, $cp from duplicata_joias where (dp_nrop = '$ss') ";
					$sql .= "union select 'T' as loja, $cp from duplicata_teste where (dp_nrop = '$ss')  ";
					$sql .= "union select 'M' as loja, $cp from duplicata_modas where (dp_nrop = '$ss')  ";
					$sql .= "union select 'O' as loja, $cp from duplicata_oculos where (dp_nrop = '$ss') ";
					$sql .= "union select 'S' as loja, $cp from duplicata_sensual where (dp_nrop = '$ss')  ";
					$sql .= "union select 'U' as loja, $cp from duplicata_usebrilhe where (dp_nrop = '$ss')  ";
					$sql .= "union select 'D' as loja, $cp from juridico_duplicata where (dp_nrop = '$ss')  ";
					$rlt = db_query($sql);	
					$this->soma= 0;
					$soma = 0;
					while ($line = db_read($rlt))
						{
							$juros = $line['dp_juros'];
							$soma = $soma + $line['dp_valor'] + $line['dp_juros'];
							$sx .= '<TR '.coluna().'>';
							$sx .= '<TD colspan=2>'.$line['dp_content'].'('.$line['loja'].')';
							$sx .= '<TD colspan=1>'.$line['dp_terminal'];
							$sx .= '<TD align="right" width="100">'.number_format($line['dp_valor'],2,',','.');
							$sx .= '<TD align="right" width="100">'.number_format($line['dp_juros'],2,',','.');
							$sx .= '<TD align="center" width="100">-';
							$sx .= '<TD>'.$soma;
						}
					$this->soma = $soma;
					return($sx);						
					}
							
			}
		function rever_transacoes_detalhe_pagamentos($drp)
			{
				for ($r=1;$r <= 9;$r++)
					{
					if ($r > 1) { $sql .= " union "; }
					$sql .= "select * from caixa_".date("Ym").'_'.strzero($r,2)." 
						where cx_nrop = '$drp'
					";
					}
				$rlt = db_query($sql);
				$soma = 0;				
				while ($line = db_read($rlt))
					{
						$soma = $soma + $line['cx_valor'];
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= $line['cx_descricao'];
						$sx .= '<TD>';
						$sx .= $line['cx_terminal'];
						$sx .= '<TD>';
						$sx .= $line['cx_tipo'];
						$sx .= '<TD align="center">';						
						$sx .= '-';
						$sx .= '<TD align="right">';						
						$sx .= number_format(round($line['cx_valor']*100)/100,2,',','.');				
					}
				$sx .= '<TR><TD align="right" colspan=3><B><I>Sub-Total</B></I>';
				$sx .= '<TD align="right"><B>'.number_format($this->soma,2,',','.');
				$sx .= '<TD align="right"><B>'.number_format($soma,2,',','.');

				$sx .= '<TR><TD align="right" colspan=3><B><I>Saldo</B></I>';
				$sx .= '<TD align="right" colspan=2><B>'.number_format($this->soma - $soma,2,',','.');
				$tot = 0;
				$it = 0;
				return($sx);
			}
		function rever_transacoes()
			{
				global $ip;
				$data = date("Ymd");
				$sql = "select * from duplicata_cfop
					where cfop_ip = '$ip' and (cfop_status = '@' or cfop_status = 'A')
					and cfop_data = $data
					order by cfop_data, cfop_docnr desc
					";
				$sx = '<div id="dados_mini3">';
				$sx .= '<h2>Transa��es abertas (sem fechamento de lote)</h2>';
				$sx .= '<table width="100%" border=0 class="tabela01">';
				$sx .= utf8_encode('<TR><TH>Trasacao<TH>Data<TH>Cliente<TH>Status<TH width="10%">Dinheiro<TH width="10%">Cartao/Outros');
				$sta = array('@'=>'Em transa��o','A'=>'Finalizada');
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$link = '';	
						if (strlen(trim($line['cfop_cliente'])) > 0)
							{ $link = '<A HREF="'.page().'?dd0='.$line['cfop_docnr'].'&dd1='.$line['cfop_cliente'].'">'; }
						$sx .= '<TR>';
						$sx .= '<TD align="center">';
						$sx .= $link.$line['cfop_docnr'].'</A>';
						$sx .= '<TD align="center">';
						$sx .= $link.stodbr($line['cfop_data']).'</A>';
						$sx .= '<TD align="center">';
						$sx .= $link.$line['cfop_cliente'].'</A>';
						$sx .= '<TD align="center">';
						$sx .= $link.$sta[$line['cfop_status']].'</A>';
						$sx .= '<TD align="right">';
						$sx .= number_format($line['cfop_din'],2,',','.');
						$sx .= '<TD align="right">';
						$sx .= number_format($line['cfop_cartao'],2,',','.');
							
					}
				$sx .= '</table>';
				return($sx);
			}
		function cancelar_lancamento($id,$drp)
			{
				$sql = "select * from caixa_".date("Ym").'_'.$this->nloja."
					where id_cx = ".$id;
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$tipo = $line['cx_tipo'];
						$aut = 0;
						if ($tipo == 'DIN') { $aut = 1; }
						if ($tipo == 'DEP') { $aut = 1; }
						if ($tipo == 'MAS') { $aut = 1; }
						if ($tipo == 'VIS') { $aut = 1; }
						if ($tipo == 'ELC') { $aut = 1; }
						if ($tipo == 'RED') { $aut = 1; }
						if ($tipo == 'HIP') { $aut = 1; }
						if ($tipo == 'DEP') { $aut = 1; }
						if ($tipo == 'DES') { $aut = 1; }
						if ($tipo == 'TRO') { $aut = 1; }
						if ($tipo == 'NOA') { $aut = 1; }
						/* Autorizado */
						
						if ($aut == 1)
						{
						if ((trim($line['cx_status'])=='@') and (trim($line['cx_nrop'])==$drp))
							{
								$sql = "delete from caixa_".date("Ym").'_'.$this->nloja."
								where id_cx = ".$id;
								$rlt2 = db_query($sql);						
							}
						} else {
							$erro = 'Op��o n�o disponivel';
						}
					}
				redirecina('nada.php');
				return(1);
			}
		function caixa_pagamentos($drp)
			{
				global $ip;
				$sql = "select * from caixa_".date("Ym").'_'.$this->nloja."
					where cx_nrop = '$drp' and cx_nrop <> '' and cx_terminal = '$ip'
					and not (cx_tipo = 'FEC' or cx_tipo = 'FES' or cx_tipo = 'SLD' or cx_tipo = 'MAN')					
				";
				
				
				$rlt = db_query($sql);
				$sld = 0;
				$tra = 0;
				while ($line = db_read($rlt))
					{
						$sld = $sld + $line['cx_valor'];
						$tra++;
					}
				if ($tra == 0) { $sld = -1; }
				return($sld);
			}
		function mostra_lancamentos($drp)
			{
				global $ip;
				$sx = '<div id="dados_mini3">';
				$sx .= '<table width="100%" class="tabela01">';
				$sx .= utf8_encode('<TR><TH>Descri��o<TH>Cliente<TH>Tipo<TH>D�bito<TH>Cr�dito');
				
				$sql = "select * from caixa_".date("Ym").'_'.$this->nloja."
					where (cx_nrop = '$drp' and cx_nrop <> '') 
						and cx_terminal = '$ip'
						and not (cx_tipo = 'FEC' or cx_tipo = 'FES' or cx_tipo = 'SLD' or cx_tipo = 'MAN')
				";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$link = '<A HREF="#" onclick="newxy2(\'cx_nota_promissoria.php?dd10=N&dd0='.$line['id_cx'].'&dd1='.$line['cx_cliente'].'&dd2='.$this->nloja.'\',900,450);">';
							if ($line['cx_tipo'] != 'NOA') 	{ $link = ''; }
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $link;
						$sx .= $line['cx_descricao'];
						$sx .= '<TD>';
						$sx .= $line['cx_nome'];
						$sx .= '<TD>';
						$sx .= $line['cx_tipo'];
						$sx .= '<TD align="center">';						
						$sx .= '-';
						$sx .= '<TD align="right">';						
						$sx .= number_format(round($line['cx_valor']*100)/100,2,',','.');
						
						/* */
						$sx .= '<TD>';
						$sx .= '<span onclick="cancelar('.$line['id_cx'].');">';
						$sx .= '<font color="red"><B>X</B></font>';
						$sx .= '</span>';
						
					}
				
				$tot = 0;
				$it = 0;
				$sx .= '</table>';
				$sx .= '</div>'.chr(13);				
				$sx .= '<script>'.chr(13);
				$sx .= 'function cancelar(id)'.chr(13);
				$sx .= '{'.chr(13);
				//$sx .= 'alert(id);'.chr(13);
				$sx .= "
						$.ajax({
  						url: 'cx_ajax.php?dd10='+id+'&dd50=cancelar',
  							success: function(data) {
    						$('#pagos').html(data);
  							}
						});
						";
				$sx .= '}'.chr(13);
				$sx .= '</script>'.chr(13);
				return($sx);
			}
	
		function desfaz_lancamento_aberto()
			{
				global $ip;	
				$tb = array('duplicata_joias','duplicata_modas','duplicata_oculos','duplicata_sensual','duplicata_teste','duplicata_usebrilhe','juridico_duplicata');
				for ($r=0;$r < count($tb);$r++)
					{
					if (strlen($tb[$r]) > 0)
						{
						$sql = "update ".$tb[$r]." set dp_nrop = '' 
							where ((dp_status = 'A' or dp_status = '@') 
								and dp_lote = '' and dp_nrop <> '') 
								and dp_terminal = '$ip' ";
						echo '<BR>'.$sql;
						$rlt = db_query($sql);
						}
					}
				$_SESSION['ids'] = '';
				$_SESSION['cliente'] = '';
			}
		function valida_session($cliente,$ips)
			{
				global $dd,$ip;
				$sql = "select * from duplicata_cfop 
						where cfop_ip = '$ip' and cfop_docnr = '$ips'
						and cfop_cliente = '$cliente'
						 ";

				$rlt = db_query($sql);	
				if ($line = db_read($rlt))
					{
						$sta = $line['cfop_status'];
						if ($sta != '@')
							{
								$_SESSION['ids'] = '';
								$_SESSION['cliente'] = '';
								redirecina(page().'?dd0='.$dd[0]);
							}
					}
				return(1);
			}
		function session_cfop($cliente)
			{
				global $ip;
				if ((strlen(trim($cliente)) < 7) and (substr($cliente,0,1)!='F'))
					{
						echo '<font class="lt1"><font color="black">ERRO: cliente nao definido</font>';
						exit;
					}
				
				
				$sql = "select * from duplicata_cfop 
						where cfop_ip = '$ip' and cfop_status = '@'
						and cfop_cliente = '$cliente'
						 ";
				$rlt = db_query($sql);
				
				if (!($line = db_read($rlt)))
					{
						$docid = '';
						$sql = "select max(id_cfop) as id from duplicata_cfop ";
						$rlt = db_query($sql);
						$line = db_read($rlt);
						$id = $line['id'];
						$docid = strzero($id+1,7);
						$data = date("Ymd");
						$hota = date("H:i:s");
						$sql = "insert into duplicata_cfop 
							(
								cfop_ip, cfop_data, cfop_hora, 
								cfop_log, cfop_docnr, cfop_status,
								cfop_cliente, cfop_lote
							) values (
								'$ip',$data,'$hora',
								'$log','$docid','@',
								'$cliente',''
							)";
							$rlt = db_query($sql);
							return($docid);
					} else {
						return($line['cfop_docnr']);
					}
			}
	
		function caixa_saldo_notas($lj,$id,$check,$cliente)
			{
				global $ip;
				$ss = $_SESSION['ids'];
				$ssc = $_SESSION['cliente'];
				if (strlen($cliente)==0)
					{ return(array(0,0)); }

				if ((strlen($ss) == 0) or ($ssc != $cliente))
					{ $ss = $this->session_cfop($cliente); }
				$_SESSION['ids'] = $ss;
								
				if (strlen($lj) > 0)
				{
				$dp = $this->loja_dp($lj);				
				if (strlen($dp)==0)
					{
						echo 'Nome da Loja nao est� definido';
						exit;
					}
				if (strlen($id) > 0)
					{
					if ($check == 1)
						{
							$sqlq = "update ".$dp." set dp_nrop = '".$ss."', dp_terminal = '".$ip."' where id_dp = ".$id;
						} else {
							$sqlq = "update ".$dp." set dp_nrop = '' where id_dp = ".$id;
						}
					}
				if (strlen($sqlq) > 0) { $rltq = db_query($sqlq); }
				}
				$wh = "(dp_cliente = '".$cliente."' ) and (dp_status = '@' or dp_status='A') and (dp_terminal = '$ip')";
				$cp = 'dp_nrop, dp_valor, dp_juros, dp_venc, id_dp ';
				$sql = "select        'J' as loja, $cp from duplicata_joias where      $wh ";
				$sql .= "union select 'T' as loja, $cp from duplicata_teste where      $wh ";
				$sql .= "union select 'M' as loja, $cp from duplicata_modas where      $wh ";
				$sql .= "union select 'O' as loja, $cp from duplicata_oculos where     $wh ";
				$sql .= "union select 'S' as loja, $cp from duplicata_sensual where    $wh ";
				$sql .= "union select 'U' as loja, $cp from duplicata_usebrilhe where  $wh ";
				$sql .= "union select 'D' as loja, $cp from juridico_duplicata where   $wh ";
				$rlt = db_query($sql);
				
				$total = 0;
				$juros = 0;
				$nts = 0;
				while ($line = db_read($rlt))
					{				
						$sdp = trim($line['dp_nrop']);
						if (strlen($sdp) > 0)
							{
							$nts++;
							$valor = $line['dp_valor'];
							$data = $line['dp_venc'];
							$jur = $this->calcula_juros($data,$valor);							
						
							$total = $total + $valor;
							$juros = $juros + $jur;
							}
					}
				$juros = round($juros*10)/10;
				$pago = 0;
				$saldo = $total + $juros - $pago;
				$erro = 0;
				if (($saldo == 0) and ($nts==0)) { $erro = 1; }
				/* Calcula Pagamentos */
				
				return(array($saldo,$juros,$erro));

			}
	
		function caixa_recibos()
			{
				global $ip;			
				$cp = ' id_dp, dp_valor, dp_juros, dp_cliente, dp_historico, dp_nrop ';
				$cp = '*';
				$sql = "select        'J' as loja, $cp from duplicata_joias where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip' ";
				$sql .= "union select 'T' as loja, $cp from duplicata_teste where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select 'M' as loja, $cp from duplicata_modas where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select 'O' as loja, $cp from duplicata_oculos where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select 'S' as loja, $cp from duplicata_sensual where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select 'U' as loja, $cp from duplicata_usebrilhe where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select 'D' as loja, $cp from juridico_duplicata where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip' ";
				$rlt = db_query($sql);
				$sx = '<center><table width="94%" align="center" class="lt2">';
				$sx .= '<TR><TH>Nome<TH>Hist�rico<TH>Valor<TH>Juros<TH>Dt.Liq.';
				while ($line = db_read($rlt))
					{
						$link = '<A HREF="javascript:newxy2(\'recibo_cliente.php?dd0='.$line['dp_cliente'].'&dd1='.$line['dp_data'].'\',700,500);" class="lt2">';						
						if (substr($line['dp_cliente'],0,1)=='F')
							{ $link = '<A HREF="javascript:newxy2(\'recibo_funcionario.php?dd0='.$line['dp_cliente'].'&dd1='.$line['dp_data'].'\',700,500);" class="lt2">'; }
						
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $link;
						$sx .= $line['dp_historico'];
						$sx .= '<TD>';
						$sx .= $line['dp_content'];						
						$sx .= '<TD align="right">';
						$sx .= number_format($line['dp_valor'],2,',','.');
						$sx .= '<TD align="right">';
						$sx .= number_format($line['dp_juros'],2,',','.');
						$sx .= '<TD align="center">';
						$sx .= stodbr($line['dp_datapaga']);						
						$sx .= '<TD align="center">';
						$sx .= trim($line['loja']);						
					}
				$sx .= '</table>';
				echo $sx;
				return(0);
			}

		function mostra_notas_do_cliente_para_quitar($rlt)
			{
				global $ip;
				$sx = '<div id="dados_mini3">';
				$sx .= '<table width="100%" class="tabela01">';
				$sx .= '<TR><TH>c<TH>L<TH>DOC<TH>Nome<TH>Descricacao<TH>Emissao<TH>Vencimento<TH>Vlr.Ori.<TH>Juros<TH>Sta';
				$tot = 0;
				$it = 0;
		
				for ($r=0;$r < count($rlt);$r++)
				{
					$cor = '';
					$line = $rlt[$r];
					$cliente = $line['dp_cliente'];
					$nlj = trim($line['dp_loja']);
					$nid = trim($line['id_dp']);
					$chk2 = trim($line['dp_nrop']);
					$term = trim($line['dp_terminal']);
					
					$chk = '';
	
					if (strlen($chk2) > 0) 
						{
							
							if ($term == $ip)
								{ $chk = 'checked'; } 
						}
					
					$form = '<input type="checkbox" value="1" name="ddc'.$it;
					$form .= '" id="cj'.$it.'" onclick="recarregar(\'cj'.$it.'\',this,\''.$nid.'\',\''.$nlj.'\',\''.$line['dp_cliente'].'\');" '.$chk.'>';
					$status = $line['dp_status'];
					$it++;
					$tot = $tot + $line['dp_valor'];
					$valor = $line['dp_valor'];
					$data = $line['dp_venc'];
					if ($data < date("Ymd")) { $cor = '<font color="red">'; }
					$jur = $this->calcula_juros($data,$valor);
					if ($jur > 0)
						{ $this->atualiza_juros($line,$jur); }
					$sx .= '<TR
					>';
					$sx .= '<TD width="1%">';
					$sx .= $form;				
					$sx .= '<TD>';
					$sx .= $line['dp_loja'];
					$sx .= '<TD>'.$cor;
					$sx .= $line['dp_doc'];
					$sx .= '<TD>'.$cor;
					$sx .= $line['dp_historico'];
					$sx .= '<TD>'.$cor;
					$sx .= $line['dp_content'];
					$sx .= '<TD align="center">'.$cor;
					$sx .= stodbr($line['dp_data']);
					$sx .= '<TD align="center">'.$cor;
					$sx .= stodbr($line['dp_venc']);
					$sx .= '<TD align="right">'.$cor;
					$sx .= number_format($line['dp_valor'],2,',','.');
					$sx .= '<TD align="right">'.$cor;
					$sx .= number_format($jur,2,',','.');
					$sx .= '<TD>';
					$sx .= $status;
					
					
				}
				$sx .= '</table>';
				$sx .= '</div>';
				
				$sx .= '
				<script>
				function recarregar(th,ta,nlj,nid,cliente)
					{
						var ok = ta.checked;	
						if (ok==1)
							{ var check = 1; } else { var check = 0; }
						var urr = \'cx_ajax.php?dd0=\'+nid+\'&dd10=\'+cliente+\'&dd1=\'+nlj+\'&dd2=\'+check;
						$.ajax({
  							url: urr, 
  							success: function(data) {
	    						$(\'#saldo\').html(data);
  							}
							});	
						$.ajax({
  							url: \'cx_ajax_pagar.php?dd51=\'+cliente+\'		&ddx='.date("Ymdhis").'\',
  							success: function(data) {
  								  $(\'#acoes\').html(data);
  							}
							});
					}
				</script>
				';
				return($sx);
			}

		function caixa_comprovantes()
			{
				
			}	
	
		function caixa_fecha()
		{
			
		}
	
		function calcula_juros($data,$valor)
			{
				$dias = DiffDataDias($data,date("Ymd"));
				$valor = $valor * ($dias * 0.002) + ($valor * 0.02);
				$valor = round($valor*100)/100;
				if ($valor < 0) { $valor = 0; }
				if ($dias <= 1) { $valor = 0; }
				return($valor);
			}
	
		function loja_dp($loja)
			{
				if ($loja == 'J') { $dp = 'duplicata_joias'; }
				if ($loja == 'M') { $dp = 'duplicata_modas'; }
				if ($loja == 'O') { $dp = 'duplicata_oculos'; }
				if ($loja == 'S') { $dp = 'duplicata_sensual'; }
				if ($loja == 'T') { $dp = 'duplicata_teste'; }
				if ($loja == 'U') { $dp = 'duplicata_usebrilhe'; }
				if ($loja == 'D') { $dp = 'juridico_duplicata'; }
				return($dp);
			}
		function cp_FIM()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$O SIM:SIM','','Confirma Finalizacao',True,True));
				return($cp);
			}			
	
		function cp_DIN()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$U8','','',False,True));
				array_push($cp,array('$N8','','Valor em Dinheiro',True,True));
				return($cp);
			}
		function cp_FUN()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$U8','','',False,True));
				array_push($cp,array('$N8','',utf8_encode('Valor para d�bito'),True,True));
				return($cp);
			}			
		function cp_TRO()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$U8','','',False,True));
				array_push($cp,array('$N8','','Valor do troco (dinheiro)',True,True));
				return($cp);
			}	
		function cp_CRE()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$U8','','',False,True));
				array_push($cp,array('$N8','','Valor para enviar para Cr�dito',True,True));
				return($cp);
			}
		function cp_CRD()
			{
				global $dd;
				$cp = array();
				$vlrc = $this->consulta_credito();
				$op = True;
				$msg = '<TD class="lt0"><font color="red">';
				$dif = round($dd[5]*100) - round(100*$vlrc); 
				
				if ($dif > 0)
					{ $msg .= 'Valor superior ao disponivel'; }
				else { $dd[6] = '1'; }
				
				$vlr = number_format($vlrc,2,',','.');
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$U8','','',False,True));
				array_push($cp,array('$N8','','Usar Credito da consultora',True,True));
				array_push($cp,array('$H8','','',$op,True));
				array_push($cp,array('$O : &S:SIM','','Confirmar o uso',True,True));
				array_push($cp,array('$A8','','Disponivel para uso:<TD><B>'.$vlr,False,True));
				array_push($cp,array('$M','',$msg,False,True));
				return($cp);
			}
		function cp_NP()
			{
				global $dd;
				if (round(brtos($dd[6])) < round(date("Ym").'00'))
					{ $dd[6] = ''; }
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));				
				array_push($cp,array('$N8','','Valor da Nota',True,True));
				array_push($cp,array('$D8','','Vencimento',True,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));				
				array_push($cp,array('$H8','','',False,True));				
				return($cp);
			}			
			
		function cp_CHQ()
			{
				global $dd;
				$cmc = new cmc7;
				$cp = array();
				$dd[4] = sonumero($dd[4]);
				if (strlen($dd[4]) > 0)
					{
						$cmc7 = $dd[4];
						if ($cmc->valid($cmc7)==1)
							{
								$dd[7] = substr($cmc7,0,3);
								$dd[8] = substr($cmc7,3,4);
								$dd[9] = substr($cmc7,23,5);
								$dd[10] = substr($cmc7,11,6);
							} else {
								$msg = '<TD><font color="red"><B>ERRO NOS D�GITOS DO CHEQUE</font>';
								$dd[7] = '';
								$dd[8] = '';
								$dd[9] = '';
								$dd[10] = '';
							}
						
						
					}
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$S100','','UMC-Barras',True,True));
				array_push($cp,array('$N8','','Valor do Cheque',True,True));
				array_push($cp,array('$D8','',utf8_encode('Pr�-datado'),True,True));
				array_push($cp,array('$S4','','Banco',True,False));
				array_push($cp,array('$S6','',utf8_encode('Ag�ncia'),True,False));
				array_push($cp,array('$S10','','Conta',True,False));
				array_push($cp,array('$S6','','Chq Nr.',True,False));
				array_push($cp,array('$M','',$msg,False,False));
				array_push($cp,array('$HV','','Pagamento em Cheque',False,True));
				return($cp);
			}
			
		function cp_DEP()
			{
				$cp = array();
				$bcos = 'HSBC-JOIAS:HSBC Joias';
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','',utf8_encode('Valor do Dep�sito'),True,True));
				array_push($cp,array('$D8','',utf8_encode('Data dep�sito'),True,True));
				array_push($cp,array('$O '.$bcos,'','Banco',True,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$S6','','Nr. Dep',True,True));
				return($cp);
			}			
			
		function cp_ABO()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$U8','','',False,True));
				array_push($cp,array('$N8','','Valor da anomalia',True,True));
				array_push($cp,array('$HV','','',False,True));
				array_push($cp,array('$HV','','',False,True));
				array_push($cp,array('$H6','','',False,True));
				array_push($cp,array('$S10','',utf8_encode('N� anomalia'),True,True));
				array_push($cp,array('$H8','','',False,True));
				return($cp);
			}				
		function cp_MAS()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','','Valor do Ticket',True,True));
				array_push($cp,array('$D8','',utf8_encode('Data lan�amento'),True,True));
				array_push($cp,array('$A','',utf8_encode('MASTERCARD - Cr�dito'),False,True));
				array_push($cp,array('$H4','','',False,True));
				array_push($cp,array('$[1-12]','','Parcelas',False,True));
				array_push($cp,array('$S10','','AUT:',True,True));
				array_push($cp,array('$S6','','DOC:',True,True));
				return($cp);
			}
		function cp_VIS()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','','Valor do Ticket',True,True));
				array_push($cp,array('$D8','',utf8_encode('Data lan�amento'),True,True));
				array_push($cp,array('$A','',utf8_encode('Visa - Cr�dito'),False,True));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$[1-12]','','Parcelas',False,False));
				array_push($cp,array('$S10','','AUT:',True,True));
				array_push($cp,array('$S6','','DOC:',True,True));
				return($cp);
			}			
		function cp_ELC()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','','Valor do Ticket',True,True));
				array_push($cp,array('$D8','',utf8_encode('Data lan�amento'),True,True));
				array_push($cp,array('$A','',utf8_encode('Visa Electron - D�bito'),False,True));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$[1-1]','','Parcelas',False,False));
				array_push($cp,array('$S10','','AUT:',True,True));
				array_push($cp,array('$S6','','DOC:',True,True));
				return($cp);
			}
		function cp_RED()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','','Valor do Ticket',True,True));
				array_push($cp,array('$D8','',utf8_encode('Data lan�amento'),True,True));
				array_push($cp,array('$A','',utf8_encode('RedeCard - D�bito'),False,True));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$[1-1]','','Parcelas',False,False));
				array_push($cp,array('$S10','','AUT:',True,True));
				array_push($cp,array('$S6','','DOC:',True,True));
				return($cp);
			}
		function cp_HIP()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','','Valor do Ticket',True,True));
				array_push($cp,array('$D8','',utf8_encode('Data lan�amento'),True,True));
				array_push($cp,array('$A','',utf8_encode('HiperCard - D�bito'),False,True));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$[1-12]','','Parcelas',False,False));
				array_push($cp,array('$S10','','AUT:',True,True));
				array_push($cp,array('$S6','','DOC:',True,True));
				return($cp);
			}
		function cp_JUR()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));				
				array_push($cp,array('$N8','','Cobrar juros de',True,True));
				array_push($cp,array('$U8','',utf8_encode('Data lan�amento'),True,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$H4','','',False,True));
				array_push($cp,array('$H4','','',False,True));
				return($cp);
			}
		function cp_DES()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$N8','','Desconto de',True,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$H4','','',False,False));
				array_push($cp,array('$H4','','',False,True));
				array_push($cp,array('$H4','','',False,True));
				return($cp);
			}									
		function cp_gravar($dd)
			{
				global $ip,$ss;
				$log = 0;
				$ips = $_SESSION['ids'];
				
				$tipo = $dd[1];
				$valor = $dd[5];
				$data = date("Ymd");
				$hora = date("H:i");
				$venc = $dd[6];
				$doc_nr = $ips;
				
				$cmc = $dd[4];
				$banco = substr($cmc,0,3);
				$agencia = substr($cmc,3,4);
				$cheque = substr($cmc,11,6);
				$cliente = $_SESSION['cliente'];
				$descricao = $dd[11];
				$nome = $_SESSION['nome'];;
				
				if ($tipo == 'FES') { $descricao = 'Encerramento de caixa'; }
				if ($tipo == 'CHQ') { $descricao = 'Pagamento em Cheque '.$banco.'-'.$cheque; }
				if ($tipo == 'DIN') { $descricao = 'Pagamento em Dinheiro'; }

				if ($tipo == 'VIS' or $tipo=='MAS' or $tipo=='RED' or $tipo=='ELC' or $tipo=='HIP')
					{
						$banco = $dd[9];
						$agencia = $dd[11];
						$conta = $dd[10];
						$cheque = $dd[6];		
					}

				if ($tipo == 'VIS') { $descricao = 'Pagamento com cartao VISA ('.$conta.'-'.$agencia.')'; }
				if ($tipo == 'MAS') { $descricao = 'Pagamento com cartao MASTER ('.$conta.'-'.$agencia.')'; }
				if ($tipo == 'RED') { $descricao = 'Pagamento com debito do RedeCard ('.$conta.'-'.$agencia.')'; }
				if ($tipo == 'ELC') { $descricao = 'Pagamento com Visa Electron ('.$conta.'-'.$agencia.')'; }
				if ($tipo == 'HIP') { $descricao = 'Pagamento com HiperCard ('.$conta.'-'.$agencia.')'; }

				if ($tipo == 'DES') { $descricao = 'Desconto no pagamento'; }				
				if ($tipo == 'TRO') { $descricao = 'Devolucao de troco'; }
				if ($tipo == 'NOA') { $descricao = 'Nota Promissoria (Loja:'.$this->loja.' vencimento '.$dd[6].')'; }				

				if ($tipo == 'CRD') { $descricao = 'Uso de credito da consultora'; }
				if ($tipo == 'JUR') { $descricao = 'Cobranca de Juros'; }
				if ($tipo == 'FUN') { $descricao = 'Venda Funcionario'; }
				if ($tipo == 'DEP') {
						$descricao = 'Deposito Bancario '.$dd[7];
						$cheque = $dd[6];
						$conta = $dd[10];
						$agencia = $dd[7];
				}

				
				if ($tipo=='TRO') { $valor = $valor * (-1); }
				if ($tipo=='CRE') { $valor = $valor * (-1); }
				if ($tipo=='JUR') { $valor = $valor * (-1); }
				
				$sta = '@';
				if ($tipo=='FES') 
					{$cliente = ''; $nome = 'Sobra de caixa'; $sta = 'A'; $ips=''; }
				if ($tipo=='FEC') 
					{$cliente = ''; $nome = 'Falta de caixa'; $sta = 'A'; $ips='';  }
				if ($tipo=='SLD')
					{$cliente = ''; $nome = 'Saldo de caixa'; $sta = 'A'; $ips='';  }
				if ($tipo=='MAN')
					{$cliente = ''; $nome = 'Lancamento Manual'; $sta = 'A'; $ips='';  }
				echo '.';
				if (strlen($venc)==10) { $venc = brtos($venc); }
				else { $venc = date("Ymd"); }
			
				$sql = "insert into caixa_".date("Ym").'_'.$this->nloja.' ';
				$sql .= "( cx_data,cx_hora,cx_tipo,
							cx_descricao,cx_valor,cx_log,
							cx_terminal,cx_cliente,cx_nome,
							
							cx_venc,cx_doc,cx_parcela,
							cx_status,cx_lote,
							
							cx_chq_banco,cx_chq_conta,cx_chq_agencia,cx_chq_nrchq,
							
							cx_proc, cx_nrop
							) values (
							$data,'$hora','$tipo',
							'$descricao',$valor,'$log',
							'$ip','$cliente','$nome',
							
							$venc,'$doc_nr','UN',
							'$sta','',
							'$banco','$conta','$agencia','$cheque',
							0, '$ips'
							)";
				$rlt = db_query($sql);

				$this->update_nota();
				
				if ($tipo == 'CHQ')
					{
						$dg1 = substr($dd[4],0,8);
						$dg2 = substr($dd[4],8,10);
						$dg3 = substr($dd[4],18,12);
						$chq_nr = substr($dd[4],11,6);
						$pre = brtos($dd[6]);
						$sql = "select * from cheque_2009 
								where chq_dig_1 = '$dg1' and
								chq_dig_2 = '$dg2' and
								chq_dig_3 = '$dg3' and 
								chq_status <> 'X' ";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								echo 'Cheque ja lancado';
							} else {
								$chq_banco = $dd[7];
								$chq_agencia = $dd[8];
								$chq_conta = $dd[9];
								$chq_nr = $dd[10];
								$doc = 'DNP'.trim($dd[0]);
								$venc = $dd[5];					
								
								$sql = "insert into cheque_2009 
								(
									chq_dig_1,chq_dig_2,chq_dig_3,
									chq_data, chq_hora, chq_valor,
									chq_dt_deposito, chq_conta, chq_nrdep,
									chq_ip, chq_tipo, chq_cliente,
									chq_nr, chq_status, chq_pre, 
									chq_dp_hora, chq_motivo, chq_dt_devolucao, 
									chq_inventario, chq_nrop, chq_lote								
								) values (
									'$dg1','$dg2','$dg3',
									$data,'$hora',$valor,
									19000101,'','',
									'$ip','C','$cliente',
									'$chq_nr','A',$pre,
									'','',19000101,
									'A','$ips',''
								);
								";
								$rlt = db_query($sql);
							}
					}
				
				if ($tipo == 'CRE')
					{
						$valor = $valor * (-1);
						/*** Lan�a Cr�dito para Cliente */
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
								1,'$doc_nr','Cr�dito de Cliente'
								)
						";
						$rlt = db_query($sql);
					}
				if ($tipo == 'CRD')
					{
						$valor = $valor * (-1);
						/*** Lan�a Cr�dito para Cliente */
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
								1,'$doc_nr','Uso de Cr�dito de Cliente'
								)
						";
						$rlt = db_query($sql);
					}
					return(1);
			}
		function update_nota()
			{
				$sql = "update caixa_".date("Ym").'_'.$this->nloja." set cx_chq_nrchq=trim(to_char(id_cx,'0000000')) where ((length(trim(cx_chq_nrchq)) = 0) or (cx_chq_nrchq isnull))
						and cx_tipo = 'NOA' ";
				$rlt = db_query($sql);
				return(1);
			}
		function total_faturamento()
			{
				global $ip;
				$cp = ' dp_valor, dp_juros ';
				$sql = "select        $cp from duplicata_joias where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip' ";
				$sql .= "union select $cp from duplicata_teste where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select $cp from duplicata_modas where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select $cp from duplicata_oculos where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select $cp from duplicata_sensual where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select $cp from duplicata_usebrilhe where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip'  ";
				$sql .= "union select $cp from juridico_duplicata where (dp_status = 'B') and dp_lote = '' and dp_terminal = '$ip' ";
				$rlt = db_query($sql);
				$tot = 0;
				$jur = 0;
				while ($line = db_read($rlt))
					{
						$tot = $tot + $line['dp_valor'];
						$jur = $jur + $line['dp_juros'];
					}
				return(array($tot,$jur));				
			}
		function saldo_caixa_aberto($tot,$jur)
			{
				global $ip;				
				$sql = "select * from (
						select count(*) as docs, cx_tipo, sum(round(cx_valor * 100))/100 as total 
						from caixa_".date("Ym").'_'.$this->nloja."
						where cx_status = 'A' and cx_terminal = '$ip' 
						and cx_lote = ''
						group by cx_tipo
						) as tabela
						inner join caixa_tipo on cx_tipo = ct_codigo
						order by ct_lado_np, ct_ordem
						";
				$rlt = db_query($sql);
				/* Faturamento */
				$td = '<TR><TD align="right">';
				$td .= 'Faturamento';
				$td .= '<TD align="right">';
				$td .= number_format($tot,2,',','.');
				/* Juros */
				$td .= '<TR><TD align="right">';
				$td .= 'Juros';
				$td .= '<TD align="right">';
				$td .= number_format($jur,2,',','.');
								
				
				$tl = '';
				$totd = 0;
				$totl = 0;
				$din = 0;
				$fun = 0;
				while ($line = db_read($rlt))
					{
						$lado = $line['ct_lado'];
						if ($line['cx_tipo'] == 'DIN') { $din = $din + $line['total']; }
						if ($line['cx_tipo'] == 'ABR') { $fun = $fun + $line['total']; }
						if ($line['cx_tipo'] == 'ABF') { $din = $din + $line['total']; }
						if ($line['cx_tipo'] == 'TRO') { $din = $din + $line['total']; }
						
						if ($lado == 'D')
							{
								$tl .= '<TR><TD align="right">';
								$tl .= trim($line['ct_descricao']);
								$tl .= '<TD align="right">';
								$tl .= number_format($line['total'],2,',','.');
								$tl .= '<TD align="center">';
								$tl .= number_format($line['docs'],0);
								$totl = $totl + $line['total'];
							} else {
								$td .= '<TR><TD align="right">';
								$td .= trim($line['ct_descricao']);
								$td .= '<TD align="right">';
								$td .= number_format($line['total'],2,',','.');
								$td .= '<TD align="center">';
								$td .= number_format($line['docs'],0);
								$totd = $totd + $line['total'];						
							}	
					}
				$this->dinheiro = $din;
				$tl .= '<TR><TD align="right">Sub-total<TD align="right">'.number_format($totl,2,',','.');
				$td .= '<TR><TD align="right">Sub-total<TD align="right">'.number_format($totd,2,',','.');
				$sx .= '<center><Table width="90%" class="lt4" width="600">';
				$sx .= '<TR valign="top">';
				$sx .= '<TD width="50%"><Table cellpadding=3 class="lt2" border=1 width="90%">'.$td.'</Table>';
				$sx .= '<TD width="50%"><Table cellpadding=3 class="lt2" border=1 width="90%">'.$tl.'</Table>';
				$sx .= '<TR><TD colspan=2>Total em dinheiro '.number_format($din,2,',','.').', fechamento de caixa de '.number_format($fun,2,',','.');
				$sx .= '. Total em dinheiro '.number_format($din+$fun,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
		function lancamento_quitar()
			{
				global $dd;
				$doc = 'DNP'.trim($dd[0]);
				$sql = "update caixa_".date("Ym").'_'.$this->nloja." 
					set cx_status = 'A' where
					cx_doc = '$doc' and cx_status = '@' ";
				$rlt = db_query($sql);
				return(1);
			}
		function notapromissoria_quitar()
			{
				global $loja,$dd,$ip;
				$ll = $this->loja_dp($loja);
				
				$sql = "select * from $ll where id_dp = ".round($dd[0]);
				$rlt = db_query($sql);
				$line = db_read($rlt);
				
				$boleto = trim($line['dp_boleto']);
				
				$sql = "update $ll  
						set dp_status = 'B', dp_horapaga = '".date("H:i")."',
						dp_datapaga = '".date("Ymd")."', dp_terminal = '$ip',
						dp_logpaga = 'CX2', dp_boleto = '$boleto'
						where id_dp = ".round($dd[0])."
						and dp_cliente = '".$this->cx_cliente."' ";
				$rlt = db_query($sql);
				return(1);
			}
		function pagamento_excluir($id)
			{
				global $dd;
				$sql = "update caixa_".date("Ym").'_'.$this->nloja." 
					set cx_status = 'X' where
					id_cx = ".round($id);
				$rlt = db_query($sql);
				redirecina('main.php?dd99=nota_promissoria&dd0='.$dd[0].'&dd1='.$dd[1]);
			}
			
		function pagamento_soma()
			{
				global $dd;
				$doc = 'DNP'.trim($dd[0]);
				$sql = "select sum(cx_valor) as total from caixa_".date("Ym").'_'.$this->nloja."
					where cx_status <> 'X' and cx_doc = '$doc' 
					group by cx_doc
					";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return($line['total']);
			}
		function acoes_quitar($tp)
			{
				global $cp,$acao,$tab_max,$dd;
				$tab_max = "100%";
				$tabela = '';
				$cp = array();
				if ($tp == 'DIN') { $cp = $this->cp_DIN(); }
				if ($tp == 'CHQ') { $cp = $this->cp_CHQ(); }
				if ($tp == 'NOA') { $cp = $this->cp_NP(); }
				if ($tp == 'MAS') { $cp = $this->cp_MAS(); }
				if ($tp == 'VIS') { $cp = $this->cp_VIS(); }
				if ($tp == 'RED') { $cp = $this->cp_RED(); }
				if ($tp == 'ELC') { $cp = $this->cp_ELC(); }
				if ($tp == 'HIP') { $cp = $this->cp_HIP(); }
				if ($tp == 'JUR') { $cp = $this->cp_JUR(); }
				if ($tp == 'DES') { $cp = $this->cp_DES(); }
				if ($tp == 'DEP') { $cp = $this->cp_DEP(); }
				if ($tp == 'TRO') { $cp = $this->cp_TRO(); }
				if ($tp == 'CRE') { $cp = $this->cp_CRE(); }
				if ($tp == 'CRD') { $cp = $this->cp_CRD(); }
				if ($tp == 'ABO') { $cp = $this->cp_ABO(); }
				if ($tp == 'FIM') { $cp = $this->cp_FIM(); }
				if ($tp == 'FUN') { $cp = $this->cp_FUN(); }
							
				/* Completa campos */
				while (count($cp) < 13)
					{
						array_push($cp,array('$H8','','',False,True));
					}
				$sf  = '';
				$sf  .= '<center>';
						$sf  .= '<table align="center" width="580" border=0>';
						for ($r=0;$r < count($cp);$r++)
							{
								$sf  .= '<TR><TD width="180" align="right">';
								$sf  .= $cp[$r][2];
								//echo '<TD>';
								$sf  .= sget('dd'.$r,$cp[$r][0],'True');
								//echo gets($cp[$r][2],$cp[$r][1],$cp[$r][1],$cp[$r][3],$cp[$r][4],$cp[$r][5],$cp[$r][6]);		
							}
						$sf  .= '<TR><TD><TD><input type="button" value="gravar >>" id="saving">';
						$sf  .= '</table>';	
				
				$sf  .= '
				<script>
				$("#saving").click(function() {
						var dd1 = "0";
						var dd1 = "'.$dd[1].'";
						var dd2 = $("#dd2").val();
						var dd3 = $("#dd3").val();
						var dd4 = $("#dd4").val();
						var dd5 = $("#dd5").val();
						var dd6 = $("#dd6").val();
						var dd7 = $("#dd7").val();
						var dd8 = $("#dd8").val();
						var dd9 = $("#dd9").val();
						var dd10 = $("#dd10").val();
						var dd11 = $("#dd11").val();
						
						$.ajax({
							type: \'GET\',
  							url: \'cx_ajax_pagamento.php\',
  							data: { dd1: dd1, dd2: dd2, dd3: dd3, dd4: dd4,
  								dd5: dd5, dd6: dd6, dd7: dd7, dd8: dd8,
  								dd9: dd9, dd10: dd10, dd11: dd11
							 }
							})
							.done(function( html ) 
								{
									$(\'#dados_mini4\').html(html); 
								});						
				});
				</script>
				
				';
				$saved = 1;
				for ($r=0;$r < count($cp);$r++)
					{
						if (($cp[$r][3]==1) and (strlen($dd[$r])==0))	
							{ $saved = 0; }
					}
				
				if ($saved > 0)
					{
						if ($this->cp_gravar($dd)==1)
							{
								redirecina('nada.php');
								exit;
							}	
					}
				echo $sf;

				if ($dif > 0)
						{
							if ($np->sync == 'FUNC')
								{
									$sql = "select * from caixa_tipo where ct_codigo = 'FUC' ";									
								} else {			
									$sql = "select * from caixa_tipo where ct_ativo = 1
									and ct_recebido = 1 ";
									$sql .= " order by ct_ordem ";
								}
						} else {
							$sql = "select * from caixa_tipo where ct_ativo = 1
								and ct_recebido = -1 ";								
						}
					$rlt = db_query($sql);
					
				return($quitar);
			}
			
	function mostra_pagamentos($acao=1)
		{
			global $dd;
			$sql = "select * from caixa_".date("Ym").'_'.$this->nloja."
				where cx_doc = 'DNP".$dd[0]."' and (cx_status = '@' or cx_status = 'A') ";
			$rlt = db_query($sql);
			$sx = '';
			while ($line = db_read($rlt))
				{
					$link = '';
					if (($line['cx_status']=='@') or ($line['cx_status']=='A'))
					{ $link = '<a href="main.php?dd99=nota_promissoria&dd0='.$dd[0].'&dd1='.$dd[1].'&dd96='.$line['id_cx'].'&dd97=DEL" class="lt1"><font color="red">excluir</A>'; }
					$tipo = $line['cx_tipo'];
					if (($tipo == 'CRD') or ($tipo == 'CHQ') or ($tipo == 'CRE'))
						{ $link = ''; }
					$linkw = '';
					if ($line['cx_tipo'] == 'NOA')
						{
							$linkw = '<A HREF="javascript:newxy2(\'nota_promissoria.php?dd0='.$line['id_cx'].'&dd2='.$this->nloja.'&dd1=caixa_'.date("Ym").'_'.$this->nloja.'\',800,400);">';
						}
					$sx .= '<TR '.coluna().' class="lt3">';
					$sx .= '<TD align="right" width="10%">';
					$sx .= number_format($line['cx_valor'],2);					
					$sx .= '<TD align="left">';
					$sx .= $linkw;
					$sx .= $line['cx_descricao'];
					if ($acao==1)
						{
						$sx .= '<TD width="10%" align="center">';
						$sx .= $link;
						}
				}
			if (strlen($sx) > 0)
				{
					$sx = '<TR><TH>Valor<TH>Descri��o<TH>A��o'.$sx;
				}
			return($sx);
			
		}
	function cx_autorizado()
		{
			global $ip;
			$sql = "select * from caixa_autorizado ";
			$sql .= " where ca_ip = '".$ip."' ";
		
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->nloja = trim($line['ca_caixa']);
					$this->loja = trim($line['ca_loja']);
					$rst = 1;
				} else {
					$this->loja = '';
					$rst = 0;
				}
			return($rst);
		}

	function cx_resultado_mostra($page='')
		{
			global $dd;
			$codigo = trim(sonumero($dd[1]));
	
			/* busca pelo codigo */
			if (strlen($codigo)==7)
				{
					$sql = "select * from cadastro where cl_cliente = '".$codigo."' ";
					$sql .= " order by cl_cliente limit 100 ";
					$rlt = db_query($sql);
					$sx .= $this->cx_mostra_consultoras($rlt,$page);
				} else {
					$st = UpperCaseSql($dd[1]).' ';
					$st = troca($st,' ',';');
					$st = splitx(';',$st);
					$sh = '';
					for ($r=0; $r < count($st);$r++)
						{
							if (strlen($wh) > 0)
								{ $wh .= ' and '; }
							$wh .= " (cl_nome like '%".$st[$r]."%' ) ";
						}
					$sql = "select * from cadastro where ".$wh." ";
					$sql .= " order by cl_nome ";
					$sql .= " limit 100";
					$rlt = db_query($sql);	
					
					$sx .= $this->cx_mostra_consultoras($rlt,$page);				
				}	
			return($sx);		
		}
	function cx_mostra_consultoras($rlt,$page='')
		{
			if (strlen($page)==0) { $page = 'cx_consultora.php'; }
			$sx = '<table width="100%" cellpadding=2 cellspacing=4>';
			$sx .= '<TR class="tabela01h"><TH  >Nome da Consultora<TH>C�digo<TH>Dt.Nasc.<TH>Tipo';
			while ($line = db_read($rlt))
				{
					$tipo = trim($line['cl_autorizada']);
					if (strlen($tipo)==0) { $tipo = 'titular'; }
					
					$link = '<A HREF="'.$page.'?dd0='.$line['cl_cliente'].'&ddx='.date("YmdHis").'">';
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD  class="tabela01">';
					$sx .= $link;
					$sx .= trim($line['cl_nome']);
					$sx .= '</A>';
					
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link;
					$sx .= trim($line['cl_cliente']);
					$sx .= '</A>';
					
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link;
					$sx .= stodbr($line['cl_dtnascimento']);
					$sx .= '</A>';
					
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link;
					$sx .= $tipo;
					$sx .= '</A>';
				}
			$sx .= '</table>';
			return($sx);
		}
	function notas_do_cliente($cliente = '')
		{
			if (strlen($cliente) > 0) { $this->cx_cliente = $cliente; }
			$cliente = $this->cx_cliente;
			$rsp = array();
			$cp = '*';
			$cp = 'id_dp,dp_status, dp_doc,dp_historico,dp_content,dp_cliente,dp_valor,dp_venc,dp_data,dp_nr,dp_nrop, dp_terminal ';
			$sql = "select        $cp,'J' as dp_loja from duplicata_joias where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente'  ";
			$sql .= "union select $cp,'T' as dp_loja from duplicata_teste where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente'  ";
			$sql .= "union select $cp,'M' as dp_loja from duplicata_modas where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente'  ";
			$sql .= "union select $cp,'O' as dp_loja from duplicata_oculos where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente'  ";
			$sql .= "union select $cp,'S' as dp_loja from duplicata_sensual where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente'  ";
			$sql .= "union select $cp,'U' as dp_loja from duplicata_usebrilhe where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente'  ";
			$sql .= "union select $cp,'D' as dp_loja from juridico_duplicata where (dp_status = '@' or dp_status = 'A') and dp_cliente = '$cliente' ";
			$sql .= " order by dp_historico, dp_venc ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					array_push($rsp,$line);
				}
			return($rsp);
		}
	/**
	 * Quitar Nota
	 */

	function nota_quitar($nt)
		{
			echo $nt->nota_dados();
		}
	 
	function mostra_notas($rst,$tp='')
		{
			$sx = '<table width="100%" cellpadding="0" cellspacing="2" class="tabela01">';
					$sx .= '<TR class="lt1">';
					$sx .= '<TH>Fatura';
					$sx .= '<TH>LJ';
					$sx .= '<TH>Valor';
					if ($tp != '2') { $sx .= '<TH>Nome'; } else { $sx .= ''; }
					$sx .= '<TH>Vencimento';
					$sx .= '<TH>Hist�rico';
					for ($r=0;$r < count($rst);$r++)
						{
							$line = $rst[$r];
		
							/* Cor se atrasado */
							$cor = '';
							if ($line['dp_venc'] < date("Ymd")) { $cor = '<font color="red">'; }
		
							/* Link de pagamento */
							//$link = '<a href="caixa_nota_quitar.php?dd0='.$line['id_dp'].'&dd2='.$line['dp_cliente'].'&dd3='.$line['dp_loja'].'&dd90='.checkpost($line['id_dp'].$line['dp_cliente']).'" class="notas">';
							if (($line['dp_status']=='@') or ($line['dp_status']=='A'))
								{ $link = '<a href="cx_consultora.php?dd0='.$line['dp_cliente'].'" class="lt1">'; }
							
							$sx .= '<TR '.coluna().'  class="lt2" align="left">';
							$sx .= '<TD align="left" height="26">';
							$sx .= $link;
							$sx .= $line['dp_doc'].'</a>';
							$sx .= '<TD>';
							$sx .= $link;
							$sx .= $line['dp_loja'].'</a>';
							$sx .= '<TD align="right"><B>';
							$sx .= $link;
							$sx .= $cor;
							$sx .= number_format($line['dp_valor'],2).'</a>';
							if ($tp != '2') 
								{
								$sx .= '<TD align="left">';
								$sx .= $link;
								$sx .= $line['dp_historico'].'</a>';
								}
							
							$sx .= '<TD>&nbsp';
							$sx .= $link;
							$sx .= $cor;
							$sx .= stodbr($line['dp_venc']).'</a>';
							$sx .= '<TD align="left">';
							$sx .= $link;
							$sx .= $line['dp_content'].'</a>';
							
							$sx .= '<TR><TD colspan=10 bgcolor="#000000" height=1>';
				}
			$sx .= '</table>';
			return($sx);
		}
	function notas_na_tela()
		{
			$rsp = array();
			$cp = '*';
			$cp = 'id_dp,dp_doc,dp_historico,dp_content,dp_cliente,dp_valor,dp_venc,dp_data,dp_nr,dp_status ';
			$sql = "select        $cp,'J' as dp_loja from duplicata_joias where dp_status = '@' ";
			$sql .= "union select $cp,'T' as dp_loja from duplicata_teste where dp_status = '@' ";
			$sql .= "union select $cp,'M' as dp_loja from duplicata_modas where dp_status = '@' ";
			$sql .= "union select $cp,'O' as dp_loja from duplicata_oculos where dp_status = '@' ";
			$sql .= "union select $cp,'S' as dp_loja from duplicata_sensual where dp_status = '@' ";
			$sql .= "union select $cp,'U' as dp_loja from duplicata_usebrilhe where dp_status = '@' ";
			$sql .= "union select $cp,'D' as dp_loja from juridico_duplicata where dp_status = '@' ";
			$sql .= " order by dp_historico, dp_venc ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					array_push($rsp,$line);
				}
			return($rsp);
		}
		
	function cx_foot()
		{
			$sx .= 
			'
			<div id="foot">
				<div id="foot_saldo">'.$this->cx_saldo_dinheiro().'</div>
			</div>
			';
			return($sx);
		}
	function cx_saldo_dinheiro()
		{
			return('0,00');
		}
	function cx_form_busca()
		{
			global $dd;
			$bt1 = 'localizar >>';
			$msg = 'Informe parte do nome da consultura ou seu c�digo';
			$sx .= '
			<form method="get" action="'.page().'">
 			<div id="search">
				'.$msg.'
				<input type="text" name="dd1" id="form_search" value="'.$dd[1].'">
				<BR>
				<input type="submit" name="dd50" id="form_button" value="'.$bt1.'">
			</div>
			</form>
			';
			return($sx);
		}
		
	function cx_resultado_busca()
		{
			
		}
	function cx_aberto()
		{
			global $ip;
			$this->tabela = "caixa_".date("Ym")."_".$this->nloja;
			$data = date("Ymd");
			$tabela = $this->tabela;
			$sql .= "select count(*) as aberto from $tabela ";
			$sql .= " where ";
			//cx_data = $data ";
			$sql .= " cx_tipo = 'ABR' and cx_lote = '' ";
			$sql .= " and cx_terminal = '".$ip."' ";	
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$rst = $line['aberto'];
		return($rst);
		}
		
	}
?>