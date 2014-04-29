<?
$include = '../';
require("../db.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
require("../_classes/_class_etiqueta.php");
require("../_classes/_class_argox.php");
$et = new etiqueta;
$e1 = new etiqueta;

$LANG = 'pt_BR';
require('db_temp.php');

$eti = $et->etiquetas_imprimir_lista($dd[1]);

//header('Content-type: application/force-download');
//header('Content-Disposition: attachment; filename="etiqueta.ltp"');

$et = new etiqueta;
$argox = new argox;
/* Importar Logo
 * $argox->ppla_import(); 
 */

header('Content-type: application/force-download');
header('Content-Disposition: attachment; filename="etiqueta.ltp"');
echo $argox->ppla_start();

$te = 1; // uma etiqueta
$it = 0;
for ($rr=0;$rr < count($eti);$rr=$rr+($te))
	{
		$it++;
		$e1->ean13  = $eti[$rr+0][0];
		echo $et->etiqueta_argox_1x1g($e1);		
	}		
echo $argox->ppla_end();

?>