<script language='JavaScript'>
function setFocus(){
	document.getElementById("txt_ean13").focus();
}

function tecla(e){
	var whichCode = (window.Event) ? e.which : e.keyCode;

	if (whichCode == 13 ){
		//alert('tecla enter\nteste');
		document.getElementById("bt_baixa").click();
	}
}

function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla > 47 && tecla < 58) || (tecla == 13)){return true;}
    else{return false;}
}

function msg(formulario){
	if (confirm("Tem certeza que deseja FINALIZAR o inventário?")){return true;}
	else{return false;}
}
function msg1(formulario){
	if (confirm("Tem certeza que deseja REFAZER o inventário?")){return true;}
	else{return false;}
}
</script>
<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item.php','Ítens à inventariar'));

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

$loja='S'; //da loja sensual

$contador;
$qtd_pecas;

acao();

$sql="SELECT p_descricao
		  FROM produto
		  where p_codigo='".trim($dd[0])."'";
$rlt=db_query($sql);
$line=db_read($rlt);

echo '<body onload="setFocus();"">';
echo '<form name="form1" action="estoque_inventario_item1.php?dd0='.$dd[0].'&contador='.$contador.'" method="post">';
echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
echo '<tr><th class="legenda" height="20" width="260">Referência do produto</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$dd[0].' '.$line['p_descricao'].'</font></td></tr>';
echo '<tr><th class="legenda" height="20" width="260">Quantidade de peças<br></th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$qtd_pecas.'</font></td></tr>';
echo '<tr><th class="legenda" height="20" width="260">Peças inventariadas<br></th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$contador.'</font></td></tr>';
echo '<tr><th class="legenda" width="260">Digite o EAN13 do produto para fazer seu inventário</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<input type="text" name="txt_ean13" size="20" maxlength="13" onkeypress="return SomenteNumero(event); tecla(event);">&nbsp;<input type="submit" name="bt_baixa" value="Confirmar"></td></tr>';
echo '<tr><td height="40" valign="bottom" align="center" colspan="2"><input type="submit" onclick="return msg1(this)" name="bt_refazer_inventario" value="Refazer o inventário deste produto"></td></tr>';
echo '<tr><td height="40" valign="bottom" align="center" colspan="2"><input type="submit" onclick="return msg(this)" name="bt_concluir" value="Finalizar o inventário deste produto"></td></tr>';
echo '</table>';
echo '</form>';
echo '</body>';

function acao(){
	global $user_log, $contador, $qtd_pecas;

	$ean13=trim($_POST['txt_ean13']);
	$pe_produto=trim($_GET['dd0']);

	$valor=0;
	
	/*
	echo "<br>Post:";
	print_r($_POST);
	
	echo "<br>Get:";
	print_r($_GET);
	*/
	/////////////////////////////////////////////////////////////////
	//Se este produto não esta sendo inventariado 
	//então setar as peças que podem ser inventariadas.
	if (strlen($pe_produto) > 0){
		$sql="select * from inventario
				where i_cod_produto='".$pe_produto."'";
		$rlt=db_query($sql);

		//Se este produto não esta sendo inventariado 
		//então setar as peças que podem ser inventariadas.
		if (pg_num_rows($rlt) == 0){
			//Zera o inventario deste produto
			$sql="UPDATE produto_estoque SET pe_inventario=0
				 WHERE pe_produto = '".$pe_produto."'";
			$rlt=db_query($sql);

			//Seta as peças que podem ser inventariadas
			$sql="UPDATE produto_estoque SET pe_inventario=1
				 WHERE pe_produto = '".$pe_produto."'
				 	and (pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X')";
			$rlt=db_query($sql);
		}
		else{
			?>
			<script>
			alert("ATENÇÃO: Este produto já esta sendo inventariado.");
			</script>
			<?
		}
	}

	////////////////////////////////////////////////////////////////
	if (strlen($_POST['bt_refazer_inventario']) > 0){
		//Zera o inventario deste produto
		$sql="UPDATE produto_estoque SET pe_inventario=0
			 WHERE pe_produto = '".$pe_produto."'";
		$rlt=db_query($sql);

		//Seta as peças que podem ser inventariadas
		$sql="UPDATE produto_estoque SET pe_inventario=1
			 WHERE pe_produto = '".$pe_produto."'
			 	and (pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X')";
		$rlt=db_query($sql);

		//Zera os registros da tabela temporária
		$sql="DELETE from inventario where i_cod_produto='".$pe_produto."'";
		$rlt=db_query($sql);
	}

	/////////////////////////////////////////////////////////////////
	if (!isset($qtd_pecas)){
		$sql="SELECT count (*) as qtd_pecas
			  FROM produto_estoque
			  where pe_produto='".$pe_produto."' and pe_status <> 'T' and pe_status <> 'X'
				and pe_status <> 'F' 
				and pe_inventario=1";

		$rlt=db_query($sql);
		$line=db_read($rlt);
		$qtd_pecas=$line['qtd_pecas'];
	}
	
	if (!isset($contador)){
		$sql="SELECT count (*) as contador
				  FROM inventario
				  where i_cod_produto='".$pe_produto."'";
				  
		$rlt=db_query($sql);
		$line=db_read($rlt);
		$contador=$line['contador'];
	}

	/////////////////////////////////////////////////////////////////
	if (strlen($_POST['bt_concluir']) > 0){
		//Os ean13 que não constam no inventário
		//devem ser inseridos com o status 'B' de baixa
		$sql="select * from produto_estoque 
				   where pe_produto = '".$pe_produto."' 
				   and (pe_status <> 'T' and pe_status <> 'X' and pe_status <> 'F') 
				   and pe_inventario=1";
		$rlt=db_query($sql);

		//echo '<br> bt_concuir: '.$sql;
		
		$tb_estoque=array();
		while($line=db_read($rlt)){
			array_push($tb_estoque, array($line['pe_ean13'], $line['pe_vlr_custo'], $line['pe_inventario']));
		}
		//print_r($tb_estoque);
		
		//Inserir no inventário caso não exista
		for($i=0; $i<count($tb_estoque); $i++){

			//echo '<br>'.$tb_estoque[$i][0].' ---- '.$tb_estoque[$i][1];
			$sql="SELECT * FROM inventario
				  where i_ean13='".$tb_estoque[$i][0]."'";
			$rlt=db_query($sql);
			if (pg_num_rows($rlt) == 0){
				//Inserir no inventario para ser baixado
				$sql="INSERT INTO inventario(
	        		    i_ean13, i_cod_produto, i_valor_custo, i_status )
				    VALUES ('".$tb_estoque[$i][0]."', '".$pe_produto."', ".$tb_estoque[$i][1].", 'B')";

				$rlt=db_query($sql);

				//$contador++;
				//redirecina("estoque_inventario_item1.php?dd0=".$pe_produto."&contador=".$contador);
			}
		}
		redirecina("estoque_inventario_item.php");
	}
	
	/////////////////////////////////////////////////////////////////
	//Verificar se o ean13 pertence ao produto
	if ((strlen($_POST['bt_baixa']) > 0) 
		|| (strlen($_POST['txt_ean13']) > 6)){

		$sql="SELECT * FROM produto_estoque
			  where pe_ean13='".$ean13."'";
		$rlt=db_query($sql);

		$codigo_ok=0;
		$valor=0;
		if (pg_num_rows($rlt) > 0){
			while($line=db_read($rlt)){
				//echo $line['pe_produto'];
				//echo '<br>dd0='.$pe_produto;
				if ($line['pe_produto'] == $pe_produto){
					$codigo_ok=1;
					$valor=$line['pe_vlr_custo'];
				}
			}
			
			if ($codigo_ok == 1){
				//Verificando se o produto está no estoque
				$sql="select * from produto_estoque 
				   		where pe_produto = '".$pe_produto."' and pe_ean13 = '".$ean13."'
				   		and (pe_status <> 'T' and pe_status <> 'X' and pe_status <> 'F') 
				   		and pe_inventario=1";

				$rlt=db_query($sql);

				//echo $sql;

				//Está no estoque?
				if (pg_num_rows($rlt) > 0){

					//Pegando o valor de custo
					$line=db_read($rlt);
					$valor=$line['pe_vlr_custo'];

					//verificar se o ean13 já foi inventariado
					$sql="SELECT * FROM inventario
						  where i_ean13='".$ean13."'";
					$rlt=db_query($sql);

					if (pg_num_rows($rlt) == 0){
						//Inserir no inventario
						$sql="INSERT INTO inventario(
				        		    i_ean13, i_cod_produto, i_valor_custo, i_status) 
							    VALUES ('".$ean13."', '".$pe_produto."', ".$valor.", 'I')";
						$rlt=db_query($sql);

						$contador++;
						redirecina("estoque_inventario_item1.php?dd0=".$pe_produto."&contador=".$contador);
					}
					else{
						?>
						<script>
						alert("Esta peça já foi inventariada.");
						</script>
						<?
					}
					return (1);
				}
				else{
					//verificar se o ean13 já foi inventariado
					$sql="SELECT * FROM inventario
						  where i_ean13='".$ean13."'";
					$rlt=db_query($sql);

					if (pg_num_rows($rlt) == 0){

						//Seta esta peça como inventariada que será reincorporada
						$sql="UPDATE produto_estoque SET pe_inventario=1
								 WHERE pe_ean13 = '".$ean13."'";
						$rlt=db_query($sql);

						//produto deve ser reincorporado ao estoque
						//Inserir no inventário
						$sql="INSERT INTO inventario(
				        		    i_ean13, i_cod_produto, i_valor_custo, i_status)
							    VALUES ('".$ean13."', '".$pe_produto."', ".$valor.", 'R')";
						$rlt=db_query($sql);

						$contador++;
						redirecina("estoque_inventario_item1.php?dd0=".$pe_produto."&contador=".$contador);
						return (1);
					}
				}
			}
			else{
				?>
				<script>
				alert("ERRO: Este código de barra não pertence a esta referência de produto.");
				</script>
				<?
				return 0;
			}
		}
		else{
			?>
			<script>
			alert("ERRO: Código inválido.");
			</script>
			<?
			return 0;
		}
		return (1);
	}
}

echo $hd->foot();
?>
