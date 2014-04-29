<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item3.php','Lista pendentes de aprovação'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

require($include."biblioteca.php");
require("estoque_funcoes.php");
require("db_temp.php");

if ($user_nivel < 9){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

//Filtrar o inventario pelo codigo, data, autorizado=1, status <> I
//armazenar em um array
$sql="SELECT * FROM inventario
	  where i_baixar_reincorporar_autorizada='1' and i_status <> 'I'
		order by i_cod_produto, i_status";
$rlt=db_query($sql);
//echo '<br>sql 1: '.$sql;
	
$inventario=array();
while($line=db_read($rlt)){
	array_push($inventario, array($line['i_ean13'], $line['i_status'], $line['i_cod_produto'], $line['i_valor_custo']));
}

$pecas_baixadas=array();
$sql_trans='BEGIN;';
for($i=0; $i < count($inventario); $i++){
	//ECHO '<BR>Inventário: '.$inventario[$i][0].'   '.$inventario[$i][2];
	
	//se no array o status for R
	//echo '<br>'.$inventario[$i][0].'-'.$inventario[$i][1].'-'.$inventario[$i][2];
		
	if ($inventario[$i][1]=='R'){
		//filtra o produto_estoque pelo ean13 (do array)
		$sql_trans.="UPDATE produto_estoque SET pe_status='A', pe_cliente='       ', pe_doc=0, pe_vlr_vendido=0, pe_inventario=1 
					   where pe_ean13 = '".$inventario[$i][0]."'
					   and pe_inventario=1;";

		$sql_trans.="INSERT INTO produto_log_".date('Ym')." ( 
	        			pl_ean13, pl_data, pl_hora, pl_cliente, pl_status, pl_kit, pl_produto, pl_log)
    					VALUES ('".$inventario[$i][0]."',".date('Ymd').",'".date('H:i')."','8284371', 'H', '', '".$inventario[$i][2]."', '".$user_log."'); ";
	}

	//se no array o status for B
	if ($inventario[$i][1]=='B'){
		//filtar o produto_estoque pelo ean13 (do array) e status <> F, T, X
		$sql="select * from produto_estoque 
			   where pe_ean13 = '".trim($inventario[$i][0])."' 
			   and (pe_status<>'T' and pe_status<>'X' and pe_status<>'F') 
			   and pe_inventario=1;";

		$rlt=db_query($sql);
		
		//echo '<br> sql:'.$sql;

		//se encontrou então baixa
		//echo '<br>Registros encontrados:'.pg_num_rows($rlt);
		
		if (pg_num_rows($rlt) > 0){
			$sql_trans.="UPDATE produto_estoque SET pe_status='T', pe_cliente='8284371', pe_doc=0, pe_vlr_vendido=0
						   where pe_ean13 = '".$inventario[$i][0]."';";

			$sql_trans.="INSERT INTO produto_log_".date('Ym')." ( 
	       				 pl_ean13, pl_data, pl_hora, pl_cliente, pl_status, pl_kit, pl_produto, pl_log) 
    					VALUES ('".$inventario[$i][0]."',".date('Ymd').",'".date('H:i')."','8284371', 'H', '', '".$inventario[$i][2]."', '".$user_log."') ;";

			//Armazenando o i_cod_produto e o i_vlr_custo
			array_push($pecas_baixadas, array($inventario[$i][2], $inventario[$i][3]));
		}
	}
	
}

//Ao finzlizar o inventário fazer a soma da baixa de cada referênci ou produto
//Gravar o total por produto na tabela inventario_historico

//echo "<br> quantidade: ".count($pecas_baixadas);

$valor=0;$quantidade=0;
for($i=0; $i< count($pecas_baixadas); $i++){
	//echo '<br>baixa'.$pecas_baixadas[$i][0].'   '.$pecas_baixadas[$i][1];

	if ($i==0){
		$codigo=$pecas_baixadas[$i][0]; 
	}
	
	if ($codigo==$pecas_baixadas[$i][0]){
		$valor+=$pecas_baixadas[$i][1];
		$quantidade++;
	}
	else{
		$sql_trans.="INSERT INTO inventario_historico(
						ih_data_iventario, ih_cod_produto, ih_valor, ih_quantidade, 
          				ih_hora, ih_log)
					    VALUES (".date('Ymd').", '".$codigo."', ".$valor.", ".$quantidade.", 
		        	    '".date('H:i')."', '".$user_log."');";
		$codigo=$pecas_baixadas[$i][0];
		$valor=$pecas_baixadas[$i][1];
		$quantidade=1;
	}
}

if ($quantidade > 0){
	$sql_trans.="INSERT INTO inventario_historico(
						ih_data_iventario, ih_cod_produto, ih_valor, ih_quantidade, 
         				ih_hora, ih_log)
					    VALUES (".date('Ymd').", '".$codigo."', ".number_format($valor,2).", ".$quantidade.", 
		        	    '".date('H:i')."', '".$user_log."');";
}

//apagar definitivamente os registros que o campo i_baixar_reincorporar_autorizada='1' da tabela inventario.
$sql_trans.="DELETE FROM inventario
	 where i_baixar_reincorporar_autorizada='1';";
$sql_trans.='COMMIT;';

db_query($sql_trans);

//ECHO $sql_trans;

$sql="select * from inventario";
$rlt=db_query($sql);
if (pg_num_rows($rlt) == 0){
	require("../db_fghi2.php");
	$sql="UPDATE lista_atividades
		   SET la_status='B', la_lido='S', la_data_lido=".date('Ymd').", la_hora_lido='".date('H:i')."', la_log_lido='".$user_log."'
			 WHERE la_cod_atendimento='INV'";
	db_query($sql);
}

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

//redirecionar para a tela da baixa
redirecina("estoque_inventario_item3.php");

echo $hd->foot();
?>	
