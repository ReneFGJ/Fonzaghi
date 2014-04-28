<?
    /**
     * Recursos Humanos - Usuários
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.26
	 * @package ususario
	 * @subpackage classe
    */
class user
	{
	var $erro='';
	var $user_id='';
	var $user_log='';
	var $user_nome='';
	var $user_nivel='';
	var $user_cracha='';
	var $user_perfil='';
	var $user_pass='';
    
	var $field_login = 'us_login';
	var $field_pass = 'us_senha';
	var $tabela = 'usuario';
	
    var $line;
	
	function lista_compras_funcionario($funcionario='',$data=0)
		{
			return($sx);
		}
	
	function lista_compras_checkin()
		{
			$sql = "select usuario_compras.us_loja as loja, * from usuario_compras
					inner join usuario on uc_cracha = us_cracha 
					where 
					us_checked = '' or us_checked isnull
					order by us_nomecompleto, us_parcela
					";
			$rlt = db_query($sql);
			
			$sx = '';
			$xnome = '';
			$xloja = '';
			$sx .= '<table width="99%" class="tabela00">';
			while ($line = db_read($rlt))
				{
					$loja = $line['loja'];
					$nome = trim($line['us_nomecompleto']);
					if (($xnome != $nome) or ($loja != $xloja))
						{
							$cracha = ' ('.trim($line['us_cracha']).')';
							$link = '<A HREF="index_compras_comprovante.php?dd0='.$line['us_cracha'].'&dd1='.$loja.'&dd2='.$line['us_data'].'">';
							$sx .= '<TR>';
							$sx .= '<TD colspan=1 class="tabela01"><B>';
							$sx .= $link.$nome.$cracha.'</A></B>';
							$xnome = $nome;
							$xloja = $loja;
							
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $line['loja'];					
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= stodbr($line['us_data']);
							$sx .= ' '.($line['us_hora']);
					
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $line['us_parcela'];
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= fmt($line['us_valor'],2);	
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= fmt($line['us_valor_parcela'],2);
						}	
				}
			$sx .= '</table>';
			return($sx);
			
		}
	
	function lista_funcionarios()
		{
			$func = array();
			
			/* Query */
			$sql = "select * from usuario where us_status='A' order by us_nomecompleto";
			
			/* Execução */
			$rlt = db_query($sql);
			
			
			$op = ' :Selecione o funcionário';
			while ($line = db_read($rlt)) {
				$nome=trim($line['us_nomecompleto']);
				$cracha=round($line['us_cracha']);
				$func[$cracha] = $nome;
			}
			return($func);		
		}
	
	
	
	
	function lista_funcionarios_option()
		{
			  
			/* Query */
			$sql = "select * from usuario where us_status='A' order by us_nomecompleto";
			
			/* Execução */
			$rlt = db_query($sql);
			
			
			$op = ' :Selecione o funcionário';
			while ($line = db_read($rlt)) {
				$nome=trim($line['us_nomecompleto']);
				$cracha=trim($line['us_cracha']);
				$op .= '&'.$cracha.':'.$nome;
			}
			return($op);
		}
		
	
	function cp_password()
		{
			$cp = array();
            array_push($cp,array('$H8','','',False,False));
			array_push($cp,array('${','','Senha',False,True));
           	array_push($cp,array('$P20','','Digite a senha atual',True,True));
			array_push($cp,array('$P20','','Digite nova senha',True,True));
			array_push($cp,array('$P20','','Redigite nova senha',True,True));
            array_push($cp,array('$}','','Endereço',False,True));
			array_push($cp,array('$B','','Alterar senha',False,True));
			return($cp);
		}
	function cp_email()
		{
			$cp = array();
            array_push($cp,array('$H8','id_us','',False,False));
			array_push($cp,array('${','','Atualização de e-mail',False,True));
           	array_push($cp,array('$S100','us_email','e-mail principal',True,True));
			array_push($cp,array('$S100','us_email_alt','e-mail alternativo',True,True));
            array_push($cp,array('$}','','E-mail',False,True));
			array_push($cp,array('$B','','Atualizar',False,True));
			return($cp);
		}		
    function cp_endereco_telefone()
        {
            $cp = array();
            
            array_push($cp,array('$H8','id_us','',False,False));
            array_push($cp,array('${','','Endereço',False,True));
            array_push($cp,array('$S70','us_endereco','Endereço',False,True));
            array_push($cp,array('$S20','us_bairro','Bairro',False,True));
            array_push($cp,array('$S10','us_cep','Cep',False,True));
            array_push($cp,array('$S20','us_cidade','Cidade',False,True));
            array_push($cp,array('$S2','us_estado','Estado',False,True));
            array_push($cp,array('$}','','Endereço',False,True));
            
            array_push($cp,array('${','','Telefones',False,True));
            array_push($cp,array('$S20','us_fone1','Telefone 1 :',False,True));
            array_push($cp,array('$S20','us_fone2','Telefone 2 :',False,True));
            array_push($cp,array('$S20','us_fone3','Telefone 3 :',False,True));
            array_push($cp,array('$}','','Telefones',False,True));
            array_push($cp,array('${','','e-mail',False,True));
            array_push($cp,array('$S70','us_email','E-mail 1 :',False,True));
            array_push($cp,array('$S80','us_email_alt','E-mail 2 :',False,True));
            array_push($cp,array('$}','','e-mail',False,True));
            array_push($cp,array('$B','','Atualizar informações',False,True));
            
            return($cp);
        
            
        }  
        
	function valida_password($pass,$nw_pass,$nw_pass2)
	    {
	       $msg='';    
	       $psw_old=trim($this->line[$this->field_pass]);    
	       if($psw_old!=$pass){        $msg="Senha antiga não confere";}
           if($nw_pass!=$nw_pass2){    $msg.="<BR>Nova senha digitada não confere com a redigitada";}
           if(strlen($nw_pass)<4){     $msg.="<BR>Senha necessita de pelo menos 4 digitos";}
           if($psw_old==$nw_pass){     $msg.="<BR>Senha não pode ser igual a antiga";}
           if(strlen($msg)==0)
           {          
	           $this->salva_password($nw_pass);
               $msg=1;
               return($msg);
           }
           $msg="<center><font color=red size=5>$msg</font>";
            return($msg);    
	    }
    function salva_password($new_pass)
        {
            $sql = "UPDATE usuario
                    SET us_senha='$new_pass'
                    WHERE us_cracha='$this->user_cracha'";
           
            $rlt = db_query($sql);
            return(1);   
        }
     
     
    
	function logout()
		{
			$_SESSION['nw_log'] = '';
			$_SESSION['nw_user'] = '';
			$_SESSION['nw_user_nome'] = '';
			$_SESSION['nw_nivel'] = '';
			$_SESSION['nw_level'] = '';
			$_SESSION['nw_cracha'] = '';
				
			setcookie('nw_log','',time()-17200);
			setcookie('nw_user','',time()-17200);
			setcookie('nw_user_nome','',time()-17200);
			setcookie('nw_nivel','',time()-17200);
			setcookie('nw_level','',time()-17200);
			setcookie('nw_cracha','',time()-17200);
			
			return(0);
		}
	
	function security()
		{
			global $hd,$_SESSION;
			
			$this->user_id 		= $_SESSION['nw_log'];
			$this->user_log 	= $_SESSION['nw_user'];
			$this->user_nome 	= $_SESSION['nw_user_nome'];
			$this->user_nivel 	= $_SESSION['nw_nivel'];
			$this->user_level	= $_SESSION['nw_level'];
			$this->user_cracha  = $_SESSION['nw_cracha'];
			$this->user_perfil  = $_SESSION['nw_perfil'];	
			
			if (strlen($this->user_id)==0)
				{
					redirecina($hd->http.'_login.php');
				}
			return(1);
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
							$user_perfil = trim($line['us_perfil']);
							
                            
							$_SESSION['nw_log'] = $user_id;
							$_SESSION['nw_user'] = $user_log;
							$_SESSION['nw_user_nome'] = $user_nome;
							$_SESSION['nw_nivel'] = $user_nivel;
							$_SESSION['nw_level'] = 1;
							$_SESSION['nw_cracha'] = $user_cracha;
							$_SESSION['nw_perfil'] = $user_perfil;
							
							setcookie('nw_log',$user_log,time()+17200);
							setcookie('nw_user',$user_id,time()+17200);
							setcookie('nw_user_nome',$user_nome,time()+17200);
							setcookie('nw_nivel',$user_nivel,time()+17200);
							setcookie('nw_level',1,time()+17200);
							setcookie('nw_cracha',$user_cracha,time()+17200);
							setcookie('nw_perfil',$user_perfil,time()+17200);

							//$this->autenticar();
							redirecina("main.php");
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

	function mostra_dados()
		{
			global $http;
			$sx = '<fieldset><legend><B>'.trim($this->line['us_nomecompleto']).'</B></legend>';
			$sx .= '<table width="98%" class="tabela00" align="center">';
			$sx .= '<TR valign="top">
					<TD rowspan="10">
						<img src="http://10.1.1.220/fonzaghi/img/foto/'.trim($this->line['us_cracha']).'.JPG" height="150">
					<TD width="60%">';
			$sx .= $this->line['us_endereco'];
			$sx .= '<BR>';
			$sx .= $this->line['us_bairro'];
			$sx .= '<BR>';
			$sx .= $this->line['us_cidade'];
			$sx .= '<BR>';
			$sx .= 'CEP: '.$this->line['us_cep'];
			$sx .= '<BR>';
			$sx .= 'Telefones: '.$this->line['us_fone1'];
			$sx .= ' '.$this->line['us_fone2'];
			$sx .= ' '.$this->line['us_fone3'];
			
			$sx .= $this->line['us_email'];
			$sx .= ' '.$this->line['us_email_alt'];
			
			$sx .= '<TD width="40%" bgcolor="#D0D0D0" class="tabela00">';
				$sx .= '<table width="100%" class="tabela01" >';
				$sx .= '<TR><TD>CPF: <B>'.trim($this->line['us_cpf']).'</b>';
				$sx .= '<TR><TD>RG: <B>'.trim($this->line['us_rg']).'</b>';
				$sx .= '<TR><TD>Nº PIS: <B>'.trim($this->line['us_pis']).'</b>';
				$sx .= '<TR><TD>Data Nascimento: <B>'.stodbr(trim($this->line['us_dtnasc'])).'</b>';
				$sx .= '<TR><TD><BR>';
				$sx .= '<TR><TD>Dt. Admissão: <B>'.stodbr($this->line['us_dtadm']).'</b>';
				$dtm = $this->line['us_dtdem'];
				if ($dtm < 20000101)
					{
						$sx .= '<TR><TD>Status: <B>Ativo</b>';
					} else {
						$sx .= '<TR><TD>Status: <font color="red">Desligado em <B>'.stodbr($dtm).'</b></font>';
					}
					
				
				$sx .= '</table>';	
			$sx .= '</table>';
			$sx .= '</fieldset>';
			//print_r($this);
			return($sx);
		}
	function le($id)
		{
			$sql = "select * from usuario 
						where us_cracha = '".$id."'
					";
			
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				$this->line = $line;
				}
			return(1);
		}
	function login()
		{
		global $dd;
		$form = new form;
		$form->class = ' class="formulario-entrada" ';
		$form->required_message = 0;
		$form->required_message_post = 0;
		

		$cp = array();
		array_push($cp,array('$H8','','',False,True));
		array_push($cp,array('$S20','','Login',True,True));
		array_push($cp,array('$P20','','Senha',True,True));
		array_push($cp,array('$B8','','Entrar',False,True));
		
		$tela = $form->editar($cp,'');
		$sx .= '<div id="login">'.$tela.'</div>';
		
		if ($form->saved > 0)
			{
				if ((strlen($dd[1]) > 0) and (strlen($dd[2]) > 0))
				{
					if ($this->login_valida($dd[1],$dd[2]))
						{ }
				}				
			}
		$sxa = '	
				
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
	function updatex()
			{
			$dx1 = 'us_codigo';
			$dx2 = 'us';
			$dx3 = 7;
			$sql = "update ".$this->tabela." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
			}		
		
	}			