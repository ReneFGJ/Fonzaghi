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
echo '<h1>Invent�rio</h1>';
array_push($menu,array('Invent�rio F�sico','Posi��o consolidada - Resumo','inventario_resumo.php'));
array_push($menu,array('Invent�rio F�sico','__Raz�o de estoque - pe�as ','inventario_razao.php'));

array_push($menu,array('Invent�rio F�sico - Completo','Inventariar',''));

array_push($menu,array('Invent�rio F�sico - Parcial','Inventariar',''));
array_push($menu,array('Invent�rio F�sico - Parcial','__Iniciar processo (zerar matrix)','inventario_parcial_zerar.php'));
array_push($menu,array('Invent�rio F�sico - Parcial','__Definir itens a inventariar','inventario_parcial_itens.php')); 
array_push($menu,array('Invent�rio F�sico - Parcial','Baixar produtos',''));
array_push($menu,array('Invent�rio F�sico - Parcial','__Baixar produtos n�o localizados','inventario_down.php')); 

$tela = menus($menu,"3");

/* Rodape */
echo $hd->foot();
?>