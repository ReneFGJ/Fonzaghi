<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produtos_checkin.php','Produtos para checkin'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
?>
<table width="<?=$tab_max;?>" class="lt1">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$st = '@';
$sql = "select * from produto_estoque ";
$sql .= " inner join produto on p_codigo = pe_produto ";
$sql .= " where pe_status = '".$st."' ";
$sql .= " order by pe_data, p_codigo ";
$rlt = db_query($sql);
$ini = 0;
while ($line = db_read($rlt))
	{
		$ini++;
		$sn .= '<TR '.coluna().'>';
		$sn .= '<TD>';
		$sn .= $line['pe_ean13'];
		$sn .= '<TD>';
		$sn .= $line['pe_produto'];
		$sn .= '<TD>';
		$sn .= $line['p_descricao'];
		$sn .= '<TD align="right">';
		$sn .= stodbr($line['pe_data']);
		$sn .= '</TD>';
		$sn .= '</TR>';
	}
?>
<center><h1><B>Produtos para <I>Check-in</I></B></h1></center>
<TABLE width="710" align="center" border="0" class="lt1">
<TR><TH>EAN13</TH>
	<TH>PROD</TH>
	<TH>Descricao</TH>
	<TH>Data</TH></TR>
<?=$sn;?>
<TR><TD colspan="3">Total de <B><?=$ini;?></B> produtos</TD></TR>
</TABLE>
<? echo $hd->foot();	?>