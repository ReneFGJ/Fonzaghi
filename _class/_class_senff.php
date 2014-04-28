<?php
    /**
     * Senff - Cartï¿½o fidelidade
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.30
	 * @package telefonia
	 * @subpackage classe
    */
    
class senff
	{
	var $tabela = 'senff_cartoes';
	var $line = '';
	var $valor = 5000;
	
	function pontos_expirar_cliente($cliente)
		{
			$sql = "select * from senff_extrato 
					where ex_cliente = '".$cliente."'
					
					";
			$rlt = db_query($sql);

			$sld = 0;
			while ($line = db_read($rlt))
				{
					$valor = $line['ex_valor'];
					$sld = $sld + $valor;
				}
			$data = date("Ymd");
			$descricao = 'Extorno por inatividade';
			if ($sld > 0)
				{
				$sql = "insert into senff_extrato 
					(
					ex_data, ex_descricao, ex_valor,
					ex_cliente, ex_doc
					) values (
					$data,'$descricao',-$sld,
					'$cliente','EXTORN'
					)		
				";
				$rlt = db_query($sql);
				return(1);
				}
			return(0);
			
		}
	
	function pontos_expirar($dias=0)
		{
			global $perfil;
			$dia = DateAdd("d",((-1)*($dias)),date("Ymd"));
			$cli = new consultora;
			
			$sql = "select * from (
						select sum(ex_valor) as saldo, max(ex_data) as data, ex_cliente 
						from senff_extrato
						group by ex_cliente
						) as tabela 
						where data < ".$dia." and saldo > 0 
						ORDER BY data desc";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR>
						<TH>Data
						<TH>Saldo
						<TH>Cliente';
			if ($perfil->valid("#MAR#ADM"))
				{ $sx .= '<Th>ação'; }
			$id = 0;
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="senff_expirar_credito.php?dd0='.$line['ex_cliente'].'" target="_new">';
					$link .= 'extornar créditos</A>';
					$id++;
					$sx .= '<TR>';
					$sx .= '<TD align="center" class="tabela01">';
					$sx .= stodbr($line['data']);
					$sx .= '<TD align="center" class="tabela01">';
					$sx .= number_format($line['saldo'],2);
					$sx .= '<TD align="center" class="tabela01">';
					$sx .= $cli->link_consultora($line['ex_cliente']);
					
					$sx .= '<TD align="center" class="tabela01">';
					if ($perfil->valid("#MAR#ADM"))
						{
						$sx .= $link;
						}
				}
			$sx .= '<TR><TD colspan=5>Total de '.$id.' consultoras.';
			$sx .= '</table>';
			return($sx);
		}
	
	function extornar_valor($id)
		{
			global $user;
			$sql = "select * from sempre_fonzaghi_2012_saldo
					where sfs_doc = '".strzero($id,7)."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					echo 'Documento já extornado!';
					return(0);
				}					

			
			$sql = "select * from sempre_fonzaghi_2012_saldo
					where id_sfs = ".$id." ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{   
					$vlr = $line['sfs_credito']*(-1);
					$cliente = $line['sfs_cliente'];
					$log = $user->user_log;
					$data = date("Ymd");
					$hora = date("H:i:s");
					$hist = "Extorno de lancamento";
					$doc = strzero($id,7);
					$doc2 = substr('00'.$line['id_sfs'],-6);
					if ($vlr < 0)
						{
						$sql = "insert into sempre_fonzaghi_2012_saldo
							(sfs_data, sfs_hora, sfs_cliente,
							sfs_credito, sfs_debito, sfs_historico, sfs_log, 
							sfs_loja, sfs_doc, sfs_tipo, 
							sfs_data_lanca
							) values (
								$data,'$hora','$cliente',
							$vlr, 0,'$hist','$log',
							'','$doc','EXT',
							$data)
						";
						$rlt = db_query($sql);
						$sql2 = "delete from senff_extrato  where ex_cliente='$cliente' and ex_doc like '%".$doc2."'";
                        $rlt2 = db_query($sql2);
						
						} else {
							echo 'Valor inválido';
						}
				}
			return(1);
			
		}
	
	function extornar($cliente)
		{
			global $dd,$acao;
			
			if ($dd[2]=='DEL')
				{
					$this->extornar_valor($dd[1]);
				}
			$sql = "select * from sempre_fonzaghi_2012_saldo 
				where sfs_cliente = '$cliente' and sfs_credito <> 0
				order by sfs_data_lanca desc, id_sfs desc ";
			$rlt = db_query($sql);
			
			$sld = 0;
			$slc = 0;
			$sa = '<table class="tabela00" width="700" align="center">';
			while ($line = db_read($rlt))
				{
					//$sld = $sld + $line['sfs_debito'];
					$slc = $slc + $line['sfs_credito'];
					$sx .= '<TR>';
					$sx .= '<TD align="center" class="tabela01">';
					$sx .= stodbr($line['sfs_data']);
					$sx .= '<TD class="tabela01">';
					$sx .= $line['sfs_historico'];
					$sx .= '<TD align="right" class="tabela01">';
					$sx .= number_format($line['sfs_credito'],2,',','.');
					$sx .= '<TD align="right" class="tabela01">';
					$sx .= number_format($line['sfs_debito'],2,',','.');
					$sx .= '<TD class="tabela01">';
					$sx .= $line['sfs_log'];


					$sx .= '<TD align="center" class="tabela01">';
					$sx .= '<A HREF="'.page().'?dd0='.$dd[0].'&dd1='.$line['id_sfs'].'&dd2=DEL">';
					$sx .= 'extornar';
					$sx .= '</A>';
					//print_r($line);
				}
			$sx .= '</table>';
			
			$sx = $sa . '<TR><TH>Data<TH>Descrição<TH>Crédito<TH>Débito'
					. '<TR><TD colspan="5">Saldo: '.number_format($slc-$sld,2,',','.')
					. $sx;
			echo $sx;
		}
	
	function mostra_cartao($line)
		{
			$nrc = trim($line['card_numero']);
			if ((strlen($nrc) ==0) and (strlen($line['id_card']) > 0))
				{ $nrc = '<font color="red">não informado</font>'; }
			else {
				if (strlen($nrc) > 0) { $nrc = '['.$nrc.']';}	
				}
			
			return($nrc);			
		}
	
	function mostra_lotes_pagamentos($lote)
		{
			$sql = "select * from senff_carga
					left join clientes on cg_cliente = cl_cliente
					left join senff_cartoes on cg_cliente = card_cliente 
					where cg_doc = '$lote' 
					order by card_numero, cl_nome
					";
			$rlt = db_query($sql);
			$sx .= '<table width="98%" class="tabela00" align="center">';
			$sx .= '<TR><TH width="50%">nome
					<TH width="7%">cpf
					<TH width="7%">código
					<TH width="7%">ID do lote
					<TH width="10%">Valor
					<TH width="10%">Vencimento
					<TH width="10%">Núm cartão';
			$id = 0;
			$tot = 0;
			$sc = -1;
			while ($line = db_read($rlt))
				{
					/* Cabecalho */
					$idc = round($line['id_card']);
					if (($idc > 0) and ($sc == -1))
						{ $sx .= '<TR><TD colspan=5 class="lt4"><B>Consultoras com cartão</B>'; $sc = 0;}
					if (($idc == 0) and ($sc == 0))
						{ $sx .= '<TR><TD colspan=5 class="lt4"><B>Consultoras sem cartão</B>'; $sc = 1;}
					
					$tot = $tot + $line['cg_valor'];
					$id++;
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['cl_nome'];
					$sx .= '<TD class="tabela01"align="center">';
					$sx .= 'CPF'.$line['cl_cpf'];
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= 'F'.$line['cg_cliente'];
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $line['cg_doc'];
					$sx .= '<TD class="tabela01" align="right">';
					$sx .= number_format($line['cg_valor'],2,',','.');
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= stodbr($line['card_dt_emissao']);
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $this->mostra_cartao($line);
				}	
			$sx .= '<TR><TD cospan=3>Total de '.$id.' pagamentos, no valor '.number_format($tot,2,',','.');
			$sx .= '</table>';
			return($sx);		
		}
	
	function mostra_lotes()
		{
			$tot = 0;
			$id = 0;
			$sql = "select cg_data,sum(cg_valor) as valor, cg_doc from senff_carga group by cg_data, cg_doc ";
			$rlt = db_query($sql);
			$sx .= '<table width="700" class="tabela00" align="center">';
			$sx .= '<TR><TH width="33%">data<TH width="33%">ID do lote<TH width="33%">Valor';
			while ($line = db_read($rlt))
				{
					$id++;
					$tot = $tot + $line['valor'];
					$link = '<A HREF="'.page().'?dd1='.$line['cg_doc'].'">';
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= stodbr($line['cg_data']);
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link;
					$sx .= $line['cg_doc'];
					$sx .= '</A>';
					$sx .= '<TD class="tabela01" align="right">';
					$sx .= number_format($line['valor'],2,',','.');
				}
			$sx .= '<TR><TD cospan=3>Total de '.$id.' lotes, no valor '.number_format($tot,2,',','.');
			$sx .= '</table>';
			return($sx);
		}
	
	function consultoras_realizar_pagamento($mtx)
		{
			$sql = "
			select * from (
				select sum(ex_valor) as total, ex_cliente from senff_extrato
				group by ex_cliente
				) as tabela
				inner join clientes on ex_cliente = cl_cliente
				left join senff_cartoes on card_cliente = cl_cliente
				where total > ".round($this->valor)."
				order by total desc
			";
			$rlt = db_query($sql);
			$id = 0;
			while ($line = db_read($rlt))
				{
					$clie = trim($line['cl_cliente']);
					$fld = 'ddx'.$clie;
					$dxp = $mtx[$fld];
					if (strlen($dxp) > 0)
						{
							$id++;
							$vlr = $this->valida_no_cartao($clie);
							if ($vlr > 0)
								{
									$doc = 'LC'.date("ymd");
									if ($this->grava_carga($clie,$vlr,$doc)==1)
										{
										$this->debita_extrato($clie,5000,'Pagamento de Bonus no Cartao',$doc);
										}
								}
						}
				}
			echo 'Gerado '.$id.' pagamentos';
		}
	function debita_extrato($clie,$vlr,$hist,$doc)
		{
			$sql = "select * from senff_extrato
					where ex_cliente = '$clie' and ex_doc = '$doc'
			";
			$rlt = db_query($sql);
			if (!($line = db_read($rlt)))
				{
					$data = date("Ymd");
					$sql = "insert into senff_extrato 
							(ex_data, ex_descricao, ex_valor, 
							ex_cliente, ex_doc
							) values (
							$data,'$hist',-$vlr,
							'$clie','$doc'
							);
					";
					$rlt = db_query($sql);
					return(1);
				}
			return(0);
		}
	function grava_carga($clie,$vlr,$doc)
		{
			$sql = "select * from senff_carga
					where cg_cliente = '$clie' and cg_doc = '$doc'
			";
			$rlt = db_query($sql);
			if (!($line = db_read($rlt)))
				{
					$data = date("Ymd");
					$hist = 'Carga no cartão';
					$sql = "insert into senff_carga 
							(cg_data, cg_descricao, cg_valor, 
							cg_cliente, cg_doc
							) values (
							$data,'$hist',$vlr,
							'$clie','$doc'
							);
					";
					$rlt = db_query($sql);
					return(1);
				}
			return(0);
		}
	function valida_no_cartao($cliente)
		{
			$vlr = 50;
			
			$sql = "select sum(ex_valor) as saldo from senff_extrato
					where ex_cliente = '$cliente' and ex_valor > 0
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt)) { $credito = round($line['saldo']); }
			
			$sql = "select sum(ex_valor) as saldo from senff_extrato
					where ex_cliente = '$cliente' and ex_valor < 0
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt)) { $debito = round($line['saldo']); }
			
			if ($credito >= 25000) { $vlr = 100; }
			if ($credito >= 50000) { $vlr = 150; }
			
			return($vlr);			
		}
	function cartao()
		{
			
		}
	
	function historico()
		{
			
		}
	
	function le($id)
		{
			$sql = "select * from ".$this->tabela." where id_card=".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
					return(1);
				}
			return(0);
		}
	
	function row()
		{
		global $cdf,$cdm,$masc;
		
		$cdf = array('id_card','card_nome','card_cpf','card_dt_emissao');
		$cdm = array('ID','Nome','CPF','Emissão');
		$masc = array('','','','D','','','','','');
		return(True);
		}
	
	function export_senff($lista)
		{
			
		}
		
	function consultoras_ativas()
		{
			global $base_host, $base_port, $base_name ,$base_user, $base_pass, $base, $conn;
			$cons = array();

			$sql = "select kh_cliente from kits_consignado where kh_status='A' ";
			
			require("../db_fghi_206_modas.php");
			$rlt = db_query($sql);
			while ($line = db_read($rlt)) { array_push($cons,trim($line['kh_cliente'])); }
			
			require("../db_fghi_206_joias.php");
			$rlt = db_query($sql);
			while ($line = db_read($rlt)) { array_push($cons,trim($line['kh_cliente'])); }

			require("../db_fghi_206_sensual.php");
			$rlt = db_query($sql);
			while ($line = db_read($rlt)) { array_push($cons,trim($line['kh_cliente'])); }
			
			require("../db_fghi_206_oculos.php");
			$rlt = db_query($sql);
			while ($line = db_read($rlt)) { array_push($cons,trim($line['kh_cliente'])); }

			return($cons);
		}
	
	function consultoras_saldo($valor=5000)
		{
			global $base_host, $base_port, $base_name ,$base_user, $base_pass, $base, $conn;			
			
			$cons = $this->consultoras_ativas();
			
			require("../db_206_telemarket.php");
			
			$sql = "
			select * from (
				select sum(ex_valor) as total, ex_cliente from senff_extrato
				group by ex_cliente
				) as tabela
				inner join clientes on ex_cliente = cl_cliente
				left join senff_cartoes on card_cliente = cl_cliente
				where total > ".round($valor)."
				order by total desc
			";
			$rlt = db_query($sql);
			
			$js .= '';	
			
			$sx = '<table width="98%" class="tabela00" align="center">';
			$sx .= '<TR><TH width=5>mk<TH width="7%">Saldo
						<TH width="7%">Codigo<TH>Consultora
						<TH width="10%">Nr. Cartão';
			$id = 0;
			while ($line = db_read($rlt))
			{
				$consultora = trim($line['ex_cliente']);
				if (in_array($consultora,$cons))
					{
						$ativa = 1;
					} else {
						$ativa = 0;
					}
				$id++;
				$rd = '';
				if ($ativa == 1) 
					{
						$rd = '<input type="checkbox" id="ddx'.$consultora.'" name="ddx'.$consultora.'" '.$rd .' checked >';
						$font = '<font color="black">';
					} else {
						$rd = '';
						$font = '<font color="red">'; 
					}
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>';
				$sx .= $rd;
				$sx .= '<TD align="right" class="tabela01">';
				$sx .= number_format($line['total'],2,',','.');
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $line['ex_cliente'];
				$sx .= '<TD align="left" class="tabela01">';
				$sx .= $font.$line['cl_nome'].'</font>';
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $this->mostra_cartao($line);
			}
			$sx .= '<TR><TD colspan=5>Total de '.$id.' consultoras';
			$sx .= '</table>';
			
			$js = '
				<script>
				function marcaall(id)

				</script>
			';
			return($sx.$js);				
		}

		function lista_consultoras($dd1=1,$dd2=3)
		{
			global $base_host, $base_port, $base_name ,$base_user, $base_pass, $base, $conn, $http, $dd;	
			$tt_cons=0;
			$tt_trocas=0;
			$tt_saldo=0;
			$trocas=0;
			$saldo=0;
			$vx1='';
			$vx='';
			switch($dd1)
			{
				case '0':
					$vx1 .= "";
					break;
				case '1':
					$vx1 .= " and cl_status='A'";
					break;
				case '2':
					$vx1 .= " and cl_status='I'";
					break;
				default:
					$vx1 .= "";
					break;	
			}
			switch($dd2)
			{
				case '3':
					$vx2 .= "";
					break;
				case '4':
					$vx2 .= " having sum(ex_valor)>=5000 ";
					break;
				case '5':
					$vx2 .= " having sum(ex_valor)<5000 ";
					break;
				default:
					$vx2 .= "";
					break;	
			}
			
			require("../db_206_telemarket.php");				
			$sql = "select sum(ex_valor) as valor, ex_cliente 
					from senff_extrato 
					where ex_valor > 0 
					group by ex_cliente 
					".$vx2."
			";
			$rlt = db_query($sql);
			$tx = '<table align="center"><tr>
					<th class="tabelaTH" align="center">N.</th>
				   <th class="tabelaTH" align="center">Cliente</th>
				   <th class="tabelaTH" align="center">Nome</th>
				   <th class="tabelaTH" align="center">Saldo Pontos</th>
				   <th class="tabelaTH" align="center">Total Pontos</th>
				   <th class="tabelaTH" align="center">Trocas</th></tr>';
			$sqlx = '';
			
			while($line=db_read($rlt))
			{
				$cliente = trim($line['ex_cliente']);
				$vlr = round($line['valor']*100)/100;
				if (strlen($sqlx) > 0) { $sqlx .= 'union '; }
				$sqlx .= "select '$cliente' as cliente, $vlr as valor ".chr(13).chr(10);
			}
			$sqlx = 'select cl_status, cl_nome, valor, cliente from ('.$sqlx.') as tabela01 ';
			$sqlx .= ' inner join cadastro on cl_cliente = cliente '.$vx1;
			$sqlx .= ' order by valor desc';
			
			 /* Consulta cadastro */
			require("../db_cadastro.php");	
			$rlt = db_query($sqlx);
			while($line=db_read($rlt))
				{
					$status=$line['cl_status'];
					$nome=$line['cl_nome'];
					
					$tt_cons++;
					$trocas = intval(($line['valor']/5000));
					$saldo = $line['valor']-(5000*$trocas);	
						
					$tx .='<tr'.coluna().'>
							    <td class="tabela01" align="center">'.$tt_cons.'</td>
							    <td class="tabela01" align="center"><a href="'.$http.'/cons/cons.php?dd0='.$line['cliente'].'"  target="_blank">'.$line['cliente'].'</a></td>
								<td class="tabela01" align="left">'.substr($nome,0,25).'</td>
								<td class="tabela01" align="center">'.number_format($saldo, 2, ',', ' ').'</td>
								<td class="tabela01" align="center">'.number_format($line['valor'], 2, ',', ' ').'</td>
								<td class="tabela01" align="center">'.$trocas.'</td>
								</tr>';
								
								$tt_trocas=$trocas+$tt_trocas;
								$tt_pontos=$line['valor']+$tt_pontos;
				}
			
			$tx .= '</table>';			
			$sx .= '<center><table><tr><td align="left">Total de Consultoras: </td><td align="right">'.$tt_cons.'<td></tr>
									<tr><td align="left">Total de Trocas: </td><td align="right">'.number_format($tt_trocas, 2, ',', ' ').'</td></tr>
									<tr><td align="left">Total de Pontos: </td><td align="right">'.number_format($tt_pontos, 2, ',', ' ').'</td></tr>
							</table><br>';
			$sx .=$tx;
			return($sx);
		}
		

		function lista_motivos($dd1=0,$dd2=0,$dd3=0,$dd4=0,$dd5=0,$dd6=0,$dd7=0,$dd8=0,$dd9=0,$dd10=0,$dd11=0,$dd12=0,$dd13=0)
		{
			global $base_host, $base_port, $base_name ,$base_user, $base_pass, $base, $conn, $http;	
			$tt_cons=0;
			$tt_trocas=0;
			$tt_saldo=0;
			$trocas=0;
			$saldo=0;
			$vx='';
			if($dd1==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Acerto na data Joia%' ";}
			if($dd2==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Acerto na data Moda%' ";}
			if($dd3==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Acerto na data Ocul%' ";}
			if($dd4==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Acerto na data Sens%' ";}
			if($dd5==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Acerto na data UB%' ";}
			if($dd6==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Conclusao de Capaci%' ";}
			if($dd7==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Credito de indicaca%' ";}
			if($dd8==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Credito de pagament%' ";}
			if($dd9==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Evento Sensual%' ";}
			if($dd10==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Pagamento de Bonus%' ";}
			if($dd11==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Pontos de anivers%' ";}
			if($dd12==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like 'Transf de pontos pa%' ";}
			if($dd13==1){
				 if(strlen(trim($vx))!=0){$vx .= ' or ';}	
				 $vx.= " substring(ex_descricao from 0 for 20) like '%Transf de pontos da%' ";}
			if (strlen(trim($vx))!=0){$vx = ' where '.$vx;}
			
			require("../db_206_telemarket.php");				
			$sql = "select substring(ex_descricao from 0 for 20) as descr,
													ex_cliente as cliente,
													ex_valor as valor 
					from senff_extrato ".$vx."  
			";
			$rlt = db_query($sql);
			$tx = '<table align="center"><tr>
					<th class="tabelaTH" align="center">N.</th>
				   <th class="tabelaTH" align="center">Cliente</th>
				   <th class="tabelaTH" align="center">Nome</th>
				   <th class="tabelaTH" align="center">Saldo Pontos</th>
				   <th class="tabelaTH" align="center">Total Pontos</th>
				   <th class="tabelaTH" align="center">Trocas</th></tr>';
			while($line=db_read($rlt))
			{
				switch(trim($line['descr']))
				{
					case 'Acerto na data Joia':
						$ttjoias++;
						$ttjoias_vlr=$line['ex_valor']+$ttjoias_vlr;
						break;
					case 'Acerto na data Moda':
						$ttmodas++;
						$ttmodas_vlr=$line['ex_valor']+$ttmodas_vlr;
						break;
					case 'Acerto na data Ocul':
						$ttoculos++;
						$ttoculos_vlr=$line['ex_valor']+$ttoculos_vlr;
						break;
					case 'Acerto na data Sens':
						$ttsensual++;
						$ttsensual_vlr=$line['ex_valor']+$ttsensual_vlr;
						break;
					case 'Acerto na data UB':
						$ttub++;
						$ttub_vlr=$line['ex_valor']+$ttub_vlr;
						break;
					case 'Conclusao de Capaci':
						$ttcapac++;
						$ttcapac_vlr=$line['ex_valor']+$ttcapac_vlr;
						break;
					case 'Credito de indicaca':
						$ttindic++;
						$ttindic_vlr=$line['ex_valor']+$ttindic_vlr;
						break;
					case 'Credito de pagament':
						$ttpag++;
						$ttpag_vlr=$line['ex_valor']+$ttpag_vlr;
						break;
					case 'Evento Sensual':
						$ttevento_s++;
						$ttevento_s_vlr=$line['ex_valor']+$ttevento_s_vlr;
						break;
					case 'Pagamento de Bonus':
						$ttbonus++;
						$ttbonus_vlr=$line['ex_valor']+$ttbonus_vlr;
						break;
					case 'Pontos de anivers':
						$ttaniv++;
						$ttaniv_vlr=$line['ex_valor']+$ttaniv_vlr;
						break;
					case 'Transf de pontos pa':
						$tttfp++;
						$tttfp_vlr=$line['ex_valor']+$tttfp_vlr;
						break;
					case 'Transf de pontos da':
						$tttfd++;
						$tttfd_vlr=$line['ex_valor']+$tttfd_vlr;
						break;		
					default:
						$ttoutros++;
						$ttoutros_vlr=$line['ex_valor']+$ttoutros_vlr;
						break;
				}
				
				$cliente = trim($line['cliente']);
				$vlr = round($line['valor']*100)/100;
				if (strlen($sqlx) > 0) { $sqlx .= 'union '; }
				$sqlx .= "select '$cliente' as cliente, $vlr as valor ".chr(13).chr(10);
			}
			echo $sqlx;
			$sqlx = 'select cl_status, cl_nome, valor, cliente from ('.$sqlx.') as tabela01 ';
			$sqlx .= ' inner join cadastro on cl_cliente = cliente '.$vx;
			$sqlx .= ' order by valor desc';
			require("../db_cadastro.php");	
			
			$rlt = db_query($sqlx);
			while($line=db_read($rlt))
			{
				 $status=$line['cl_status'];
				 $nome=$line['cl_nome'];
				
				$tx .='<tr'.coluna().'>
						    <td class="tabela01" align="center"></td>
						    </tr>';
							
							
			}

			$tx .= '</table>';			
			$sx .= '<center><table>
									<tr><td align="left">Total acertos jóias		: </td>'.$ttjoias.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos modas		: </td>'.$ttmodas.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos óculos		: </td>'.$ttoculos.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos sensual		: </td>'.$ttsensual.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos UB			: </td>'.$ttub.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos capacitação	: </td>'.$ttcapac.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos indicação	: </td>'.$ttindic.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos pagamento	: </td>'.$ttpag.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos evento sensual: </td>'.$ttevento_s.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos bônus		: </td>'.$ttbonus.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos aniversário	: </td>'.$ttaniv.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos transf para	: </td>'.$tttfp.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos transf da	: </td>'.$tttfd.'<td align="right"><td></tr>
									<tr><td align="left">Total acertos outros		: </td>'.$ttoutros.'<td align="right"><td></tr>
									
							</table><br>';
			$sx .=$tx;
			return($sx);
		}
		
		function filtro_senff_motivos($dd1,$dd2,$dd3,$dd4,$dd5,$dd6,$dd7,$dd8,$dd9,$dd10,$dd11,$dd12,$dd13)
		{
			global $http;
			
			
			$sx = '<script>
					
					var acerto_joias='.$dd1.';
					var acerto_modas='.$dd2.';
					var acerto_oculos='.$dd3.';
					var acerto_sensual='.$dd4.';
					var acerto_ub='.$dd5.';
					var capacitacao='.$dd6.';
					var indicacao='.$dd7.';
					var pagamento='.$dd8.';
					var evento_sensual='.$dd9.';
					var bonus='.$dd10.';
					var aniversario='.$dd11.';
					var tf_para='.$dd12.';
					var tf_da='.$dd13.';
					
					
					var dd1 = document.getElementById("dd1");
					var dd2 = document.getElementById("dd2");
					var dd3 = document.getElementById("dd3");
					var dd4 = document.getElementById("dd4");
					var dd5 = document.getElementById("dd5");
					var dd6 = document.getElementById("dd6");
					var dd7 = document.getElementById("dd7");
					var dd8 = document.getElementById("dd8");
					var dd9 = document.getElementById("dd9");
					var dd10 = document.getElementById("dd10");
					var dd11 = document.getElementById("dd11");
					var dd12 = document.getElementById("dd12");
					var dd13 = document.getElementById("dd13");
					
					
					function acerto_joias()
					{
						if(acerto_joias!=0){
							acerto_joias=0;
							document.getElementById("acerto_joiasA").style.display="none";
							document.getElementById("acerto_joiasB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							acerto_joias=1;
							document.getElementById("acerto_joiasB").style.display="none";
							document.getElementById("acerto_joiasA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function acerto_modas()
					{
						if(acerto_modas!=0){
							acerto_modas=0;
							document.getElementById("acerto_modasA").style.display="none";
							document.getElementById("acerto_modasB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							acerto_modas=1;
							document.getElementById("acerto_modasB").style.display="none";
							document.getElementById("acerto_modasA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function acerto_oculos()
					{
						if(acerto_oculos!=0){
							acerto_oculos=0;
							document.getElementById("acerto_oculosA").style.display="none";
							document.getElementById("acerto_oculosB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							acerto_oculos=1;
							document.getElementById("acerto_oculosB").style.display="none";
							document.getElementById("acerto_oculosA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function acerto_sensual()
					{
						if(acerto_sensual!=0){
							acerto_sensual=0;
							document.getElementById("acerto_sensualA").style.display="none";
							document.getElementById("acerto_sensualB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							acerto_sensual=1;
							document.getElementById("acerto_sensualB").style.display="none";
							document.getElementById("acerto_sensualA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function acerto_ub()
					{
						if(acerto_ub!=0){
							acerto_ub=0;
							document.getElementById("acerto_ubA").style.display="none";
							document.getElementById("acerto_ubB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							acerto_ub=1;
							document.getElementById("acerto_ubB").style.display="none";
							document.getElementById("acerto_ubA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function capacitacao()
					{
						if(capacitacao!=0){
							capacitacao=0;
							document.getElementById("capacitacaoA").style.display="none";
							document.getElementById("capacitacaoB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							capacitacao=1;
							document.getElementById("capacitacaoB").style.display="none";
							document.getElementById("capacitacaoA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function indicacao()
					{
						if(indicacao!=0){
							indicacao=0;
							document.getElementById("indicacaoA").style.display="none";
							document.getElementById("indicacaoB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							indicacao=1;
							document.getElementById("indicacaoB").style.display="none";
							document.getElementById("indicacaoA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function pagamento()
					{
						if(pagamento!=0){
							pagamento=0;
							document.getElementById("pagamentoA").style.display="none";
							document.getElementById("pagamentoB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							pagamento=1;
							document.getElementById("pagamentoB").style.display="none";
							document.getElementById("pagamentoA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function evento_sensual()
					{
						if(evento_sensual!=0){
							evento_sensual=0;
							document.getElementById("evento_sensualA").style.display="none";
							document.getElementById("evento_sensualB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							evento_sensual=1;
							document.getElementById("evento_sensualB").style.display="none";
							document.getElementById("evento_sensualA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function bonus()
					{
						if(bonus!=0){
							bonus=0;
							document.getElementById("bonusA").style.display="none";
							document.getElementById("bonusB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							bonus=1;
							document.getElementById("bonusB").style.display="none";
							document.getElementById("bonusA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function aniversario()
					{
						if(aniversario!=0){
							aniversario=0;
							document.getElementById("aniversarioA").style.display="none";
							document.getElementById("aniversarioB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							aniversario=1;
							document.getElementById("aniversarioB").style.display="none";
							document.getElementById("aniversarioA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function tf_para()
					{
						if(tf_para!=0){
							tf_para=0;
							document.getElementById("tf_paraA").style.display="none";
							document.getElementById("tf_paraB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							tf_para=1;
							document.getElementById("tf_paraB").style.display="none";
							document.getElementById("tf_paraA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					function tf_da()
					{
						if(tf_da!=0){
							tf_da=0;
							document.getElementById("tf_daA").style.display="none";
							document.getElementById("tf_daB").style.display="inline";
							document.getElementById("dd3").value="1";
							
						}else{
							tf_da=1;
							document.getElementById("tf_daB").style.display="none";
							document.getElementById("tf_daA").style.display="inline";
							document.getElementById("dd3").value="0";
						}
					}
					</script>

					<center>
					<div id="acerto_joiasA" style="display:inline"><img src=../img/joia-a.png onclick="acerto_joias()" height="50" width="120"></div>
					<div id="acerto_joiasB" style="display:none"><img src=../img/joia-c.png onclick="acerto_joias()" height="50" width="120"></div>
					<div id="acerto_modasA" style="display:inline"><img src=../img/modas-a.png onclick="acerto_modas()" height="50" width="120"></div>
					<div id="acerto_modasB" style="display:none"><img src=../img/modas-c.png onclick="acerto_modas()" height="50" width="120"></div>
					<div id="acerto_oculosA" style="display:inline;"><img src=../img/oculos-a.png onclick="acerto_oculos()" height="50" width="120"></div>
					<div id="acerto_oculosB" style="display:none; "><img src=../img/oculos-c.png onclick="acerto_oculos()" height="50" width="120"></div>
					<div id="acerto_sensualA" style="display:inline"><img src=../img/sensual-a.png onclick="acerto_sensual()" height="50" width="120"></div>
					<div id="acerto_sensualB" style="display:none"><img src=../img/sensual-c.png onclick="acerto_sensual()" height="50" width="120"></div>
					<div id="acerto_ubA" style="display:inline"><img src=../img/ub-a.png onclick="acerto_ub()" height="50" width="120"></div>
					<div id="acerto_ubB" style="display:none"><img src=../img/ub-c.png onclick="acerto_ub()" height="50" width="120"></div>
					<div id="capacitacaoA" style="display:inline;"><img src=../img/conclusao-a.png onclick="capacitacao()" height="50" width="120"></div>
					<div id="capacitacaoB" style="display:none; "><img src=../img/conclusao-c.png onclick="capacitacao()" height="50" width="120"></div>
					<div id="indicacaoA" style="display:inline"><img src=../img/ind-a.png onclick="indicacao()" height="50" width="120"></div>
					<div id="indicacaoB" style="display:none"><img src=../img/ind-c.png onclick="indicacao()" height="50" width="120"></div>
					<div id="pagamentoA" style="display:inline"><img src=../img/pag-a.png onclick="pagamento()" height="50" width="120"></div>
					<div id="pagamentoB" style="display:none"><img src=../img/pag-c.png onclick="pagamento()" height="50" width="120"></div>
					<div id="evento_sensualA" style="display:inline;"><img src=../img/ev-a.png onclick="evento_sensual()" height="50" width="120"></div>
					<div id="evento_sensualB" style="display:none; "><img src=../img/ev-c.png onclick="evento_sensual()" height="50" width="120"></div>
					<div id="bonusA" style="display:inline"><img src=../img/bon-a.png onclick="bonus()" height="50" width="120"></div>
					<div id="bonusB" style="display:none"><img src=../img/bon-c.png onclick="bonus()" height="50" width="120"></div>
					<div id="aniversarioA" style="display:inline"><img src=../img/niver-a.png onclick="aniversario()" height="50" width="120"></div>
					<div id="aniversarioB" style="display:none"><img src=../img/niver-c.png onclick="aniversario()" height="50" width="120"></div>
					<div id="tf_paraA" style="display:inline;"><img src=../img/tfp-a.png onclick="tf_para()" height="50" width="120"></div>
					<div id="tf_paraB" style="display:none; "><img src=../img/tfp-c.png onclick="tf_para()" height="50" width="120"></div>
					<div id="tf_daA" style="display:inline;"><img src=../img/tfd-a.png onclick="tf_da()" height="50" width="120"></div>
					<div id="tf_daB" style="display:none; "><img src=../img/tfd-c.png onclick="tf_da()" height="50" width="120"></div>
					
					<form action="'.$http.'senff/senff_lista_metas.php">
					<input type="hidden" id="dd1" name="dd1" value="'.$dd1.'" />
					<input type="hidden" id="dd2" name="dd2" value="'.$dd2.'" />
					<input type="hidden" id="dd3" name="dd3" value="'.$dd3.'" />
					<input type="hidden" id="dd4" name="dd4" value="'.$dd4.'" />
					<input type="hidden" id="dd5" name="dd5" value="'.$dd5.'" />
					<input type="hidden" id="dd6" name="dd6" value="'.$dd6.'" />
					<input type="hidden" id="dd7" name="dd7" value="'.$dd7.'" />
					<input type="hidden" id="dd8" name="dd8" value="'.$dd8.'" />
					<input type="hidden" id="dd9" name="dd9" value="'.$dd9.'" />
					<input type="hidden" id="dd10" name="dd10" value="'.$dd10.'" />
					<input type="hidden" id="dd11" name="dd11 value="'.$dd11.'" />
					<input type="hidden" id="dd12" name="dd12" value="'.$dd12.'" />
					<input type="hidden" id="dd13" name="dd13" value="'.$dd13.'" />
					<input type="image" src=../img/lupa_cinza.png name="image" height="42" width="42">
					</form>
					
			';
			
			if($dd1==1){
			$sx .='<script>
							acerto_joias=0;
							document.getElementById("acerto_joiasA").style.display="none";
							document.getElementById("acerto_joiasB").style.display="inline";
							document.getElementById("dd1").value="1";
					</script>';	
			}
			if($dd2==1){
			$sx .='<script>
							acerto_modas=0;
							document.getElementById("acerto_modasA").style.display="none";
							document.getElementById("acerto_modasB").style.display="inline";
							document.getElementById("dd2").value="1";
					</script>';	
			}
			if($dd3==1){
			$sx .='<script>
							acerto_oculos=0;
							document.getElementById("acerto_oculosA").style.display="none";
							document.getElementById("acerto_oculosB").style.display="inline";
							document.getElementById("dd3").value="1";
							
					</script>';
			}
			if($dd4==1){
			$sx .='<script>
							acerto_sensual=0;
							document.getElementById("acerto_sensualA").style.display="none";
							document.getElementById("acerto_sensualB").style.display="inline";
							document.getElementById("dd4").value="1";
					</script>';	
			}
			if($dd5==1){
			$sx .='<script>
							acerto_ub=0;
							document.getElementById("acerto_ubA").style.display="none";
							document.getElementById("acerto_ubB").style.display="inline";
							document.getElementById("dd5").value="1";
					</script>';	
			}
			if($dd6==1){
			$sx .='<script>
							capacitacao=0;
							document.getElementById("capacitacaoA").style.display="none";
							document.getElementById("capacitacaoB").style.display="inline";
							document.getElementById("dd6").value="1";
							
					</script>';
			}
			if($dd7==1){
			$sx .='<script>
							indicacao=0;
							document.getElementById("indicacaoA").style.display="none";
							document.getElementById("indicacaoB").style.display="inline";
							document.getElementById("dd7").value="1";
					</script>';	
			}
			if($dd8==1){
			$sx .='<script>
							pagamento=0;
							document.getElementById("pagamentoA").style.display="none";
							document.getElementById("pagamentoB").style.display="inline";
							document.getElementById("dd8").value="1";
					</script>';	
			}
			if($dd9==1){
			$sx .='<script>
							evento_sensual=0;
							document.getElementById("evento_sensualA").style.display="none";
							document.getElementById("evento_sensualB").style.display="inline";
							document.getElementById("dd9").value="1";
							
					</script>';
			}
			if($dd10==1){
			$sx .='<script>
							bonus=0;
							document.getElementById("bonusA").style.display="none";
							document.getElementById("bonusB").style.display="inline";
							document.getElementById("dd10").value="1";
					</script>';	
			}
			if($dd11==1){
			$sx .='<script>
							aniversario=0;
							document.getElementById("aniversarioA").style.display="none";
							document.getElementById("aniversarioB").style.display="inline";
							document.getElementById("dd11").value="1";
					</script>';	
			}
			if($dd12==1){
			$sx .='<script>
							tf_para=0;
							document.getElementById("tf_paraA").style.display="none";
							document.getElementById("tf_paraB").style.display="inline";
							document.getElementById("dd12").value="1";
							
					</script>';
			}
			if($dd13==1){
			$sx .='<script>
							tf_da=0;
							document.getElementById("tf_daA").style.display="none";
							document.getElementById("tf_daB").style.display="inline";
							document.getElementById("dd13").value="1";
							
					</script>';
			}
			
			
			
			return($sx);
		}
		
		
	}
?>
