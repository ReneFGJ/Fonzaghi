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
$titulo_site='RSPNV';
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$op = array();
array_push($op,'');
array_push($op,'Somente sem vendas');
array_push($op,'Vendas até 5 ítens');
array_push($op,'Vendas de 5 a 10 ítens');
array_push($op,'Vemdas acima de 10 ítens');

$cp = array();
array_push($cp,array('$D8','','Data inicial',True,True,''));
array_push($cp,array('$D8','','Data final',True,True,''));
array_push($cp,array('$O 1:'.$op[1].'&2:'.$op[2].'&3:'.$op[3].'&4:'.$op[4].'','','Opções',True,True,''));

echo '<CENTER><h1>Relatório Produtos não vendidos</h1></CENTER>';
echo '<TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($dd[0]=='' && $dd[1]==''){
	echo $hd->foot();	
	exit;
}

$sql = " select p_codigo, p_descricao, venda, estoque  ";
$sql .= " from( ";
$sql .= "	select sum(venda) as venda, sum(estoque) as estoque, pe_produto  ";
$sql .= "	from ( ";
$sql .= "		select count(*) as venda, 0 as estoque, pe_produto  ";
$sql .= "			from produto_estoque ";
$sql .= "			where (pe_lastupdate >= ".brtos($dd[0])." and pe_lastupdate <= ".brtos($dd[1]).") ";
$sql .= "				and pe_status = 'T' ";
$sql .= "			group by pe_produto ";
$sql .= "			union ";
$sql .= "			select 0, count(*) as estoque, pe_produto ";
$sql .= "				from produto_estoque ";
$sql .= "				where (pe_lastupdate >= ".brtos($dd[0])." and pe_lastupdate <= ".brtos($dd[1]).") ";
$sql .= "				and pe_status <> 'T' ";
$sql .= "				and pe_status <> 'X' ";
$sql .= "			group by pe_produto ";
$sql .= "	) as r1 ";
$sql .= "	group by pe_produto ";
$sql .= "	order by pe_produto ";
$sql .= " )as r2  ";
$sql .= " left join produto on pe_produto=p_codigo ";

if ($dd[2]==1){$sql .= " where venda = 0 ";}
if ($dd[2]==2){$sql .= " where venda <= 5 ";}
if ($dd[2]==3){$sql .= " where venda >= 5 and venda <= 10 ";}
if ($dd[2]==4){$sql .= " where venda > 10 ";}

$sql .= " order by venda, estoque desc ";

$rlt = db_query($sql);

echo '<center><h2>De ' .$dd[0]. ' até '.$dd[1].' - <i>'.$op[$dd[2]].'</i></h2></center>';

echo '<center><h2>Ordenação: Qtd. Vendido > Qtd. Estoque [desc]</h2>';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';
echo '<TR><TH>Código</TH>';
echo '<TH>Descrição</TH>';
echo '<TH>Qtd. Vendido</TH>';
echo '<TH>Qtd. Estoque</TH>';
echo '<TH>Porcentagem</TH>';
echo '</TR>';

$tot=0;$tot_venda=0;$tot_estoque=0;	

while ($line = db_read($rlt)){
	echo '<TR '.coluna().'>';
	echo '<TD>';
	echo $line['p_codigo'];
	echo '<TD>';
	echo $line['p_descricao'];

	echo '<TD align="center">';
	echo $line['venda'];

	echo '<TD align="center">';
	echo $line['estoque'];
	
	echo '<TD align="center">';
	if ($line['estoque'] > 0){
		echo number_format((($line['venda']/$line['estoque'])*100),1).'%';
	}
	else{
		echo '0.0%';
	}	
	echo '</TR>';
	$tot++;
	$tot_venda+=$line['venda'];
	$tot_estoque+=$line['estoque'];	
}

echo '<TR>';
echo '<th colspan="2" align="left">Totais:</hh>';
echo '<th align="center">';
echo $tot_venda;
echo '<th align="center">';
echo $tot_estoque;
echo '</TR>';
echo '<TR>';
echo '<th colspan="5" align="left">Total de registros: '.$tot.'</hh>';
echo '</TABLE>';

echo $hd->foot();	
?>