<?php
array_push($breadcrumbs, array('index.php','Loja'));


$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_debug.php');
require('../_classes/_class_produto.php');
global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require("db_temp.php");

if (strlen($dd[3]) == 0) { $dd[3] = date("d/m/Y"); }
if (strlen($dd[2]) == 0) { $dd[2] = date("d/m/").(date("Y")-1); }

	$cl = new produto;
	$cp = array();
	array_push($cp,array('$H8','','',False,True));
	array_push($cp,array('$S5','','Numero mostruario',True,True));
	array_push($cp,array('$D8','','Data Inicial',True,True));
	array_push($cp,array('$D8','','Data final',True,True));
	$tabela = '';
	
	$http_edit = 'historico_mostruario.php';
	$http_redirect = '';
	$tit = 'Consulta de Mostruario';

	/** Comandos de Edi��o */
	echo '<h1>'.$tit.'</h1><center>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
if ($saved > 0)
	{
		$kits = strzero($dd[1],5);
		$dti = brtos($dd[2]);
		$dtf = brtos($dd[3]);
		$sql = "select * from kits_consignado 
			where kh_kits = '$kits'
			and ((kh_acerto >= $dti and kh_acerto <= $dtf)
			or (kh_data >= $dti and kh_data <= $dtf))
			order by kh_data
		";
		echo '<H2>Historico do mostruario '.$kits.'</h2>';
		require('db_temp.php');
		$rlt = db_query($sql);
		echo '<table class="lt1" width="'.$tab_max.'">';
		echo '<TR>';
		echo '<TH>Data<TH>Log forn.<TH>Data Acerto<TH>Log Acerto<TH>Cliente<TH>Venda<TH>Com.<TH>Log Monta<TH>Status';
		$sta = array('@'=>'Cadastrado','A'=>'Fornecido','B'=>'Acertado');
		$tot = 0;
		while ($line = db_read($rlt))
		{
			$tot++;
			$status = $line['kh_status'];
			echo '<TR '.coluna().'>';
			
			if ($status == '@')
				{
					echo '<TD>';
					echo stodbr($line['kh_data']);
										
					echo '<TD colspan=6 align="center">';
					echo '= disponivel =';					
				} else {
					echo '<TD>';
					echo stodbr($line['kh_fornecimento']);
					echo '<TD align="center">';
					echo ($line['kh_log']);

					echo '<TD>';
					echo stodbr($line['kh_data']);
					echo '<TD align="center">';
					echo ($line['kh_log_acerto']);
					echo '<TD align="center">';
					echo ($line['kh_cliente']);
					echo '<TD align="right">';
					echo number_format($line['kh_pago'],2);
					echo '<TD>';
					echo ($line['kh_comissao']).'%';
				}
			echo '<TD align="center">';
			echo $line['kh_monta'];
			echo '<TD>';
			echo $sta[$status];
			
			
			$ln = $line;
		}
		echo '<TR><TD colspan=7>total de '.$tot.' movimentos';
		echo '</table>';
	}
		
?>

