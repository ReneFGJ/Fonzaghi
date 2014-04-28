<?php
	class telefone
		{
			var $id_tel;
			var $tel_ddd;
			var $tel_fone;
			var $tel_data;
			var $tel_cliente;
			var $tel_status;
			var $tel_atualizado;
			var $tel_parentesco;
			var $tel_tipo;
			var $tel_checado;
			var $tel_checado_log;
			var $tel_ativo;
			var $tel_contato;
			
			var $tabela = 'telefones_intra';
			
		function grava()
			{
				echo "oi";
				if (strlen($this->id_tel) == 0)
					{
					if (strlen(trim($this->tel_fone)) > 0)
					{
						$pre = substr($this->tel_fone,0,1);
						if (($pre=='7') or ($pre=='8') or ($pre=='9'))
							{$this->tel_tipo = 'C'; } else {
								$this->tel_tipo = 'F';
							}
						$sql = "insert into telefones_intra ";
						$sql .= "(tel_ddd,tel_fone,tel_data,";
						$sql .= "tel_cliente,tel_status,tel_atualizado,";
						$sql .= "tel_parentesco,tel_tipo,tel_checado,";
						$sql .= "tel_checado_log,tel_ativo,tel_contato)";
						$sql .= " values ";
						$sql .= "('".substr($this->tel_ddd,0,3)."',";
						$sql .= "'".subsrt($this->tel_fone,0,20)."',";
						$sql .= "'".$this->tel_data."',";
						$sql .= "'".$this->tel_cliente."',";
						$sql .= "'".$this->tel_status."',";
						$sql .= "'".$this->tel_atualizado."',";
						$sql .= "'".$this->tel_parentesco."',";
						$sql .= "'".$this->tel_tipo."',";
						$sql .= "'".$this->tel_checado."',";
						$sql .= "'".$this->tel_checado_log."',";
						$sql .= "'".$this->tel_ativo."',";
						$sql .= "'".subsrt($this->tel_contato,0,20)."')";
						$rlt = db_query($sql);
						} else {
							
						}
					}

					return(0);					
			}
			
		function cp()
			{
				global $dd;
				if (strlen($dd[3]) == 0) { $dd[3] = '41'; }
				if (strlen(trim($dd[8])) == 0) { $dd[8] = '0'; }
				$cp = array();
				array_push($cp,array('$H8','id_tel','H',False,True,''));
				array_push($cp,array('$H8','tel_cliente','Cliente',True,True,''));
				array_push($cp,array('$H8','','Cliente '.$dd[1],False,True,''));
				array_push($cp,array('$S3','tel_ddd','DDD',True,True,''));
				array_push($cp,array('$S12','tel_fone','Telefone',True,True,''));
				array_push($cp,array('$Q tp_nome:tp_cod:select * from telefone_tipo where tp_ativo=1 order by tp_nome','tel_tipo','Tipo',True,True,''));
				array_push($cp,array('$Q p_nome:p_cod:select * from parentesco where p_ativo=1 order by p_nome','tel_parentesco','Parentesco',False,True,''));
				array_push($cp,array('$H8','tel_status','',False,True,''));
				array_push($cp,array('$U8','tel_atualizado','',False,True,''));
				array_push($cp,array('$U8','tel_data','',False,True,''));
				array_push($cp,array('$HV','tel_checado','0',False,True,''));
				array_push($cp,array('$O 1:Ativo&0:Inativo','tel_ativo','Ativo',False,True,''));
				array_push($cp,array('$S20','tel_contato','Dados Complementares',False,True,''));
				
				return($cp);
			}
		function le($id)
			{
				if (strlen($id) > 0) { $this->id_tel = $id; }
				$sql = "select * from ".$this->tabela;
				$sql .= " where id_tel = ".sonumero($this->id_tel);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$this->id_tel = $line['id_tel'];
					$this->tel_ddd = $line['tel_ddd'];
					$this->tel_fone = $line['tel_fone'];
					$this->tel_data = $line['tel_data'];
					$this->tel_cliente = $line['tel_cliente'];
					$this->tel_status = $line['tel_status'];
					$this->tel_atualizado = $line['tel_atualizado'];
					$this->tel_parentesco = $line['tel_parentesco'];
					$this->tel_tipo = $line['tel_tipo'];
					$this->tel_checado = $line['tel_checado'];
					$this->tel_checado_log = $line['tel_checado_log'];
					$this->tel_ativo = $line['tel_ativo'];
					$this->tel_contato = $line['tel_contato'];
					return(1);					
				} else {
					return(0);
				}
			}
		
		function telefone_format($tel,$tp=1)
			{
				$tel1 = sonumero($tel);
				$ddd = '';
				$in = 0;
				$tl = '';
				$er = 0;
				for ($r=0;$r < strlen($tel1);$r++)
					{
						$ch = substr($tel1,strlen($tel1)-$r-1,1);
						if ($in == 4) { $tl = '-'.$tl; }
						if (($tp==1) and ($r >= 8))
							{ $ddd = $ch.$ddd; $ch = ''; }
						if ($tp!=1)
							{ if ($in == 8) { $tl = ')'.$tl; } } 
							
						$tl = $ch.$tl;
						$in++;
					}
				if (strlen($tl) > 10) { $tl = '('.$tl; }
				if (strlen($tl) == 8) { $tl = '3'.$tl; }
				return(array(substr($ddd,0,3),$tl));
			}
		function telefone_dados($tel)
			{
				$tl = trim($tel);
				$tl = troca($tl,'0','');
				$tl = troca($tl,'1','');
				$tl = troca($tl,'2','');
				$tl = troca($tl,'3','');
				$tl = troca($tl,'4','');
				$tl = troca($tl,'5','');
				$tl = troca($tl,'6','');
				$tl = troca($tl,'7','');
				$tl = troca($tl,'8','');
				$tl = troca($tl,'9','');
				$tl = troca($tl,'-','');
				$tl = troca($tl,'.','');

				return(trim($tl));
			}
			
		function mostra_telefone($rsp,$tp='1')
			{
				global $coluna,$user_nivel,$include;
				if ($tp == '1') { $sx = '<TR><TH>ddd<TH>telefone<TH>compl.<TH>parente<TH>dt.cad.<TH>Tipo'; }
				if ($tp == '2') { $sx = '<TR><TH>telefone<TH>compl.<TH>Atualizado<TH>parente/Tipo'; }
				if (($tp == '1') and ($user_nivel > 7)) { $sx .= '<TH>ação'; }
				for ($r=0;$r < count($rsp);$r++)
					{
						if ($tp == '1')
							{
							$sx .= '<TR '.coluna().'>';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][0].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][1].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][2].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][3].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][4].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][5].'&nbsp;';
							if (($tp == '1') and ($user_nivel > 7))
								{
								$onclick = 'onclick="newxy2(';
								$onclick .= chr(39).'telefone.php?dd0='.$rsp[$r][6].'&dd1=0001538'.chr(39);
								$onclick .= ',600,400);"';
							 	$link = '<IMG SRC="'.$include.'img/icone_editar.gif" '.$onclick.' >';
							 	$sx .= '<TD align="center">';
							 	$sx .= $link; 
								}
							}
						if ($tp == '2')
							{
							$sx .= '<TR '.coluna().'>';
							$sx .= '<TD align="center">';
							$sx .= '('.$rsp[$r][0].') ';
							$sx .= $rsp[$r][1].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][5].'&nbsp;';						
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][4].'&nbsp;';						
							$sx .= '<TD align="center">';
							$sx .= $rsp[$r][7].'&nbsp;';						
														}	
					}
				return($sx);
			}
						
		function telefones_busca_por_cliente_old()
			{
				$sql = "select * from telefones ";
				$sql .= "left join parentesco on p_cod = tel_parentesco ";
				$sql .= "left join telefone_tipo on tp_cod = tel_tipo ";
				$sql .= " where tel_cliente = '".$this->tel_cliente."'";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$ddd = substr(trim($line['tel_ddd']),0,3);
						$tel = $line['tel_fone'];
						$con = $line['tel_contato'];
						$data = $line['tel_atualizado'];
						$datau = date("Ymd");
						$tipo = $line['tp_nome'];
						$parentesco = $line['p_nome'];
						$cliente = $line['tel_cliente'];
						$atu =round('0'.$line['tel_checado']);
						if (strlen($tel) > 0)
							{
								$con = $this->telefone_dados($tel);
								$tel = $this->telefone_format($tel);
								$tel0 = $tel[0];
								$tel1 = $tel[1];
								if (strlen($ddd) == 0) { $ddd = $tel0; }
								if (strlen($ddd) == 0) { $ddd = '41'; }
								if ($ddd == '041') { $ddd = '41'; }
								$sql = "insert into telefones_intra ";
								$sql .= "(tel_ddd,tel_fone,tel_data,";
								$sql .= "tel_cliente,tel_status,tel_atualizado,";
								$sql .= "tel_parentesco,tel_tipo,tel_checado,";
								$sql .= "tel_checado_log,tel_ativo,tel_contato)";
								$sql .= " values ";
								$sql .= "('$ddd','$tel1',$data,";
								$sql .= "'$cliente','A',$datau,";
								$sql .= "'$parentesco','$tipo',1,";
								$sql .= "'',1,'$con');";

								if (strlen($tel1) > 0)
									{
									$xrlt = db_query($sql);
								
									$sqlx = "update telefones ";
									$sqlx .= " set tel_ativo = 0 ";
									$sqlx .= " where id_tel = ".$line['id_tel'];
									
									$sqlx = "delete from telefones ";
									$sqlx .= " where id_tel = ".$line['id_tel'];
									$xrlt = db_query($sqlx);
									}
							}
					}
				return(1);
			}
	function consultora_habilita_novo_telefone()
		{
	
			$sx .= '<A HREF="#" ';
			$sx .= ' onclick="newxy2('.chr(39).'../cadastro/telefone.php?';
			$sx .= '?dd1='.$this->tel_cliente;
			$sx .= '&dd90='.checkpost($this->tel_cliente);
			$sx .= chr(39).',600,400);" class="botao">novo telefone</A>';
			return($sx);
		}

		function telefones_busca_por_cliente()
			{
				$this->telefones_busca_por_cliente_old();
				
				$sql = "select * from ".$this->tabela." ";
				$sql .= "left join parentesco on p_cod = tel_parentesco ";
				$sql .= "left join telefone_tipo on tp_cod = tel_tipo ";
				$sql .= " where tel_cliente = '".$this->tel_cliente."'";
				$rlt = db_query($sql);

			
				$rsp = array();
				while ($line = db_read($rlt))
					{
						$ativo = $line['tel_ativo'];
						
						if ($ativo != '0')
						{
						$id = $line['id_tel'];
						$ddd = substr(trim($line['tel_ddd']),0,3);
						$tel = $line['tel_fone'];
						$con = $line['tel_contato'];
						$data = $line['tel_data'];
						if (strlen($data)==0) { $data = date("Ymd"); }
						$datau = date("Ymd");
						$tipo = $line['tp_nome'];
						$tipo_2 = $line['tp_cod'];
						$parentesco = $line['p_nome'];
						$parentesco_2 = $line['p_cod'];
						$cliente = $line['tel_cliente'];
						$cont = $line['tel_contato'];
						$atu =round('0'.$line['tel_checado']);
						if ($atu == 0)
							{
								$con = substr($this->telefone_dados($tel),0,20);
								$xtel = $this->telefone_format($tel);
								$tel0 = $xtel[0];
								$tel1 = $xtel[1];
								$tipo = substr($tipo,0,1);
								if (strlen($ddd) == 0) { $ddd = $tel0; }
								if (strlen($ddd) == 0) { $ddd = '41'; }
								if ($ddd == '041') { $ddd = '41'; }
																
								$sql = "insert into telefones_intra ";
								$sql .= "(tel_ddd,tel_fone,tel_data,";
								$sql .= "tel_cliente,tel_status,tel_atualizado,";
								$sql .= "tel_parentesco,tel_tipo,tel_checado,";
								$sql .= "tel_checado_log,tel_ativo,tel_contato)";
								$sql .= " values ";
								$sql .= "('$ddd','$tel1',$data,";
								$sql .= "'$cliente','A',$datau,";
								$sql .= "'$parentesco_2','$tipo_2',1,";
								$sql .= "'',1,'$con');";
								$xrlt = db_query($sql);
								
								$sqlx = "update telefones_intra ";
								$sqlx .= " set tel_ativo = 0 ";
								$sqlx .= " where id_tel = ".$line['id_tel'];
								$xrlt = db_query($sqlx);
							}
						array_push($rsp,array($ddd,$tel,$con,$parentesco,stodbr($data),$tipo,$id,$cont.' '.$parentesco));
						}
					}
				return($rsp);
			}
		function updatex()
			{
				return(1);
			}
		}
