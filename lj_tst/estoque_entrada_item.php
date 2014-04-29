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
require('../_class/_class_pedido.php');
$ped = new pedido;
require('../_class/_class_estoque.php');
$est = new estoque;
require('../_class/_class_produto.php');
$prod = new produto;
require($include.'_class_form.php');
$form = new form;

$ped->le_item($dd[0]);
$pedido=$ped->line_item['pedi_nrped'];
$ped->le($pedido);
//if (strlen($ped)==0) { redirecina('estoque_entrada.php'); }

/* informar a quantidade */

require("db_temp.php");
//$sqlq = "select p_descricao || '-' || p_ean13 as p_descricao, p_codigo from produto where p_fornecedor like '%".trim($ped->ref)."%'"; 
$sqlq = "select p_descricao || '-' || p_ean13 as p_descricao, p_codigo from produto where p_ean13 like '%".trim($ped->ref)."%'";

$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$Q p_descricao:p_codigo:'.$sqlq,'','Produto',True,True));
if (strlen($dd[3]) <= 0)
    {
            array_push($cp,array('$H8','','',True,True));
    } else {
            $max=$ped->line_item['pedi_quan'];
            $proc=$ped->line_item['pedi_proc'];
            $resto=$max-$proc;
            
            array_push($cp,array('$O 0:Não&1:Sim','','Venc. ? ',False,True));
            array_push($cp,array('$D8','','Validade',False,True));
            array_push($cp,array('$I4','','Quant. (max. '.$resto.')',False,True));
            array_push($cp,array('$S4','','Tamanho',False,True));
            
            $prod->le($dd[3]);
            $user=$_SESSION['nw_user'];
            $id_prod=$prod->line['p_codigo'];
            $desc_prod=$prod->line['p_descricao'];
            $ref_forn=$ped->line_item['pedi_ref_item'];
            $pedido=$ped->line_item['pedi_nrped'];
            $cod_ped=$ped->line_item['pedi_ped'];
            $forn=round($ped->line['ped_fornecedor']);
            $venc=substr($dd[5],6,4).substr($dd[5],3,2).substr($dd[5],0,2);
            $status=$dd[4];
            $qtd=$dd[6];
            $tam=strtoupper($dd[7]);
            $preco=$prod->line['p_preco'];
            $custo=$prod->line['p_custo'];
            $comissao=$prod->line['p_comissao'];  
              
    }   

$valida=1;
$tela = $form->editar($cp,'');
$tela2 = $ped->mostra_item();
if ($form->saved > 0)
   {
                       
                if ($status==1){
                    /*validade maior que 60 dias executa */  
                    if ($venc<(date(Ymd)+60)){
                        $valida=0;
                        $aviso =">>>>>>Vencimento menor que 60 dias.<<<<<<";
                        header("Location: estoque_entrada_item.php?dd0=$dd[0]&dd3=$id_prod&dd4=$status&dd6=$qtd&dd7=$tam&dd95=$aviso");
                    }    
                }
                /*Quantidade a ser etiquetada maior que pedido, nï¿½o executa*/    
                if(($qtd>$resto)||($qtd==0)){
                    $valida=0;
                    $aviso =">>>>>>Quantidade maior que a existente ou zerada<<<<<<";
                    header("Location: estoque_entrada_item.php?dd0=$dd[0]&dd3=$id_prod&dd4=$status&dd6=$qtd&dd7=$tam&dd95=$aviso");       
                }
                 /*Tamanho do produto nï¿½o foi preenchido, nï¿½o executa*/    
                if(strlen($tam)==0){
                    $valida=0;
                    $aviso =">>>>>>Tamanho não definido<<<<<<";
                    header("Location: estoque_entrada_item.php?dd0=$dd[0]&dd3=$id_prod&dd4=$status&dd95=$aviso");       
                }
                
                if ($valida==1) {
                for ($r=1;$r<=round($qtd);$r++)
                     {
                     	 require("db_temp.php");
                     	 $est->db="db_temp.php";   
                         $cd=$est->ultimo_registro();
            	         $earn=substr("0".$cd.DV_EAN13($cd),-12); 
					 	 	
            	 	     $est->inserir_produto_estoque($id_prod,$ref_forn,$forn,$venc,$qtd,$tam,$user,$preco,$custo,$comissao,$earn,$pedido);
                         
                      }
                $est->atualiza_pedido($dd[0], $ref_forn, $qtd);
                $aviso = '';
                $cd=$est->ultimo_registro();
				$ean = substr($cd,-9);
				$est->atualiza_estoque_atual($nloja, $ean);
                redirecina('estoque_entrada.php?dd2='.$pedido.'&dd90='.checkpost($pedido));
                }
	}else{
	    echo '<table align=center width="95%">
	           <tr>
	               <td width="65%">'.$tela2.'</td>
	               <td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
	               <td width="35%" bgcolor="#FFFFFF">'.$tela.'</td></tr>
	          <tr>
	               <td align="center" colspan="3"><font color="RED" size="5">'.$dd[95].'</font></td></tr>    
	          <tr>
	               <td align="center" colspan="3">'.$est->lista_produtos_estoque($pedido,$forn).'</font></td></tr>
	          </table>';
        
        
	}
  
/* Rodape */
echo $hd->foot();
?>
