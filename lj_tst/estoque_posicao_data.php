<?
$breadcrumbs=array();

$include = '../';

require("../cab_novo.php");
require($include."sisdoc_data.php");
require($include."sisdoc_grafico.php");
require($include."sisdoc_debug.php");

require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");


$estilo = '';
$tabela = "";
$cp = array();
array_push($cp,array('$D8','','Data de análise ',True,True,''));

if (strlen($dd[0]) ==0) { $dd[0] = date("01/m/Y"); }
if (strlen($dd[1]) ==0) { $dd[1] = date("d/m/Y"); }

/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<br>';
	echo '<h1>Posição de estoques em</h1>';

	echo '<center><TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';

if ($saved < 1){
	//echo '<font class="lt0">(Parou '.$saved.')</font> '; 
	require("../foot.php");
	exit; 
} else {
	$dd1 = brtos($dd[0]);
	$dd2 = brtos($dd[1]);
	require("db_temp.php");
	
	$sqlx = "
			select status,sum(valor)/100 as valor, sum(pecas) as pecas from (
			select sum(round(pe_vlr_custo)*100) as valor,'A' as status, count(*) as pecas from produto_estoque where 
				pe_data <= $dd1 and pe_lastupdate > $dd1
			union 
			select sum(round(pe_vlr_custo)*100) as valor, 'A', count(*) as pecas from produto_estoque where 
				pe_data <= $dd1 and pe_lastupdate < $dd1 and (pe_status <> 'X' and pe_status <> 'T')
			) as tabela group by status ";
	
	$rlt = db_query($sqlx);
	$line = db_read($rlt);
	echo '<H2>Data de apuração '.$dd[1].'<H2>';
	echo '<center><h2>Total de '.$line['pecas'].' pecas, valor do estoque R$ '.number_format($line['valor'],2).' </h2>';
}
require("../foot.php");
?>