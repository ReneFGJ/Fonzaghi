<?

$include = '../';
$nocab = 1;
require('../cab_novo.php');
$user->le($_SESSION['nw_cracha']); 
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');

require('../_class/_class_avaliacao_competencia.php');
$aval = new avaliacao;

$verb = $dd[0];

switch ($verb){
	case 'save':
		$funcionario = $dd[1];
		$observacao = $dd[2];
		$aval->historico_save($funcionario,$observacao);
		echo '<script>window.opener.location.reload();</script>';
	break;
	case 'cancel':
		$id = $dd[1];
		$aval->historico_cancel($id);
		echo '<script>window.opener.location.reload();</script>';
	break;
	default:
	break;
}

?>