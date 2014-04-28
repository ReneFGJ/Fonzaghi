<?php
class senff_consultora
	{
		var $cliente;
		function setCliente($cliente)
			{
				$this->cliente = $cliente;
			}
		function saldos()
			{
			global $base_name,$base_host,$base_user,$lm, $vm;	
			require("../db_senff.php");
			
			$cliente = $this->cliente;
			$sql = "select sum(ex_valor) as valor from senff_extrato where ex_cliente = '".$cliente."' ";
			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$tot1 = $line['valor'];

			$sql = "select sum(ex_valor) as valor from senff_extrato 
					where (ex_cliente = '".$cliente."' and ex_valor >= 0) or 
					(ex_cliente = '".$cliente."' and ex_doc = 'EXTORN')  ";
			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$tot2 = $line['valor'];
			
			$sx = '
					<table class="tabela00" width="240">
						<TR>
							<TD colspan=2 align="center" class="lt2"><B>Cartão Fidelidade Fonzaghi</B>
						<TR class="lt0">
							<TD align="center" width="120">Pontos Atuais
							<TD align="center" width="120">Pontos Acumulados
						<TR>
							<TD align="center" class="tabela01 lt2">'.number_format($tot1,0,',','.').'
							<TD align="center" class="tabela01 lt2">'.number_format($tot2,0,',','.').'
					</table>
				
			';
			
						
					
			
			return($sx);				
			}		
	}
?>