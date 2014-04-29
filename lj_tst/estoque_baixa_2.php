<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_baixa.php','Baixa de estoque de produto danificado/amostra'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

require("estoque_funcoes.php");

$senha=$_GET['dd55'];

echo '<form name="f1" action="estoque_baixa_2.php" method="post">';

echo '<table border="0" align="center" class="lt1">';
echo '<tr><td colspan="2">';
echo '<fieldset><legend>Confirme</legend>';
echo '<TABLE align="center" width="250" class="lt1">';
	echo '<tr><td height="40" colspan="2" align="center" class="lt3">Baixar outro código de barras?</td></tr>';
	echo '<tr>';
	echo '<td height="40" align="right"><input type="submit" name="acao" value="Sim"></td>';
	echo '<td height="40" align="left"><input type="submit" name="acao" value="Não"></td>';
	echo '</tr>';
echo '</TABLE>';
echo '</fieldset>';
echo '</td>';
echo '</tr>';
echo '</table>';

echo "</form>";

//print_r($_POST);

if ($_POST['acao']=='Sim'){
	redirecina("estoque_baixa.php?baixa=0&dd55=".$senha."");
}

if ($_POST['acao']=='Não'){
	//Verifica se existe lote aberto
	require("db_temp.php");	
	$sql="SELECT * FROM produto_estoque where pe_status='H' ";
	$rlt = db_query($sql);	

	if (pg_num_rows($rlt) > 0){	
		$sql="UPDATE produto_estoque ";
	   	$sql.=" SET pe_cliente='8284371', pe_status='T'  ";
		$sql.=" WHERE pe_status = 'H' ";
		db_query($sql);
			
		/////////////////////////////Se nao, gerar fatura para o caixa
		require("../db_ecaixa.php");
		$sql="INSERT INTO caixa_".date("Ym")."_01 ( ";
	    $sql.=" cx_data, cx_hora, cx_tipo, cx_descricao, cx_valor, cx_log,  ";
    	$sql.=" cx_terminal, cx_cliente, cx_nome, cx_venc, cx_doc, cx_parcela,  ";
		$sql.=" cx_status, cx_lote, cx_chq_banco, cx_chq_conta, cx_chq_agencia,  ";
		$sql.=" cx_chq_nrchq, cx_proc ) ";
		$sql.=" values	 (".date("Ymd").",'".date('H:i')."' , '', 'Baixa de produtos em estoque', 0, 0,  ";
		$sql.=" '', '8284371', 'Fonzaghi baixa de estoque', ".date("Ymd").", '', '',  ";
		$sql.=" '@', '', '', '', '',  ";
		$sql.=" '', 0) ";
		db_query($sql);
	}
	redirecina("index.php");
}
echo $hd->foot();	
?>

