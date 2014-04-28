<?php
 /**
  * Aniversariantes
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.14
  * @package Classe
  * @subpackage -
 */
require_once($include."sisdoc_email.php");
 class aniversariante
	{
		var $include_class = '../';
		var $tel='';
		var $ddd='';
		var $pais='55';
		var $lista_envio='';
		var $msg_mkt='';
		var $erro='';
		var $cliente='';
		
		function sms_aniver()
		{
			global $base_name,$base_host,$base_user;
			$this->get_msg();
			require($this->include_class.'db_cadastro.php');
			$this->lista_envio =  '<table width="700" align="center">';
			$sql = "select * from cadastro 
					inner join (select * from telefones_intra  ) as tb 
						on cl_cliente = tel_cliente
					where 	(cl_dtnascimento-((round(cl_dtnascimento/10000))*10000))=".date('md')." and 
							cl_last>=".date("Ymd", mktime(0, 0, 0, (date("m")-3), date("d"), date("Y")))." and 
								(tel_parentesco is null or 
								 tel_parentesco='-' or 
								 tel_parentesco='') and
								 tel_ativo = 1
								 
					order by cl_cliente, 
							 tel_atualizado			 
						";
			$rlt = db_query($sql);
			while($line = db_read($rlt))
			{
				$this->cliente = $line['cl_nome'];
				$this->ddd = $line['tel_ddd'];
				$this->tel = sonumero($line['tel_fone']);
				if($this->sms_envia()==0){
					if(strlen(trim($not_send))==0){
					$not_send .= '<tr><td><HR>Telefones inválidos para envio de SMS</td></tr>';	
					}
					$not_send .= '<tr><td><HR>'.$line['cl_nome'].'-('.$line['tel_ddd'].')'.$line['tel_fone'].'</td></tr>';
				}								
			}	
			$this->lista_envio .= $not_send;
			$this->lista_envio .= '</table>';
			
			$dest = 'sistemas@fonzaghi.com.br';
			$assu = 'Aniversariantes de '.date('Ymd');
			$texto = '<h1>Mensagens de aniversário enviadas</h1><br>';
			$texto .= $this->lista_envio;
			enviaremail_authe($dest,'',$assu,$texto);
			$dest = 'antonio@fonzaghi.com.br';
			enviaremail_authe($dest,'',$assu,$texto);	
			return(1);
		}
		
		function get_msg()
		{
			global $base_name,$base_host,$base_user;
			require($this->include_class.'db_206_telemarket.php');
			
			$sql = "select * from sms_historico
					where id_sms_hist=306
			 ";
				
			$rlt = db_query($sql);
			if($line = db_read($rlt)){
				$this->msg_mkt = trim($line['sms_hist_descricao']);
				return(1);
			}else{
				return(0);	
			}	
			
		}
		
		function sms_envia()
		{
			global $base_name,$base_host,$base_user;
			require($this->include_class.'db_206_telemarket.php');
			$sms = new sms;
            $sms->mensagem = $this->msg_mkt;
            $sms->destinatario = trim($this->pais).trim($this->ddd).sonumero($this->tel);
            $this->erro = $sms->sms_envia();
            if(trim($this->erro)=='00'){	
            	$this->lista_envio .= '<TR><TD>';
            	$this->lista_envio .= '<HR>';
            	$this->lista_envio .= 'Enviado para '.$this->cliente.'/  +'.substr($sms->destinatario,0,2).'('.substr($sms->destinatario,2,2).')'.substr($sms->destinatario,4,10);
            	$this->lista_envio .= '<BR>'.$this->erro.' - '.$sms->MsgErro($this->erro);
            	$this->lista_envio .= '<BR>Você tem '.$sms->atualiza_creditos($this->erro).' créditos!!';
            	return(1);
			}else{
				return(0);
			}	
            //verifica se os creditos estão no fim e informa os administradores
            $sms->informa_admin();
			
		}
	}		
?>