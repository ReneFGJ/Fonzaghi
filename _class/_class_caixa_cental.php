<?php
    /**
     * Financeiro - Caixa Central
     * @author Rene F. Gabriel Junior <renefgj@gmail.com
     * @copyright Copyright (c) 2014 - sisDOC.com.br
     * @access public
     * @version v.0.14.01
     * @package Classe
     * @subpackage Financeiro
    */
class caixa_central
	{
	var $tabela_contas_pagar = 'contas_pagar';
	var $tabela_contas_receber = 'contas_receber';
	var $tabela_caixa = '';
	var $tabela_extrato_banco = 'banco_extrato';
	
	var $pagamentos;
	var $recebimentos;
	
	function saldo_receita($d1,$d2,$nohd=0,$juridico=0)
		{
			$sql = "";
			$i1 = 1; $i2 = 5;
			if ($juridico==1)
				{
					$i1 = 9;
					$i2 = 9;
				}
			
			for ($r=$i1;$r <=$i2;$r++)
				{
					$file = "caixa_".substr($d1,0,6)."_".strzero($r,2);
					if (strlen($sql) > 0) { $sql .= " union "; }
					$sql .= "select cx_tipo, sum(cx_valor) as valor, 
						count(*) as total 
						from $file 
						where cx_tipo <> '' 
						group by cx_tipo";
				}
			$sql = "select cx_tipo, sum(valor) as valor,
						sum(total) as total from (
						".$sql."
						) as tabela group by cx_tipo
					";
			$rlt = db_query($sql);
			$din = 0;
			$bco = 0;
			$car = 0;
			$out = 0;
			
			while ($line = db_read($rlt))
				{
					$tp = trim($line['cx_tipo']);
					$valor = $line['valor'];
					switch ($tp)
						{
						case 'DIN':
							$din = $din + $valor;
							break;
						case 'TOR':
							$din = $din - $valor;
							break;							
						case 'RED':
							$car = $car + $valor;
							break;	
						case 'VIS':
							$car = $car + $valor;
							break;													
						case 'HIP':
							$car = $car + $valor;
							break;													
						case 'MAS':
							$car = $car + $valor;
							break;
						case 'HIP':
							$car = $car + $valor;
							break;
						case 'CRD':
							$car = $car + $valor;
							break;
						case 'ELC':
							$car = $car + $valor;
							break;															
																		
						case 'DEP':
							$bco = $bco + $valor;
							break;
						case 'CHQ':
							$bco = $bco + $valor;
							break;																				
						}
				}
			$tot = $out+$bco+$car+$din;				
			$rs = array($din,$car,$bco,$out,$tot);
			$this->resumo_caixa = $rs;
			if ($nohd==0)
				{
				$sx .= '<table class="tabela00" width="98%" align="center">';
				$sx .= '<TR>
						<TH>Período
						<TH>Dinheiro
						<TH>Cheque/Dep.
						<TH>Cartões
						<TH>Outros
						<TH>Total';
				}
			$sx .= '<TR>
					<TD align="center" class="tabela01">'.substr($d1,4,2).'/'.substr($d1,0,4).'
					<TD class="tabela01" align="center">'.fmt($din,2).'
					<TD class="tabela01" align="center">'.fmt($bco,2).'
					<TD class="tabela01" align="center">'.fmt($car,2).'
					<TD class="tabela01" align="center">'.fmt($out,2).'
					<TD class="tabela01" align="right"><B>'.fmt($tot,2).'</B>';
			if ($nohd==2)
				{
				$sx .= '</table>';
				}
			return($sx);
		}
	
	function depositos_lista($data)
		{
			$sql = "select * from banco_extrato
				inner join banco on id_bco = ext_conta
						where ext_data = $data 
						and ext_auto = 'N'
				";
			$rlt = db_query($sql);
			$id = 0;
			$sx = '<table width="100%" class="tabela00">';
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= '<TR valign="top">';
					$sx .= '<TD class="tabela01 lt1">';
					$sx .= '<B>'.trim($line['bco_descricao']).'</b>';
					$sx .= '<BR><font class="lt0">'.trim($line['ext_historico']).'</font>';
					$sx .= '<TD class="tabela01" align="right">';
					$sx .= fmt($line['ext_valor'],2);
					//print_r($line);
				}
			$sx .= '</table>';
			if ($id == 0) { $sx = ''; }
			return($sx);
		}
	
	function updatex()
		{
			$sql = "select * from ".$this->tabela_contas_pagar."
					where cr_historico_asc = ''
			";
			$rlt = db_query($sql);
			$sql = "";
			while ($line = db_read($rlt))
				{
					$sql .= "update ".$this->tabela_contas_pagar." 
							set cr_historico_asc = '".UpperCaseSQL(trim($line['cr_historico']))."' 
							where id_cr = ".$line['id_cr'].';'.chr(13).chr(10);					
				}
			if (strlen($sql) > 0) { $rlt = db_query($sql); }
			return(1);
		}
	
	function cp_busca()
		{
			global $dd,$acao;
			if (strlen($dd[1]) == 0) { $dd[1] = stodbr(DateAdd("m",-6,date("Ymd"))); }
			if (strlen($dd[2]) == 0) { $dd[2] = stodbr(DateAdd("m",6,date("Ymd"))); }
			if (strlen($dd[7]) == 0) { $dd[7] = '0.00'; }
			if (strlen($dd[8]) == 0) { $dd[8] = '9999999.99'; }
			$dd[0] = '';
			$cp = array();
			array_push($cp,array('$H8','','id',False,True,''));
			array_push($cp,array('$d8','','Data inicial',False,True,''));
			array_push($cp,array('$d9','','Data final',False,True,''));
			array_push($cp,array('$S30','','Histótico / tipo',False,True,''));
			array_push($cp,array('$S15','','Pedido',False,True,''));
			array_push($cp,array('$S15','','Documento',False,True,''));
			array_push($cp,array('$O Z:Abertos/Quitados&T:Todos&A:Abertos&B:Quitados','','<I>Status</I>',False,True,''));
			array_push($cp,array('$N15','','Valor de',False,True,''));
			array_push($cp,array('$N15','','Até',False,True,''));
			return($cp);
		}
	
	function botao_lancamento_manual()
		{
			$sx = '
			<BR>
			<input type="button" 
				value="lançamento manual"
				class="botao-geral"
				onclick="newxy2(\'caixa_central_manual.php\',600,600);"
				>
			<BR><BR><BR>			
			';
			return($sx);
		}
	
	function encerrante_caixa($data)
		{
			global $user;
			if ($data > date("Ymd")) { return(0); }
			$tdata = xstod($data);
			$valor = 0;
			$sql = "select * from caixa_".date("Ym",$tdata)."_99
						where cx_tipo = 'FES' and cx_venc = $data
						order by cx_venc desc
						";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$valor = $line['cx_valor'];
				} else {
					$hora = '19:00';
					$valor = 0;
					$ip = '10.1.1.256';
					$sql = "insert into caixa_".date("Ym")."_99 
						(
						cx_data, cx_hora, cx_tipo,
						cx_descricao, cx_valor, cx_log,
						cx_terminal, cx_cliente, cx_nome,
						cx_venc, cx_doc, cx_parcela,
						cx_status, cx_lote, cx_chq_banco,
						cx_proc
						) values (
						$data,'$hora','FES',
						'Encerramento de Caixa:".$user->user_log."',$valor,0,
						'$ip','','Encerramento de caixa',
						$data,'','UNI',
						'A','','',
						1
						)";
					$rlt = db_query($sql);
				}
			return($valor);			
		}
	
	function recupera_encerrante_caixa_anterior($data)
		{
			$sql = "select * from caixa_".date("Ym")."_99
						where cx_tipo = 'FES' and cx_venc < $data
						order by cx_venc desc
						";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$valor = $line['cx_valor'];
					return($valor);
					exit;
				} else {
					$data = xstod($data)-15*24*60*60;
					$sql = "select * from caixa_".date("Ym",$data)."_99
							where cx_tipo = 'FES' 
							order by cx_venc desc
							";
					echo $sql;
					$rlt = db_query($sql);
					$line = db_read($rlt);
					$valor = $line['cx_valor'];
					return($valor);
					exit;					
				}
		}
	
	function lanca_abertura_caixa($data)
		{
			global $ip, $user;
			
			if ($data == date("Ymd"))	
				{
				$sql = "select * from caixa_".date("Ym")."_99
						where cx_tipo = 'ABR' and cx_venc = $data";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{

					} else {
						$hora = date("H:i");
						$venc = $data;
						$valor = $this->recupera_encerrante_caixa_anterior($data);
						/* Abre caixa */
						$sql = "insert into caixa_".date("Ym")."_99 
							(
							cx_data, cx_hora, cx_tipo,
							cx_descricao, cx_valor, cx_log,
							cx_terminal, cx_cliente, cx_nome,
							cx_venc, cx_doc, cx_parcela,
							cx_status, cx_lote, cx_chq_banco,
							cx_proc
							) values (
							$data,'$hora','ABR',
							'Abertura de Caixa:".$user->user_log."',$valor,0,
							'$ip','','Abertura de caixa automático',
							$venc,'','UNI',
							'A','','',
							1
							)";
						$rlt = db_query($sql);
					}
				}
			
		}
	
	function calendario_mini($data,$tipo='M')
		{
			$cr_vlr = array();
			$cp_vlr = array();
			$vlr = array();
			$vlr = array();
			$vlr = array();
			for ($k=0;$k < 35;$k++) { array_push($cr_vlr,0);array_push($cp_vlr,0); }
			//$sql="CREATE TABLE contas_pagar (  id_cr serial NOT NULL,  cr_cliente char(7),  cr_valor float8,  cr_venc int8,  cr_tipo char(1),  cr_historico char(80),  cr_pedido char(10),  cr_previsao int2,  cr_parcela char(8),  cr_dt_quitacao int8,  cr_status char(1),  cr_img char(15),  cr_doc char(20),  cr_lastupdate int8,  cr_data int8,  cr_conta char(5),  cr_empresa char(3),  cr_valor_original float8,  cr_cc char(7)) ";
			//$rlt = db_query($sql);

			////////////////////////////////// contas a pagar
			$sql = "select sum(cr_valor) as valor,cr_venc ";
			$sql .= ", ct_dc ";
			$sql .= " from ".$this->tabela_contas_pagar;
			$sql .= " left join contas_tipo on cr_conta = ct_codigo ";
			$sql .= " where cr_status <> 'X' and (cr_venc >= ".substr($data,0,6)."01 and cr_venc <= ".substr($data,0,6)."31) ";
			$sql .= " group by cr_venc ";
			$sql .= ",ct_dc";

			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
				$ddia = intval(substr($line['cr_venc'],6,2));
				$tpx = trim($line["ct_dc"]);
				if (strlen($tpx) == 0) { $tpx = "D"; }
				if ($tpx == "C")
					{ $cr_vlr[$ddia] = $cr_vlr[$ddia] + $line['valor']; }
				else
					{ $cp_vlr[$ddia] = $cp_vlr[$ddia]+ $line['valor']; }
				}

			/////////////////////////////////////////////////////////////q Contas a Recerbe
			$sql = "select sum(cr_valor) as valor,cr_venc,ct_dc 
					from  ".$this->tabela_contas_receber;
			$sql .= " left join contas_tipo on cr_conta = ct_codigo ";
			$sql .= " where cr_status <> 'X' and (cr_venc >= ".substr($data,0,6)."01 and cr_venc <= ".substr($data,0,6)."31) ";
			$sql .= " group by cr_venc, ct_dc  ";
			$sql .= " order by cr_venc ";

			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
				$ddia = intval(substr($line['cr_venc'],6,2));
				$tpx = trim($line["ct_dc"]);
				if (strlen($tpx) == 0) { $tpx = "C"; }
			
				if ($tpx =="C")
					{ $cr_vlr[$ddia] = $cr_vlr[$ddia] + $line['valor']; }
				else
					{ $cp_vlr[$ddia] = $cp_vlr[$ddia]+ $line['valor']; }	
				}
			if ($tipo == 'N')
			{
				$sx = '
				<TABLE width="150" align="center" border=1>
				<TR><TD bgcolor="#c0c0c0" colspan="10"class="lt1" align="center">
					'.nomemes(intval(substr($data,4,2))).'/'.substr($data,0,4).'</TD></TR>
				<TR align="center" bgcolor="#000000" align="center" class="lt0">
					<TD width="14%"><font color="#ffffff">Dom.</TD>
					<TD width="14%"><font color="#ffffff">Seg.</TD>
					<TD width="14%"><font color="#ffffff">Ter.</TD>
					<TD width="14%"><font color="#ffffff">Qua.</TD>
					<TD width="14%"><font color="#ffffff">Qui.</TD>
					<TD width="14%"><font color="#ffffff">Sex.</TD>
					<TD width="14%"><font color="#ffffff">Sab.</TD>
				</TR>
				<TR>';
			} else {
				$sx = '
				<TABLE width="120" align="center" border=0 class="tabela00 lt0" cellpadding=0 cellspacing=0 >
				<TR><TD bgcolor="#c0c0c0" colspan="10"class="lt1" align="center">
					'.nomemes(intval(substr($data,4,2))).'/'.substr($data,0,4).'</TD></TR>
				<TR align="center" bgcolor="#000000" align="center" class="lt0">
					<TD width="14%"><font color="#ffffff">D</TD>
					<TD width="14%"><font color="#ffffff">S</TD>
					<TD width="14%"><font color="#ffffff">T</TD>
					<TD width="14%"><font color="#ffffff">Q</TD>
					<TD width="14%"><font color="#ffffff">Q</TD>
					<TD width="14%"><font color="#ffffff">S</TD>
					<TD width="14%"><font color="#ffffff">S</TD>
				</TR>
				<TR>';
			}
			$dd1=substr($data,0,6).'01';
			$dd2=substr($data,0,6).'01';
			$dd3=date("w",xstod($dd1));

			$tot1=0;
			$tot2=0;
			for ($k = 0; $k < $dd3; $k++) { $sx .= '<TD>&nbsp;</TD>'; }
			
			while (substr($dd1,0,6) == substr($dd2,0,6))
				{
				$lk1 = '<A HREF="'.page().'?dd1='.$dd2.'">';
				$ndia = intval(substr($dd2,6,2));
				$mst_vlr = '';

				if ($vlr[$ndia] > 0) { $mst_vlr = fmt($cr_vlr[$ndia],2); }
				if ((date('w',xstod($dd2)) == 0 and ($ndia > 1)))
					{ $sx .= '<TR align="center">'; }

				$sx .= '<TD align="center">';
				////////////////////
				$tot1 = $tot1 + $cp_vlr[$ndia];
				$tot2 = $tot2 + $cr_vlr[$ndia];
				////////////////////
				$msk_v1 = fmt($cp_vlr[$ndia],2); 
				if ($msk_v1 == '0.00') 
					{ $msk_v1 = '-'; } 
				else 
					{ $msk_v1 = '<font class=lt2><font color=blue ><B>'.$msk_v1; }
				
				$msk_v2 = fmt($cr_vlr[$ndia],2); 
				if ($msk_v2 == '0,00') 
					{ $msk_v2 = '-'; } 
				else 
					{ $msk_v2 = '<font class=lt2><font color=orange ><B>'.$msk_v2; }
				if ($tipo == 'N')
					{
					$sx .= '<font class="lt0">'.stodbr($dd2).'</font><BR>';
					$sx .= $lk1;
					$sx .= $msk_v1;
					$sx .= '<BR>';
					$sx .= $lk2;
					$sx .= $msk_v2;					
					} else {
							
						$sx .= '<font class="lt1">'.$lk1.substr($dd2,6,2).'</A></font><BR>';
					} 
				$dd2 = DateAdd('d',1,$dd2);
			}
			if ($tipo == 'N')
				{
				$sx .= '
					<TR><TD colspan="10" class="lt1"><B>Total a pagar <font color=blue><?=number_format($tot1,2)?></font>
					, total a receber <font color=orange ><?=number_format($tot2,2)?>, <font color=gray > saldo do mês '.fmt($tot2-$tot1,2).'</font></TD></TR>';
				}	
			$sx .= '</TABLE>';
			return($sx);
		}
	
	function contas_cabecalho($data,$saldo=0)
		{
			$dia = weekday(xstod($data));
			$nome_mes = intval(substr($data,6,2));
			if ($saldo > 0) { $saldo = number_format($saldo,2); }
			$sx = '
			<TABLE cellpadding="2" cellspacing="0" border="1" width="99%" align="center">
			<TR valign="top" bgcolor="#e2e2e2">
			<TD align="center" width="100">
			<font class=lt1><B>'.stodbr($data).'</B></font><BR>
			<font class=lt2><B>'.nomemes($nome_mes).'</B></font><BR>
			<font class=lt2><B>'.nomedia($dia).'</B></font>
			<TD align="center"><font class=lt4>Acumulado do dia</font><P><center>
			<font class="lt5">'.fmt($this->pagamentos,2).'</P></font></TD>
			<TD>'.$this->botoes_navegacao($data,'','caixa_central_pagar_edit.php','caixa_central_busca.php','caixa_central_calendario.php').'
			<TD width="120">'.$this->calendario_mini($data).'
			</table>';
			return($sx);
		}
	function botoes_navegacao($data,$pg='',$pg_edit='',$pg_search='',$pg_cal='')
		{
			$dx01 = DateAdd('m',-1,$data);
			$dx02 = DateAdd('d',-7,$data);
			$dx03 = DateAdd('d',-1,$data);
			$dx04 = date("Ymd");
			$dx05 = DateAdd('d',1,$data);
			$dx06 = DateAdd('d',7,$data);
			$dx07 = DateAdd('m',1,$data);
			$link01='<A HREF="'.$pg.'?dd1='.$dx01.'">&nbsp;<<<&nbsp;</A>';
			$link02='<A HREF="'.$pg.'?dd1='.$dx02.'">&nbsp;<<&nbsp;</A>';
			$link03='<A HREF="'.$pg.'?dd1='.$dx03.'">&nbsp;<&nbsp;';
			$link04='<A HREF="'.$pg.'?dd1='.$dx04.'">&nbsp;HOJE&nbsp;';
			$link05='<A HREF="'.$pg.'?dd1='.$dx05.'">&nbsp;></A>&nbsp;';
			$link06='<A HREF="'.$pg.'?dd1='.$dx06.'">&nbsp;>></A>&nbsp;';
			$link07='<A HREF="'.$pg.'?dd1='.$dx07.'">&nbsp;>>></A>&nbsp;';
			$link11='<A HREF="#" onclick="newxy2('.chr(39).$pg_edit."?dd3=".stodbr($data)."',600,600);".'">&nbsp;+&nbsp;';
			$link12='<A HREF="'.$pg_search.'?dd1='.$dx11.'">&nbsp;Busca&nbsp;';
			$link13='<A HREF="'.$pg.'?dd1='.$data.'">&nbsp;Refresh&nbsp;';
			$link14='<A HREF="'.$pg_cal.'?dd1='.$data.'">&nbsp;Calendário&nbsp;';
			$link15='';
			$link16='';
			$link17='';
			
			$sx = '
			<TABLE class="lt1" align="center">
			<TR>
			<TD><TABLE class="tabela01 lt1" border="0" cellspacing="0"><TR><TD>'.$link01.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01 lt2" border="0" cellspacing="0"><TR><TD>'.$link02.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01 lt3" border="0" cellspacing="0"><TR><TD>'.$link03.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01 lt4" border="0" cellspacing="0"><TR><TD>'.$link04.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01 lt3" border="0" cellspacing="0"><TR><TD>'.$link05.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01 lt2" border="0" cellspacing="0"><TR><TD>'.$link06.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01 lt1" border="0" cellspacing="0"><TR><TD>'.$link07.'</TD></TD></TR></TABLE>
			</TR>
			</TABLE>

			<TABLE class="lt1" align="center">
			<TR>
			<TD><TABLE class="tabela01" border="0" cellspacing="0"><TR><TD>'.$link11.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01" border="0" cellspacing="0"><TR><TD>'.$link12.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01" border="0" cellspacing="0"><TR><TD>'.$link13.'</TD></TD></TR></TABLE>
			<TD><TABLE class="tabela01" cellspacing="0"><TR><TD>'.$link14.'</TD></TD></TR></TABLE>
			';
			if (strlen($link17) > 0)
			{
				$sx .= '<TD><TABLE class="lt1" border="1" cellspacing="0"><TR><TD>'.$link17.'</TD></TD></TR></TABLE>';
			}
			$sx .= '</TR>
			</TABLE>';
			return($sx);	
		}
	
	function contas_receber_caixa($data)
		{
		$this->tabela_caixa = 'caixa_'.substr($data,0,4).substr($data,4,2).'_99';
		
		/* Consulta */			
		$sql = "select * from ".$this->tabela_caixa;
		$sql .= " where cx_status = 'A' ";
		$sql .= " and (cx_data >= ".$data." ";
		$sql .= " and cx_data <= ".$data.") ";
		$sql .= " order by cx_tipo ";

		$rlt = db_query($sql);
		$vlr = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		/* DIN, CHQ, ABR, ABC, ORO, FIF, LT */
		$tot = 0;
		
		while ($line = db_read($rlt))
			{
			$it++;
			
			$link = '<A HREF="#" onclick="newxy2('.chr(39).'cx_cadastro.php?dd0='.$line['id_cx'].'&dd1='.$data.chr(39).',640,350);">';
			$cod = trim($line['cx_tipo']);
			$valor = $line['cx_valor'];
			
			switch ($cod)
				{
				case 'DIN':
					$vlr[0] = $vlr[0] + $valor;
					break;
				case 'CHQ':
					$vlr[1] = $vlr[1] + $valor;
					break;
				case 'CRE':
					$vlr[2] = $vlr[2] + $valor;
					break;
				case 'ORO':
					$vlr[3] = $vlr[3] + $valor;
					break;	
				case 'FIF':
					$vlr[4] = $vlr[4] + $valor;
					break;
				case 'ABR':
					$vlr[8] = $vlr[8] + $valor;
					$alink = '<A HREF="javascript:newxy2('.chr(39).'caixa_central_abertura.php?dd0='.$line['id_cx'].chr(39).',600,400);">';
					//$tot+=$valor;
					break;	
				case 'FES':
					$vlr[10] = $vlr[10] + $valor;
					$blink = '<A HREF="javascript:newxy2('.chr(39).'caixa_central_encerramento.php?dd0='.$line['id_cx'].chr(39).',600,400);">';					
					//$tot-=$valor;
					break;
				default:
					$vlr[9] = $vlr[9] + $valor;
					break;											
				}
				//$tot+=$valor;			
			}
			/* Gerar valor de encerrante */
			if (strlen($blink) == 0)
				{ $vlr[10] = $this->encerrante_caixa($data);}
			$encerrante = $vlr[10];
			$sinal = '';
			if ($encerrante > 0) { $sinal = '+'; }
			
			/* Mostra informações */
			$sx = '		
				<table class="tabela00 lt1" width="100%">
					<TR>
						<TH><B>Grupo da Conta</B>
						<TH><B>Total</B>
					<TR '.coluna().'><TD class="tabela01">'.$alink.'<font color="#0000ff">Abertura</font></TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[8]+$vlr[9],2).'</TD></TR>
					<TR '.coluna().'><TD class="tabela01">'.$blink.'<font color="#008f00">Encerramento</font></TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.$sinal.fmt($encerrante,2).'</TD></TR>						
					<TR '.coluna().'><TD class="tabela01">Dinheiro (entrada)</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[0],2).'</TD></TR>
					<TR '.coluna().'><TD class="tabela01">Cheque (entrada)</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[1],2).'</TD></TR>
					<TR '.coluna().'><TD class="tabela01">Outras (entrada)</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($tot,2).'</TD></TR>
					<TR '.coluna().'><TD class="tabela01">Entrada Bancária</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[2],2).'</TD></TR>
					<TR '.coluna().'><TD class="tabela01">Outras (3)</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[3],2).'</TD></TR>
					<TR '.coluna().'><TD class="tabela01">Outras (7)</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[4],2).'</TD></TR>																																				
					<TR '.coluna().'><TD class="tabela01">Outras (9)</TD>
						<TD class="tabela01 lt2" align="right" width="100">
						'.fmt($vlr[9],2).'</TD></TR>																		
					<TR>
						<TD class="tabela00 lt1"></TD>
						<TD align="right" colspan="1">
							<B><Font color="#000000">
							'.fmt($vlr[0]+$vlr[1]+$vlr[2]+$vlr[3]+$vlr[4]+$vlr[8]+$vlr[9]-$vlr[10],2).'
							</FONT></B></TD></tr></TR>
					<TR>';
			$sx .= '</table>';
			
			$this->recebimentos = ($vlr[0]+$vlr[1]+$vlr[2]+$vlr[3]+$vlr[4]+$vlr[8]+$vlr[9]-$vlr[10]);			
			return($sx);
		}
	function saldo_contas_dia()
		{
			$saldo = round(100*$this->pagamentos - $this->recebimentos*100)/100;
			$saldo = fmt($saldo,2);
			$cor = '<font color="black">';
			if ($saldo > 0)
				{ $cor =  '<font color="red">'; }
			else 
				{ $cor =  '<font color="blue">'; }

			$sx = '<BR>&nbsp;';
			$sx .= '<center><table width="90%" class="tabela01" align="center">
				<TR><TD class="lt0">SALDO</td></tr>
				<TR><TD class="lt4" align="center"><B>'.$cor.$saldo.'</B></font></TD></TR>
				</table></center>';
			$sx .= '<BR>&nbsp;';
			return($sx);
		}
	function contas_receitas_resumo($data=0,$tipo='')
		{
			if ($data < 20000101) { $data = date("Ymd"); } 
			$sql = "select count(*) as total, 
						sum(cr_valor) as valor,
						cr_doc
					from ".$this->tabela_contas_receber." 
						where cr_venc = $data
						and cr_status <> 'X'
					group by cr_doc 
			";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00 lt2" width="100%">';
			$sx .= '<TR><TD colspan=3 class="lt2" align="center"><B>Pagamentos</B>';
			$sx .= '<TR>
					<TH>Tipo
					<TH>It
					<TH>Valor
			';
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$link = '<A href="'.page().'?dd1='.$data.'&dd2='.$line['cr_doc'].'" class="link">';
					$tot = $tot + $line['valor'];
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">'.$link.$line['cr_doc'].'</A>';
					$sx .= '<TD class="tabela01" align="center">('.$link.$line['total'].'</A>)';
					$sx .= '<TD class="tabela01" align="right">'.$link.fmt($line['valor'],2).'</a>';
					$sx .= chr(13).chr(10);
				}	
			if ($tot > 0)
				{
					$sx .= '<TR><TD colspan=3><B>Total '.fmt($tot,2).'</B>';
				}
			/* Libera link */
			if (strlen($tipo) > 0)
				{
					$link = '<A href="'.page().'?dd1='.$data.'" class="link lt0">';
					$sx .= '<TR><TD colspan=3>';
					$sx .= $link;
					$sx .= 'limpa filtro';
					$sx .= '</A>';
				}
			$sx .= '</table>';
			return($sx);
		}	

	function contas_receber($data=0,$tipo='')
		{
			if ($data < 20000101) { $data = date("Ymd"); }
			$tabela = "caixa_".date("Ym",$tdata)."_99";
			$tdata = xstod($data);
			$sql = "select * from caixa_".date("Ym",$tdata)."_99 
					where cx_data = $data
					order by cx_hora, id_cx
			";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00 lt2" width="100%">';
			$sx .= '<TR>
					<TH width="10%">Valor
					<TH width="63%">Histórico
					<TH width="10%">Hora
					<TH width="6%">-
					<TH width="6%">-
					<TH width="2%">-
					';
			while ($line = db_read($rlt))
				{
					$link1 = '<A href="#" onclick="newxy2(\'caixa_central_manual.php?dd1='.stodbr($data).'&dd0='.$line['id_cx'].'\',600,600);">';
					$link1a = '</A>';
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD align="right" class="tabela01">';
					$sx .= $link1.$cor.fmt($line['cx_valor'],2).$link1a;
					$sx .= '<TD class="tabela01">';
					$sx .= $link1.$cor.$line['cx_descricao'].$link1a;
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link1.$cor.$line['cx_hora'].$link1a;
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link1.$cor.$line['cr_parcela'].$link1a;
					$sx .= '<TD align="center" class="tabela01">';
					$sx .= '-';
					$sx .= '<TD class="tabela01" align="center">'.$link1a;
					$sx .= '-';
										
					$sx .= chr(13).chr(10);
				}
			$sx .= '</table>';
			return($sx);
		}
/**
 * Conta corrente
 */
 	function depositos_periodo($d1=0,$d2=0)
		{
			$valor = 0;
			$sql = "select * from ".$this->tabela_extrato_banco." 
					where ext_data >= $d1 and ext_data <= $d2
					and ext_auto = 'N' and ext_tipo = 'DIN'
			";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$valor = $valor + $line['ext_valor'];
				}		
			
			$sx = '<TR '.coluna().'>';
			$sx .= '<TD class="tabela01 lt1">Depósito em dinheiro</TD>';
			$sx .= '<TD class="tabela01 lt2" align="right">'.number_format($valor,2).'</TD>';
			$sx .= '</tr>';
			$this->deposito_dinheiro = $valor;
			return($sx);
		}
 		
		
/**
 * Contas a Pagar 
 */
 
 	function contas_pagar_detalhes($data=0,$dataf=0)
		{
			if ($dataf > 0) 
				{ $wh = 'and (cr_venc >= '.$data.' and cr_venc <= '.$dataf.')'; }
			else
				{
					$dataf = $data;
					$wh = ' and cr_venc = '.$data.' '; 
				}
			$sql = "select sum(cr_valor) as total, cg_descricao,cg_codigo
						 from ".$this->tabela_contas_pagar."
 						left join contas_tipo on ct_codigo = cr_conta
 						left join contas_grupo on ct_grupo = cg_codigo
 						where cr_status <> 'X' and cr_tipo <> 'D' 
 					    $wh
 					group by cg_codigo,cg_descricao ";
			$rlt = db_query($sql);
			$tot = 0;
			$itens1=0;
			while ($line = db_read($rlt))
				{
				$tot = $tot +$line['total'];
				if ($line['total'] > 0)
					{
					$st .= '<TR '.coluna().'>';
					$st .= '<TD class="tabela01 lt1">'.trim($line['cg_descricao']).'</TD>';
					$st .= '<TD class="tabela01 lt2" align="right">'.number_format($line['total'],2).'</TD>';
					$st .= '</tr>';
					}
				$itens1++;
				}

			$st .= $this->depositos_periodo($data,$dataf);
			$tot = $tot + $this->deposito_dinheiro;			
			
			$sx = '<table class="tabela00 lt1" width="100%">';
			$sx .= '<TR>
					<TH height="40" valign="bottom"><B>Grupo da Conta</B></TH>
					<TH height="40" valign="bottom"><B>Total</B></TH>
					</TR>
					';
			$sx .= $st;
			if ($tot > 0)
				{ $sx .= '<TR>
					<TD>
					<TD align="right" class="lt1">
					<B>'.fmt($tot,2).'</B>'; }			
			$sx .= '</table>';
			
			$this->pagamentos = $tot;

 		return($sx);
		}
	
	function contas_pagas_resumo($data=0,$tipo='')
		{
			if ($data < 20000101) { $data = date("Ymd"); } 
			$sql = "select count(*) as total, 
						sum(cr_valor) as valor,
						cr_doc
					from ".$this->tabela_contas_pagar." 
						where cr_venc = $data
						and cr_status <> 'X'
					group by cr_doc 
			";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00 lt2" width="100%">';
			$sx .= '<TR><TD colspan=3 class="lt2" align="center"><B>Pagamentos</B>';
			$sx .= '<TR>
					<TH>Tipo
					<TH>It
					<TH>Valor
			';
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$link = '<A href="'.page().'?dd1='.$data.'&dd2='.$line['cr_doc'].'" class="link">';
					$tot = $tot + $line['valor'];
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">'.$link.$line['cr_doc'].'</A>';
					$sx .= '<TD class="tabela01" align="center">('.$link.$line['total'].'</A>)';
					$sx .= '<TD class="tabela01" align="right">'.$link.fmt($line['valor'],2).'</a>';
					$sx .= chr(13).chr(10);
				}	
			if ($tot > 0)
				{
					$sx .= '<TR><TD colspan=3><B>Total '.fmt($tot,2).'</B>';
				}
			/* Libera link */
			if (strlen($tipo) > 0)
				{
					$link = '<A href="'.page().'?dd1='.$data.'" class="link lt0">';
					$sx .= '<TR><TD colspan=3>';
					$sx .= $link;
					$sx .= 'limpa filtro';
					$sx .= '</A>';
				}
			$sx .= '</table>';
			return($sx);
		}
	
	function contas_pagar($data=0,$tipo='')
		{
			if ($data < 20000101) { $data = date("Ymd"); }
			if (strlen($tipo) > 0) { $wh = " and cr_doc = '$tipo' ";} 
			$sql = "select * from ".$this->tabela_contas_pagar." 
					where cr_venc = $data
						$wh and cr_status <> 'X' 
					order by cr_previsao, cr_doc, cr_valor desc
			";
			$rlt = db_query($sql);
			$sx = $this->contas_pagar_linhas($rlt);
			return($sx);
		}
	function contas_pagar_linhas($rlt,$with_data=0)
		{
			if ($with_data == 1) { $withdate = '<TH width="6%">Data'; }
			$sx = '<table class="tabela00 lt2" width="100%">';
			$sx .= '<TR>
					'.$withdate.'
					<TH width="10%">Valor
					<TH width="*">Histórico
					<TH width="10%">Pedido
					<TH width="6%">Parcela
					<TH width="6%">Documento
					<TH width="2%">St
					';
			$tot = 0;
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$tot = $tot + $line['cr_valor'];
												
					$link1 = '<A href="#" onclick="newxy2(\'caixa_central_pagar_edit.php?dd0='.$line['id_cr'].'\',600,600);" >';
					$link1a = '</A>';
					$link2 = '<A href="#" onclick="newxy2(\'caixa_central_quitar.php?dd0='.$line['id_cr'].'\',600,600);" >';
					$link2a = '</A>';					
					$link3 = '<A href="#" onclick="newxy2(\'caixa_central_quitar.php?dd0='.$line['id_cr'].'\',600,600);" >';
					$link3a = '</A>';					
					$cor = '<font class="financeiro_open">';
					$status = trim($line['cr_status']);
					$link = '<A HREF="caixa_central.php?dd1='.$line['cr_venc'].'">';
					
					if (trim($line['cr_previsao'])==1 )
						{
							$cor = '<font class="financeiro_previsao">';
						}
											
					if ($status=='B')
						{
							$cor = '<font class="financeiro_quitado">';
							$link1 = '<A href="#" onclick="newxy2(\'caixa_centarl_pagar_ver.php?dd0='.$line['id_cr'].'\',300,800);" >';
							$link2 = '<A href="#" onclick="newxy2(\'upload.php?dd0='.$line['id_cr'].'\',600,600);" >';
							$status = '<img src="img/icone_documento_no.png">';
						}
					$img = trim($line['cr_img']);
					if (strlen($img) > 0)
						{
							$img = $this->mostra_imagem($img);
							$link2 = '<A href="#" onclick="newxy2(\''.$img.'\',800,600);" >';
							$status = '<img src="img/icone_documento.png">';
						}
						
					/* Mostra Pedido */
					$pedido = trim($line['cr_pedido']);
					$link_pedido_a = '</A>';
					$link_pedido = $this->link_romaneio($pedido);
					if (strlen($link_pedido) == 0) { $link_pedido_a = ''; }
					
					$sx .= '<TR '.coluna().'>';
					if ($with_data == 1) 
						{
							$sx .= '<TD align="right" class="tabela01">';
							$sx .= $link.$cor.stodbr($line['cr_venc']).$link1a;
						}
					$sx .= '<TD align="right" class="tabela01">';
					$sx .= $link1.$cor.fmt($line['cr_valor'],2).$link1a;
					$sx .= '<TD class="tabela01">';
					$sx .= $link1.$cor.$line['cr_historico'].$link1a;
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link_pedido.$cor.$line['cr_pedido'].$link_pedido_a;
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link1.$cor.$line['cr_parcela'].$link1a;
					$sx .= '<TD align="center" class="tabela01 lt0"><nobr>';
					$sx .= $link1.$cor.$line['cr_doc'];
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link2.$cor.$status.$link2a;
										
					$sx .= chr(13).chr(10);
				}
			if ($with_data == 1)
				{
					$sx .= '<TR><TD colspan=10><B><I>Total '.fmt($tot,2).' em '.$id.' registros</I></B>';
				}
			$sx .= '</table>';
			return($sx);
		}
		function link_pedido($pedido)
			{
				$link = '../pedidos/pedido_mostra.php?dd1='.$pedido;
				$link = '<A href="#" onclick="newxy2(\''.$link.'\',800,600);" >';
				if (strlen($pedido < 7)) { $link = ''; }
				return($link);			
			}
		function link_romaneio($pedido)
			{
				$link = '../pedidos/pedido_romaneio.php?dd1='.$pedido;
				$link = '<A href="#" onclick="newxy2(\''.$link.'\',800,600);" >';
				if (strlen($pedido < 7)) { $link = ''; }
				return($link);			
			}
		function mostra_imagem($doc)
			{
				if (strlen($doc) > 0)
					{
						$dir = 'scanner/';
						$dir .= substr($doc,0,4).'/';
						$dir .= substr($doc,4,2).'/';
						$file = $dir.$doc;

						if (file_exists($file))
							{
								return($file);
							}
						return('');
					}
			}
	
	
	}
?>
