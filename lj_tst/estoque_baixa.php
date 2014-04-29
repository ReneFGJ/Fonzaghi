<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('estoque_baixa.php','Baixa de estoque de produto danificado/amostra'));

$include = '../';
require("../cab_novo.php");

require($include."_class_form.php");
$form = new form;

require("estoque_funcoes.php");
require("db_temp.php");

echo '<h1>Baixas de estoques</h1>';

$cp=array();
$motivo=array();
array_push($motivo,'Produto com defeito');
array_push($motivo,'Produto utilizado como mostruário');
array_push($motivo,'Produto não localizado no inventário');
array_push($motivo,'Produto com etiqueta errada');
array_push($motivo,'Outros motivos');
array_push($motivo,'Produto vencido');
array_push($motivo,'Produto desmembrado (parte com defeito)');
array_push($motivo,'Transferência de lojas');
array_push($motivo,'Produto avariado sem possibilidade de venda');
array_push($motivo,'Devolução fornecedor');
$ops = '';
for ($r=0;$r < count($motivo);$r++)
	{
		$ops .= '&';
		$ops .= trim($r).':'.trim($motivo[$r]);
	}
array_push($cp,array('$S8','','Senha da baixa',True,True,''));
array_push($cp,array('$T30:15','','Código EAN13 das peças',True,True,''));
array_push($cp,array('$O '.$ops,'','Motivo da baixa',True,True,''));

//$dd[0]=$_GET['dd55'];

$tela = $form->editar($cp,'');
	
if ($form->saved == 0)
	{
		echo $tela;
	} else {
			
	if (estoque_senha_confere($dd[0])==1)
		{
		require("db_temp.php");	
	
		$pecas = troca($dd[1],chr(13),';');
		$pec = splitx(';',$pecas);
	
	
		for ($r=0;$r < count($pec);$r++)
			{
			$peca = $pec[$r];
			echo '<BR>'.$peca.' ';
		
			$sql="Select * from produto_estoque
				WHERE pe_ean13='".$peca."'"; 
			$rlt=db_query($sql);	
			if ($line=db_read($rlt))
				{
	/* */		$sta = trim($line['pe_status']);
				$ok = 0;
				switch ($sta)
					{
					case 'T':
						if ($line['pe_cliente']=='8284371')
							{ echo '<font size="+1" color="#ff0000">Este código de barras já foi baixado.</font>'; }
						else 
							{ echo '<font size="+1" color="#ff0000">Este código de barras já foi vendido.</font>'; }
						break;
					case 'F':
						echo '<font size="+1" color="#ff0000">Este código de barras consta como fornecido para o cliente '.$line['pe_cliente'].'.</font>';
						break;
					case 'X':
						echo '<font size="+1" color="#ff0000">Este código está cancelado.</font>';											
						break;					
					case 'X':
						echo '<font size="+1" color="#00ff00">Este código processo de baixa.</font>';											
						break;
					default: 
						$ok = 1;
						echo 'Baixa ok!';				
					}
				if ($ok == 1)
					{
					$sql="UPDATE produto_estoque
	   					SET pe_cliente='8284371', pe_status='H', 
		    			pe_lastupdate=".date('Ymd').", pe_log='".$user_log."', 
						pe_vlr_vendido=0, pe_doc=".$dd[2]."
						WHERE pe_ean13='".$peca."'";
						db_query($sql);
	
					$sql="INSERT INTO produto_log_".date('Ym')." ( 
			        		pl_ean13, pl_data, pl_hora, pl_cliente, pl_status, pl_kit, pl_produto, pl_log) 
			    			VALUES ('".$peca."',".date('Ymd').",'".date('H:i')."','8284371', 'H', '', '".$line['pe_produto']."', '".$user_log."') ";
						db_query($sql);
					}
				} else {
					echo ' Peça não localizada';
				}
			}
			
		$sql="UPDATE produto_estoque ";
	   	$sql.=" SET pe_cliente='8284371', pe_status='T'  ";
		$sql.=" WHERE pe_status = 'H' ";
		db_query($sql);
	} else {
		if (strlen($dd[0]) > 0){
			echo '<br><font size="+1" color="#ff0000">Senha inválida.</font>';
		}
	}
}


/* Finaliza Baixa dos produtos */
$baixa=$_GET['baixa'];
if (strlen($baixa)==0){
	//Verifica se existe lote aberto
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
}

echo $hd->foot();	
?>