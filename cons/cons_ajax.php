<?php
$include = '../';
require("../db.php");
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');
//require("../css/letras.css");

global $ljx,$lj,$rst,$nota;
 /* Security */
session_start();

/* Seguran�a */
require("../_class/_class_user.php");
$user = new user;
require("../_class/_class_user_perfil.php");
$perfil = new user_perfil;
$user->security();
$ss = $user;

$login = $_SESSION['nw_user'];
$verb = $dd[1];
$xsec = $dd[90];
$xsep = checkpost($dd[0].$secu);
$ljx=$dd[7];
$nota=$dd[8];

if ((!($xsec==$xsep)) or ($login == '')) { echo 'Erro de post'; exit; }

require("../_class/_class_consignado.php");

		$consignado = new consignado;
		$consignado->cliente = $dd[0];
		global $base_name,$base_host,$base_user;	
		require("../db_fghi_206_cadastro.php");
		$consignado->le($consultora2->cliente);

require("../_class/_class_relatorios.php");
		
		$relat = new relatorios;
		$relat->cliente=$dd[0];

require("../_class/_class_2via.php");
		$v2 = new segunda_via;	
			
require("../_class/_class_senff_consultora.php");
		$senff = new senff_consultora;
		$senff->setCliente($dd[0]);
			

switch ($verb) {
	case 'insere_mensagem':
			/* Salvar nova mensagem */
			$msg=$dd[20];
			$cliente=$dd[0];
			$tipo=$dd[21];
			$consignado->salva_msg($login,$msg,$cliente,$tipo,$ljx);
			
			$pag = $consignado->recupera_pagina();
			$tela04a = $consignado->historico_relacionamento($ljx,$pag);		 
			$ttln=$consignado->ttln; /* Total de registros a mostrar por p�gina */
			$ttrn=$consignado->tthistoricos; /* Total de registros com mensagens */
			/* Montage da tela */
			echo $consignado->cabecalho_tipo_loja();
			echo '<table width="100%" height="320" border=0>
					<TR valign="top">';
			echo '<TD width="90%">';
			echo utf8_encode($tela04a);
			echo '<TD width="10%" align="center">';
			echo $consignado->nova_mensagem();
			echo '</table>';
			echo $consignado->mostra_navegador_paginas('cons_ajax.php','cons04','mensagens');
		
			break;
				
	case 'mensagens':
			/*primeiro acesso*/
			$pag = $consignado->recupera_pagina();
			$tela04a = $consignado->historico_relacionamento($ljx,$pag);		 
			$ttln=$consignado->ttln; /* Total de registros a mostrar por p�gina */
			$ttrn=$consignado->tthistoricos; /* Total de registros com mensagens */
			/* Montage da tela */
			echo $consignado->cabecalho_tipo_loja();
			echo '<table width="100%" height="320" border=0>
					<TR valign="top">';
			echo '<TD width="90%">';
			echo utf8_encode($tela04a);
			echo '<TD width="10%" align="center">';
			echo $consignado->nova_mensagem();
			echo '</table>';
			echo $consignado->mostra_navegador_paginas('cons_ajax.php','cons04','mensagens');
		
			break;
	case '2via':
		/*primeiro acesso*/
			$pag = $consignado->recupera_pagina();
			$v2->cliente = $dd[0];
			if (strlen($v2->data_ini) == 0){	$v2->data_ini = dateAdd('m',-20,date("Ym").'01'); }
			if (strlen($v2->data_fim) == 0){	$v2->data_fim = date("Ymd"); }
			$v2->loja = $dd[7];
			 /* Montage da tela */
			echo $relat->cabecalho_tipo_loja_2via();
			$tela06a = $relat->lista_vias($ljx,$pag);		 
			echo '<div style="overflow:scroll; height:350px;"><table width="100%" height="320" border=0>
					<TR valign="top">';
			echo '<TD width="90%">';
			echo utf8_encode($tela06a);
			echo '<TD width="10%" align="center">';
			echo '</table></div>';
			break;		
			
	case 'financeiro':
		/*primeiro acesso*/
			
			 /* Montage da tela */
			echo $relat->cabecalho_financeiro();
			switch ($nota) {
				//movimento de notas promissorias	
				case 'np':
					$relat->total_registros_nota($nota);
					$pag = $relat->recupera_pagina();
					$pagina= $relat->mostra_navegador_paginas('cons_ajax.php','cons03','financeiro','np');		
					$tela03a=$relat->nota_promissoria('np',$pag);
					
					break;
				//movimento loja	
				case 'ml':
					$relat->total_registros_nota($nota);
					$pag = $relat->recupera_pagina();
					$pagina= $relat->mostra_navegador_paginas('cons_ajax.php','cons03','financeiro','ml');
					$tela03a=$relat->movimento_loja('ml',$pag);		
					
					break;
				//ultimos movimentos de caixa	
				case 'um':
					
					$relat->total_ult_movimentos_caixa($dd[0]);
					$pag = $relat->recupera_pagina();
					$pagina= $relat->mostra_navegador_paginas('cons_ajax.php','cons03','financeiro','um');		
					$tela03a=$relat->mostra_ultimos_mov_caixa($pag);
					break;
				
				case 'cr':
					
					$pag = $relat->recupera_pagina();
					$relat->total_ult_creditos($dd[0]);
					$pagina= $relat->mostra_navegador_paginas('cons_ajax.php','cons03','financeiro','cr');		
					$tela03a=$relat->mostra_ultimos_creditos($pag);
					break;	
				
				default:
					
					break;
			}
			 
					$sx ='<div  width="100%"  style="overflow:scroll; height:350px;"><table width="100%" height="320" border=0>
					<TR valign="top">
					<TD width="90%">'.
					utf8_encode($tela03a);
					$sx .='</table></div>';
					if(	$nota=='cr' or 
						$nota=='ml' or
						$nota=='um' or
						$nota=='np')
					{
						echo $sx;
					}else{
						echo $sx.$pagina;
					}
			break;
							
	 case senff:
		 	$relat->total_ult_movimentos_senff($dd[0]);
			$pag = $relat->recupera_pagina();
			$pagina= $relat->mostra_navegador_paginas('cons_ajax.php','cons07','senff');		
			$tela07a=$relat->mostra_relatorio_senff($pag);
            
            if ($perfil->valid('#MAR#ADM'))
            {
                $link='</br><div><b><a href="javascript:newxy2(\'http://10.1.1.220/fonzaghi/senff/\',800,700);">Sistema Cart�o Senff</a></div>';
    
             } else{$link='';};
            
    
            
		 	echo  ' 
		 	        <table width="100%" height="320" border=0>
					<tr valign="top">
					<td width="90%">
					   <div style="overflow:scroll; height:350px;">'.
					   utf8_encode($tela07a).'
					   </div>
					</td>   
					<td width="10%" align="center">
					   <div>'.utf8_encode($senff->saldos()).'</div>'.utf8_encode($link).
					'</td>   
					</table>';
		 	break;		
			
}
?> 	

