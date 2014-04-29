<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
$include = '../';
require('../cab_novo.php');

require("../_class/_class_monitoramento.php");
require("../db_monitoramento.php");
$mon = new monitoramento;
$mon->log($dd[0]);

require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_tips.php');
require($include.'sisdoc_lojas.php');
require("../css/letras.css");

/* Recupera dados do cliente */
$cliente = $dd[0];
if (strlen($cliente)==0)
	{ echo 'OPs, cliente vazio'; }

/* Validar o Checkpost */
//if (checkpost($dd[0])!=$dd[90]) { echo 'Erro de post'; exit; }
require("../_class/_class_metas_medias.php");
$meta_medias = new meta_medias;

//Abre classe indicações
require("../_class/_class_indicacao.php");
$ind = new indicacoes;
$ind->indicacoes_validadas($cliente);

/* Abre classe da consultora */
require("../_class/_class_consultora.php");
$cons = new consultora;
$cons->le($cliente);
$cons->le_completo($cliente);
/* Cartï¿½o Fidelidade */
require("../_class/_class_senff_consultora.php");
$senff = new senff_consultora;
$senff->setCliente($cliente);

require("../_class/_class_consignado.php");
$consignado = new consignado;
$consignado->cliente = $cliente;
$consignado->pecas_quantidades();
$consignado->nome = $cons->nome;
require("../db_fghi_206_cadastro.php");

require("../_class/_class_duplicatas.php");
$duplicata = new duplicatas;
$duplicata->db_cliente = $cliente;

require("../_class/_class_cursos.php");
$cursos = new cursos;
$cursos->cliente = $cliente;

require("../_class/_class_messages.php");
$messa = new message;
$messa->cliente = $cliente;
$messa->mensagens_count();
$messa->le_completo($cliente);

/**** Dados da 2Via */
require("../_class/_class_2via.php");
$v2 = new segunda_via;
$v2->cliente = $cliente;

require("../_class/_class_relatorios.php");
$rel = new relatorios;
$rel->cliente = $cliente;

/**** Classe para cadastro via ajax*/
require('../_class/_class_form_ajax.php');
$ajax = new form_ajax;

echo '<div align="center"><font color="#6A70A4">'.$cliente.'-'.$cons->nome.'</font></div>';
if($perfil->valid('#TTT'))
{
	$perf ='<li><a href="#" onClick="goto(\'#cons05\', this); return false">Perfil Completo</a></li>'; 
}
?><div id="nav">
			<ul>
				<li><a class="active" href="#" onClick="goto('#cons01', this); return false">Perfil básico</a></li>
				<li><a href="#" onClick="goto('#cons02', this); return false">Perfil gráfico</a></li>
				<li><a href="#" onClick="goto('#cons03', this); return false">Financeiro</a></li>
				<li><a href="#" onClick="goto('#cons04', this); return false">Mensagens</a></li>
				<?=$perf;?>
				<li><a href="#" onClick="goto('#cons06', this); return false">2º Via</a></li>
				<li><a href="#" onClick="goto('#cons07', this); return false">Cartão Fidelidade</a></li>
				
			</ul>
		</div>
				
		<div id="wrap" > 
				
		<div id="content">
		
			<div class="contentbox-wrapper">
				
				<?
				/* Aba 01 */ 
				require("cons_01.php"); 
				/* Aba 02 */ 
				require("cons_02.php"); 
				/* Aba 03 */ 
				require("cons_03.php"); 
				/* Aba 04 */ 
				require("cons_04.php"); 
				/* Aba 05 */ 
				require("cons_05.php"); 
				/* Aba 06 */ 
				require("cons_06.php");
				/* Aba 07 */ 
				require("cons_07.php"); 
//***redirecionamento de aba






				?>				
			</div>			
			
		</div>	
	
<?
$jx ='<script>
		function goto(id, t){	
			//animate to the div id.
			$(".contentbox-wrapper").animate({"left": -($(id).position().left), "top": -($(id).position().top)}, 600);
			
			// remove "active" class from all links inside #nav
		    $("#nav a").removeClass("active");
			
			// add active class to the current link
		    $(t).addClass("active");	
		}
		</script>';

$s = "'".$_SESSION['CONSPAGE']."'";
$jy ='<script>
		var id='.$s.';
		var t=this;
			//animate to the div id.
		$(".contentbox-wrapper").animate({"left": -($(id).position().left), "top": -($(id).position().top)}, 600);
		
		// remove "active" class from all links inside #nav
	    $("#nav a").removeClass("active");
		
		// add active class to the current link
	    $(t).addClass("active");
	   	
	  </script>
	';
//echo trim($_SESSION['CONSPAGE']);
if(strlen(trim($_SESSION['CONSPAGE']))>0)
{
	
	echo $jy;
	$_SESSION['CONSPAGE']='';
	echo $jx;
}else{
	echo $jx;
}

?>
<div  ondrag="return false"  ondragleave="return false" oncontextmenu="return false" ondragstart="return false" onselectstart="return false" oncopy="return false">

</div>

<?
require("cliente_indicacao_calcular.php");
?>




 