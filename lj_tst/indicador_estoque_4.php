<?
$include = '../';
require("../cab_novo.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require("db_temp.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
<TR><TD class="lt3">Posição do estoque quantitativo</TD></TR>
<TR><TD>
<?
//$pe_nrped = '08540/10';

echo '>>> <B>Pedido '.$pe_nrped.'</B>';
echo '<BR>';

$sql = "select count(*) as total, pe_status, sum(pe_vlr_custo) as custo, avg(pe_vlr_custo) as media from produto_estoque ";
if (strlen($pe_nrped) > 0) { $sql .= " where pe_nrped = '".$pe_nrped."' "; }
$sql .= " group by pe_status ";
$rlt = db_query($sql);
$tot1=0;
$tot2=0;
while ($line = db_read($rlt))
	{
	$tot1=$tot1 + $line['total'];
	$tot2=$tot2 + $line['custo'];
	
	$sta = $line['pe_status'];
	$status = $sta;
	if ($sta == '@') { $status = 'Etiquetas emitidas, aguardando check-in'; }
	if ($sta == 'F') { $status = 'Fonecidas'; }
	if ($sta == 'A') { $status = 'Em estoque'; }
	if ($sta == 'B') { $status = 'Devolvido (troca)'; }
	if ($sta == 'T') { $status = 'Faturada'; }
	if ($sta == 'X') { $status = 'Entrada cancelada'; }
	$sa .= '<TR '.coluna().'>';
	$sa .= '<TD>';
	$sa .= $status;
	$sa .= '<TD align="right">';
	$sa .= $line['total'].'</TD>';

	$sa .= '<TD align="right">';
	$sa .= number_format($line['media'],2).'</TD>';

	$sa .= '<TD align="right">';
	$sa .= number_format($line['custo'],2).'</TD>';

	$sa .= '</TR>';
	}
?>
<table width="100%">
	<TR>
		<TH>Descrição</TH>
		<TH>Quant.</TH>
		<TH>Cust.Médio</TH>
		<TH>Custo total</TH>
	</TR>
	<?=$sa;?>
	<TR>
		<TD></TD>
		<TD align="right"><?=number_format($tot1,2);?></TD>
		<TD></TD>
		<TD align="right"><?=number_format($tot2,2);?></TD>
	</TR>
</table>
</TD></TR>
<TR><TD><fieldset><legend>Metodologia</legend>

</fieldset>
</TD></TR>
</table>

<? echo $hd->foot();	?>