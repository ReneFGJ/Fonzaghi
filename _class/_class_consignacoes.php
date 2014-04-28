<?php
class consignacoes
	{
		var $dt_acerto;
		var $kit_atrasado = 0;
		
		var $v1=0;
		var $v2=0;
		var $v3=0;
		
		function acertos_tempo_medio($d1,$d2,$periodo=0)
			{
				/* Limites */
				$co = new consultora;
				
				$ndia1 = 0;
				$ndia2 = 99999;
				if ($periodo > 0)
					{
						$ndia1 = 7 * ($periodo-1);
						$ndia2 = 7 * ($periodo);
					}
				if ($periodo == 6)
					{
						$ndia1 = 35;
						$ndia2 = 999999;
					}
				$sql = "select * from kits_consignado
							where kh_acerto >= $d1 and kh_acerto <= $d2
							and kh_status = 'B'
						order by kh_acerto
				";
				$rlt = db_query($sql);
				$sx = '<table class="tabela00 lt1" width="98%" align="center">';
				$sx .= '<TR>
						<TH>Dt. Fornecimento
						<TH>Dt. Acerto
						<TH>Cliente
						<TH>Log.Forn.
						<TH>Log.Acerto
						<TH>Vlr.Venda
						<th>Dias
						';
				$id = 0;
				$tm = 0;
				while ($line = db_read($rlt))
					{
						$df=$line['kh_fornecimento'];
						$da=$line['kh_acerto'];						
						$dt = DiffDataDias($df,$da,'d');
						
						if (($dt >= $ndia1) and ($dt < $ndia2))
						{
						
						$id++;
						
						$tm = $tm + $dt;
						
						$cliente = $line['kh_cliente'];
						$sx .= '<TR>';
						$sx .= '<TD align="center" class="tabela01">';
							$sx .= stodbr($df);
						$sx .= '<TD align="center" class="tabela01">';
							$sx .= stodbr($da);
						$sx .= '<TD align="center" class="tabela01">'.
							$co->link_consultora($cliente);
						
						
						$sx .= '<TD align="center" class="tabela01">';
						
						$sx .= $line['kh_log'];
						$sx .= '<TD align="center" class="tabela01">';
						$sx .= $line['kh_log_acerto'];
						
						$sx .= '<TD align="right" class="tabela01">';
						$sx .= fmt($line['kh_vlr_vend'],2);
						
						$sx .= '<TD align="center" class="tabela01">';						
						$sx .= $dt;
						}
					}
				$sx .= '</table>';
				
				/* Media */
				if ($id > 0)
					{
						$med = fmt($tm/$id,1).' dias';
					} else {
						$med = '-';
					}
				$sa = '<table class="tabela01" width="400" align="center">
						<TR>
							<TH width="50%">Acertos
							<TH>Média de dias
						<TR>
							<TD align="center" class="lt5">'.$id.'
							<TD align="center" class="lt5">'.$med.'
						</table>
				';
				return($sa.$sx);
			}
		
		function acertos_resumo($d1,$d2)
			{
				$sql = "select 
							sum(kh_acerto) as kh_acerto,
							sum(kh_pago) as kh_pago,
							avg(kh_comissao) as kh_comissao,
							sum(kh_vlr_vend) as kh_vlr_vend,
							sum(kh_vlr_forn) as kh_vlr_forn 
						from (";				
				$sql .= "SELECT
							1 as kh_acerto,
							sum(kh_pago) as kh_pago,
							avg(kh_vlr_comissao) as kh_comissao,
							sum(kh_vlr_vend) as kh_vlr_vend,
							sum(kh_vlr_forn) as kh_vlr_forn 						 
						FROM kits_consignado 
							WHERE (kh_acerto >= ".$d1." AND kh_acerto <= ".$d2.")
							group by kh_cliente ";
				$sql .= ") as tabela  ";
				$rlt = db_query($sql);
				$sx .= '<table width="90%" align="center">';
				$sx .= '<TR>
						<TH>Acertos
						<TH>Vlr.Acerto
						<TH>Acerto médio
						<TH>Acima da média
						<TH>Abaixo da média
						<TH>Zerados';
				if ($line = db_read($rlt))
					{
						if ($line['kh_acerto'] > 0)
							{
								$acerto_medio = ($line['kh_pago'] / $line['kh_acerto']);
							} else {
								$acerto_medio = 0;
							}
						$sx .= '<TR>
								<TD align="center" class="tabela01 lt5">'.$line['kh_acerto'].'
								<TD align="center" class="tabela01 lt5">'.fmt($line['kh_pago'],2).'
								<TD align="center" class="tabela01 lt5">'.fmt($acerto_medio,2).'
								';
						$this->v1 = $line['kh_acerto'];
						$this->v2 = $line['kh_pago'];
					}
				/* Acima da Média */
				$sql = "select count(*) as total, sum(kh_pago) as kh_pago from ( ";
				$sql .= "SELECT
							1 as kh_acerto,
							sum(kh_pago) as kh_pago,
							avg(kh_vlr_comissao) as kh_comissao,
							sum(kh_vlr_vend) as kh_vlr_vend,
							sum(kh_vlr_forn) as kh_vlr_forn 						 
						FROM kits_consignado 
							WHERE (kh_acerto >= ".$d1." AND kh_acerto <= ".$d2.")
							group by kh_cliente ";
				$sql .= ") as tabela where kh_pago >= ".round($acerto_medio);
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$sx .= '<TD align="center" class="tabela01 lt5">';
				$sx .= fmt($line['kh_pago'],2);
				$sx .= ' ('.$line['total'].')';
				
				/* Abaixo da Média */
				$sql = "select count(*) as total, sum(kh_pago) as kh_pago from ( ";
				$sql .= "SELECT
							1 as kh_acerto,
							sum(kh_pago) as kh_pago,
							avg(kh_vlr_comissao) as kh_comissao,
							sum(kh_vlr_vend) as kh_vlr_vend,
							sum(kh_vlr_forn) as kh_vlr_forn 						 
						FROM kits_consignado 
							WHERE (kh_acerto >= ".$d1." AND kh_acerto <= ".$d2.")
							group by kh_cliente ";
				$sql .= ") as tabela where kh_pago < ".round($acerto_medio);
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$sx .= '<TD align="center" class="tabela01 lt5">';
				$sx .= fmt($line['kh_pago'],2);
				$sx .= ' ('.$line['total'].')';				
				
				/* Acertos Zerados */
				$sql = "select count(*) as total from ( ";
				$sql .= "SELECT
							1 as kh_acerto,
							sum(kh_pago) as kh_pago,
							avg(kh_vlr_comissao) as kh_comissao,
							sum(kh_vlr_vend) as kh_vlr_vend,
							sum(kh_vlr_forn) as kh_vlr_forn 						 
						FROM kits_consignado 
							WHERE (kh_acerto >= ".$d1." AND kh_acerto <= ".$d2.")
							group by kh_cliente ";
				$sql .= ") as tabela where kh_pago = 0";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$sx .= '<TD align="center" class="tabela01 lt5">';
				$sx .= $line['total'];
				$this->v3 = $line['total'];				
				/* Acima da Média */
				
				$sx .= '</table>';
				return($sx);			
			}		
		
		function acertos_detalhe($d1,$d2)
			{
				$sql = "SELECT kh_cliente, cl_nome, cl_clientep, kh_acerto, kh_pago, kh_log, kh_vlr_comissao, ";
				$sql .= "kh_vlr_vend, kh_vlr_forn ";
				$sql .= "  FROM kits_consignado ";
				$sql .= "LEFT JOIN clientes ON clientes.cl_cliente=kh_cliente ";
				$sql .= "WHERE (kh_acerto >= ".$d1." AND kh_acerto <= ".$d2.") ";
				$sql .= "	ORDER BY kh_acerto";	
				$rlt = db_query($sql);
				$sx = '<table class="tabela00" width="98%">';
				$sx .= '<TR>
							<TH>Nome
							<TH>Código
							<TH>Dt.Acerto
							<TH>Login
							<TH>Comissão
							<TH>Vlr.Vendido
							<TH>Vlr.Fornecido
							<TH>Vlr.Acerto
					';
				$tot = 0;
				$id = 0;
				while ($line = db_read($rlt))
					{
						$id++;
						$tot = $tot + $line['kh_pago'];
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= $line['cl_nome'];
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['kh_cliente'];
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= stodbr($line['kh_acerto']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['kh_log'];
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['kh_vlr_comissao'].'%';
						$sx .= '<TD class="tabela01" align="right">';
						$sx .= fmt($line['kh_vlr_vend'],2);
						$sx .= '<TD class="tabela01" align="right">';
						$sx .= fmt($line['kh_vlr_forn'],2);
						$sx .= '<TD class="tabela01" align="right">';
						$sx .= '<B>'.fmt($line['kh_pago'],2).'</B>';
												
					}
				if ($id > 0)
					{
					$sx .= '<TR><TD colspan=5 align="left"><I>Total de '.$id.' acertos ('.fmt($tot,2).')';
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function historico($c1,$c2,$data_acerto)
			{
				$sql = "insert into historico_".date("Ym")." ";
				$sql .= "(hi_cliente,hi_tipo,hi_data,hi_hora,hi_descricao) ";
				$sql .= " values ";
				$sql .= "('".$c1."','001','".date("Ymd")."','".
							date("H:i")."','Transferência de Kit ".$nome_loja.
							" de ".$c1.". Acerto de ".$c2." para ".$data_acerto."')";
				$rlt = db_query($sql);
				
			}
		
		function transfere_pecas($c1,$c2,$data_acerto)
			{
				$sql = "select * from produto_estoque ";
				$sql .= " where pe_cliente = '".$c1."' ";
				$sql .= " and pe_status = 'F' ";
				$rlt = db_query($sql);
				
				while ($line = db_read($rlt))
				{
					$sql = "insert into produto_log_".date("Ym");	
					$sql .= " (pl_ean13,pl_data,pl_hora,";
					$sql .= " pl_cliente, pl_status, pl_kit, ";
					$sql .= " pl_produto,pl_log )";
					$sql .= " values ";
					$sql .= "('".$line['pe_ean13']."','".date("Ymd")."','".date("H:i")."',";
					$sql .= "'".$c2."','O','',";
					$sql .= "'".$line['pe_produto']."','".$user_log."')";
					$xxx = db_query($sql);
		
					$sql = "update produto_estoque set pe_cliente = '".$c2."' ";
					$sql .= " where pe_cliente = '".$c1."' and pe_status = 'F' ";
					$sql .= " and id_pe = ".$line['id_pe'];
					$xxx = db_query($sql);
				}
				
				$sql = "update kits_consignado set kh_cliente = '".$c2."' ";
				$sql .= ", kh_previsao = ".brtos($data_acerto)." ";
				$sql .= " where kh_cliente = '".$c1."' ";
				$sql .= " and kh_status = 'A' ";
				$xxx = db_query($sql);

				//$sql = "update produto_estoque ";
				//$sql .= " set pe_cliente = '".$c2."' ";
				//$sql .= " where pe_cliente = '".$c1."' ";
				//$sql .= " and pe_status = 'T' ";
				//$xxx = db_query($sql);
			}
		
		function cliente_com_kit($cliente)
			{
				$this->kit_atrasado = 0;
				$sql = "select * from kits_consignado ";
					$sql .= " where kh_cliente = '".$cliente."' and kh_status = 'A'";
					$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->dt_acerto = $line['kh_previsao'];
						if (($line['kh_previsao']) < date("Ymd"))
							{
								$this->kit_atrasado = 1;
							}
						return(1); 
					}
				else 
					{
						$this->dt_acerto = '';
						return(0); 
					}			
			}
			
		function cp_troca_kit()
			{
				global $dd,$acao;
				$cp = array();
				//$dd[7]='';
				
				/*0*/array_push($cp,array('$H4','','',False,True,''));
				/*1*/array_push($cp,array('$A','','Troca de Títularidade do Kits ',False,True,''));
				if (strlen($dd[2]) > 0)
				{
					$com_kit = $this->cliente_com_kit($dd[2]);
					if (($com_kit == 1) and ($this->kit_atrasado == 0))
						{
							$dd[3] = stodbr($this->dt_acerto);
							/*2*/array_push($cp,array('$S7','','Código do cliente atual ',True,False,''));
							/*3*/array_push($cp,array('$D8','','Data do acerto',True,False,''));
							/*4*/array_push($cp,array('$S7','','Transferir para o código ',True,True,''));
							/*5*/array_push($cp,array('$D8','','Data do acerto',True,False,''));
							/*6*/array_push($cp,array('$B8','','Selecionar > ',False,True,''));
							$clie_nova = $dd[4];
							$erro = '';
							if ($this->cliente_com_kit($clie_nova)==1)
								{
									$erro .= '<center><font color=red >Cliente '.$dd[4].' já tem kit</font>';
								}
							if ($dd[2]==$clie_nova)
								{
									$erro .= '<center><font color=red >Cliente original é igual ao destino</font>';		
								}
							/* Verifica se existe cliente */
							$consultora = new consultora;
							$exist = $consultora->exists_cliente($clie_nova);
							
							if ($exist == 0)
								{
									$erro .= '<center><font color=red >Código ('.$dd[4].') da consultora é inválido</font>';
								}							
							/* Para se conter erro */
							if (strlen($erro) > 0)
								{
									/*7*/array_push($cp,array('$H8','','',True,True,''));
									/*8*/array_push($cp,array('$M','',$erro,False,True,''));
								}
						} else {
							$cliente = $dd[2];
							$erro = '<center>';
							if ($com_kit == 0)
								{
									$erro .= '<font color=red >Cliente '.$cliente.' sem KIT</font><BR>';
								}
							if ($this->kit_atrasado == 1)
								{
									$erro .= '<font color=red >O Kit está atrasado</font><BR>';
								}
							/*2*/array_push($cp,array('$S7','','Código da cliente atual ',True,True,''));
							/*3*/array_push($cp,array('$H8','','',True,True,''));
							/*4*/array_push($cp,array('$H8','','',True,True,''));
							/*5*/array_push($cp,array('$H8','','',True,True,''));
							/*6*/array_push($cp,array('$B8','','Substituir >>> ',False,True,''));
							/*7*/array_push($cp,array('$M','',$erro,False,True,''));
													
						}
				} else {
					/*2*/array_push($cp,array('$S7','','Código da cliente atual ',True,True,''));
					/*3*/array_push($cp,array('$H8','','',True,True,''));
					/*4*/array_push($cp,array('$H8','','',True,True,''));
					/*5*/array_push($cp,array('$H8','','',True,True,''));
					/*6*/array_push($cp,array('$B8','','Substituir >>>> ',False,True,''));
					
				}	
				return($cp);			
			}
	}
?>