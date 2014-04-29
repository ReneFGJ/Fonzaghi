<?
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
$clie = $dd[0];
require("../db_fghi_210.php");

$sql = "select * from clientes where cl_cliente = '".$clie."' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	$nome = $line['cl_nome'];
	$cl_dtnascimento = sonumero($line['cl_dtnascimento']);
	$cpf = $line['cl_cpf'];
	}
///
require("db_temp.php");

$sql = "select * from kits_consignado ";
$sql .= " where kh_cliente = '".$clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	$dtf = $line['kh_fornecimento'];
	$dtp = $line['kh_previsao'];
	}

$sql = "select * from produto_estoque ";
$sql .= " inner join produto on p_codigo = pe_produto ";
$sql .= " where pe_cliente = '".$clie."' and pe_status = 'F' ";
$sql .= " order by p_codigo ";
$rlt = db_query($sql);
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
<TR><TD align="center" class="lt5">Tabela de consignação</TD></TR>
<TR><TD></TD></TR>
<TR><TD><fieldset><legend>Cliente</legend>
<table width="100%" class="lt1">
<TR><TD class="lt0">Nome</TD><TD align="right">Código</TD></TR>
<TR><TD><B><?=$nome;?></B></TD><TD align="right"><B><?=$clie;?></B></TD></TR>

<TR><TD class="lt0">CPF</TD><TD align="right">Dt. nascimento</TD></TR>
<TR><TD><B><?=$cpf;?></B></TD><TD align="right"><B><?=stodbr($cl_dtnascimento);?></B></TD></TR>

<TR><TD class="lt0">Data fornecimento</TD><TD align="right">Data de acerto</TD></TR>
<TR><TD><B><?=stodbr($dtf);?></B></TD><TD align="right"><B><?=stodbr($dtp);?></B></TD></TR>

</table>
</fieldset>
</TD></TR>
<TR><TD class="lt3"><fieldset><legend>Produtos Consignados</legend>
<table width="100%" class="lt1">
<TR><TH>Ref.
<TH>Código</TH>
<TH>Descrição</TH>
<TH>EAN13</TH>
<TH>Valor</TH>
<?
$tot=0;
$totv=0;
$dtb = $dtf;
while ($line = db_read($rlt))
	{
	$vlr = $line['pe_vlr_venda'];
	$cdo = $line['p_codigo'];
	$ean = $line['p_ean13'];
	$des = $line['p_descricao'];
	$dta = $line['pe_data'];
	if ($dta > $dtb)
		{
		echo '<TR><TD colspan="4">>> Fornecido em <B>'.stodbr($dta).'</B></TD></TD>';
		$dtb = $dta;
		}
	echo '<TR '.coluna().'>';
	echo '<TD width="10%">'.$cdo.'</TD>';
	echo '<TD width="10%" align="center"><nobr>'.$ean.'</TD>';
	echo '<TD>'.$des.'</TD>';
	echo '<TD align="center" width="10%">'.$line['pe_ean13'].'</TD>';
	echo '<TD align="right" width="10%">'.number_format($vlr,2).'</TD>';
	$tot = $tot+1;
	$totv = $totv + $vlr;
	}
?>	
</TD></TR>
<TR><TD colspan="5" align="right">Valor fornecido <B><?=number_format($totv,2);?></B> (<B><?=$tot;?></B> peça(s))</TD></TR>
</table>
</fieldset>
<BR>
As mercadorias são consignadas, sua comissão é de 30%. Seu kit está com <?=$tot;?> peças. Valor total fornecido R$ <?=number_format($totv,2);?>.
A data de seu acerto é <?=stodbr($dtp);?>.
<BR>
</TD></TR>

</table>
