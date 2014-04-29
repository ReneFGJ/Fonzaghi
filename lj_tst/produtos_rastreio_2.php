<?

$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/produtos_rastreio.php','Rastreio de produto'));

$include = '../';
require($include."cab.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require("db_temp.php");
//require("func_kitnr.php");
$titulo_site='PSRA1';
?>
<table width="<?=$tab_max;?>" class="lt1">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$cp = array();
array_push($cp,array('$S12','','Código do cliente ',True,True,''));
array_push($cp,array('$D8','','De ',True,True,''));
array_push($cp,array('$D8','','Até ',True,True,''));

echo '<CENTER><font class="lt5">Relatório de Rastreio de Peças</font></CENTER>';
echo '<TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($dd[0]==''){
	echo $hd->foot();	
	exit;
}

$campo=''; 

$da1 = substr(brtos($dd[1]),0,6);
$da2 = substr(brtos($dd[2]),0,6);

(strlen($dd[0]) <= 6)?$campo='pl_produto':$campo='pl_cliente';

/////////////////////////////////// NOVA CONSULTA
$sqlc = ""; // Zera variavél de UNION
$ano = substr(brtos($dd[1]),0,4);
$mes = substr(brtos($dd[1]),4,2);
$tabela_data = $ano.$mes;
$fuga = 0; /// Evitar o LOOP
$nr = chr(13);

////////////// Posição atual da peça quando individual
if (strlen($dd[0]) == 7)
	{
	$sql = "select * from produto_estoque where pe_cliente = '".$dd[0]."' ";
	$rlt = db_query($sql);
	$line = db_read($rlt);
	$pe_status = $line['pe_status'];
	}

$sql = "select * from ( ".$nr;
while (($tabela_data <= $da2) and ($fuga < 12))
	{
	$fuga++;
	if (strlen($sqlc) > 0) { $sqlc .= chr(13).' union '.chr(13); }
	$sqlc .= "select pl_data,pl_ean13,pl_status,pl_log,pl_cliente, pl_hora from produto_log_".$tabela_data." ";
	$sqlc .= " where ".$campo."= '".$dd[0]."' AND ";
	$sqlc .= "(pl_data >= ".brtos($dd[1])." ";
	$sqlc .= " AND pl_data <= ".brtos($dd[2]).") ";
	
	$mes++;
	if ($mes > 12) { $ano++;$mes = 1; } /// Caso mes maior que 12 incrementa ano e seta o mês para janeiro
	$mes = strzero($mes,2);
	$tabela_data = $ano.$mes;
	}
$sql .= $sqlc;
$sql .= ") as resultado ";
$sql .= "	LEFT JOIN produto_estoque ON pe_ean13=pl_ean13 ";
$sql .= "	LEFT JOIN clientes ON 	cl_cliente=pl_cliente ";
$sql .= "   LEFT JOIN produto  ON   p_codigo = pe_produto ";
$sql .= " order by pl_data desc , pl_hora desc ";

$rlt = db_query($sql);
//echo $sql;

echo '<center>De ' .$dd[1]. ' até '.$dd[2].'</center>';
echo '<br>';

echo '<TABLE border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" width="'.$tab_max.'" align="center" class="lt2">';	
echo '<TR>';
echo '<TH bgcolor="#D4D4D4">Descricao</TH>';
echo '<TH bgcolor="#D4D4D4">Status</TH>';
echo '</TR>';
//-----------------------------------------------------
$line = db_read($rlt); //Pegando a primeira linha
echo '<TR '.coluna().'>';
echo '<TD>';
echo $line['p_descricao'];
echo '<TD align="center">'.$pe_status.'</TD>';
echo '</TR>';	
echo '</TABLE>';
//-----------------------------------------------------
$tot = 1;	
$status_A=0; $status_B=0; $status_C=0;
$status_F=0; $status_T=0; $status_X=0;
$status_H=0; $status_U=0; $status_CH=0;

echo '<br><center>Grupo do Código <strong>'.$dd[0].'</strong></center>';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';	
echo '<TH>Dt. e Hora</TH>';
echo '<TH>Log de Mov.</TH>';
echo '<TH>Código/EAN13</TH>';
echo '<TH>Preço</TH>';
echo '<TH>Cliente</TH>';
echo '<TH>S</TH>';
while ($line = db_read($rlt)){
	$tot++;
	($line['pl_status']=='A')? $status_A++:$status_A;
	($line['pl_status']=='B')? $status_B++:$status_B;
	($line['pl_status']=='C')? $status_C++:$status_C;
	($line['pl_status']=='F')? $status_F++:$status_F;
	($line['pl_status']=='T')? $status_T++:$status_T;
	($line['pl_status']=='X')? $status_X++:$status_X;
	($line['pl_status']=='H')? $status_H++:$status_H;
	($line['pl_status']=='U')? $status_U++:$status_U;
	($line['pl_status']=='@')? $status_CH++:$status_CH;

	echo '<TR '.coluna().'>';

	echo '<TD>';
	echo stodbr($line['pl_data']); 
	echo '&nbsp;';
	echo $line['pl_hora']; 
	echo '<TD>';
	echo $line['pl_log']; 
	echo '<TD>';
	echo $line['pe_produto']; 
	echo '/';
	echo $line['pl_ean13']; 
	echo '<TD width="70" align="right">';
	echo number_format($line['p_preco'],2); 
	echo '<TD>';	
	echo $line['pl_cliente']." - ". $line['cl_nome']; 
	echo '<TD align="center" width="30">';
	echo $line['pl_status']; 
	echo '<TD>';
	echo '</TR>';
}

echo "<tr align='left'><td colspan=6><strong>Total de ítens: ".$tot. "</strong></td></tr>";
echo '</TABLE>';
//echo "<br><center>Fornecidos: (?)     Devolvidos: (?)     Entradas: (?)     Vendidos: (?)     Cancelados: (?)</center>";
echo "<br>";
$status_show='';
$status_show="Status/Totais: Entrada (A=".$status_A.")";
$status_show.=" Chekin (@=".$status_CH.") ";
$status_show.=" Fornecidos (F=".$status_F.") ";
$status_show.="<br>";
$status_show.=" Vendido (T=".$status_T.") ";
$status_show.=" Venda para funcionários (U=".$status_U.") ";
$status_show.=" Cancelado (X=".$status_X.") ";
$status_show.="<br>";
$status_show.=" (H=".$status_H.") ";
$status_show.=" (B=".$status_B.") ";
$status_show.=" (C=".$status_C.") ";
echo $status_show;

echo $hd->foot();	
?>