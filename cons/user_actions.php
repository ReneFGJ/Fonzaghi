<?
 /**
  * 
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.41
  * @package Classe
  * @subpackage UC00XX - 
 */
session_start();
ob_start();
$include = '../';
require("../db.php");
require("../_class/_class_user.php");
$user = new user;
$user->security();
$cliente=$dd[99];
$nivel=$dd[99];
require($include."sisdoc_debug.php");
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require("../css/letras.css");
require("../db_fghi_206_cadastro.php");

require($include."sisdoc_windows.php");



//echo "cliente: ".$cliente;

if ($nivel >= 9){
	if (isset($_POST['id_chk'])) { 
		$sql = "UPDATE mensagem SET msg_status='X' WHERE "; 
		foreach ( $_POST['id_chk'] as $id ) { 
			$sql .= "(id_msg = {$id}) OR "; 
		} 
		$sql=substr($sql, 0, -4); 
		
		//echo $sql;
		$rlt=db_query($sql);
		require("../close.php");	
	}
	else{
		echo msg_erro("Não há registro selecionado.");
		echo '<center><font class="lt2"><A HREF="../cadastro/mensagens_excluir.php?dd99='.$cliente.'&dd98='.$nivel.'">Voltar</A></font></center>';		
	}
	
}
else{
	echo msg_erro("ERRO: Você não tem permissão para executar esta ação!");
}
require("../foot.php");	
?>