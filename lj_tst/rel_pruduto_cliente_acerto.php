<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('rel_pruduto_cliente_acerto.php','Extrato de acerto'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");


$titulo_site='REXA';
require("db_temp.php");

echo '<center><table width='.$tab_max.'>';
echo '<TR><TD>';
echo '<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">';
echo '</TD></TR>';
echo '</table>';

echo '<CENTER><font class="lt5">Extrato de acerto</font></CENTER>';

if ($dd[0]==''){
	$cp = array();
	array_push($cp,array('$S7','','Código da(o) cliente',True,True,''));
	
	echo '<TABLE align="center" width="'.$tab_max.'">';
	echo '<TR><TD>';
	editar();
	echo '</TABLE>';
	echo '<TR><TD align="center" class="lt1">Passo 1 de 2';

	require("../foot.php");	
	exit;
}

$cliente=$dd[0];
$sql="SELECT pe_lastupdate FROM produto_estoque where pe_cliente ='".$cliente."' and pe_status = 'T' group by pe_lastupdate order by pe_lastupdate desc";
//echo '<br>'.$sql;

$rtl=db_query($sql);

$i=0;
$datas2=array();
$datas='';
while($line=db_read($rtl)){
	$datas.= $i.':'.stodbr($line['pe_lastupdate']).'&';
	array_push($datas2, $line['pe_lastupdate']);
	$i++;
}	
$datas=substr($datas,0,(strlen($datas)-1));

if ($dd[1]==''){
	if (pg_num_rows($rtl) > 0){
	
		$cp = array();
		array_push($cp,array('$HS7','','Código da(o) cliente',false,false,''));
		array_push($cp,array('$O '.$datas.'','','Data do acerto',True,True,''));
	
		echo '<TABLE align="center" width="'.$tab_max.'">';
		echo '<TR><TD>';
		editar();
		echo '<TR><TD align="center" class="lt1">Passo 2 de 2';
		echo '</TABLE>';	
		$dd[0]='';
	}
	else{
		echo '<br><br><CENTER><font class="lt3"><b>Operação cancelada!<br>A(O) cliente não efetuou nenhum acerto.</b></font></CENTER>';	
	}
}
else{
	//echo '<br>data: '.$datas2[$dd[1]];
	//echo '<br>cliente: '.$dd[0];	

	$sql = "select * from kits_consignado ";
	$sql .= " where kh_cliente = '".$cliente."' ";
	$sql .= " and kh_status = 'A' ";
	$rlt = db_query($sql);

	if ($line = db_read($rlt)){
		$dtp = $line['kh_previsao'];
	}
	
	$tot_valor_venda=0;
	$tot_valor_comissao=0;
	$tot_valor_pagar=0;
	$reg=0;
	
	$sql = " SELECT pe_cliente, cl_nome, pe_ean13, p_descricao, pe_vlr_venda, ";
	$sql .= " (pe_vlr_venda*(pe_comissao/100)) as valor_comissao,  ";
	$sql .= " (pe_vlr_venda - (pe_vlr_venda*(pe_comissao/100))) as valor_pagar, ";
	$sql .= " pe_lastupdate, pe_comissao ";
	$sql .= " FROM produto_estoque ";
  	$sql .= " left join clientes on cl_cliente = pe_cliente  ";
  	$sql .= " left join produto on p_codigo = pe_produto  ";
  	$sql .= " where pe_cliente='".$cliente."' ";
	$sql .= " and pe_status = 'T' ";
	$sql .= " and pe_lastupdate = ".$datas2[$dd[1]]." ";
    $sql .= " order by p_descricao ";
//	echo '<br>'.$sql;
	$rlt=db_query($sql);
	$line=db_read($rlt);

	echo '<table width='.$tab_max.' class="lt1">';
	//echo '<table width='.$tab_max.' border="2" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<TR><TD><fieldset><legend>Cliente</legend>';
	echo '<table border="0" width='.$tab_max.' class="lt1">';
	echo '<TR><TD class="lt0">Nome</TD><TD align="right">Código</TD></TR>';
	echo '<TR><TD><B>'.$line['cl_nome'].'</B></TD><TD align="right"><B>'.$line['pe_cliente'].'</B></TD></TR>';
	echo '<TR><TD align="right" colspan="2">Data de acerto</TD></TR>';
	echo '<TR><TD align="right" colspan="2"><B>'.stodbr($datas2[$dd[1]]).'</B></TD>';
	echo '</table>';
	echo '</fieldset>';	

	echo '<TR><TD><fieldset><legend>Produtos vendidos</legend>';
	//echo '<table border="0" width='.$tab_max.' class="lt1">';
	echo '<table  class="1_naoLinhaVertical" width='.$tab_max.' align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<TH class="1_th">EAN13</TH>';
	echo '<TH class="1_th">Descrição</TH>';
	echo '<TH class="1_th">Vlr. da<br>venda</TH>';
	echo '<TH class="1_th">Vlr. da<br>comissão ('.$line['pe_comissao'].'%)</TH>';
	echo '<TH class="1_th">Vlr. à<br>pagar</TH>';
	echo '</tr>';
	
	echo '<tr '.coluna().'>';
	echo '<td class="1_td" align="center">'.$line['pe_ean13'].'</td>';
	echo '<td class="1_td">'.$line['p_descricao'].'</td>';
	echo '<td class="1_td" width="90" align="right">'.number_format($line['pe_vlr_venda'],2).'</td>';
	echo '<td class="1_td" width="90" align="right">'.number_format($line['valor_comissao'],2).'</td>';
	echo '<td class="1_td" width="90" align="right">'.number_format($line['valor_pagar'],2).'</td>';
	echo '</tr>';
	
	$tot_valor_venda+=$line['pe_vlr_venda'];
	$tot_valor_comissao+=$line['valor_comissao'];
	$tot_valor_pagar+=$line['valor_pagar'];
	$reg++;

	while($line=db_read($rlt)){
		echo '<tr '.coluna().'>';
		echo '<td class="1_td" align="center">'.$line['pe_ean13'].'</td>';
		echo '<td class="1_td">'.$line['p_descricao'].'</td>';
		echo '<td class="1_td" width="90" align="right">'.number_format($line['pe_vlr_venda'],2).'</td>';
		echo '<td class="1_td" width="90" align="right">'.number_format($line['valor_comissao'],2).'</td>';
		echo '<td class="1_td" width="90" align="right">'.number_format($line['valor_pagar'],2).'</td>';
		echo '</tr>';	
		
		$tot_valor_venda+=$line['pe_vlr_venda'];
		$tot_valor_comissao+=$line['valor_comissao'];
		$tot_valor_pagar+=$line['valor_pagar'];
		$reg++;
	}

	
	echo '<tr>';
	echo '<td class="legendatotal" colspan="2">Totais:</td>';
	echo '<td class="total">'.number_format($tot_valor_venda,2).'</td>';
	echo '<td class="total">'.number_format($tot_valor_comissao,2).'</td>';
	echo '<td class="total">'.number_format($tot_valor_pagar,2).'</td>';
	echo '</tr>';
	
	echo '<tr>';
	echo '<td colspan="5" class="rodape">'.$reg.' ítens</td>';
	echo '</tr>';

	echo '</table>';
	echo '</fieldset>';	

	echo '</table>';
	
}

require("../foot.php");	
?>