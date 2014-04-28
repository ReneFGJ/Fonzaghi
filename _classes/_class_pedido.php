<?php
require_once($include."sisdoc_email.php");

class pedido
	{
		var $id;
		var $pedido;
		var $line;
		var $line_item;
	    var $op_pedidos;
        var $ref;
        var $nota_tipo;
		
		var $frete;
		var $forma_pagamento;
		
		var $tabela = 'pedido';
		
		function row()
		{
			global $cdf,$cdm,$masc;
			
			$cdf = array('id_ped','ped_nrped','ped_nomefornecedor','ped_items','ped_valor','ped_data','ped_status');
			$cdm = array('ID','Pedido','Descrição','Itens','Valor','Data','Status');
			$masc = array('','#','','#','$R','#','#','','');
			return(True);
		}
		
		function pagamentos()
			{
				$pedido = $this->pedido;
				$sql = "select * from contas_pagar 
						where cr_pedido = '$pedido' 
						order by cr_venc
					";
				$rlt = db_query($sql);
            	$sx = '<TABLE class=lt1 width="100%" border="0" cellpadding="0" cellspacing="1">';
				$sx .= '<TR><TD>';
            		$sx .= '<fieldset><legend><B>Programação e Pagamentos</B></legend>';
				
				$sx .= '<table width="100%" border=1 cellpadding=2 cellspacing=0 class="tabela00 lt0">';
				$sx .= '<TR><TH>data<TH>descrição<th>parc.<TH>valor';
				$id = 0;
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$cor = '<font color="blak">';
						$status = $line['cr_status'];
						$prev = $line['cr_previsao'];
						
						if ($status=='A') { $cor = '<font color="blue">'; }
						if (($prev=='1') and ($status=='A')) { $cor = '<font color="orange">'; }
						if ($status=='B') { $cor = '<font color="#404040">'; }
						
						$tot = $tot + $line['cr_valor'];
						$sx .= '<TR>
								<TD align="center">'.$cor.stodbr($line['cr_venc']).'
								<TD>'.$cor.$line['cr_historico'].'
								<TD align="center">'.$cor.$line['cr_parcela'].'
								<TD align="right">'.$cor.number_format($line['cr_valor'],2).'
								<TD align="center">'.$cor.$line['cr_status'].' ';
					}
				if ($tot > 0)
					{
						$sx .= '<TR><TD align="right" colspan=5 ><B>total '.number_format($tot,2);
					}
				$sx .= '</table>';
				$sx .= '</fieldset>';
				$sx .= '</table>';
				return($sx);
			}
		
		function fmt_cnpj($fmt)
			{
				$fmt=sonumero($fmt);
				if ($fmt > 11)
					{
						$fmt = strzero($fmt,14);
						$fmt = substr($fmt,0,2).'.'.substr($fmt,2,3).'.'.substr($fmt,5,3).'/'.substr($fmt,8,4).'-'.substr($fmt,12,2);
					}
				return($fmt);
			}
		function fmt_cep($fmt)
			{
				$fmt = sonumero($fmt);
				if (strlen($fmt)==8)
					{
						return(' CEP: '.substr($fmt,0,2).'.'.substr($fmt,3,3).'-'.substr($fmt,5,3));
					}
				return('');
			}
			
		function mostra_pedido_totais($items,$pecas,$valor_total)
			{
				$line = $this->line;
				$xobs = trim(mst($line['ped_obs']));
				if (strlen($xobs) > 0)
					{
						$obs = '<fieldset><legend class="plt0"><B>observações</B></legend>';
						$obs .= $xobs;
						$obs .= '</fieldset>';
					}
				
				$sx = '';
				$sx .= '<fieldset><legend>Dados gerais</legend>';
				$sx .= '<table width="99%" class="plt0" align="center" border=0>';
				$sx .= '<TR class="plt0">
							<Td colspan=2>Forma pagamento
							<Td align="center">Data despacho
							<Td align="center">Frete
							<Td align="center">Itens
							<Td align="center">Peças
							<Td align="right">Valor Total';
				$sx .= '<TR class="plt1">
							<TD colspan=2 width="10%"><B><nobr>'.$this->line['fpt_descricao'].'</nobr></B>
							<TD width="10%"><B>'.stodbr($line['ped_previsao']).'</b>
							<TD align="center"><B>'.$this->line['ped_frete'].'</B>
							<TD align="center"><B>'.$items.'</B>
							<TD align="center"><B>'.$pecas.'</B>
							<TD align="right" class="plt2"><B>'.fmt($valor_total,2).'</B>';
				
				$sx .= '<TR class="plt0" valign="top">
							<TD colspan=10>Transportadora
						<TR class="plt1">
							<TD colspan=10><B>'.$line['ped_fretetransportadora'].'</B>';
				
				$sx .= '<TR class="plt0" valign="top">
								<Td width="120">Condições<BR><B><nobr>'.$this->line['fpv_descricao'].'</nobr></B>
								<TD colspan=6 width="75%">'.$obs;			
				
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);
				
			}			
			
		function mostra_pedido_lista_produto_item($line,$it,$tipo)
			{
				$sx .= '<TR class="plt1" 
							style="background-color: #E0E0E0;"							
							>';
				$sx .= '<TD align="center" style="border-top: 1px solid #000000;" >';
				$sx .= $it;
				$sx .= '<TD align="center" style="border-top: 1px solid #000000;" >';
				$sx .= trim($line['pedi_ref_forn']);				
				$sx .= '<TD style="border-top: 1px solid #000000;" >';
				$sx .= trim($line['pedi_descricao']);
				$sx .= '<TD align="center" style="border-top: 1px solid #000000;">';
				$sx .= fmt($line['pedi_quan'],0);
				$sx .= '<TD align="right" style="border-top: 1px solid #000000;">';
				$sx .= fmt($line['pedi_vlrunit'],2);
				$sx .= '<TD align="right" style="border-top: 1px solid #000000;">';
				$sx .= fmt($line['pedi_vlrunit'] * $line['pedi_quan'],2);
				if ($tipo == 2)
					{
					$sx .= '<TD align="right" style="border-top: 1px solid #000000;">';
					$sx .= fmt($line['pedi_preco2'],2);
					}
				
				$sx .= '<TR><TD><TD colspan=4 class="plt0">';
				$sx .= mst($line['pedi_obs']);
				return($sx);
			}
			
		function mostra_pedido_lista_produto($pedido,$tipo=0)
			{
				global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
				
				$sql = "select * from pedido_item
						where pedi_nrped = '$pedido' 
				";
				$rlt = db_query($sql);
				$sx = '<table width="99%" class="tabela00" align="center">';
				$sx .= '<TR><TH>it
							<TH>Ref.
							<TH>Descricao
							<TH>Quant.
							<TH>Vlr.Unit
							<TH>Vlt.Total
						';
				$vlr = 0;
				$it = 0;
				$pc = 0;
				while ($line = db_read($rlt))
					{
						$pc = $pc + $line['pedi_quan'];
						$it++;
						$vlr = $vlr + ($line['pedi_vlrunit'] * $line['pedi_quan']);
						$sx .= $this->mostra_pedido_lista_produto_item($line,$it,$tipo);
					}
				$sx .= '</table>';
				$sx .= $this->mostra_pedido_totais($it,$pc,$vlr,$this->pg);
				return($sx);
			}			
		function uso_interno()
			{
                $cp1 = trim($this->line['ped_valor']);
                $cp2 = trim($this->line['ped_nrnf']);
                $cp3 = trim($this->line['ped_nf_data']);
                $cp4 = trim($this->line['ped_nf_vlr']);
                $cp5 = trim($this->line['ped_nf_conf']);
                $cp6 = trim($this->line['ped_nf_conf_dt']);
                $cp7 = trim($this->line['ped_chegada']);
                $cp8 = trim($this->line['ped_valor']); 				
				$s .= '';
            	/* Uso Interno */
            	$link_conf = '&nbsp;';
            	$conf_data = '&nbsp;';
            	$conf_nf   = '_______________';
            	$conf_data_nf = '___/___/____';
            	$conf_vlr = '&nbsp;';
            	$conf_vlr_romaneio = '&nbsp;';
            	$conf_log = '&nbsp;';
	            
            	if (strlen($cp1) > 0) { $conf_vlr_romaneio = number_format($cp1,2); }
            	if (strlen($cp2) > 0) { $conf_nf = $cp2; }
            	if (strlen($cp3) > 0) { $data_nf = stodbr($cp3); }
            	if (strlen($cp4) > 0) { $valor_nf = number_format($cp4,2); }
            	if (strlen($cp5) > 0) { $conf_log = $cp5; }
            	if (strlen($cp6) > 0) { $conf_data_nf =stodbr($cp6); }
            	if (strlen($cp7) > 0) { $conf_data = stodbr($cp7); }
            	if (strlen($cp8) > 0) { $conf_vlr = number_format($cp8,2); }
            	$link_conf = '<A HREF="javascript:newxy(\'../ed_edit_pop.php?dd0='.$id_ped.'&dd99=pedido_fx\',400,400);">';
				
            	$s = '<TABLE class=lt1 width="350" border="0" cellpadding="0" cellspacing="1">';
				$s .= '<TR><TD>';
            		$s .= '<fieldset><legend><B>Uso Interno</B></legend>';
            		$s .= '<TABLE class=lt1 width="100%" border="0" cellpadding="0" cellspacing="1">';
            			$s .= '<TR><TD align=right wi>NºNF:</TD><TD><TT><B>';
            			$s .= ''.$link_conf.'';
            			$s .= $conf_nf;
            			$s .= '</TD>';
            			$s .= '<TD align=right>Data NF:</TD>';
            			$s .= '<TD><B><TT>'.$link_conf.'';
            			$s .= $data_nf;
            			$s .= '</TD></TR>';
            			$s .= '<TR><TD align=right>Valor NF:</TD>';
            			$s .= '<TD><B><TT>'.$link_conf.$valor_nf.'</TD>';
            			$s .= '<TD align=right>Vlr.Enviado</TD>';
            			$s .= '<TD><B><TT>'.$link_conf.$conf_vlr_romaneio.'</TD></TR>';
            			$s .= '<TR><TD align=right>Dt.Receb:</TD>';
            			$s .= '<TD><B><TT>'.$link_conf;
            			$s .= $conf_data;
            			$s .= '</TD><TD align=right>Conf.por:</TD>';
            			$s .= '<TD><B><TT>'.$link_conf.'';
            			$s .= ''.$conf_log;
            			$s .= '</TD></TR>';
            			$s .= '<TR><TD align=right>Dt.Conf.:</TD><TD><B>';
            			$s .= '<TT>'.$link_conf.$conf_data_nf .'</TD>';
            			$s .= '</TR></TABLE>';
            		$s .= '</fieldset></TD></TR></TABLE>';
				return($s);				
			}
		function mostra_empresa()
			{
				global $hd;
				$sx = '';
				$line = $this->line;
				
				$sx .= '<table width="98%" class="plt1">';
				$sx .= '<TR valign="top">
						<TD width="200">';
				$sx .= '<img src="http://www.fonzaghi.com.br/img/logo_pedido.jpg">';
				$sx .= '<TD class="plt1">';
				$sx .= '<B>'.$line['e_nome'].'</B>';
				$sx .= '<BR>'.$line['e_endereco'];
				$sx .= '<BR>'.trim($line['e_bairro']).',';
				$sx .= ' '.trim($line['e_cidade']).'-'.trim($line['e_uf']);
				$sx .= ' '.$this->fmt_cep($line['e_cep']);
				
				$sx .= '<BR>CNPJ: '.trim($line['e_cnpj']).',';
				$sx .= ' IE: '.trim($line['e_ie']).'';
				$sx .= '<BR>Fone/Fax: '.trim($line['e_fone']);
				
				$sx .= '<TD width="120" align="right" class="plt0">';
				$sx .= '<B>nº pedido</B>';
				$sx .= '<BR><font class="plt5">'.$line['ped_nrped'].'</font><BR>';
				$sx .= '<BR>'.stodbr(sonumero($line['ped_data']));
				$sx .= ', '.trim($line['ped_hora']);
				$sx .= '<BR><NOBR><B>'.$hd->mostra_nome_login(trim($line['ped_login'])).'</B>';
				$sx .= '</table>';
				return($sx);
			}

		function mostra_empresa_romaneio()
			{
				global $hd;
				$sx = '';
				$line = $this->line;
				
				$sx .= '<table width="98%" class="plt1">';
				$sx .= '<TR valign="top">';
				$sx .= '<TD class="plt1">';
				$sx .= '<B>'.$line['e_nome'].'</B>';
				$sx .= '<BR>'.$line['e_endereco'];
				$sx .= '<BR>'.trim($line['e_bairro']).',';
				$sx .= ' '.trim($line['e_cidade']).'-'.trim($line['e_uf']);
				$sx .= ' '.$this->fmt_cep($line['e_cep']);
				
				$sx .= '<BR>CNPJ: '.trim($line['e_cnpj']).',';
				$sx .= ' IE: '.trim($line['e_ie']).'';
				$sx .= '<BR>Fone/Fax: '.trim($line['e_fone']);
				
				$sx .= '<TD width="*" align="right" class="plt0">';
				$sx .= '<h1>Romaneio</h1>';
				
				$sx .= '<TD width="120" align="right" class="plt0">';
				$sx .= '<B>nº pedido</B>';
				$sx .= '<BR><font class="plt5">'.$line['ped_nrped'].'</font><BR>';
				$sx .= '<BR>'.stodbr(sonumero($line['ped_data']));
				$sx .= ', '.trim($line['ped_hora']);
				$sx .= '<BR><NOBR><B>'.$hd->mostra_nome_login(trim($line['ped_login'])).'</B>';
				$sx .= '</table>';
				return($sx);
			}
		
		function mostra_pedido($id=0,$nr='')
			{
				$sx .= $this->mostra_dados_fornecedor();
				$pedido_status = $this->line['ped_status'];
				
				if ($pedido_status == 'T')
				{
					echo '<FONT face="Tahoma" size="4"><CENTER>Pedido temporário, não finalizado</CENTER></FONT>';
					exit;
				}
				if ($pedido_status == 'X')
				{
					echo '<FONT face="Tahoma" size="6"><CENTER><FONT COLOR="RED">Pedido Cancelado</CENTER></FONT>';
				}				
				
				return($sx);
			}
		
         function mostra_dados_fornecedor()
		 	{
		 		$line = $this->line;
		 		$sx .= '<fieldset><legend>Dados do Fornecedor</legend>';
				$sx .= '<table class="ptabela00" width="98%" align="center" border=0>';
				$sx .= '<TR valign="top"><TD colspan=2>';
					$sx .= '<font class="plt2"><B>'.trim($line['fo_nomefantasia']).'</B></font>';
					$sx .= '<BR>';
					$sx .= '<font class="plt1">'.trim($line['fo_razaosocial']).'</font>';
					
				$sx .= '<TD colspan=2 rowspan=2 width="50%" align="right">
						<font class="plt0"><B>Contato</B><font>';
					$sx .= '<BR>'.trim($line['fo_contato']);
					$sx .= ' / Representante: '.trim($line['fo_representante']);
					$sx .= '<BR>';
					$sx .= 'Fone:'.trim($line['fo_fone']);
					$sx .= ', Cel.:'.trim($line['fo_celular']);
					$sx .= ', Fax:'.trim($line['fo_fax']);
										
					$sx .= '<BR>e-mail: '.trim($line['fo_email']);					
					
				$sx .= '<TR valign="top">
						<TD class="plt1" colspan=1  width="25%">
						<font class="plt0"><B>Endereço</B><font>';
					$sx .= '<BR>'.trim($line['fo_endereco']);
					$sx .= '<BR>'.trim($line['fo_bairro']);
					$sx .= ', '.trim($line['fo_cidade']);
					$sx .= '-'.trim($line['fo_estado']);
					$sx .= '<BR>'.$this->fmt_cep($line['fo_cep']); 
					
				$sx .= '<TD align="left" class="plt1" width="25%">
						<font class="plt0"><B>Dados de Faturamento</B><font>';
					$sx .= '<BR>CNPJ/CPF: <font class="plt1">';
					$sx .= $this->fmt_cnpj($line['fo_cgc']);
					$sx .= '</font>';
					$sx .= '<BR>';
					$sx .= 'IE: <font class="plt1">'.trim($line['fo_ie']).'</font>';
					
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);
		 	}
         /*Carrega boxlist*/	
        function lista_pedidos_option($lj='')
        {
     		global $base_name,$base_server,$base_host,$base_user;
			if (strlen($lj) > 0)
				{
				$wh = ' ped_empresa='.$lj.' and ';
				}
			require("../db_caixa_central.php");
            $sql = "select * from pedido 
            				where 
            					$wh 
            					ped_chegada notnull 
            					order by ped_nrped desc
            					limit 100
            					";
            $rlt = db_query($sql);
            $op = ' :Selecione o pedido';
         
            while ($line = db_read($rlt)) 
            {
                $pedido=trim($line['ped_nrped']);
                $op .= '&'.$pedido.':'.$pedido;
            }
            
            $this->op_pedidos=$op;
            return($op);
     
        }
        
		function lista_produto($pedido,$pag='')
			{
				global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
				$sql = "select * from pedido_item
						where pedi_nrped = '$pedido' 
				";
				$rlt = db_query($sql);
				$sx = '<table width="96%" class="tabela00" align="center">';
				while ($line = db_read($rlt))
					{
						$sx .= $this->lista_item($line,$pag);
					}
				$sx .= '</table>';
				return($sx);
			}
		function mostra_item()
			{
				$line = $this->line_item;
				$sx .= '<table width="100%" class="tabela00" align="left">';
				$sx .= '<TR><TD width="60%">Descrição: <B>'.$line['pedi_descricao'].'</B>';
				$sx .= '<TD width="40%">Quantidade: <B>'.$line['pedi_quan'].'</B>';				
				$sx .= '<TR><TD>Ref.Loja: <B>'.$line['pedi_ref_item'].'</B>';
				$sx .= '<TD>Preço fornecedor: <B>'.number_format($line['pedi_vlrunit'],2,',','.').'</B>';
				$sx .= '<TR><TD>Ref.Fornecedor: <B>'.$line['pedi_ref_forn'].'</B>';
				$sx .= '<TD>Preço total: <B>'.number_format($line['pedi_vlrunit']*$line['pedi_quan'],2,',','.').'</B>';
				$sx .= '<TR><TD>NR.Doc.: <B>'.$line['pedi_nrdoc'].'</B>';
				$sx .= '<TD>Preço Marcado Individual: <B>'.number_format($line['pedi_preco2'],2,',','.').'</B>';
				$sx .= '<TR><TD>Observação: <B>'.mst($line['pedi_obs']).'</B>';
				$sx .= '<TD>Tipo: <B>'.$line['pedi_tipo'].'</B>';
				$sx .= '</table>';
				return($sx);
			}
		function lista_item_header()
			{
				$sx = '<TR>';
				$sx .= '<TH>Ref.Forn.
						<TH>Ref.Interna
						<TH>Descrição
						<TH>Quant.
						<TH>Vlr.Unit.
						<TH>Vlr.Total
						<TH>Custo
						<TH>Entradas';
				return($sx);
			}
		function lista_item($line,$pag='')
			{
				$pedido = trim($line['pedi_nrped']);
				$it = $line['pedi_quan'];
				$pr = $line['pedi_proc'];
				if ($it > $pr)
					{
						$link = '<A HREF="estoque_entrada_ref.php?dd0='.$line['id_pedi'].'&dd2='.$pedido.'&dd90='.checkpost($pedido).'" class="link">';
					} else {
						$link = '<font color="#009900">';
                        $link2='</font>';
					}
				$sx = '<TR>';
				$sx .= '<TD width="6%" class="tabela01">'.$link.$line['pedi_ref_item'].$link2.'</A>';
				$sx .= '<TD width="6%" class="tabela01">'.$link.$line['pedi_ref_forn'].$link2.'</A>';
				$sx .= '<TD width="54%" class="tabela01">'.$link.$line['pedi_descricao'].$link2.'</A>';
				$sx .= '<TD align="center" width="4%" class="tabela01">'.$link.number_format($line['pedi_quan'],0,',','.').$link2.'</A>';
				$sx .= '<TD align="right" width="8%" class="tabela01">'.$link.number_format($line['pedi_vlrunit'],2,',','.').$link2.'</A>';
				$sx .= '<TD align="right" width="10%" class="tabela01">'.$link.number_format($line['pedi_vlrunit'] * $line['pedi_quan'],2,',','.').$link2.'</A>';
				$sx .= '<TD align="right" width="8%" class="tabela01">'.$link.number_format($line['pedi_preco2'],2,',','.').$link2.'</A>';
				$sx .= '<TD width="4%" class="tabela01" align="center">'.$link.$line['pedi_proc'].$link2.'</A>';
				return($sx);
				
			}
		function le_item($id)
			{
				global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
				$sql = "select * from pedido_item where id_pedi = '".$id."'";
				$rlt = db_query($sql);
				$this->line = array();
				if ($line = db_read($rlt))
					{
						$this->pedido = $line['pedi_nrped'];
						$this->ref = $line['pedi_ref_item'];
						$this->line_item = $line;
						return(1);
					} else {
						return(0);
					}
			}
        function mostra_item2($pedido_nr)
            {
            	global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
                global $dd;
                $sql = "select * from pedido_item where pedi_nrped = '".$pedido_nr."'";
                $rlt = db_query($sql);
                $it = 0;
                $to = 0;
                while ($line = db_read($rlt))
                    {
                    $pedi_ref_item = trim($line['pedi_ref_item']);
                    $pedi_ref_forn = trim($line['pedi_ref_forn']);
                    $pedi_descricao = trim($line['pedi_descricao']);
                    $pedi_quan = trim($line['pedi_quan']);
                    $pedi_vlrunit = trim($line['pedi_vlrunit']);
                    $pedi_tipo = trim($line['pedi_tipo']);
                    $pedi_preco2 = trim($line['pedi_preco2']);
                    
                    $it = $it + 1;
                    $to = $to + ($pedi_vlrunit*$pedi_quan);
                    $pedi_obs = trim($line['pedi_obs']);
                    $pedi_opc = trim($line['pedi_opc']);
                    $pedi_nrdoc = trim($line['pedi_nrdoc']);
                    
                    $sx .= '<TR bgcolor="#FFFFFF"><TD height=15>&nbsp;';
                    $sx .= $pedi_ref_forn;
                    $sx .= '</TD><TD>&nbsp;';
                    $sx .= $pedi_ref_item;
                    $sx .= '</TD><TD><B>';
                    $sx .= $pedi_descricao; // nome do item
                    $sx .= '</TD><TD align=right>';
                    $sx .= number_format($pedi_quan,0); // preco unit
                    $sx .= '</TD><TD align=right>';
                    $sx .= number_format($pedi_vlrunit,2); // outro preco
                    $sx .= '</TD>';
                    $sx .= '<TD align=right>';
                    $sx .= number_format($pedi_vlrunit*$pedi_quan,2); // preco total
                    
                    if ($dd[99]=='pedido') 
                    {
                        $sx .= '</TD><TD align=right>';
                        $sx .= $pedi_tipo; // 0%   
                    }else{
                        $sx .= '<TD align=right><B>';
                        $sx .= number_format($pedi_preco2,2); // preco total
                    }
                    
                    $sx .= '</TD></TR>';
                    if (strlen($pedi_obs) > 0)
                        {
                            $sx .= '<TR class="lt0" valign="top"><TD colspan="2">&nbsp;</TD>';
                            $sx .= '<TD colspan="5">';
                            $sx .= troca($pedi_obs,chr(10),'<BR>');
                            $sx .= '</TD></TR>';
                        }
                    $sx .= '<TR bgcolor="#FFFFFF"><TD colspan=8  height=1><img src=../img/nada.jpg width=100% height=1 alt= border=0></TD></TR>';
                    }
                    $this->to=$to;
                return($sx);
            }
		function le_id($id)
			{
				global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
				 
				$sql = "select * from pedido
						inner join empresa on ped_empresa = id_e 
						left join fornecedores on ped_fornecedor = fo_codfor
						where id_ped = '".round($id)."'";
				$rlt = db_query($sql);
				$this->line = array();
				if ($line = db_read($rlt))
					{
						$this->pedido = $line['ped_nrped'];				
						$this->line = $line;
                        return(1);
					} else {
						return(0);
					}
			}


		function le($pedido)
			{
				global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
				 
				$sql = "select * from pedido
						inner join empresa on ped_empresa = id_e 
						left join fornecedores on ped_fornecedor = fo_codfor
						left join forma_paga_vezes on id_fpv = ped_fp_vezes
						left join forma_paga_tipo on id_fpt = ped_fp_tipo
						where ped_nrped = '".$pedido."'";
				$rlt = db_query($sql);
				$this->line = array();
				if ($line = db_read($rlt))
					{
						$this->pedido = $line['ped_nrped'];				
						$this->line = $line;
                        return(1);
					} else {
						return(0);
					}
			}
        
        function mostra_nota_romaneio($id_ped=0,$fornecedor='', $ped_nrped=''){
            $sx ='<script>
                    function open_romaneio() 
                    {
                        window.open("../pedidos/pedido_notas.php?dd95='.$id_ped.'&dd96='.$fornecedor.'&dd97='.$ped_nrped.'&dd99=romaneio","_self");
                    }
                  </script>';
            $sx .='<button type="button" onclick="open_romaneio()">Romaneio</button>';
            
            return($sx);
        }
        
        
         function mostra_nota_pedido($id_ped=0,$fornecedor='', $ped_nrped=''){
            $sx ='<script>
                    function open_pedido() 
                    {
                        window.open("../pedidos/pedido_notas.php?dd95='.$id_ped.'&dd96='.$fornecedor.'&dd97='.$ped_nrped.'&dd99=pedido","_self");
                    }
                  </script>';
            $sx .='<button type="button" onclick="open_pedido()">Pedido</button>';
            return($sx);
        }
        
        
        function forma_pagamento_tipo()
        {
        	global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
                
            $sql = "select * from forma_paga_tipo where id_fpt = ".$this->tp;
            $rlt = db_query($sql);
                if ($line = db_read($rlt))
                    {
                         $tp=$this->tp = $line['fpt_descricao'];
                    } 
            return($tp);    
            
        }

        function forma_pagamento_vezes()
        {
        		global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
                
                $sql = "select * from forma_paga_vezes where id_fpv = ".$this->fp;
                $rlt = db_query($sql);
                if ($line = db_read($rlt))
                    {
                        $fp= $this->fp = $line['fpv_descricao'];
                    } 
                    
            return($fp);    
            
        }
        
       /* Método - gera nota de pedidos
       * @var $id_ped integer    - id_ped da tabela pedido $dd[95]
       * @var $ped_nrped string    - ped_nrped da tabela pedido $dd[97]
       
       * @result bollean
       */
        
        function mostra_nota($id_ped,$ped_nrped)
        {
            global $dd;
				
				if(strlen($ped_nrped)==0)
				{
					$this->le_id($id_ped);
					$ped_nrped=$this->pedido;
				}
				
				$this->le($ped_nrped);                     
       
                $id_ped = $this->line['id_ped'];
                $pedido_nr = trim($this->line['ped_nrped']);
                $empresa = $this->line['ped_empresa'];
                $fornecedor = $this->line['ped_fornecedor'];
                $ped_prev = $this->line['ped_dtentrega'];
                $ped_preve = substr($ped_preve,8,2).'/'.substr($ped_preve,5,2).'/'.substr($ped_preve,0,4);
                $cp1 = trim($this->line['ped_valor']);
                $cp2 = trim($this->line['ped_nrnf']);
                $cp3 = trim($this->line['ped_nf_data']);
                $cp4 = trim($this->line['ped_nf_vlr']);
                $cp5 = trim($this->line['ped_nf_conf']);
                $cp6 = trim($this->line['ped_nf_conf_dt']);
                $cp7 = trim($this->line['ped_chegada']);
                $cp8 = trim($this->line['ped_valor']);    

                $this->fp = $this->line['ped_fp_vezes'];
                $this->tp = $this->line['ped_fp_tipo'];
                //$this->tp = $this->forma_pagamento_tipo();
                //$this->fp = $this->forma_pagamento_vezes();
                
            
                if ($this->line['ped_status'] == 'T')
                    {
                    echo '<FONT face="Tahoma" size="4"><CENTER>Pedido temporário, não finalizado</CENTER></FONT>';
                    exit;
                    }
                if ($this->line['ped_status'] == 'X')
                    {
                    echo '<FONT face="Tahoma" size="6"><CENTER><FONT COLOR="RED">Pedido Cancelado</CENTER></FONT>';
                    }
                    
                    $sql = "select * from empresa where id_e = 0".$empresa;
                    $rlt = db_query($sql);
                    if ($line = db_read($rlt))
                        {
                        $empresa_nome =trim($line['e_nome']);
                        $empresa_ende =trim($line['e_endereco']);
                        $empresa_ende .='<BR>'.trim($line['e_bairro']).', '.trim($line['e_cidade']).'&nbsp;&nbsp;CEP: '.trim($line['e_cep']);
                        $empresa_ende .='<BR>CNPJ:'.trim($line['e_cnpj']).'&nbsp;&nbsp;I.E.:'.trim($line['e_ie']);
                        $empresa_fone ='Fone: '.trim($line['e_fone']).' / '.trim($line['e_email']).' '; 
                        }
                        
            //////////////////////////// Fornecedor / Cliente
            
            $forn = new fornecedor; 
            $forn->le($fornecedor);     
                
            if (strlen($fornecedor) > 0)
                {
                	
                   $fornecedor=substr(('00000'.trim($fornecedor)),-7);  
                   $forn = new fornecedor; 
                   $forn->le($fornecedor);     
                
                    $fornecedor_nome = trim($forn->line['fo_nomefantasia']);
                    $fornecedor_razao = trim($forn->line['fo_razaosocial']);
                    $fo_cep = trim($forn->line['fo_cep']);
                    $fo_endereco = trim($forn->line['fo_endereco']);
                    $fo_complemento = trim($forn->line['fo_complemento']);
                    $fo_estado = trim($forn->line['fo_estado']);
                    $fo_cgc = trim($forn->line['fo_cgc']);
                    $fo_ie = trim($forn->line['fo_ie']);
                    $fo_fone = trim($forn->line['fo_fone']);
                    $fo_fax = trim($forn->line['fo_fax']);
                    $fo_celular = trim($forn->line['fo_celular']);
                    $fo_fone_ramal = trim($forn->line['fo_fone_ramal']);
                    $fo_contato = trim($forn->line['fo_contato']);
                    $fo_representante = trim($forn->line['fo_representante']);
                    $fo_cidade = trim($forn->line['fo_cidade']);
                    $fo_bairro = trim($forn->line['fo_bairro']);
                    $fo_tipopessoa = trim($forn->line['fo_tipopessoa']);
                   
                }
         
            //////////////////////////// Dados do pedido
            $s = '<table class="noprint"><tr><td>'.$this->mostra_nota_romaneio($id_ped,$fornecedor, $ped_nrped).'</td>
                             <td>'.$this->mostra_nota_pedido($id_ped,$fornecedor, $ped_nrped).'</td>
                             <td>'.emailcab("../pedidos/pedido_notas.php?dd95=$id_ped&dd96=$fornecedor&dd97=$pedido_nr&dd99=$dd[99]").'</td>
                             <td><b>Nota de '.$dd[99].'</td>
                             </tr></table>';

            $s .= '<TABLE width="100%" class="lt1" border="0">';
            $s .= '<TR valign="top"><TD rowspan="2">';
            $s .= '<img src="http://www.fonzaghi.com.br/img/logo_pedido.jpg" width="160" height="79" alt="" border="0"></TD>';
            $s .= '<TD class="lt1"><B>'.$empresa_nome.'</B>';
            $s .= '<BR>'.$empresa_ende;
            $s .= '<BR>'.$empresa_fone;
            $s .= '</TD>';
            $s .= '<TD width="20" align="right">';
            $s .= '<fieldset><legend><B>nº pedido</B></legend>';
            $s .= '<CENTER><FONT SIZE=6>&nbsp;'.$pedido_nr.'&nbsp;</FONT></CENTER></fieldset>';
            $s .= '<TR class="lt0"><TD colspan="2" align="right">';
            $s .= '<DIV align=right><NOBR>dt/ped. '.$this->line['ped_data'].' '.$this->line['ped_hora'];
            $s .= ' - '.$this->line['ped_login'].'</DIV></TD></TR></TABLE>';
            $s .= $forn->mostra_fornecedor_notas($fornecedor);
            //////////////////// parte IV
            $s .= '<TABLE width=94% cellpadding=1 cellspacing=0 class=lt1 border=0 align="center">';
            if ($dd[99]=='pedido') {
                $s .= '<TR><TD colspan="4">Para consultar pagamentos acesse <B>http://www.fonzaghi.com.br/pedido.php</B> e informe o código <B>'.substr(md5($pedido_nr.$secu),5,6).'</B></TD></TR>';
            }
            
            $s .= '<TR><TD class="lt1" width="15%" align=right ><NOBR>Forma pagamento<B></TD>';
            $s .= '<TD class="lt3" ><B>'.$this->fp.'</TD>';
            $s .= '<TD align=right class=lt1 width="15%"><NOBR>Tipo Pagamento</TD>';
            $s .= '<TD class="lt3"><B>'.$this->tp;
            $s .= '</TABLE>';
            
            ////////////////// Items do Pedido (CAB)
            $s .= '<TABLE width=97% cellpadding=1 cellspacing=0 class=lt1 border=0 align="center">';
            $s .= '<TR>';
            $s .= '<TD colspan=3><fieldset><legend><B>Items do Pedido</B></legend>';
            $s .= '<TABLE width=100% cellpadding=0 cellspacing=1  class=lt1 border=0  height=400>';
            $s .= '<TR>';
            $s .= '<TD height=15><FONT COLOR=#808080>ref.</TD>';
            $s .= '<TD><font color=#808080>ref.forn</TD>';
            $s .= '<TD><FONT COLOR=#808080>descricao</TD>';
            $s .= '<TD align=right><FONT COLOR=#808080>quant.</TD>';
            $s .= '<TD align=right><FONT COLOR=#808080>preço unit.</TD>';
            $s .= '<TD align=right><FONT COLOR=#808080>sub-total</TD>';
            $s .= '<TD align=right><FONT COLOR=#808080></TD>';
            $s .= '</TR>';
            $s .= '<TR><TD colspan=8 bgcolor="#FFFFFF" height=2><img src=../img/nada.jpg width=100% height=1 alt= border=0></TD></TR>';
            ////////////// Descrevendo Itens
           
            $s .= $this->mostra_item2($pedido_nr);
            $s .= '<TR><TD colspan=8 bgcolor=#FFFFFF height=99%></TD></TR>';
            $s .= '<TR><TD colspan=5 height=15 align=right>Total Geral</TD>';
            $s .= '<TD align=right><B>';
            $s .= number_format($this->to,2);
            $s .= '</B></TD></TR><TR><TD height=*></TD></TR>';
            $s .= '</TABLE>';
            $s .= '</fieldset>';
            $s .= '</TR>';
            $s .= '<TR>';
            $s .= '<TD colspan=3>';
            $s .= '<fieldset><legend><B>Dados Finais</B></legend>';
            $s .= '<TABLE width=100% cellpadding=0 cellspacing=1  class=lt1 border=0  height=100 align="center">';
            $s .= '<TR valign=top>';
            $s .= '<TD height=15 colspan=3>Observações</TD>';
            $s .= '<TD>&nbsp;</TD>';
            $s .= '<TD width=120 align=right>Total do Pedido</TD>';
            $s .= '</TR>';
            $s .= '<TR valign=top>';
            if ($dd[99]=='pedido'){
                $s .= '<TD width=78% colspan=3 rowspan=3 height=15  bgcolor=#FFFFFF class="lt3" >';
                $s .= '<B>'.mst($this->line['ped_obs']).'</B>';
                $s .= '</TD><TD width="1%">&nbsp;</TD>';
                $s .= '<TD width=20% bgcolor=#c0c0c0 align=right>';
                $s .= '<FONT SIZE=2>'.number_format($this->to,2).'&nbsp;</B></TD>';
                $s .= '</TR>';
                $s .= '<TR><TD width=1>&nbsp;</TD>';
                $s .= '<TD align=right>Despachar em:</TD>';
            } else {
                $s .= '<TD width=78% colspan=3 rowspan=3 height=15  bgcolor=#c0c0c0 >';
                $s .= '<B>'.mst($this->line['ped_obs']).'</B>';
                $s .= '</TD><TD width="1%">&nbsp;</TD>';
                $s .= '<TD width=20% bgcolor=#c0c0c0 align=right>';
                $s .= '<FONT SIZE=2>'.number_format($this->to,2).'&nbsp;</B></TD>';
                $s .= '</TR>';
                $s .= '<TR><TD width=1>&nbsp;</TD>';
                $s .= '<TD align=right>Entrega em:</TD>';
            }

            $s .= '</TR>';
            $s .= '<TR><TD width=1>&nbsp;</TD>';
            $s .= '<TD width=20% bgcolor=#c0c0c0 align=right>';
            $s .= '<B><FONT SIZE=5>'.stodbr($this->line['ped_previsao']).'</B></TD></TR>';
            $s .= '<TR><TD align=left colspan=3></TD></TR>';
            $s .= '</TABLE>';
            $s .= '</TD></TR></TD></TR></TABLE>';
            //////////////////////////////////// conferencia
            $s .= '<TABLE class=lt1 width="98%" border=0 cellpadding=0 cellspacing=0  align="center">';
            $s .= '<TR valign="top"><TD width="65%">';
            
            
            $s .= $this->uso_interno();
            
            if (emailfoot($s,$dd[80],'Pedido de compra'))
                {
                    $sx='Fim do envio';
                    return($sx);
                } else {
                    $sx=$s;
                    return($sx);
                }
                            
                        
                }       

    function lista_pedidos()
        {
           global $base_name,$base_server,$base_host,$base_user;
		   require("../db_caixa_central.php");
            
            $sql = "select  cr_pedido, 
                            pedido.ped_valortotal as vlr_ped, 
                            sum(cr_valor_original) as vlr_fin,
                            (pedido.ped_valortotal-sum(cr_valor_original)) as diferenca 
                    from contas_pagar 
                    left join pedido on cr_pedido=ped_nrped 
                    where pedido.ped_status='F' and
                          cr_status!='X'  
                    group by cr_pedido,pedido.ped_valortotal 
                    order by cr_pedido ";

            $rlt = db_query($sql);
            
            $sx = '<center><table class=tabela01><tr>
                            <th>Pedido</th>
                            <th>Vlr Pedido</th>
                            <th>Vlr Pago</th>
                            <th>Diferença</th>
                            </tr>';
            while ($line= db_read($rlt)) 
            {
                if($line['diferenca']>-1&&$line['diferenca']>1){
                $sx.='<tr>
                     <td class=tabela01 align="center">'.$line['cr_pedido'].'</td>
                     <td class=tabela01 align="right">'.number_format($line['vlr_ped'],2).'</td>
                     <td class=tabela01 align="right">'.number_format($line['vlr_fin'],2).'</td>
                     <td class=tabela01 align="right">'.number_format($line['diferenca'],2).'</td>
                     </tr>';
                     $ttped+=$line['vlr_ped'];
                     $ttfin+=$line['vlr_fin'];
                     $ttdif+=$line['diferenca'];
                }            
            }
            $sx .='<tr>
                        <td>Total</td>
                        <td class=tabela01 align="right">'.number_format($ttped,2).'</td>
                        <td class=tabela01 align="right">'.number_format($ttfin,2).'</td>
                        <td class=tabela01 align="right">'.number_format($ttdif,2).'</td>
                        </tr>';            
            $sx .= '</table>';
            
            return($sx);    
        }   
        function lista_pedidos_fornecedor()
            {
            global $dd;
            global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
            $this->atualiza_pedidos_pagos();
            $codigo = trim(sonumero($dd[1]));
            $ano=$dd[2];
            if (strlen($ano)==0) {$ano=date('Y');}
            if (strlen($codigo)>0) 
            {
                $fx=" and pedido.ped_fornecedor='$codigo' ";   
            }
            
            
            $sql = "select  pedido.ped_fornecedor as forn,
                            cr_pedido, 
                            pedido.ped_valortotal as vlr_ped, 
                            sum(cr_valor_original) as vlr_fin,
                            (pedido.ped_valortotal-sum(cr_valor_original)) as diferenca 
                    from contas_pagar 
                    left join pedido on cr_pedido=ped_nrped 
                    where pedido.ped_status='F' and
                          cr_status!='X' and
                          pedido.ped_nf_conf!='' and
                          pedido.ped_data<='".$ano."1231' and
                          pedido.ped_data>='".$ano."0101'
                          ".$fx."  
                    group by cr_pedido,
                          pedido.ped_valortotal,
                          pedido.ped_fornecedor
                    order by pedido.ped_fornecedor ";
            $rlt = db_query($sql);
            $sx = '<center><div width="98%">
                   <table class=tabela00 width="98%"><tr>
                   <th class=tabela00 width="55%" align="center">Fornecedor</th>
                   <th class=tabela00 width="15%" align="right">Total Pedidos</th>
                   <th class=tabela00 width="15%" align="right">Total Pago</th>
                   <th class=tabela00 width="15%" align="right">Diferença</th>
                   </tr></table>';
            $forn_ant='';

            while ($line= db_read($rlt)) 
                {
                    
                if($line['diferenca']>-1&&$line['diferenca']>1)
                    {
                    if($line['forn']!=$forn_ant)
                        {
                        if ($ttped2!=0||$ttfin2!=0||$ttdif2!=0)
                            {
                            $forn = new fornecedor;
                            $forn->le3($forn_ant); 
                            $span1='<a'.$forn_ant.'>';    
                            $sx.='<div align="center">
                                  <table class=tabela01 width="98%">
                                  <tr>
                                  <td class=tabela00 width="55%" align="left" colspan="2" >'.$span1.$forn->fantasia.'</a'.$forn_ant.'></td>
                                  <td class=tabela00 width="15%" align="right">'.number_format($ttped2,2).'</td>
                                  <td class=tabela00 width="15%" align="right">'.number_format($ttfin2,2).'</td>
                                  <td class=tabela00 width="15%" align="right">'.$font.number_format($ttdif2,2).'</font></td>
                                  </tr>
                                  </table>
                                  </div>     
                                  '.chr(13).$tx.chr(13);
                            $js .= '$(document).ready(function()
                                        {
                                        $("a'.$forn_ant.'").click(function()
                                            {
                                            $("b'.$forn_ant.'").toggle();
                                            });
                                        });
                                   '.chr(13);
                            $ttped2=0;
                            $ttfin2=0;
                            $ttdif2=0;
                            $tx='';       
                            }
                        }
                    
                    if ($line['diferenca']<0) {$font='<font color="#RED">';}
                    if ($line['diferenca']>0) {$font='<font color="#BLUE">';}
                    $up = '<a HREF="../pedidos/pedido_notas.php?dd95='.substr(trim($line['cr_pedido']),0,5).'&dd96='.trim($line['forn']).'&dd97='.trim($line['cr_pedido']).'" target="_blank" ';
                    $up .= '">';

                    $tx .= '<b'.$line['forn'].' style="display: none;" ><table width="98%" >';
                    $tx.='<tr>
                          <td class=tabela02 align="center" width="30%">'.$font.$line['forn'].'</font></td>
                          <td class=tabela02 align="center" width="25%">'.$up.$font.$line['cr_pedido'].'</a></font></td>
                          <td class=tabela02 align="right" width="15%">'.$font.number_format($line['vlr_ped'],2).'</font></td>
                          <td class=tabela02 align="right" width="15%">'.$font.number_format($line['vlr_fin'],2).'</font></td>
                          <td class=tabela02 align="right" width="15%">'.$font.number_format($line['diferenca'],2).'</font></td>
                          </font></tr></table>
                          </b'.$line['forn'].'>
                         ';
                    $ttped2+=$line['vlr_ped'];
                    $ttfin2+=$line['vlr_fin'];
                    $ttdif2+=$line['diferenca'];
                    $ttped+=$line['vlr_ped'];
                    $ttfin+=$line['vlr_fin'];
                    $ttdif+=$line['diferenca'];
                    
               $forn_ant=$line['forn']; 
                    }
               }
               $sx .='<table width="98%" bgcolor="#FFFFFF"><tr>
                      <td colspan="2"  width="55%">Total</td>
                      <td align="right" width="15%">'.number_format($ttped,2).'</td>
                      <td align="right" width="15%">'.number_format($ttfin,2).'</td>
                      <td align="right" width="15%">'.number_format($ttdif,2).'</td>
                      </tr>';            
               $sx .= '</table>';
               $js1.='<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>';
               
               $sx .=$js1.'<script>'.$js.'</script>';  
               return($sx);
        }
        function atualiza_pedidos_pagos()
        {
            global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
            $sql="select  pedido.ped_validado as validado,
                          pedido.ped_fornecedor as forn,
                          cr_pedido, 
                          pedido.ped_valortotal as vlr_ped, 
                          sum(cr_valor_original) as vlr_fin,
                          (pedido.ped_valortotal-sum(cr_valor_original)) as diferenca 
                    from contas_pagar 
                    left join pedido on cr_pedido=ped_nrped 
                    where pedido.ped_status='F' and
                          cr_status!='X' and
                          pedido.ped_nf_conf!='' 
                    group by 
                          pedido.ped_validado,  
                          cr_pedido,
                          pedido.ped_valortotal,
                          pedido.ped_fornecedor
                    order by pedido.ped_fornecedor";
            $rlt=db_query($sql);
            while ($line=db_read($rlt)) {
                if (($line['diferenca']<1)&&($line['diferenca']>-1)&&($line['validado']!=1)) {
                    $sql2="UPDATE pedido
                            SET ped_validado='1'
                            WHERE ped_nrped='".$line['cr_pedido']."'";
                    $rlt2=db_query($sql2);        
                $sx .= "<br>".$line['cr_pedido'];
                $sx .= "-----------".number_format($line['diferenca'],2);    
                $sx .= "-----------atualizado.....";    
                }
            }
            if(strlen($sx)==0){$sx="Não há pedidos a serem atualizados!";}
            return($sx);
        }

}
?>
