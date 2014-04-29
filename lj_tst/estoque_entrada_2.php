<?
$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');

echo '<H1>Entrada de Mercadodia no Estoque</h1>';
echo '<h3>Pedido '.$dd[2].'</h3>';

require('../_class/_class_pedido.php');
$ped = new pedido;

$rs = $ped->le($dd[2]);

echo $ped->mostra_dados_fornecedor();

if ($rs == 0)
    {
        echo '<center>';
        echo '<div class="erro">';
        echo 'Pedido não localizado!';
        echo $dd[2]."<<<<"; 
        echo '</div>';
        echo '<form action="'.page().'">';
        echo '<input type="submit" value="voltar">';
        echo '</form>';
    } else {
    	$tela = $ped->lista_produto($dd[2],'estoque_entrada_item.php');
        echo $tela;
    }
  

/* Rodape */
echo $hd->foot();
?>