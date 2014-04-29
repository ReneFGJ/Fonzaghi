<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,True));
$messa = '** ATENÇÃO **<BR>Confirmar esta operação fará com que todos os produtos sejam marcados como invetariados.';
array_push($cp,array('$M8','',$messa,False,True));
array_push($cp,array('$S5','',$messa,False,True));
array_push($cp,array('$O : &S:SIM','','Confirmo a operação',False,False));

$tela = $form->editar($cp,'');
if ($form->saved > 0)
	{
			require('../_class/_class_produto.php');
			$pd = new produto;

			require("db_loja.php");		
			$pd->inventario_todos_produtos();
			echo '<h3>Operação executada com sucesso!</h3>';
	} else {
		echo $tela;
	}

/* Rodape */
echo $hd->foot();
?>