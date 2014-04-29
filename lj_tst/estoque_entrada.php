<?
$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');

$_SESSION['pedido'] = $dd[0];

echo '<H1>Entrada de Mercadodia no Estoque</h1>';
echo '<h3>Por pedido de compra</h3>';

require('../_class/_class_pedido.php');
$ped = new pedido;
require($include.'_class_form.php');
$form = new form;
$form->hidden_error = 1;
$form->hidden_form_error = 1;

$op_ped=$ped->lista_pedidos_option($dd[1]);
$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$Q e_nome:id_e:select * from empresa where e_ativo=1 order by e_nome','','Filtrar por empresa',False,True));
array_push($cp,array('$O '.$op_ped,'ped_nrped','Selecione o Pedido',True,True));
array_push($cp,array('$B8','','Filtra / Seleciona >>',False,True));


$tela = $form->editar($cp,'');
if ($form->saved > 0)
    {
		redirecina('estoque_entrada_2.php?dd2='.$dd[2].'&dd90='.checkpost($dd[2]));
    } else {
        echo $tela;
    }
  

/* Rodape */
echo $hd->foot();
?>