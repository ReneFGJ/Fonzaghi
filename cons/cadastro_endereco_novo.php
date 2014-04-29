<?
/**
  * Alterar endereço
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version 
  * @package 
  * @subpackage UC00XX - Alterar endereço
 */
$include = '../';
$nocab=1;
require($include."cab_novo.php");
require("../_class/_class_consultora_endereco.php");
require("../_class/_class_cep.php");
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require("../_class/_class_form.php");
$form = new form;
$ce = new cep;
$ct = new consultora_endereco;
require('../db_cadastro.php');
$cp = $ct->cp();
$tabela = $ct->tabela;

/* Validar o CEP informado */
$dd[0] = $ct ->busca_id_endereco($dd[1]);
if ((strlen($dd[2]) > 5) or strlen($dd[0])>0)
	{
		$dd[2] = $ct->ce_cep;	
		require('../db_fghi_206_cep.php');
		if ($ce->consulta_cep($dd[2])==1)
			{
				if (strlen($dd[10]) == 0)
					{
						
						//$dd[1] = ;  
				 		$dd[5] = $user->user_log;
				 		$dd[6] = $ce->cidade;
				 		$dd[7] = $ce->uf;
				 		$dd[9] = $ce->bairro;
				 		$dd[10] = $ce->endereco;
				 		$dd[14] = $ce->cidade_cod;
					} 
			} 
			else { $dd[2] == 0; }
	} else {
		$dd[2] = '';
	}

require('../db_cadastro.php');
if (strlen($dd[2]) == 0)
	{
		$cp = $ct->cp_cep();
				
	} else {
		$cp = $ct->cp();
				
	}


echo '<table width="100%" align="right"><TR><TD>';
echo $form->editar($cp, $tabela); 
echo '</TD></TR></table>';
		

if ($form->saved > 0)
	{
		require("../close.php");
	}

?>

