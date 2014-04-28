<?
class user
	{
	var $erro='';
	var $user_id;
	var $user_log;
	var $user_nome;
	var $user_nivel;
	var $user_cracha;
	
	var $field_login = 'us_login';
	var $field_pass = 'us_senha';
	var $tabela = 'usuario';
	
	function security()
		{
			global $hd;
			$this->user_id 		= $_SESSION['nw_log'];
			$this->user_log 	= $_SESSION['nw_user'];
			$this->user_nome 	= $_SESSION['nw_user_nome'];
			$this->user_nivel 	= $_SESSION['nw_nivel'];
			$this->user_level	= $_SESSION['nw_level'];
			$this->$user_cracha = $_SESSION['nw_cracha'];	
			
			if (strlen($this->user_id)==0)
				{
					redireciona($hd->http.'_login.php');
				}
		}
	
	function login_valida($user,$pass)
		{
			$user = uppercasesql($user);
			$senha = uppercasesql($pass);
			$sql = "select * from ".$this->tabela." 
					where ".$this->field_login." = '$user' 
					and us_status = 'A'
					";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					if ($senha == uppercasesql(trim($line[$this->field_pass])))
						{
							$user_id = trim($line['id_us']);
							$user_nome = trim($line['us_nomecompleto']);
							$user_nivel = intval('0'.$line['us_nivel']);
							$user_log = trim($line['us_login']);
							$user_cracha = trim($line['us_cracha']);
							
							$_SESSION['nw_log'] = $user_id;
							$_SESSION['nw_user'] = $user_log;
							$_SESSION['nw_user_nome'] = $user_nome;
							$_SESSION['nw_nivel'] = $user_nivel;
							$_SESSION['nw_level'] = 1;
							$_SESSION['nw_cracha'] = $user_cracha;
							
							setcookie('nw_log',$user_log,time()+17200);
							setcookie('nw_user',$user_id,time()+17200);
							setcookie('nw_user_nome',$user_nome,time()+17200);
							setcookie('nw_nivel',$user_nivel,time()+17200);
							setcookie('nw_level',1,time()+17200);
							setcookie('nw_cracha',$user_cracha,time()+17200);
							 
							//$this->autenticar();
							redirecina("_main.php");
							return(1);
						} else {
							$this->erro = 'Senha incorreta<BR>';
							return(0);
						}
				} else {
					$this->erro = 'Login incorreto<BR>';
					return(0);
				}
		}
		
	function login()
		{
		global $dd;
		if ((strlen($dd[1]) > 0) and (strlen($dd[2]) > 0))
			{
				if ($this->login_valida($dd[1],$dd[2]))
					{
						
					}
			}
		$sx = '	
				<div id="login">
				<table>
				<TR><TD>
				<form method="post" action="'.page().'">
				<p>Login<br /><input name="dd1" type="text" placeholder="login" class="formulario-entrada" /><br />
				<br />Senha<br /><input name="dd2" type="password" placeholder="******" class="formulario-entrada" /><br />
			<font color="red">'.$this->erro.'</font>
				<input type="submit" name="acao" class="estilo-botao" value="ENTRAR">
				<input type="hidden" name="dd10" value="">
				<br />				</form>
				<table>
			</div>
			';
		return($sx);
		}
	}			