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

//Listar os produtos do inventário que não foram autorizados e nem finalizados
$sql="SELECT i_cod_produto, p_descricao, i_status, count(*) as status_count, sum(i_valor_custo) as status_valor
		  FROM inventario  
		  left join produto on p_codigo=i_cod_produto
	    group by i_cod_produto, p_descricao, i_status
		order by i_cod_produto, i_status";

$rlt=db_query($sql);

if (pg_num_rows($rlt) > 0){
	echo '<form action="estoque_inventario_item4_2.php" method="post">';
	echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<tr><td colspan="6" align="center" class="lt5">Lista aprovada de inventários</td></tr>';
	echo '<tr>';
	echo '<th class="1_th">Produto</th>';
	echo '<th class="1_th">Baixar</th>';
	echo '<th class="1_th">Reincorporar</th>';
	echo '<th class="1_th">Inventariados</th>';
	echo '<th class="1_th">Ação</th>';
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
			$link_baixa='<a href="estoque_inventario_item4_1.php?codigo='.$cod_produto.'&status=B">'.$baixa_qtd.'</a>';
			$link_reincorporar='<a href="estoque_inventario_item4_1.php?codigo='.$cod_produto.'&status=R">'.$reincorporar_qtd.'</a>';
			$link_inventario='<a href="estoque_inventario_item4_1.php?codigo='.$cod_produto.'&status=I">'.$inventario_qtd.'</a>';

			echo '<tr '.coluna().'>';
			echo '<td class="1_td">'.$cod_produto.' '.$descricao.'</td>';
			echo '<td class="1_td" align="center">'.$link_baixa.'</td>';
			echo '<td class="1_td" align="center">'.$link_reincorporar.'</td>';
			echo '<td class="1_td" align="center">'.$link_inventario.'</td>';
			echo '<td class="1_td" align="center"><input type="submit" name="bt_baixar['.$itens.']" value="Baixar"></td>';	

			echo '<td class="1_td" align="center"><input type="hidden" name="txt_codigo['.$itens.']" value="'.$cod_produto.'"></td>';
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
	echo '</table>';
	echo '</form>';
}
else{
	echo "<br>Não há registro para ser exibido.";
}

echo $hd->foot();

?>
