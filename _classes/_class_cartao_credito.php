<?php
 /**
  * Cartão de crédito
  * @author Willian Fellipe Laynes (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.16
  * @package Classe
  * @subpackage cartao_credito
 */
class cartao_credito
	{
		
  	
	function cp()
	{
		$op_tipo = $this->option_tipo_card();	
		$cp = array();
		
		/*campos cadastrados manualmente*/
		/*0*/array_push($cp,array('$H8','id_ccard','',False,False));
		/*1*/array_push($cp,array('$O '.$op_tipo,'ccard_tipo','Bandeira',True,True));
		/*2*/array_push($cp,array('$S10','ccard_doc','Documento',True,True));
		/*3*/array_push($cp,array('$S10','ccard_auto','Auto',True,True));
		/*4*/array_push($cp,array('$N','ccard_valor','Valor',True,True));
		/*5*/array_push($cp,array('$S2','ccard_parcelas','Parcelas',True,True));
		/*6*/array_push($cp,array('$S14','ccard_documento','Documento 2 ',True,True));
		/*7*/array_push($cp,array('$S50','ccard_historico','Histórico',True,True));
		/*8*/array_push($cp,array('$O @:Ativo&X:Cancelado','ccard_status','Status',True,True));
		
		/*Campos automaticos*/
		/*9*/array_push($cp,array('$H8','ccard_cliente','',False,True));
		/*10*/array_push($cp,array('$H8','ccard_data','',False,True));
		/*11*/array_push($cp,array('$H8','ccard_hora','',False,True));
		/*12*/array_push($cp,array('$H8','ccard_log','',False,True));
		
		
		/** Campos usados somente no pagamento/liquidação
		array_push($cp,array('$H8','ccard_pago','9',True,True));
		array_push($cp,array('$H8','ccard_pago_hora','10',True,True));
		array_push($cp,array('$H8','ccard_loja','',True,True));
		array_push($cp,array('$H8','ccard_pago_log','',True,True));
		 */
		 
		/** Campos usados somente no cancelamento
		array_push($cp,array('$H8','ccard_cancelado','',True,True));
		array_push($cp,array('$H8','ccard_cancelado_hora','',True,True));
		array_push($cp,array('$H8','ccard_cancelado_log','',True,True));
		 */
		
		
		return($cp);
	}

	function option_tipo_card()
	{
		
		$op = ' :Selecione bandeira';
        $op .= '&MAS:Master';
        $op .= '&VIS:Visa';
		$op .= '&ELC:Visa Electron';
		$op .= '&HIP:Hipercard';
		$op .= '&RED:RedeCard';
		
		return($op);
	}
	
	function valida_auto($doc='',$auto=''){
		$sql = "select * from credito_cartao
				where 	ccard_doc='".$doc."' and
						ccard_auto='".$auto."'
				";
		$rlt = db_query($sql);
		if($line = db_read($rlt)){
			
			return(1);
		}else{
			return(0);
		}		
	}
		
}
?>