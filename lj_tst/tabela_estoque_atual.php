<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/tabela_estoque_atual.php','Posição atual do estoque'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require("db_temp.php");
//$dd1=date("d/m/Y");
$titulo_site='RSEST';
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$lo1 = '<a href="tabela_estoque_atual.php?dd50=0">';
$lo2 = '<a href="tabela_estoque_atual.php?dd50=1">';

$sql = "select * from ( select pe_produto, sum(q1) as q1, ";
$sql .= "       sum(v1) as v1, sum(q2) as q2, sum(v2) as v2 from ( ";
$sql .= "            select 0 as q1,0 as v1, count(*) as q2,   ";
$sql .= "            round(sum(pe_vlr_custo)*100)/100 as v2,  ";
$sql .= "            pe_produto from produto_estoque  ";
$sql .= "            where pe_status <> 'X' and pe_status <> 'T'  ";
$sql .= "                  and pe_status <> 'F'  ";
$sql .= "            group by pe_produto ";
$sql .= "            union  ";
$sql .= " select count(*) as q1,  ";
$sql .= "            round(sum(pe_vlr_custo)*100)/100 as v1,0,0, ";
$sql .= "            pe_produto from produto_estoque  ";
$sql .= "            where  pe_status = 'F'  ";
$sql .= "            group by pe_produto ";
$sql .= " ) as tabela00 ";
$sql .= " group by pe_produto ) as tabela01 ";
$sql .= " left join produto on pe_produto = p_codigo ";

if (strlen($dd[50]) == 0) { $sql .= " order by pe_produto "; }
if ($dd[50] == '0') { $sql .= " order by pe_produto "; }
if ($dd[50] == '1') { $sql .= " order by p_descricao "; }
$rlt = db_query($sql);

echo '<CENTER><font class="lt5">Relatório de Posição Atual de Estoque</font></CENTER>';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR><TH>'.$lo1.'Código</TH>';
echo '<TH>'.$lo2.'Descrição</TH>';
echo '<TH>Preço</TH>';
echo '<TH>Qtd. Consignado</TH>';
echo '<TH>Custo do Estoque</TH>';
echo '<TH>Qtd. em Estoque</TH>';
echo '<TH>Qtd. Deferida</TH>';
echo '</TR>';
$tot = 0;
$qtd_esqote=0;$qtd_consig=0;
$vlr_estoque=0;$vlr_consig=0;

while ($line = db_read($rlt))
	{
	$tot++;
	echo '<TR '.coluna().'>';
	echo '<TD>';
	echo $line['pe_produto'];
	echo '<TD>';
	echo $line['p_descricao'];
	echo '<TD align="right">';	
	echo number_format($line['p_preco'],2);
	echo '<TD align="right">';
	echo $line['q1'];
	echo '<TD align="right">';
	echo number_format((($line['q1']+$line['q2'])*$line['p_preco']),2);
	echo '<TD align="right">';
	echo $line['q2']; 
	echo '<TD align="center">';
	echo '____';
	echo '</TR>';
	
	$qtd_esqote  += $line['q2'];
	$qtd_consig  += $line['q1'];
	$vlr_estoque += $line['v2'];
	$vlr_consig  += $line['v1'];
	}
echo '</TABLE>';
echo '<TABLE border="0" width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR><td>Total de produtos: '.$tot.'</td></TR>';
echo '</table>';

echo '</TABLE>';
echo '<TABLE border="0" width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR>';
echo '<TH>Qtd. em Estoque<br>Total</TH>';
echo '<TH>Qtd. Consignado<br>Total</TH>';
echo '<TH>Valor Total<br>Produtos em Estoque</TH>';
echo '<TH>Valor Total<br>Produtos Consignados</TH>';
echo '<TH>Valor Total<br>Produtos em Estoque e Consignados</TH>';
echo '</TR>';
echo '<TR>';
echo '<td align="center">'.$qtd_esqote.'</td>';
echo '<td align="center">'.$qtd_consig.'</td>';
echo '<td align="center">'.number_format($vlr_estoque,2).'</td>';
echo '<td align="center">'.number_format($vlr_consig,2).'</td>';
echo '<td align="center">'.number_format($vlr_estoque+$vlr_consig,2).'</td>';
echo '</TR>';

echo '</table>';


echo $hd->foot();
?>


