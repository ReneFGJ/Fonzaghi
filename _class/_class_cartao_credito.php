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
		
  	var $cliente = '';
	
	function cp()
	{
		$op_tipo = $this->option_tipo_card();	
		$cp = array();
		
		/*campos cadastrados manualmente*/
		/*0*/array_push($cp,array('$H8','id_ccard','',False,False));
		/*1*/array_push($cp,array('$S7','ccard_cliente','Cliente',False,False));
		/*2*/array_push($cp,array('$O '.$op_tipo,'ccard_tipo','Bandeira',True,True));
		/*3*/array_push($cp,array('$S10','ccard_doc','Documento',True,True));
		/*4*/array_push($cp,array('$S10','ccard_auto','Auto',True,True));
		/*5*/array_push($cp,array('$N','ccard_valor','Valor',True,True));
		/*6*/array_push($cp,array('$S2','ccard_parcelas','Parcelas',True,True));
		/*7*/array_push($cp,array('$S14','ccard_documento','Referencia interna ',True,True));
		/*8*/array_push($cp,array('$S50','ccard_historico','Histórico',True,True));
		
		/*Campos automaticos*/
		/*9*/array_push($cp,array('$H8','ccard_status','',False,True));
		/*10*/array_push($cp,array('$H8','ccard_data','',False,True));
		/*11*/array_push($cp,array('$H8','ccard_hora','',False,True));
		/*12*/array_push($cp,array('$H8','ccard_log','',False,True));
		/*13*/array_push($cp,array('$H8','ccard_ip','',False,True));
		
		/** Campos usados somente no pagamento/liquidação
		array_push($cp,array('$H8','ccard_pago','9',True,True));
		array_push($cp,array('$H8','ccard_pago_hora','10',True,True));
		array_push($cp,array('$H8','ccard_loja','',True,True));
		array_push($cp,array('$H8','ccard_pago_log','',True,True));
		 */
		 
		/** Campos usados somente no cancelamento
		array_push($cp,array('$H8','ccard_cancelado_data','',True,True));
		array_push($cp,array('$H8','ccard_cancelado_hora','',True,True));
		array_push($cp,array('$H8','ccard_cancelado_log','',True,True));
		 */
		
		
		return($cp);
	}

	function cp_cancelar()
	{
		$op_tipo = $this->option_tipo_card();	
		$cp = array();
		
				/*campos cadastrados manualmente*/
		/*0*/array_push($cp,array('$H8','id_ccard','',False,False));
		/*1*/array_push($cp,array('$S7','ccard_cliente','Cliente',False,False));
		/*2*/array_push($cp,array('$O '.$op_tipo,'ccard_tipo','Bandeira',False,False));
		/*3*/array_push($cp,array('$S10','ccard_doc','Documento',False,False));
		/*4*/array_push($cp,array('$S10','ccard_auto','Auto',False,False));
		/*5*/array_push($cp,array('$N','ccard_valor','Valor',False,False));
		/*6*/array_push($cp,array('$S2','ccard_parcelas','Parcelas',False,False));
		/*7*/array_push($cp,array('$S14','ccard_documento','Referencia interna ',False,False));
		/*8*/array_push($cp,array('$S50','ccard_historico','Histórico',False,False));
		
		/*Campos automaticos*/
		/*9*/array_push($cp,array('$H8','ccard_status','',False,True));
		/*10*/array_push($cp,array('$H8','ccard_data','',False,True));
		/*11*/array_push($cp,array('$H8','ccard_hora','',False,True));
		/*12*/array_push($cp,array('$H8','ccard_log','',False,True));
		
		/*13*/array_push($cp,array('$H8','ccard_cancelado_data','',False,True));
		/*14*/array_push($cp,array('$H8','ccard_cancelado_hora','',False,True));
		/*15*/array_push($cp,array('$H8','ccard_cancelado_log','',False,True));
		
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
	function lista_lancamentos(){
		$sql = "select * from credito_cartao
				where ccard_cliente='".$this->cliente."'
		";
		
		
  		$rlt = db_query($sql);
		$sx = '<table width="95%">';
		$sx .= '<tr><th class="tabelaTH" width="9%" align="center">Data</th>
					<th class="tabelaTH" width="9%" align="center">Hora</th>
					<th class="tabelaTH" width="9%" align="left">Doc</th>
					<th class="tabelaTH" width="9%" align="left">Auto</th>
					<th class="tabelaTH" width="9%" align="left">Status</th>
					<th class="tabelaTH" width="9%" align="left">Lançado por</th>
					<th class="tabelaTH" width="9%" align="right">Valor</th>
					<th class="tabelaTH" width="9%" align="center">Parcelas</th>
					<th class="tabelaTH" width="9%" align="left">Ref. interna</th>
					<th class="tabelaTH" width="9%" align="left">Histórico</th>
				</tr>';
		while($line = db_read($rlt)){
			$sx .= '<tr><td class="tabela01" width="9%" align="center">'.$line['ccard_data'].'</td>
						<td class="tabela01" width="9%" align="center">'.$line['ccard_hora'].'</td>
						<td class="tabela01" width="9%" align="left">'.$line['ccard_doc'].'</td>
						<td class="tabela01" width="9%" align="left">'.$line['ccard_auto'].'</td>
						<td class="tabela01" width="9%" align="center">'.$this->status($line['ccard_status']).'</td>
						<td class="tabela01" width="9%" align="left">'.$line['ccard_log'].'</td>
						<td class="tabela01" width="9%" align="right">R$ '.number_format($line['ccard_valor'],2).'</td>
						<td class="tabela01" width="9%" align="center">'.$line['ccard_parcelas'].'</td>
						<td class="tabela01" width="9%" align="left">'.$line['ccard_documento'].'</td>
						<td class="tabela01" width="9%" align="left">'.$line['ccard_historico'].'</td>
					</tr>';
		}
		$sx .= '</table>';
		return($sx);
	}
	
	function status($st)
	{
		switch($st) {
			case '1':
				$st = 'SIM';
				break;
			case '@':
				$st = 'Pendente';
				break;	
			case 'B':
				$st = 'Baixado';
				break;	
			default:
				break;	
		}
			
		
		return($st);
	}
		
}
?>