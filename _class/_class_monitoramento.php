<?php
class monitoramento
	{
	function log($cliente='')
		{
			global $user;
			$usr = $user->user_log;
			$data = date("Ymd");
			$hora = date("H:i:s");
			
			$sql = "insert into _log_cadastro_".date("Y")."
					(
					log_cliente, log_data, log_hora, log_user
					) values (
					'$cliente',$data,'$hora','$usr') ";
			$rlt = db_query($sql);
			return(1);
		}	
	}
?>
