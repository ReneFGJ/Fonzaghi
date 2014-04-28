<?php
class user_perfil
	{
		var $id_user_field = 'id_us';
		var $user_name_field = 'us_nomecompleto';
		var $tabela_usuario = 'usuario';
		var $tabela = 'usuario_perfil';
		var $tabela_perfil = 'usuario_perfis_ativo';
		
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
			$sql = "update usuario set us_perfil = '".$per."' where us_codigo = '".$id."' ";
			$rlt = db_query($sql);
			return(1);
		}
		
	function display()
		{
			global $ss;
			$id = $ss->user_codigo;
			//$id = $
			$sql = "select * from ".$this->tabela_perfil;
			$sql .= " inner join ".$this->tabela." on up_perfil = usp_codigo ";
			$sql .= " where up_usuario = '".$id."' and up_ativo = 1";
			
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
			$sql = "select * from ".$this->tabela_usuario." where us_status = 'A' order by ".$this->user_name_field;
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$cod = strzero($line[$this->id_user_field],7);
				$sel = '';
				if ($cod == $dd[1]) { $sel = ' selected '; }
				$user_name = trim($line[$this->user_name_field]);
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
			$sx .= '<TR><TD><TD>';
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
		
	function valida($perfis)
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
			return($ok);
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