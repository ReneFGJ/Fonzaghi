<?php
$ac = array();
$ad = array();
$ap = array();
class consignado
	{
		
 /**
  * Consignado
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v.0.14.13
  * @package Classe
  * @subpackage UC0028 - Classe de Interoperabilidade de dados
 */
 	
	 
	var $x; 
	 
	/*atributos dos kits consignados*/
 	var $id_kh;
  	var $kh_data;
  	var $kh_fornecimento;
  	var $kh_previsao;
  	var $kh_acerto;
  	var $kh_log;
  	var $kh_pc_forn;
  	var $kh_pc_vend;
  	var $kh_vlr_forn;
  	var $kh_vlr_vend;
  	var $kh_vlr_comissao;
  	var $kh_vlr_comissao_repre;
  	var $kh_kits;
  	var $kh_cliente;
  	var $kh_comissao;
  	var $kh_pago;
  	var $kh_status;
  	var $kh_dif;
  	var $kh_nr_consignacao;
  	var $kh_log_acerto;
  	var $kh_desconto;
  	var $kh_nf;
  	var $kh_n;
 	
	/*atributos dos acertos*/
	var $lojas;
	var $ttlojas=6; 	/* sï¿½o 7 lojas contando de 0..6*/
	var $ace;
	var $med;
	var $maxi;
	var $pec;
	var $ven;
	var $bru;
	var $acerto_mes;
	var $media_lojas;
	var $maximo_lojas;
	
	/*atributos do historico*/
	var $ttln=8; /* Total de registro a mostrar por pagina */
	var $tthistoricos=0; /* Total de registros com mensagens */
	
	var $conteudo;
	var $historico;
	var $p;
	
	
	/*atributos do cliente*/
	var $id;
	var $nome;
	var $nasc;
	var $cpf;
	var $codigo;
	var $foto;
	var $dtcadastro;
	var $equipe;
	var $coord;
	var $coord_email;
	var $cliente;
	
	var $cl_clientepai;
	var $cl_nomepai;
	var $cl_nomemae;
	var $cl_rg;
	var $cl_rguf;
	var $cl_propaganda;
	var $cl_tipo;
	var $cl_equipe;
	var $cl_update;
	var $cl_autorizada;
	var $cl_search;
	var $cl_status;
	var $cl_nasc;
	var $cl_senha;
	var $cl_senha_lembrete;
	var $cl_senha_status;
	var $cl_senha_update;
	
	var $rx;
	var $lx;
	var $pag=1;
	
	var $db;
	var $line;
	var $tabela = 'cadastro';
	
		function relatorio_novas()
			{
				$cons = new consultora;
				$sql = "
					select * from (
					select count(*) as total, kh_cliente as kh_cliente_old from kits_consignado 
						where kh_status = 'B' and kh_acerto > ".((date("Y")-1).date("md"))."
						group by kh_cliente
					) as tabela 
					right join ( select kh_cliente as kh_cliente_novo, * from kits_consignado where kh_status = 'A' )
					as tabela2
 					on kh_cliente_novo = kh_cliente_old
 					where kh_cliente_old isnull
 					order by kh_previsao
 					";
					
				$rlt = db_query($sql);
				$sx = '<table width="800" align="center">';
				$sx .= '<TR><TH>cliente<TH>Fornecimento<TH>Prev.Acerto';
				$id = 0;
				while ($line = db_read($rlt))
					{
						$id++;
						$clie = trim($line['kh_cliente_novo']);
						$clin = trim($line['kh_cliente']);
						//if (strlen($clin) == 0)
							{
								$sx .= '<TR>';
								$sx .= '<TD align="center" class="tabela01">';
								$sx .= $cons->link_consultora($line['kh_cliente_novo']);
								$sx .= '<TD align="center" class="tabela01">';
								$sx .= stodbr($line['kh_fornecimento']);
								$sx .= '<TD align="center" class="tabela01">';
								$sx .= stodbr($line['kh_previsao']);
								
								//print_r($line);								
							}
						
					}
				$sx .= '<TR><TD colspan=10>Total '.$id;
				$sx .= '</table>';
				return($sx);
									
			}
	
		function le()
			{
				
			}
		function produtos_consigandos_consultora()
			{
				global $ac,$ad,$ap,$az,$http_site;
				$col = 0;
				$sx = '<table class="tabela00">';
				$sx .= '<TR valign="top">';
				for ($r=0;$r < 10;$r++)
					{
					
					if (strlen($ap[$r])!= 0)
						{	
							if ($col > 3) { $sx .= '<TR>'; $col=0; }
							$title = $ac[$r]; $title = troca($title,'blue','grey');
							/*Se $az[] estiver zerado nï¿½o mostra o icone da loja*/
							if ($az[$r]!=0) {
							$sx .= '<TD class="tabela01 lt0" align="center" style="padding: 2px; ">';
							$sx .= '<img src="'.$http_site.'img/icone_lj_'.($r).'.png" width="40">&nbsp;';
							$sx .= '<BR>'.$title;	
							}
							
							$col++;	
						}
					}
				$sx .= '</table>';
				return($sx);
			}
		function nova_mensagem()
			{
				$sx = '<img src="/fonzaghi/img/btm_novo.fw.png" width="100" id="msg_new" valign="bottom">';
				
				$sx .= '<div id="form_msg"  style="display: none;">'.$this->form_msg_post().'</div>
				
				<script>
					$("#msg_new").click(function() {
							 $("#form_msg").fadeIn();
							 $("#msg_close").show();
							 $("#msg_new").hide();
					});
					$("#msg_close").click(function() {
							 $("#form_msg").fadeOut();
							 $("#msg_close").hide();
							 $("#msg_new").show();								
							});							
				</script>
				';
				return($sx);
			}
		function form_msg_post()	
			{
				global $dd,$secu;
				$sx = '<CENTER><font class="lt5">Nova Mensagem</font></CENTER>
				<TABLE border="0" align="center" width="100%">
					<tr>
						<td align="center"  colspan="2" class="lt3">Data: '.stodbr(date('Ymd')).'
					</tr>
					<TR>
						<TD> <textarea id="dd20" name="comentário" rows="6" cols="60"></textarea></td> 
					</TR>	
					<tr>
						<td>
							<select id="dd21">
								<option value="C01">Comunicado</option>
  								<option value="A01">Aviso</option>
  							</select>
  						</td>
					</tr>
				</TABLE>';
				
				$sx .= '<a><img src="/fonzaghi/img/btm_voltar.fw.png" align="left" width="100" height="30" id="msg_close" style="display: none;" ></a>';
				$sx .= '';
				$sx .= '<img src="/fonzaghi/img/btm_salvar.fw.png" align="right" width="100" height="30" id="postar">';;
				$sx .= '
				<script>
					$("#postar").click(function() {
							
							/*
							 $("#form_msg").fadeOut();
							 $("#msg_close").hide();
							 $("#msg_new").show();
							 */
							 
							 var checkpost = "'.checkpost($dd[0].$secu).'";
							 var mess = $("#dd20").val();
							 var tipo = $("#dd21").val();
							 var loja = "'.$dd[7].'";
							 
							 $.ajax({								
								type: "POST",
								url: "cons_ajax.php",
								data: { dd0:"'.$dd[0].'", dd1: "insere_mensagem" ,dd2: "1", dd7: loja, dd20: mess, dd21: tipo, dd8: "at",  dd90: checkpost }
								}).done(function( data ) { $("#cons04").html( data ); });
							
					});
				</script>
				';		
				return($sx);
			}
		function salva_msg($log,$msg,$cliente,$tipo,$loja)
		{
			$sql = "insert into historico_".date('Y')."
			 		 (h_data,h_hora,h_log,
			 		  h_texto,h_cliente,h_tipo,
			 		  h_loja) 
			 		 values
			 		 (".date('Ymd').",'".date("H:i")."','".$log."',
			 		 '".$msg."','".$cliente."','".$tipo."','".$loja."')
			 ";
			 $rlt = db_query($sql);
			 	
 		}
		function cabecalho_tipo_loja()
			{
				global $dd;
				$lj = array('T','D','J','M','O','U','S');		
				$sx = '<table class="tabela00" width="100%" height="16" bgcolor="#E0E0E0" >';
				$sx .= '<TR>';

				for ($r=0;$r < count($lj);$r++)
					{
						$js = 'onclick="atualiza_tela4(\''.$lj[$r].'\')" ';
						$sx .= '<TD class="tabela00" width="8%" height="35">';
						$sx .= '<a href="#" '.$js.'><img src="../img/btm_ct_ab.png" height="15" width="20" border=0 ></a>
								<font color="#A0A0A0"> '.$this->nome_da_loja($lj[$r]).'</font></td>';
					}
				$sx .= '</tr></table>';
				$sx .= '
				<script>
					function atualiza_tela4(loja)
						{
							var checkpost="'.$dd[90].'";
							$.ajax({
								type: "POST",
								url: "cons_ajax.php",
								data: { dd0:"'.$dd[0].'", dd1: "mensagens",dd2: "1", dd7: loja,  dd90: checkpost }
								}).done(function( data ) {$("#cons04").html( data ); });							
						}
				</script>
				';
				
				$sx = utf8_encode($sx);
				return($sx);				
			}
		function mostra_navegador_paginas($pag_http='',$div='',$verb='')
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
							data: { dd0:"'.$dd[0].'", dd1: "'.$verb.'", dd2: "'.($pag-1).'", dd90: checkpost  }
						}).done(function( data ) {$("#'.$div.'").html( data ); });
				});
				/* NEXT */
				$("#pag_next'.$verb.'").click(function() {
						var checkpost="'.$dd[90].'";
						$.ajax({
							type: "POST",
							url: "'.$pag_http.'",
							data: { dd0:"'.$dd[0].'", dd1: "'.$verb.'", dd2: "'.($pag+1).'", dd90: checkpost  }
						}).done(function( data ) {$("#'.$div.'").html( data ); });					
				});
				</script>
				';
				return($sx);
			}
		function recupera_pagina()
			{
				global $dd;
				$pag = round($dd[2]);
				if ($pag == 0) { $pag = 1; }
				$this->pag = $pag;
				return($pag);
			}
		function nome_da_loja($lj)
			{
				switch ($lj)
					{
					case 'J': return("Jóias"); break;
					case 'M': return("Modas"); break;
					case 'S': return("Sensual"); break;
					case 'O': return("Óculos"); break;
					case 'D': return("Juridico"); break;
					case 'U': return("UseBrilhe"); break;
					case 'E': return("Ex Modas"); break;
					case 'G': return("Ex Jóias"); break;
					case 'T': return("Todas"); break;
					}
					return('?');
			}
		function setdb($db){
			global $base_name,$base_host,$base_user;	
			
			$this->setlojas();
			
			switch ($db) {
				case 'Jóias':			require("../db_fghi_206_joias.php");		break;
				case 'Modas':			require("../db_fghi_206_modas.php");		break;
				case 'Óculos':			require("../db_fghi_206_oculos.php");		break;
				case 'Catálogo':		require("../db_fghi_206_ub.php");			break;
				case 'Sensual':			require("../db_fghi_206_sensual.php");		break;
				case 'Express Modas':	require("../db_fghi_206_express.php");		break;
				case 'Express Jóias':	require("../db_fghi_206_express_joias.php");break;
				default:															break;
			}
			return(1);
		}
		function setconsignado($cliente,$db){
			$this->setdb($db);
					
			$sql = "select * from kits_consignado where kh_status = 'A' and kh_cliente = '".$cliente."'";
			
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
					{
					
				$this->id_kh = $line['id_kh'];
				$this->kh_data = $line['kh_data'];
				$this->kh_fornecimento = $line['kh_fornecimento'];
				$this->kh_previsao = $line['kh_previsao'];
				$this->kh_acerto = $line['kh_acerto'];
				$this->kh_log = $line['kh_log'];
				$this->kh_pc_forn = $line['kh_pc_forn'];
				$this->kh_pc_vend = $line['kh_pc_vend'];
				$this->kh_vlr_forn = $line['kh_vlr_forn'];
				$this->kh_vlr_vend = $line['kh_vlr_vend'];
				$this->kh_vlr_comissao = $line['kh_vlr_comissao'];
				$this->kh_vlr_comissao_repre = $line['kh_vlr_comissao_repre'];
				$this->kh_kits = $line['kh_kits'];
				$this->kh_cliente = $line['kh_cliente'];
				$this->kh_comissao = $line['kh_comissao'];
				$this->kh_pago = $line['kh_pago'];
				$this->kh_status = $line['kh_status'];
				$this->kh_nr_consignacao = $line['kh_nr_consignacao'];
				$this->kh_log_acerto = $line['kh_log_acerto'];
				$this->kh_desconto = $line['kh_desconto'];
				$this->kh_nf = $line['kh_nf'];
				$this->kh_n = $line['kh_n'];
				
				$ok = 1;
				} else {
				$this->id_kh = '';
				$this->kh_data = '';
				$this->kh_fornecimento = '';
				$this->kh_previsao = '';
				$this->kh_acerto = '';
				$this->kh_log = '';
				$this->kh_pc_forn = '';
				$this->kh_pc_vend = '';
				$this->kh_vlr_forn = '';
				$this->kh_vlr_vend = '';
				$this->kh_vlr_comissao = '';
				$this->kh_vlr_comissao_repre = '';
				$this->kh_kits = '';
				$this->kh_cliente = '';
				$this->kh_comissao = '';
				$this->kh_pago = '';
				$this->kh_status = '';
				$this->kh_nr_consignacao = '';
				$this->kh_log_acerto = '';
				$this->kh_desconto = '';
				$this->kh_nf = '';
				$this->kh_n = '';	
					
				$ok = 0;
				}
				
				return($ok);	
			
		}
		function consultora_status($sta)
		{
			switch ($sta)	
				{
					case 'A': { $sta = '<font color="green">Ativa'; break; }
					case 'I': { $sta = '<font color="red">Inativa'; break; }
					case '@': { $sta = 'Em cadastro'; break; }
				}
				return($sta); 
		}
		function historico_relacionamento($loja='T',$pag=1)
			{
			global $base_name,$base_server,$base_host,$base_user;
			require("../db_fghi_206_cadastro.php");	
		
			$ano = 	(date('Y')+1);
			$ttanos = 5;	
			
			if($loja=='') { $loja = 'T'; }
			if ($loja != 'T') { $lx=" and (h_loja='".$loja."')"; }	
			
			/* Monta query de consulta */	
			$sql2 = "SELECT COUNT(*) FROM (";			
			while ($i++ <= $ttanos) {
					$sql2 .= "SELECT id_h, h_data, h_hora, h_log, h_texto, h_cliente, h_tipo, h_loja 
							FROM historico_".($ano-$i)." 
							where h_cliente ='".$this->cliente."'".$lx; 
					if ($i<=$ttanos) { $sql2 .=" union "; } else {$sql2 .=" ) AS t ";}							
					  
					 /*Query historicos*/
					$sql .= "SELECT id_h, h_data, h_hora, h_log, h_texto, h_cliente, h_tipo, h_loja 
							FROM historico_".($ano-$i)." 
							where h_cliente ='".$this->cliente."'".$lx; 
					if ($i<=$ttanos) { $sql .=" union "; } else {$sql .=" order by h_data desc, h_hora desc ";}
				}
				/* Starter da pï¿½gina */
				/*Carrega o total de historicos pesquisados, para ser utilizado como limite nos laï¿½os*/
				$rlt = db_query($sql2);
				$line=db_read($rlt);
				$tth=$this->tthistoricos = $line['count'];
				$ln=0;	
				$r=0;
				/*atributo que altera quantidade de linhas da tabela de historico*/
				$ttln=$this->ttln;	
					
				 /*Carrega os atributos dos historicos*/
				$sql3 =$sql.'limit '.($this->ttln).' offset '.(($pag-1)*$this->ttln);	
				$ln=$ttln+$ln;
				$rlt = db_query($sql3);

				 /*Carrega historicos*/
				$sx = 	'<table border="0"  class="lt0"  width="100%" align="center" cellpadding="5" cellspacing="1" bgcolor="">
							<TR bgcolor="Silver">
							<TH width="1%" > Núm </TH>
							<TH width="100" >Data</TH>
							<TH width="60%" >Texto</TH>
							<TH width="4%" >Tipo</TH>
							<TH width="4%" >Log</TH>
							<TH width="10%">Loja</TH>
							</TR>';
							
					while($line=db_read($rlt)){
						++$r;
												
							$sx .= '<TR>
									<TD width="2%"  border="0" align="center" ><TT>['.$r.']</td>
									<TD width="5%"  border="0" align="center" ><TT><nobr>'.stodbr($line['h_data']).' '.$line['h_hora'].'</td>
		     						<TD border="0" ><TT>'.ucfirst(strtolower($line['h_texto'])).'</td>
		     						<TD border="0" align="center" ><TT>'.$line['h_tipo'].'</td>
		     						<TD border="0" align="center" ><TT>'.$line['h_log'].'</td>
		     						<TD border="0" align="center" ><TT>'.$this->nome_da_loja($line['h_loja']).'</td></tr>';
		     						}

					$sx .=  '<TR><TD colspan="5"><HR></TD></TR> </table>';
			return($sx);
		}
		
		function setlojasid(){
			$sx = array('J','M','O','U','S','E','G');
			
			
			return($sx);
		}
		function setlojas(){
				
			$this->lojas[0]='Jóias';
			$this->lojas[1]='Modas';
			$this->lojas[2]='Óculos';
			$this->lojas[3]='Catálogo';
			$this->lojas[4]='Sensual';
			$this->lojas[5]='Express Modas';
			$this->lojas[6]='Express Jóias';
			
			return(1);
		}
		function pecas_quantidades(){
				global $ac, $ad, $ap,$az,$perfil;
				$lojas=$this->setlojasid();
				
				$this->setlojas();
				for ($i=0; $i <=$this->ttlojas ; $i++) { 
					
					$this->setconsignado($this->cliente, $this->lojas[$i]);
					
					$prev = $this->kh_previsao;
				if (($perfil->valid('#ADM#COB#COJ#COM#COO#COS#GEG#GEC#DIR#MST#REC#CCC#SSS'))){
		
					$link = '<A HREF="#" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$this->kh_cliente.'&dd1='.$lojas[$i].chr(39).',400,450);">';
				}
					if ($prev > date("Ymd"))
					{
						
						if(strlen($this->kh_pc_forn)==0){
							$az[$i]=0;
						}else{
							$az[$i]=1;
							$ac[$i] ='<font color=blue>Ativo ('.$this->kh_pc_forn.' peças)'; 
							$ad[$i] = $link.stodbr($prev).'</font></A>';
						};	
						
					} else {
						if(strlen($this->kh_pc_forn)==0){
							$az[$i]=0;
						}else{
							$az[$i]=1;
						};	
						$ac[$i] ='<font color=red>Ativo ('.$this->kh_pc_forn.' peças)'; 
						$ad[$i] = $link.stodbr($prev).'</A>';
					}
					$ap[$i] = $ap[$i] + $this->kh_pc_forn;
				}
			return(1);
		}
		function pecas_retiradas(){
			global $ac, $ad, $ap, $lm, $vm, $perfil;
			$this->set_limite_valor();
			$this->pecas_quantidades();
			$lojas=$this->setlojasid();
			$sx = '<TABLE width="'.$tab_max.'" align="center" border="0">
					<TR class="lt0" align="center">';
			for ($i=0; $i <= $this->ttlojas; $i++) 
			{
				$line=$this->le_cliente_pecas();
				
				if($line>0)
				{
					$cliente = $line['cp_cliente'];
					$qtd_pecas = $line['cp_loja_'.($i+1)];
					$vlr_max = $line['cp_valor_'.($i+1)];
					 
				}
				 
		if (($perfil->valid('#ADM#COB#COJ#COM#COO#COS#GEG#GEC#DIR#MST#REC#CCC#SSS'))){
			    
				$link = '<A HREF="#" onclick="newxy2('.chr(39).'cliente_acerto_pecas_numero.php?dd0='.$cliente.'&dd1='.$qtd_pecas.'&dd2='.$vlr_max.'&dd3='.$i.chr(39).',400,450);">';
		}		
				$sx .= '<TD width="10%"><fieldset><legend>'.$this->lojas[$i].'</legend><center><font class="lt1">'.$ac[$i].'<BR>&nbsp;'.$ad[$i].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$link.$lm[$i].'&nbsp;peças(s)<BR>R$ '.$vm[$i].'</center></TD>';
				
			}
			$sx .= '</TABLE>';
			
			return($sx);
		}
		function le_cliente_pecas()
		{
			global $base_name,$base_host,$base_user;	
			require("../db_fghi_210.php");
			$sql = "select * from clientes_pecas ";
			$sql .= " where cp_cliente = '".$this->cliente."'";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					return($line);
				} else {
					$sql2 = "insert into clientes_pecas (
							  cp_cliente,cp_loja_1,cp_loja_2,
							  cp_loja_3,cp_loja_4,cp_loja_5,
							  cp_loja_6,cp_loja_7,cp_loja_8,
							  cp_loja_9,cp_lastupdate_1,cp_lastupdate_2,
							  cp_lastupdate_3,cp_lastupdate_4,cp_lastupdate_5,
							  cp_lastupdate_6,cp_lastupdate_7,cp_lastupdate_8,
							  cp_lastupdate_9,cp_valor_1,cp_valor_2,
							  cp_valor_3,cp_valor_4,cp_valor_5,
							  cp_valor_6,cp_valor_7,cp_valor_8,
							  cp_valor_9
							    )VALUES (
							  '".$this->cliente."',140,40,20,40,40,40,40,40,40,
							  ".date('Ymd').",".date('Ymd').",".date('Ymd').",".date('Ymd').",".date('Ymd').",".date('Ymd').",".date('Ymd').",".date('Ymd').",".date('Ymd').",
							  0,1040,800,0,0,0,0,0,0)";
					$rlt2 = db_query($sql2);
					
					$sql3 = "select * from clientes_pecas ";
					$sql3 .= " where cp_cliente = '".$this->cliente."'";
					$rlt3 = db_query($sql3);
					if ($line = db_read($rlt3)){
						return($line);
					}else {		
						return(0);
					}
				}
		}
		function grava_limite($cliente='',$pc='',$vlr='',$lj='')
		{
			global $base_name,$base_host,$base_user;	
			require("../db_fghi_210.php");
			if(isset($cliente))
			{
				$sql = "update clientes_pecas 
						set cp_loja_".($lj+1)."=".$pc.", cp_valor_".($lj+1)."=".$vlr."
						where cp_cliente='".$cliente."'
						";
				$rlt = db_query($sql);	
				return(1);
			}else{
				
				return(0);
			}			
					
			
		}
		/*Carrega os limites de peï¿½as e valores*/
		function set_limite_valor(){
			global $base_name,$base_host,$base_user,$lm, $vm;	
			require("../db_fghi_210.php");
			//$consultora->cliente;
			$lm = array(0,0,0,0,0,0,0,0,0,0,0);
			$vm = array(0,0,0,0,0,0,0,0,0,0,0);
			$sql = "select * from clientes_pecas where cp_cliente = '".$this->cliente."'" ;
			$rlt = db_query($sql);
			$dtm = DateAdd("m",-1,date("Ymd"));
	
			if ($line = db_read($rlt))
			{
				for ($i=0; $i <= $this->ttlojas; $i++) {
					$lm[$i] = $line['cp_loja_'.($i+1)];
					$vm[$i] = number_format($line['cp_valor_'.($i+1)],2,',','.');
					if ($line['cp_lastupdate_'.($i+1)] > $dtm) { $dtm = 0; }
				}
			}		
			$ano = date("Y");
			$mes = date("m")-1;
			if ($mes == 0) { $mes = 12; $ano--; }
			$mes = strzero($mes,2);	
			
			return(1);
		}
		/*Carrega o nï¿½mero de acertos;mï¿½dia dos acertos;acerto mï¿½ximo;peï¿½a retiradas;peï¿½as vendidas*/
		function vendas($loja){
			$this->setdb($loja);
			$sql = "select sum(kh_vlr_forn) as vlr_fornecido, sum(kh_vlr_vend) as vlr_vendido, count(*) as acertos,
						   avg(kh_pago) as media, max(kh_pago) as maximo, sum(kh_pc_forn) as pc_fornecido,
						   sum(kh_pc_vend) as pc_vendido 
					FROM kits_consignado
					where kh_status= 'B' and kh_cliente = '".$this->cliente."'";
			$rlt = db_query($sql);
			
			while ($line=db_read($rlt)) {
				
				$this->ace[$loja] = $line['acertos'];
				$this->med[$loja] = $line['media'];
				$this->maxi[$loja] = $line['maximo'];
				if ($this->media_lojas<$line['media']) { $this->media_lojas=$line['media'];}
				if ($this->maximo_lojas<$line['maximo']) { $this->maximo_lojas=$line['maximo'];}
				if ($line['pc_fornecido']!=0) {$this->pec[$loja] = ($line['pc_vendido']/$line['pc_fornecido'])*100;}
				if ($line['vlr_fornecido']!=0) {$this->ven[$loja] = ($line['vlr_vendido']/$line['vlr_fornecido'])*100;}
				if ($line['pc_vendido']!=0) {$this->bru[$loja] = $line['vlr_vendido']/$line['pc_vendido'];}
			}
			return(1);
			}
		/*Carrega os valores dos acertos por loja dos ultimos 12 meses*/
		function vendas_periodo($loja=''){
			
			for ($i=0; $i <=12 ; $i++) { $acerto_mes[$i][$loja]=0;}	
			$sql = "SELECT kh_acerto, kh_pago, kh_pc_forn,
						   kh_pc_vend, kh_vlr_forn, kh_vlr_vend
					FROM kits_consignado
					where kh_status= 'B' and kh_cliente = '".$this->cliente."' and kh_acerto >'".(date('Y')-1).date('m')."99'   
					order by kh_acerto desc";
			$rlt = db_query($sql);
			
			while ($line=db_read($rlt)) {
				 $mes = round(substr($line['kh_acerto'],4,2))	;
			 	 $this->acerto_mes[$mes][$loja] += $line['kh_pago'];
			}
			
		return(1);
		}	
		/*Gera grï¿½fico das lojas com os acertos dos ï¿½ltimos 12 meses*/
		function grafico_vendas(){
			$lj=$this->setlojasid();
			$heigh_max = 80;
			$sx = '<TABLE width="'.$tab_max.'" align="center" border="0">
					<TR class="lt0" align="center">';
			
			for ($i=0; $i <= $this->ttlojas; $i++) {
					
				$this->vendas($this->lojas[$i]); 
				$this->vendas_periodo($this->lojas[$i]);	
				if ($this->maximo_lojas > 0)
					{ $m = $heigh_max/$this->maximo_lojas; } else {
						$m = 0;
					}
				
				/* Logica */
				/* Regra s
				 * se valor > 2x $media normaliza no top
				 */
				$link = '<A HREF="javascript:newxy2(\'cliente_vendas_loja_detalhe.php?dd0='.$this->cliente.'&dd99='.$lj[$i].'\',640,480);">';
				
				$sx .=	'<TD width="5%"><fieldset><legend>'.$this->lojas[$i].'</legend><center><font class="lt1">
						<table class="lt1" border=0 cellpadding=0 cellspacing=0 >
						<tr valign="bottom">';
				 	
				$sht = $this->acerto_mes;
				for ($r=0 ;$r < 12;$r++)
					{
						$mes = $r+round(date('m'))+1;	;
						if ($mes>12) {$mes=$mes-12;}	
						
						$height = 0;
						$vlrr = $sht[$mes][$this->lojas[$i]];
						$vlr = $vlrr;
						if ($vlr > 300 ) { $vlr = 300; $img = "03"; }
						if ($vlr <= 300 ) { $img = "02"; }
						if ($vlr <= 150 ) { $img = "01"; }
						if ($vlr == 0 ) { $img = "01"; $height = 1; }
						else
							{ $height = round(($vlr/300)*$heigh_max); }
							
	
						$sx .= '<td height="80" width="3%">';
						$sx .= $link.'<img width="8" title = "Mês '.$mes.' - R$ '.$vlrr.'"
								src="../ico/barra_tp_'.$img.'.png"
								height="'.$height.'" width="10" border=1 
								class="img_01"></a>';
					}
				$sx .= '</table>
						<style>
						.img_01
							{ border: 1px solid #FFFFFF; }
						.img_01:hover
							{ border: 1px solid #000000; }
						</style>
				
						<table class="lt0" align="center">
						<tr align="center">
						<td>Acerto</td>
						<td>Média</td>
						<td>Máxima</td>
						</tr>
						<tr align="center">
						<td>'.$this->ace[$this->lojas[$i]].'</td>
						<td>'.number_format($this->med[$this->lojas[$i]],2,',','.').'</td>
						<td>'.number_format($this->maxi[$this->lojas[$i]],2,',','.').'</td>
						</tr>
						<tr align="center">
						<td>Peças</td>
						<td>Venda</td>
						<td>Bruto</td>
						</tr>
						<tr align="center">
						<td>'.number_format($this->pec[$this->lojas[$i]],0).'%</td>
						<td>'.number_format($this->ven[$this->lojas[$i]],0).'%</td>
						<td>'.number_format($this->bru[$this->lojas[$i]],2,',','.').'</td>
						</tr>
						</table>
						</TD>';
			
			}
				$sx .= '</TABLE>';
				return($sx);
		}
		function creditos_cliente(){
			global $base_name,$base_host,$base_user,$lm, $vm, $dd,$secu;
			require("../db_ecaixa.php");	
			$vlr = 0;
			$cliente=$this->cliente;
			$sql = "select * from credito_outros where ccard_cliente = '$cliente' and ccard_pago = 0 ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$vlr = $vlr + $line['ccard_valor'];	
				}
			if ($vlr < 0) { $vlr = 0; }
			
			$sx = '	<span id="credito_a" style="cursor: pointer;">
						<table class="tabela00" width="240">
							<TR><TD colspan=2 align="center" class="lt2"><B>Créditos</B>
							<TR class="lt0"><TD align="center" width="120">Valor Atual
							<TR><TD align="center" class="tabela01 lt2">'.number_format($vlr,2,',','.').'
            			</table>
            		</span>
					
					<script>
					$("#credito_a").click(function() {
						
					/* Ajax Inicial */
					var checkpost="'.checkpost($dd[0].$secu).'";
					$.ajax({
						type: "POST",
						url: "cons_ajax.php",
						data: {	 dd0:"'.$dd[0].'", dd1: "financeiro", dd2: "1", dd8:"cr", dd90: checkpost }
					}).done(function( data ) { $("#cons03").html( data );goto(\'#cons03\', this)}); });
																				
					</script>
					'; 
			
			return($sx);	
		}

	
		
}
?>