<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla > 47 && tecla < 58)) return true;
    else{
    if (tecla != 8) return false;
    else return true;
    }
}
</script>
<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario.php','Inventário de um produto'));

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

$cp=array();
array_push($cp,array('$S6','','Digite a referência do produto',True,True,''));
array_push($cp,array('$CH','','Zerar inventário',True,True,''));

echo '<CENTER><font class="lt5">Inventário</font></CENTER>';
if ($dd[0]==''){
	echo '<TABLE border="0" align="center" width="30%">';
	echo '<TR><TD>';
	editar();
	echo '</TD></TR>';
	echo '</TABLE>';
	echo $hd->foot();	
	exit;
}

//Verifica se existe alguma acao para efetuar.
acao();

$sql="SELECT pe_produto, pe_ean13, pe_status, pe_inventario
  FROM produto_estoque
  where pe_produto = '".$dd[0]."'
	and (pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X')
	and pe_inventario = 0 
	order by pe_produto, pe_ean13";

$rlt=db_query($sql);
//echo $sql;

if (pg_num_rows($rlt) > 0){
	$lista ='<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	$lista .='<th colspan="7" class="1_th">Listagem de EAN13 não inventariados</th>';
	
	//usar 4 colunas para listar os ean13
	$coluna=1;
	$naoInventariadas=0;
	$inventariadas=0;
	while ($line = db_read($rlt)){
		if ($coluna==1){$lista.='<tr '.coluna().'>';}
		
		$lista .='<td class="1_td" align="center" width="100">'.$line['pe_ean13'].'</td>';
	
		if ($coluna==7){
			$lista .='</tr>';
			$coluna=0;
		}
		$coluna++;
		$naoInventariadas++;
	}
	
	while($coluna <= 7){
		$lista .='<td class="1_td" align="center" width="100">&nbsp;&nbsp;</td>';
		$coluna++;
	}
	if ($coluna==8){$lista .='</tr>';}

	//Peças inventariadas	
	$sql="SELECT * FROM produto_estoque
		  where pe_produto = '".$dd[0]."'
			and (pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X')
			and pe_inventario = 1";

	$rlt=db_query($sql);
	$inventariadas=pg_num_rows($rlt);

	$lista .='<tr><td colspan="7" class="rodapetotal">'.$naoInventariadas.' ítens</td></tr>';
	$lista .='</table>';

	$sql="SELECT p_descricao
		  FROM produto
		  where p_codigo='".$dd[0]."'";
	$rlt=db_query($sql);
	$line=db_read($rlt);

	echo '<form action="estoque_inventario.php?dd0='.$dd[0].'" method="post">';
	echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<tr><th class="legenda" height="20" width="260">Referência do produto</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$dd[0].' '.$line['p_descricao'].'</font></td></tr>';
	echo '<tr><th class="legenda" height="20" width="260">Peças não inventariadas<br><font size="1"><i>(veja listagem abaixo)<i></font></th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$naoInventariadas.'</font></td></tr>';
	echo '<tr><th class="legenda" height="20" width="260">Peças inventariadas<br></th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$inventariadas.'</font></td></tr>';
	echo '<tr><th class="legenda" height="20" width="260">Total</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.($inventariadas+$naoInventariadas).'</font></td></tr>';

	$pendentes=$_GET['pendentes'];
	if ($pendentes==1){
		echo '<tr><th class="legenda" width="260">Informe a senha da baixa e clique no botão "Concluir"</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<input type="text" name="txt_senha" size="20" maxlength="13">&nbsp;<input type="submit" name="bt_baixa_todos" value="Concluir">&nbsp;|&nbsp;<input type="submit" name="bt_cancelar" value="Cancelar"></td></tr>';
	}	
	else{
		echo '<tr><th class="legenda" width="260">Digite o EAN13 do produto para fazer seu inventário<br><font size="1" color="#0000ff"><i>(obs: caso o EAN13 tenha sido baixado ele será incorporado ao estoque novamente)</i></font></th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<input type="text" name="txt_ean13" size="20" maxlength="13" onkeypress="return SomenteNumero(event)">&nbsp;<input type="submit" name="bt_baixa" value="Confirmar"></td></tr>';
		$link='<a href="estoque_inventario.php?dd0='.$dd[0].'&pendentes=1">Clique aqui</a>';
		echo '<tr><th class="legenda" width="260">Dar baixa nos produtos pendentes?</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;'.$link.'</td></tr>';
	}
	echo '</table>';
	echo '</form>';
	echo $lista;
}
else{	
	//print_r($_GET);
	
	if (strlen($_GET['etapa'])==0){
		$link1='<a href="estoque_inventario.php?dd0='.$dd[0].'&etapa=1">SIM</a>';
		$link2='<a href="estoque_inventario.php?dd0=">NÃO</a>';

		echo "<font class='lt2'>Para a referência <b>".$dd[0]."</b> não há ítnes no estoque.<br>
		&nbsp;Deseja reincorporar algum ítem ao estoque? (".$link1."/".$link2.")</font>";
	}
	else if ($_GET['etapa'] == 1){//reintegração
		echo '<form action="estoque_inventario.php?dd0=X&etapa=2" method="post">';
		echo '<font class="lt2">Digite o ean13 do produto</font>';
		echo '&nbsp;<input type="text" name="txt_ean13" onkeypress="return SomenteNumero(event)">';
		echo '<input type="submit" name="bt_reincorporar" value="Concluir">';
		echo '&nbsp;|&nbsp;<input type="submit" name="bt_cancelar_1" value="Cancelar">';
		echo '</form>';
	}
	else if ($_GET['etapa'] == 2){//
		if (strlen($_POST['txt_ean13']) > 0 && strlen($_POST['bt_cancelar'])==0){
			$sql="select * from produto_estoque where pe_ean13='".$_POST['txt_ean13']."'";
			$rlt=db_query($sql);
			//echo $sql;
			if (pg_num_rows($rlt) > 0){
				$sql="UPDATE produto_estoque SET pe_status='A', pe_cliente='       ', pe_doc=0, pe_vlr_vendido=0, pe_inventario=1 
				   where pe_ean13 = '".$_POST['txt_ean13']."'";
				$rlt=db_query($sql);
				?><script>
				window.location = 'estoque_inventario.php?dd0=';
				alert("Produto reincorporado com sucesso!")
				</script><?
			}
			else{
				?><script>
				window.location = 'estoque_inventario.php?dd0=X&etapa=1';
				alert("Não foi possível alterar o status do produto para 'A'. O produto não está registrado.")
				</script><?
			}
		}
		else if (strlen($_POST['bt_cancelar_1']) > 0){	
			?><script>
			window.location = 'estoque_inventario.php?';
			</script><?
		}
	}

}

function acao(){
	global $user_log;
	//print_r($_POST);
	//echo $user_log;

	//Zerar o inventário
	if ($_POST['dd1']==1){
		$sql="UPDATE produto_estoque SET pe_inventario=0 
			   where pe_produto = '".$_POST['dd0']."'
			   and pe_cliente <> '8284371' and pe_status <> 'T'";
		$rlt=db_query($sql);
	}
	
	if (strlen($_POST['bt_baixa']) > 0){
		$ean13=$_POST['txt_ean13'];
		if (strlen($ean13) > 0){
			//Localizando o ean13
			$sql="select * from produto_estoque 
			   where pe_ean13 = '".$ean13."'
			   and (pe_status='T' or pe_status='X' or pe_status='F') ";
			$rlt=db_query($sql);
			
			//Reincorporar o produto ao estoque
			if (pg_num_rows($rlt) > 0){
				$sql="UPDATE produto_estoque SET pe_status='A', pe_cliente='       ', pe_doc=0, pe_vlr_vendido=0, pe_inventario=1 
				   where pe_ean13 = '".$ean13."'";
				$rlt=db_query($sql);
				//echo $sql;
			}
			else{		
				$sql="UPDATE produto_estoque SET pe_inventario=1 
				   where pe_ean13 = '".$ean13."'";
				$rlt=db_query($sql);
				//echo $sql;
			}
		}
	}
	else if (strlen($_POST['bt_baixa_todos']) > 0){
		//Verificar a senha da baixa		
		if (estoque_senha_confere($_POST['txt_senha'])==1){

			//print_r($_GET);
			$sql="select * from produto_estoque 
				where pe_produto='".$_GET['dd0']."' 
					and pe_inventario=0";

			$rlt=db_query($sql);
			$tb=array();
			
			while($line=db_read($rlt)){
				array_push($tb, $line['pe_ean13']);
			}

			//print_r($tb);
			
			//echo '<br> count: '.count($tb);
			
			for($i=0; $i< count($tb); $i++){
				$sql="INSERT INTO produto_log_".date('Ym')." ( ";
		        $sql.=" pl_ean13, pl_data, pl_hora, pl_cliente, pl_status, pl_kit, pl_produto,  ";
	    	    $sql.=" pl_log) ";
	    		$sql.=" VALUES ('".$tb[$i]."',".date('Ymd').",'".date('H:i')."','8284371', 'H', '', '".$_GET['dd0']."',  ";
		        $sql.=" '".$user_log."') ";
				//echo "<br>".$sql;
				db_query($sql);
			}
			
			//pe_cliente='8284371' é FONZAGHI
			$sql="UPDATE produto_estoque ";
		   	$sql.=" SET pe_cliente='8284371', pe_status='T',  ";
		    $sql.=" pe_lastupdate=".date('Ymd').", pe_log='".$user_log."',  ";
			$sql.=" pe_vlr_vendido=0, pe_doc=2, pe_inventario=1"; //Produto não localizado no inventário
			$sql.=" WHERE pe_produto='".$_GET['dd0']."' and (pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X')";
			$sql.=" and pe_inventario=0";
			//echo $sql;
			db_query($sql);

			?><script>
			alert("Operação concluída com sucesso!")
			window.location = 'estoque_inventario.php?';
			</script><?
		}
		else{
			?><script>alert("Senha inválida!")</script><?
		
		}
	}
	else if (strlen($_POST['bt_cancelar']) > 0){
		?><script>alert("Operação cancelada.")</script><?
	}	
	return (1);
}

function zerar_inventario($referencia){
	//require("db_temp.php");

	$sql="UPDATE produto_estoque
	   SET pe_inventario=0
	   where pe_produto = '".$referencia."'
		and (pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X')";
		
	$rlt=db_query($sql);

	return (1);
}

echo $hd->foot();

?>
