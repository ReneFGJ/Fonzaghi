<?php
class user_perfil
	{
		var $id_user_field = 'id_us';
		var $usuario = 'user';
		var $user_name_field = 'us_nome';
		var $user_login = 'us_login';
		var $user_email = 'us_email';
		var $tabela_usuario = 'usuario';
		var $tabela = 'usuario_perfil';
		var $tabela_perfil = 'usuario_perfis_ativo';
		var $versao = '0.12.35';
		
	function mostrar()
		{
			echo 'MOSTRAR DADOS';
		}

	function display_perfil($id)
		{
			global $ss,$dd;
			
			if ($dd[2]=='DEL')
				{
					$sql = "select * from usuario_perfis_ativo where id_up = ".round($id);
					$rlt = db_query($sql);
					$line = db_read($rlt);
					
					$codigo = $line['up_usuario'];
										
					$sql = "delete from usuario_perfis_ativo where id_up = ".round($dd[1]);
					$rlt = db_query($sql);
					
					$this->set($codigo);
				}
			//$id = $
			$sql = "select * from ".$this->tabela." where id_usp = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					echo '<h1>'.$line['usp_descricao'].'</h1>';
				}
			
			$sql = "select * from ".$this->tabela_perfil;
			$sql .= " inner join ".$this->tabela." on up_perfil = usp_codigo ";
			$sql .= " inner join usuario on up_usuario = us_codigo ";
			$sql .= " where id_usp = ".round($id)." and up_ativo = 1";
			$rlt = db_query($sql);
			
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH width="30%">Login';
			$sx .= '<TH width="60%">Nome';
			$sx .= '<TH width="10%">Indicação';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="'.page().'?dd0='.$id.'&dd1='.$line['id_up'].'&dd2=DEL" class="link">';
					$link .= 'excluir';
					$link .= '</A>';
					
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>'.$line['us_login'];
					$sx .= '<TD>'.$line['us_nome'];					
					$sx .= '<TD width="10%" align="center">'.stodbr($line['up_data']);
					$sx .= '<TD>';
					$sx .= $link;
				}
			$sx .= '</table>';
			return($sx);
		}
				
	function set($id)
		{
			$sql = "select * from ".$this->tabela_perfil;
			$sql .= " inner join ".$this->tabela." on up_perfil = usp_codigo ";
			$sql .= " where up_usuario = '".$id."' and up_ativo = 1";			
			$rlt = db_query($sql);
			$per = "#RES";
			while ($line = db_read($rlt))
				{
					$per .= trim($line['usp_codigo']);
				}
			//$per = substr($per,0,30);
			$sql = "update usuario set us_perfil = '".trim($per)."' where us_codigo = '".$id."' ";
			$rlt = db_query($sql);
			return(1);
		}
		
	function display($id=0)
		{
			global $ss;
			//$id = $
			$sql = "select * from ".$this->tabela_perfil;
			$sql .= " inner join ".$this->tabela." on up_perfil = usp_codigo ";
			$sql .= " where up_usuario = '".strzero($id,7)."' and up_ativo = 1";
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>'.msg('description');
			$sx .= '<TH>'.msg('indication');
			while ($line = db_read($rlt))
				{
					$sx .= '<TR '.coluna().'><TD>'.$line['usp_descricao'];
					$sx .= '<TD width="10%" align="center">'.stodbr($line['up_data']);
				}
			$sx .= '</table>';
			return($sx);
		}
		
	function valid($type)
		{
				global $ss;
				$xper = ' '.$ss->user_perfil;
				for ($rr=1;$rr < strlen($xper);$rr=$rr+4)
					{
					$per = substr($xper,$rr,4);
					//echo '<BR>'.$per.'='.$type;
					if (strpos(' '.$type,$per) > 0)
						{ return(True); }
					}
				return(False);
		}
		
	function atribui_perfil($user,$perfil)
		{
			$sql = "select * from ".$this->tabela_perfil."
				where up_usuario = '$user' and
				up_perfil = '$perfil' ";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					
				} else {
					$data = date("Ymd");
					$sql = "insert into ".$this->tabela_perfil." 
						(up_perfil, up_usuario, 
						up_data, up_data_end, up_ativo)
						values 
						('$perfil','$user',
						$data,19000101,1)
					";
					$rlt = db_query($sql);					
				}
			return(1);
		}
		
	function perfil_atribui_form()
		{
			global $dd;
			$sx .= '<table>';
			$sx .= '<TR><TD><form method="post" action="'.page().'">';
			$sx .= '<TR valign="center">';
			$sx .= '<TH>User<TH>Perfil';
			$sx .= '<TR valign="center">';
			$sx .= '<TD>';
			$sx .= '<select size=18 name="dd1" style="width: 400px">';
			$sql = "select * from ".$this->tabela_usuario." where
					us_senha_md5 = 1 
					order by ".$this->user_login;
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$cod = strzero($line[$this->id_user_field],7);
				$sel = '';
				if ($cod == $dd[1]) { $sel = ' selected '; }
				$user_name = trim($line[$this->user_login]);
				$user_name .= ' ('.trim($line[$this->user_email]).')';
				$user_name = substr($user_name,0,60);
				$sx .= '<option value="'.$cod.'" '.$sel.'>';
				$sx .= trim($user_name);
				$sx .= '</option>';
			}
			$sx .= '</select>';
			$sx .= '<TD>';
			
			$sx .= '<select size=18 name="dd2" style="width: 200px;">';
			$sql = "select * from ".$this->tabela." where usp_ativo = 1 order by usp_descricao ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$cod = trim($line['usp_codigo']);
				$user_name = trim($line['usp_descricao']);
				$sel = '';
				if ($cod == $dd[2]) { $sel = ' selected '; }
				
				$sx .= '<option value="'.$cod.'" '.$sel.'>';
				$sx .= trim($user_name);
				$sx .= '</option>';
			}
			$sx .= '</select>';	
			$sx .= '<TR><TD><TD>xx';
			$sx .= '<input type="submit" value="set perfil >>>">';		
			$sx .= '</table>';
			
			if ((strlen($dd[1]) > 0) and (strlen($dd[2]) > 0))
				{
					$ox = $this->atribui_perfil($dd[1],$dd[2]); 
					if ($ox == 1)
						{
							$sx .= '<center><font color="green">';
							$sx .= '<BR><BR>Seted Perfil';
							$sx .= '<BR><BR>';
						}
					
				}
			return($sx);
		}

	function perfil($user)
		{
		$sql = "select up_perfil from usuario_perfis_ativo 
				where up_usuario = '$user' 
				group by up_perfil
				order by up_perfil
				";
		$rlt = db_query($sql);
		$perfil = '';
		while ($line = db_read($rlt))
		{
			$perfil .= $line['up_perfil'];
		}
		$_SESSION['perfil'] = $perfil;
		return($perfil);
		}
	function strucuture()
		{
			$sql = "
			CREATE TABLE usuario_perfil (
 	 		id_usp serial NOT NULL,
  	 		usp_codigo char(4),
  	 		usp_descricao char(50),
  	 		usp_ativo integer
			);
			
			CREATE TABLE usuario_perfis_ativo (
  			id_up serial NOT NULL,
  			up_perfil char(4),
  			up_usuario char(7),
  			up_data integer,
  			up_data_end integer,
  			up_ativo integer
  			);

			INSERT INTO usuario_perfil (usp_codigo, usp_descricao, usp_ativo) VALUES
				('#MAS', 'Master', 1),
				('#ADM', 'Admin', 1),
				('#COO', 'Coordenador', 1),
				('#SCR', 'Secretary', 1),
				('#RES', 'Reserach', 1),
				('#MEM', 'Member of committee', 1),
				('#ADC', 'Ad Hoc', 1);
				";
			$rlt = db_query($sql);
		}
		
	function row_perfil()
		{
			global $cdf, $cdm, $masc; 
			$cdf = array('id_usp','usp_descricao','usp_codigo');
			$cdm = array('cod',msg('nome'),msg('codigo'));
			$masc = array('','','','','','','');
			return(1);			
		}
	function cp_perfil()
		{
			$cp = array();
			array_push($cp,array('$H8','id_usp','',False,True));
			array_push($cp,array('$S4','usp_codigo','',True,True));
			array_push($cp,array('$S50','usp_descricao','',True,True));
			array_push($cp,array('$O 1:SIM&0:Não','usp_ativo','',True,True));
			return($cp);
		}
	
	function valida_perfil($perfis)
		{
			$ok = 0;
			$perfis = ' '.$perfis;
			$pr = ' '.$_SESSION['perfil'];
			for ($rx=1;$rx < strlen($pr);$rx=$rx+4)
				{
					$pb = substr($pr,$rx,4);
					$pt = strpos($perfis,$pb);
					if ($pt > 0) { $ok = 1; }
				}
			if ($ok==0)
				{
					echo '<CENTER>';
					echo '<BR><BR><BR>';
					echo '<font color="red">';
					echo 'ACESSO RESTRITO';
					echo '</font>';
					echo '<BR><BR><BR>';
					exit;
				}
			return($ok);
		}	
	}
?>