<?
$include = '../';
$nocab=1;
require("../cab_novo.php");
require($include.'sisdoc_windows.php');
require($include."sisdoc_email.php");

?>
<style>
body {
	BACKGROUND-POSITION: center 50%; 
	FONT-SIZE: 9px; 
	BACKGROUND-IMAGE: url(/img/bg.gif); 
	MARGIN: 100px; 
	width: 400px; 
	height: 100px; 
	COLOR: ##dfefff; 
	font-family: Verdana, Arial, Helvetica, sans-serif; 
	font-size: 10pt; 
	font-weight: normal; 
	color: #000000; 
}
</style>
<CENTER>
<?

/*
echo '<br>post: ';
print_r($_POST);

echo '<br>GET: ';
print_r($_GET);
*/

$clie=$dd[0];


if (count($_POST) > 0){
	if ($_POST['cmbData'] <> 'Todas'){$data=substr($_POST['cmbData'],6,4).substr($_POST['cmbData'],3,2).substr($_POST['cmbData'],0,2);}
	else{$data=$_POST['cmbData'];}
	$listarDevolvidos=$_POST['chk_devolvido'];
}
else{
	$data=$dd[1];
	$listarDevolvidos=$dd[2];
}

global $nome;

emailcab($http_local);
echo emailcab('tabela_fornecidos_popup2.php?dd0='.$clie.'&dd1='.$data.'&dd2='.$listarDevolvidos);
require("tabela_fornecidos_a2.php");

echo '<center>';
echo '<font class="lt1">';
echo '___________________________________________<br>';
echo 'Assinatura: '.$nome;
echo '</font>';
echo '</center>';

echo $hd->foot();	
?>
</center>
