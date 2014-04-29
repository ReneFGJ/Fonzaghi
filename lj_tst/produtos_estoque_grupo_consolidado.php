<?
$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_debug.php');
require($include.'sisdoc_data.php');
?>
<table width="<?=$tab_max;?>" class="lt1">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$sql = "select count(*) as total, p_codigo, pe_status, p_class_1 from produto_estoque ";
$sql .= "inner join produto on pe_produto = p_codigo ";
$sql .= "where p_class_1 like '".trim($dd[0])."%' ";
$sql .= "group by p_codigo, pe_status, p_class_1 ";
$sql .= "order by  p_class_1, p_codigo ";
$rlt = db_query($sql);

$sld = 0;
$sn = '';
$ar = array(0,0,0,0,0,0,0,0,0,0);
$x = 'X';
while ($line = db_read($rlt))
	{
	$sl = '<TR>';
	$sl .= '<TD>'.$line['p_class_1'].'</TD>';
	$sl .= '<TD>'.stodbr($line['pe_data']).'</TD>';
	$sl .= '<TD>'.$line['pe_status'].'</TD>';
	$sl .= '<TD>'.$line['total'].'</TD>';
	$sl .= '<TD>'.$line['p_codigo'].'</TD>';
	$sl .= '</TR>';
	$sn = $sl. $sn;
	$sta = $line['pe_status'];
	
	if ($x != $line['p_class_1'])
		{
		if ($x != 'X')
		{
		$sa .= '<TR '.coluna().' class="lt4">';
		$sa .= '<TD align="center">'.$x.'</TD>';
		$sa .= '<TD align="center">'.$ar[0].'</TD>';
		$sa .= '<TD align="center">'.$ar[1].'</TD>';
//		$sa .= '<TD align="center">'.$ar[2].'</TD>';
		$sa .= '<TD align="center">'.$ar[3].'</TD>';
//		$sa .= '<TD align="center">'.$ar[4].'</TD>';
//		$sa .= '<TD align="center">'.$ar[5].'</TD>';
		$sa .= '<TD align="center">'.($ar[0]+$ar[1]).'</TD>';
		}
		$ar = array(0,0,0,0,0,0,0,0,0,0);
		$x = $line['p_class_1'];
		$sa .= '</TR>';		
		}
	if ($sta == 'A') { $ar[0] = $ar[0] + $line['total']; }
	if ($sta == 'F') { $ar[1] = $ar[1] + $line['total']; }
	if ($sta == 'D') { $ar[2] = $ar[2] + $line['total']; }
	if ($sta == 'T') { $ar[3] = $ar[3] + $line['total']; }
	}
		$sa .= '<TR '.coluna().' class="lt4">';
		$sa .= '<TD align="center">'.$x.'</TD>';
		$sa .= '<TD align="center">'.$ar[0].'</TD>';
		$sa .= '<TD align="center">'.$ar[1].'</TD>';
//		$sa .= '<TD align="center">'.$ar[2].'</TD>';
		$sa .= '<TD align="center">'.$ar[3].'</TD>';
//		$sa .= '<TD align="center">'.$ar[4].'</TD>';
//		$sa .= '<TD align="center">'.$ar[5].'</TD>';
		$sa .= '<TD align="center">'.($ar[0]+$ar[1]).'</TD>';
	$sa .= '</TR>';
?>
<TABLE width="710" align="center" border="1" class="lt1">
<TR><TD colspan="7" class="lt4"><?=$dd[0];?> - Posição consolidada</TD></TR>
<TR>
	<TH>Grupo</TH>
	<TH>Est.Loja</TH>
	<TH>Consignado</TH>
	<TH>Faturado</TH>
	<TH>Saldo estoque</TH>
</TR>
<?=$sa;?>
</TABLE>
<? echo $hd->foot();	?>