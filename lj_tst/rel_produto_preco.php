<?

$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/rel_produto_preco.php','Tabela de produtos com preço'));

$include = '../';
require($include."cab.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require("db_temp.php");
require("func_kitnr.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$lo1 = '<a href="rel_produto_preco.php?dd50=0">';
$lo2 = '<a href="rel_produto_preco.php?dd50=1">';
$lo3 = '<a href="rel_produto_preco.php?dd50=2">';
$lo4 = '<a href="rel_produto_preco.php?dd50=3">';

$sql = "select * from produto ";
$sql .= " where p_ativo = 1 ";
if (strlen($dd[50]) == 0) { $sql .= " order by p_codigo "; }
if ($dd[50] == '0') { $sql .= " order by p_codigo "; }
if ($dd[50] == '1') { $sql .= " order by p_descricao "; }
if ($dd[50] == '2') { $sql .= " order by p_ean13 "; }
if ($dd[50] == '3') { $sql .= " order by p_custo "; }
$rlt = db_query($sql);

echo '<CENTER><font class="lt5">Tabela de Preço dos produtos</font></CENTER>';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR><TH>'.$lo1.'Código</TH>';
echo '<TH>'.$lo2.'Descrição</TH>';
echo '<TH>'.$lo3.'EAN13</TH>';
echo '<TH>'.$lo4.'Preço</TH>';
echo '<TH>Fm</TH>';
echo '<TH>Grupo</TH>';
echo '</TR>';
$tot = 0;
while ($line = db_read($rlt))
	{
	$tot++;
	echo '<TR '.coluna().'>';
	echo '<TD>';
	echo $line['p_codigo'];
	echo '<TD>';
	echo $line['p_descricao'];
	echo '<TD>';
	echo $line['p_ean13'];
	echo '<TD align="right">';
	$vlr = $line['p_custo'];
	
	$peca_prec = (round($vlr * markup($vlr)*10)/10);
	echo number_format($peca_prec,2);

	echo '<TD align="center">x';
	$vlr = $line['p_custo'];
	echo number_format(markup($vlr),1);
	echo '<TD align="center">';
	echo $line['p_class_1'];

	echo '</TR>';
	}
echo '</TABLE>';
echo '<TABLE border="0" width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR><td>Total de produtos: '.$tot.'</td></TR>';
echo '</table>';

//echo 'Total de produtos '.$tot;

echo $hd->foot();	
?>