<?

class relatorios
	{
		
 /**
  * Relatórios
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2013 - sisDOC.com.br
  * @access public
  * @version v.0.13.33
  * @package Classe
  * @subpackage UC00XX - Classe de Interoperabilidade de dados
 */
	var $msg=array();	
	var $cliente; 
 	var $tipo;
	var $ttln=10; /* Total de registro a mostrar por pagina */
	var $tx;
	var $tot_aberto = 0;
	var $tot_atraso = 0;
	var $tthistorico;
	var $tthistoricos;
	var $ttsubsoma;
	var $ttsoma;	/*utilizar na somatoria de varias páginas*/
	var $meses=4;	/*quantidade de meses a ser gravado no array $mes*/
	var $mes; /*array dos meses*/
	
	
	var	$line;					
	
		/*Gera cabeçalho da aba 2via*/
		function cabecalho_tipo_loja_2via()
			{
				
				global $dd, $consignado;
				$lj = array('T','M','E','J','G','S','U','O');		
				$sx = '<table class="tabela00" width="100%" height="16" bgcolor="#E0E0E0" >';
				$sx .= '<TR>';

				for ($r=0;$r < count($lj);$r++)
					{
						$js = 'onclick="atualiza_tela6(\''.$lj[$r].'\')" ';
						$sx .= '<TD class="tabela00" width="12%" height="35">';
						$sx .= '<a href="#" '.$js.'><img src="../img/btm_ct_ab.png" height="15" width="20" border=0 >
								</a><font color="#A0A0A0"> '.$consignado->nome_da_loja($lj[$r]).'</font></td>';
					}
				$sx .= '</tr></table>';
				$sx .= '
				<script>
					function atualiza_tela6(loja)
						{
							var checkpost="'.$dd[90].'";
								$.ajax({
								type: "POST",
								url: "cons_ajax.php",
								data: { dd0:"'.$dd[0].'", dd1: "2via", dd2: "1" , dd7: loja, dd90: checkpost }
								}).done(function( data ) {$("#cons06").html( data );});							
						}
				</script>
				';
				
				$sx = utf8_encode($sx);
				return($sx);				
			}
		/*Gera cabeçalho da aba Financeiro*/
		function cabecalho_financeiro()
			{
				
				global $dd, $consignado;
				$lb = array('Notas Promissórias','Movimento na Loja','Últimos Movimentos de Caixa','Créditos');		
				$lb2 = array('np','ml','um','cr');		
				
				$sx = '<table class="tabela00" width="100%" height="16" bgcolor="#E0E0E0" >';
				$sx .= '<TR>';

				for ($r=0;$r < count($lb);$r++)
					{
						$js = 'onclick="atualiza_tela3_'.$lb2[$r].'(\''.$lb2[$r].'\')" ';
						$sx .= '<TD class="tabela00" width="12%" height="35">';
						$sx .= '<a href="#" '.$js.'><img src="../img/btm_ct_ab.png" height="15" width="20" border=0 >
								</a><font color="#A0A0A0"> '.$lb[$r].'</font></td>';
					}
				$sx .= '</tr></table>';
				for ($r=0;$r < count($lb2);$r++)
					{
				
				$sx .= '
				<script>
					function atualiza_tela3_'.$lb2[$r].'(nota)
						{
							var checkpost="'.$dd[90].'";
								$.ajax({
								type: "POST",
								url: "cons_ajax.php",
								data: { dd0:"'.$dd[0].'", dd1: "financeiro", dd2: "1" , dd8: nota, dd90: checkpost }
								}).done(function( data ) {$("#cons03").html( data );});							
						}
				</script>
				';
				}
				
				$sx = utf8_encode($sx);
				return($sx);				
			}
		
		/*Lista 2ª via dos ultimos dias, chamando o método segunda_via_busca da classe _class_2via.php*/			
		function lista_vias($lja='T'){
			global $v2;
			
			$rst= $v2->segunda_via_busca();
			$sx .= $this->segunda_via_mostra_dados($rst);
			
			return($sx);
		}
		
		/*Chama a tela cons_2via_visualizar.php para exibição dos dados*/
		function segunda_via_mostra_dados($rst)
		{
		global $tab_max;
		$sx .= '<table width="'.$tab_max.'" cellspacing=8 cellpadding=0 class="lt1">';
		$col = 99;
		$colm = 12;
		$xdia = 19000101;
		$lojas = array(
				'M'=>'Modas', 'J'=>'Joias','O'=>'Óculos',
				'S'=>'Sensual', 'E'=>'Modas Express',
				'G'=>'Jóias Express', 'U'=>'UseBrilhe'
		);
		for ($r=0;$r < count($rst);$r++)
			{
			$line = $rst[$r];
			$mes = substr($line[4],0,6);
			$dia = $line[4];
				
			$link = '<A HREF="#" onclick="newxy2('.chr(39).'cons_2via_visualizar.php?dd0=';
			$link .= $line[0].'&dd1='.$mes.chr(39).',820,500);">';
			
			if ($dia != $xdia)
				{
					$sx .= '<TR><TD>';
					$sx .= '<TR><TD colspan=12 style="border-bottom: 1px solid Black; line-height: 150%;">';
					$sx .= '<font class="lt3">';
					$sx .= substr($dia,6,2);
					$sx .= ' ';
					$sx .= nomemes(round(substr($dia,4,2)));
					$sx .= ' '.substr($dia,0,4).'.';
					$xdia = $dia;
					$col = 99;
				}	
			
			if ($col >= $colm)
				{$sx .= '<TR>'; $col = 0; }
			$sx .= '<TD align="center" width="50" bgcolor="#F0F0F0">';
			$sx .= $link;
			$sx .= $lojas[$line[3]];
			$sx .= '(';
			$sx .= $line[2];
			$sx .= ')';
			$sx .= '<BR>';
			$sx .= $line[5].' '.$line[6];
			$col++;
			}
		$sx .= '</table>';
		return($sx);
		}
	
		/*Seta o total de registros do método resumo_notas*/
		function total_registros_nota($nota){
		global $base_name,$base_server,$base_host,$base_user, $dd;
		require("../db_fghi_210.php");
		//if($nota=='np'){ $nt = " and substring(dp_doc,1,1)='N' ";}	
				
		$sql = "select count(*) from (select  'J' as loja,* from duplicata_joias where dp_cliente = '".$dd[0]."' ".$nt." union 
				select  'M' as loja,* from duplicata_modas where dp_cliente = '".$dd[0]."' ".$nt." union 
				select  'O' as loja,* from duplicata_oculos where dp_cliente = '".$dd[0]."' ".$nt." union 
				select  'U' as loja,* from duplicata_usebrilhe where dp_cliente = '".$dd[0]."' ".$nt." union 
				select  'S' as loja,* from duplicata_sensual where dp_cliente = '".$dd[0]."' ".$nt." union 
				select 'D' as loja,* from juridico_duplicata where dp_cliente = '".$dd[0]."' ".$nt." 
				order by dp_data desc, loja, dp_status) as total ";
			$rlt = db_query($sql);
			while(($line = db_read($rlt))!=0){
			$sx=$this->tthistoricos=$line['count'];
			}	
			return($sx);
		}
		
		/*Pesquisa no BD notas*/
		function resumo_notas($nota='np',$pag='1'){
 			global $base_name,$base_server,$base_host,$base_user, $dd, $consignado;
			require("../db_fghi_210.php");
			switch ($nota) {
				case 'np':	$tx = '';		break;
				case 'ml':	$tx = 'and ';		break;
				default: 		break;
			}
			$sql = "select  'J' as loja,* from duplicata_joias where dp_cliente = '".$dd[0]."' union 
					select  'M' as loja,* from duplicata_modas where dp_cliente = '".$dd[0]."' union 
					select  'O' as loja,* from duplicata_oculos where dp_cliente = '".$dd[0]."' union 
					select  'U' as loja,* from duplicata_usebrilhe where dp_cliente = '".$dd[0]."' union 
					select  'S' as loja,* from duplicata_sensual where dp_cliente = '".$dd[0]."' union 
					select 'D' as loja,* from juridico_duplicata where dp_cliente = '".$dd[0]."'
					order by dp_data desc, loja, dp_status ";
			$ttln=$this->ttln;
			$sql3 =$sql.'limit '.($this->ttln).' offset '.(($pag-1)*$this->ttln);	
			$ln=$ttln+$ln;
			$sql3 = $sql;
			$rlt = db_query($sql3);
			$xmes = "X";
			$sy = '';
			while ($line = db_read($rlt)){
				$sta = $line['dp_status'];
				$status = '';
				$cor = '';
				switch ($sta) {
				case 'B':
					$status = "LIQUIDADO"; 
				break;
				case '@':
			 		$status = "NA TELA";
			 		$cor = '<font color="#ff0000">'; 
				break;
				case 'A':
					if ($line['dp_venc'] <  date("Ymd")) {
				 		$status = "ATRASADO"; 
				 		$cor = '<font color="#ff0000">'; 
				 		$this->tot_atraso = $this->tot_atraso + $line['dp_valor']; 
					}
					if ($line['dp_venc'] >= date("Ymd")) {
				 		$status = "ABERTO"; 
				 		$cor = '<font color="#ff8040">'; 
				 		$this->tot_aberto = $this->tot_aberto + $line['dp_valor'];
					}
				break;
				default:
				break;
			}
			
			$loja = $consignado->nome_da_loja($line['loja']);
			
			if (substr($line['dp_content'],0,1) == 'N'){
				if ($sta == 'B') { $status = "PAGO"; }
					$sx[0] .= '<TR>
						<TD ><TT>'.$cor.($loja).'</TD>
						<TD ><TT>'.$cor.($line['dp_doc']).'</TD>
						<TD ><TT>'.$cor.stodbr($line['dp_data']).'</TD>
						<TD ><TT>'.$cor.stodbr($line['dp_venc']).'</TD>
						<TD ><TT>'.$cor.trim($line['dp_content']).'</TD>
						<TD ><TT>'.stodbr($line['dp_datapaga']).'</TD>
						<TD align="right"><TT>'.$cor.number_format($line['dp_valor'],2).'</TD>
						<TD align="center"><TT>'.$status.'</TD>
						</TR>';
						$this->tthistorico[0]=$this->tthistorico[0]+1;
				} 
				else {
					$sx[1] .= '<TR >
						<TD ><TT>'.$cor.($loja).'</TD>
						<TD ><TT>'.$cor.($line['dp_doc']).'</TD>
						<TD ><TT>'.$cor.stodbr($line['dp_data']).'</TD>
						<TD ><TT>'.$cor.stodbr($line['dp_venc']).'</TD>
						<TD ><TT>'.$cor.trim($line['dp_content']).'</TD>
						<TD ><TT>'.stodbr($line['dp_datapaga']).'</TD>
						<TD align="right"><TT>'.$cor.number_format($line['dp_valor'],2).'</TD>
						<TD align="center"><TT>'.$status.'</TD>
					</TR>';
					$this->tthistorico[1]=$this->tthistorico[1]+1;				
				}
			}
		
			return($sx);
			}
	/*Recupera o número da última página - utilizar junto com o método mostra_navegador_paginas*/
	function recupera_pagina()
			{
				global $dd;
				$pag = round($dd[2]);
				if ($pag == 0) { $pag = 1; }
				$this->pag = $pag;
				return($pag);
			}
	/*Mostra navegador nas páginas com ajax*/		
	function mostra_navegador_paginas($pag_http='',$div='',$verb='',$nota='')
			{
				global $dd;
				$pag = $this->pag;
				$sx = '';
				
				$totp = $this->tthistoricos;
				
				if ($pag > 1)
					{
						$sx .= '<img src="/fonzaghi/img/icone_arrow_calender_left.png" width=18 id="pag_back'.$verb.'" >';		
					} else {
						$sx .= '<img src="/fonzaghi/img/nada_gray.gif" width=18 id="pag_back_none" >';
					}
				$sx .= ' ['.$pag.'] ';
				/* verifica se existe proxima pagina */
				if ((($pag-1) * $this->ttln + $this->ttln) < $totp)
					{ 
						$sx .= '<img src="/fonzaghi/img/icone_arrow_calender_right.png" width=18 id="pag_next'.$verb.'">';
					} else {
						$sx .= '<img src="/fonzaghi/img/nada_gray.gif" width=18 id="pag_next_none" >';
					}
				
				/* Script do Ajax */
				$sx .= 
				'<script>
				/* BACK */
				$("#pag_back'.$verb.'").click(function() {
						var checkpost="'.$dd[90].'";
						$.ajax({
							type: "POST",
							url: "'.$pag_http.'",
							data: { dd0:"'.$dd[0].'", dd1: "'.$verb.'", dd2: "'.($pag-1).'",dd8: "'.$nota.'", dd90: checkpost  }
						}).done(function( data ) {$("#'.$div.'").html( data ); });
				});
				/* NEXT */
				$("#pag_next'.$verb.'").click(function() {
						var checkpost="'.$dd[90].'";
						$.ajax({
							type: "POST",
							url: "'.$pag_http.'",
							data: { dd0:"'.$dd[0].'", dd1: "'.$verb.'", dd2: "'.($pag+1).'",dd8:"'.$nota.'", dd90: checkpost  }
						}).done(function( data ) {$("#'.$div.'").html( data ); });					
				});
				</script>
				';
				return($sx);
			}
		/*Lista notas promissórias*/
		function nota_promissoria($nota='',$pag='1'){
		$st=$this->resumo_notas($nota,$pag);	
		$sx .='<table border="0"  class="lt0"  width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="">
				<TR bgcolor="Silver">	
					<TH >Lj.</TH>
					<th >Doc.</th>
					<TH >Emissão</TH>
					<th >Venc.</th>
					<TH >Descrição</TH>
					<TH >Dt. Liquidação</TH>
					<TH >Valor</TH>
					<TH >Status</TH>
				</TR>
				'.$st[0].'
					<TR><TD colspan="8"><HR></TD></TR> 
				<TR>
					<td colspan="5" align="left"><b>Notas Abertas<TD colspan="2" align="right"><b>'.number_format($this->tot_aberto+$this->tot_atraso,2).'</td>
				</tr>
				</table>';
				
			return($sx);
		}
		/*Lista movimento das lojas*/
		function movimento_loja($nota='',$pag='1'){
		$sy=$this->resumo_notas($nota,$pag);	
		$sx = '<table border="0"  class="lt0"  width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="">
				<TR  bgcolor="Silver">
					<TH >Lj.</TH>
					<th >Doc.</th>
					<TH >Emissão</TH>
					<th >Venc.</th>
					<TH >Descrição</TH>
					<TH >Dt. Liquidação</TH>
					<TH >Valor</TH>
					<TH >Status</TH>
				</TR>
				'.$sy[1].'
				</TD>
				</TR>
				<TR><TD colspan="8"><HR></TD></TR> 
		</TABLE>';
			
			return($sx);
 		}
		
		function total_ult_movimentos_caixa($cliente,$pag='1'){
			global $base_name,$base_server,$base_host,$base_user;
			require("../db_ecaixa.php");
			$lj = array('S','J','M','O','U','D');
			$per = array('_01','_02','_03','_04','_05','_09');
			$this->set_mes();
			$mes = $this->mes;
			$sql .= 'select count(*) from (';
			for ($r=0; $r <count($mes) ; $r++) { 
				for ($i=0; $i <count($lj) ; $i++) {
					
					$sql  .= "select '".$lj[$i]."' as loja,* from caixa_".$mes[$r].$per[$i]." where cx_cliente = '".$cliente."' and cx_status <> 'X' ";
					if(($r==count($mes)-1) && ($i==count($lj)-1)){ }else{$sql .= " union ";}
				}
			}	
		
			$sql .= ' ) as total';
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$sx = $this->tthistoricos=$line['count'];
			
		return($sx);	
		}

		function total_ult_creditos($cliente,$pag='1'){
			global $base_name,$base_server,$base_host,$base_user;
			require("../db_ecaixa.php");
			$sql = "select sum(ccard_valor),count(*) from credito_outros where ccard_cliente = '".$this->cliente."'  and ccard_pago=0 ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$this->ttsoma=$line['sum'];
			$this->tthistoricos=$line['count'];
			
			
			return(1);	
		}
		
		function mostra_ultimos_creditos($pag='1'){
			$sql2 = "select sum(ccard_valor) from (select ccard_valor from credito_outros where ccard_cliente = '".$this->cliente."' and ccard_pago=0  order by ccard_data limit ".(($pag-1)*$this->ttln).") as tb";
			$rlt2 = db_query($sql2);
			$line2 = db_read($rlt2);
		    $this->ttsubsoma=$line2['sum'];
			
			 	
				
				
			$sld=$this->ttsubsoma;
				
			$sql = "select * from credito_outros where ccard_cliente = '".$this->cliente."'  and ccard_pago=0 ";
			$sql .= "order by ccard_data, id_ccard";	
			//echo '<br>'.$sql .= ' limit '.($this->ttln).' offset '.(($pag-1)*$this->ttln);
			$rlt = db_query($sql);
			
			
			while ($line = db_read($rlt))
			{$tt++;
				$vlr = $line['ccard_valor'];
				$sld = $sld + $vlr;
				
				if ($vlr < 0){$ft = '<font color="red">'; }
				$s.= '<TR >';
				$s.= '<TD><TT>';
				$s.= $ft;
				$s.= substr(stodbr($line['ccard_data']),0,5);
				$s.= '<TD align="right" width="14%"><TT><noBR>';
				$s.= $ft;
				$s.= number_format($line['ccard_valor'],2);
				$s.= '</TD>';
				$s.= '<TD><TT>';
				$s.= $ft;
				$s.= $line['ccard_historico'];
				$s.= '<TD align="right"><TT>';
				$s.= number_format($sld,2);
				$s.= '<TD align="right"><TT>';
				$s.= $line['ccard_loja'];
				$s.= '</TR>';
			}
			
			$sld = intval($sld*100)/100;
		
			$sx = '<table class="lt0" width="100%" cellpadding="5" cellspacing="1">
			<TR bgcolor="Silver">
				<TH width="10">data</TH>
				<TH width="20">valor</TH>
				<TH width="250">histórico</TH>
				<TH width="20">sub-total</TH>
				<TH width="5">L</TH>
			</TR>';
			//$sx .= '<TR><TD colspan="3" align="left"><B>Sub-saldo Anterior </b></td><td align="right"><b>'.number_format($this->ttsubsoma,2).'</B></TD></TR>
			$sx .= $s;
		//	$sx .='<TR><TD colspan="3" align="left"><B>Sub-saldo  </b></td><td align="right"><b>'.number_format($sld,2).'</B></TD></TR>';
			$sx .= '<TR><TD colspan="5"><HR></TD></TR> 
			<TR><TD colspan="3" align="left"><B>Saldo </b></td><td align="right"><b>'.number_format($this->ttsoma,2).'</B></TD></TR>
			</table>';
	
	
			
			
			return($sx);
		}
		
		
		
		
		function mostra_mes($cliente,$pag='1'){
			global $base_name,$base_server,$base_host,$base_user,$consignado;
			require("../db_ecaixa.php");
			$lj = array('S','J','M','O','U','D');
			$per = array('_01','_02','_03','_04','_05','_09');
			$mes = $this->mes;
			
			for ($r=0; $r <count($mes) ; $r++) { 
				for ($i=0; $i <count($lj) ; $i++) {
					
					$sql  .= "select '".$lj[$i]."' as loja,* from caixa_".$mes[$r].$per[$i]." where cx_cliente = '".$cliente."' and cx_status <> 'X' ";
					if(($r==count($mes)-1) && ($i==count($lj)-1)){ }else{$sql .= " union ";}
				}
			}		
			
			$sql .= " order by cx_data desc, cx_hora desc, id_cx desc ";
			//$sql .= 'limit '.($this->ttln).' offset '.(($pag-1)*$this->ttln);
			$rlt = db_query($sql);
			$sx = '';
			$sld = 0;
			
			while ($line = db_read($rlt))
				{
				$loja = $consignado->nome_da_loja($line['loja']);	
				$sx .= '<TR >';
				$sx .= $ft.'<TD  align="center">';
				$sx .= $loja;
				$sx .= $ft.'<TD  align="center"><TT>';
				$sx .= stodbr($line['cx_data']);
				$sx .= ' '.$line['cx_hora'];
				$sx .= $ft.'<TD  align="center"><TT>';
				$sx .= stodbr($line['cx_venc']);
				$sx .= $ft.'<TD  align="center"><TT>';
				$sx .= $line['cx_doc'];
				$sx .= $ft.'<TD  align="center"><TT>';
				$sx .= $line['cx_tipo'];
				$sx .= $ft.'<TD  align="left"><TT>';
				$ss = trim($line['cx_descricao']);
				
				if ($ss == 'Nota Promissoria') { 
					$ss = 'Nota Promissoria (Aberta)'; 
					$sld = $sld - $line['cx_valor'];
				}
				else{
					$sld = $sld + $line['cx_valor'];
				}
				$sx .= $ss;
				
				$sx .= '<TD  align="center"><TT>';
				if (strlen(trim($line['cx_chq_nrchq']))==0){
					$sx .= '-';
				}
				else{
					$sx .= $line['cx_chq_nrchq'];
				}
			
				$sx .= '<TD  align="right"><TT>';
				$sx .= number_format($line['cx_valor'],2);
				
				$sx .= '<TD  align="center"><TT>';
				$sx .= $line['cx_lote'];
				
				$sx .= '</TR>';
				}
			return($sx);
		}
		/*seta meses a serem utilizados na array $mes[] */
		function set_mes(){
			$meses=$this->meses;
			$this->mes[0] = date("Ym");
			
			for ($i=1; $i <$meses ; $i++) { 
				$this->mes[$i]=substr(dateadd("m",($i*-1),date("Ymd")),0,6);	
			}
			return(1);
		}	
		
		function mostra_ultimos_mov_caixa($pag){
			global $dd;
		
			$cliente=$dd[0];
			$this->set_mes($this->meses);
			$mes=$this->mes;
			$meses=$this->meses;
			$sc=$this->mostra_mes($cliente,$pag);
			$ft = '<font color="black">';
			$sx = '	<table border="0"  class="lt0"  width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="">	
					
					<TR bgcolor="Silver">
						<TH >Lj.</TH>
						<TH >Data</TH>
						<TH >Venc.</TH>
						<TH >Doc.</TH>
						<TH >Tipo</TH>
						<TH >Histórico</TH>
						<TH >Chq/Docs</TH>
						<TH >Valor</TH>
						<TH >Lote</TH>
					</TR>
					<TR>
						<TD class="lt0" colspan="8" align="center">	Período de '.substr($mes[0],4,2).'/'.substr($mes[0],0,4).
						  ' a '.substr($mes[$meses-1],4,2).'/'.substr($mes[$meses-1],0,4).'
						</TD>
					</TR>
					
					'.$sc.'
					<TR><TD colspan="9"><HR></TD></TR> 
					<TR><TD colspan="7" align="left"><b>Totais:</TD>
						<TD  align="right"><b>'.number_format($sld,2).'</TD>
						<TD></TD>
					</TR>
					</table>';
			
			
			return($sx);
		}

		function total_ult_movimentos_senff($cliente,$pag='1'){
			global $base_name,$base_server,$base_host,$base_user;
			require("../db_senff.php");
			$sql = "select count(*),sum(ex_valor) from senff_extrato where ex_cliente = '".$cliente."' 
					";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$sx = $this->tthistoricos=$line['count'];
			$this->ttsoma=$line['sum'];
		return($sx);	
		}

		function mostra_relatorio_senff($pag='1'){
			global $base_name,$base_server,$base_host,$base_user,$senff;	
			$sh = '<table border="0"  class="lt0"  width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="">	
					<tr valign="top">
						<td class="lt1" colspan="6" align="center"><br><B>Extrato cartão fidelidade </td>
					</tr>
	  				<tr bgcolor="silver"> 
					   <th width="80" class="head" scope="col">Data</th>
	   					<th width="80" class="head" scope="col">Doc.</th>
	   					<th width="60%" class="head" scope="col">Descrição</th>
	   					<th width="10%" class="head" scope="col">Débito</th>
	   					<th width="10%" class="head" scope="col">Crédito</th>
	   					<th width="10%" class="head" scope="col">Saldo</th>
				   </tr>';
      	
			require("../db_senff.php");
			$cliente=$this->cliente;
			$sql = "select * from senff_extrato where ex_cliente = '".$cliente."' 
					order by ex_data, ex_valor desc
					";
			//$sql .= ' limit '.($this->ttln).' offset '.(($pag-1)*$this->ttln);
			$rlt = db_query($sql);
			$saldo = 0;
			$total = $this->ttsoma;
			while ($line = db_read($rlt)){
				$v = $line['ex_valor'];
				if ($v >= 0) 
					{ $v2 = number_format($v,2,',','.'); $v1 = ''; }
					else 
					{ $v1 = number_format($v,2,',','.'); $v2 = ''; }						
				$saldo = $saldo + $line['ex_valor'];
				$sx = '<tr class="TRD1" align="center" border="0px;">
	   				    	<td><tt><B>'.stodbr($line['ex_data']).'</td>
							<td><tt><B>'.$line['ex_doc'].'</td>	    	
	    					<td align="left"><tt>'.trim($line['ex_descricao']).'</td>
	    					<td align="right"><tt>'.$v1.'</td>
	   						<td align="right"><tt>'.$v2.'</td>
	   						<td align="right"><tt>'.number_format($saldo,2,',','.').'</td>
      					</tr>' .  
      					$sx;
			}    

 				$sx .= '<TR><TD colspan="6"><HR></TD></TR>
 						</table>';
			
			return($sh.$sx);
		}
		
		function duplicatas_total(){
		global $base_name,$base_host,$base_user,$duplicata;	
		
		$duplicata->set_tabelas();
		require("../db_fghi_210.php");
		$s = '';
		$cod_clie=$duplicata->db_cliente;
		$div = round(100 / ($duplicata->ttdp+1));
		
		$vlr1 = 0;
		$vlr2 = 0;
		$vlr3 = 0;
		
		for ($r=0;$r < $duplicata->ttdp;$r++)
		{
			
			$sql = "select sum(dp_valor) as total from ".$duplicata->dupl[$r][2];
			$sql .= " where dp_cliente = '".$cod_clie."' and ( dp_status = '@' or dp_status = 'A') and dp_venc >= ".date("Ymd");
			$xrlt = db_query($sql);
			
			if ($xline = db_read($xrlt)){ $vlr1 = $xline['total']+$vlr1;}

			$sql = "select sum(dp_valor) as total from ".$duplicata->dupl[$r][2];
			$sql .= " where dp_cliente = '".$cod_clie."' and ( dp_status = '@' or dp_status = 'A') and dp_venc < ".date("Ymd");
			$xrlt = db_query($sql);
			
			if ($xline = db_read($xrlt))	{ $vlr2 = $xline['total']+$vlr2;}
							
		}
		
		$f1 = '<font class="lt1">'; $f2 = '<font class="lt1">'; $f3 = '<font class="lt1">';
		
		if ($vlr1 > 0) { $f1 = '<font color="GREEN">'; }
		if ($vlr2 > 0) { $f2 = '<font color="RED">'; }
		if ($vlr3 > 0) { $f3 = '<font color="BLUE">'; }
		
		if ($vlr1==0 and $vlr2==0 and $vlr3==0){$vlr1=0;$vlr2=0;$vlr3=0;$f1='<font color="GRAY">';$f2='<font color="GRAY">';$f3='<font color="GRAY">'; }
		if (strlen($cod_clie)==0){$vlr1=0;$vlr2=0;$vlr3=0;$f1='<font color="GRAY">';$f2='<font color="GRAY">';$f3='<font color="GRAY">'; }
		
		$tx = '<span id="grafico_a" style="cursor: pointer;">
				<table class="tabela00" width="240">
					<TR>
						<TD colspan=2 align="center" class="lt2"><B>Total Duplicatas</B>
					<TR class="lt0">
						<TD align="center" width="120">Notas abertas
						<TD align="center" width="120">Notas atrasadas
					<TR><TD align="center" class="tabela01 lt2">'.$f1.number_format($vlr1,2).'</font>
						<TD align="center" class="tabela01 lt2">'.$f2.number_format($vlr2,2).'</font>
				</table>
			   </span>            		
               	<script>
				$("#grafico_a").click(function() {goto(\'#cons02\', this)});
				</script>
					'; 
	return($tx);
	}		
	
	function tips($cx1,$cx2)
	{
		global $tips_obj;
		$tips_obj++;
		$csi = "cdint".$tips_obj;
		$cs = '<A HREF="#" ';
		$cs .= ' onMouseMove="Hint('.chr(39).$csi.chr(39).',2)" ';
		$cs .= ' onMouseOut="Hint('.chr(39).$csi.chr(39).',1)" class="link">';
		$cs .= $cx1;
		$cs .= '</A>';
		$cs .= '<div id="'.$csi.'" class="tips" style="position:absolute; z-index:1; visibility: hidden; ">';
		$cs .= $cx2;
		$cs .= '</div>';
		return($cs);
	}
	
 
}
?>		