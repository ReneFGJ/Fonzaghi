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
$e2 = new etiqueta;
$e3 = new etiqueta;
$e4 = new etiqueta;
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

$te = 3; // Tres etiquetas
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
        

        $e2->ean13  = $eti[$rr+1][0];
        $e2->codigo = $eti[$rr+1][8];
        $e2->preco  = $eti[$rr+1][3];
        $e2->tam    = $eti[$rr+1][5];
        $e2->img    = 'img_loja/icone_loja_A.jpg';
        $e2->nome   = $eti[$rr+1][6];
        $e2->comissao   = $eti[$rr+1][7];
        $e2->validade   = $eti[$rr+1][9];
        

        $e3->ean13  = $eti[$rr+2][0];
        $e3->codigo = $eti[$rr+2][8];
        $e3->preco  = $eti[$rr+2][3];
        $e3->tam    = $eti[$rr+2][5];
        $e3->img    = 'img_loja/icone_loja_A.jpg';
        $e3->nome   = $eti[$rr+2][6];
        $e3->comissao   = $eti[$rr+2][7];   
        $e3->validade   = $eti[$rr+2][9];
        
    
        echo $et->etiqueta_argox_3x1e($e1,$e2,$e3);     
    }       
echo $argox->ppla_end();

?>