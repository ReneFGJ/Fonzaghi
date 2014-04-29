<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_menus.php');

$menu = array();

/////////////////////////////////////////////////// MANAGERS
echo '<h1>Inventário</h1>';
array_push($menu,array('Inventário Físico','Posição consolidada - Resumo','inventario_resumo.php'));
array_push($menu,array('Inventário Físico','__Razão de estoque - peças ','inventario_razao.php'));

array_push($menu,array('Inventário Físico - Completo','Inventariar',''));

array_push($menu,array('Inventário Físico - Parcial','Inventariar',''));
array_push($menu,array('Inventário Físico - Parcial','__Iniciar processo (zerar matrix)','inventario_parcial_zerar.php'));
array_push($menu,array('Inventário Físico - Parcial','__Definir itens a inventariar','inventario_parcial_itens.php')); 
array_push($menu,array('Inventário Físico - Parcial','Baixar produtos',''));
array_push($menu,array('Inventário Físico - Parcial','__Baixar produtos não localizados','inventario_down.php')); 

$tela = menus($menu,"3");

/* Rodape */
echo $hd->foot();
?>