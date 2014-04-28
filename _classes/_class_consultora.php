<?php
class consultora
	{
		
 /**
  * Consultora
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.41
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
	
	var $tabela = 'cadastro';
	
	function messagens($cliente='')
		{
			
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
		

	function le($id='')
		{
			global $base_name,$base_server,$base_host,$base_user;
			if (strlen($id) > 0) {$this->codigo = $id; }

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
						$ok = 1;
					} else {
						$ok = 0;
					}
				return($ok);				
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
				{ return('Senha original inválida'); }
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
		require("db_fghi_210.php");

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
			$img = '';
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
			$link .= "javascript:newxy2('/fonzaghi/cadastro/email.php?dd1=4702051',730,450);";
			$sx .= '<A HREF="#" ';
			$sx .= ' onclick="newxy2('.chr(39).'/fonzaghi/cadastro/email.php?dd1='.$this->codigo;
			$sx .= chr(39).',800,450);" class="botao">novo e-mail</A>';
						
			$sx .= '<HR><BR>';
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
			global $tab_max;
			$sta = $this->consultora_status($this->cl_status);
			$sx = '<BR><table  width="'.$tab_max.'" cellpadding="0" cellspacing="0" border="0" align="center"><TR><TD>'.chr(13);
			$sx .= '<table width="100%" class="lt2" align="center" border=0>'.chr(13);
			$sx .= '<TR valign="top"><TD rowspan="14" width="84"><img src="'.$this->foto().'" width="140"  alt="" border="0"></TD>'.chr(13);
			$sx .= '<TD class="lt4" colspan="4">'.$cp3.'<B>'.$this->nome.'</A></B> ('.$this->codigo.')</TD><TD width="1%">'.$link_cp1.'</TD></TR>'.chr(13);
			
			$sx .= '<TR class="lt0" width="60%">'.chr(13);
			/*
			 *  Mensagens
			 */
			$sx .= '<TD width="200" rowspan=8>';
			$sx .= $this->messagens();		
			
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
			$sx .= '		<TD align="right">Naturalidade</TD>'.chr(13);			
			$sx .= '		<TD><B>'.$this->cl_naturalidade.'</TD>'.chr(13);
		
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '		<TD align="right" colspan="1">Propaganda</TD>'.chr(13);
			$sx .= '		<TD colspan="1"><B>'.$this->cl_propaganda_1.''.chr(13);
			$sx .= ' / '.$this->cl_propaganda_2.'&nbsp;</TD>'.chr(13);
				
			$sx .= '	</table>'.chr(13);
			$sx .= '	</TD></TR>'.chr(13);
			$sx .= '	</table>'.chr(13);
			return($sx);			
		}
	}
?>