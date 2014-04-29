<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('fornecimentos.php','Tabela de produtos consignados'));
array_push($breadcrumbs, array('tabela_fornecidos.php','Visualizar item'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");

if (count($_POST) > 0){
	if ($_POST['cmbData'] <> 'Todas'){$data=substr($_POST['cmbData'],6,4).substr($_POST['cmbData'],3,2).substr($_POST['cmbData'],0,2);}
	else{$data=$_POST['cmbData'];}
	$listarDevolvidos=$_POST['chk_devolvido'];
}
else{
	$data='';
	$listarDevolvidos=0;
}
$tab_max='98%';
echo '<center>';
echo '<form action="tabela_fornecidos2.php?dd0='.$dd[0].'&dd1='.$data.'&dd2='.$listarDevolvidos.'" method="post">';
$sql="SELECT pe_lastupdate FROM produto_estoque where pe_cliente ='".$dd[0]."' and pe_status = 'F' group by pe_lastupdate order by pe_lastupdate desc";
$rtl=db_query($sql);

echo '<font class="lt1" >Filtrar: </font> ';
echo '<select name="cmbData" size="1">';
echo '<option value="Todas"></option>';
while($line=db_read($rtl)){
	$data=$line['pe_lastupdate'];
	$data2=substr($data,6,2).'/'.substr($data,4,2).'/'.substr($data,0,4);
	echo '<option value="'.$data2.'">'.$data2.'</option>';
}	
echo '</select>';
echo '<BR><input type="checkbox" name="chk_devolvido" value="1"><font class="lt1" >Listar produtos devolvidos</FONT>';

echo '<BR><input class="botao-geral" type="submit" name="bt_aplicar" value="Aplicar">';

echo '</form>';

require("tabela_fornecidos_a2.php");
echo $hd->foot();	

?>