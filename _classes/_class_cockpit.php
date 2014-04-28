<?php
class cockpit
	{
		var $data_ini;
		var $data_fim;
		var $tabela = "cf_fator";
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_cff','',False,True));
				array_push($cp,array('$S1','cff_loja','Fator',False,False));
				array_push($cp,array('$N8','cff_fator','Fator',False,True));
				array_push($cp,array('$N8','cff_vmin','Preço Min.',False,True));
				return($cp);
			}
		
		function cf($loja = '')
		{
			$sql = "select max(cf_parcial) as paecial, max(cf_saldo), cf_data from cf 
					where cf_data >= ".$this->data_ini." and cf_data < ".$this->data_ini."
					and cf_loja = '$loja'  
					group by cf_data
					order by cf_data ";
			$rlt = db_query($sql);
		}	
		function cf_indicador($loja = '')
		{
			$sql = "select max(cf_saldo) as saldo 
					from cf 
					where cf_data < ".$this->data_ini." 
					and cf_loja = '$loja' "; 
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{ $inicial = $line['saldo']; } else { $inicial = array(0,0); }
			/* Saldo FInal */
			$sql = "select max(cf_saldo) as saldo 
					from cf 
					where cf_data >= ".$this->data_ini." and cf_data <= ".$this->data_fim."
					and cf_loja = '$loja' "; 
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{ $acumulado = $line['saldo']; } else { $acumulado = array(0,0); }

			$geral = $acumulado - $inicial;
			return(array($geral,$acumulado));
		}
		function updatex()
			{
				
			}	
	}
?>
