<?
    /**
     * Calendario Fonzaghi
     * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package Calendario
     * @subpackage class
    */
class calendario
	{
	function calendario_acerto($ano,$mes)
		{
			$data1 = $ano.strzero($mes,2).'00';
			$data2 = $ano.strzero($mes,2).'99';

			$am = array();
			$ar = array();
			$ap = array();

			/* Calendário Marcado */			
			$sql = "select * from calendario where cal_data >= $data1 and cal_data <= $data2 ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$dia = round(substr($line['cal_data'],6,2));
					$ap[$dia] = $ap[$dia] + $line['cal_liberado']; 
				}
				
			/* Calendario realizado */
			$sql = "select * from kits_consignado 
					where (kh_previsao >= $data1 and kh_previsao <= $data2)
					or (kh_acerto >= $data1 and kh_acerto <= $data2)
			";
			$rlt = db_query($sql);

			for ($r=0;$r <= 31;$r++)
				{
					array_push($am,0); array_push($ar,0); array_push($ap,0);
				}
			$max = 100;
			while ($line = db_read($rlt))
				{
					$sta = $line['kh_status'];
					
					$data3 = $line['kh_previsao'];
					$data4 = $line['kh_acerto'];
					
					if (($data3 >= $data1) and ($data3 <= $data2))
						{
						$dia = round(substr($line['kh_previsao'],6,2));
						$am[$dia] = $am[$dia] + 1;	
						if ($am[$dia] > $max) { $max = $am[$dia]; }
						}	
					
					if (($data4 >= $data1) and ($data4 <= $data2))
						{
						$dia = round(substr($line['kh_acerto'],6,2));
						$ar[$dia] = $ar[$dia] + 1;
						if ($ar[$dia] > $max) { $max = $ar[$dia]; }
						}
				}
				
			$sa = '<TR align="center">'; 
			$sb = '<TR align="center">'; 
			$sd = '<TR align="center">';
			$sc = '<TR align="center">';
			$sh = '<TR align="center" valign="bottom">';
			$t1 = 0;
			$t2 = 0;
			$t3 = 0;
			for ($r=1;$r < count($am);$r++)
				{
					$sd .= '<TD width="3%" class="tabela01">'.$r;
					$sa .= '<TD>'.$am[$r];
					$sb .= '<TD>'.$ar[$r];
					$sc .= '<TD>'.$ap[$r];
					
					$sh .= '<TD height="'.$max.'">';
					$sh .= '<img src="../img/bloco_pink.png" width="10" height="0'.$ap[$r].'" title="programado">';
					$sh .= '<img src="../img/bloco_azul.png" width="10" height="'.$am[$r].'" title="previsto">';
					$sh .= '<img src="../img/bloco_laranja.png" width="10" height="'.$ar[$r].'" title="realizado">';
					$t1 = $t1 + $am[$r];
					$t2 = $t2 + $ar[$r];
					$t3 = $t3 + $ap[$r];
				}
			$sd .= '<TD>Total';
			$sa .= '<TD bgcolor="#8080FF"><I>'.$t1.'</i>';
			$sb .= '<TD bgcolor="orange"><I>'.$t2.'</i>';
			$sc .= '<TD bgcolor="pink"><I>'.$t3.'</i>';
			$ss = '<table width="100%" class="tabela00">'.$sd.$sc.$sa.$sb.$sh.'</table>';
			
			$ss .= '<BR>
			<BR><B>Pink</B> - Programado - Quantiadde de acertos definidos no calendário previamente
			<BR><B>Previsto</B> - Previsto - Acertos agendados com previsão para a data
			<BR><B>Realizado<B> - Realizado - Quantidade de acertos efetivados na data
			<BR><BR>
			';
			
			return($ss);
			
		}
    function mostra_calendario($ano,$mes)
		{
			$dias = array();
			for ($r=0;$r < 32;$r++) { array_push($dias,''); }
			$sql = "select * from calendario 
					where cal_data >= ".$ano.$mes."00 and cal_data <= ".$ano.$mes."99 
				";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$ddia = round(substr($line['cal_data'],6,2));
					$dias[$ddia] = $line;
				}

			$di = mktime(0,0,0,$mes,1,$ano);
			$da = $di;
			$wd = date("w",$da);
			
			$sx = '<table border=1 width="400" class="tabela00">';
			$sx .= '<TR><TD colspan="7" align="center">'.$this->mes_nome($mes).'/'.date("Y",$da).'</td></tr>';
			$sx .= '<TR>';
			
			for ($r=0;$r < $wd; $r++)
				{
					$sx .= '<TD align="center" '.$cor.'>-';
				}
	
			while (date("m",$di)==date("m",$da))
				{
					
					$ddia = round(date("d",$da));
					$ln = $dias[$ddia];
					if (!is_array($ln))
						{
							$this->calendario_grava_dia($da);
							$ln = array();
						}
					
					/* Cores */
					$wd = date("w",$da);
					$cor = '';
					if ($wd==0 or $wd==6) { $cor = ' style="background-color: #C0C0C0; "'; } 
					if ((date("w",$da)==0) and (date("d",$da) != 1)) { $sx .= '<TR>'; }
					
					$status = $ln['cal_ativo'];
					if ($status == '2') { $cor = ' style="background-color: #FFC0C0; "'; }
					
					/* Mostra calendario */
					$sx .= '<TD align="center" '.$cor.'>';
					$sx .= date("d",$da);
					$da = $da + 24*60*60;
				}
			$sx .= '</table>';
			return($sx);
		}
	function calendario_grava_dia($dia)
		{
			$abre = array('00:00','09:00','08:00','08:00','08:00','08:00','00:00');
			$fecha = array('00:00','19:00','19:00','19:00','19:00','18:00','00:00');
			$data = date("Ymd",$dia);
			$wd = date("w",$dia);
			$horai = $abre[$wd];
			$horaf = $fecha[$wd];
			$desccricao = '';
			$ativo = 1;
			if ($wd==0 or $wd == 6) { $ativo = 0; }
			$sql = "select * from calendario where cal_data = ".$data;
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					
				} else {
					$sql = "insert into calendario 
							(
								cal_data, cal_weekday, cal_ativo,
								cal_horario_ini, cal_horario_fim,
								cal_descricao
							) values (
								$data, $wd, '$ativo',
								'$horai','$horaf',
								'$desc'
							)
					";
					$rlt = db_query($sql);
					
				}
		}
	 function dia_da_semana($data)
	 	{
	 		
	 	}
	 function mes_nome($mes=0)
	 	{
	 		switch ($mes)
				{
				case 1: return('Janeiro'); break;
				case 2: return('Fevereiro'); break;
				case 3: return('Março'); break;
				case 4: return('Abril'); break;
				case 5: return('Maio'); break;
				case 6: return('Junho'); break;
				case 7: return('Julho'); break;
				case 8: return('Agosto'); break;
				case 9: return('Setembro'); break;
				case 10: return('Outubro'); break;
				case 11: return('Novembro'); break;
				case 12: return('Dezembro'); break;
				default: return('erro'); break;
				}
	 	}
	 function weekday($nd='')
	 	{
	 		
	 	}
	}
    
?>
	