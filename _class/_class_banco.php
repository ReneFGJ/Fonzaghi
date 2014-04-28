<?php
 /**
  * Banco
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.18
  * @package Classe
  * @subpackage -
 */
 
class banco
	{
	var $id;
	var $line;
	
	var $tabela_cheque = 'cheque_2009';
	
	function baixa_cheques($nrdep,$cheques,$data)
		{
			global $user;
			$sql = "";
			for ($r=0;$r < count($cheques);$r++)
				{
					$sql .= "update ".$this->tabela_cheque." set 
								chq_status = 'D',
								chq_conta = '".$user->user_log."',
								chq_nrdep = '".$nrdep."',
								chq_dp_hora = '".date("H:i")."'
							where id_chq = ".$cheques[$r].';'.chr(13);					
				}
			if (strlen($sql) > 0)
				{ $rrr = db_query($sql); }
			return(1);
		}
	
	function le($id)
		{
			$sql = "select * from banco where id_bco = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
				}		
			return(1);
		}
	function mostra()
		{
			$line = $this->line;
			$sx = '<B>'.$line['bco_descricao'].'</B>';
			$sx .= '<BR>AG. '.$line['bco_agencia'];
			$sx .= ', CC:'.$line['bco_conta'];
			
			return($sx);			
		}
	function nr_dep()
		{
			$sql = "select max(id_ext) as nr from banco_extrato ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$nr = (round($line['nr'])+1);
			return($nr);
		}
	function deposito($conta,$valor,$data,$tipo)
		{
		$nrdep = 'D'.strzero($this->nr_dep(),7);
		$this->nrdep = $nrdep;
		
		if ($tipo == 'DIN')
			{
				$tipo_descricao = "Deposito Dinheiro";
			}
		if ($tipo == 'DEP')
			{
				$tipo_descricao = "Deposito Cheque";
			}			
		$sql = "insert into banco_extrato (";
		$sql .= "ext_conta,ext_historico,ext_valor,";
		$sql .= "ext_status,ext_tipo,ext_data,";
		$sql .= "ext_venc,ext_doc,ext_pedido,";
		$sql .= "ext_pre,ext_ativo,ext_auto";
		$sql .= ") values (";
		$sql .= $conta.",'".$tipo_descricao."',".(round($valor*100)/100).",";
		$sql .= "'A','".$tipo."','".date("Ymd")."',";
		$sql .= "'".$data."','DEPOSITO','".$nrdep."',";
		$sql .= "'".$data."','S','N'";
		$sql .= ")";
		$rlt = db_query($sql);			
		}
	}
?>
