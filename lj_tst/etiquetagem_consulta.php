<?php
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require("../_classes/_class_produto.php");
$pt = new produto;

require("db_temp.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?php
$cp = array();
array_push($cp,array('$H8','','',True,True,''));
array_push($cp,array('$S15','','Código EAN13',True,True,''));


echo '<CENTER><h1>Consultar de Códigos</h1></CENTER>';
echo '<center><TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($saved > 0)
	{
		$ok = $pt->consulta_codigos( $dd[1] );
	}

require("../foot.php");	?>