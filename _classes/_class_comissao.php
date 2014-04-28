<?php
class comissao
	{
		
		function troca_comissao_up($most,$clie,$tp,$vref)
			{ 
				$sql = "update produto_consignado ";
				$sql .= " set ";
				$sql .= " pe_cliente = '".$clie."', ";
				$sql .= " pe_vref = ".$vref;
				$sql .= " where pe_mostruario = '".$most."' ";
				$sql .= " and pe_tipo = '".$tp."' ";
				echo '<BR>'.$sql;
				$rlt = db_query($sql);
			}	
		function troca_comissao($most,$clie,$comi)
			{
				$sql = "select * from vref ";
				$sql .= " where ref_data < ".date("Ymd");
				$sql .= " order by ref_data desc ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$vr = $line;
					}
				$aa = 1;
				if ($comi == 30) { $aa = 0.65; }
				if ($comi == 40) { $aa = 0.825; }
				$v=$this->troca_comissao_up($most,$clie,'0',$vr[3]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'1',$vr[4]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'2',$vr[5]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'3',$vr[6]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'4',$vr[7]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'5',$vr[8]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'6',$vr[9]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'7',$vr[10]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'8',$vr[11]*$aa);
				$v=$this->troca_comissao_up($most,$clie,'9',$vr[12]*$aa);
				
				$sql = "update kits_consignado set kh_comissao = ".$comi;
				$sql .= " where kh_kits='".$most."' and kh_status = 'A' ";
				$rlt = db_query($sql);
				return(1);	
			}
	}
?>
