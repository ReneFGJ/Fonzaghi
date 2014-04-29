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

//print_r($_GET);

acao();

$cod_produto=$_GET['codigo'];
$status=$_GET['status'];

if ($status=='B'){$titulo='Lista de ítens para baixar';}
if ($status=='R'){$titulo='Lista de ítens para reincorporar';}
if ($status=='I'){$titulo='Lista de ítens inventariados';}

//Listar os produtos do inventário que não foram autorizados e nem finalizados
$sql="SELECT i_cod_produto, p_descricao, i_ean13, i_comentario_supervisor, i_comentario_diretoria,
			i_baixar_reincorporar_autorizada
		  FROM inventario  
		  left join produto on p_codigo=i_cod_produto
		  where i_cod_produto='".$cod_produto."' 
			and i_status='".$status."' 
			order by i_cod_produto, i_ean13";
	
$rlt=db_query($sql);

if (pg_num_rows($rlt) > 0){
	echo '<form action="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status='.$status.'" method="post">';
	echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<tr><td colspan="6" align="center" class="lt5">'.$titulo.'</td></tr>';
	echo '<tr>';
	echo '<th class="1_th">Produto</th>';
	echo '<th class="1_th">EAN13</th>';
	echo '<th class="1_th">Comentário<br>do(a) supervisor(a)</th>';
	echo '<th class="1_th">Comentário<br>da diretoria</th>';
	if (strlen($_GET['todos']) == 0){$link_todos='<a href="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status='.$status.'&todos=1"><font color="#0000ff">Todos</font></a>';}
	else{
		if ($_GET['todos'] == 0){$link_todos='<a href="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status='.$status.'&todos=1"><font color="#0000ff">Todos</font></a>';}
		else{$link_todos='<a href="estoque_inventario_item3_1.php?codigo='.$cod_produto.'&status='.$status.'&todos=0"><font color="#0000ff">Nenhum</font></a>';}
	}
	
	echo '<th class="1_th">Aprovado<br>'.$link_todos.'</th>';
	echo '</tr>';
	
	//usar 4 colunas para listar os ean13
	$itens=0;
	$baixa=0;$baixa_qtd=0;
	$inventario=0;$inventario_qtd=0;
	$reincorporar=0;$reincorporar_qtd=0;

	for($i=0; $i < pg_num_rows($rlt); $i++){
		$line = db_read($rlt);

		echo '<tr '.coluna().'>';
		echo '<td class="1_td">'.$line['i_cod_produto'].' '.$line['p_descricao'].'</td>';
		echo '<td class="1_td" align="center">'.$line['i_ean13'].'</td>';

		if (strlen(trim($line['i_comentario_supervisor'])) > 0)
			echo '<td class="1_td" align="center">'.$line['i_comentario_supervisor'].'</td>';
		else
			echo '<td class="1_td" align="center">&nbsp;</td>';

		echo '<td class="1_td" align="center"><input type="text" name="txt_comentario2[]" size="20" value="'.trim($line['i_comentario_diretoria']).'" maxlength="50"></td>';

		if ($line['i_baixar_reincorporar_autorizada']=='1')
			echo '<td class="1_td" align="center"><input type="checkbox" name="chk_aprovado['.$i.']" checked value="'.$line['i_ean13'].'"></td>';
		else
			echo '<td class="1_td" align="center"><input type="checkbox" name="chk_aprovado['.$i.']" value="'.$line['i_ean13'].'"></td>';

		echo '<td class="1_td" align="center"><input type="hidden" name="txt_ean13[]" value="'.$line['i_ean13'].'"></td>';
		echo '</tr>';
		$itens++;
	}
	echo '<tr><td colspan="6" class="rodapetotal">'.$itens.' ítnes</td></tr>';
	echo '<tr><td colspan="6" align="center"><input type="submit" name="bt_gravar" value="Gravar">&nbsp;&nbsp;';
	echo '<input type="submit" name="bt_cancelar" value="Cancelar"></td></tr>';
	echo '</table>';
	echo '</form>';
}
else{
	echo "<br>Não há registro para ser exibido.";
}

function acao(){
	global $user_log;
	//echo '<br>count: '.count($_POST['txt_ean13']);
	
	//ECHO '<BR> POST';
	//print_r($_POST);
	
	//ECHO '<BR> GET';
	//print_r($_GET);
	
	if (strlen($_GET['todos']) >0){

		$sql="UPDATE inventario SET i_baixar_reincorporar_autorizada = '".$_GET['todos']."' 
				WHERE i_cod_produto='".$_GET['codigo']."'";

		$rlt=db_query($sql);
	}

	if (strlen($_POST['bt_gravar']) > 0){
		for($i=0; $i<count($_POST['txt_ean13']); $i++){
			//echo "<br>Arpvado: ".$_POST['txt_ean13'][$i].'   '.$_POST['chk_aprovado'][$i];

			$sql="UPDATE inventario
					SET i_comentario_diretoria='".$_POST['txt_comentario2'][$i]."', ";

			if (strlen($_POST['chk_aprovado'][$i]) > 0)
				$sql.=" i_baixar_reincorporar_autorizada = '1' " ;
			else
				$sql.=" i_baixar_reincorporar_autorizada = '0' " ;

			$sql.=" WHERE i_ean13='".$_POST['txt_ean13'][$i]."'";
			//echo '<br>sql='.$sql;
			$rlt=db_query($sql);
		}
		redirecina("estoque_inventario_item3.php");
	}
	
	if (strlen($_POST['bt_cancelar']) > 0){
		redirecina("estoque_inventario_item3.php");
	}
	
}

echo $hd->foot();
?>
