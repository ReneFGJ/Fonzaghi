	<?
$include = '../';
require($include."cab_novo.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_data.php");

require($include.'_class_form.php');
$form = new form;

require("../_class/_class_consultora.php");

require("../_class/_class_consignacoes.php");
$cons = new consignacoes;

require("db_temp.php");
//$loja = 'S';
if ($perfil->valid('#GEG#GER#ADM#SSS#CCC'))
{
	$cp = $cons->cp_troca_kit();
	$tabela = "";
	$tela = $form->editar($cp,'');

	if ($form->saved > 0)
		{
			require("db_temp.php");
			$cons->transfere_pecas($dd[2],$dd[4],$dd[5]);
			
			require("../db_2via.php");
			$cons->historico($dd[2],$dd[4],$dd[5]);
			echo 'Transferência realizada com sucesso !';
			echo 'SAVED';
		} else {
			echo $tela;
		}

}
?>