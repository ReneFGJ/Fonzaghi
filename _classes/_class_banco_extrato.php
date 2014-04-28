<?
    /**
     * Classe de conta corrente
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2011 - sisDOC.com.br
	 * @access public
     * @version v0.11.30
	 * @package Classe
	 * @subpackage Financeiro
     */
	 
class banco_extrato
	{
	var $ext_conta;
	var $ext_historico;
	var $ext_valor;
	var $ext_status;
	var $ext_tipo;
	var $ext_doc;
	var $ext_pedido;
	var $ext_data;
	var $ext_venc;
	var $ext_pre;
	var $ext_img;
	var $ext_data_lnc;
	var $ext_sinal;
	
	/** Método de Exclusão de Lançamentos Dobrados 
	 * @result integer Número de registro excluídos
	*/
	function exclusão_lancamentos_dobrados()
		{
		$sql = "select * from (
		select max(id_ext) as max, ext_data , ext_valor, count(*) as conta, ext_historico,ext_conta from banco_extrato
		group by ext_data , ext_valor, ext_historico, ext_conta
		) as tabela
		where conta > 1";
		$rlt = db_query($sql);

		$sqlu = "delete from banco_extrato where ";
		$ro=0;
		while ($line = db_read($rlt))
			{
			if ($ro > 0) { $sqlu .= " or "; }
			$sqlu .= ' id_ext = '.$line['max'];
			$ro++;
			}
		if ($ro > 0)
			{ $rlt = db_query($sqlu); }		
		return($ro);
		}
	}
?>