<?
 /**
  * Classe mail
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.44
  * @package Classe
  * @subpackage UC0001 - Classe mail
 */
 
class mail
	{
	var $id_mail;
	var $mail_codigo;
	var $mail_nome;
	var $mail_email;
	var $mail_consultora_nome;
	var $mail_consultora_email;

	function cp()
		{
		$cp = array();
		}
		
	function salvar()
		{
//		$sql = "select * from 
		echo 'Salvar';
		print_r($this);
		echo '<HR>';
		return(true);
		}
	
	function structure()
		{
			$sql = "CREATE TABLE mail (".chr(13).chr(10);
		  	$sql .= "id_mail serial NOT NULL,".chr(13).chr(10);
		  	$sql .= "  mail_nome char(40) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_email char(80) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_codigo char(7) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_mailing char(5) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_enviado char(1) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_ativo int(11) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_consultora char(80) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  mail_consultora_codigo char(7) NOT NULL,".chr(13).chr(10);
	  		$sql .= "  PRIMARY KEY (id_mail) ".chr(13).chr(10);
	  		$sql .= "  ) ";
			return($sql);
		}
	}
?>