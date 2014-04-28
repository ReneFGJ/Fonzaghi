<?
    /**
     * Telefonia - SMS
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package sms
	 * @subpackage classe
    */
    
class sms
	{
	var $destinatario;
	var $mensagem;
	var $modoteste = 0;
	var $identificador='FONZAGHI';
	var $agendamento;
	
	var $usuario = 'fonzaghi';
	var $senha = 'd22u33';
	var $remetente  = 'FONZAGHI';
	var $erro;
	
	function sms_envia()
		{
			$ok = 1;
			if (strlen($this->mensagem) == 0) { $ok = -1; }
			if (strlen($this->destinatario) == 0) { $ok = -2; }
			if (strlen($this->mensagem) > 140) { $ok = -3; }
			if ($ok == 1) { $erro = $this->sms_connect(); }
			if ($ok < 0) { $erro = $ok; }
			$this->erro = $erro;
			$this->sms_save();
			return($erro);
		}
	
	function sms_save()
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			$sql = "insert into sms ";
			$sql .= "(sms_data,sms_hora,sms_destino,sms_ip,sms_mensagem,sms_erro,sms_login) ";
			$sql .= " values ";
			$sql .= "(".date("Ymd").",'".date("H:i:s")."','".$this->destinatario."',";
			$sql .= "'".$ip."','".$this->mensagem."',".round($this->erro).",'".$user_log."')";
			$rlt = db_query($sql);
		}
	function sms_form()
		{
			global $dd;
			$sx .= '<form method="post">';
			$sx .= '<table>';
			$sx .= '<TR class="lt0">';
			$sx .= '<TD>PAIS';
			$sx .= '<TD>DDD';
			$sx .= '<TD>TELEFONE';

			$sx .= '<TR class="lt0">';
			$sx .= '<TD><input type="text" name="dd2" size=3 maxlenght=3 value="55" readonly>';
			$ddd = array('41','42','43','47','51','11');
			$sx .= '<TD><select name="dd3">';
			for ($r=0;$r < count($ddd);$r++)
				{
					$sel = '';
					if ($dd[3] == $ddd[$r]) { $sel = 'selected'; }
					$sx .= '<option value="'.$ddd[$r].'" '.$sel.'>'.$ddd[$r].'</option>';
				}
			$sx .= '</select>';
			$sx .= '<TD><input type="text" name="dd4" size=10 maxlenght=10 value="'.$dd[4].'">';

			$sx .= '<TR><TD colspan=3 class="lt0">MENSAGEM';
			$sx .= '<TR><TD colspan=3 >';
			$sx .= '<textarea name="dd5" rows="4" cols="30">'.$dd[5].'</textarea>';
			$sx .= '<TR><TD colspan=3  ><input name="dd10" type="SUBMIT" value="Enviar >>>">';
			$sx .= '</table>';
			$sx .= '</form>';
			return($sx);
		}
		
	function sms_connect()
		{
			$ch = curl_init();
			///// inicializa parï¿½metros da mensagem
			$usuario        = $this->usuario;
			$senha          = $this->senha;      
			
			$remetente      = $this->remetente;    
			$destinatario   = $this->destinatario;
			$agendamento    = "AAAA-MM-DD hh:mm:ss";
			$agendamento    = $this->agendamento;  
			$mensagem       = $this->mensagem;
			$identificador  = $this->identificador;                
			$modoTeste      = $this->modoTeste;             
			
			///// monta o conteuo do parametro "messages" (nao alterar)
			$codedMsg       = $remetente."\t".$destinatario."\t".$agendamento."\t".$mensagem."\t".$identificador;
			
			$modoTeste = '';
			///// configura parï¿½metros de conexï¿½o (nï¿½o alterar)
			$path           = "/3.0/user_message_send.php";
			$parametros     = $path.'?testmode='.$modoTeste.'&linesep=0&user='.urlencode($usuario).'&pass='.urlencode($senha).'&messages='.urlencode($codedMsg);
			$url            = "https://cgi2sms.com.br".$parametros;
								
			echo $url;
			
			///// realiza a conexao
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$result = curl_exec($ch);
			$result = ($result == ""?$result="1":$result);
			curl_close ($ch); 
			///// verifica o resultado
			$error      = explode("\n",urldecode($result));
			$error[0]   = (int)trim($error[0]);
			
			if($error[0] != 0){
	    		///// para o caso de um erro genï¿½rico
    			$errorCode  = $error[0];
			} else {
	    		///// para o caso de erro especï¿½fico
    			$errorPhone	= explode(" ",urldecode($error[1]));
    			$errorCode  = $errorPhone[0];
			}
			echo '--'.$errorCode;
			return($errorCode);
			}
		Function MsgErro($errorCode)
			{
			switch($errorCode) {
	    		case -1   : $msg = "Mensagem em branco"; break;
	    		case -2   : $msg = "Destinatário inválido"; break;
	    		case -3   : $msg = "Texto superior a 150 caracteres"; break;
	    		case 0   : $msg = "Mensagem enviada com sucesso"; break;
    			case 1   : $msg = "Problemas de conexão"; break;
    			case 10  : $msg = "Username e/ou Senha inválido(s)"; break;
    			case 11  : $msg = "Parametro(s) inválido(s) ou faltando"; break;
    			case 12  : $msg = "Número de telefone inválido ou não coberto pelo Comunika"; break;
    			case 13  : $msg = "Operadora desativada para envio de mensagens"; break;
	    		case 14  : $msg = "Usuário não pode enviar mensagens para esta operadora"; break;
    			case 15  : $msg = "Créditos insuficientes";	break;
	    		case 16  : $msg = "Tempo mínimo entre duas requisições em andamento"; break;
    			case 17  : $msg = "Permissição negada para a utilização do CGI/Produtos Comunika"; break;
    			case 18  : $msg = "Operadora Offline"; break;
    			case 19  : $msg = "IP de origem negado"; break;
    			case 404 : $msg = "Página não encontrada"; break;
				}
			return($msg);
			}
	}
?>