<?php
class loja
	{
		
	var $id;
	
	function bases_lojas()
		{
			$bs = array();
			array_push($bs,'db_fghi_206_sensual.php');
			array_push($bs,'db_fghi_206_oculos.php');
			array_push($bs,'db_fghi_206_TST.php');
			array_push($bs,'db_fghi_206_ub.php');
			array_push($bs,'db_fghi_206_modas.php');
			array_push($bs,'db_fghi_206_joias.php');
			array_push($bs,'db_fghi_206_express_joias.php');
			array_push($bs,'db_fghi_206_express.php');
			return($bs);			
		}
	function valida_entrada_datas($dd1,$dd2)
		{
			if ($dd2 < $dd1) { return(0); }
			if ($dd2 < round(date("Y").'0101'))	 { return(0); }
			return(1);
		}
		
	function solicita_periodo_de_ate_lojas()
		{
			global $dd;
			$cp = array();
			$loja = '0:Todas';
			array_push($cp,array('$H8','','',False,True));
			array_push($cp,array('{','','Alertar acertos coletivos',False,False));
			array_push($cp,array('$D8','','de',True,True));
			array_push($cp,array('$D8','','para',True,True));
			
			array_push($cp,array('$O '.$loja,'','Lojas',True,True));
			array_push($cp,array('}','','Alertar acertos coletivos',False,False));
			return($cp);
		}
	
	function altera_acerto_coletivo($de,$para)
		{
			$sql = "select count(*) as total from kits_consignado where kh_previsao = $de and kh_status = 'A' ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			
			$sql = "update kits_consignado set kh_previsao = $para 
			where kh_previsao = $de and kh_status = 'A' ";
			$rlt = db_query($sql);
			return('<BR>Total de '.$total.' acertos afetados');			
		}
	function busca_siglas(){
		global $base_name,$base_server,$base_host,$base_user;
		require("../db_fghi.php");
		
		$sql = " select * from empresa";
		$sql.= " where e_codigo='".$this->id."'";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		
		$sx=$line['e_sigla'];
		echo $sx;
		return($sx);
		
	}	
		
	}
?>
