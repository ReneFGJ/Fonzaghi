<?php
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));

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
array_push($cp,array('$T20:8','','Peças',True,True,''));
array_push($cp,array('$S7','','Justificativa ',True,True,''));
array_push($cp,array('$N8','','Valor do desconto',True,True,''));
array_push($cp,array('$O : &P:Desconto por preço&D:Desconto percentual','','Tipo do desconto',True,True,''));

echo '<CENTER><font class="lt5">Relatório de Rastreio de Peças</font></CENTER>';
echo '<TABLE align="center" width="'.$tab_max.'">';
echo '<TR><TD colspan=2>Digite "FIM" no final do código das peças';
echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($saved > 0)
	{
	if (strpos($dd[1],'FIM') > 0)
	{
		$pra = splitx(chr(13),$dd[1].chr(13).' '.chr(13));
		echo '<TABLE align="center" width="'.$tab_max.'">';
		for ($ra=0;$ra < count($pra);$ra++)
			{
					$ean = $pra[$ra];
				$ok = $pt->produto_desconto($ean,$dd[3],$dd[4],$dd[2]);
			}	
		echo '</TABLE>';
	} else {
		echo 'FIM não localizado';
	}	
	}

echo $hd->foot();	?>