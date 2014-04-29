<?
$include = '../';
require("../db.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
?>
<style>
	body 
		{ 
			margin: 0px 0px 0px 0px;
			font-family:Verdana, Geneva, Arial, Helvetica, sans-serif;
			font-size: 8px;
			color: #404040; 
		}
</style>
<head>
	<title>:: Sistema de Caixa ::</title>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
	<link rel="STYLESHEET" type="text/css" href="../letras.css">
</head>
<body onload="printpage()">
<?

require("../_classes/_class_etiqueta.php");
$et = new etiqueta;
$e1 = new etiqueta;
$e2 = new etiqueta;
$e3 = new etiqueta;

require('db_temp.php');

$eti = $et->etiquetas_imprimir_lista();


$et = new etiqueta;
$te = 3; // Tres etiquetas
for ($rr=0;$rr < count($eti);$rr=$rr+($te))
	{
		if ($rr > 0) { echo $et->np(); }
		$e1->ean13  = $eti[$rr+0][0];
		$e1->codigo = $eti[$rr+0][1];
		$e1->preco  = $eti[$rr+0][3];
		$e1->tam    = $eti[$rr+0][5];
		$e1->img    = 'img_loja/icone_loja_A.jpg';
		$e1->nome   = $eti[$rr+0][6];
		$e1->comissao   = $eti[$rr+0][7];

		$e2->ean13  = $eti[$rr+1][0];
		$e2->codigo = $eti[$rr+1][1];
		$e2->preco  = $eti[$rr+1][3];
		$e2->tam    = $eti[$rr+1][5];
		$e2->img    = 'img_loja/icone_loja_A.jpg';
		$e2->nome   = $eti[$rr+1][6];
		$e2->comissao   = $eti[$rr+1][7];

		$e3->ean13  = $eti[$rr+2][0];
		$e3->codigo = $eti[$rr+2][1];
		$e3->preco  = $eti[$rr+2][3];
		$e3->tam    = $eti[$rr+2][5];
		$e3->img    = 'img_loja/icone_loja_A.jpg';
		$e3->nome   = $eti[$rr+2][6];
		$e3->comissao   = $eti[$rr+2][7];
	
		echo $et->etiqueta_linha_3x1($e1,$e2,$e3);		
	}

?>
</body>
<script language="Javascript1.2">
  function printpage() {
  window.print();
  }
</script>

