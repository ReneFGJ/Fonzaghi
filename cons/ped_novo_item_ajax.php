<?php
$include = '../';
require("../db.php");

require("../_class/_class_pedidos.php");
$peds = new pedidos;
$verb = trim($dd[1]);
$peds->pedido_nr = $dd[0];

switch ($verb)
{
	/* abre form item novo */
	case 'item_novo':
		echo $peds->item_novo_form();
		break;	
	/* abre form item novo */
	case 'item_novo_grava':
		echo $peds->item_novo_grava($dd);
		break;
	/* Deletar item */
	case 'item_del':
		echo $peds->item_deletar($peds->pedido_nr);
		break;	
	/* Mostra os items */
	case 'mostra_itens':
		echo $peds->tabela_de_items();
		break;		
}
?>
