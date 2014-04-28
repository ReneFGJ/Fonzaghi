<?php
    /**
     * JÃ³ias relatorios
     * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v.0.13.49
     * @package Classe
     * @subpackage Financeiro
    */

	class joias_mostruario
		{
					
			var $op = array('');
			var $include_class= '../';
		
		function mostra_relatorio($d1,$d2,$f1,$f2)
			{
				global $base_name,$base_server,$base_host,$base_user,$user;
				require($this->include_class."db_fghi_206_joias.php");	
				switch($f1)
				{
					case '0':	$tx1=' (1=1)'; break;		
					case '1':	$tx1=' (kh_kits like \'00%\' or kh_kits like \'01%\') ';break;
					case '2':	$tx1=' (kh_kits like \'02%\')';break;
					case '3':	$tx1=' (kh_kits like \'05%\')';break;
					case '4':	$tx1=' (kh_kits like \'06%\' or kh_kits like \'07%\') ';break;
					default: $tx1='(1=1)';break;
				}
				
				switch($f2)
				{
					case '0':	$tx2=' desc ';break;		
					case '1':	$tx2=' desc limit 500 ';break;
					case '2':	$tx2=' desc limit 100 ';break;
					case '3':	$tx2=' desc limit 50 ';break;
					case '4':	$tx2=' desc limit 10 ';break;
					default: $tx2=' desc '; break;
						
					
				}
				
				$sql ='	select avg(kh_pago), kh_kits 
						from kits_consignado
						where kh_acerto>='.$d1.' and 
							  kh_acerto<='.$d2.' and 
							  '.$tx1.'  
						group by kh_kits 
						order by avg(kh_pago) '.$tx2;
				$rlt = db_query($sql);
				$tt=0;
				$sx = '<table><tr><th class="tabelaH" width="100px" align="center">Mostruário</th><th  width="100px" align="center">Média</th></tr>';
				while($line=db_read($rlt))
				{
					$tt_avg += $line['avg'];
					$tt_kit++;	
					$sx .= '<tr><td class="tabela01" align="center">'.$line['kh_kits'].'</td>
								<td class="tabela01" align="right">'.number_format($line['avg'],2).'</td><tr>';
				}
				$sx .= '</td></tr></table>';
				$sx ='<center><h3>Total de '.$tt_kit.' mostruários totalizando o montante de R$ '.number_format($tt_avg,2).'
					  </h3>'.$sx;
				return($sx);
			}
         

         
         
}