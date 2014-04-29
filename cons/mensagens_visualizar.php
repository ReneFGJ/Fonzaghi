<?
 /**
  * Visualização de Mensagens
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.41
  * @package Classe
  * @subpackage UC00XX - Visualização */
session_start();
ob_start();
$include = '../';
require("../db.php");
require("../_class/_class_user.php");
$user = new user;
$user->security();
$cliente=$dd[99];
$tipo=$dd[98];
require($include."sisdoc_debug.php");
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require("../css/letras.css");
require("../db_fghi_206_cadastro.php");
global $messa;
require("../_class/_class_messages.php");
$messa = new message;
$messa->cliente = $cliente;
$messa->set_inf_rest($tipo);


//if (strlen($tipo) <=0 && strlen($cliente) <= 0){	exit;}

$sx = '<table border="0"  class="1_naoLinhaVertical" width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
		<TH class="1_th" width="10"> Nº </TH>
		<TH class="1_th" width="120">Dt. e Hrs.</TH>
		<TH class="1_th"  width="350">Texto</TH>
		<TH class="1_th">Tipo de mensagem</TH>';

$tx=$messa->tx;
$i=$messa->ttln; 

echo $sx .= $tx.'<tr><td colspan="5" class="rodapetotal">'.$i.' mensagens</td></tr></table>';

require("../foot.php");	
?>
