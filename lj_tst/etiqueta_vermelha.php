<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
?>
<img src="img/logo_empresa.png" alt="" border="0" align="right">
<h1>Menu Principal</h1>
<h2>Loja - <?=$nloja_nome;?></h2>
<?
require('../_class/_class_produto.php');
$prod = new produto;

require($include.'_class_form.php');
$form = new form;

$cp = array();
$op .= '&100:SEM RETORNO';
for ($i=99; $i >=0 ; $i--) { 
	$op .= '&'.$i.':'.(100-$i).'% OFF';
}

array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$O : '.$op,'','Desconto',True,False));
array_push($cp,array('$T20:15','','Etiquetas:',True,False));
array_push($cp,array('$B8','','Aplicar >>',False,False));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$pc = troca($dd[2],chr(13),';');
		$pcs = splitx(';',$pc);
		for ($r=0;$r < count($pcs);$r++)
			{
				$ean = $pcs[$r];
				$desconto = $dd[1];
				echo '<BR>'.$pcs[$r];
				echo '=>';
				echo $prod->etiqueta_vermelha($ean,$desconto);
				$tt++;
			}
			echo '<BR>Total de '.$tt.' etiquetas.';
	} else {
		echo $tela;
	}

echo $hd->foot();
?>