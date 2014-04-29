<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item4.php','Lista aprovada de inventários'));

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

if ($user_nivel < 0){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

//print_r($_POST);

if (strlen($_POST['bt_cancelar']) > 0){
	redirecina("estoque_inventario_item4.php");
}

if (strlen($_POST['bt_confirmar']) > 0){
	if (inventarioSenhaConfere($_POST['txt_codigo'], $_POST['txt_senha'])==1){
		//Filtrar o inventario pelo codigo, data, autorizado=1, status <> I
		//armazenar em um array
		$sql="SELECT * FROM inventario
			  where i_cod_produto='".$_POST['txt_codigo']."' 
			  	and i_baixar_reincorporar_autorizada='1' and i_status <> 'I'
				order by i_status";
		$rlt=db_query($sql);
		//echo '<br>sql 1: '.$sql;
		
		$inventario=array();
		while($line=db_read($rlt)){
			array_push($inventario, array($line['i_ean13'], $line['i_status'], $line['i_cod_produto']));
		}

		$sql_trans='BEGIN;';
		for($i=0; $i < count($inventario); $i++){
			//se no array o status for R
			//echo '<br>'.$inventario[$i][0].'-'.$inventario[$i][1].'-'.$inventario[$i][2];
			
			if ($inventario[$i][1]=='R'){
				//filtar o produto_estoque pelo ean13 (do array)
				$sql_trans.="UPDATE produto_estoque SET pe_status='A', pe_cliente='       ', pe_doc=0, pe_vlr_vendido=0, pe_inventario=1 
				   where pe_ean13 = '".$inventario[$i][0]."';";


				$sql_trans.="INSERT INTO produto_log_".date('Ym')." ( 
		        			pl_ean13, pl_data, pl_hora, pl_cliente, pl_status, pl_kit, pl_produto, pl_log)
	    					VALUES ('".$inventario[$i][0]."',".date('Ymd').",'".date('H:i')."','8284371', 'H', '', '".$inventario[$i][2]."', '".$user_log."'); ";
			}
			
			//se no array o status for B
			if ($inventario[$i][1]=='B'){
				//filtar o produto_estoque pelo ean13 (do array) e status <> F, T, X
				$sql.="select * from produto_estoque 
					   where pe_ean13 = '".$inventario[$i][0]."' 
					   and (pe_status='T' and pe_status='X' and pe_status='F') ;";
				//$rlt=db_query($sql);
				//echo '<br>sql 4: '.$sql;

				//se encontrou então baixa
				if (pg_num_rows($rlt) > 0){
					$sql_trans.="UPDATE produto_estoque SET pe_status='X', pe_cliente='       ', pe_doc=0, pe_vlr_vendido=0, pe_inventario=1 
						   where pe_ean13 = '".$inventario[$i][0]."';";

					$sql_trans.="INSERT INTO produto_log_".date('Ym')." ( 
			        				 pl_ean13, pl_data, pl_hora, pl_cliente, pl_status, pl_kit, pl_produto, pl_log) 
	    								VALUES ('".$inventario[$i][0]."',".date('Ymd').",'".date('H:i')."','8284371', 'H', '', '".$inventario[$i][2]."', '".$user_log."') ;";
				}
			}
		}
		
		//Ao finzlizar o inventário fazer a soma da baixa de cada referênci ou produto
		$sql="SELECT i_cod_produto, sum(i_valor_custo) as valor_total, , count(*) as quantidade
				  FROM inventario
				  where i_status='B' and i_baixar_reincorporar_autorizada='1'
				  group by i_cod_produto, i_valor_custo";
		$rlt=db_query($sql);

		//Gravar o total por produto na tabela inventario_historico
		while($line=db_read($rlt)){
			$sql_trans.="INSERT INTO inventario_historico(
							ih_data_iventario, ih_cod_produto, ih_valor, ih_quantidade, 
            				ih_hora, ih_log)
					    VALUES (".date('Ymd').", '".$line['i_cod_produto']."', ".$line['valor_total'].", ".$line['quantidade'].", 
				            '".date('H:i')."', '".$user_log."');";
		}
		
		//apagar definitivamente os registros que o campo i_baixar_reincorporar_autorizada='1' da tabela inventario.
		$sql_trans.="DELETE FROM inventario
			 where i_baixar_reincorporar_autorizada='1';";

		$sql_trans.='COMMIT;';
		
		ECHO $sql_trans;

		//redirecionar para a tela da baixa
		//redirecina("estoque_inventario_item4.php");
	}
	else{
		echo $hd->foot();
		?>
		<script>
		alert('Senha inválida.');
		window.location = 'estoque_inventario_item4.php';
		</script>
		<?
	}  
}

echo $hd->foot();
?>	
