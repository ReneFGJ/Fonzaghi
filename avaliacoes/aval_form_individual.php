<?

$include = '../';
require("../cab_novo.php");
//* conteudo */
require($include."sisdoc_debug.php");
require($include."sisdoc_data.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");
require($include."sisdoc_colunas.php");
require("../_class/_class_avaliacao_competencia.php");
require("../db_cadastro.php");

require("../db_drh.php");

		
$aval = new avaliacao;
echo $aval->avaliacao_individual($funcionario);
			
		


?>

