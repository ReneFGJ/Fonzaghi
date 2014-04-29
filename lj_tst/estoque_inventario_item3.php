<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item3.php','Lista pendentes de aprovação'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
echo '1';
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

//require($include."biblioteca.php");
echo '3';
require("estoque_funcoes.php");
echo '3';
require("db_temp.php");

if ($user_nivel < 9){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

acao();

//Listar os produtos do inventário que não foram autorizados e nem finalizados
$sql="SELECT i_cod_produto, p_descricao, i_status, count(*) as status_count, sum(i_valor_custo) as status_valor, i_enviado
		  FROM inventario  
		  left join produto on p_codigo=i_cod_produto
		  where i_enviado='S'
	      group by i_cod_produto, p_descricao, i_status, i_enviado
		  order by i_cod_produto, i_status";

$rlt=db_query($sql);

if (pg_num_rows($rlt) > 0){
	echo '<form action="estoque_inventario_item3.php" method="post">';
	echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<tr><td colspan="5" align="center" class="lt5">Lista pendentes de aprovação</td></tr>';
	echo '<tr>';
	echo '<th class="1_th">Produto</th>';
	echo '<th class="1_th">Baixar<br>(Valor)</th>';
	echo '<th class="1_th">Reincorporar<br>(Valor)</th>';
	echo '<th class="1_th">Inventariados<br>(Valor)</th>';
	echo '<th class="1_th">Aprovado</th>';
	echo '</tr>';
	
	//usar 4 colunas para listar os ean13
	$itens=0;
	$baixa=0;$baixa_qtd=0;
	$inventario=0;$inventario_qtd=0;
	$reincorporar=0;$reincorporar_qtd=0;

	
	for($i=0; $i <= pg_num_rows($rlt); $i++){
		$line = db_read($rlt);
		
		//Inicializando as variáveis
		if ($i==0){
			$cod_produto=$line['i_cod_produto'];
			
			if ($line['i_status'] == 'B'){
				$baixa=$line['status_valor'];
				$baixa_qtd=$line['status_count'];
			}
			if ($line['i_status'] == 'R'){
				$reincorporar=$line['status_valor'];
				$reincorporar_qtd=$line['status_count'];
			}
			if ($line['i_status'] == 'I'){
				$inventario=$line['status_valor'];
				$inventario_qtd=$line['status_count'];
			}
			$descricao=$line['p_descricao'];
		}
		
		if ($cod_produto==$line['i_cod_produto']){
			if ($line['i_status'] == 'B'){
				$baixa=$line['status_valor'];
				$baixa_qtd=$line['status_count'];
			}
			if ($line['i_status'] == 'R'){
				$reincorporar=$line['status_valor'];
				$reincorporar_qtd=$line['status_count'];
			}
			if ($line['i_status'] == 'I'){
				$inventario=$line['status_valor'];
				$inventario_qtd=$line['status_count'];
			}
			$descricao=$line['p_descricao'];
		}
		else{
			if ($baixa_qtd > 0){$link_baixa='<a href="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status=B">'.$baixa_qtd.'</a>';}
			else{$link_baixa=0;}
			
			if ($reincorporar_qtd > 0){$link_reincorporar='<a href="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status=R">'.$reincorporar_qtd.'</a>';}
			else{$link_reincorporar=0;}
			
			if ($inventario_qtd > 0){$link_inventariado='<a href="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status=I">'.$inventario_qtd.'</a>';}
			else{$link_inventariado=0;}

			echo '<tr '.coluna().'>';
			echo '<td class="1_td">'.$cod_produto.' '.$descricao.'</td>';
			echo '<td class="1_td" align="center">'.$link_baixa.'<br>(<font color="#ff0000">'.number_format($baixa,2).'</font>)</td>';
			echo '<td class="1_td" align="center">'.$link_reincorporar.'<br>('.number_format($reincorporar,2).')</td>';
			echo '<td class="1_td" align="center">'.$link_inventariado.'<br>('.number_format($inventario,2).')</td>';
			echo '<input type="hidden" name="txt_codigo['.$itens.']" value="'.$cod_produto.'">';

			$aprovado=todos_aprovados($cod_produto);
			if ($aprovado == 1){
				echo '<td class="1_td" align="center"><input type="checkbox" name="chk_aprovado['.$itens.']" checked value="'.$cod_produto.'"></td>';
			}
			else if ($aprovado == 0) {
				echo '<td class="1_td" align="center"><input type="checkbox" name="chk_aprovado['.$itens.']" value="'.$cod_produto.'"></td>';
			}

			echo '</tr>';
			$itens++;
			
			//mudança de registro
			$cod_produto=$line['i_cod_produto'];
			$baixa=0;$baixa_qtd=0;
			$inventario=0;$inventario_qtd=0;
			$reincorporar=0;$reincorporar_qtd=0;

			//Salvando o registro que foi mudado
			if ($line['i_status'] == 'B'){
				$baixa=$line['status_valor'];
				$baixa_qtd=$line['status_count'];
			}
			if ($line['i_status'] == 'R'){
				$reincorporar=$line['status_valor'];
				$reincorporar_qtd=$line['status_count'];
			}
			if ($line['i_status'] == 'I'){
				$inventario=$line['status_valor'];
				$inventario_qtd=$line['status_count'];
			}
			$descricao=$line['p_descricao'];
		}
	}
	echo '<tr><td colspan="6" class="rodapetotal">'.$itens.' ítnes</td></tr>';
	
	echo '<tr>';
	echo '<td colspan="4" align="center"><input type="submit" name="bt_confirmar" value="Baixar as peças aprovadas"></td>';
	echo '<td align="center"><input type="submit" name="bt_gravar" value="Gravar seleção dos aprovados"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
}
else{
	echo "<br>Não há registro para ser exibido.";
}

function todos_aprovados($codigo){
	$sql="select * from inventario
			where i_cod_produto='".$codigo."'";
	$rlt=db_query($sql);
	
	while($line=db_read($rlt)){	
		if ($line['i_baixar_reincorporar_autorizada']=='0'){
			return 0;
		}
	}

	return 1;
}

function acao(){
	//ECHO '<BR> POST: ';
	//print_r($_POST);

	if (strlen($_POST['bt_gravar']) > 0){
		for($i=0; $i<count($_POST['txt_codigo']); $i++){
			$sql="UPDATE inventario SET ";

			if (strlen($_POST['chk_aprovado'][$i]) > 0)
				$sql.=" i_baixar_reincorporar_autorizada = '1' " ;
			else
				$sql.=" i_baixar_reincorporar_autorizada = '0' " ;

			$sql.=" WHERE i_cod_produto='".$_POST['txt_codigo'][$i]."'";

			$rlt=db_query($sql);
		}
	}

	if (strlen($_POST['bt_confirmar']) > 0){
		redirecina('estoque_inventario_item3_3.php');
	}
	
}

echo $hd->foot();

?>

