<?
$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');



$_SESSION['pedido'] = $dd[0];

require('../_class/_class_pedido.php');
$ped = new pedido;
require($include.'_class_form.php');
$form = new form;
$op_ped=$ped->lista_pedidos_option(16);
$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$M','','<center><h2>Entrada de mercadoria no estoque</h2></center>',False,True));
array_push($cp,array('$O '.$op_ped,'aagc_loja','Loja',false,True));
//array_push($cp,array('$Q','','Pedido:',False,True));
array_push($cp,array('$S14','','Quantidade:',False,True));
array_push($cp,array('$D8','','Vencimento',True,True));
array_push($cp,array('$S14','','Tamanho:',False,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
    {
        $rs = $ped->le($dd[2]);
        if ($rs == 0)
            {
                echo '<center>';
                echo '<div class="erro">';
                echo 'Pedido não localizado!';
                echo '</div>';
                
                echo '<form action="'.page().'">';
                echo '<input type="submit" value="voltar">';
                echo '</form>';
            } else {
                $tela = $ped->lista_produto($dd[2],'estoque_entrada_item.php');
                echo $tela;
            }
    } else {
        echo $tela;
    }

/* Rodape */
echo $hd->foot();
?>