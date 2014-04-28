<?php
 /**
  * Printers
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.17
  * @package Classe
  * @subpackage Classe de Interoperabilidade de dados
 */
require_once($include."sisdoc_email.php");
 class printers
	{
		var $include_class = '../';	
		
		function email_printers()
		{
			$this->atualiza_printers();
			$dest = 'sistemas@fonzaghi.com.br';
			$assu = 'Contador impressoras '.date('Ymd');
			$texto = '<h1>Total de paginas impressas até '.date('d/m/Y').'</h1>';
			$texto .= $this->visualizar_tabela();
			enviaremail_authe($dest,'',$assu,$texto);
			$dest = 'ti@fonzaghi.com.br';
			enviaremail_authe($dest,'',$assu,$texto);	
			return(1);
		}
		function visualizar_tabela()
		{
			global $base_name,$base_server,$base_host,$base_user,$tab_max;
			require($this->include_class."db_206_printers.php");
			$sx =  '<h1>Registros modificados</h1>';
			$sx .=  '<table border="0" width='.$tab_max.' align="center" cellpadding="0" cellspacing="0">';
			$sx .= '<tr>';
			$sx .= '<td colspan="6">';
			$sx .= '<p><b><i>Aviso:</i></b> Os registros listados abaixo foram cadastrados na data de hoje ('.date('d/m/Y').'). Caso esta operação se repita 
			apenas os campos <b><i>Páginas</i></b> e <b><i>Toner</i></b> serão atualizados.';
			$sx .= '</p>';
			$sx .= '</td>';
			$sx .= '<tr>';
			$sx .= '<TD colspan="6" class="lt1" align="center">Ordenação: <b><i>http</i></b></td>';
			$sx .= '</tr>';
			$sx .= '<TH class="tabelaTH">Data</TH>';
			$sx .= '<TH class="tabelaTH">IP</TH>';
			$sx .= '<TH class="tabelaTH">Modelo</TH>';
			$sx .= '<TH class="tabelaTH">Nome</TH>';
			$sx .= '<TH class="tabelaTH">Páginas P/B</TH>';
			$sx .= '<TH class="tabelaTH">Páginas Coloridas</TH>';
			$sx .= '<TH class="tabelaTH">Toner</TH>';
			$sx .= '</tr>';
			$sql = "select * from (SELECT * FROM printers_count
			  						left join printers on pr_codigo = pc_codigo) as tb 
			  		inner join printers_tipo on pt_codigo=pr_tipo
			  		where pc_data = ".date('Ymd')."
			  		order by pr_counter, pc_data
			";
			  
			$rlt = db_query($sql);
			
			$reg=0;
			while ($line=db_read($rlt)){
				$sx .= '<tr '.coluna().'>';
			
				$sx .= '<td class="tabela00" align="right">';
				$sx .= stodbr($line['pc_data']);
				$sx .= '</td>';
			
				$sx .= '<td class="tabela00" align="right">';
				$sx .= $line['pr_ip'];
				$sx .= '</td>';
			
				$sx .= '<td class="tabela00" align="right">';
				$sx .= $line['pt_modelo'];
				$sx .= '</td>';
			
				$sx .= '<td class="tabela00" align="right">';
				$sx .= $line['pr_nome'];
				$sx .= '</td>';
			
				$sx .= '<td class="tabela00" align="center">';
				$sx .= $line['pc_pages'];
				$sx .= '</td>';
			
				$sx .= '<td class="tabela00" align="center">';
				$sx .= $line['pc_pages_color'];
				$sx .= '</td>';
			
				$sx .= '<td class="tabela00" align="left">';
				$sx .= $line['pc_tonner'];
				$sx .= '</td>';
			
				$sx .= '</tr>';
				$reg++;
			}
			
			$sx .= '<tr>';
			$sx .= '<td colspan="6" class="rodapetotal">'.$reg.' ítens</td>';
			$sx .= '</tr>';
			
			$sx .= '</table>';
			return($sx);	
		}
		function pagina_nome($endereco)
		{
			$endereco=trim($endereco);
			for($i=strlen($endereco); $i > 0; $i--){
				if ($endereco[$i]=='/'){
					break;
				}
			}
			
			$pagina=substr($endereco, ($i+1), strlen($endereco)-($i-1) );
			return $pagina;
		}
		function atualiza_printers()
		{
			global $base_name,$base_server,$base_host,$base_user,$tab_max;
			require($this->include_class."db_206_printers.php");
			$sql = "SELECT * FROM printers where pr_ativa = 1 order by pr_counter";
			$rlt = db_query($sql);
			
			$pagina='';
			$msg_erro=0;
			$Handle=0;
			$st .= '<table width="100%" class="lt0">';
			while ($line=db_read($rlt)){
				$pagina = $this->pagina_nome($line['pr_counter']);
				$idioma = strtolower($line['pr_idioma']);
				
				$st .= '<TR><TD>'.$line['pr_nome'];
				$st .= '<TD>'.$line['pr_codigo'];
				$st .= '<TD>'.$line['pr_ip'];
				$st .= '<TD>'.$line['pr_counter'];
				
			switch ($pagina) {
				/**http site tipo 1**********/
				case 'billing_counters.htm':
					
					$Handle=fopen(trim($line['pr_counter']),"R");
					if ($Handle){
						$output='';
						while(!feof($Handle)){
							$output.= fgets($Handle,4096);
						}
						fclose($Handle);
						
						$output=strip_tags($output);
						$pos=strrpos($output,"Total");
						$entrou=0;$parametro=1;
						$pages=0;$toner=0;
					
						for ($i=$pos; $i<strlen($output); $i++){
							if (ord($output[$i]) >= 48 && ord($output[$i]) <= 57){
								$entrou=1;
							
								if ($parametro == 1){
									$pages.=$output[$i];
								}else{
									$toner.=$output[$i];
								}
							}
							else{
								if ($entrou == 1){
									$parametro=2;
								}
							}
						}
						$pages=sonumero($pages*1);
						$toner=0;
				
						$sql2 = "SELECT id_pc, pc_data, pc_text, pc_pages, pc_tonner, pc_codigo, pc_erro ";
						$sql2 .= " FROM printers_count ";
						$sql2 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
					
						$rlt2 = db_query($sql2);
						
						if (pg_num_rows($rlt2)==0){
							$sql3 = "INSERT INTO printers_count(pc_data,  ";
							$sql3 .= " pc_pages, pc_tonner, pc_codigo, pc_erro) ";
							$sql3 .= " VALUES (".date('Ymd').", ".$pages.", ".$toner.", '".$line['pr_codigo']."', 0);";
					
						}
						else{
							$sql3 = " UPDATE printers_count ";
							$sql3 .= " SET pc_pages=".$pages.", pc_tonner=".$toner." ";
							$sql3 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
						}
					
						$rlt3 = db_query($sql3);	
					
						$sql3='';
					
						$pages=0;
						$toner=0;
					}			
					else{
						if ($msg_erro == 0){
							echo '<br><font color="#ff0000"><b>Página(s) não encontrada(s):</b></font>';
							$msg_erro=1;
						}
						echo '<br><font color="#ff0000"><b>'.$line['pr_counter'].'</b></font>';
					}
					break;
				/**http site tipo 2**********/	
				case 'system.cgi':
			
					$Handle=fopen(trim($line['pr_counter']),"R");
					if ($Handle){
						$output='';
						while(!feof($Handle)){
							$output.= fgets($Handle,4096);
						}
						fclose($Handle);
						
						$output=strip_tags($output);
						$pos=strrpos($output,"impressas");
						$entrou=0;$parametro=1;
						$pages=0;
					
						for ($i=$pos; $i<strlen($output); $i++){
							if (ord($output[$i]) >= 48 && ord($output[$i]) <= 57){
								$entrou=1;
								if ($parametro == 1){$pages.=$output[$i];}
							}
							else{
								if ($entrou == 1){break;}
							}
						}
						$pages=$pages*1;
				
						$sql2 = "SELECT id_pc, pc_data, pc_text, pc_pages, pc_tonner, pc_codigo, pc_erro ";
						$sql2 .= " FROM printers_count ";
						$sql2 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
						$rlt2 = db_query($sql2);
						if (pg_num_rows($rlt2)==0){
							$sql3 = "INSERT INTO printers_count(pc_data,  ";
							$sql3 .= " pc_pages, pc_tonner, pc_codigo, pc_erro) ";
							$sql3 .= " VALUES (".date('Ymd').", ".$pages.", 0, '".$line['pr_codigo']."', 0);";
					
						}
						else{
							$sql3 = " UPDATE printers_count ";
							$sql3 .= " SET pc_pages=".$pages."";
							$sql3 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
						}
					
						$rlt3 = db_query($sql3);	
					
						$sql3='';
					
						$pages=0;
					}
					else{
						if ($msg_erro == 0){
							echo '<br><font color="#ff0000"><b>Página(s) não encontrada(s):</b></font>';
							$msg_erro=1;
						}
						echo '<br><font color="#ff0000"><b>'.$line['pr_counter'].'</b></font>';
					}
			
					break;
				/**http site tipo 3**********/	
				case 'supplies_status.htm':
					
					$Handle=fopen(trim($line['pr_counter']),"R");
					if ($Handle){
						$out='';
						while(!feof($Handle)){
							$out.= fgets($Handle,4096);
						}
						fclose($Handle);
						
						$output=strip_tags($out);
					
						$pos=strrpos($output,"Fuser Kit");
					
						$entrou=0;$parametro=1;
						$pages=0;$toner=0;
					
						for ($i=$pos; $i<strlen($output); $i++){
							if (ord($output[$i]) >= 48 && ord($output[$i]) <= 57){
								$entrou=1;
							
								if ($parametro == 1){
									$pages.=$output[$i];
								}else{
									$toner.=$output[$i];
								}
							}
							else{
								if ($entrou == 1){
									$parametro=2;
								}
							}
						}
						$pages=$pages*1;
						$toner=$pages*1;
						
						/**************** Copiador **********************/			
			
						$output=strip_tags($out);
						$pos=strpos($output,"Fax");
						$pages_fax = sonumero(substr($output,$pos,30));
						
						$pages = $page + $pages_fax + $pages_copias;
				
						$sql2 = "SELECT id_pc, pc_data, pc_text, pc_pages, pc_tonner, pc_codigo, pc_erro ";
						$sql2 .= " FROM printers_count ";
						$sql2 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
					
						$rlt2 = db_query($sql2);
					
						if (pg_num_rows($rlt2)==0){
							$sql3 = "INSERT INTO printers_count(pc_data,  ";
							$sql3 .= " pc_pages, pc_tonner, pc_codigo, pc_erro) ";
							$sql3 .= " VALUES (".date('Ymd').", ".$pages.", ".$toner.", '".$line['pr_codigo']."', 0);";
					
						}
						else{
							$sql3 = " UPDATE printers_count ";
							$sql3 .= " SET pc_pages=".$pages.", pc_tonner=".$toner." ";
							$sql3 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
						}
						$rlt3 = db_query($sql3);	
					
						$sql3='';
					
						$pages=0;
						$toner=0;
					}			
					else{
						if ($msg_erro == 0){
							echo '<br><font color="#ff0000"><b>Página(s) não encontrada(s):</b></font>';
							$msg_erro=1;
						}
						echo '<br><font color="#ff0000"><b>'.$line['pr_counter'].'</b></font>';
					}
					break;
				/**http site tipo 4**********/	
				case 'getUnificationCounter.cgi':
					
					/***********Idioma da pagina em português*************************/
					if ($idioma == 'pt') 
					{
						$Handle=fopen(trim($line['pr_counter']),"R");
						if ($Handle)
						{
							$out='';
							while(!feof($Handle))
							{
								$out.= fgets($Handle,4096);
							}
							fclose($Handle);
							
							$output=strip_tags($out);
						
							/**************** Impressoras **********************/
						
							$pos=strrpos($output,"Impressora");
							$entrou=0;
							$parametro=1;
							$pages=0;
						
							for ($i=$pos; $i<strlen($output); $i++)
							{
								if (ord($output[$i]) >= 48 && ord($output[$i]) <= 57)
								{
									$entrou=1;
									if ($parametro == 1){$pages.=$output[$i];}
								}else{
									if ($entrou == 1){break;}
								}
							}
							$pages=$pages*1;
							$page = $pages;
				
							/**************** Copiador **********************/			
				
							$output=strip_tags($out);
							$pos=strpos($output,"Copiador");
							$pages_copias = sonumero(substr($output,$pos,30));
				
							$pos=strpos($output,"Fax");
							$pages_fax = sonumero(substr($output,$pos,30));
							
							$pages = $page + $pages_fax + $pages_copias;
							/*****/
					
							$sql2 = "SELECT id_pc, pc_data, pc_text, pc_pages, pc_tonner, pc_codigo, pc_erro ";
							$sql2 .= " FROM printers_count ";
							$sql2 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
							$rlt2 = db_query($sql2);
							
							if (pg_num_rows($rlt2)==0)
							{
								$sql3 = "INSERT INTO printers_count(pc_data,  ";
								$sql3 .= " pc_pages, pc_tonner, pc_codigo, pc_erro) ";
								$sql3 .= " VALUES (".date('Ymd').", ".$pages.", 0, '".$line['pr_codigo']."', 0);";
							}else{
								$sql3 = " UPDATE printers_count ";
								$sql3 .= " SET pc_pages=".$pages."";
								$sql3 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
							}
							$rlt3 = db_query($sql3);	
							$sql3='';
							$pages=0;
						}else{
							if ($msg_erro == 0)
							{
								echo '<br><font color="#ff0000"><b>Página(s) não encontrada(s):</b></font>';
								$msg_erro=1;
							}
							echo '<br><font color="#ff0000"><b>'.$line['pr_counter'].'</b></font>';
						}
					}
					/***********Idioma da pagina em inglês*************************/
					if ($idioma == 'en') 
					{
						$Handle=fopen(trim($line['pr_counter']),"R");
						if ($Handle)
						{
							$out='';
							while(!feof($Handle))
							{
								$out.= fgets($Handle,4096);
							}
							fclose($Handle);
							
							$output=strip_tags($out);
						
							/**************** Impressos e copias **********************/			
				
							$output=strip_tags($out);
							/*bloco Coverage*/
							$pos=strpos($output,"Coverage");
							$outputx = substr($output,$pos,110);
							/*primeiro Page: do bloco*/
							$posx=strpos($outputx,"Page:");
							$copier_bw =sonumero(substr($outputx,$posx,10));
							/*copia codigo apos primeiro Page:*/
							$outputx = substr($outputx,$posx+10,30);
							/*segundo Page: do bloco*/
							$posx=strpos($outputx,"Page:");
							$copier_cl =sonumero(substr($outputx,$posx,10));
							
							$sql2 = "SELECT id_pc, pc_data, pc_text, pc_pages, pc_tonner, pc_codigo, pc_erro ";
							$sql2 .= " FROM printers_count ";
							$sql2 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
						
							$rlt2 = db_query($sql2);
						
							if (pg_num_rows($rlt2)==0)
							{
								$sql3 = "INSERT INTO printers_count(pc_data,  ";
								$sql3 .= " pc_pages, pc_tonner, pc_codigo, pc_erro, pc_pages_color) ";
								$sql3 .= " VALUES (".date('Ymd').", ".$copier_bw.", 0, '".$line['pr_codigo']."', 0,".$copier_cl.");";
						
							}else{
								$sql3 = " UPDATE printers_count ";
								$sql3 .= " SET pc_pages=".$copier_bw.", pc_pages_color=".$copier_cl."";
								$sql3 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
							}
							$rlt3 = db_query($sql3);	
							$sql3='';
							$pages=0;
						}else{
							if ($msg_erro == 0)
							{
								echo '<br><font color="#ff0000"><b>Página(s) não encontrada(s):</b></font>';
								$msg_erro=1;
							}
							echo '<br><font color="#ff0000"><b>'.$line['pr_counter'].'</b></font>';
						}
					}
					break;		
				/**http site tipo 5**********/	
				case 'counters.json':
					$Handle=fopen(trim($line['pr_counter']),"R");
					if ($Handle)
					{
						$out='';
						while(!feof($Handle))
						{
							$out.= fgets($Handle,4096);
						}
						fclose($Handle);
						$output=strip_tags($out);
					
						/**************** Impressos e copias **********************/			
			
						$output=strip_tags($out);
						/*bloco Coverage*/
						$pos=strpos($output,"GXI_BILLING_TOTAL_IMP_CNT:");
						$copier_bw = sonumero(substr($output,$pos,40));
						$copier_cl = 0;
						$sql2 = "SELECT id_pc, pc_data, pc_text, pc_pages, pc_tonner, pc_codigo, pc_erro ";
						$sql2 .= " FROM printers_count ";
						$sql2 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
					
						$rlt2 = db_query($sql2);
					
						if (pg_num_rows($rlt2)==0)
						{
							$sql3 = "INSERT INTO printers_count(pc_data,  ";
							$sql3 .= " pc_pages, pc_tonner, pc_codigo, pc_erro, pc_pages_color) ";
							$sql3 .= " VALUES (".date('Ymd').", ".$copier_bw.", 0, '".$line['pr_codigo']."', 0,".$copier_cl.");";
					
						}else{
							$sql3 = " UPDATE printers_count ";
							$sql3 .= " SET pc_pages=".$copier_bw.", pc_pages_color=".$copier_cl."";
							$sql3 .= " where pc_data=".date('Ymd')." and pc_codigo='".$line['pr_codigo']."'";
						}
					
						$rlt3 = db_query($sql3);	
					
						$sql3='';
					
						$pages=0;
					}else{
						if ($msg_erro == 0)
						{
							echo '<br><font color="#ff0000"><b>Página(s) não encontrada(s):</b></font>';
							$msg_erro=1;
						}
						echo '<br><font color="#ff0000"><b>'.$line['pr_counter'].'</b></font>';
					}
					break;
				default:
					
					break;
			}
		}
			return (1);	
		}
}
?>
