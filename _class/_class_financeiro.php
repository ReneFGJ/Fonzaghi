<?php
    /**
     * Financeiro
     * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v.0.13.49
     * @package Classe
     * @subpackage Financeiro
    */
    

class financeiro
		{
					
			var $op = array('');
			var $mov_vlr = array ('');
			var $mov_tipo = array ('');
			var $mov_banco = array (''); 
			var $mov_bc_nome = array ('');  
			var $mov_grupo = array ('');
			var $mov_dia = array('');
			var $include_class= '../';
			var $cal_dia = array ('');
			var $cal_ativo = array ('');
					
			
	function updatex()
		{
			$sql = "select * from contas_pagar
					where cr_historico_asc = ''
			";
			$rlt = db_query($sql);
			$sql = "";
			while ($line = db_read($rlt))
				{
					$sql .= "update contas_pagar 
							set cr_historico_asc = '".UpperCaseSQL(trim($line['cr_historico']))."' 
							where id_cr = ".$line['id_cr'].';'.chr(13).chr(10);					
				}
			if (strlen($sql) > 0) { $rlt = db_query($sql); }
			return(1);
		}    

	function saldo_notas($dd1)
		{
			$nps = array();
			array_push($nps,'duplicata_joias');
			array_push($nps,'duplicata_modas');
			array_push($nps,'duplicata_oculos');
			array_push($nps,'duplicata_sensual');
			array_push($nps,'duplicata_teste');
			array_push($nps,'duplicata_usebrilhe');
			array_push($nps,'duplicata_extras');
			array_push($nps,'juridico_duplicata');
			
			$npn = array();
			array_push($npn,'Joias');
			array_push($npn,'Modas');
			array_push($npn,'Oculos');
			array_push($npn,'Sensual');
			array_push($npn,'Teste');
			array_push($npn,'UseBrilhe');
			array_push($npn,'Extras');
			array_push($npn,'Juridico');			
			$sx = '<table width="700">';
			$sx .= '<TR>';
			$sx .= '<TH class="tabelaTH" align="left">'.'Loja';
			$sx .= '<TH class="tabelaTH">'.'Notas';
			$sx .= '<TH class="tabelaTH" align="right">'.'Valor Total';
			$nts = 0;
			$total = 0;
			for ($r=0;$r < count($nps);$r++)
				{
				$table = $nps[$r];
				$sql = "select count(*) as total, sum(round(dp_valor*100))/100 as valor 
						from $table
						where dp_data <= $dd1 and 
						((dp_status = 'A' or dp_status = '@') or (dp_datapaga > $dd1 and dp_status = 'B')); ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$sx .= '<TR>';
				$sx .= '<TD class="tabela00">'.$npn[$r];
				$sx .= '<TD class="tabela00" align="center">'.$line['total'];
				$sx .= '<TD class="tabela00" align="right">'.number_format($line['valor'],2,',','.');
				$valor = $valor + $line['valor'];
				$total = $total + $line['total'];
				}
			$sx .= '<TR><TD>Total';
			$sx .= '<TD align="center"><B>';
			$sx .= $total;
			$sx .= '<TD align="right"><B>';
			$sx .= number_format($valor,2,',','.');
			$sx .= '</table>';
			return($sx);
		}
	
	function cp()
		{
			global $dd, $user_id, $acao;
			/* seta variaveis se vazia */
			if (strlen($dd[2]) ==0) { $dd[2] = '0.00'; }
			if (strlen($dd[3]) ==0) { $dd[3] = date("d/m/Y"); }
			if (strlen($dd[7]) ==0) { $dd[7] = date("d/m/Y"); }
			if (strlen($dd[12]) ==0) { $dd[12] = '19000101'; }
			$dd[11] = '0000000';
			$dd[15] = UpperCaseSQL($dd[4]);
			$dd[16] = '';
			$dd[17] = date("Ymd");
			$dd[18] = $dd[2];
			$dd[19] = '0'.$user_id;
			$dd[20] = date("H:i"); /* Hora */
			$dd[21] = '0'.$user_id; /* log so usuario */
			
			$cp = array();
			//// dd00
			array_push($cp,array('$H4','id_cr','cod',False,True,''));
			array_push($cp,array('$Q dt_descricao:dt_codigo:select * from documento_tipo where dt_ativo=1 order by dt_ordem','cr_tipo','Tipo Documento ',False,True,''));
			array_push($cp,array('$N10','cr_valor','Valor ',True,True,''));
			array_push($cp,array('$D8','cr_venc','Vencimento ',True,True,''));
			array_push($cp,array('$S80','cr_historico','Histórico ',True,True,''));

			//// dd05
			array_push($cp,array('$S10','cr_pedido','Pedido ',True,True,''));
			array_push($cp,array('$S10','cr_parcela','Parcela ',True,True,''));
			array_push($cp,array('$O 0:Não&1:SIM','cr_previsao','Previsão ',True,True,''));
			array_push($cp,array('$S10','cr_doc','Nº Doc. ',False,True,''));
			array_push($cp,array('$Q e_nome:id_e:select * from empresa where e_ativo=1 order by e_ordem','cr_empresa','Empresa ',False,True,''));

			//// dd10
			array_push($cp,array('$Q ct_descricao:ct_codigo:select * from contas_tipo where ct_ativo=1 and ct_tipo=2 order by ct_descricao','cr_conta','Conta ',False,True,''));
			array_push($cp,array('$H8','cr_cliente','Cliente : ',False,True,''));
			array_push($cp,array('$H8','cr_dt_quitacao','Dt. Quitação : ',False,True,''));
			array_push($cp,array('$O N:Ativo&X:Excluir','cr_status','Situação',False,True,''));
			array_push($cp,array('$H1','cr_img','Img',False,True,''));

			//// dd15
			array_push($cp,array('$H1','cr_historico_asc','',False,True,''));
			array_push($cp,array('$H1','cr_cc','',False,True,''));
			array_push($cp,array('$H1','cr_lastupdate','',False,True,''));
			array_push($cp,array('$H1','cr_valor_original','',False,True,''));
			array_push($cp,array('$H1','cr_log','',False,True,''));

			//// dd20
			array_push($cp,array('$H1','cr_hora','',False,True,''));
			array_push($cp,array('$H1','cr_log_paga','',False,True,''));
			
			if (strlen($dd[0])==0)
				{
				array_push($cp,array('${','','Periodicidade',False,True,''));
				array_push($cp,array('$O d:só este&m:todo mes','','Replicações',False,True,''));
				array_push($cp,array('$[1-48]','','Número de lancamentos',False,True,''));
				array_push($cp,array('$}','','Periodicidade',False,True,''));				
				}	
			//array_push($cp,array('$H8','','',True,True,''));		
			return($cp);
		}			
		function cp_data()
			{
				global $dd;
				$cp = array();
				array_push($cp,array('$H8','','',false,True));
                array_push($cp,array('$O &01:Janeiro&02:Fevereiro&03:Marco&04:Abril&05:Maio&06:Junho&07:Julho&08:Agosto&09:Setembro&10:Outubro&11:Novembro&12:Dezembro','','Mes',false,True));
                array_push($cp,array('$O &'.date('Y').':'.date('Y').'&'.(date('Y')-1).':'.(date('Y')-1).'&'.(date('Y')-2).':'.(date('Y')-2).'&'.(date('Y')-3).':'.(date('Y')-3),'','Ano',false,True));
                array_push($cp,array('$O &1:Joias - Santander&16:Joias - HSBC&3:Modas - Santander&17:Modas - Itau&18:Sensual - Itau&21:Sensual - Santander&22:Use Brilhe - Santander&20:Use Brilhe - Itau','','Contas',false,True));
				return($cp);
			}
			
		function cp_mes()
			{
				global $dd;
				$cp = array();
				array_push($cp,array('$H8','','',false,True));
                array_push($cp,array('$D','','Data inicial',false,True));
				return($cp);
			}
				
		function le_balancete($mes,$ano,$conta=0)
			{
				global $base_name,$base_server,$base_host,$base_user,$user;
				require($this->include_class."db_caixa_central.php");	
				/*0- joias, 1-modas, 2-sensual, 3-UB */
				$sql ="select beg_descricao, ext_conta,ext_tipo,bco_descricao, sum(ext_valor),ext_data 
						from( select ext_conta,ext_tipo, bco_descricao, ext_valor,ext_data,ext_auto 
							from ( select ext_conta,ext_tipo,ext_valor, ext_data,ext_auto 
								from banco_extrato 
								where 	ext_data>=".$ano.$mes."00 and 
										ext_data<=".$ano.$mes."99 and
										ext_auto='S' 		
								) as tb 
							left join banco on id_bco=ext_conta 
							where id_bco='".$conta."') as tb 
						left outer join banco_extrato_grupo on beg_grupo=ext_tipo		
						group by beg_descricao, ext_conta,ext_tipo,bco_descricao,ext_data 
						order by ext_conta,ext_data,ext_tipo"; 
				$rlt = db_query($sql);
				$i=0;
				while($line = db_read($rlt))
				{
							if(trim(round(substr($line['ext_data'],6,2)))!=$diax){$i=0;}
							$dia=$this->mov_dia[$dia][$i]=trim(round(substr($line['ext_data'],6,2)));
		 					$this->mov_vlr[$dia][$i]=$line['sum'];
		 					$this->mov_tipo[$dia][$i]=trim($line['ext_tipo']);
		 					$this->mov_grupo[$dia][$i]=$line['beg_descricao'];
		 					$this->mov_banco[$dia][$i]=$line['ext_conta']; 
							$this->mov_bc_nome[$dia][$i]=$line['bco_descricao'];
							$diax=$dia;
					$i++;
        		}	
					
				return(1);
			}

		function carrega_calendario($mes,$ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			require($this->include_class."db_bi.php");	
			$sql = "select * from calendario 
						where cal_data>=".$ano.$mes."00 and
							  cal_data<=".$ano.$mes."99	
						order by cal_data
						";
			$rlt = db_query($sql);
			$i=1;
			while($line=db_read($rlt))
				{
					$this->cal_dia[$i]=trim(round(substr($line['cal_data'],6,2)));
					$this->cal_ativo[$i]=$line['cal_ativo'];
					$this->cal_semana[$i]=$line['cal_weekday'];
					$i++;
				}
			return(1);
				
		}
		function mostra_balancete($mes,$ano,$conta=0)
			{
				$this->carrega_calendario($mes, $ano);	
				$this->le_balancete($mes, $ano, $conta);
				$i=0;
				$vxtt=0;
				while($i<=count($this->cal_dia)-1)
				{
						$j=0;	
						$dia=trim(round($this->cal_dia[$i]));
							if($dia!=$diax)
							{
								$vx0=$vx1=$vx2=$vx3=$vx4=$vx5=$vx6=$vx7=0;
							}
						while($j<=count($this->mov_dia[$i]))
						{	
							$tipo = trim($this->mov_tipo[$dia][$j]);
							switch($tipo)
							{
								case 'SLD':
									$vx0 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;	
								case 'RES':
								case 'APL':
									$vx1 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;
								case 'DEC':
									$vx2 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;
								case 'DEV':
									$vx3 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;
								case 'VIS':
									$vx4 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;
								case 'CHQ':
									$vx5 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;	
								case 'TAX':
								case 'IOF':
									$vx6 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;
								case 'DIN':
									$vx7 += $this->mov_vlr[$dia][$j];
									$vxtt += $this->mov_vlr[$dia][$j];
								break;
								
								default:
									if(trim($tipo)!='')
									{
										echo "<br>Verificar com TI movimentos do tipo : ".$tipo." nao esta sendo computado";
									}
								break;
							}
							$j++;
						}
					if($i!=0)
					{	
					if($i==1 or $i==17)
					{
							$st= 'style="font-weight:bold; font-size:8px; height:20px; width:5%"';
							$tx  = '<br><table><th class="tabela01" '.$st.'>Dia</th>';	
							$tx0 = '<tr><td class="tabela01" '.$st.'>Saldo inicial</td>';					
							$tx1 = '<tr><td class="tabela01" '.$st.'>Aplicações/Resgates automáticos</td>';
							$tx2 = '<tr><td class="tabela01" '.$st.'>Depositos em cheque</td>';
							$tx3 = '<tr><td class="tabela01" '.$st.'>Cheques devolvidos</td>';
							$tx4 = '<tr><td class="tabela01" '.$st.'>Depositos de terceiro/Dinheiro</td>';
							$tx5 = '<tr><td class="tabela01" '.$st.'>Cartões de débito e crédito</td>';
							$tx6 = '<tr><td class="tabela01" '.$st.'>Cheques/DOC/TED</td>';
							$tx7 = '<tr><td class="tabela01" '.$st.'>Juros IOF/IOC despesas bancarias</td>';
							$tx8 = '<tr><td class="tabela01" '.$st.'>Transferencias entre contas</td>';
							$tx9 = '<tr><td class="tabela01" '.$st.'>Saldo final</td>';
							$tx10  = '</table>';
					}
					$stH= 'style="font-size:8px; height:20px; width:5%" align="center"';
					$st= 'style="font-size:8px; height:20px; width:5%" align="right"';
					$tx  .= '<th class="tabela01" '.$stH.'>'.$i.'</th>';
					$tx0 .= '<td class="tabela01" '.$st.'>'.number_format($vxtt,2,',','.').'</td>';					
					$tx1 .= '<td class="tabela01" '.$st.'>'.number_format($vx1,2,',','.').'</td>';
					$tx2 .= '<td class="tabela01" '.$st.'>'.number_format($vx2,2,',','.').'</td>';
					$tx3 .= '<td class="tabela01" '.$st.'>'.number_format($vx3,2,',','.').'</td>';
					$tx4 .= '<td class="tabela01" '.$st.'>'.number_format($vx7,2,',','.').'</td>';
					$tx5 .= '<td class="tabela01" '.$st.'>'.number_format($vx4,2,',','.').'</td>';
					$tx6 .= '<td class="tabela01" '.$st.'>'.number_format($vx5,2,',','.').'</td>';
					$tx7 .= '<td class="tabela01" '.$st.'>'.number_format($vx6,2,',','.').'</td>';
					$tx8 .= '<td class="tabela01" '.$st.'>0</td>';
					$tx9 .= '<td class="tabela01" '.$st.'>'.number_format($vxtt,2,',','.').'</td>';
					}else{
					//$vxtt = $vx0+$vx1+$vx2+$vx3+$vx4+$vx5+$vx6+$vx7+$vxtt;	
					}
					$vx0tt+=$vx0;
					$vx1tt+=$vx1;
					$vx2tt+=$vx2;
					$vx3tt+=$vx3;
					$vx4tt+=$vx4;
					$vx5tt+=$vx5;
					$vx6tt+=$vx6;
					$vx7tt+=$vx7;
					
					if($i==count($this->cal_dia)-1)
					{
						$stH= 'style="font-size:8px; height:20px; width:5%" align="center"';
						$st= 'style="font-size:8px; height:20px; width:5%" align="right"';
						$tx  .= '<th class="tabela01" '.$stH.'>Total</th>';
						$tx0 .= '<td class="tabela01" '.$st.'>'.number_format($vx0tt,2,',','.').'</td>';					
						$tx1 .= '<td class="tabela01" '.$st.'>'.number_format($vx1tt,2,',','.').'</td>';
						$tx2 .= '<td class="tabela01" '.$st.'>'.number_format($vx2tt,2,',','.').'</td>';
						$tx3 .= '<td class="tabela01" '.$st.'>'.number_format($vx3tt,2,',','.').'</td>';
						$tx4 .= '<td class="tabela01" '.$st.'>'.number_format($vx7tt,2,',','.').'</td>';
						$tx5 .= '<td class="tabela01" '.$st.'>'.number_format($vx4tt,2,',','.').'</td>';
						$tx6 .= '<td class="tabela01" '.$st.'>'.number_format($vx5tt,2,',','.').'</td>';
						$tx7 .= '<td class="tabela01" '.$st.'>'.number_format($vx6tt,2,',','.').'</td>';
						$tx8 .= '<td class="tabela01" '.$st.'>0</td>';
						$tx9 .= '<td class="tabela01" '.$st.'>'.number_format($vxtt,2,',','.').'</td>';
					}
					
					if(($i==16) or ($i==count($this->cal_dia)-1))
					{
						$tx0.='</tr>'.chr(10);$tx1.='</tr>'.chr(10);$tx2.='</tr>'.chr(10);$tx3.='</tr>'.chr(10);$tx4.='</tr>'.chr(10);$tx5.='</tr>'.chr(10);$tx6.='</tr>'.chr(10);$tx7.='</tr>'.chr(10);$tx8.='</tr>'.chr(10);$tx9.='</tr>'.chr(10);
						$sx .= $tx.$tx0.$tx1.$tx2.$tx3.$tx4.$tx5.$tx6.$tx7.$tx8.$tx9.$tx10;
						
					}
					
					$diax=$dia;
				$i++;
				}
				
				return($sx);
			}
			
			function inserir_saldo($mes,$ano)
			{
				global $base_name,$base_server,$base_host,$base_user,$user;
				require($this->include_class."db_caixa_central.php");
				//$mes = 01;
				$sql = "select id_bco from banco
						where bco_ativo='S' and id_bco<>2 and id_bco<>23
						group by id_bco
						";
				$rlt = db_query($sql);
				while($line = db_read($rlt))
				{
					$conta=$line['id_bco'];
					$sql1 = "select sum(ext_valor) from banco_extrato 
							where ext_conta=".$conta." and
								   ext_data>=".$ano.$mes."00 and
								   ext_data<=".$ano.$mes."99 and 
								   ext_auto='S' and
								   (
								   ext_tipo='SLD' or
								   ext_tipo='RES' or
								   ext_tipo='APL' or
								   ext_tipo='DEC' or
								   ext_tipo='DEV' or
								   ext_tipo='VIS' or
								   ext_tipo='CHQ' or
								   ext_tipo='TAX' or
								   ext_tipo='IOF' or	
								   ext_tipo='DIN')
							";
					$rlt1 = db_query($sql1);
					$line1 = db_read($rlt1);
					/*caso seja dezembro carrega janeiro*/
					$mesx = $mes+1;
					$anox = $ano;
					if($mesx==13)
					{
						$mesx='01';
						$anox=$ano+1;
					}
					$sql3 = "select * from banco_extrato
							where ext_conta=".$conta." and
								   ext_data>=".$anox.substr('0'.$mesx,-8)."00 and
								   ext_data<=".$anox.substr('0'.$mesx,-8)."99 and
								   ext_tipo='SLD'
								   	
							";

					$rlt3 = db_query($sql3);
					
					if($line3 = db_read($rlt3))
					{
						$sql2 = "update banco_extrato 
								 set ext_valor= ".$line1['sum'].",
								 	 ext_data_lanc=".date('Ymd')."
								 where id_ext=".$line3['id_ext']."	 
								";
						$vld=1;		
					}else{
						$sql2 = "insert into banco_extrato(
														  ext_conta,ext_historico,ext_valor,
								  						  ext_status,ext_tipo,ext_doc,
								  						  ext_pedido,ext_ativo,ext_auto,
								  						  ext_data,ext_venc,ext_pre,
								  						  ext_data_lanc
												) values (
														".$conta.",'Saldo Inicial',".$line1['sum'].",
														'A','SLD','0000000',
														'','S','S',
														".$anox.substr("0".$mesx,-2)."00,0,0,	
														".date('Ymd')."
								)";
					}	
					$rlt2 = db_query($sql2);
					$line3='';
					$vld=0;
				}		
				return(1);
			}
         

         
         
}