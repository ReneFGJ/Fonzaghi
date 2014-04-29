<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Home'));

$include = '../';
require('../cab_novo.php');
$user->le($_SESSION['nw_cracha']); 
if(strlen(trim($dd[50]))>0){
	$_SESSION['dd50']=$dd[50];
}
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_lojas.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_row.php');
require('db_temp.php');
switch ($nloja) {
	case 'M':
	$nlink = 'lj_modas';
	break;
	case 'J':
	$nlink = 'lj_joias';
	break;
	case 'O':
	$nlink = 'lj_oculos';
	break;
	case 'S':
	$nlink = 'lj_sensual';
	break;
	case 'U':
	$nlink = 'lj_ub';
	break;
	case 'E':
	$nlink = 'lj_modas_ex';
	break;
	case 'G':
	$nlink = 'lj_joias_Ex';
	break;
	
}
require('../_class/_class_produto.php');
$prod = new produto;
require('../_class/_class_botoes.php');
$bot = new form_botoes;
$tela = $bot->action($nlink.'/produtos_atendentes.php',1);
$tela .= $bot->data('Data inicial');
$tela .= $bot->data('Data final');
$tela .= $bot->submit();

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
			
			echo '<div style="float:left; position:absolute" class="noprint"><br><br><br>';
			echo $tela;	
			echo '</div><div style="float:center;"><center>';
			echo '<h1>'.$nloja_nome.' - Vendas de produto por atendentes - '.$dd[1].' a '.$dd[2].'</h1>';  
			echo '<table width="98%" align="center">';
			echo $prod->produto_atendente($_SESSION['dd50']);
			echo '</table></center>';	
		
    	 
    }else{
    	echo '<div style="float:left; position:absolute"><br><br><br>';
   		echo $tela;
		echo '</div><div style="float:center;"><center>';
		echo '<h1>'.$nloja_nome.' Vendas de produto por atendentes </h1>';  
		echo '<table width="98%" align="center">';
		echo '</table></center>';	

   }
echo $hd->foot();
echo '</div>';
?>