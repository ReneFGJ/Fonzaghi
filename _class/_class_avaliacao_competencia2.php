<?php

    /**
     * Sistema de avaliação de competências
     * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package avaliacao
     * @subpackage classe
    */

require("../db_drh.php");

class avaliacao
{
	//propriedades
	var $nome;
	var $cargo;
	var $avaliador;
	var $competencia;
	var $descricao;
	//métodos
	/*Zera toda a agenda de avaliacao, atualizando a data de final de avaliação*/
	function zera_agenda_avaliacao()
	{
		$ano = date('Y');
		$mes= date('m');			
		$diasmes = date('t', mktime(0,0,0,$mes,1,$ano)); 	
		
		$limite = $ano.$mes.$diasmes;
		
		$sql='update aval_avaliacoes set  aa_pagina=1, aa_status=\'A\', aa_limite='.$limite;
		$rlt = db_query($sql);
		
		return('');
	}
	
	function dados_competencia()
	{
		$sql = 'select * from aval_competencias';
		
		$rlt = db_query($sql);
		
		while ($line = db_read($rlt)){
		$cont++;	
		$descricao[$cont][1]=$line['ac_descricao'];
		$descricao[$cont][2]=$line['ac_conceito'];
		}
		return array($descricao);	
	}
	function total_competencia(){
		$sql = 'select count(*) from aval_competencias where ac_ativo=1';
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$total = $line['count'];
		
		return($total);
	}
	function avaliacao_grupo($avaliador='',$competencia=''){
				
			global $acao,$dd,$avaliador,$base_name,$base_host,$base_user, $tt_competencias;	
			$acao = $_GET['acao'];
			$valida = array('','','','','','','','','','','','','','','','','','','','','');
			$ano = date("Y");
			$mes = date("m");
			$comp=round($dd[2]); 
			$tt_competencias = $this->total_competencia();
			if ($comp == 0) { $comp = 1; }
			if ($comp == $tt_competencias+1) { $comp = $tt_competencias; }
			
			$competencia = $this->numtochar($competencia);	
			list ($descricao) = $this->dados_competencia() ;
			
			/* Montagem da tela de saida */
			$sx .= '<input type="hidden" name="dd1" id="dd1" value="'.$avaliador.'">';
			$sx .= '<input type="hidden" name="dd2" id="dd2" value="'.$competencia.'" >';
			
			$linka = '<A HREF="'.page().'?dd1='.$dd[1].'&dd2='.(round($comp)-1).'&acao=next">';
			$linkp = '<A HREF="'.page().'?dd1='.$dd[1].'&dd2='.(round($comp)+1).'&acao=next">';
		
			$sx .= '<form method="get" action="'.page().'" ><table align="center" width="900" cellspacing=0 cellpadding=0>';
			$sx .= '<TR>';
			$sx .= '<TD colspan=11 class="tabela00" style="background-color:#5188C6"><font color="#FFFFFF">'.$comp.' - '.$descricao[$comp][1].'</font>';
			$sx .= '</TR>';
			$sx .= '<TR>';
			$sx .= '<TD  height=90 style="background-color:#FFFFFF" class="lt1">'.$descricao[$comp][2];
			$sx .= '<TD  height=90 colspan=10 style="background-color:#FFFFFF"><img src="../img/competencia'.$comp.'.jpg"';
			$sx .= '<TR '.coluna().'>';
			
			$sx .= '<TR>';
			$sx .= '<TD class="tabela00">'.$linka.'<img src="../img/icone_arrow_calender_left.png" height="20" border=0></A>';
			$sx .= '<TD colspan=9  class="tabela00" >';
			$sx .= '<TD class="tabela00">'.$linkp.'<img src="../img/icone_arrow_calender_right.png" height="20" border=0 align="right"></A>';
			$sx .= '<TR '.coluna().'>';
			
			$sx .= '<tr><th>Colaborador</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th></tr>';	
			
			$sx .= $this->monta_tela($avaliador, $competencia);
		
			$sx .='<tr><td align=right colspan=11><input type="submit" name="acao" value="Salvar">';
			$sx .='<input type="hidden" name="dd1" id="dd1" value="'.$avaliador.'">';
			$sx .='<input type="hidden" name="dd2" id="dd2" value="'.$competencia.'" >';		
			$sx .='</td></tr></table><table align="center" width="700">';
			$sx .='<tr><td style="background-color:#FFE0E0">&nbsp&nbsp&nbsp</td><td>Sofrível - 1, 2, 3 e 4</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#FFFFE0">&nbsp&nbsp&nbsp</td><td>Regular - 5 e 6</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#E0FFE0">&nbsp&nbsp&nbsp</td><td>Bom - 7 e 8</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#E0E0FF">&nbsp&nbsp&nbsp</td><td>Ótimo - 9 e 10</td></font></tr>';
			$sx .='</tr></table>'; 
			$sx .= '</A></form>';
			  
			/* Salva */
			if ($acao=='Salvar')
				{
				if ($competencia>$tt_competencias) {$competencia=$tt_competencias;}	
				$this->gravar($avaliador, $competencia, $ano, $mes);
				}
		
			return($sx);
	}
	/*Função para acrecentar ZEROS na frente de um numero para que fique com três casas*/
	function numtochar($num){
		
		$competencia='0000'.$num;
		$competencia=substr($competencia,-3);
		
		return($competencia);
	}
	function competencias_pendentes(){
		
		$sql = '';
		$rlt = db_query($sql);
		while ($line = db_read($rlt)){
			
		}
		
	}
	function gravar($avaliador,$competencia,$ano,$mes)
	{
			$competencia = $this->numtochar($competencia);	
			/* Deleta dados anteriores */
				$sql = "delete from aval_dados 
								where avd_avaliador = '$avaliador'
								and avd_criterio = '$competencia'
								and avd_ref_ano =  '$ano'
								and avd_ref_mes = '$mes'
				";
				
				$xrlt = db_query($sql);
				$sql = "select * from aval_avaliacoes 
						where aa_avaliador = '$avaliador'
				 ";
					
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
					$funcionario = $line['aa_funcionario'];
					$data = date("Ymd");
					$vlr = round($_GET['group_'.round($funcionario)]);
					if ($vlr > 0)
						{
						$sql = "insert into aval_dados 
								(
								avd_funcionario, avd_avaliador, avd_criterio,
								avd_nota, avd_data, avd_ref_ano,
								avd_ref_mes
								) values (
								'$funcionario','$avaliador','$competencia',
								'$vlr',$data,'$ano',
								'$mes')
						";
						
						$xrlt = db_query($sql);
						
						}
					}
				$this->atualiza_avaliacao();
		return(1);
	}
	function monta_tela($avaliador,$competencia )
	{
			
				/*Query para obter funcionarios subordinados ao gestor*/
			$sql = "select * from aval_avaliacoes 
					where aa_avaliador ='$avaliador'
					";
			/* Execução */
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
				$competencia=$this->numtochar($competencia);
					
				$funcionario=$line['aa_funcionario'];
				/*Query para verificar se o funcionario já possui note e carregar na tela já selecionado*/
				$sql2 = "select * from aval_dados
						where avd_funcionario='$funcionario' and
							  avd_criterio='$competencia'  
						 ";	
				
				$rlt2 = db_query($sql2);
				$line2 = db_read($rlt2);			
				$valida[$funcionario] = $line2['avd_nota'];
				
				}
				
				/*Query para obter funcionarios subordinados ao gestor, com status de avaliação pendente(P)*/	
				$sql = "select * from aval_avaliacoes 
				where aa_avaliador ='$avaliador' and
					  aa_status = 'P'
				";
			
				/* Execução */
				$rlt = db_query($sql);
		
				while ($line = db_read($rlt))
				{
				/*Obtem o nome*/
				$funcionario=$line['aa_funcionario'];
				$this->cl_drh();		
				$this->op_fghi();
				$nome=trim($this->dados_funcionario($funcionario));
				$this->cl_fghi();
				$this->op_drh();									
												
				$vl = array('','','','','','','','','','','','','','','','','','','','','');
				
				/*Muda para checked os funcionarios que já tenham nota*/	
				if($valida[$funcionario]!='')
					{
					 $vl[$valida[$funcionario]] = 'Checked';
					 
					 $vlr=1;
					 
					}else {
							if (strlen($acao) > 0)
								{
								$vlr = round($_GET['group_'.round($funcionario)]);
								if ($vlr > 0) 
									{
							 			$vl[$vlr] = 'Checked'; 
									}
								}
							}
				$font = '<font>';
				if ((strlen($acao) > 0) and ($vlr==0)) 
					{
					 $font = '<font color="red">'; 
					 $ok=0;
					}
				$tx .= '<tr><td>'.$font.$nome.'</font></td>';
				$sty = 'class="tabela01" style="background-color:';
				$sty2 = '"><input type="radio" name="group_'.round($funcionario);
				
				$tx .= '<td '.$sty.'#FFE0E0'.$sty2.'" value="1"'.$vl[1].'></td>';
				$tx .= '<td '.$sty.'#FFE0E0'.$sty2.'" value="2"'.$vl[2].'></td>';
				$tx .= '<td '.$sty.'#FFE0E0'.$sty2.'" value="3"'.$vl[3].'></td>';
				$tx .= '<td '.$sty.'#FFE0E0'.$sty2.'" value="4"'.$vl[4].'></td>';
				$tx .= '<td '.$sty.'#FFFFE0'.$sty2.'" value="5"'.$vl[5].'></td>';
				$tx .= '<td '.$sty.'#FFFFE0'.$sty2.'" value="6"'.$vl[6].'></td>';
				$tx .= '<td '.$sty.'#E0FFE0'.$sty2.'" value="7"'.$vl[7].'></td>';
				$tx .= '<td '.$sty.'#E0FFE0'.$sty2.'" value="8"'.$vl[8].'></td>';
				$tx .= '<td '.$sty.'#E0E0FF'.$sty2.'" value="9"'.$vl[9].'></td>';
				$tx .= '<td '.$sty.'#E0E0FF'.$sty2.'" value="10"'.$vl[10].'></td></tr>';				 
			}
		
		return($tx);
	}
	/*Gera a tela para autoavaliacao com todas as competências*/
	function avaliacao_individual($funcionario,$avaliador)
	{
			global $acao;
			$acao = $_GET['acao'];
			$valida = array('','','','','','','','','','','','','','','','','','','','','');
			
			
			/* Salva */
			$ano = date("Y");
			$mes = date("m");
			
						
			if (strlen($acao) > 0)
				{
				/* Deleta dados anteriores */
				$sql = "delete from aval_dados 
								where avd_funcionario = '$funcionario' 
								and avd_avaliador = '$avaliador'
								and avd_ref_ano =  '$ano'
								and avd_ref_mes = '$mes'
				";
			
				$xrlt = db_query($sql);
									
				/* Deleta dados anteriores */	
				$sql = "select * from aval_competencias 
							order by ac_ordem ";	
				
				$rlt = db_query($sql);
				//print_r($_GET);
				while ($line = db_read($rlt))
				{
					$competencia = $line['ac_codigo'];
					$data = date("Ymd");
					
					$vlr = round($_GET['group_'.$line['ac_codigo']]);
					//echo '<BR>-->'.$vlr;
					if ($vlr > 0)
					{
					$sql = "insert into aval_dados 
							(
							avd_funcionario, avd_avaliador, avd_criterio,
							avd_nota, avd_data, avd_ref_ano,
							avd_ref_mes
							) values (
							'$funcionario','$avaliador','$competencia',
							'$vlr',$data,'$ano',
							'$mes')
					";
					$xrlt = db_query($sql);
					}
				}
				
				$this->atualiza_avaliacao();
				
				}
		
				  
			/* Query */
			$sql = "select * from aval_competencias 
					left join aval_dados on ac_codigo=avd_criterio
					where avd_funcionario = '$funcionario' 
								and avd_avaliador = '$avaliador'
								and avd_ref_ano =  '$ano'
								and avd_ref_mes = '$mes'
					";
			
			/* Execução */
			$rlt = db_query($sql);

			while ($line = db_read($rlt))
			{
				$valida[$line['ac_codigo']] = $line['avd_nota'];
		
			}
			
			/* Query */
			$sql = "select * from aval_competencias order by ac_ordem ";
			
			/* Execução */
			$rlt = db_query($sql);
	
			
			/* Montagem da tela de saida */
			$sx .= '<form method="get" action="'.page().'"><table><table align="center" width="700"><tr><td>';
			$sx .= '<input type="hidden" name="dd1" id="dd1" value="'.$avaliador.'" size="9" maxlength="7"  >';
			$sx .= '<input type="hidden" name="dd2" id="dd2" value="'.$funcionario.'" size="9" maxlength="7"  >';
			$sx .= '<div><table align="right"><tr><th>Competências</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th></tr>';	
			$desc = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
			$ok=1;
			if (strlen($acao)==0) {$ok=0;}
			
			while ($line = db_read($rlt))
			{
				$r =$line['ac_codigo'];
				$vl = array('','','','','','','','','','','','','','','','','','','','','');
				$descricao = trim($line['ac_conceito']);
				$competencia = trim($line['ac_descricao']);
				$ordem = trim($line['ac_ordem']);
				if($valida[$r]!='')
				{
					 $vl[$valida[$r]] = 'Checked';
					 $vlr=1;
					 
				}else {
					if (strlen($acao) > 0)
					{
						$vlr = round($_GET['group_'.$r]);
						if ($vlr > 0) 
						{
							 $vl[$vlr] = 'Checked'; 
						}
						
					}
				}
				
				$font = '<font>';
				if ((strlen($acao) > 0) and ($vlr==0)) { $font = '<font color="red">'; $ok=0;}
				$sx .= '<tr>';
				$sx .= '<td class="tabela00" align="left">'; 
				$tips = tips($font.$competencia.'</font>',$descricao);
				$sx .= $tips;
				$sx .= '</td>';
				$sx .= '
				<td class="tabela01" style="background-color:#FFE0E0"><input type="radio" name="group_'.$r.'" value="1" '.$vl[1].'></td> 
				<td class="tabela01" style="background-color:#FFE0E0"><input type="radio" name="group_'.$r.'" value="2" '.$vl[2].'></td>
				<td class="tabela01" style="background-color:#FFE0E0"><input type="radio" name="group_'.$r.'" value="3" '.$vl[3].'></td>
				<td class="tabela01" style="background-color:#FFE0E0"><input type="radio" name="group_'.$r.'" value="4" '.$vl[4].'></td>
				<td class="tabela01" style="background-color:#FFFFE0"><input type="radio" name="group_'.$r.'" value="5" '.$vl[5].'></td>
				<td class="tabela01" style="background-color:#FFFFE0"><input type="radio" name="group_'.$r.'" value="6" '.$vl[6].'></td>
				<td class="tabela01" style="background-color:#E0FFE0"><input type="radio" name="group_'.$r.'" value="7" '.$vl[7].'></td>
				<td class="tabela01" style="background-color:#E0FFE0"><input type="radio" name="group_'.$r.'" value="8" '.$vl[8].'></td>
				<td class="tabela01" style="background-color:#E0E0FF"><input type="radio" name="group_'.$r.'" value="9" '.$vl[9].'></td>
				<td class="tabela01" style="background-color:#E0E0FF"><input type="radio" name="group_'.$r.'" value="10" '.$vl[10].'></td>
				</tr>';
				$sx .='';
			}
						
			$sx .= '</td><td>';	
			$sx .= '<input type="submit" name="acao" value=" Salvar ">';
			while ($a <= $r+1) {
			$sx .= $desc[$a];
			$a++;
			}   
			$sx .='</td></tr></table><tr></tr>';
			$sx .='<tr><table align="center" width="700" >';
			$sx .='<tr><td style="background-color:#FFE0E0">&nbsp&nbsp&nbsp</td><td>Sofrível</td><td>1</td><td>2</td><td>3</td><td>4</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#FFFFE0">&nbsp&nbsp&nbsp</td><td>Regular</td><td>5</td><td>6</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#E0FFE0">&nbsp&nbsp&nbsp</td><td>Bom</td><td>7</td><td>8</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#E0E0FF">&nbsp&nbsp&nbsp</td><td>Ótimo</td><td>9</td><td>10</td></font></tr>';
			$sx .='</table></tr></table>'; 
			
			$sx .= '</form>';
			
			if ($ok==1) {
				$sx = '<H3>Avaliação concluída</H3> ';
				$this->atualiza_avaliacao();
			}
			return($sx);

	}
	function dados_funcionario($funcionario){
			
			  
			/* Query */
			$sql = "select * from usuario where us_cracha= '$funcionario'";
			
			/* Execução */
			$rlt = db_query($sql);
			
			
			if ($line = db_read($rlt)) {
			$nome=$line['us_nomecompleto'];
			
			return($nome);
			
			} else {
					return ("Funcionario não cadastrado.");
			}
		
	}
	function dados_func($funcionario){
			
			  
			/* Query */
			$sql = "select * from usuario where us_cracha= '$funcionario'";
			
			/* Execução */
			$rlt = db_query($sql);
			
			 echo "3";
			 /* Montagem da tela de saida */
			if ($line = db_read($rlt)) {
			$sx = '<table width="98%" align="center">';
			$sx .= '<TR '.coluna().'>';
			$sx .= '<TD>'.$funcionario.' - '.$line['us_nomecompleto'].'</TD></TR>';
			$sx .= '<TR '.coluna().'>';
			$sx .= '<TD>'.$line['us_cargo'].'</TD></TR>';
			
			return($sx);
			
			} else {
					return ("Funcionario não cadastrado.");
			}
		
	}
	function atualiza_avaliacao()
		{
			
		/*Atualiza aval_avaliacoes --------------------*/	
							
		$sql= 	'select avd_funcionario, avd_avaliador, avd_ref_ano,
					avd_ref_mes 
			from aval_dados
			group by avd_funcionario, avd_avaliador, avd_ref_ano, 
					 avd_ref_mes';
					 
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) 
		{
			$funcionario=$line['avd_funcionario'];
			$avaliador=$line['avd_avaliador'];
			$mes=$line['avd_ref_mes'];
			$ano=$line['avd_ref_ano'];
			$diasmes = date('t', mktime(0,0,0,$mes,1,$ano));
			
			$sql2 = 'select * from aval_avaliacoes 
					 where aa_funcionario= \''.$funcionario.'\' and 
					 	   aa_avaliador= \''.$avaliador.'\' and 
					 	   aa_limite<\''.$ano.$mes.'99\' and 
					 	   aa_limite>\''.$ano.$mes.'00\'';
			
			$rlt2 = db_query($sql2);
			$line2 = db_read($rlt2);
			$dx = strlen($line2['id_aa']);
			while ($dx==0) {
				$dx++;
				$sql3 ='insert into aval_avaliacoes 
							(
							aa_funcionario, aa_status, aa_limite,
							aa_avaliador,aa_pagina
							) values (
							\''.$funcionario.'\',\'\',\''.$ano.$mes.$diasmes.'\',
							\''.$avaliador.'\',\'1\')';
				$rlt3 = db_query($sql3);
			}			   
			
		}	
								
					
				
/*----------------------------*/			
		
		/*Query - total de competencias cadastradas*/
		$sql3 = 'select count(*) from aval_competencias';
		
		/* Execução */
		$rlt3 = db_query($sql3);
		
		while ($line3 = db_read($rlt3)) 
		{
			$totcomp = $line3['count'];
		}	
		
		/*Query - avaliações cadastradas*/
		$sql = 'select * from aval_avaliacoes';
		
		/* Execução */
		$rlt = db_query($sql);
		
		while ($line = db_read($rlt)) 
		{
		 	$data=$line['aa_limite'];
		 	$ano = substr($line['aa_limite'],0,4) ;
		 	$mes = substr($line['aa_limite'],4,2) ;
		 	$avaliador = $line['aa_avaliador'];
		 	$funcionario = $line['aa_funcionario'];
				
			/*Query - verifica se avaliação esta concluída*/
			$sql2 = 'select count(avd_funcionario) from aval_dados
				 	where avd_avaliador = \''.$avaliador.'\' and
      			 	avd_funcionario = \''.$funcionario.'\' and
        		 	avd_ref_ano = \''.$ano.'\' and
         		 	avd_ref_mes = \''.$mes.'\'';
		
			/* Execução */
			$rlt2 = db_query($sql2);
				
			while ($line2 = db_read($rlt2)) 
			{	
				$total = $line2['count'];
				if ($total==$totcomp) {
					
					$sql4= 'update aval_avaliacoes set aa_status=\'B\' 
							where	aa_avaliador = \''.$avaliador.'\' and
      			 					aa_funcionario = \''.$funcionario.'\' and
									aa_limite = \''.$data.'\'';
					$rlt2 = db_query($sql4);
					$sx= 'B';
				}else{
					
					$sql4= 'update aval_avaliacoes set aa_status=\'P\' 
							where	aa_avaliador = \''.$avaliador.'\' and
      			 					aa_funcionario = \''.$funcionario.'\' and
									aa_limite = \''.$data.'\'';
					$rlt2 = db_query($sql4);
					$sx= 'P';
				
				}
			}
		}

	
	
		
		return('');
	}
	function cargo_gestor($func=''){
	
		$sql = "select * from aval_gestor_cargo 
				left join aval_cargos on aagc_cargo = acg_codigo
				where aagc_ativo = 1
				order by aagc_gestor, acg_cargo,id_acg
				";
					
		$rlt = db_query($sql);
		
		$xcargo = '';
		$sx = '<TABLE align="center" width="95%" cellpadding="0" cellspacing="0" class="lt0" border="0">';
		$sx .= '<TR><script language="javascript1.2">
      				img5=new Image();
					img5.src="../img/bt_clean.png";
					img6=new Image();
					img6.src="../img/bt_clean_on.png";
			
					img3=new Image();
					img3.src="../img/bt_busca.png";
					img4=new Image();
					img4.src="../img/bt_busca_on.png";
			
					img1=new Image();
					img1.src="../img/bt_novo.png";
					img2=new Image();
					img2.src="../img/bt_novo_on.png";
    			</script></TR>';
		
		$sx .='<TR><TD  colspan=2><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
		$sx .='<TR><TD> <td align="right"><a onmouseout="document.images[\'novo\'].src=img1.src;" onmouseover="document.images[\'novo\'].src=img2.src;" alt="Titulo" href="aval_cargo_gestor_ed.php" title="Novo Registro"><img border="0" name="novo" src="http://localhost/projetos/include/img/bt_novo.png"></img></a></td></TR>';
		$sx .='<TR><TD colspan=2><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
		$sx .='<TR><TD></BR></TR>';
		$sx .='<TR bgcolor="#c0c0c0" valign="top" align="left" class="lt0"><TD colspan=2 ><B>&nbsp&nbsp&nbspAvaliador</B></TD></TR>';
		
		
		while ($line = db_read($rlt))
		{		
			$gestor = $line['aagc_gestor'];
			if ($gestor != $xgestor)
			{
				$tt++;
				$sx .= '<TR bgcolor="#F5F5F0" valign="top" align="left" class="lt0">';
				$sx .= '<TD colspan=2><font size="3px">';
			 	$idf = round($line['aagc_gestor']);
				$sx .= ucwords(strtolower($func[$idf])).'</font>';
			}
			$sx .= '<TR bgcolor="#F0F0F0"><TD>&nbsp&nbsp&nbsp-'.ucwords(strtolower($line['acg_cargo']));
			$sx .= '<td width="20" align="right"><a href="aval_cargo_gestor_ed.php?dd0='.$line['id_aagc'].'"><img width="20" border="0" height="19" alt="" src="../img/icone_editar.gif"></img></a></td>';
			$xgestor = $gestor;
			
		}
		$sx .= '<TR><TD><b>Total de '.$tt.' avaliadores.</b></TD></TR>';
		$sx .= '</table>';

		return($sx);
	}
 	function cp_competencias(){
 		$cp = array();
		array_push($cp,array('$H8','id_ac','',false,True));
		array_push($cp,array('$S60','ac_descricao','Competência',false,True));
		array_push($cp,array('$T80:5','ac_conceito','Descrição',False,True));
		array_push($cp,array('$O 1:Ativo&0:Não ativo','ac_ativo','Status',False,True));
		array_push($cp,array('$S2','ac_ordem','Ordem',false,True));
		array_push($cp,array('$H8','ac_codigo','',false,True));
		
		return($cp);
 	}
	function row_competencias(){
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "aval_competencias";
		$tabela = "aval_competencias";
		$label = "Cadastro de Competências";
		/* Páginas para Editar */
		$http_edit = 'aval_competencias_ed.php'; 
		$offset = 20;
		$order  = "ac_ordem";
		
		$cdf = array('id_ac','ac_codigo','ac_descricao','ac_conceito','ac_ativo','ac_ordem');
		$cdm = array('ID','Ordem','Descrição','Conceito','Status','Codigo');
		$masc = array('','','','','','','','','','','','','');
		return(True);	
	}
	function updatex_competencias()
			{
			$dx1 = 'ac_codigo';
			$dx2 = 'ac';
			$dx3 = 3;
			$sql = "update aval_competencias set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
			}		
	function row_cargo()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "aval_cargos";
		$tabela = "aval_cargos";
		$label = "Cadastro de Cargos";
		/* Páginas para Editar */
		$http_edit = 'aval_cargos_ed.php'; 
		$offset = 20;
		$order  = "acg_descricao";
		
		$cdf = array('id_acg','acg_codigo','acg_cargo','acg_descricao','acg_ativo');
		$cdm = array('ID','Cargo','Descrição','Status','Codigo');
		$masc = array('','','','','','','','','','','','','');
		return(True);
		}
	function cp_cargo()
		{
			$cp = array();
			array_push($cp,array('$H8','id_acg','',false,True));
			array_push($cp,array('$S60','acg_cargo','Nome do Cargo',false,True));
			array_push($cp,array('$S80','acg_descricao','Descrição do Cargo',false,True));
			array_push($cp,array('$O 1:Ativo&0:Não ativo','acg_ativo','Status',False,True));
			array_push($cp,array('$H8','acg_codigo','',false,True));
			return($cp);
		}
	function updatex_cargo()
		{
			$dx1 = 'acg_codigo';
			$dx2 = 'acg';
			$dx3 = 4;
			$sql = "update aval_cargos set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			print_r($sql);
			return(1);
		}
	function row_gestor()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "aval_gestor_cargo";
		$tabela = "aval_gestor_cargo";
		$label = "Cadastro de Gestor";
		/* Páginas para Editar */
		$http_edit = 'aval_gestor_cargo_ed.php'; 
		$offset = 20;
		$order  = "aagc_cargo";
		
		$cdf = array('id_aagc','aagc_cargo','aagc_gestor','aagc_ativo');
		$cdm = array('ID','Código','Cargo','Gestor','Status');
		$masc = array('','','','','','','','','');
		return(True);
		}
	function cp_gestor()
		{
			global $user;
			$op = $user->lista_funcionarios_option();
			$cp = array();
			array_push($cp,array('$H8','id_aagc','',false,True));
			array_push($cp,array('$Q acg_cargo:acg_codigo:select acg_codigo,acg_cargo,acg_descricao from aval_cargos where acg_ativo=1 group by acg_cargo,acg_descricao,acg_codigo order by acg_cargo','aagc_cargo','Cargo',False,True));
			//array_push($cp,array('$S7','aagc_cargo','Cargo',false,True));
			array_push($cp,array('$O '.$op,'aagc_gestor','Gestor do departamento',false,True));
			array_push($cp,array('$O 1:Ativo&0:Não ativo','aagc_ativo','Status',True,True));
			return($cp);
		}
	function cp_cargo_gestor()
		{
			global $user;
			$op = $user->lista_funcionarios_option();
			$cp = array();
			array_push($cp,array('$H8','id_aagc','',false,True));
			array_push($cp,array('$Q acg_cargo:acg_codigo:select acg_codigo,acg_cargo,acg_descricao from aval_cargos where acg_ativo=1 group by acg_cargo,acg_descricao,acg_codigo order by acg_cargo','aagc_cargo','Cargo',False,True));
			//array_push($cp,array('$S7','aagc_cargo','Cargo',false,True));
			array_push($cp,array('$O '.$op,'aagc_gestor','Gestor do departamento',false,True));
			array_push($cp,array('$O 1:Ativo&0:Não ativo','aagc_ativo','Status',True,True));
			return($cp);
		}	
	function updatex_gestor()
			{
			$dx1 = 'id_aagc';
			$dx2 = 'aagc';
			$dx3 = 4;
			$sql = "update aval_gestor_cargo set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
			}				
	function op_fghi(){
		$dbconn = pg_connect("host=localhost port=5432 user=postgres dbname=FGHI password=admin");
		return($dbconn);
	}
	function op_drh(){
		$dbconn = pg_connect("host=localhost port=5432 user=postgres dbname=DRH password=admin");
		return($dbconn);
	}
	function cl_fghi(){
		$dbconn = pg_connect("host=localhost port=5432 user=postgres dbname=FGHI password=admin");
		$dbconn = pg_close($dbconn);	
		return($dbconn);
	}
	function cl_drh(){
		$dbconn = pg_connect("host=localhost port=5432 user=postgres dbname=DRH password=admin");
		$dbconn = pg_close($dbconn);	
		return($dbconn);
	}
	function gestor_cargo($func){
		$sql = "select * from aval_cargos 
				left join aval_gestor_cargo on aagc_cargo = acg_codigo
				where aagc_ativo = 1
				order by acg_cargo, acg_codigo";
				
		$rlt = db_query($sql);
	
		$xcargo = '';
		$sx = '<TABLE align="center" width="95%" cellpadding="0" cellspacing="0" class="lt0" border="0">';
		$sx .= '<TR><script language="javascript1.2">
      				img5=new Image();
					img5.src="../img/bt_clean.png";
					img6=new Image();
					img6.src="../img/bt_clean_on.png";
			
					img3=new Image();
					img3.src="../img/bt_busca.png";
					img4=new Image();
					img4.src="../img/bt_busca_on.png";
			
					img1=new Image();
					img1.src="../img/bt_novo.png";
					img2=new Image();
					img2.src="../img/bt_novo_on.png";
    			</script></TR>';
		
		$sx .='<TR><TD  colspan=2><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
		$sx .='<TR><TD> <td align="right"><a onmouseout="document.images[\'novo\'].src=img1.src;" onmouseover="document.images[\'novo\'].src=img2.src;" alt="Titulo" href="aval_gestor_cargo_ed.php" title="Novo Registro"><img border="0" name="novo" src="http://localhost/projetos/include/img/bt_novo.png"></img></a></td></TR>';
		$sx .='<TR><TD colspan=2><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
		$sx .='<TR><TD></BR></TR>';
		
		while ($line = db_read($rlt))
		{
			$cargo = $line['acg_codigo'];
			
			if ($cargo != $xcargo)
			{	$tt++;
				$sx .= '<TR style="background-color:#BCBEC0"><TD colspan=2><font size="3px">'.$line['acg_cargo'].'</font>';
				$xcargo = $cargo;
			}

			$sx .= '<TR>';
			$sx .= '<TD>&nbsp&nbsp&nbsp';
			$idf = round($line['aagc_gestor']);
			$sx .= ucwords(strtolower($func[$idf]));
			$sx .= '<td width="20"><a href="aval_gestor_cargo_ed.php?dd0='.$line['id_aagc'].'"><img width="20" border="0" height="19" alt="" src="../img/icone_editar.gif"></img></a></td>';
		}
		$sx .= '<TR><TD><b>Total de '.$tt.' cargos.</b></TD></TR>';
		$sx .= '</table>';

		return($sx);
	}
	function grafico_dinamico($grs,$modelo,$titulo)
		{		
			$sx =   '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      				google.load(\'visualization\', \'1\', {packages: [\'charteditor\']});
    				</script>
    				<script type="text/javascript">
    				var wrapper;
		    
    				function init() {
      				wrapper = new google.visualization.ChartWrapper({
        			chartType: \''.$modelo.'\',
          			dataTable: ['.$grs.'],
          			options: {\'title\': \''.$titulo.'\'},
          			containerId: \'vis_div\'
      				});
      				wrapper.draw();
    				}
    
    				function openEditor() {
      				// Handler for the "Open Editor" button.
      				var editor = new google.visualization.ChartEditor();
      				google.visualization.events.addListener(editor, \'ok\',
        			function() {
          			wrapper = editor.getChartWrapper();
          			wrapper.draw(document.getElementById(\'visualization\'));
      				});
      				editor.openDialog(wrapper);
    				}
    
			    	google.setOnLoadCallback(init);
    
    				</script>
  					</head>
  					<body style="font-family: Arial;border: 0 none;">
    				<input type=\'button\' onclick=\'openEditor()\' value=\'Gerar Gráfico\'>
    				<center><div id=\'visualization\' style="width:95%;height:400px"></center>
  					</body> ';
			return ($sx);
			
	}

			
}