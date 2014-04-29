<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_item4.php','Lista aprovada de inventários'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

require($include."biblioteca.php");
require("estoque_funcoes.php");
require("db_temp.php");

if ($user_nivel < 0){
	echo '<br><br><CENTER><font class="lt3"><b>Acesso negado.</b></font></CENTER>';
	echo $hd->foot();	
	exit;
}

//print_r($_POST);
//echo '<br> count: '.count($_POST['txt_codigo']);

$codigo='';
$data=0;
for($i=0; $i<count($_POST['txt_codigo']); $i++){
	//echo '<br> $i  '.$i.$_POST['bt_baixar'][$i];
	
	if (strlen($_POST['bt_baixar'][$i]) > 0){
		$codigo=$_POST['txt_codigo'][$i];
		break;
	}
}

if (strlen($codigo) == 0){
	echo '<br>Referência não encontrada.';
	echo $hd->foot();	
	exit;
}

$sql="select p_descricao from produto
	where p_codigo='".$codigo."'";
	
$rlt=db_query($sql);
$line=db_read($rlt);

echo '<form action="estoque_inventario_item4_3.php" method="post">';
echo '<table border="0" width="300">';
echo '<TR>';
echo '<TD><fieldset><legend><font size="2" color="#c0c0c0">Baixa/Reincorporação</font></legend>';
echo '<table border="0" width="100%" class="lt1">';
echo '<TR>';
echo '<TD colspan="2" height="50" align="center" class="lt2"><b>'.$codigo.' '.$line['p_descricao'].'</b></TD>';
echo '</TR>';

echo '<TR>';
echo '<TD height="50" align="center" class="lt1">Senha <input type="text" name="txt_senha"></TD>';
echo '</TR>';

echo '<TR>';
echo '<TD align="center" height="50" colspan="2" class="lt0"><input type="submit" name="bt_confirmar" value="Confirmar">&nbsp;<input type="submit" name="bt_cancelar" value="Cancelar"></TD>';
echo '</TR>';

echo '<td align="center"><input type="hidden" name="txt_data" value="'.$data.'"></td>';
echo '<td align="center"><input type="hidden" name="txt_codigo" value="'.$codigo.'"></td>';

echo '</table>';
echo '</fieldset>';
echo '</TD>';
echo '</TR>';
echo '</table>';
echo '</form>';

echo $hd->foot();
?>
