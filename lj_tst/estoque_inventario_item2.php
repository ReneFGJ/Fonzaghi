<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item2.php','Lista de ítens inventariados'));

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

if ($user_nivel < 0){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

require("../db_fghi2.php");
require("db_temp.php");

//Listar os produtos do inventário que não foram autorizados e nem finalizados
$sql="SELECT i_cod_produto, p_descricao, i_status, count(*) as status_count, sum(i_valor_custo) as status_valor, i_enviado
		  FROM inventario  
		  left join produto on p_codigo=i_cod_produto
		  where i_baixar_reincorporar_autorizada='0'
		    group by i_cod_produto, p_descricao, i_status, i_enviado
			order by i_enviado, i_cod_produto, i_status";

$rlt=db_query($sql);

if (pg_num_rows($rlt) > 0){
	echo '<form action="estoque_inventario_item2.php" method="post">';
	echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<tr><td colspan="5" align="center" class="lt5">Lista de ítens inventariados</td></tr>';
	echo '<tr>';

	$img="<img src='img/icone_retangulo_amarelo.JPG' width='10' height='10' alt='' border='0'>";
	echo '<td height="20">'.$img.'<b>[Não enviados à diretoria]</b></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th class="1_th">Produto</th>';
	echo '<th class="1_th">Baixar</th>';
	echo '<th class="1_th">Reincorporar</th>';
	echo '<th class="1_th">Inventariados</th>';
	echo '</tr>';
	
	//usar 4 colunas para listar os ean13
	$itens=0;
	$baixa=0;$baixa_qtd=0;
	$inventario=0;$inventario_qtd=0;
	$reincorporar=0;$reincorporar_qtd=0;
	$enviado='';
	
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
			$enviado=$line['i_enviado'];
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
			$enviado=$line['i_enviado'];
		}
		else{
			if ($baixa_qtd > 0){$link_baixa='<a href="estoque_inventario_item2_1.php?codigo='.$cod_produto.'&status=B">'.$baixa_qtd.'</a>';}
			else{$link_baixa=0;}

			if ($reincorporar_qtd > 0){$link_reincorporar='<a href="estoque_inventario_item2_1.php?codigo='.$cod_produto.'&status=R">'.$reincorporar_qtd.'</a>';}
			else{$link_reincorporar=0;}
			
			if ($inventario_qtd > 0){$link_inventario='<a href="estoque_inventario_item2_1.php?codigo='.$cod_produto.'&status=I">'.$inventario_qtd.'</a>';}
			else{$link_inventario=0;}
			
			if ($enviado=='N'){$cor="#ffff66";}
			else{$cor="";}
			
			echo '<tr '.coluna().'>';
			//echo '<td class="1_td" bgcolor="'.$cor.'"><font color="'.$cor.'">'.$cod_produto.' '.$descricao.'</font></td>';
			echo '<td class="1_td" bgcolor="'.$cor.'">'.$cod_produto.' '.$descricao.'</td>';
			echo '<td class="1_td" bgcolor="'.$cor.'" align="center">'.$link_baixa.'</td>';
			echo '<td class="1_td" bgcolor="'.$cor.'" align="center">'.$link_reincorporar.'</td>';
			echo '<td class="1_td" bgcolor="'.$cor.'" align="center">'.$link_inventario.'</td>';
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
			$enviado=$line['i_enviado'];
		}
	}
	echo '<tr><td colspan="5" class="rodapetotal">'.$itens.' ítnes</td></tr>';
	echo '<tr><td colspan="5" align="center"><input type="Button" onclick="newxy2(\'msg_enviar.php\', 450, 330);" name="bt_diretoria" value="Enviar pedido de baixa à diretoria"></td></tr>';	
	echo '</table>';
	echo '</form>';
}
else{
	echo "<br>Não há registro para ser exibido.";
}


echo $hd->foot();

	?>
