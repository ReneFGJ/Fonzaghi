<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('estoque_rel_1.php','Margens de vendas'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");

require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");

require("db_temp.php");

echo '<h2>Posição do Estoque - '.$tit1.'</h2>';
	
$tabela = "";
$cp = array();
array_push($cp,array('$H4','','',False,True,''));
array_push($cp,array('$A','','Estoque x Vendas',False,True,''));
array_push($cp,array('$[2010-'.date("Y").']','','Ano calculo',True,True,''));
/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';
	
if ($saved < 1) {  exit; }

if (strlen($dd[2]) > 0)
{
$sql = "
		select count(*) as pecas, sum(venda) as venda, sum(estoque) as estoque, data, p_class_1 from 
		(
			select round(pe_data/100) as data, 0 as venda, 1 as estoque, id_pe, pe_produto, p_class_1 from produto_estoque 
			inner join produto on p_codigo = pe_produto where pe_status <> 'X' and p_class_1 like '".$dd[2]."%' 
		
			union 
			
			select round(pe_lastupdate/100) as data, 1 as venda, 0 as estoque, id_pe, pe_produto, p_class_1 from produto_estoque 
			inner join produto on p_codigo = pe_produto where pe_status = 'T' and p_class_1 like '".$dd[2]."%'
		) as tabela 
		group by p_class_1, data
		order by data
		";
} else {		
$sql = "
		select count(*) as pecas, sum(venda) as venda, sum(estoque) as estoque, data from 
		(
			select round(pe_data/100) as data, 0 as venda, 1 as estoque, id_pe, pe_produto, p_class_1 from produto_estoque 
			inner join produto on p_codigo = pe_produto where pe_status <> 'X'
		
			union 
			
			select round(pe_lastupdate/100) as data, 1 as venda, 0 as estoque, id_pe, pe_produto, p_class_1 from produto_estoque 
			inner join produto on p_codigo = pe_produto where pe_status = 'T'
		) as tabela 
		group by data
		order by data
		";
}
		
$rlt = db_query($sql);
$pcs = array();
$max = 1000;
$sx = '';
$saldo = 0;
while ($line = db_read($rlt))
{
	$saldo = $saldo + $line['estoque']-$line['venda'];
	if (strlen($sx) > 0)
		{ $sx .= ', '.chr(13).chr(10); }
	$sx .= "['".substr($line['data'],4,2).'/'.substr($line['data'],0,4)."',".$line['estoque'].",".$line['venda'].",".$saldo."]";
}

?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Data');
        data.addColumn('number', 'Entradas');
        data.addColumn('number', 'Vendas');
        data.addColumn('number', 'Estoque');
        data.addRows([
			<?php echo $sx; ?>
        ]);

        var options = {
          title: 'Posição do estoque peças',
          hAxis: {title: 'Mês/Ano',  titleTextStyle: {color: 'Blue'}}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
<?
$sql = "select * from produto_grupos where pg_ativo = 1 order by pg_descricao ";
$rlt = db_query($sql);
$col = 99;
$sx = '';
while ($line = db_read($rlt))
{
	if ($col > 2)
		{ $sx .= '<TR>'; $col=0; }
	$sx .= '<TD>';
	$sx .= '<A HREF="estoque_rel_2.php?dd1='.$dd[1].'&dd2='.$line['pg_codigo'].'&acao=busca">';
	$sx .= $line['pg_descricao'];
	$col++;
}
echo '<table width="'.$tab_max.'" border=1>';
echo '<TR><TH colspan=3 >Categorias';
echo $sx;
echo '</table>';
require("../foot.php");
?>
