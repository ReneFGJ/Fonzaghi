<?

$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/produtos_reprocecar.php','Reprocessar precos'));

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
$sql = "";
while ($line = db_read($rlt))
	{
		$cor = "";
		$pro = " - ";
		if (round($line['pe_vlr_venda']*100) != round($line['p_preco']*100)) 
			{ 
			$cor = '<font color="green">'; 
			$sql = "update produto_estoque set pe_vlr_venda = ".$line['p_preco']." where pe_produto = '".$line['pe_produto']."' ";
			$sql .= " and pe_status = '@' and id_pe = ".$line['id_pe'];
			$pro = "alterado";
			$xxx = db_query($sql);
			}
			
		if ($line['pe_vlr_custo'] <= 0)
			{
			$cor = '<font color="red">';
			$pro = 'Custo inválido';
			}
		$ini++;
		$sn .= '<TR '.coluna().'>';
		$sn .= '<TD>'.$cor;
		$sn .= $line['pe_ean13'];
		$sn .= '<TD>'.$cor;
		$sn .= $line['pe_produto'];
		$sn .= '<TD>'.$cor;
		$sn .= $line['p_descricao'];
		$sn .= '<TD align="right" width="60">'.$cor;
		$sn .= number_format($line['pe_vlr_venda'],2);
		$sn .= '<TD align="right" width="60">'.$cor;
		$sn .= number_format($line['pe_vlr_custo'],2);
		$sn .= '<TD align="right" width="60">'.$cor;
		$sn .= number_format($line['p_preco'],2);
		$sn .= '<TD align="right">'.$cor;
		$sn .= stodbr($line['pe_data']);
		$sn .= '<TD align="center">'.$cor;
		$sn .= $pro;
		$sn .= '</TD>';
		$sn .= '</TR>';
	}
?>
<center><h1><B>Produtos para reprocessar preços</h1></center>
<TABLE width="710" align="center" border="0" class="lt1">
<TR><TH>EAN13</TH>
	<TH>PROD</TH>
	<TH>Descricao</TH>
	<TH>Preço Etiqueta</TH>
	<TH>Custo</TH>
	<TH>Preço tabela</TH>
	<TH>Data</TH>
<?=$sn;?>
<TR><TD colspan="3">Total de <B><?=$ini;?></B> produtos</TD></TR>
</TABLE>
<? echo $hd->foot();	?>