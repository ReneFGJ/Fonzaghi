<?
$include = '../';
require("../cab_novo.php");
require("db_temp.php");

require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_debug.php');

require("../_classes/_class_produto.php");
require("../_classes/_class_cardex.php");

require("../_class/_class_produto_categoria.php");
$prodc = new categoria;

$produto = new produto;
$produto->p_codigo = $dd[0];

echo '<center>';
echo $produto->mostra_produto();

require('../db_fghi.php');
$sql = "select * from fornecedores where fo_codfor = '".$produto->p_fornecedor."' ";
$rlt = db_query($sql);
if ($fline = db_read($rlt))
		{ $fornec = $fline['fo_nomefantasia']; }
		
require("db_temp.php");
$sql = "select count(*) as total, pe_status, sum(pe_vlr_vendido) as vendido ";
$sql .= " from produto_estoque where pe_produto = '".$produto->p_codigo."' and pe_status <> 'X' ";
$sql .= " group by pe_status order by pe_status ";
$rlt = db_query($sql);
$cp1 = array();
$cp2 = array();


	while($xline = db_read($rlt))
	{
		$sta = $xline['pe_status'];
		array($cp1,$xline['pe_status']);
		array($cp2,$xline['total']);
		$sr .= '<TR '.coluna().'>';
		$sr .= '<TD>';
		$sr .= $xline['pe_status'];
		$sr .= '<TD>';
		if ($sta == '@') { $sta = 'Etiquetando'; }
		if ($sta == 'F') { $sta = 'Fornecido(s)'; $to1 = $to1 + $xline['total']; }
		if ($sta == 'T') { $sta = 'Vendido(s)';  $to2 = $to2 + $xline['total'];}
		if ($sta == 'A') { $sta = 'Em estoque (não fornecido)';  $to3 = $to3 + $xline['total']; }
		if ($sta == 'B') { $sta = 'Em estoque (retorno)';  $to3 = $to3 + $xline['total'];}
		$sr .= $sta;
		$sr .= '<TD align="right">';
		$sr .= $xline['total'];
		$sr .= '<TD align="right">';
		$sr .= number_format($xline['vendido'],2);
		$sr .= '</TR>';
	}

/*****
 * Categorização 
 */
echo $prodc->form_categoria($produto->p_codigo);

/**********************************/
////////////////////////////////////////////////////////////////////////////////////////////// Movimento de estoque
$data = date("Ym").'01';
$im = 0;
$sql = '';
$cr = array();
while ($im < 13)
	{
	array_push($cr,array(substr($data,0,6),0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0));
	if (strlen($sql) > 0) { $sql .= ' union '; }
	$sql .= "select count(*) as total, '".substr($data,0,6)."' as data, ";
	$sql .= " pl_status from produto_log_".substr($data,0,6)." ";
	$sql .= " where pl_produto = '".$produto->p_codigo."' and pl_status <> 'X'  group by pl_status ";
	$data = dateadd('m',-1,$data);
	$im++;
	}
$sql .= " order by data ";
$rlt = db_query($sql);
$sm = '';
$max =200;
while ($line = db_read($rlt))
	{
	$mes = $line['data'];
	$vlr = $line['total'];
	$stz = $line['pl_status'];
	if (($vlr > $max) and (($stz != 'H') and ($stz != '@'))) { $max = $vlr; echo '['.$stz.']'; }
	for ($pos = 0;$pos < 13;$pos++)
		{
		$fl = 9;
		if ($stz == '@') { $fl = 1; }
		if ($stz == 'A') { $fl = 2; }
		if ($stz == 'B') { $fl = 2; }
		if ($stz == 'D') { $fl = 3; }
		if ($stz == 'F') { $fl = 4; }
		if ($stz == 'T') { $fl = 5; }
		if ($cr[$pos][0] == $mes)
			{ 
			$cr[$pos][$fl] = $vlr;
			}
		}
	}
$c1 = '';
$c2 = '';
$c3 = '';
$c4 = '';
$c5 = '';
$c6 = '';
$c7 = '';
$c0 = '';
$cA = '';
$sz = 200;
for ($for = (count($cr)-1);$for >= 0; $for--)
	{
	$ind = $cr[$for][4]+$cr[$for][5];
	if ($ind > 0)
		{
		$ind = number_format(100*$cr[$for][5] / $ind,1).'%';
		}
	$c0 .= '<TD align="center" width="5%">'.substr($cr[$for][0],4,2).'/'.substr($cr[$for][0],2,2).'</TD>';
	$c1 .= '<TD align="center">'.$cr[$for][1].'</TD>';
	$c2 .= '<TD align="center">'.($cr[$for][2]+$cr[$for][3]).'</TD>';
	$c3 .= '<TD align="center">'.$cr[$for][3].'</TD>';
	$c4 .= '<TD align="center">'.$cr[$for][4].'</TD>';
	$c5 .= '<TD align="center">'.$cr[$for][5].'</TD>';
	$c6 .= '<TD align="center">'.$ind.'</TD>';

	$sz1 = round($cr[$for][1] / $max *$sz);
	$sz2 = round(($cr[$for][4]) / $max *$sz);
	$sz3 = round($cr[$for][5] / $max *$sz);

	$cA .= '<TD align="center" valign="bottom">';
	$cA .= '<img src="img/nada_01.png" width="7" height="'.$sz1.'" alt="" border="1">';
	$cA .= '<img src="img/nada_02.png" width="7" height="'.$sz2.'" alt="" border="1">';
	$cA .= '<img src="img/nada_03.png" width="7" height="'.$sz3.'" alt="" border="1">';
	}
?>
<?=$sa;?>
<table width="500" class="lt1" border="1">
<TR><TD>
<table width="500" class="lt1">
<?=$sr;?>
</table>
<TR><TD><table width="100%" class="lt1"><TR align="center">
	<TD>Na loja: <B><?=$to3;?></TD>
	<TD>Vendido: <B><?=$to2;?></TD>
	<TD>Fornecidos: <B><?=$to1;?></TD>
	<TD>Em estoque: <B><?=($to3+$to1);?></TD>
</TR></table></TD></TR>
</table>
<BR><BR>
<table cellpadding="3" cellspacing="0" width="<?=$tab_max;?>" class="lt1" border="1">
<tr><TD>Mês</TD><?=$c0;?></tr>
<tr><TD>Entrada</TD><?=$c1;?></tr>
<tr><TD>Giro</TD><?=$c4;?></tr>
<tr><TD>Vendidas</TD><?=$c5;?></tr>
<tr valign="bottom"><TD height="200">gráfico</TD><?=$cA;?></tr>
<tr><TD>Índice (<?=$max;?>)</TD><?=$c6;?></tr>
</table><br>
<center><a class="botao-geral" href="produtos_atendentes.php?dd50=<?=$produto->p_codigo;?>">Vendas por Atende</a></center><br>
<center><H3>Posição Histórica</H3></center>

<?
$sql = "select * ";
$sql .= " from produto_estoque ";
$sql .= " inner join clientes on cl_cliente = pe_cliente ";
$sql .= " and (pe_status = 'F' or pe_status = 'T') ";
$sql .= " where pe_produto = '".$produto->p_codigo."' ";
$sql .= " order by pe_status ";

$rlt = db_query($sql);
echo '<table width="870" class="lt1" border="1" cellpadding="3" cellspacing="0">';
echo '<TR><TH>data</TH><TH>nome</TH><TH>EAN13</TH><TH>Cliente</TH><TH>Status</TH><TH>Log</TH></TR>';
while ($line = db_read($rlt))
	{
	echo '<TR>';
	echo '<TD align="center">';
	echo stodbr($line['pe_lastupdate']);
	echo '<TD>';
	echo $line['cl_nome'];
	echo '<TD align="center">';
	echo $line['pe_ean13'];
	echo '<TD align="center">';
	echo $line['cl_cliente'];
	echo '<TD align="center">';
	echo $line['pe_status'];
	echo '<TD align="center">';
	echo $line['pe_log'];
	echo '</TR>';
	}
	
require("../foot.php");
?>

