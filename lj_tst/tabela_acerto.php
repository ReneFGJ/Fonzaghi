<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('tabela_acerto.php','Relatório de acerto'));


$include = '../';
require("../cab_novo.php");

require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

$media_vlr = 150;
$rev_ls = 0;
$rev_up = 0;
$rev_down = 0;
require("db_temp.php");
//$dd1=date("d/m/Y");
$titulo_site='RSACE';

if ($base_name == 'FGHI_MODAS')
{
	$sql = "update kits_consignado set kh_pago = kh_vlr_vend * 0.7,  kh_vlr_comissao_repre = 1 where kh_data >= 20110201 and kh_vlr_comissao_repre = 0";
	$rltt= db_query($sql);
}
?>
<h1>Tabela de Acerto</h1>
<h2>Loja - <?=$nloja_nome;?></h2>
<?
$tabela = "clientes";
$cp = array();
array_push($cp,array('$D8','','Data inicial da Busca ',True,True,''));
array_push($cp,array('$D8','','Data final da Busca ',True,True,''));
array_push($cp,array('$Q cl_clientep:cl_clientep:SELECT cl_clientep FROM clientes GROUP BY cl_clientep ORDER BY cl_clientep','cl_clientep', 'Equipe ',False,True,''));
//array_push($cp,array('$Q pcc:pc_conta:select pc_conta||chr(32) ||pc_descricao as pcc,* from pc_contas order by pc_conta','pc_conta_debito','Conta débito',False,True,''));
array_push($cp,array('$O N:Não&S:SIM','','Detalhado',True,True,''));
echo '<center><font class="lt5">Relatório de Acerto</font></CENTER>';
echo '<CENTER><TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($dd[0]=='' && $dd[1]==''){
	echo $hd->foot();	
	exit;
}

$sql = "SELECT count(*) as acertos, sum(round(kh_pago)) as total ";
$sql .= "  FROM kits_consignado ";
$sql .= "LEFT JOIN clientes ON clientes.cl_cliente=kh_cliente ";
$sql .= "WHERE (kh_acerto >= ".brtos($dd[0])." AND kh_acerto <= ".brtos($dd[1]).") ";

$rlt = db_query($sql);
$line = db_read($rlt);
$acer = $line['acertos'];
if ($acer > 0)
	{ $media_vlr = round($line['total']/$acer*100)/100; }

////////////////////////////////////////////////////////////////////////////////////////////////////

$sql = "SELECT kh_cliente, cl_nome, kh_acerto, kh_pago, kh_log, kh_vlr_comissao, ";
$sql .= "kh_vlr_vend, kh_vlr_forn ";
$sql .= "  FROM kits_consignado ";
$sql .= "LEFT JOIN clientes ON clientes.cl_cliente=kh_cliente ";
$sql .= "WHERE (kh_acerto >= ".brtos($dd[0])." AND kh_acerto <= ".brtos($dd[1]).") ";
$sql .= "	ORDER BY kh_acerto";

//echo $sql;
$rlt = db_query($sql);

echo '<center>De ' .$dd[0]. ' até '.$dd[1].'</center>';
echo '<br>';

//echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';	
echo '<table  class="lt1" width='.$tab_max.' align="center" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF"><TR><TD>';
if ($dd[3] == 'S')
	{
	echo '<TH class="1_th" width="65">Dt. Acerto</TH>';
	echo '<TH class="1_th" width="260">Cliente</TH>';
	echo '<TH class="1_th" width="90">Equipe</TH>';
	echo '<TH class="1_th" width="70">Vlr.<br>Acerto</TH>';
	echo '<TH class="1_th">% venda<br>por valor</TH>';
	echo '<TH class="1_th">Log da<br>Atendente</TH>';
	echo '<TH class="1_th">Comissão</TH>';
	}

$maximo=0;$minimo=99999999;
$valor_total=0;$acertos_zerados=0;$acertos_total=0;
while ($line = db_read($rlt))
	{
	$vlr = $line['kh_pago'];
	
	if ($vlr < $media_vlr) { $rev_down++; }
	if ($vlr >= ($media_vlr*2)) { $rev_up++; }
	if ($vlr < round($media_vlr/2)) { $rev_ls++; }
	
	if ($dd[3] == 'S')
		{
		$sx .= '<TR '.coluna().'>';
		
		$sx .= '<TD class="1_td" width="65" align="center">';
		$sx .= stodbr($line['kh_acerto']);
		
		$sx .= '<TD class="1_td" width="260">';
		$sx .= $line['kh_cliente'];
		$sx .= ' ';
		$sx .= $line['cl_nome'];
		
		$sx .= '<TD class="1_td" align="center" width="90">';
		$sx .= $line['cl_clientep'];
		
		$sx .= '<TD class="1_td" align="right" width="70">';
		$sx .= number_format($line['kh_pago'],2);
		
		$sx .= '<TD class="1_td" align="right">';
		if ($line['kh_vlr_forn'] > 0){
			$sx .= number_format((($line['kh_vlr_vend']/$line['kh_vlr_forn'])*100),0).'%';
		}
		else { 	$sx .= '0%'; }
	
		$sx .= '<TD class="1_td" align="center">';
		$sx .= $line['kh_log'];
		$sx .= '<TD class="1_td" align="right">';
		$sx .= $line['kh_vlr_comissao'].'%';
		$sx .= '</TR>';
	}
	
	/////////////////////Estatística
	$valor_total += $line['kh_pago'];
	($line['kh_pago'] > $maximo)? $maximo=$line['kh_pago']:$maximo;
	($line['kh_pago'] < $minimo)? $minimo=$line['kh_pago']:$minimo;
	($line['kh_pago'] == 0)? $acertos_zerados++:$acertos_zerados;
	$acertos_total++;	
}
$sx .= '<TR>';
$sx .= '<td colspan="7" class="rodapetotal">'.$acertos_total.' ítens</td>';
$sx .= '</TR>';

$sx .= '<TR><td colspan="7">';
$sx .= '<fieldset><legend>Estatística</legend>';
$sx .= '<table  class="1_naoLinhaVertical" width='.$tab_max.' align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';

$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">VALOR DO ACERTO</td>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">R$ '.number_format($valor_total, 2);
$sx .= '</TR>';

$sx .= '<TR '.coluna().'> ';
$sx .= '<td class="legendatotalabaixo" align="left">VALOR MÁXIMO DO ACERTO</td>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">R$ '.number_format($maximo, 2).'</td>';
$sx .= '</TR>';

$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">VALOR MÍNIMO DO ACERTO</TH>';
if ($acertos_total > 0){
	$sx .= '<TD class="totalabaixo" align="right" colspan="6">R$ '.number_format($minimo, 2);
}
else{
	$sx .= '<TD class="totalabaixo" align="right" colspan="6">R$ 0.00';
}
$sx .= '</TR>';

$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">VALOR MÉDIO DO ACERTO</TH>';
if ($acertos_total > 0){
	$sx .= '<TD class="totalabaixo" align="right" colspan="6">R$ '.number_format(($valor_total/$acertos_total), 2);
}
else{
	$sx .= '<TD class="totalabaixo" align="right" colspan="6">R$ 0.00';
}
$sx .= '</TR>';

$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">ACERTOS ZERADOS</TH>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">'.$acertos_zerados;
$sx .= '</TR>';

$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">Nº DE ACERTOS</TH>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">'.$acertos_total;
$sx .= '</TR>';

if ($acertos_total > 0) { $por = number_format(100 * $rev_up / $acertos_total,2).'%'; } else { $por = ' - '; }
$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">ACERTOS COM O DOBRO DA MÉDIA ('.number_format($media_vlr * 2,2).')</TH>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">'.$rev_up.' ('.$por.')';
$sx .= '</TR>';

$submedia = ($acer - $rev_up - $rev_down - $rev_ls )*(-1);
if ($acertos_total > 0) { $por = number_format(100 * ($submedia) / $acertos_total,2).'%'; } else { $por = ' - '; }
$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">ACERTOS ACIMA DA MÉDIA e ABAIXO DO DOBRO ('.number_format($media_vlr,2).')</TH>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">'.($submedia).' ('.$por.')';
$sx .= '</TR>';

if ($acertos_total > 0) { $por = number_format(100 * ($rev_down - $rev_ls) / $acertos_total,2).'%'; } else { $por = ' - '; }
$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">ACERTOS ABAIXO DA MÉDIA ('.number_format($media_vlr,2).')</TH>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">'.($rev_down - $rev_ls).' ('.$por.')';
$sx .= '</TR>';

if ($acertos_total > 0) { $por = number_format(100 * ($rev_ls) / $acertos_total,2).'%'; } else { $por = ' - '; }
$sx .= '<TR '.coluna().'>';
$sx .= '<td class="legendatotalabaixo" align="left">ACERTOS ABAIXO DA METADE DA MÉDIA ('.number_format($media_vlr / 2,2).')</TH>';
$sx .= '<TD class="totalabaixo" align="right" colspan="6">'.($rev_ls).' ('.$por.')';
$sx .= '</TR>';

$sx .= '</table>';
$sx .= '</fieldset>';
$sx .= '</td></TR>';

$sx .= '</TABLE>';
echo $sx;

echo $hd->foot();
?>


