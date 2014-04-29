<?
$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('estoque_entrada.php','Entrada estoque'));
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_dv.php');
require($include.'_class_form.php');

$form = new form;
require('../_class/_class_pedido.php');
$ped = new pedido;
$rs = $ped->le($dd[2]);
echo $ped->mostra_dados_fornecedor();

/* Item */
$ped->le_item($dd[0]);
$pedido=$ped->line_item['pedi_nrped'];

echo '<table class="tabela00" width="99%" align="center">';
echo $ped->lista_item_header();
echo $ped->lista_item($ped->line_item);
echo '</table>';

$ped->le($pedido);
require("db_temp.php");
//echo $sqlq = "select p_descricao || '-' || p_ean13 as p_descricao, p_codigo from produto where p_fornecedor like '%".trim($ped->ref)."%'";
echo $sqlq = "select p_descricao || '-' || p_ean13 as p_descricao, p_codigo from produto where p_ean13 like '%".trim($ped->ref)."%'";

$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$Q p_descricao:p_codigo:'.$sqlq,'','Produto no cadastro',True,True));
$tela = $form->editar($cp,'');
if ($form->saved > 0)
   {
        header("Location: estoque_entrada_item.php?dd0=".$dd[0]."&dd3=".$dd[1]);
    }else{
        echo $tela;
    }
  
/* Cadastro de produtos */
echo '<BR><BR>';
echo '<a href="produto.php" target="_new" class="botao-geral">Cadastro de Produtos</A>';
echo '<BR><BR>';

/* Rodape */
echo $hd->foot();
?>
