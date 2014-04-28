<?
    /**
     * Consultora endereco
     * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package Classe
     * @subpackage Consultora endereco
    */
class consultora_endereco
	{
	var $id_ce;
	var $ce_cliente;
	var $ce_data;
	var $ce_hora;
	var $ce_log;
	var $ce_cidade;
	var $ce_estado;
	var $ce_pais;
	var $ce_bairro;
	var $ce_endereco;
	var $ce_ativo;
	var $ce_extinto;
	var $ce_extinto_log;
	var $ce_cep;
	var $ce_cidade_cod;
	
	var $tabela = "cadastro_endereco";		
	function cp()
		{
			global $user;
			$cp = array();
			array_push($cp,array('$H8','id_ce','',False,True));//0
			array_push($cp,array('$H8','ce_cliente','',True,True));//1
			array_push($cp,array('$S8','ce_cep','CEP',False,True));//2
			array_push($cp,array('$U8','ce_data','',True,True));//3
			array_push($cp,array('$HV','ce_hora',date("H:i"),True,True));//4
			array_push($cp,array('$HV','ce_log',$user->user_log,True,True));//5
			array_push($cp,array('$S40','ce_cidade','Cidade',True,True));//6
			array_push($cp,array('$S2','ce_estado','Estado',False,True));//7
			array_push($cp,array('$HV','ce_pais','BRA',False,True));//8
			array_push($cp,array('$S30','ce_bairro','Bairro',True,True));//9
			array_push($cp,array('$S100','ce_endereco','Endereço',True,True));//10
			array_push($cp,array('$O 1:SIM&0:NÃO','ce_ativo','Ativo',True,True));//11
			array_push($cp,array('$HV','ce_extinto','0',False,True));//12
			array_push($cp,array('$H8','ce_extinto_log','',False,True));//13
			array_push($cp,array('$H8','ce_cidade_cod','Cidade',True,True));//14
			//array_push($cp,array('$B','','Salvar',True,True));//14
			return($cp);
		}
	function cp_cep()
		{
			global $user;
			$cp = array();
			array_push($cp,array('$H8','id_ce','',False,True));
			array_push($cp,array('$H8','ce_cliente','',True,True));
			array_push($cp,array('$S8','ce_cep','Informe o CEP',False,True));
			array_push($cp,array('$H8','','',True,True));
			return($cp);
		}		

	function le($id)
		{
			if (strlen($id) > 0) { $this->id_ce = $id; }
			$sql = "select * from ".$this->tabela." ";
			$sql .= " where id_ce = ".$this->id_ce;
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->id_ce = $line['id_ce'];
					$this->ce_cliente = $line['ce_cliente'];
					$this->ce_data = $line['ce_data'];
					$this->ce_hora = $line['ce_hora'];
					$this->ce_log = $line['ce_log'];
					$this->ce_cidade = $line['ce_cidade'];
					$this->ce_cidade_cod = $line['ce_cidade_cod'];
					$this->ce_estato = $line['ce_estato'];
					$this->ce_pais = $line['ce_pais'];
					$this->ce_bairro = $line['ce_bairro'];
					$this->ce_endereco = $line['ce_endereco'];
					$this->ce_estado = $line['ce_estado'];
					$this->ce_ativo = $line['ce_ativo'];
					$this->ce_extinto = $line['ce_extinto'];
					$this->ce_cep = $line['ce_cep'];
					$this->ce_extinto_log = $line['ce_extinto_log'];
					return(1);
				}
			return(0);
		}
	function validar_exclusao()
		{
			$sql = "select count(*) as total from ".$this->tabela;
			$sql .= " where ce_cliente = '".$this->ce_cliente."' ";
			$sql .= " and ce_ativo = 1";
			$sql .= " group by ce_cliente";
			
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					if ($line['total'] > 1) { return(1); } else { return(0); }
				} else {
					return(0);
				}
		}
	function esconder_comprovante()
			{
				global $user_log;
				if ($this->validar_exclusao() == 1)
				{
					if (strlen($this->id_ce) > 0)
						{
							$sql = "update ".$this->tabela;
							$sql .= " set ce_ativo = 0 ";
							$sql .= " , ce_extinto = ".date("Ymd");
							$sql .= " , ce_extinto_log = '".$user_log."' ";
							$sql .= " where id_ce = ".$this->id_ce;
							$rlt = db_query($sql);
							return(1);
						}
				}
				return(0);
			}
					
	function consultora_mostra_icone_comprovante()
		{
			global $user_nivel,$secu;
			if (strlen($this->ce_cliente) > 0)
				{
				$comp = substr($this->ce_data,0,4).'/'.substr($this->ce_data,4,2).'/';
				$fl = '';
				$sx = '';
				$filex = $this->ce_cliente.'-'.strzero($this->id_ce,7);
				$filex .= '-'.substr(md5($secu.$filex),5,8);
				$file = '/dados/imagens/cadastro/';
				if (file_exists($file.$filex.'.pdf')) { $fl = $filex.'.pdf'; }
				if (file_exists($file.$filex.'.jpg')) { $fl = $filex.'.jpg'; }
				if (file_exists($file.$filex.'.png')) { $fl = $filex.'.png'; }
				
				if (file_exists($file.$comp.$filex.'.pdf')) { $fl = $filex.'.pdf'; }
				if (file_exists($file.$comp.$filex.'.jpg')) { $fl = $filex.'.jpg'; }
				if (file_exists($file.$comp.$filex.'.png')) { $fl = $filex.'.png'; }

				if (strlen($fl) > 0)
					{
						$linkv = 'onclick="newxy2('.chr(39).'cadastro_endereco_comprovante_ver.php?dd0='.$this->id_ce.'&dd1='.$this->ce_cliente.chr(39).',800,600);"';
						$linkc = 'onclick="newxy2('.chr(39).'cadastro_endereco_comprovante_cancelar.php?dd0='.$this->id_ce.'&dd1='.$this->ce_cliente.chr(39).',600,400);"';
						$sx .= '<img src="../img/img_folder.png" align="right" '.$linkv.'>';
						if ($user_nivel > 6)
							{ $sx .= '<img src="../img/img_cancel.png" align="right" '.$linkc.'  width="20">'; }			
					} else {
						$link = 'onclick="newxy2('.chr(39).'cadastro_endereco_comprovante.php?dd0='.$this->id_ce.'&dd1='.$this->ce_cliente.chr(39).',600,400);"';
						$linkc = 'onclick="newxy2('.chr(39).'cadastro_endereco_comprovante_cancelar.php?dd0='.$this->id_ce.'&dd1='.$this->ce_cliente.chr(39).',600,400);"';
						$sx .= '<img src="../img/img_folder_off.png" align="right" '.$link.'>';
						if ($user_nivel > 6)
							{ $sx .= '<img src="../img/img_cancel.png" align="right" '.$linkc.'  width="20">'; }			
					}
				}
			return($sx);
		}
	function consultora_upload_comprovante()
		{
			$sx .= '';
		}
	function consultora_endereco_mostrar($id)
		{
			if (strlen($id) > 0) { $this->ce_cliente = $id; }
			$sr = '';
			$sql = "select * from cadastro_endereco where ce_cliente = '".$this->ce_cliente."'";
			$sql .= " and ce_ativo = 1 ";
			$xrlt = db_query($sql);
			while ($xline = db_read($xrlt))
				{
					$this->le($xline['id_ce']);
					$sr .= $this->consultora_endereco();
				}
			$sr .= $this->consultora_habilita_novo_endereco();
			return($sr);
		}
	function consultora_habilita_novo_endereco()
		{
	
			$sx .= '<A HREF="#" ';
			$sx .= ' onclick="newxy2('.chr(39).'cadastro_endereco_novo.php';
			$sx .= '?dd1='.$this->ce_cliente;
			$sx .= '&dd90='.checkpost($this->ce_cliente);
			$sx .= chr(39).',600,400);" class="botao">novo endereço</A>';
			return($sx);
		}
		
	function consultora_endereco()
		{
			global $tab_max;
			$link = 'onclick="newxy2('.chr(39).'cadastro_endereco_comprovante.php'.chr(39).',600,400);"';
			$sx = '<table  width="'.$tab_max.'" cellpadding="0" cellspacing="0" border="1" align="center"><TR><TD>'.chr(13);
			$sx .= '<table width="100%" class="lt2" align="center" border=0>'.chr(13);
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '		<TD>Cidade</TD>'.chr(13);
			$sx .= '		<TD>Estado</TD>'.chr(13);
			$sx .= '		<TD>Pais</TD>'.chr(13);
			$sx .= '		<TD>Atualizado</TD>'.chr(13);
			$sx .= '		<TD rowspan="10">'.$this->consultora_mostra_icone_comprovante();
			$sx .= '	<TR>'.chr(13);
			$sx .= '		<TD><B>'.$this->ce_cidade.'</TD>'.chr(13);		
			$sx .= '		<TD><B>'.$this->ce_estado.'</TD>'.chr(13);		
			$sx .= '		<TD><B>'.$this->ce_pais.'</TD>'.chr(13);		
			$sx .= '		<TD><B>'.stodbr($this->ce_data).'</TD>'.chr(13);		
					
			$sx .= '	<TR class="lt0">'.chr(13);
			$sx .= '		<TD>CEP:</TD>'.chr(13);
			$sx .= '		<TD>Cidade / Bairro:</TD>'.chr(13);
			$sx .= '		<TD colspan="3">Endereço:</TD>'.chr(13);
			$sx .= '	<TR>'.chr(13);
			$sx .= '		<TD><B>'.$this->ce_cep.'</TD>'.chr(13);
			$sx .= '		<TD><B>'.$this->ce_cidade.' / '.$this->ce_bairro.'</TD>'.chr(13);
			$sx .= '		<TD colspan="2"><B>'.$this->ce_endereco.'</TD>'.chr(13);
			$sx .= '	</table>'.chr(13);
			$sx .= '	</TD></TR>'.chr(13);
			$sx .= '	</table>'.chr(13);
			return($sx);	
			//consultora_endereco Object ( [id_ce] => 1 [ce_cliente] => 0001538 [ce_data] => 20111221 [ce_hora] => 11:30 [ce_log] => RENE [ce_cidade] => 00006015 [ce_estado] => [ce_pais] => BRA [ce_bairro] => Bigorrilho [ce_endereco] => Rua Agostinho, 2885 ap. 1203 [ce_ativo] => 1 [ce_extinto] => 0 [ce_extinto_log] => [ce_cep] => 80.710-000 [tabela] => cadastro_endereco [ce_estato] => ) 		
		}
	function busca_id_endereco($consultora)
	{
		$sql = "select * from cadastro_endereco
				where ce_cliente='".$consultora."' and
					  ce_ativo=1
				order by ce_data desc, ce_hora limit 1
		";
		$rlt = db_query($sql);
		if($line=db_read($rlt))
		{
			$id = $line['id_ce'];
			$this->le($id);	
			return($id);
		}else{
			return(0);
		}
				
	}
}
