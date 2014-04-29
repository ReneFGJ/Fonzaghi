<?
 /**
  * Alterar data de acerto
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.41
  * @package Classe
  * @subpackage UC0029 - Alterar data de acerto
 */
session_start();
ob_start();
$include = '../';
require("../db.php");
/* Segurança */
require("../_class/_class_user.php");
$user = new user;
require("../_class/_class_user_perfil.php");
$perfil = new user_perfil;
$user->security();
$ss = $user;

require($include."sisdoc_debug.php");
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require("../css/letras.css");

$cliente=$dd[99];
$nivel=$dd[98];
require("../db_fghi_206_cadastro.php");
//mensagens
$msg=array();
array_push($msg, "0-Recepção, utilizar para informações direcionadas a recepção");
array_push($msg, "1-Informativa, e removido automaticamente após primeira leitura");
array_push($msg, "2-Informativa, removida somente manualmente");
array_push($msg, "3-Informativa, removida somente pelas coordenadoras ou supervisora");
array_push($msg, "4-Informativa, removida somente pela supervisora");
array_push($msg, "5-Informativa, removida somente pelo jurídico");
array_push($msg, "6-Restritiva, removida pelas coordenadoras");
array_push($msg, "7-Restritiva, removida pela supervisora");
array_push($msg, "8-Restritiva, removida pelo jurídico, com possibilidade de liberar");
array_push($msg, "9-Restritiva, Bloqueio total, removido pelo jurídico");

$sql = "SELECT id_msg, msg_cliente, msg_text, msg_hora, msg_lido, msg_data_lido, 
	       msg_hora_lido, msg_nivel, msg_data
	  	FROM mensagem
		  where msg_cliente = '".$cliente."'
		  	and msg_status <> 'X' 
		  order by msg_data desc, msg_hora";

echo '<CENTER><font class="lt5">Lista de mensagens restritivas</font></CENTER>';

$rlt = db_query($sql);

echo '<form action="user_actions.php?dd99='.$cliente.'&dd98='.$nivel.'" method="post">';

echo '<table border="0"  class="1_naoLinhaVertical" width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
echo '<TH class="1_th" width="10">Excluir</TH>';
echo '<TH class="1_th" width="10"> Nº </TH>';
echo '<TH class="1_th" width="120">Dt. e Hrs.</TH>';
echo '<TH class="1_th"  width="350">Texto</TH>';
echo '<TH class="1_th">Tipo de mensagem</TH>';

$i=0;
while ($line = db_read($rlt)){
	$i++;
	echo '<TR '.coluna().'>';
	
	echo '<TD class="1_td" width="10" align="center">';
	echo '<input type="checkbox" name="id_chk[]" value='.$line['id_msg'].'>';
	echo '</td>';
	
	echo '<TD class="1_td" width="10" align="center">';
	echo '['.$i.']';
	
	echo '<TD class="1_td" align="center">';
	echo stodbr($line['msg_data']).'   '.$line['msg_hora'];
	
	echo '<TD width="200" class="1_td">';
	echo $line['msg_text'];
	
	echo '<TD width="300" class="1_td" align="left">';
	echo $msg[$line['msg_nivel']];
	echo '</tr>';
}
echo '<tr>';
echo '<td colspan="5" class="rodapetotal">'.$i.' mensagens</td>';
echo '</tr>';

if (($perfil->valid('#ADM#COB#COJ#COM#COO#COS#GEG#GEC#DIR#MST#REC'))) 
    {
    echo '<tr>';
    echo '<td align="center" colspan="5">';
    echo '<input type="submit" name="do_action" value=" E x c l u i r "/>';
    echo '</td>';
    echo '</tr>';
    }

echo '</table>';

echo '</form>';

require("../foot.php");	
?>

