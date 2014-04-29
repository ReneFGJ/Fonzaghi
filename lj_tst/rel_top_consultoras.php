<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Home'));

$include = '../';
require('../cab_novo.php');
$user->le($_SESSION['nw_cracha']); 
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_lojas.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_row.php');
require('../_class/_class_top_consultoras.php');
$tops = new top_consultoras; 
require('../_class/_class_botoes.php');
$bot = new form_botoes;
$lj = array();
array_push($lj,array('Joias ','0'));
array_push($lj,array('Modas ','1'));
array_push($lj,array('Óculos','2'));
array_push($lj,array('Use Brilhe','3'));
array_push($lj,array('Sensual','4'));
array_push($lj,array('Modas Express','5'));
array_push($lj,array('Joias Express','6'));

$top = array();
array_push($top,array(' Todos','0'));
array_push($top,array('Top 500','1'));
array_push($top,array('Top 100','2'));
array_push($top,array('Top 50','3'));
array_push($top,array('Top 10','4'));

$tela = $bot->action('coordenadoras/rel_top_consultoras.php',1);
$tela .= $bot->data('Data inicial');
$tela .= $bot->data('Data final');
$tela .= $bot->mostrar_botoes($lj);
$tela .= $bot->mostrar_botoes($top);
$tela .= $bot->submit();
if(strlen(trim($dd[3]))==0){
				$f1=$dd[3]=0;
			}else{
				$f1=$dd[3];  //filtro 1,
			}
			if(strlen(trim($dd[4]))==0){
				$f2=$dd[4]=0;
			}else{
				$f2=$dd[4];  //filtro 2,
			}
if (strlen($acao) > 0)
    {
    		$d1=substr($dd[1],6,4).substr($dd[1],3,2).substr($dd[1],0,2); //data inicial
			$d2=substr($dd[2],6,4).substr($dd[2],3,2).substr($dd[2],0,2); //data final
			if (strlen(trim($d1))==0) 
			{
				$d1='19900101';
				$dd[1]=substr($d1,6,2).'/'.substr($d1,4,2).'/'.substr($d1,0,4);
			}
			if (strlen(trim($d2))==0) 
			{
				$d2=date('Ymd');
				$dd[2]=substr($d2,6,2).'/'.substr($d2,4,2).'/'.substr($d2,0,4);
			}
			
			setlj();
			require('../'.$setlj[3][$f1]);
			echo '<div style="float:left; position:absolute" class="noprint"><br><br><br>';
			echo $tela;	
			echo '</div><div style="float:center;"><center>';
			echo '<h1>'. $setlj[1][$f1].' - Top vendas - '.$dd[1].' a '.$dd[2].'</h1>';  
			echo '<table width="98%" align="center">';
			echo $tops->lista_top($d1,$d2,$f2);
			echo '</table></center>';	
		
    	 
    }else{
    	echo '<div style="float:left; position:absolute"><br><br><br>';
   		echo $tela;
		echo '</div><div style="float:center;"><center>';
		echo '<h1> Top vendas</h1>';  
		echo '<table width="98%" align="center">';
		echo '</table></center>';	

   }
echo $hd->foot();
echo '</div>';
?>