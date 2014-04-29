<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('rel_produtos_vendas.php','Relatório Produtos mais vendidos'));


$include = '../';
require("../cab_novo.php");

require($include."sisdoc_data.php");
require($include."sisdoc_colunas.php");
require("../_class/_class_form.php");
$form = new form;

require("db_temp.php");
//$dd1=date("d/m/Y");
$titulo_site='RSACE';
?>
<img align="right" src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
<?
if (strlen($dd[0]) == 0) { $dd[0] = date("01/m/Y"); }
if (strlen($dd[1]) == 0) { $dd[1] = date("d/m/Y"); }

$cp = array();
array_push($cp,array('$D8','','Data inicial',True,True,''));
array_push($cp,array('$D8','','Data final',True,True,''));
//array_push($cp,array('$B8','','Pesquisar >>>',False,True,''));

echo '<h1>Relatório Produtos baixados</h1>';
$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
	$sql = "select p_codigo, p_descricao, count(*) as pecas_vendidas, pe_doc, ";
	$sql .= " sum(pe_vlr_venda) as vlr_venda, sum(pe_vlr_custo) as vlr_custo, sum(pe_vlr_venda-pe_vlr_custo) as vlr_lucro ";
	$sql .= " from produto, produto_estoque ";
	$sql .= " where (p_codigo=pe_produto) ";
	$sql .= " and (pe_lastupdate >= ".brtos($dd[0])." and pe_lastupdate <= ".brtos($dd[1]).")  ";
	$sql .= " and pe_status = 'T' and pe_vlr_vendido = 0 ";
	$sql .= " group by p_codigo, p_descricao, pe_doc ";
	$sql .= " order by pe_doc, pecas_vendidas desc ";
	
	$sql_totais = "select count(*) as pecas_vendidas, ";
	$sql_totais .= "		sum(pe_vlr_venda) as vlr_venda,  ";
	$sql_totais .= "		sum(pe_vlr_custo) as vlr_custo,  ";
	$sql_totais .= "		sum(pe_vlr_venda-pe_vlr_custo) as vlr_lucro  ";
	$sql_totais .= "	from produto_estoque  ";
	$sql_totais .= "	where (pe_lastupdate >= ".brtos($dd[0])." and pe_lastupdate <= ".brtos($dd[1]).") and (pe_vlr_vendido = 0) ";
	$sql_totais .= "		and pe_status = 'T' 
						 ";
	
	$rlt = db_query($sql);
	
	echo '<center><h2>De ' .$dd[0]. ' até '.$dd[1].'</h2></center>';
	echo '<center><h2>Ordenação: Qtd. Baixadas</h2>';
	echo '<TABLE width="'.$tab_max.'" align="center" class="lt2">';
	echo '<TR><TH>Código</TH>';
	echo '<TH>Descrição</TH>';
	echo '<TH>Qtd. Vendidos</TH>';
	echo '<TH>Vlr. Venda</TH>';
	echo '<TH>Vlr. Custo</TH>';
	echo '<TH>Lucratividade</TH>';
	echo '</TR>';
	
	$tot=0;
	while ($line = db_read($rlt)){
		echo '<TR '.coluna().'>';
		echo '<TD>';
		echo $line['p_codigo'];
		echo '<TD>';
		echo $line['p_descricao'];
		echo '<TD align="right">';
		echo $line['pecas_vendidas'];
		echo '<TD align="right">';
		echo number_format($line['pe_vlr_vendido'],2);
		echo '<TD align="right">';
		echo number_format($line['vlr_custo'],2);
		echo '<TD align="right">';
		echo number_format($line['vlr_lucro'],2);
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
		echo number_format($line['pe_vlr_vendido'],2);
		echo '<th align="right">';
		echo number_format($line['vlr_custo'],2);
		echo '<th align="right">';
		echo number_format($line['vlr_lucro'],2);
		echo '</TR>';
		echo '<TR>';
		echo '<th colspan="6" align="left">Total de registros: '.$tot.'</hh>';
		echo '</TABLE>';
	} else {
		echo $tela;
	}	 


echo $hd->foot();	
?>