<?
ob_start();
$include = '../';
require("../db.php");

require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require($include.'sisdoc_security.php');

security();

if (strlen($_POST['bt_enviar']) > 0){
	require("db_temp.php");
	$sql="UPDATE inventario SET i_enviado='S' WHERE i_enviado='N';";
	$rlt=db_query($sql);
}

require("../db_fghi2.php");
acao();

$sql="SELECT lat_descricao, lat_codigo
		  FROM lista_atividades_tipo
		  order by lat_descricao";

$rlt=db_query($sql);

$tipo=array();
//array_push($tipo, array('',''));
while($line=db_read($rlt)){
	array_push($tipo, array($line['lat_codigo'], $line['lat_descricao']));
}

echo '<form action="msg_enviar.php?log='.$user_log.'" method="post">';
echo '<table class="lt1" border="0" class="1_naoLinhaVertical" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
echo '<tr><td height="40" valign="top" colspan="2" align="center" class="lt5">Envio de mensagem</td></tr>';
echo '<tr><th align="right" class="legenda">Data</th>';
echo '<td bgcolor="#F0F0F0" class="1_td">'.date('d/m/Y').'</td></tr>';
echo '<tr><th align="right" class="legenda">Hora</th>';
echo '<td bgcolor="#F0F0F0" class="1_td">'.date('H:i').'</td></tr>';
echo '<tr><th align="right" class="legenda">De</th>';
echo '<td bgcolor="#F0F0F0" class="1_td">'.$user_log.'</td></tr>';
echo '<tr><th align="right" class="legenda">Para<br><i>(Log ou Nível)</i></th>';
echo '<td bgcolor="#F0F0F0" class="1_td"><input type="text" name="dd0" size="10" maxlength="20" value="MARCELO"></td></tr>';
echo '<tr><th align="right" class="legenda">Tipo de mensagem</th>';
//--------------------------------------------------
echo '<td bgcolor="#F0F0F0" class="1_td">';
echo '<select name="cmb_tipo" size="1">';
for($i=0;$i<count($tipo);$i++){
	echo '<option value="'.$tipo[$i][0].'">'.$tipo[$i][1].'</option>';
}
echo '</select>';
echo '</td></tr>';
//--------------------------------------------------
echo '<tr><th align="right" class="legenda">Título</th>';
echo '<td bgcolor="#F0F0F0" class="1_td"><input type="text" name="dd1" size="37" maxlength="100"></td></tr>';
echo '<tr><th align="right" class="legenda">Descrição</th>';
echo '<td bgcolor="#F0F0F0" class="1_td"><textarea class="MeuTextArea" cols="28" rows="5" name="dd2"></textarea></td></tr>';

echo '<tr>';
echo '<td height="40" align="center" colspan="2"><input type="submit" name="bt_enviar" value="Enviar">&nbsp;
		<input type="submit" name="bt_cancelar" value="Cancelar"></td>';
echo '</tr>';

echo '</table>';
echo '</form>';

function acao(){
	global $user_log;
	if (strlen($_POST['bt_enviar']) > 0){

		$sql="INSERT INTO lista_atividades(la_data, la_hora, la_de, ";
		if (soNumero($_POST['dd0'])){$sql.= "la_perfil, ";	}
		else{$sql.= "la_para, ";} //log
		$sql.= "la_cod_atendimento, la_titulo, la_descricao, la_status, la_http) 
				VALUES (".date('Ymd').", '".date('H:i')."', '".$user_log."', ";
		if (soNumero($_POST['dd0'])){$sql.= "".troca($_POST['dd0'],"'","´").", ";}
		else{$sql.="'".strtoupper($_POST['dd0'])."', ";}

		$la_http='';
		if (trim($_POST['cmb_tipo'])=='INV'){$la_http='sensual/estoque_inventario_item3.php';}
		
		$sql.= "'".$_POST['cmb_tipo']."','".troca($_POST['dd1'],"'","´")."','".troca($_POST['dd2'],"'","´")."' ,'A', '".$la_http."');";
		$rlt=db_query($sql);
		
		//echo $sql;
		?>
		<script>
		close();
		</script>
		<?
	}
	
	if (strlen($_POST['bt_cancelar']) > 0){
		?>
		<script>
		close();
		</script>
		<?
	}
}
?>
