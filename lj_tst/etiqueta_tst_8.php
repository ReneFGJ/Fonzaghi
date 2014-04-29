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

$eti = $et->etiquetas_imprimir_lista2($dd[1]);

$et = new etiqueta;
$argox = new argox;

header('Content-type: application/force-download');
header('Content-Disposition: attachment; filename="etiqueta.ltp"');
echo $argox->ppla_start();

$te = 1; // uma etiqueta
$it = 0;
for ($rr=0;$rr < count($eti);$rr=$rr+($te))
    {
        $it++;
        $e1->ean13  = $eti[$rr+0][0];
        $e1->codigo = $eti[$rr+0][8];
        $e1->preco  = $eti[$rr+0][3];
        $e1->tam    = $eti[$rr+0][5];
        $e1->img    = 'img_loja/icone_loja_A.jpg';
        $e1->nome   = $eti[$rr+0][6];
        $e1->comissao   = $eti[$rr+0][7];
        $e1->validade   = $eti[$rr+0][9];
		$e1->v_ref   = $eti[$rr+0][10];
		$e1->classe	= $eti[$rr+0][11];	
    
        echo $et->etiqueta_argox_1x1h($e1);     
    }       
echo $argox->ppla_end();

?>