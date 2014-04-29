<?php
$include = '../';
require("../db.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
require("../_classes/_class_etiqueta.php");
require("../_classes/_class_argox.php");
$et = new etiqueta;
$e1 = new etiqueta;
$e2 = new etiqueta;
$e3 = new etiqueta;
$e4 = new etiqueta;
$LANG = 'pt_BR';
require('db_temp.php');

$et = new etiqueta;
$argox = new argox;
/* Importar Logo
 * $argox->ppla_import(); 
 */

header('Content-type: application/force-download');
header('Content-Disposition: attachment; filename="etiqueta.ltp"');

echo $argox->ppla_start();

$qtd = $dd[0];
$num1 = $dd[1];
$num2 = $dd[2];  
$num3 = $dd[3];
$num4 = $dd[4];
$nome1 = $dd[5];
$nome2 = $dd[6];

$te = $dd[7]; // Tres etiquetas
$it = 0;
for ($rr=0;$rr < $qtd; $rr=$rr+($te))
    {
        $it++;
        $e1->ean13  = $num1.$num2.$num3.$num4;
        $e1->codigo = $num1;
        $e1->preco  = $num2.','.$num3;
        $e1->tam    = $num4;
        $e1->img    = '';
        $e1->nome   = $nome1;
        $e1->comissao   = $nome2;
        $e1->validade   = 'Fonzaghi Joias';
        

        $e2->ean13  = $num1.$num2.$num3.$num4;
        $e2->codigo = $num1;
        $e2->preco  = $num2.','.$num3;
        $e2->tam    = $num4;
        $e2->img    = '';
        $e2->nome   = $nome1;
        $e2->comissao   = $nome2;
        $e2->validade   = 'Fonzaghi Joias';	        

        $e3->ean13  = $num1.$num2.$num3.$num4;
        $e3->codigo = $num1;
        $e3->preco  = $num2.','.$num3;
        $e3->tam    = $num4;
        $e3->img    = '';
        $e3->nome   = $nome1;
        $e3->comissao   = $nome2;
        $e3->validade   = 'Fonzaghi Joias';
        
    	if($te==3)
    	{
    		echo $et->etiqueta_argox_3x1c($e1,$e2,$e3); 
		}
		if($te==1)
    	{
    		echo $et->etiqueta_argox_1x1j($e1,$e2,$e3); 
		}
             
    }       
echo $argox->ppla_end();


?>