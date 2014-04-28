<?
    /**
     * Top consultoras
	 * @author Willia Fellipe Laynes <willianlaynes@hotmail.com>
	 * @copyright Copyright (c) 2014 - sisDOC.com.br
	 * @access public
     * @version v.0.14.12
	 * @package _class
	 * @subpackage 
    */
    
class top_consultoras
	{
	
		var $op = array('');
			var $include_class= '../';
		
		function lista_top($d1,$d2,$f2)
			{
				global $base_name,$base_server,$base_host,$base_user,$user;
				
				switch($f2)
				{
					case '0':	$tx2=' desc ';break;		
					case '1':	$tx2=' desc limit 500 ';break;
					case '2':	$tx2=' desc limit 100 ';break;
					case '3':	$tx2=' desc limit 50 ';break;
					case '4':	$tx2=' desc limit 10 ';break;
					default: $tx2=' desc '; break;
						
					
				}
				
				$sql ='	select * from (
						select kh_cliente, count(*) as total, sum(kh_pago) as pago from kits_consignado 
						where kh_acerto >= '.$d1.' and kh_acerto <= '.$d2.'
						group by kh_cliente 
						) as tabela
						left join clientes on kh_cliente = cl_cliente
						order by pago '.$tx2;	
				
				$rlt = db_query($sql);
				$tt=0;
				$sx = '<table><tr>
							<th class="tabelaH" width="100px" align="center">Código</th>
							<th class="tabelaH" width="100px" align="center">Nome</th>
							<th class="tabelaH" width="100px" align="center">Total de Acertos</th>
							<th class="tabelaH" width="100px" align="center">Total pago</th>
						</tr>';
				while($line=db_read($rlt))
				{
					$tt_pago += $line['pago'];
					$tt_acertos += $line['total'];
					$tt_clientes++;	
					
					$sx .= '<tr><td class="tabela01" align="center">'.$line['kh_cliente'].'</td>
								<td class="tabela01" align="left">'.$line['cl_nome'].'</td>
								<td class="tabela01" align="center">'.$line['total'].'</td>
								<td class="tabela01" align="right">'.number_format($line['pago'],2).'</td>	
							<tr>';
				}
				$sx .= '</td></tr></table>';
				$sx ='<center><h3>Total de consultoras '.$tt_clientes.' totalizando o montante de R$ '.number_format($tt_pago,2).' em '.$tt_acertos.' acertos
					  </h3>'.$sx;
				return($sx);
			}
         
		
	}
?>