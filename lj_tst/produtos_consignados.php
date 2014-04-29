<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('rel_produtos_vendas.php','Relatório Produtos mais vendidos'));


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
$titulo_site='RSACE';
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$cp = array();
array_push($cp,array('$D8','','Data inicial',True,True,''));
array_push($cp,array('$D8','','Data final',True,True,''));
array_push($cp,array('$O :Todas as peças&1:Peças promocionais&2:Peças Normais','','Tipo',True,True,''));

echo '<CENTER><h1>Relatório Produtos mais consignados</h1>';
echo '<TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($dd[0]=='' && $dd[1]==''){
	echo $hd->foot();	
	exit;
}


$sql_totais = "select count(*) as pecas_vendidas, ";
$sql_totais .= "		sum(pe_vlr_venda) as vlr_venda,  ";
$sql_totais .= "		sum(pe_vlr_vendido) as vlr_custo,  ";
$sql_totais .= "		sum(pe_vlr_venda-pe_vlr_custo) as vlr_lucro  ";
$sql_totais .= "	from produto_estoque  ";
$sql_totais .= "	where (pe_fornecimento >= ".brtos($dd[0])." and pe_fornecimento <= ".brtos($dd[1]).")  ";
$sql_totais .= "		and (pe_status = 'T' or pe_status = 'F' ) ";
if ($dd[2] == '1') { $sql_totais .= ' and (v_ref < 1) '; }
if ($dd[2] == '2') { $sql_totais .= ' and (v_ref = 1) '; }

$sql = "
	select v_ref, p_codigo, p_descricao, count(*) as pecas_vendidas, 
	sum(pe_vlr_venda) as vlr_venda, sum(pe_vlr_custo) as vlr_custo, 
	sum(pe_vlr_venda * v_ref -pe_vlr_custo) as vlr_lucro,
	sum(pe_vlr_vendido) as pe_vlr_vendido
	from produto_estoque 
	left join produto on pe_produto = p_codigo
	where 
	((pe_fornecimento >= ".brtos($dd[0])." and pe_fornecimento <= ".brtos($dd[1]).") and pe_fornecimento <= 20111122) 
	and (pe_status = 'T' or pe_status = 'F') ";
if ($dd[2] == '1') { $sql .= ' and (v_ref < 1) '; }
if ($dd[2] == '2') { $sql .= ' and (v_ref = 1) '; }
	$sql .= "
	group by p_codigo, p_descricao, v_ref 
	order by p_codigo desc ";
	$rlt = db_query($sql);

echo '<center><h2>De ' .$dd[0]. ' até '.$dd[1].'</h2></center>';
echo '<center>Ordenação: Qtd. Fornecidos';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR><TH>Código</TH>';
echo '<TH>Descrição</TH>';
echo '<TH>Qtd. Vendidos</TH>';
echo '<TH>Vlr. Venda</TH>';
echo '<TH>Vlr. Custo</TH>';
echo '<TH>Vlr. Bruto</TH>';
echo '<TH>Vlr. Pago</TH>';
echo '</TR>';

$tot=0;
while ($line = db_read($rlt)){
	$vref = $line['v_ref'];
	echo '<TR '.coluna().'>';
	echo '<TD>';
	echo $line['p_codigo'];
	echo '<TD>';
	echo $line['p_descricao'];
	echo '<TD align="right">';
	echo $line['pecas_vendidas'];
	echo '<TD align="right">';
	echo number_format($line['vlr_venda'],2);
	echo '<TD align="right">';
	echo number_format((1-$line['v_ref'])*100,0).'%';
	echo '<TD align="right">';
	echo number_format($line['vlr_lucro'] ,2);
	echo '<TD align="right">';
	echo number_format($line['pe_vlr_vendido'],2);
	echo '</TR>';
	$tot++;
	}

$rlt = db_query($sql_totais);
$line = db_read($rlt);

echo '<TR>';
echo '<th colspan="2" align="left">Totais:</hh>';
echo '<th align="right">';
echo $line['pecas_vendidas'];
echo '<th align="right">';
echo number_format($line['vlr_venda'],2);
echo '<th align="right">&nbsp;';
echo '<th align="right">';
echo number_format($line['vlr_lucro'],2);
echo '<th align="right">';
echo number_format($line['vlr_custo'],2);
echo '</TR>';
echo '<TR>';
echo '<th colspan="6" align="left">Total de registros: '.$tot.'</hh>';
echo '</TABLE>';

echo $hd->foot();	
?>