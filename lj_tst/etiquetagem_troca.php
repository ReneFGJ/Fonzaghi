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
array_push($cp,array('$Q produto:produto:select pe_produto || \' \' || pe_tam as produto, pe_tam, pe_produto from produto_estoque where pe_status = \'@\' group by pe_tam, pe_produto order by produto','','Peças',True,True,''));
array_push($cp,array('$S6','','Para (código)',True,True,''));
array_push($cp,array('$S3','','Tamanho',True,True,''));


echo '<CENTER><h1>Troca de Códigos</h1></CENTER>';
echo '<TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($saved > 0)
	{
		$cod_de = substr($dd[1],0,6);
		$cod_tam_de = trim(substr($dd[1],7,4));
		$cod_para = $dd[2];
		$cod_tam_para = $dd[3];
		
		$ok = $pt->troca_codigos( $cod_de,$cod_para, $cod_tam_de,$cod_tam_para );
		if ($ok == TRUE)
			{
				echo '<H1><font color="green">Código alterado com sucesso!</h1>';
			}
	}

require("../foot.php");	?>