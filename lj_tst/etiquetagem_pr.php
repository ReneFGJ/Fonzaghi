<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','iNDEX'));

$include = '../';
require("../cab_novo.php");
require($include.'sisdoc_windows.php');
require("../_classes/_class_etiqueta.php");
require('db_temp.php');

$et = new etiqueta;
?>
<h1>Impressão de Etiquetas</h1>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<center>
	<table width="98%" align=center class="lt4" border=0>
		<TR><TD align="center">TOTAL DE ETIQUETAS<BR>
			<font style="font-size: 72px;">
			<? 
			$total = $et->etiqueta_total_imprimir();
			$etiqu = $et->etiqueta_total_imprimir_login(); 
			echo $total;
			?><BR>
			</font>
			
		</TR>
	<TR><TD align="center">	
		<?
		if ($total > 0)
			{
			$link = '<A href="javascript:newxy2(';
			$link .= "'etiqueta_tst.php',800,300);";
			$link .= '">Imprimir todas</A>';
			//echo $link;	
			echo '<table width="100%" border=0 class="tabela00">';
			echo '<TR>';
			if ($nloja=='G')
			{
				echo '<TH>et. joias express';	
			}
			if ($nloja=='U')
			{
				echo '<TH>et. UB s/valor';	
			}

			if($nloja=='M' or
				$nloja=='O' or
				$nloja=='S' or
				$nloja=='E'){
				echo '<TH>et. dupla';
				echo '<TH>et. adesiva 3x';
				echo '<TH>et. Plástica 3x';
				echo '<TH>et. Plástica 3x Validade';
				echo '<TH>et. Plástica Peq. 3x Validade';
				echo '<TH>et. Convites Design';
	            
			}	
			echo '<TH>login';
			
			
			for ($r=0;$r < count($etiqu);$r++)
				{
					if ($nloja=='G')
					{
							$link7 = '<A href="javascript:newxy2(';
							$link7 .= "'etiqueta_tst_8.php?dd1=".$etiqu[$r][1]."',800,300);";
							$link7 .= '">Imprimir ';
                    }
					if ($nloja=='U')
					{
							$link8 = '<A href="javascript:newxy2(';
							$link8 .= "'etiqueta_tst_9.php?dd1=".$etiqu[$r][1]."',800,300);";
							$link8 .= '">Imprimir ';
                    }
					
                    if($nloja=='M' or
						$nloja=='O' or
						$nloja=='S' or
						$nloja=='E'){
							
		                    $link = '<A href="javascript:newxy2(';
							$link .= "'etiqueta_tst.php?dd1=".$etiqu[$r][1]."',800,300);";
							$link .= '">Imprimir ';
							
							$link2 = '<A href="javascript:newxy2(';
							$link2 .= "'etiqueta_tst_2.php?dd1=".$etiqu[$r][1]."',800,300);";
							$link2 .= '">Imprimir ';
							
							$link3 = '<A href="javascript:newxy2(';
							$link3 .= "'etiqueta_tst_3.php?dd1=".$etiqu[$r][1]."',800,300);";
							$link3 .= '">Imprimir ';					
		
							$link4 = '<A href="javascript:newxy2(';
							$link4 .= "'etiqueta_tst_5.php?dd1=".$etiqu[$r][1]."',800,300);";
							$link4 .= '">Imprimir ';					
		
		                    $link5 = '<A href="javascript:newxy2(';
		                    $link5 .= "'etiqueta_tst_6.php?dd1=".$etiqu[$r][1]."',800,300);";
		                    $link5 .= '">Imprimir ';                    
		
							$link6 = '<A href="javascript:newxy2(';
		                    $link6 .= "'etiqueta_tst_7.php?dd1=".$etiqu[$r][1]."',800,300);";
		                    $link6 .= '">Imprimir ';                    
	                    }
					
					echo '<TR>';
					if ($nloja=='G')
					{
						echo '<TD align="center" class="tabela01">';
						echo $link7;
						echo $etiqu[$r][0];
					}
					if ($nloja=='U')
					{
						echo '<TD align="center" class="tabela01">';
						echo $link8;
						echo $etiqu[$r][0];
					}
					
					if( $nloja=='M' or
						$nloja=='O' or
						$nloja=='S' or
						$nloja=='E'){	
						echo '<TD align="center" class="tabela01">';
						echo $link;
						echo $etiqu[$r][0];					
	
						echo '<TD align="center" class="tabela01">';
						echo $link2;
						echo $etiqu[$r][0];	
						
						echo '<TD align="center" class="tabela01">';
						echo $link3;
						echo $etiqu[$r][0];									
	
						echo '<TD align="center" class="tabela01">';
						echo $link4;
						echo $etiqu[$r][0];
						
	                    echo '<TD align="center" class="tabela01">';
	                    echo $link5;
	                    echo $etiqu[$r][0];	
						
						echo '<TD align="center" class="tabela01">';
	                    echo $link6;
	                    echo $etiqu[$r][0];
					}
					echo '<TD class="tabela01">';
					echo $etiqu[$r][1];					
				}
			echo '</table>';
			
			if ($perfil->valid('#MST'))
    		{
				$ti = '<A href="javascript:newxy2(';
				$ti .= "'etiqueta_tst_10.php?dd1=".$etiqu[$r][1]."',800,300);";
				$ti .= '">Não usar, somente TI ';
				echo $ti;
			}
			}
		?>
	</TR>
	</table>
</center>
<?
echo $hd->foot();
?>