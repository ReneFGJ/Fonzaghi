<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'_class_form.php');
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
$form = new form;
$cp = array();
array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$S6','','Ref. do produto',True,True));

$tela = $form->editar($cp,'');
if ($form->saved > 0)
	{
		if (strlen($dd[1])==6)
			{
				require("db_loja.php");
				require('../_class/_class_produto.php');
				$pd = new produto;
				if ($pd->inventario_desmarcar_produtos($dd[1])==1)
					{
						echo '<h1>Operação finalizada com sucesso!</h1>';
						echo '<BR><center>';
						echo '<BR><form action="inventario_resumo.php">';
						echo '<input type="submit" value="voltar" class="botao-geral">';
						echo '</form>';
						echo '<BR><BR><BR>';
					}
			} else {
				echo $tela;
			}
	} else {
		echo $tela;
	}

/* Rodape */
echo $hd->foot();
?>