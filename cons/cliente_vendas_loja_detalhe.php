<?
$loja=$_GET['dd99'];
$cliente=$_GET['dd0'];

if (strlen($loja) <=0 && strlen($cliente) <= 0){
	exit;
}

$include = '../';
require("../db.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require("../css/letras.css");
?>
<head>
<title>Relatório detalhado de vendas</title>
<link rel="STYLESHEET" type="text/css" href="">
</head>

<?



switch ($loja){
	case 'J':	
		require("../db_fghi_206_joias.php");
		break;
	case 'O':	
		require("../db_fghi_206_oculos.php");
		break;
	case 'S':	
		require("../db_fghi_206_sensual.php");
		break;
	case 'U':	
		require("../db_fghi_206_ub.php");
		break;
	case 'M':	
		require("../db_fghi_206_modas.php");
		break;
	case 'E':	
		require("../db_fghi_206_express.php");
		break;
	case 'G':	
		require("../db_fghi_206_express_joias.php");
		break;				
}
loja_detalhe($cliente);
//require("../foot.php");	

function loja_detalhe($cliente){
	$sql = "SELECT kh_acerto, kh_pago, kh_pc_forn, kh_pc_vend, kh_vlr_forn, kh_vlr_vend";
	$sql .= " FROM kits_consignado";
	$sql .= " where kh_status= 'B' and kh_cliente = '".$cliente."'";
	$sql .= " order by kh_acerto desc";
	//echo $sql;
	
	$rlt = db_query($sql);
	
	$index=0;
	$ano=substr(date("Ymd"),0,4);
	$mes=substr(date("Ymd"),4,2);

	$mes_registro=0;	
	$ano_registro=0;
	$mes_anterior=0;
	$ano_anterior=0;

	echo '<CENTER><font class="lt5">Detalhamento dos dados do gráfico</font></CENTER>';
	echo '<table border="0"  class="lt1" width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<tr>';
	echo '<th class="lt1" width="80">Lançamento</th>';
	echo '<th class="lt1">Mês</th>';
	echo '<th class="lt1" width="80">Vlr.<br>Acerto</th>';
	echo '<th class="lt1" width="80">Peças</th>';
	echo '<th class="lt1" width="80">Vlr.<br>Fornecido</th>';
	echo '<th class="lt1" width="80">%</th>';	
	echo '</tr>';
	$tot_pago=0;
	$tot_fornecido=0;
	$tot_peca=0;
	
	while ($line = db_read($rlt)){
		$ano_registro=substr($line['kh_acerto'],0,4);
		$mes_registro=substr($line['kh_acerto'],4,2);
		$tot_pago+=$line['kh_pago'];
		$tot_fornecido+=$line['kh_vlr_forn'];
		$tot_peca+=$line['kh_pc_vend'];

		while ($ano_registro < $ano){
			if ($ano_anterior == $ano && $mes_anterior == $mes){
				//echo '<tr><td>ano anterior: '.$ano_anterior.'</td></tr>';
				//echo '<tr><td>mes anterior: '.$mes_anterior.'</td></tr>';
				//$ano--;
				$mes--; //mes dezembro
			}
				
			while($mes > 0){ //do mes atual até janeiro
				$index++;
				echo '<tr'.coluna().'>';
				echo '<td class="1_td" width="80" align="center">'.$index.'</td>';
				if (strlen($mes)==1)
					echo '<td class="1_td" align="center">'.$ano.'-0'.$mes.'</td>';
				else
					echo '<td class="1_td" align="center">'.$ano.'-'.$mes.'</td>';
				echo '<td class="1_td" align="right"> - </td>';
				echo '<td class="1_td" align="right"> - </td>';
				echo '<td class="1_td" align="right"> - </td>';
				echo '<td class="1_td" align="right"> - </td>';
				echo '</tr>';

				$mes--;
				if ($index >= 24){break;}
					
			}
			if ($index >= 24){break;}
			$mes=12; //mes dezembro
			$ano--;
		}

		if ($index >= 24){break;}		
		
		while ($mes_registro < $mes){

			if ($mes_anterior == $mes){
				//echo '<tr><td>mes anterior '.$mes_anterior.'</td></tr>';
				$mes--;
			}
			
			if ($mes_registro >= $mes){break;}
			
			$index++;	
			echo '<tr'.coluna().'>';
			echo '<td class="1_td" width="80" align="center">'.$index.'</td>';
			if (strlen($mes)==1)
				echo '<td class="1_td" align="center">'.$ano.'-0'.$mes.'</td>';
			else
				echo '<td class="1_td" align="center">'.$ano.'-'.$mes.'</td>';
			echo '<td class="1_td" align="right"> - </td>';
			echo '<td class="1_td" align="right"> - </td>';
			echo '<td class="1_td" align="right"> - </td>';
			echo '<td class="1_td" align="right"> - </td>';
			echo '</tr>';

			$mes--;
			if ($mes_registro == $mes){break;}
	
			if ($index >= 24){break;}
		}
		if ($index >= 24){break;}
		
		
		
		//if ($mes_registro == $mes)
		{		
		$index++;
		echo '<tr'.coluna().'>';
		echo '<td class="1_td"  width="80" align="center">'.$index.'</td>';
		echo '<td class="1_td" align="center" width="80">'.$ano_registro.'-'.$mes_registro.'</td>';
		echo '<td class="1_td" align="right">'.number_format($line['kh_pago'],2).'</td>';
		echo '<td class="1_td" align="right">'.$line['kh_pc_vend'].'</td>';
		echo '<td class="1_td" align="right">'.number_format($line['kh_vlr_forn'],2).'</td>';
		if ($line['kh_vlr_forn'] > 0)
			echo '<td class="1_td" align="right">'.number_format((($line['kh_pago']/$line['kh_vlr_forn'])*100),1).'%</td>';
		else
			echo '<td class="1_td" align="right">0.0%</td>';
		echo '</tr>';
		$ano_anterior=$ano_registro;
		$mes_anterior=$mes_registro;
		}
		
	}//while
	
	while ($index < 24){
		$index++;
		if ($mes_anterior == $mes){
			$mes--;
		}
			
		if ($mes <= 0){
			$ano--;
			$mes=12;
		}
		
		echo '<tr'.coluna().'>';
		echo '<td class="1_td" width="80" align="center">'.$index.'</td>';
		if (strlen($mes)==1)
			echo '<td class="1_td" align="center">'.$ano.'-0'.$mes.'</td>';
		else
			echo '<td class="1_td" align="center">'.$ano.'-'.$mes.'</td>';
		echo '<td class="1_td" align="right"> - </td>';
		echo '<td class="1_td" align="right"> - </td>';
		echo '<td class="1_td" align="right"> - </td>';
		echo '<td class="1_td" align="right"> - </td>';
		echo '</tr>';
		$mes--;
	}
	echo '<tr>';
	echo '<td class="legendatotal" colspan="2">Totais:</td>';
	echo '<td class="total">'.number_format($tot_pago,2).'</td>';
	echo '<td class="total">'.$tot_peca.'</td>';
	echo '<td class="total">'.number_format($tot_fornecido,2).'</td>';
	echo '<td class="total">-</td>';
	echo '</tr>';
	echo '</table>';
}
?>