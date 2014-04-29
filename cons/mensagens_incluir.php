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
require("../_class/_class_user.php");
$user = new user;
$user->security();


require($include."sisdoc_debug.php");
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require("../css/letras.css");

$cliente=$dd[99];
$nome=$dd[98];


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

$cp = array();
array_push($cp,array('$T65:2','','Texto',True,True,''));
array_push($cp,array('$O 0:'.$msg[0].'&1:'.$msg[1].'&2:'.$msg[2].'&3:'.$msg[3].'&4:'.$msg[4].'&5:'.$msg[5].'&6:'.$msg[6].'&7:'.$msg[7].'&8:'.$msg[8].'&9:'.$msg[9].'','','Tipo de mensagem',True,True,''));
	
echo '<CENTER><font class="lt5">Cadastro de Mensagem</font></CENTER>';
echo '<TABLE border="0" align="center" width="600">';
	
echo '<tr class="lt1">';
echo '<td align="center">Cliente</td> ';
echo '<td align="center">Data</td>';
echo '</tr>';
	
echo '<tr class="lt2">';
echo '<td align="center" bgcolor="#F0F0F0">'.$cliente.' - '.$nome.'</td> ';
echo '<td  align="center" bgcolor="#F0F0F0">'.stodbr(date('Ymd')).'</td>';
echo '</tr>';
	
echo '<TR><TD colspan="2">';
editar();
echo '</TD></TR>';	
	
echo '</TABLE>';
require("../db_fghi_206_cadastro.php");

if (strlen($dd[0]) > 0 || strlen($dd[1]) > 0){
	$sql="INSERT INTO mensagem(
            	msg_cliente, msg_text, msg_data, msg_hora, msg_lido, 
	            msg_data_lido, msg_hora_lido, msg_nivel)
    		VALUES ('".$cliente."', '".$dd[0]."', ".date('Ymd').", '".date('H:i')."', 0, 
        	    0, '-', '".$dd[1]."')";
	
	$rlt=db_query($sql);
	require("../close.php");
}

require("../foot.php");	

?>
