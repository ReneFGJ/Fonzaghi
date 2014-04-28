<?php
class acertos
	{
		
		function calendario($ano,$mes)
			{
				$mes = strzero(round($mes),2);
				$datai = $ano.$mes.'00';
				$dataf = $ano.$mes.'99';
				$sql = "select count(*) as total, kh_previsao as dia from kits_consignado
					where kh_status = 'A'
					and kh_previsao >= ".$datai." and kh_previsao <= ".$dataf."
					group by kh_previsao
					order by kh_previsao ";

				$rlt = db_query($sql);
				$dias = array();
				$ndias = 30;
				$mes = round($mes);
				if (($mes == 1) or ($mes == 3) or ($mes == 5) or ($mes == 7) or ($mes == 8) or ($mes == 10) or ($mes == 12)) { $ndias = 31; }
				if ($mes == 2) { $mes = 28; }
				if (round($ano/4) == ($ano/4)) { $ndias = 29; }
				for ($r=0;$r <= $ndias;$r++)
					{
						array_push($dias,0);		
					}
				while ($line = db_read($rlt))
					{
						$total = $line['total'];
						$ndia = round(substr($line['dia'],6,2));
						$dias[$ndia] = $total;
					}
				return($dias);
			}
	}
