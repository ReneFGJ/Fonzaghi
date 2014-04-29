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

echo '<h2>Margens de comercialização - '.$tit1.'</h2>';
	
$tabela = "";
$cp = array();
array_push($cp,array('$H4','','',False,True,''));
array_push($cp,array('$A','','Margens de comercialização',False,True,''));
array_push($cp,array('$D8','','Data ',True,True,''));
array_push($cp,array('$D8','','até',True,True,''));
if (strlen($dd[2]) ==0) { $dd[2] = date("d/m/Y"); }
if (strlen($dd[3]) ==0) { $dd[3] = date("d/m/Y"); }
/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';
	
if ($saved < 1) {  exit; }

$data1 = brtos($dd[2]);
$data2 = brtos($dd[3]);

$sql = "select  sum(round(pe_vlr_venda*100))/100 as face,  
		sum(round(pe_vlr_custo*100))/100 as custo, 
		sum(round(pe_vlr_vendido*100))/100 as vendido, 
		p_class_1, count(*) as pecas, pg_descricao
	from produto_estoque
	inner join produto on p_codigo = pe_produto
	inner join produto_grupos on pg_codigo = p_class_1	
	where pe_status = 'T' and pe_lastupdate >= $data1 and pe_lastupdate <= $data2
	and pe_vlr_custo > 0
	group by p_class_1, pg_descricao
	order by pg_descricao
	";
	
if (strlen($dd[1]) > 0)
	{
		$sql = "select  sum(round(pe_vlr_venda*100))/100 as face,  
		sum(round(pe_vlr_custo*100))/100 as custo, 
		sum(round(pe_vlr_vendido*100))/100 as vendido, 
		p_class_1, count(*) as pecas, p_descricao as pg_descricao
		 
		from produto_estoque
		inner join produto on p_codigo = pe_produto
		inner join produto_grupos on pg_codigo = p_class_1
		
		where pe_status = 'T' and pe_lastupdate >= $data1 and pe_lastupdate <= $data2
		and pe_vlr_custo > 0
		and p_class_1 = '".$dd[1]."' 
		group by p_class_1, p_descricao
		order by p_descricao
		";
		
		
	}
$rlt = db_query($sql);
$m1=0;
$m2=0;
$m3=0;
$m5=0;

while ($line = db_read($rlt))
	{
		$link = '<a href="estoque_rel_1.php?acao=busca&dd2='.$dd[2].'&dd3='.$dd[3].'&dd1='.$line['p_class_1'].'&dd90='.checkpost($line['p_class_1']).'">';
		$x1 = $line['custo'];
		$x2 = $line['vendido'];
		$x0 = $line['face'];		
		$m1 = $m1 + $x1;
		$m2 = $m2 + $x2;
		$m3 = $m3 + $x0;
		$m5 = $m5 + $line['pecas'];
		$x3 = 0;
		if ($x2 > 0)
			{ $x3 = ($x2 / $x1) - 1; }
		if ($x2 > 0)
			{ $x4 = ($x0 / $x2) - 1; }
		if ($x2 > 0)
			{ $x5 = ($x0 / $x1) - 1; }
		$sx .= '<TR '.coluna().'>';
		$sx .= '<TD>'.$link.$line['pg_descricao'].'</A>';
		$sx .= '<TD align="center">'.$line['pecas'];
		$sx .= '<TD align="right">'.number_format($line['custo'],2);
		$sx .= '<TD align="right">'.number_format($line['face'],2);
		$sx .= '<TD align="right">'.number_format($line['vendido'],2);
		$sx .= '<TD align="right"><B>'.number_format($x3*100,1).'%';
//		$sx .= '<TD align="right">'.number_format($x4*100,1).'%';
//		$sx .= '<TD align="right">'.number_format($x5*100,1).'%';
	}

if ($m2 > 0) { $m4 = ($m2 / $m1) - 1; }
	echo '<font class="lt0">período de '.$dd[2].' até '.$dd[3];
echo '<TABLE width="'.$tab_max.'" class="lt1">';
echo '<TR><TH>descrição<TH>total<TH>custo<TH>vlr.face<TH>vlr.vendido
		<TH>margem';
echo '<TR><TD>';

		$sx .= '<TR '.coluna().'>';
		$sx .= '<TD align="right"><B>Total Geral';
		$sx .= '<TD align="center"><B>'.$m5;
		$sx .= '<TD align="right"><B>'.number_format($m1,2);
		$sx .= '<TD align="right"><B>'.number_format($m3,2);
		$sx .= '<TD align="right"><B>'.number_format($m2,2);
		$sx .= '<TD align="right"><B>'.number_format($m4*100,1).'%';
	
	echo $sx;

echo '</TABLE>';

require("../foot.php");
?>
