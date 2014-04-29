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

$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$S7','','Cliente',True,True));


echo '<table>';
editar();
echo '</table>';


if ($saved > 0)
	{
		$funcionario = $dd[1];
		$func = new avaliacao;
		echo $func->dados_func($funcionario);
		

	}
?>