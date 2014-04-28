<?php
class consultora
	{
		
 /**
  * Consultora
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.13.45
  * @package Classe
  * @subpackage UC0028 - Classe de Interoperabilidade de dados
 */
 
 		
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
	VAR $line;
	var $line2;
	var $tabela = 'cadastro';
	var $include_class = '../';
	
	function update($cliente='')
		{
			if (strlen($cliente) > 0)
			{
			$sql = "update ".$this->tabela." set cl_last = '".date("Ymd")."'
					where cl_cliente = '".$cliente."' ";
			$rlt = db_query($sql);
			}
			return(1);
		}
	
	function link_consultora($cliente='')
		{
			$link = '<A HREF="../cons/cons.php?dd0='.$cliente.'&dd90='.checkpost($cliente).'" class="link" target="_new">';
			$link .= $cliente.'</A>';
			return($link);
		}
	
	function consultoras_loja()
		{
			$sql = "select kh_cliente from kits_consignado where kh_status = 'A' group by kh_cliente ";
			$rlt = db_query($sql);
			$cs = array();
			while ($line = db_read($rlt))
				{
					array_push($cs,trim($line['kh_cliente']));
				}
			return($cs);
		}
	
	function consultora_bairros($cons)
		{
			$sql = "select count(*) as total, pc_bairro, pc_cidade
					from cadastro_completo  ";
			$sql .= " where ";
			for ($r=0;$r < count($cons); $r++)
				{
					if ($r > 0) { $sql .= ' or '; }
					$sql .= " ( pc_codigo = '".$cons[$r]."') ";
				}
			$sql .= " group by pc_bairro, pc_cidade
						order by pc_cidade, total desc";
			$rlt = db_query($sql);
			$sx .= '<table width="90%" align="center" border=0><TR><TD>';
			$sx .= '<div class="tres_colunas">';
			$xcid = '';
			$tot = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$cid = trim($line['pc_cidade']);
					if ($xcid != $cid)
						{
							if ($tot1 > 0)
								{ $sx .= '<I>Sub-total '.$tot1.'</I><BR>';}
							$sx .= '<HR><B>'.$cid.'</B><BR>';
							$xcid = $cid;
							$tot1 = 0;
						}
					
					$bairro = lowercase(trim($line['pc_bairro']));
					$sx .= UpperCase(substr($bairro,0,1)).substr($bairro,1,strlen($bairro));
					$sx .= ' ('.$line['total'].')';
					$sx .= '<BR>';
					$tot = $tot + $line['total'];
					$tot1 = $tot1 + $line['total'];
				}
			if ($tot1 > 0)
				{ $sx .= '<I>Sub-total '.$tot1.'</I><BR>';}
			if ($tot1 > 0)
				{ $sx .= '<I><B>Total geral '.$tot.'</B></I><BR>';}				
								
			$sx .= '</div></table>';
			return($sx);
		}	
	
	function form_busca()
		{
			global $dd;
			$bt1 = 'localizar >>';
			$msg = 'Informe parte do nome da consultura ou seu cï¿½digo';
			$sx .= '
			<table align="center"><TR><TD>
			<form method="get" action="'.page().'">
 			<div id="search">
				'.$msg.'
				<input type="text" name="dd1" id="form_search" value="'.$dd[1].'">
				<BR>
				<input type="submit" name="dd50" id="form_button" value="'.$bt1.'">
			</div>
			</form>
			</td></tr></table>
			';
			return($sx);
		}	

	function resultado_mostra($page='')
		{
			global $dd;
			$codigo = trim(sonumero($dd[1]));
	
			/* busca pelo codigo */
			if (strlen($codigo)==7)
				{
					$sql = "select * from cadastro where cl_cliente = '".$codigo."' ";
					$sql .= " order by cl_cliente limit 3000 ";
					$rlt = db_query($sql);
					$sx .= $this->mostra_consultoras($rlt,$page);
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
					$sql .= " limit 3000";
					$rlt = db_query($sql);	
					
					$sx .= $this->mostra_consultoras($rlt,$page);				
				}	
			return($sx);		
		}
		
	function mostra_consultoras($rlt,$page='')
		{
			if (strlen($page)==0) { $page = 'cx_consultora.php'; }
			$sx = '<table width="100%" cellpadding=2 cellspacing=4>';
			$sx .= '<TR class="tabela01h"><TH  >Nome da Consultora<TH>Código<TH>Dt.Nasc.<TH>Tipo';
			while ($line = db_read($rlt))
				{
					$tipo = trim($line['cl_autorizada']);
					if (strlen($tipo)==0) { $tipo = 'titular'; }
					
					$link = '<A HREF="'.$page.'?dd0='.$line['cl_cliente'].'&ddx='.date("YmdHis").'">';
					$sx .= '<TR>';
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

	function exists_cliente($id)
		{
			global $base_name,$base_server,$base_host,$base_user;
			if (strlen($id) > 0) {$this->codigo = $id; }
			/* abre banco das consultoras */
			require("../db_fghi_206_cadastro.php");
			
				$cp .= '*';			
				$sql = "select $cp from cadastro where cl_cliente = '".$id."' ";
				$rlt = db_query($sql);				
				if ($line = db_read($rlt))
					{
						return(1);
					} else {
						return(0);
					}			
		}	
		
	function le($id='')
		{
			global $base_name,$base_server,$base_host,$base_user;
			if (strlen($id) > 0) {$this->codigo = $id; }
			/* abre banco das consultoras */
			require("../db_fghi_206_cadastro.php");
			
				$cp .= '*';			
				$sql = "select $cp from cadastro where cl_cliente = '".$id."' or id_cl = ".round($id);
				
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$this->codigo = $line['cl_cliente'];
						$this->foto = $line['cl_cliente'];
						$this->nome = $line['cl_nome'];
						$this->nasc = $line['cl_dtnascimento'];
						$this->cpf = $line['cl_cpf'];
						$this->dtcadastro = $line['cl_dtcadastro'];
						$this->equipe = $line['cl_clientep'];
						$this->cl_propaganda_1 = substr($line['cl_propaganda'],0,3);
						$this->cl_propaganda_2 = substr($line['cl_propaganda'],3,3);
						$this->cl_cidade = $line['cl_cidade'];
						$this->cl_rg = $line['cl_rg'];
						$this->cl_cep = $line['cl_cep'];
						$this->cl_nomepai = $line['cl_nomepai'];
						$this->cl_nomemae = $line['cl_nomemae'];
						$this->cl_status = $line['cl_status'];
						$this->cl_update = $line['cl_update'];
						$this->cl_senha = $line['cl_senha'];
						$this->cl_senha_lembrete = $line['cl_senha_lembrete'];
						$this->cl_senha_status = $line['cl_senha_status'];
						$this->cl_senha_update = $line['cl_senha_update'];
						$this->cl_clientepai = $line['cl_clientepai'];
  						$this->cl_rguf = $line['cl_rguf'];
  						$this->cl_tipo = $line['cl_tipo'];
  						$this->cl_equipe = $line['cl_equipe'];
  						$this->cl_autorizada = $line['cl_autorizada'];
  						$this->cl_status = $line['cl_status'];
  						$this->cl_nasc = $line['cl_nasc'];
  						$this->cl_naturalidade = $line['cl_naturalidade'];
  						$this->line = $line;						
						
						$ok = 1;
					} else {
						$ok = 0;
					}
				return($ok);				
		}
	function le_email($id='',$edit=0)
	{
		global $base_name,$base_server,$base_host,$base_user;
			
			require("../db_fghi_206_cadastro.php");
			
			if (strlen($id) > 0) {$this->codigo = $id; }
		
		
				$sql="select * from email 
						where e_cliente='".$this->codigo."' and
							  e_status='A'
						";
				$rlt = db_query($sql);
				
				$rlt = db_query($sql);
	
				$sx ='<table border="0"  class="lt0" width="100%" align="center" cellpadding="1" cellspacing="6" bgcolor="">
						<tr><th class="tabelaTH" align="left">E-mail(s)</th>';
					if($edit==1)
						 {	
							$sx .='<th align="center">Editar</th>';
						 }	
				$sx .='</tr>';
				while ($line = db_read($rlt))
				{
				
					$sx .= '<tr><td class="tabela00" align="left" >'.$this->mostra_email($line['e_mail']).'</td>';
					//$sx .= '<td class="tabela00" align="left" >'.$this->status_email($line['e_status']).'</td>';
					$onclick = '<a href="javascript:newxy2(\'email.php?dd0='.$line['id_e'].'&dd1='.$this->codigo.'\',640,480);">';
				 	$link = $onclick.'<IMG SRC="../img/icone_editar.gif"></a>';
					 if($edit==1)
					 {	
					 	$sx .= '<td align="center">'.$link.'</td>'; 
					 }
		 		 
				}
			$link ='<a href="javascript:newxy2(\'email.php?dd1='.$this->codigo.'\',640,480);">';	
			$sx .= '		<TR class="lt0">'.chr(13);
			$sx .= '		<TD colspan="1" align="center">'.$link.'::Novo E-mail::</a></TD>'.chr(13);
			$sx .='</table>';
		
		return($sx);
	}
	function mostra_email($email='')
	{
		global $perfil;
		if($perfil->valid('#TTT'))
		{
			return($email);
		}else{
			$sz = strlen(trim($email));
			$sx = substr($email,0,6).str_repeat('*',($sz-6));
		}
		return($sx);
	}
	function mostra_telefone($tel='')
	{
		global $perfil;
		if($perfil->valid('#TTT'))
		{
			return($tel);
		}else{
			$sz = strlen(trim($tel));
			if ($sz < 3) { $sz = 3; }
			$sx = str_repeat('*',($sz-3)).substr($tel,$sz-3,6);
			//$sx = $tel;
		}
		return($sx);
	}

	

	function le_email_ajax($id='')
	{
		global $base_name,$base_server,$base_host,$base_user,$ajax;
		require($this->include_class."db_fghi_206_cadastro.php");
			
		if (strlen($id) > 0) {$this->codigo = $id; }
		
		$aj = array();
		$sql="select * from email 
				where e_cliente='".$this->codigo."' and
					  e_status='A'
				";
		$rlt = db_query($sql);
		
		while ($line = db_read($rlt))
		{
			array_push($aj,array($line['e_mail'],$line['id_e']));
		}
		return($aj);
	}


	function status_email($st='')
	{
		switch ($st) {
			case 'A':
				return('Ativo');
				break;
			case 'I':
				return('Inativo');
				break;
		}
	}
	function le_endereco($id='')
		{
			global $base_name,$base_server,$base_host,$base_user;
			if (strlen($id) > 0) {$this->codigo = $id; }
			/* abre banco das consultoras */
			require("../db_fghi_206_cadastro.php");
			
				$cp .= '*';			
				$sql = "select $cp from cadastro_endereco 
						where (ce_cliente = '".$id."' or ce_cliente = '".round($id)."') and 
							   ce_ativo=1
						order by ce_data desc ,ce_hora
						limit 1								   
							   ";
				
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$this->ce_cliente = $line['ce_cliente'];
						$this->ce_data = $line['ce_data'];
						$this->ce_hora = $line['ce_hora'];
						$this->ce_log = $line['ce_log'];
						$this->ce_cidade = $line['ce_cidade'];
						$this->ce_estado = $line['ce_estado'];
						$this->ce_pais = $line['ce_pais'];
						$this->ce_bairro = $line['ce_bairro'];
						$this->ce_endereco = $line['ce_endereco'];
						$this->ce_ativo = $line['ce_ativo'];
						$this->ce_extinto = $line['ce_extinto'];
						$this->ce_extinto_log = $line['ce_extinto_log'];
						$this->ce_cep = $line['ce_cep'];
						$this->ce_cidade_cod = $line['ce_cidade_cod'];
					
						$ok = 1;
					} else {
						$ok = 0;
					}
					
			return($ok);		
		}

	function le_completo($id='')
		{
			global $base_name,$base_server,$base_host,$base_user;
			
			if (strlen($id) > 0) {$this->codigo = $id; }
			/* abre banco das consultoras */
			require("../db_fghi_206_cadastro.php");
			
				$cp .= '*';			
				$sql = "select $cp from cadastro_completo where pc_codigo = '".$id."' or pc_codigo = '".round($id)."'";
				
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$this->pc_codigo = $line['pc_codigo'];
						$this->pc_cpf = $line['pc_cpf'];
						$this->pc_nome = $line['pc_nome'];
						$this->pc_pai = $line['pc_pai'];
						$this->pc_rg = $line['pc_rg'];
						$this->pc_dt_nasc = $line['pc_dt_nasc'];
						$this->pc_naturalidade = $line['pc_naturalidade'];
						$this->pc_endereco = substr($line['pc_endereco'],0,3);
						$this->pc_bairro = substr($line['pc_bairro'],3,3);
						$this->pc_cidade = $line['pc_cidade'];
						$this->pc_estado = $line['pc_estado'];
						$this->pc_cep = $line['pc_cep'];
						$this->pc_empresa_trabalha = $line['pc_empresa_trabalha'];
						$this->pc_empresa_endereco = $line['pc_empresa_endereco'];
						$this->pc_funcao = $line['pc_funcao'];
						$this->pc_salario = $line['pc_salario'];
						$this->pc_estado_civil = $line['pc_estado_civil'];
						$this->pc_nome_conj = $line['pc_nome_conj'];
						$this->pc_dt_nasc_conj = $line['pc_dt_nasc_conj'];
						$this->pc_empresa_trabalha_conj = $line['pc_empresa_trabalha_conj'];
						$this->pc_funcao_conj = $line['pc_funcao_conj'];
						$this->pc_salario_conj = $line['pc_salario_conj'];
						$this->pc_resi_propria = $line['pc_resi_propria'];
						$this->pc_vlr_aluguel = $line['pc_vlr_aluguel'];
						$this->pc_fone1 = $line['pc_fone1'];
						$this->pc_fone2 = $line['pc_fone2'];
						$this->pc_obs = $line['pc_obs'];
						$this->pc_lista_codigo = $line['pc_lista_codigo'];
						$this->pc_log = $line['pc_log'];
						$this->pc_data_cadastro = $line['pc_data_cadastro'];
						$this->pc_hora_cadastro = $line['pc_hora_cadastro'];
						$this->pc_status = $line['pc_status'];
						$this->pc_fone_cliente = $line['pc_fone_cliente'];
						$this->pc_fone_conjuge = $line['pc_fone_conjuge'];
						$this->pc_propaganda_1 = $line['pc_propaganda_1'];
						$this->pc_propaganda_2 = $line['pc_propaganda_2'];
						$this->pc_empresa_fone = $line['pc_empresa_fone'];
						$this->pc_empresa_endereco_conj = $line['pc_empresa_endereco_conj'];
						$this->pc_empresa_fone_conj = $line['pc_empresa_fone_conj'];
						$this->pc_casa_obs = $line['pc_casa_obs'];
						$this->pc_analise = $line['pc_analise'];
						$this->pc_update = $line['pc_update'];
						$this->pc_autorizada = $line['pc_autorizada'];
						$this->pc_tipo = $line['pc_tipo'];
						$this->line2 = $line;						
						$ok = 1;
					} else {
						$ok = 0;
					}
				return($ok);				
		}		


	function busca_consultora()
			{
			global $base_host, $base_port, $base_name ,$base_user, $base_pass, $base, $conn,$dd,$acao;
			
			$form = new form;
			
			$cp = array();
			array_push($cp,array('$H8','','',False,False));
			array_push($cp,array('$S7','','Código consultora',True,False));
			
			$tela = $form->editar($cp,'');
			if ($form->saved > 0)
				{
					require("../db_fghi_206_cadastro.php");
					$this->le($dd[1]);
					$this->tela = $this->mostra_dados_pessoais();
					return(1);
				} else {
					$this->tela = $tela;
					return(0);
				}
				return(0);			
			}		
	
	function carrega_imagem($cliente='0000000')
		{
				
			$sql = "select * from capacitacao_participacao where cp_cliente='".$cliente."' and cp_staus='B'";
			$rlt = db_query($sql);
			$t=0;
			
			while ($line = db_read($rlt)) {
				
			switch (round($line['cp_curso'])) {
				case 'MKT PESSOAL':	
					$cr[0]='-g';
                    $this->dt[0]=$line['cp_data'];
					break;
				case 'ATD. CLIENTE':
					$cr[1]='-g';
                    $this->dt[1]=$line['cp_data'];
					break;
				case 'FINANÇAS PESSOAIS':
					$cr[2]='-g';
                    $this->dt[2]=$line['cp_data'];
					break;
				case 'PRODUTO':
					$cr[3]='-g';
                    $this->dt[3]=$line['cp_data'];
					break;
				case 'MOTIVAÇÃO':
					$cr[4]='-g';
                    $this->dt[4]=$line['cp_data'];
					break;
				default:
					
					break;
			}
			}
				
			return ($cr);
		
		}	
	
	function busca_cliente_sql($nome)
		{
			$nome = trim(UpperCaseSql($nome)).' ';
			$ca = array();
			while (strpos($nome,' ') > 0)
				{
					$pos = strpos($nome,' ');
					$nn = substr($nome,0,$pos);
					$nome = trim(substr($nome,$pos,strlen($nome))).' ';
					if (strlen($nn) > 0)
						{ array_push($ca,$nn); }
				}
			$fld = array('cl_cliente','cl_nome','cl_cpf');
			for ($ra=0;$ra < count($fld);$ra++)
				{
					if (strlen($sqlw) > 0) { $sqlw .= ' or '; }
					$sqlw .= '(';
					for ($rb=0;$rb < count($ca);$rb++)
						{
							if ($rb > 0) { $sqlw .= ' and '; }
							$sqlw .= $fld[$ra]." like '%".$ca[$rb]."%' ";	
						}
					$sqlw .= ')';
				}
			return($sqlw);
		}

	function foto_tirar()
		{
			$sx .= '<A HREF="#" ';
			$sx .= ' onclick="newxy2('.chr(39).'/cadastro/index.php?';
			$sx .= '?cpf='.sonumero($this->cpf);
			//$sx .= '&dd90='.checkpost($this->cpf);
			$sx .= chr(39).',800,450);" class="botao">nova fotografia</A>';
			return($sx);
		}

	function senha()
		{
			global $user_nivel;
			if ($user_nivel > 0)
			{
				$link = 'onclick="newxy2(';
				$link .= chr(39);
				$link .= 'consultora_altera_senha.php';
				$link .= '?dd1='.$this->codigo;
				$link .= '&dd90='.checkpost($this->codigo);
				$link .= chr(39);
				$link .= ',600,600);"';
			}
			if (strlen(trim($this->cl_senha_status))==0) { $this->gera_senha_padrao(); }
			$img = '../img/icone_password_1.png';
			if ($this->cl_senha_status == '@') { $img = '../img/icone_password_2.png'; }
			if ($this->cl_senha_status == 'A') { $img = '../img/icone_password_3.png'; }
			$alt = 'Lembrete: '.trim($this->cl_senha_lembrete);
			$sx = '<A HREF="#" '.$link.'><img src="'.$img.'" height=24 title="'.$alt.'" alt="'.$alt.'" border=0></A>';
			return($sx);
		}

	function senha_muda($old,$new,$lem)
		{
			if (trim($old) != trim($this->cl_senha))  
				{ return('Senha original invï¿½lida'); }
			$sql = "update cadastro set ";
			$sql .= " cl_senha = '".lowercasesql($new)."' ,";
			$sql .= " cl_senha_lembrete = '".lowercase($lem)."', ";
			$sql .= " cl_senha_update = ".date("Ymd")." ,";
			$sql .= " cl_senha_status = 'A' ";
			$sql .= " where cl_cliente = '".$this->codigo."' ";
			$rlt = db_query($sql);
			return('Senha alterada com sucesso!');
		}

	function gera_senha_padrao()
		{
			if (strlen($this->codigo) == 7)
				{
					$s = substr(sonumero($this->cpf),0,4);
					$sql = "update ".$this->tabela;
					$sql .= " set ";
					$sql .= " cl_senha = '".$s."', ";
					$sql .= " cl_senha_lembrete = 'Parte do CPF' ,";
					$sql .= " cl_senha_update = ".date("Ymd")." ,";
					$sql .= " cl_senha_status = '@' ";
					$sql .= " where cl_cliente = '".$this->codigo."' ";
					
					$rlt = db_query($sql);
					$this->cl_senha = $s;
					$this->cl_senha_lembrete = 'Parte do CPF';
					$this->cl_senha_status = '@';
				}
			return(1);
			
		}

	function consultora_debitos()
		{
		global $base_name,$base_host,$base_user;		
		require("../db_fghi_210.php");

		$cp = 'dp_valor as valor, dp_data as emissao, dp_venc as vencimento, dp_content as historico ';
		$sql = "select 'Joias' as loja, $cp from duplicata_joias where dp_status = 'A' and dp_cliente = '".$this->codigo."' ";
		$sql .= " union ";
		$sql .= "select 'Modas' as loja, $cp from duplicata_modas where dp_status = 'A' and dp_cliente = '".$this->codigo."' ";
		$sql .= " union ";
		$sql .= "select 'Catalogo' as loja, $cp from duplicata_usebrilhe where dp_status = 'A' and dp_cliente = '".$this->codigo."' ";
		$sql .= " union ";
		$sql .= "select 'Oculos' as loja, $cp from duplicata_oculos where dp_status = 'A' and dp_cliente = '".$this->codigo."' ";
		$sql .= " union ";
		$sql .= "select 'Sensual' as loja, $cp from duplicata_sensual where dp_status = 'A' and dp_cliente = '".$this->codigo."' ";
		$sql .= " union ";
		$sql .= "select 'Juridico' as loja, $cp from juridico_duplicata where dp_status = 'A' and dp_cliente = '".$this->codigo."' ";
		$sql .= " order by vencimento ";
		$crlt = db_query($sql);
		$erro = '000';
		$rst = array();
		$tot = 0;
		$it = 0;
		while ($line = db_read($crlt))
			{
			$str = '';
			$str .= trim($line['loja']);
			$str .= '|';
			$str .= stodbr($line['emissao']);
			$str .= '|';
			$str .= number_format($line['valor'],2);
			$str .= '|';
			$str .= stodbr($line['vencimento']);
			$str .= '|';
			$str .= trim($line['historico']);
			$tot = $tot + $line['valor'];
			$it++;
			array_push($rst,$str);
			array_push($rst,'Notas');
			}
		if ($it > 0)
			{
			$str = '<B><I>';
			$str .= 'Total';
			$str .= '|';
			$str .= '&nbsp;';
			$str .= '|<B><I>';
			$str .= number_format($tot,2);
			$str .= '|';
			$str .= '&nbsp;';
			$str .= '|<B><I>';
			$str .= $it.' notas abertas';
			array_push($rst,$str);
			array_push($rst,'Notas');
			}
		
		return(array($erro,$rst));
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
	
	function foto()
		{
			$cpf = sonumero($this->cpf);
			for ($r=0;$r < 30;$r++)
				{
					$imgf = '../img/clientes/img_'.sonumero($this->cpf).chr(97+$r).'.jpg';
					$imga = '../img/clientes/img_'.sonumero($this->cpf).chr(97+$r).'.jpg';
					
					if (file_exists($imgf))
						{ $img = $imga; }
				}
			if (strlen($img)==0)
				{ $img = '/fonzaghi/public/000/0000000.jpg'; }
			return($img);
		}

	function mostrar_email()
		{
		
			$sx .= '<A HREF="#" ';
			$sx .= ' onclick="newxy2(\'../cons/email.php?dd1='.$this->codigo.'\',600,400);" class="botao"><img src="../img/plus.png" width="16"></A>';
			return($sx);
		}

	function mostra_dados_pessoais_mini()
		{
			$sx .= '
			<div id="dados_mini">
			<center>foto
				<img src="'.$this->foto().'" width="140"  alt="" border="0">
			</center>
			</div><div id="dados_mini2">
				'.trim($this->nome).' ('.$this->codigo.')'.'
			</div>
			';			
			return($sx);
		}

	function mostra_dados_pessoais()
		{
			global $tab_max,$rel;
			
			$sta = $this->consultora_status($this->cl_status);
			$sx = '<BR><table  width="'.$tab_max.'" cellpadding="0" cellspacing="0" border="0" align="center"><TR><TD>'.chr(13);
			$sx .= '<table width="100%" class="lt2" align="center" border=0>'.chr(13);
			$sx .= '<TR valign="top"><TD rowspan="14" width="84"><div id="foto"><a href="javascript:newxy2(\'../cadastro/fotos/index.php?cpf='.$this->cpf.'\',730,450);"><img src="'.$this->foto().'" width="240"  alt="" border="0"></a></div></TD>'.chr(13);
			$sx .= '<TD class="lt4" colspan="4">'.$cp3.'<B>'.$this->nome.'</A></B> ('.$this->codigo.')</TD><TD width="1%">'.$link_cp1.'</TD></TR>'.chr(13);
			
			$sx .= '<TR class="lt0" width="60%">'.chr(13);
			/*
			 *  Mensagens
			 */
			$sx .= '<TD width="200" rowspan=8>';
			$sx .= '		<TD align="right">CPF:</TD>'.chr(13);
			$sx .= '		<TD width="30%" class="lt1"><B>'.$this->cpf.'</TD>'.chr(13);
			$sx .= '<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right">Cadastrado em:</TD>'.chr(13);
			$sx .= '		<TD>'.stodbr($this->dtcadastro).'</TD>'.chr(13);
			
			$sx .= '<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right">Status:</TD>'.chr(13);
			$sx .= '		<TD><B>'.$sta.'</TD>'.chr(13);
			
			$sx .= '<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right">Atualizado:</TD>'.chr(13);
			$sx .= '		<TD><B>'.stodbr($this->cl_update).'</TD>'.chr(13);
			
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right">RG:</TD>'.chr(13);
			$sx .= '		<TD><B>'.$this->cl_rg.'</TD>'.chr(13);
			
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right">Dt Nascimento:</TD>'.chr(13);
			$sx .= '		<TD><B>'.stodbr($this->nasc).'</TD>'.chr(13);
						
			$sx .= '	<TR class="lt0">'.chr(13);			
			$sx .= '		<TD align="right">Naturalidade:</TD>'.chr(13);			
			$sx .= '		<TD><B>'.$this->cl_naturalidade.'</TD>'.chr(13);
		
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right" >Propaganda:</TD>'.chr(13);
			$sx .= '		<TD ><B>'.$this->cl_propaganda_1.''.chr(13);
			$sx .= ' / '.$this->cl_propaganda_2.'&nbsp;</TD>'.chr(13);
			
			$tel=$this->le_telefones($this->codigo);
			$mail=$this->le_email();
			$link2=$this->mostrar_email();
			$tips=tips('<img src="../img/phone.png" width="32">','<div style="background-color:#FFFFFF"; height:350px;">'.$tel.'</div>');
			$tips2=tips('<img src="../img/email2.png" width="32">','<div style="background-color:#FFFFFF"; height:350px;">'.$mail.'</div>');
			$link1='<a href="javascript:newwin(\'telefone.php?dd1='.$this->codigo.'\',600,800);"><img src="../img/plus.png" width="16"></a>';
			
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '	<TD align="left"><b>'.$tips.$link1.' - '.$tips2.$link2.'</TD>'.chr(13);
			
			$sx .= '	</table>'.chr(13);
			$sx .= '	</TD></TR>'.chr(13);
			$sx .= '	</table>'.chr(13);
			return($sx);			
		}
	
	function mostra_dados_pessoais2()
		{
			global $tab_max, $relat;
			$td1=' width="30%" ';
			$td2=' width="70%" ';
			
			$sta = $this->consultora_status($this->cl_status);
			$sx = '<BR><table width="100%">
						<TR >
							<TD colspan="4" >'.chr(13);
			$sx .= '   		<table  valign="top" width="100%" class="lt2" align="center" border=0>'.chr(13);
			$sx .= '			<TR>';
			$sx .= '				<TD  class="lt4">'.$cp3.'<B>'.$this->nome.'</A></B> ('.$this->codigo.')</TD>
								</TR>'.chr(13);
			$sx .= '		</table>';			
			$sx .= '		</td>
						</tr>
						<tr>
							 <td align="center" valign="top">';
			
			$sx .= '		<table  width="100%">';
			
			$sx .= '		<TR class="lt0" '.$bg1.'>'.chr(13);
			$sx .= '		<TD '.$td1.'>Status   :</TD>'.chr(13);
			$sx .= '		<TD '.$td2.'>'.$sta .'</TD>'.chr(13);						
			
			$sx .= '		<TR class="lt0" '.$bg2.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>Cadastrado em   :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.stodbr($this->dtcadastro).'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg2.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>CPF  :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->cpf .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg1.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>RG :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->cl_rg .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg2.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>Nascimento :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.stodbr($this->nasc).'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg1.'>'.chr(13);			
			$sx .= '		<TD'.$td1.'>Naturalidade </TD>'.chr(13);			
			$sx .= '		<TD'.$td2.'>'.$this->cl_naturalidade .'</TD>'.chr(13);
		
			$sx .= '		<TR class="lt0" '.$bg2.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>Pai :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->cl_nomepai .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg1.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>Mãe  :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->cl_nomemae  .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0">'.chr(13);
			$sx .= '		<TD '.$td1.'>Propaganda</TD>'.chr(13);
			$sx .= '		<TD '.$td2.'><B>'.$this->cl_propaganda_1.''.chr(13);
			$sx .= ' / '.$this->cl_propaganda_2.'&nbsp;</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg1.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>Tipo     :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->cl_tipo     .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0" '.$bg2.'>'.chr(13);
			$sx .= '		<TD'.$td1.'>Autorizada      :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->cl_autorizada      .'</TD>'.chr(13);
			
			$sx .= '		</table>'.chr(13);
			$sx .= '	</td></tr>'.chr(13);
			$sx .= '	</table>'.chr(13);
			
			
			return($sx);			
	
		}		
		function mostra_dados_pessoais_completo($id)
		{
			global $ajax;	
			$ajax = new form_ajax;
			$ajax->vertical=1;
			$ajax->url='../email_atualiza.php';
			
			$sx .= '	<table   valign="top" width="'.$tab_max.'" cellpadding="0" cellspacing="0" border="0" align="center">'.chr(13);
			$sx .= '	<tr><td  valign="top" width="55%" height="100%">'.chr(13);
			$sx .= '	<fieldset><legend>Dados pessoais</legend>';
			$sx .= $this->mostra_dados_pessoais2();		
			$sx .= '	</fieldset>';
			
			$sx .= '	<fieldset><legend>Endereço</legend>';
			$sx .= $this->mostra_endereco();		
			$sx .= '	</fieldset>';
			
			
			$sx .= '	</td><td  valign="top" width="45%" >';
			
			$sx .= '	<fieldset><legend>Telefones</legend>';
			$sx .= $this->le_telefones($id);
			$sx .= '	</fieldset>';	


			$sx .= '	<fieldset><legend>E-mail</legend>';
			$sx .= $this->le_email($id,'1');
			$sx .= '	</fieldset>';	

			/*$aj = $this->le_email_ajax($id);
			$sx .= '	<fieldset><legend>E-mail</legend>';
			//$sx .= $ajax->mostrar($aj);
			$sx .= '	</fieldset>';	
			*/						
			$sx .= '	</td></tr>'.chr(13);
			$sx .= '	</table>'.chr(13);
				
			
			return($sx);
		}
		function le_telefones($id='',$edit='1')
		{
			global $base_name,$base_server,$base_host,$base_user;
			
			require("../db_fghi_206_cadastro.php");
			
			if (strlen($id) > 0) {$this->codigo = $id; }
		
		
				$sql = "select * from telefones_intra 
						left join parentesco on tel_parentesco=p_cod 
						left join telefone_tipo on tp_cod=tel_tipo
						where tel_ativo=1 and tel_cliente='".$this->codigo."'";
				
				$rlt = db_query($sql);
	
				$sx ='<table border="0"  class="lt0" width="'.$tab_max.'" align="center" cellpadding="1" cellspacing="6" bgcolor="">
						<tr>
						<th>Número</th>
						<th align="center">Compl.</th>
						<th>Atualizado</th>
						<th align="center">Parentesco</th>';
						 if($edit==1)
						 {	
							$sx .='<th align="center">Editar</th>';
						 }	
				$sx .='</tr>';
				
			while ($line = db_read($rlt)){
			
				$sx .= '<tr>
						 <td>('.$line['tel_ddd'].')'.$this->mostra_telefone($line['tel_fone']).'</td>  	
				 		 <td>'.$line['tp_nome'].'</td>
				 		 <td>'.stodbr($line['tel_atualizado']).'</td>
				 		 <td align="center">'.$line['p_nome'].'</td>'; 
						//		if (($tp == '1') and ($user_nivel > 7))
						//		{
								$onclick = 'onclick="newxy2(\'telefone.php?dd0='.$line['id_tel'].'&dd1='.$this->codigo.'\',600,400);"';
							 	$link3 = '<IMG SRC="../img/icone_editar.gif" '.$onclick.' >';
								 if($edit==1)
								 {	
								 	$sx .= '<TD align="center">';
								 	$sx .= $link3.'</td>'; 
								 }	
						//		}
				$sx .= '</tr>';			
	
				 		
			}
			$link1='<a href="javascript:newxy2(\'telefone.php?dd1='.$this->codigo.'\',640,480);">';	
		
			$sx .= '		<TR class="lt0">'.chr(13);
			$sx .= '		<TD colspan="4" align="center">'.$link1.'::Novo Telefone::</a></TD>'.chr(13);

				 
			$sx .='</table>';
	
			return($sx);				
		}

	
		
	function mostra_endereco(){
			global $tab_max;
			
			$this->le_endereco($this->codigo);
			$td1=' width="200" align="left"';
			$td2=' width="600" align="left"><b';
			
			$sta = $this->consultora_status($this->cl_status);
		
			$sx .= '		<table  align="center" valign="top" width="100%">'.chr(13);

			$sx .= '		<TR class="lt0">'.chr(13);
			$sx .= '		<TD'.$td1.'>Endereço </TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->ce_endereco .'</TD>'.chr(13);
	
			$sx .= '		<TR class="lt0">'.chr(13);			
			$sx .= '		<TD'.$td1.'>Bairro :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->ce_bairro .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0">'.chr(13);			
			$sx .= '		<TD'.$td1.'>Cidade :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->ce_cidade.'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0">'.chr(13);			
			$sx .= '		<TD'.$td1.'>Estado :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->ce_estado .'</TD>'.chr(13);
			
			$sx .= '		<TR class="lt0">'.chr(13);
			$sx .= '		<TD'.$td1.'>CEP :</TD>'.chr(13);
			$sx .= '		<TD'.$td2.'>'.$this->ce_cep.'</TD>'.chr(13);
			
			$link1='<a href="javascript:newxy2(\'cadastro_endereco_novo.php?dd1='.$this->codigo.'\',640,480);">';	
			
			$sx .= '		<TR class="lt0">'.chr(13);
			$sx .= '		<TD colspan="2" align="center">'.$link1.'::Novo Endereço::</a></TD>'.chr(13);
			
			$sx .= '		</table>'.chr(13);
			
			
		return($sx);
	}

	function icones_cursos(){
			
            
            $sx = '
	               	<table cellpadding=0 cellspacing=0>
    	           	<TR>        
               		<TR><div class="cursos"><img width="80" src="../ico/mk'.$cr[0].'.png" /></div></TR>
               		<TR><div class="cursos"><img width="80" src="../ico/at'.$cr[1].'.png" /></div></TR>
               		<TR><div class="cursos"><img width="80" src="../ico/fp'.$cr[2].'.png" /></div></TR>
               		<TR><div class="cursos"><img width="80" src="../ico/ps'.$cr[3].'.png" /></div></TR>
               		<TR><div class="cursos"><img width="80" src="../ico/mot'.$cr[4].'.png" /></div></TR>
               		</TR>
               		</table>
               		';
		    return($sx);
			
		}
		
	function pecas_retiradas(){
			
			

			$sx = '<TABLE width="<?=$tab_max;?>" align="center" border="0">
					<TR class="lt0" align="center">
					<TD width="20%"><fieldset><legend>Joias</legend><center><font class="lt2">'.$ac[0].'<BR>&nbsp;'.$ad[0].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[0].'&nbsp;peças(s)<BR>R$ '.$vm[0].'</center></TD>
					<TD width="20%"><fieldset><legend>Modas</legend><center><font class="lt2">'.$ac[1].'<BR>&nbsp;'.$ad[1].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[1].'&nbsp;peças(s)<BR>R$ '.$vm[1].'</center></TD>
					<TD width="20%"><fieldset><legend>Óculos</legend><center><font class="lt2">'.$ac[2].'<BR>&nbsp;'.$ad[2].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[2].'&nbsp;peças(s)<BR>R$ '.$vm[2].'</center></TD>
					<TD width="20%"><fieldset><legend>Catálogo</legend><center><font class="lt2">'.$ac[3].'<BR>&nbsp;'.$ad[3].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[3].'&nbsp;peças(s)<BR>R$ '.$vm[3].'</center></TD>
					<TD width="20%"><fieldset><legend>Sensual</legend><center><font class="lt2">'.$ac[4].'<BR>&nbsp;<?'.$ad[4].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[4].'&nbsp;peças(s)<BR>R$ '.$vm[4].'</center></TD>
					<TD width="20%"><fieldset><legend>Express Modas</legend><center><font class="lt2">'.$ac[5].'<BR>&nbsp;'.$ad[5].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[5].'&nbsp;peças(s)<BR>R$ '.$vm[5].'</center></TD>
					<TD width="20%"><fieldset><legend>Express Joias</legend><center><font class="lt2">'.$ac[6].'<BR>&nbsp;'.$ad[6].'&nbsp;<BR><fonts class="lt0">limite&nbsp;'.$lm[6].'&nbsp;peças(s)<BR>R$ '.$vm[6].'</center></TD>
					</TABLE>
					';

			
			return($sx);
		}
	function atualizar_email_auto($id='',$email='')
	{
		global $base_name,$base_server,$base_host,$base_user;
		require("db_fghi_206_cadastro.php");
		
		$sql = " select * from email where e_mail='".$email."'";
		$rlt = db_query($sql);
		if($line=db_read($rlt))
		{
			return('Já existe este e-mail no banco de dados');
			
		}else{
			$sql = "update email set e_mail='".$email."' where id_e=".$id;
			$rlt = db_query($sql);
			return('Atualizado');
		}
			 
		
	}
	
	function lista_aniversariantes($data)
	{
		$sql = "select * from cadastro 
				where (cl_dtnascimento-((round(cl_dtnascimento/10000))*10000))=".substr($data,-4)." and
				      cl_last>=".date("Ymd", mktime(0, 0, 0, (date("m")-3), date("d"), date("Y")))." 
				";
		$rlt = db_query($sql);
		$sx = '<table width="100%">';
		$sx .= '<tr><th class="tabelaTH">Código</th><th class="tabelaTH">Consultora</th></tr>';
		while($line = db_read($rlt))
		{
			$sx .= '<tr><td class="tabela01">'.$line['cl_cliente'].'</td><td class="tabela01">'.$line['cl_nome'].'</td></tr>';
		}
		$sx .="<table>";	
		return($sx);
	}
	

}
?>