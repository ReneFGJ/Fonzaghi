<?
require($include.'sisdoc_debug.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'biblioteca.php');
require($include.'letras.css');
require("../db_fghi_210.php");

$clie=$dd[0];
$listarDevolvidos=$dd[2];
//print_r($_POST);
if (count($_POST) > 0){
	if (strlen($_POST['dd81']) == 0){
		if ($_POST['cmbData'] <> 'Todas'){$data=substr($_POST['cmbData'],6,4).substr($_POST['cmbData'],3,2).substr($_POST['cmbData'],0,2);}
		else{$data=$_POST['cmbData'];}
		$listarDevolvidos=$_POST['chk_devolvido'];
	}else{
		$data=$_GET['dd1'];
		$listarDevolvidos=$_GET['dd2'];
	}
}
else{
	$data=$dd[1];
	$listarDevolvidos=$dd[2];
}

/*
echo '<br>POST: ';
print_r($_POST);

echo '<br>GET: ';
print_r($_GET);

ECHO '<BR> LISTAR: '.$listarDevolvidos;
*/

$sql = "select * from clientes where cl_cliente = '".$clie."' ";
$rlt = db_query($sql);
if ($line = db_read($rlt)){
	$nome = $line['cl_nome'];
	$cl_dtnascimento = sonumero($line['cl_dtnascimento']);
}

require("db_temp.php");

$sql = "select * from kits_consignado ";
$sql .= " where kh_cliente = '".$clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);

if ($line = db_read($rlt)){
	$dtf = $line['kh_fornecimento'];
	$dtp = $line['kh_previsao'];
}
$tab_max='98%';

?>
<table border="0" width="<?=$tab_max;?>" class="lt1">
<TR>
<TD align="center" class="lt5">Tabela de consignação</td></TR>
</TR>
<tr>
<td align="center" ><a align="right" class="botao-geral" href="tabela_fornecidos2.php?dd0=<?=$dd[0];?>&dd1=<?=$data;?>&dd2=<?=$listarDevolvidos;?>&dd80=1">Com foto</a>&nbsp;&nbsp;
					<a align="right" class="botao-geral" href="tabela_fornecidos2.php?dd0=<?=$dd[0];?>&dd1=<?=$data;?>&dd2=<?=$listarDevolvidos;?>&dd80=0">Sem foto</a>&nbsp;&nbsp;
</tr>
<TR>
<TD><fieldset><legend>Cliente</legend>
<table border="0" width="100%" class="lt1">
<TR>
<TD class="lt0">Nome</TD>
<TD align="right">Código</TD></TR>

<TR>
<TD><B><?=$nome;?></B></TD>
<TD align="right"><B><?=$clie;?></B></TD>
</TR>

<TR>
<TD colspan="2" align="right">Dt. nascimento</TD>
</TR>

<TR>
<TD colspan="2" align="right"><B><?=stodbr($cl_dtnascimento);?></B></TD>
</TR>

<TR>
<TD class="lt0">Data fornecimento</TD>
<TD align="right">Data de acerto</TD>
</TR>

<TR>
<TD><B><?=stodbr($dtf);?></B></TD>
<TD align="right"><B><?=stodbr($dtp);?></B></TD>
</TR>
</table>
</fieldset>
</TD>
</TR>

<TR>
<TD class="lt3">
<fieldset>
<legend>Produtos Consignados</legend>
<table border="0"  class="1_naoLinhaVertical" width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">

<TR>
<TH class="1_th" align="center">Ítem</TH>
<TH class="1_th" align="center">Ref.</TH>
<TH class="1_th" align="center">EAN13</TH>
<TH class="1_th" align="left">Descrição</TH>
<TH class="1_th" align="right">Vlr. <br>da venda</TH>
<TH class="1_th" align="right">Comissão</TH>
<TH class="1_th" align="right">Vlr. <br>da comissão</TH>
<TH class="1_th" align="right">Chq</TH>
<?
if($dd[80]<>0){
	echo '<TH class="1_th" align="center">Foto</TH>';
}

$sql = "SELECT pe_lastupdate, pe_produto, pe_ean13, p_descricao, ";
$sql .= " pe_vlr_venda, pe_comissao, (pe_vlr_venda * (pe_comissao/100)) as vlr_comissao, pe_cliente, pe_status ";
$sql .= " FROM produto_estoque ";
$sql .= " inner join produto on p_codigo = pe_produto ";
$sql .= " where pe_status = 'F' ";
$sql .= " and pe_cliente = '".$clie."' ";
if (strlen($data) > 0 && $data <> 'Todas'){
	$sql .= " and pe_lastupdate = '".$data."' ";
}
$sql .= " order by pe_lastupdate, pe_produto, pe_ean13 ";

$rlt = db_query($sql);

$item=0;
$tot_valor_venda=0;
$tot_valor_comissao=0;
$tot_valor_pagar=0;
$tot_qtd=0;

$linhas=0;
$pagina=1;

while ($line = db_read($rlt)){
	$item++;
	$linhas++;

	if ($linhas==40 && $pagina==1){
		cabecalho();
		$pagina=2;
		$linhas=0;
	}

	if ($linhas==50 && $pagina==2){	
		cabecalho();
		$pagina=2;
		$linhas=0;
	}

	$vlr = $line['pe_vlr_venda'];
	$dta = $line['pe_lastupdate'];

	if ($dta > $dtb){
		echo '<TR><TD class="lt5_1" colspan="8"><i>>> Fornecido em <B>'.stodbr($dta).'</i></B></TD></TD>';
		$dtb = $dta;
	}
	
	$ean13 = ($line['pe_ean13']);
	global $perfil;
	if (!($perfil->valid('#ADM#COB#COJ#COM#COO#COS#GEG#GEC#DIR#MST#REC#ESS#ESM#ESJ#ESO')))
	//if ($user_nivel < 5)
		{
		$ean13 = substr($ean13,0,4).'****'.substr($ean13,8,6);
		}
	//	}
	$img = 'img_produto/'.trim($line['pe_produto']).'.jpg';
	echo '<TR '.coluna().'>';
	echo '<TD class="1_td" align="center">'.$item.'</TD>';
	echo '<TD class="1_td" align="center">'.$line['pe_produto'].'</TD>';
	echo '<TD class="1_td" align="center">'.$ean13.'</TD>';	
	echo '<TD class="1_td" align="left">'.$line['p_descricao'].'</TD>';	
	echo '<TD class="1_td" align="right">'.number_format($line['pe_vlr_venda'],2).'</TD>';
	echo '<TD class="1_td" align="right">'.$line['pe_comissao'].'%</TD>';
	echo '<TD class="1_td" align="right">'.number_format($line['vlr_comissao'],2).'</TD>';
	echo '<TD class="1_td" align="right">[ __ ]</TD>';
	if($dd[80]<>0){
		echo '<TD class="1_td" align="center"><img src="'.$img.'" width="90px" height="90px"></TD>';
	}
	$tot_valor_venda+=$line['pe_vlr_venda'];
	$tot_valor_comissao+=$line['vlr_comissao'];
}
$tot_valor_pagar=$tot_valor_venda-$tot_valor_comissao;

echo '<tr>';
echo '<td class="legendatotal" colspan="4">Totais:</td>';
echo '<td class="total">'.number_format($tot_valor_venda,2).'</td>';
echo '<td class="total">-</td>';
echo '<td class="total">'.number_format($tot_valor_comissao,2).'</td>';
echo '<td class="total">-</td>';
echo '</tr>';
	
echo '<tr>';
echo '<td colspan="5" class="rodape">'.$item.' ítens</td>';
echo '</tr>';
?>	
</table>
</fieldset>
<?
//echo 'Data: '.$data;
$sql="SELECT pl_data, pl_hora, pe_produto, pl_ean13, p_descricao, pl_cliente, pl_status, pl_kit, pl_produto, 
       pl_log, id_pl";

if (strlen($data) > 0 && $data <> 'Todas'){
	$sql.=" FROM produto_log_".substr($data, 0,6);
}
else{
	$sql.=" FROM produto_log_".date('Ym');
}
$sql.=" inner join produto_estoque on pe_ean13 = pl_ean13
  inner join produto on p_codigo = pe_produto
  where pl_cliente='".$dd[0]."' and pl_status='D'";
  
if (strlen($data) > 0 && $data <> 'Todas'){
	$sql.=" and pl_data=".$data." ";
}

$sql.=" order by pl_data, pl_hora, pe_produto, pl_ean13";
//echo $sql;
$rlt=db_query($sql);

if ($listarDevolvidos==1){
	$sql="SELECT pl_data, pl_hora, pe_produto, pl_ean13, p_descricao, pl_cliente, pl_status, pl_kit, pl_produto, 
	       pl_log, id_pl";
	
	if (strlen($data) > 0 && $data <> 'Todas'){
		$sql.=" FROM produto_log_".substr($data, 0,6);
	}
	else{
		$sql.=" FROM produto_log_".date('Ym');
	}
	$sql.=" inner join produto_estoque on pe_ean13 = pl_ean13
	  inner join produto on p_codigo = pe_produto
	  where pl_cliente='".$dd[0]."' and pl_status='D'";
	  
	if (strlen($data) > 0 && $data <> 'Todas'){
		$sql.=" and pl_data=".$data." ";
	}
	
	$sql.=" order by pl_data, pl_hora, pe_produto, pl_ean13";
	//echo $sql;
	$rlt=db_query($sql);
	
	if (pg_num_rows($rlt) > 0){
		?>
		<fieldset>
		<legend>Produtos Devolvidos</legend>
		<table border="0"  class="1_naoLinhaVertical" width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
		<TR>
		<TH class="1_th">Ítem</TH>
		<TH class="1_th">Data-Hrs.</TH>
		<TH class="1_th">Ref.</TH>
		<TH class="1_th">EAN13</TH>
		<TH class="1_th">Descrição</TH>
		<TH class="1_th">Chq</TH>
		<tr>
		<?
		$item=0;
		$linhas=0;
		$pagina=1;
		while($line=db_read($rlt)){
			$item++;
			echo '<TR '.coluna().'>';
			echo '<TD class="1_td" align="center">'.left_zero($item,3).'</TD>';
			echo '<TD class="1_td" align="center">'.stodbr($line['pl_data']).'-'.$line['pl_hora'].'</TD>';
			echo '<TD class="1_td" align="center">'.$line['pe_produto'].'</TD>';
			echo '<TD class="1_td" align="center">'.$line['pl_ean13'].'</TD>';	
			echo '<TD class="1_td" align="left">'.$line['p_descricao'].'</TD>';	
			echo '<TD class="1_td" align="right">[ __ ] '.$line['pl_log'].'</TD>';
		}
		echo '<tr>';
		echo '<td colspan="6" class="rodapetotal">'.$item.' ítens</td>';
		echo '</tr>';
		?>
		</tr>
		</table>
		</fieldset>
		<?
	}//if (pg_num_rows($rlt) > 0){
}//listar devolvidos
?>
<BR><p>
Obs: Seu kit está com <?=$item;?> peças.&nbsp; 
O valor total fornecido é R$ <?=number_format($tot_valor_venda,2);?>.&nbsp; 
Sua comissão é de 30% ou seja <b>R$ <?=number_format($tot_valor_comissao,2);?></b>.&nbsp; 
O valor do acerto é de R$ <?=number_format(($tot_valor_venda - $tot_valor_comissao),2); ?>&nbsp;e está programada para <b><?=stodbr($dtp);?></b>.
</p><BR>
</TD></TR>
</table>

<?
function cabecalho(){
	echo '</table>';
	echo '</fieldset>';
	echo '</tr>';

	echo '<tr>';
	echo '<td>';
	echo '<div style="page-break-before: always"></div>';
	echo '</td>';
	echo '</tr>';

	echo '<TR>';
	echo '<TD><img src="img/logo_empresa.png" width="231" height="79" alt="" border="0"></TD>';
	echo '</TR>';

	echo '<TR>';
	echo '<TD class="lt0"><b>&nbsp;&nbsp;&nbsp;&nbsp;Fone (41) 3233-0303</B></TD>';
	echo '</TR>';
		
	echo '<TR>';
	echo '<td align="center" class="lt5">Tabela de consignação</td>';
	echo '</tr>';

	echo '<TR>';
	echo '<TD class="lt3">';

	echo '<fieldset>';
	echo '<legend>Produtos Consignados</legend>';
	echo '<table border="0"  class="1_naoLinhaVertical" width="100%" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<TR>';
	echo '<TH class="1_th">Ítem</TH>';
	echo '<TH class="1_th">Ref.</TH>';
	echo '<TH class="1_th">EAN13</TH>';		
	echo '<TH class="1_th">Descrição</TH>';
	echo '<TH class="1_th">Vlr. <br>da venda</TH>';
	echo '<TH class="1_th">Comissão</TH>';
	echo '<TH class="1_th">Vlr. <br>da comissão</TH>';
	echo '<TH class="1_th">Chq</TH>';
	echo '</tr>';
}

?>
