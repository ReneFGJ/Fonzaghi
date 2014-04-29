<?php
$include = '../';
require('../cab_novo.php');   
require("../_class/_class_cargos.php");
$cargo = new cargos;

require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
require("../db_fghi.php");
$cargo->le($dd[1]);


$car_nome =  $cargo->car_nome;
$car_descricao =  $cargo->car_descricao;
$loja_nome= $aval->nome_loja($dd[0]);
if((strlen($dd[0])==0) or(strlen($dd[1])==0))
{
	redirecina('aval_peso_ed_lista.php');
}else{
echo '<center><table width="80%">
		<tr><td width="100%" class="botao-geral" height="20" colspan="2" align="center">'.$loja_nome.'</td></tr>
		<tr><td width="40%" class="botao-geral" height="20">Cargo :</td><td  width="60%" rowspan="20" align="right">'.$aval->combo_competencia().'</td></tr>
		<tr><td width="40%" class="tabela01" height="20">'.$car_nome.'</td></tr>
		<tr><td width="40%" class="botao-geral" height="20">Descrição :</td></tr>
		<tr><td width="40%" class="tabela01" valign="top">'.$car_descricao.'</td></tr></table>
';

		
}


echo $hd->foot();

?>