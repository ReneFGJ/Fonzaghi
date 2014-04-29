<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require("../_classes/_class_comissao.php");
$com = new comissao;

require($include."sisdoc_colunas.php");
require($include."sisdoc_data.php");

require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");

require("db_temp.php");
echo 
'
<img src="img/logo_empresa.png" alt="" border="0" align="right">
<h1>Alterar Comissão de Mostruário</h1>
';
$tabela = "";
$cp = array();
array_push($cp,array('$H4','','',False,True,''));
array_push($cp,array('$A','','Kits com acerto de ',False,True,''));
array_push($cp,array('$I8','','Mostruario Nº',True,True,''));
array_push($cp,array('$S7','','Cliente',True,True,''));
array_push($cp,array('$O 30:30&40:40&50:50','','Comissão',True,True,''));

/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<TABLE width="'.$tab_max.'" align="center">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';
	
if ($saved < 1) {  exit; }

$most = strzero($dd[2],5);
$clie = $dd[3];
$comi = $dd[4];
$com->troca_comissao($most,$clie,$comi);
//troca_comissao($most,$clie,$comi)
?>



?>