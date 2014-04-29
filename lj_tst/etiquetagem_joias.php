<?php
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
require("../_classes/_class_etiqueta.php");
require("../_classes/_class_argox.php");
require($include.'_class_form.php');
$form = new form;
require('db_temp.php');

$et = new etiqueta;
$e1 = new etiqueta;
$e2 = new etiqueta;
$e3 = new etiqueta;
$e4 = new etiqueta;
$LANG = 'pt_BR';

			
$et = new etiqueta;
$argox = new argox;
$cp = array();
$op = '0-VIP-Ping.folheado#OURO:0-VIP-Ping.folheado#OURO&';
$op .= '1-VIP-Corr.folheado#OURO BRANCO:1-VIP-Corr.folheado#OURO BRANCO&';
$op .= '2-Pulseira folheado#OURO:2-Pulseira folheado#OURO&';
$op .= '2-Pulseira folheado#OURO BRANCO:2-Pulseira folheado#OURO BRANCO&';
$op .= '2-VIP-Puls. folheado#OURO:2-VIP-Puls. folheado#OURO&';
$op .= '2-VIP-Puls. folheado#OURO BRANCO:2-VIP-Puls. folheado#OURO BRANCO&';
$op .= '3-Anel folheado#OURO:3-Anel folheado#OURO&';
$op .= '3-Anel folheado#OURO BRANCO:3-Anel folheado#OURO BRANCO&';
$op .= '3-VIP Anel folh.#OURO:3-VIP Anel folh.#OURO&';
$op .= '3-VIP Anel folh.#OURO BRANCO:3-VIP Anel folh.#OURO BRANCO&';
$op .= '4-Brinco#fol.OURO:4-Brinco#fol.OURO&';
$op .= '4-Brinco fol.#O.BRANCO:4-Brinco fol.#O.BRANCO&';
$op .= '4-VIP-Brinco#fol.OURO:4-VIP-Brinco#fol.OURO&';
$op .= '4-VIP-Brinco#fol.OURO BRANCO:4-VIP-Brinco#fol.OURO BRANCO&';
$op .= '4-Cj. folh.#OURO:4-Cj. folh.#OURO&';
$op .= '4-VIP-Cj. folh.#OURO:4-VIP-Cj. folh.#OURO&';
$op .= '5-Pingente # PRATA LEVE:5-Pingente # PRATA LEVE&';
$op .= '5-Pingente # PRATA PURA:5-Pingente # PRATA PURA&';
$op .= '5-VIP-Ping. # PRATA LEVE:5-VIP-Ping. # PRATA LEVE&';
$op .= '5-VIP-Pingente# PRATA PURA:5-VIP-Pingente# PRATA PURA&';
$op .= '6-Corrente # PRATA LEVE:6-Corrente # PRATA LEVE&';
$op .= '6-Corrente # PRATA PURA:6-Corrente # PRATA PURA&';
$op .= '6-VIP-Corr. # PRATA LEVE:6-VIP-Corr. # PRATA LEVE&';
$op .= '6-VIP-Corrente#  PRATA PURA:6-VIP-Corrente#  PRATA PURA&';
$op .= '7-Pulseira # PRATA LEVE:7-Pulseira # PRATA LEVE&';
$op .= '7-Pulseira # PRATA PURA:7-Pulseira # PRATA PURA&';
$op .= '7-VIP-Puls.# PRATA LEVE:7-VIP-Puls.# PRATA LEVE&';
$op .= '7-VIP-Pulseira # PRATA PURA:7-VIP-Pulseira # PRATA PURA&';
$op .= '8-Anel# PRATA LEVE:8-Anel# PRATA LEVE&';
$op .= '8-Anel # PRATA PURA:8-Anel # PRATA PURA&';
$op .= '8-VIP-Anel# PRATA LEVE:8-VIP-Anel# PRATA LEVE&';
$op .= '8-VIP-Anel # PRATA PURA:8-VIP-Anel # PRATA PURA&';
$op .= '9-Brinco# prata leve:9-Brinco# prata leve&';
$op .= '9-Brinco# prata pura:9-Brinco# prata pura&';
$op .= '9-VIP-Brinco # prata leve:9-VIP-Brinco # prata leve&';
$op .= '9-VIP-Brinco # prata pura:9-VIP-Brinco # prata pura&';
$op .= '0#-Conjutos Folheados:0#-Conjutos Folheados&';
$op .= '1-Peca Promocional:1-Peca Promocional&';

array_push($cp,array('$O '.$op,'','Quantidade',False,True,''));
array_push($cp,array('$S6','','Pontos',false,True));
array_push($cp,array('$O 5:05&15:15&30:30&50:50&100:100&250:250&500:500','','Quantidade',False,True,''));
array_push($cp,array('$O 1:Lacre&3:Adesiva 3x1 ','','Tipo',False,True,''));

$menu  =  '<h1>Impressão de etiquetas Joias</h1>';

$sx  = $dd[0];
$sx2 = $dd[1];

$pos  = strripos($sx,'#');
$pos2  = strripos($sx2,',');
$qtd = $dd[2];
$num1 = round(substr($sx,0,1));
$num2 = round(trim(substr($sx2,0,$pos2)));  
$num3 = round(trim(substr($sx2,$pos2+1,4)));
$num4 = $num2+$num3;
$nome1 = substr($sx,2,($pos-2));
$nome2 = trim(substr($sx,($pos+1),100));

$menu .= $form->editar($cp,'');

if($form->saved>0)
{
	redirecina('etiqueta_tst_10.php?dd0='.$qtd.'&dd1='.$num1.'&dd2='.$num2.'&dd3='.$num3.'&dd4='.$num4.'&dd5='.$nome1.'&dd6='.$nome2.'&dd7='.$dd[3]);
	
}else{
	echo $menu;
}

?>