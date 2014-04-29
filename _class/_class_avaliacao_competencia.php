<?php

    /**
     * Sistema de avaliação de competências
     * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v.0.14.18
     * @package avaliacao
     * @subpackage classe
    */

require_once("../db_drh.php");
//require_once('_class_funcionario.php');
//require_once('_class_cargos.php');
class avaliacao
{
	//propriedades
	var $nome;
	var $cargo;
	var $avaliador;
	var $competencia;
	var $descricao;
    var $pag;
    var $car;
    var $loja;
    var $op_lojas;
    var $ttcomp;
    var $lista_comp;
    var $lista_nota;
    var $lista_avaliador;
    var $lista_nota_auto;
	var $lista_obs;
    var $ttavaliadores;
	var $indice_comp;
	var $mes;
	var $ano;
	//métodos
	
	function historico($cracha)
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$dd;
        require("../db_206_rh.php");
		$sql = "select * from aval_historico
				where avh_funcionario='".$cracha."' and
					  avh_status='1'
				order by id_avh desc , avh_data 
		";
		$rlt = db_query($sql);
		$sx = '<center><div id="aval_hist_nv" href="#" style="display: inline;"><a class="botao-geral" height="50px" >Novo</a></div>
						<div id="aval_hist_save" href="#" style="display: none;"><a class="botao-geral" height="50px">Salvar</a></div>
						';
		$sx .= '<br><br><div style="overflow:scroll; height:600px;">';
		$this->js .= '<script>';
		while ($line = db_read($rlt))
		{
			$id = $line['id_avh'];
			$sx .= '<div id="id_1tbhist'.$id.'" class="botao-geral" align="left">'.date('d-m-Y', strtotime($line['avh_data'])).' - '.$line['avh_log'].'<img id="id_hist'.$id.'" src="../ico/cancel.png" height="15" align="right"></div>';
			$sx .= '<div id="id_2tbhist'.$id.'" class="tabela01">'.$line['avh_observacao'].'</div><br>';
			
			$this->js .= '	$("#id_hist'.$id.'").click(function(){
									$("#id_1tbhist'.$id.'").hide();
									$("#id_2tbhist'.$id.'").hide();
									
									$.ajax({
										type: "POST",
										url: "aval_js.php",
										data: { dd0:"cancel", dd1:'.$id.'}
									}).done(function( data ) { $("#aval_hist_saved").html( data ); });

							})
			';						
		}
		$this->js .= '</script>';
		$sx .= '</div></center>';
		$this->js .= '<script>
								$("#aval_hist_nv").click(function(){
										$("#aval_hist_box").show();
										$("#aval_hist_nv").hide();
										$("#aval_hist_save").show();
									})
								$("#aval_hist_save").click(function(){
										$("#aval_hist_box").hide();
										$("#aval_hist_nv").show();
										$("#aval_hist_save").hide();
									
										/* Ajax Inicial */
									var aval_obs = $("#aval_hist_box1").val();
									alert(aval_obs); 
									$.ajax({
										type: "POST",
										url: "aval_js.php",
										data: { dd0:"save", dd1:"'.$dd[3].'", dd2:aval_obs }
									}).done(function( data ) { $("#aval_hist_saved").html( data ); });
										
								})
								</script>';
		return($sx);
	}
	function historico_save($funcionario,$obs)
	{
		global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
			  $avh_funcionario = $funcionario;
			  $avh_log_cracha = $user->user_cracha;
			  $avh_log = $user->user_log;
			  $avh_data = date('Ymd');
			  $avh_observacao = trim($obs);
  
		$sx = "insert into aval_historico
				( avh_funcionario, avh_log_cracha, avh_log,
				  avh_data, avh_observacao)
				values 
				('$avh_funcionario', '$avh_log_cracha', '$avh_log',
				 '$avh_data', '$avh_observacao')	  
		";
		$rlt = db_query($sx);
		
		return(1);
	}
	function historico_cancel($id)
	{
		global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
			  
		$sx = "update aval_historico 
				set avh_status='0'
				where id_avh=".$id."
			";
		$rlt = db_query($sx);
		
		return(1);
	}
	function historico_box()
	{
		$sx = '<center><div id="aval_hist_box" style="display: none;">
				<textarea id="aval_hist_box1" rows="5" cols="50"></textarea>		
				</div><div id="aval_hist_saved"></div>
				<center>';			
		return($sx);
	}
	function le_competencia($comp)
	{
		global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
		$sql = "select * from aval_competencia
				where ac_condigo='".$comp."'
		";
		$rlt = db_query($sql);
		if($line = db_read($rlt))
		{
			$sx = $line['ac_descricao'];
		}
		return($sx);
	}
	 /*Zera toda a agenda de avaliacao, atualizando a data de final de avaliação*/
	function zera_agenda_avaliacao()
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
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
	    global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
		$sql = 'select * from aval_competencias order by ac_ordem';
		
		$rlt = db_query($sql);
		
		while ($line = db_read($rlt)){
		$cont++;	
		$descricao[$cont][1]=$line['ac_descricao'];
		$descricao[$cont][2]=$line['ac_conceito'];
		}
		return array($descricao);	
	}
	
	function avaliacao_grupo($avaliador='',$competencia=''){
				
			global $acao,$dd,$avaliador,$base_name,$base_host,$base_user, $tt_competencias;	
		
			$acao = $_GET['acao'];
			$valida = array('','','','','','','','','','','','','','','','','','','','','');
			
			$ano = date("Y");
			$mes = date("m");
			$comp=round($dd[2]); 
			$this->carrega_indice_competencia(); 
			$tt_competencias = $this->ttcomp;
			
			if ($comp == 0) { $comp = 1; }
			if ($comp == $tt_competencias+1) { $comp = $tt_competencias; }
			
			$competencia = $this->numtochar($competencia);	
			list ($descricao) = $this->dados_competencia() ;
					
			/* Salva */
            if (($acao=='Salvar') or ($dd[20]=='Salvar'))
                {
                if ($competencia>$tt_competencias) 
                {
                	$competencia=$tt_competencias;
                } 
                $this->gravar($avaliador, $this->indice_comp[$dd[21]], $ano, $mes);
				}
        
			
			$linka = '<A HREF="'.page().'?dd1='.$dd[1].'&dd2='.(round($comp)-1).'&acao=next">';
			//$linkp = '<A HREF="'.page().'?dd1='.$dd[1].'&dd2='.(round($comp)+1).'&acao=next">';
		
			$sx .= '<form method="get" name="frm" id="frm" action="'.page().'" >
				<BR>
				<table align="center" width="98%" cellspacing=5 cellpadding=0 >
					<TR>
					<TD colspan=11 class="tabela00 bg_blue padding5" >
						<font color="#FFFFFF">
						'.$comp.' - '.$descricao[$comp][1].'
						</font>
					</TR>
					
					<TR><TD>&nbsp;
					
					';
			
			/* Informações sobre o critério */
			$sx .= '<TR valign="top" class="padding5">';
			$sx .= '<TD width="70%" height=120 style="background-color:#FFFFFF" class="lt3">'.$descricao[$comp][2];
			$sx .= '<TD width="30%"  height=120 colspan=10 style="background-color:#FFFFFF"><img src="../img/competencia'.$comp.'.jpg"';
			$sx .= '<TR '.coluna().'>';
			$sx .= '<TR>';
			 $txt='icone_arrow_calender_left.png';    
            if (1==$comp) {
            	$txt='transparente21.jpg';
			}
			$sx .= '<TD class="tabela00">    
			             <img src="../img/'.$txt.'" height="40" border=0 id="bt_prev"
			                 style="cursor: pointer;"
			             >';
			$sx .= '<TD colspan=9  class="tabela00" >';
		    $txt='icone_arrow_calender_right.png';    
            if (21==$comp) {
            	$txt='transparente21.jpg';
			}
			$sx .= '<TD class="tabela00">
			             <img src="../img/'.$txt.'" 
			                 style="cursor: pointer;"
			                 id="bt_next" height="40" 
			                 border=0 align="right">';
			$sx .= '<TR '.coluna().'>';
			$sx .= '<tr><th>Colaborador</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th></tr>';
            
            /* Select */	
			$sx .= $this->monta_tela($avaliador, $competencia);
            
			$sx .='<tr><td align=right colspan=11><input type="submit" id="acao" name="acao" value="Salvar">';
			$sx .='<input type="hidden" name="dd1" id="dd1" value="'.$avaliador.'">';
			$sx .='<input type="hidden" name="dd2" id="dd2" value="'.round($comp).'" >';
            
            $sx .='<input type="hidden" name="dd20" id="dd20" value="" >';
            $sx .='<input type="hidden" name="dd21" id="dd21" value="'.round($comp).'" >';	
            	
			$sx .='</td></tr></table><table align="center" width="700">';
			$sx .='<tr><td style="background-color:#FFE0E0">&nbsp&nbsp&nbsp</td><td>Sofrível - 1, 2, 3 e 4</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#FFFFE0">&nbsp&nbsp&nbsp</td><td>Regular - 5 e 6</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#E0FFE0">&nbsp&nbsp&nbsp</td><td>Bom - 7 e 8</td><td>&nbsp&nbsp</td>';
			$sx .='<td style="background-color:#E0E0FF">&nbsp&nbsp&nbsp</td><td>Ótimo - 9 e 10</td></font></tr>';
			$sx .='</tr></table>'; 
			$sx .= '</A></form>';
            
            /*
             */
			 $sx .= '<script>
			     $("#bt_next").click(function() {
                        $("#dd2").val('.(round($comp)+1).');
                        $("#dd20").val("Salvar");
                        document.frm.submit();
                 });
                 $("#bt_prev").click(function() {
                        $("#dd2").val('.(round($comp)-1).');
                        $("#dd20").val("Salvar");
                        document.frm.submit();
                 });                 
                 </script>
             ';
		
			return($sx);
	}
	/*Função para acrecentar ZEROS na frente de um numero para que fique com três casas*/
	function numtochar($num){
		
		$competencia='0000'.$num;
		$competencia=substr($competencia,-3);
		
		return($competencia);
	}

	function gravar($avaliador,$competencia,$ano,$mes)
	{
	    global $acao,$dd,$avaliador,$base_name,$base_host,$base_user;    
			    require("../db_drh.php");
				
			    $competencia = $this->numtochar($competencia);
            	
                 /*Query para obter funcionarios subordinados ao gestor, com status de avaliação pendente(P)*/    
                $sql = "select distinct(aagc_cargo) from aval_gestor_cargo 
                		where aagc_gestor ='$avaliador' and
                      	aagc_ativo = 1
                ";
                /* Execução */
                $rlt = db_query($sql);
                while ($line = db_read($rlt)){
                /*Obtem o nome*/
                            $cargo=$line['aagc_cargo'];
                             require("../db_fghi.php"); 
                            /* Query */
                            
                            $sql2 = "select us_cracha,us_nomecompleto from usuario where us_cargo_avaliacao= '$cargo'";
                            /* Execução */
                            $rlt2 = db_query($sql2);
                            while ($line2 = db_read($rlt2)){
                                $func=$line2['us_cracha'];
                                $nome=$line2['us_nomecompleto'];
                                $vl = array('','','','','','','','','','','','','','','','','','','','','');
                           
                             require("../db_drh.php");
                            
        					$data = date("Ymd");
        					$vlr = round($_GET['group_'.$func]);
        					if ($vlr > 0)
        						{
        						
        						/* Deleta dados anteriores */
                                $sql = "delete from aval_dados 
                                    where avd_avaliador = '$avaliador'
                                    and avd_funcionario = '$func'
                                    and avd_criterio = '$competencia'
                                    and avd_ref_ano =  '$ano'
                                    and avd_ref_mes = '$mes'
                                    ";
                                $xrlt = db_query($sql);
								
                				$sql = "insert into aval_dados 
        								(
        								avd_funcionario, avd_avaliador, avd_criterio,
        								avd_nota, avd_data, avd_ref_ano,
        								avd_ref_mes
        								) values (
        								'$func','$avaliador','$competencia',
        								'$vlr',$data,'$ano',
        								'$mes')
        						";
        						$xrlt = db_query($sql);
        					
        						}
					
                    }
					}
				
        				$this->atualiza_avaliacao();
        				
		return(1);
	}
	
	function carrega_indice_competencia(){
			$sql = 'select * from aval_competencias
					where ac_ativo=1
					order by ac_ordem';
			$rlt = db_query($sql);
			while($line = db_read($rlt)){
				$tt++;
				$this->indice_comp[$tt] = $line['ac_codigo'];
			}
			$this->ttcomp = $tt;
			return(1);
	}

	function monta_tela($avaliador,$competencia ){
	       global $base_name,$base_server,$base_host,$base_user,$dd;
            /* data */
            $ano = date("Y");
            $mes = date("m");
		
				/*Query para obter funcionarios subordinados ao gestor, com status de avaliação pendente(P)*/	
				
				$sql = "select aagc_cargo, aagc_loja from aval_gestor_cargo  
				where aagc_gestor ='$avaliador' and
				      aagc_ativo = 1
				group by aagc_cargo, aagc_loja 
				";
				/* Execução */
				$rlt = db_query($sql);
				while ($line = db_read($rlt)){
				/*Obtem o nome*/
            	
            				$cargo=$line['aagc_cargo'];
            				$lj=$line['aagc_loja'];
                            
            		         require("../db_fghi.php"); 
                    	  
                            /* Query */
                            $sql2 = "select * from usuario 
                            		 where 	us_cargo_avaliacao = '$cargo' and 
                            				us_loja ='$lj' and 
                            				us_status ='A'
                            		";
									
                            /* Execução */
                            $rlt2 = db_query($sql2);
                           
                            while ($line2 = db_read($rlt2)){
                                $func=$line2['us_cracha'];
                                $nome=$line2['us_nomecompleto'];
                                $vl = array('','','','','','','','','','','','','','','','','','','','','');
 
                               if(round($dd[2])<1)
                               {
	                               	$dd[2]=1; 
                               }
                               if($dd[2]>$this->ttcomp)
                               {
                               		$dd[2]=$this->ttcomp; 
                               	}
                               $crit= $this->indice_comp[$dd[2]];
                               
                               require("../db_drh.php");
                               
                               $sql3 ="select * from aval_dados where avd_funcionario='".$func."' and 
                                                                     avd_avaliador='".$avaliador."' and
                                                                     avd_criterio='".$crit."' and
                                                                     avd_ref_ano='".$ano."' and
                                                                     avd_ref_mes='".$mes."'
                                                                     order by avd_criterio   
                                                                     ";
                                $rlt3 = db_query($sql3);
                                $line3 = db_read($rlt3);
                                $vl[$line3['avd_nota']]='Checked';                               
                                
                                $tx .= '<tr '.coluna().'><td>'.$font.$nome.'</font></td>';
                                $sty = 'class="tabela01" style="background-color:';
                                $sty2 = '"><input type="radio" name="group_'.$func;
                                
                                $tx .= '<td width="10" '.$sty.'#FFE0E0'.$sty2.'" value="1"'.$vl[1].'></td>';
                                $tx .= '<td width="10" '.$sty.'#FFE0E0'.$sty2.'" value="2"'.$vl[2].'></td>';
                                $tx .= '<td width="10" '.$sty.'#FFE0E0'.$sty2.'" value="3"'.$vl[3].'></td>';
                                $tx .= '<td width="10" '.$sty.'#FFE0E0'.$sty2.'" value="4"'.$vl[4].'></td>';
                                $tx .= '<td width="10" '.$sty.'#FFFFE0'.$sty2.'" value="5"'.$vl[5].'></td>';
                                $tx .= '<td width="10" '.$sty.'#FFFFE0'.$sty2.'" value="6"'.$vl[6].'></td>';
                                $tx .= '<td width="10" '.$sty.'#E0FFE0'.$sty2.'" value="7"'.$vl[7].'></td>';
                                $tx .= '<td width="10" '.$sty.'#E0FFE0'.$sty2.'" value="8"'.$vl[8].'></td>';
                                $tx .= '<td width="10" '.$sty.'#E0E0FF'.$sty2.'" value="9"'.$vl[9].'></td>';
                                $tx .= '<td width="10" '.$sty.'#E0E0FF'.$sty2.'" value="10"'.$vl[10].'></td></tr>';
                           }

		          }
		
		return($tx);
	}
	/*Gera a tela para autoavaliacao com todas as competências*/
	function avaliacao_individual($funcionario,$avaliador)
	{       
			global $acao,$form;
            global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_206_rh.php");
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
			$sql = "select ac_codigo,avd_nota from aval_competencias 
					left join aval_dados on ac_codigo=avd_criterio
					where avd_funcionario = '$funcionario' 
								and avd_avaliador = '$avaliador'
								and avd_ref_ano =  '$ano'
								and avd_ref_mes = '$mes'
					";
			
			/* Execução */
			$rlt = db_query($sql);

			while ($line = db_read($rlt)){
				$valida[$line['ac_codigo']] = $line['avd_nota'];
			}
			/* Query */
			$sql = "select ac_codigo,ac_conceito,ac_descricao,ac_ordem from aval_competencias order by ac_ordem ";
			/* Execução */
			$rlt = db_query($sql);
			/* Montagem da tela de saida */
			$sx .= '<form method="get" action="'.page().'"><table><table align="center" width="700"><tr><td>';
			$sx .= '<input type="hidden" name="dd1" id="dd1" value="'.$avaliador.'" size="9" maxlength="7"  >';
			$sx .= '<input type="hidden" name="dd2" id="dd2" value="'.$funcionario.'" size="9" maxlength="7"  >';
			$sx .= '<div><table align="right"><tr><th>Competências</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>6</th><th>7</th><th>8</th><th>9</th><th>10</th></tr>';	
			$desc = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
			$ok=1;
			if (strlen($acao)==0)
			{
				$ok=0;
			}
			while ($line = db_read($rlt))
			{
				$r =$line['ac_codigo'];
				$vl = array('','','','','','','','','','','','','','','','','','','','','');
				$descricao = trim($line['ac_conceito']);
				$competencia = trim($line['ac_descricao']);
				$ordem = trim($line['ac_ordem']);
				if($valida[$r]!=''){
					 $vl[$valida[$r]] = 'Checked';
					 $vlr=1;
				}else {
					if (strlen($acao) > 0){
						$vlr = round($_GET['group_'.$r]);
						if ($vlr > 0){
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
	function dados_func($funcionario)
	{
			
			
			/* Query */
			$sql = "select us_nomecompleto,us_cargo from usuario where us_cracha= '$funcionario'";
			
			/* Execução */
			$rlt = db_query($sql);
			
			
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
	function atualiza_avaliacao(){
	    global $base_name,$base_server,$base_host,$base_user,$dd;
		require("../db_drh.php");	
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
				$sql3 ="insert into aval_avaliacoes 
							(
							aa_funcionario, aa_status, aa_limite,
							aa_avaliador,aa_pagina
							) values (
							'".$funcionario."','',".$ano.$mes.$diasmes.",
							'".$avaliador."','".$dd[2]."')";
				$rlt3 = db_query($sql3);
			}			   
		}	
/*----------------------------*/			
		
		/*Query - total de competencias cadastradas*/
		$sql3 = 'select count(*) from aval_competencias where ac_ativo=1';
		
		/* Execução */
		$rlt3 = db_query($sql3);
		
		while ($line3 = db_read($rlt3)) 
		{
			$this->ttcomp=$totcomp = $line3['count'];
		}	
		
		/*Query - avaliações cadastradas*/
		$sql = 'select * from aval_avaliacoes
		   		where aa_avaliador=\''.$dd[1].'\'
			  
		';
		
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
			$sql5 = 'select * from aval_dados
				 	where avd_avaliador = \''.$avaliador.'\' and
      			 	avd_funcionario = \''.$funcionario.'\' and
        		 	avd_ref_ano = \''.$ano.'\' and
         		 	avd_ref_mes = \''.$mes.'\' order by avd_criterio';
		  
			/* Execução */
			$rlt5 = db_query($sql5);
			$maior=1;
            $comp=0;
            $tt=0;     
			/*codigo para tentar otimizar o update
			$this->sql_up1='<br>update aval_avaliacoes as av set
						aa_status=col.aa_status,
					    aa_pagina=col.aa_pagina
					from (values';      
			 */
			 
			while ($line5=db_read($rlt5)) 
			{    
				if (($maior)==round($line5['avd_criterio'])) 
				{
				    $comp=$maior+1;
                    $maior++;
                    $tt++;
				}else
				{
				    $tt++;
                }
                if ($tt>=round($totcomp)) 
                {
                	/*codigo para tentar otimizar o update
                	    $this->sql_up1.="(	'".$avaliador."',
                	    					'".$funcionario."',
                	    					'".$data."',
                	    					'B',
                	    					".$comp."),";
							*/

												$sql4= 'update aval_avaliacoes set aa_status=\'B\',
					                aa_pagina = \''.$comp.'\'  
							where	aa_avaliador = \''.$avaliador.'\' and
      			 					aa_funcionario = \''.$funcionario.'\' and
      			 					aa_limite = \''.$data.'\'';
					$rlt4 = db_query($sql4);
					$sx= 'B';
				}else{
					/*codigo para tentar otimizar o update
					$this->sql_up1.="(	'".$avaliador."',
                	    					'".$funcionario."',
                	    					".$data.",
                	    					'P',
                	    					'".$comp."'),";
											*/
					$sql4= 'update aval_avaliacoes set aa_status=\'P\',
					                aa_pagina = \''.$comp.'\'  
							where	aa_avaliador = \''.$avaliador.'\' and
      			 					aa_funcionario = \''.$funcionario.'\' and
      			 					aa_limite = \''.$data.'\'';
					$rlt4 = db_query($sql4);
					$sx= 'P';
				}
			}
			
		}
						  
		
		return(1);
	}
	function cargo_gestor($func='')
	{
	    global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
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
		//$sx .='<TR bgcolor="#c0c0c0" valign="top" align="left" class="lt0"><TD colspan=2 ><B>&nbsp&nbsp&nbspAvaliador</B></TD></TR>';
		
		
		while ($line = db_read($rlt))
		{
		    $this->lista_cargos();	
            $this->lista_lojas();
            	
			$gestor = $line['aagc_gestor'];
            $cargo = round($line['aagc_cargo']);
            $lj = $line['aagc_loja'];
			if ($gestor != $xgestor)
			{
				$tt++;
				$sx .= '<TR bgcolor="#BCBEC0" valign="top" align="left" class="lt0">';
				$sx .= '<TD colspan=3><font size="3px">';
			 	$idf = round($line['aagc_gestor']);
				$sx .= $line['aagc_gestor'].' - '.ucwords(strtolower($func[$idf])).'</font>';
			}
			$sx .= '<TR bgcolor="#F0F0F0"><TD width="200">&nbsp&nbsp&nbsp'.$this->loja[round($lj)].'<td>'.ucwords(strtolower($this->car[round($cargo)]));
			$sx .= '<td width="20" align="right"><a href="aval_cargo_gestor_ed.php?dd0='.$line['id_aagc'].'"><img width="20" border="0" height="19" alt="" src="../img/icone_editar.gif"></img></a></td>';
			$xgestor = $gestor;
			
		}
		$sx .= '<TR><TD><b>Total de '.$tt.' avaliadores.</b></TD></TR>';
		$sx .= '</table>';

		return($sx);
	}


    function peso_competencia()
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        $sql = "select * from aval_cargos_matrix 
                order by acm_competencia,acm_loja,acm_cargo
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
        
        $sx .='<TR><TD  colspan=5><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
        $sx .='<TR><td colspan=5 align="right"><a onmouseout="document.images[\'novo\'].src=img1.src;" onmouseover="document.images[\'novo\'].src=img2.src;" alt="Titulo" href="aval_peso_ed.php" title="Novo Registro"><img border="0" name="novo" src="../img/bt_novo.png"></img></a></td></TR>';
        $sx .='<TR><TD colspan=5><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
        $sx .='<TR><TD></BR></TR>';
        
        while ($line = db_read($rlt))
        {
            $this->lista_cargos();  
            $this->lista_lojas();
            $this->carrega_competencias();
            $competencia = trim($line['acm_competencia']);
            $cargo = trim($line['acm_cargo']);
            $lj = $line['acm_loja'];
            $nota= $line['acm_peso'];
            $corte= $line['acm_corte'];
            $comp_nome=$this->lista_comp[3][$competencia];
            if ($competencia != $xcompetencia)
            {
                $tt++;
                $sx .= '<TR bgcolor="#BCBEC0" valign="top" align="left">';
                $sx .= '<TD colspan=2 class="lt0"><font size="3px">';
                $idf = trim($line['acm_competencia']);
                $sx .= $competencia.' - '.$comp_nome;
                $sx .= '<td class="lt0" align="center"><font size="3px">Peso
                        <td class="lt0" align="center"><font size="3px">Corte
                        <td class="lt0" align="center"><font size="3px">Ação';
            }
            $sx .= '<TR bgcolor="#F0F0F0" class="lt1"><TD colspan=2 align="left" width="200">&nbsp&nbsp&nbsp'.$this->loja[round($lj)].'
                                          /'.$this->car[round($cargo)].'
                                          <td align="center">'.$nota.'
                                          <td align="center">'.$corte;
            $sx .= '<td width="20" align="right">
                    <a href="aval_peso_ed.php?dd0='.$line['id_acm'].'"><img width="20" border="0" height="19" alt="" src="../img/icone_editar.gif"></img></a></td>';
            $xcompetencia = $competencia;
                
        }
        $sx .= '<TR><TD><b>Total de '.$tt.' registros.</b></TD></TR>';
        $sx .= '</table>';
        return($sx);
    }

 	function cp_competencias()
 	{
 		$cp = array();
		array_push($cp,array('$H8','id_ac','',false,True));
		array_push($cp,array('$S60','ac_descricao','Competência',false,True));
		array_push($cp,array('$T80:5','ac_conceito','Descrição',False,True));
		array_push($cp,array('$O 1:Ativo&0:Não ativo','ac_ativo','Status',False,True));
		array_push($cp,array('$S2','ac_ordem','Ordem',false,True));
		array_push($cp,array('$H8','ac_codigo','',false,True));
		
		return($cp);
 	}
	function row_competencias()
	{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
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
	        global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_206_rh.php");
			$dx1 = 'ac_codigo';
			$dx2 = 'ac';
			$dx3 = 3;
			$sql = "update aval_competencias set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
	}		
	
    function row_gestor()
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        $this->tabela = "aval_gestor_cargo";
        $tabela = "aval_gestor_cargo";
        $label = "Cadastro de Gestor";
        /* Páginas para Editar */
        $http_edit = 'aval_gestor_cargo_ed.php'; 
        $offset = 20;
        $order  = "aagc_cargo";
        $cdf = array('id_aagc','aagc_cargo','aagc_gestor','aagc_ativo','aagc_loja');
        $cdm = array('ID','Código','Cargo','Gestor','Status','Loja');
        $masc = array('','','','','','','','','');
        return(True);
    }
             
    function updatex_gestor()
	{
	       global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_206_rh.php");
			$dx1 = 'id_aagc';
			$dx2 = 'aagc';
			$dx3 = 4;
			$sql = "update aval_gestor_cargo set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
	}
	
            	
	/*Carrega cargos em um array*/
	function lista_cargos()
	{
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_fghi.php");	        
	    $sql = 'select * from cargos order by id_car';
        $rlt = db_query($sql);
        while($line=db_read($rlt))
        {
            $this->car[round($line['car_cod'])]=$line['car_nome'];    
        }
        $car=$this->car;        
        return($car);
	}
    
    /*Carrega cargos em um array*/
    function lista_lojas()
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_fghi.php");          
        $sql = 'select * from empresa where e_cargo=1 order by e_nome';
        $rlt = db_query($sql);
        while($line=db_read($rlt))
        {
            $this->loja[round($line['e_codigo'])]=$line['e_nome'];    
        }
        $loja=$this->loja;        
        return($loja);
    }
	/*Carrega boxlist*/
	 function lista_lojas_option()
	 {
            global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_fghi.php");       
            $sql = 'select * from empresa where e_cargo=1 order by e_nome';
            $rlt = db_query($sql);
            $op = ' :Selecione o loja';
            while ($line = db_read($rlt)) 
            {
                $loja=trim($line['e_nome']);
                $codigo=trim($line['e_codigo']);
                $op .= '&'.$codigo.':'.$loja;
            }
            
            $this->op_lojas=$op;
            return($op);
     
     }
     
     function lista_competencia_option()
     {
            global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_drh.php");       
            $sql = 'select * from aval_competencias where ac_ativo=1 order by ac_descricao';
            $rlt = db_query($sql);
            $op = ' :Selecione competência';
            while ($line = db_read($rlt)) 
            {
                $comp=trim($line['ac_descricao']);
                $codigo=trim($line['ac_codigo']);
                $op .= '&'.$codigo.':'.$comp;
            }
            $this->op_competencia=$op;
            return($op);
     }
	
	
	 
    function cargo_func($func='',$lj='',$cargo='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;         
        $this->lista_cargos();
        $this->lista_lojas();
        require("../db_fghi.php");
        if(strlen(trim($func))>0)
        {
            $tx.="and us_cracha='$func' ";
        }
        if(strlen(trim($lj))>0 &&trim($lj)!='04')
        {
            $tx.=" and us_loja='$lj' ";
        }
        if(strlen(trim($cargo))>0)
        {
            $tx.=" and us_cargo_avaliacao='$cargo' ";
        }
                    
        $sql = "select * from usuario where us_status = 'A' $tx order by us_nomecompleto";
        $rlt = db_query($sql);
        $sx .= '<TABLE align="center" class="pg_white border padding5 wc80" cellpadding="10" cellspacing="5">';
        $sx .= '<TR><TD colspan=11 class="tabela00 bg_blue padding5" ><font color="#FFFFFF">'.$desc.'</font></TD></TR>';
        $sx .= '<TR><TD>&nbsp</TD></TR>';
        $sx .='<TR align="left"><TH>Nome<TH>Loja<TH>Cargo</TR>';
        while ($line = db_read($rlt))
        {
            $cargo = round($line['us_cargo_avaliacao']);
            $lj = round($line['us_loja']);
            $sx .= '<TR class="lt2"><TD>'.ucwords(strtolower($line['us_nomecompleto']))."<TD>".$this->loja[$lj].'<TD>'.$this->car[$cargo].'</font>';
            $tot++;        
        }
        $sx .= '<TR><TD><b>Total de '.$tot.' funcionários.</b></TD></TR>';
        $sx .= '</table>';
        return($sx);    
    } 
    
	function gestor_cargo($func)
	{
        global $base_name,$base_server,$base_host,$base_user,$user;	        
	    $this->lista_cargos();
        $this->lista_lojas();
        require("../db_drh.php");            
		$sql = "select * from aval_gestor_cargo where aagc_ativo = 1 order by aagc_loja, aagc_cargo";
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
		$sx .='<TR><TD> <td align="right"><a onmouseout="document.images[\'novo\'].src=img1.src;" onmouseover="document.images[\'novo\'].src=img2.src;" alt="Titulo" href="aval_gestor_cargo_ed.php" title="Novo Registro"><img border="0" name="novo" src="../img/bt_novo.png"></img></a></td></TR>';
		$sx .='<TR><TD colspan=2><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
		$sx .='<TR><TD></BR></TR>';
		while ($line = db_read($rlt))
		{
			$cargo = round($line['aagc_cargo']);
            $lj = round($line['aagc_loja']);
			if (($cargo != $xcargo)||$lj!=$xlj)
			{
			    $tt++;
        		$sx .= '<TR style="background-color:#BCBEC0"><TD colspan=2><font size="3px">'.$this->loja[$lj].' - '.$this->car[$cargo].'</font>';
				$xcargo = $cargo;
                $xlj = $lj;
			}
			$sx .= '<TR>';
			$sx .= '<TD>&nbsp&nbsp&nbsp';
			$idf = round($line['aagc_gestor']);
			$sx .= $line['aagc_gestor'].ucwords(strtolower($func[$idf]));
			$sx .= '<td width="20"><a href="aval_gestor_cargo_ed.php?dd0='.$line['id_aagc'].'"><img width="20" border="0" height="19" alt="" src="../img/icone_editar.gif"></img></a></td>';
		}
		$sx .= '<TR><TD><b>Total de '.$tt.' cargos.</b></TD></TR>';
		$sx .= '</table>';

		return($sx);
	}

    function ultima_pagina()
    {
        global $base_name,$base_server,$base_host,$base_user,$user,$dd;
        require("../db_drh.php");       
        $sql="select min(aa_pagina) from aval_avaliacoes 
             where aa_avaliador='".$dd[1]."' and aa_status='P' and aa_funcionario!='".$dd[1]."'";
        $rlt=db_query($sql);
        $line=db_read($rlt);
        $competencia=($line['min']);
        return($competencia);
    }

     function lista_avaliadores()
     {
         global $base_name,$base_server,$base_host,$base_user,$user;
         require("../db_206_rh.php");
         $sql = "select distinct(aagc_gestor) from aval_gestor_cargo where aagc_ativo=1";
         $rlt = db_query($sql);
         $op = ':: Todos ::';
         require_once('_class_funcionario.php');
         $func = new funcionario;
         require("../db_fghi.php");
         while ($line = db_read($rlt)) 
         {
             $id=trim($line['aagc_gestor']);    
             $func->le('',$id,'','');
             $avaliador=$func->line2[1];
             $op .= '&'.$id.':'.$avaliador['us_nomecompleto'];
             
             $this->lista_avaliador[$id]=$func->line['us_nomecompleto'];
         }
         require("../db_206_rh.php");
         return($op);
     }  
     
    function mostra_avaliador($avaliador='')
    {
         global $base_name,$base_server,$base_host,$base_user,$user;
         $func = new funcionario;
         require_once('_class_cargos.php');
         $carg = new cargos;
         require("../db_206_rh.php");
         if (strlen(trim($avaliador)))
         {
            $tx=" where aagc_gestor='$avaliador' ";
         }
         $sql = "select aagc_loja, aagc_cargo
                 from aval_gestor_cargo 
                 $tx 
                 group by aagc_loja, aagc_cargo
                 order by aagc_loja, aagc_cargo";
         $rlt = db_query($sql);
         require("../db_fghi.php");
         while ($line = db_read($rlt)) 
         {
             
             $cargo=trim($line['aagc_cargo']);    
             $loja=trim($line['aagc_loja']);    
             $func->le('','',$cargo,$loja);
             $carg->le($line['aagc_cargo']);
             $cargonome=$carg->car_nome;
             $lojanome= $this->loja[round($line['aagc_loja'])];
             $j=1;
             $fx.="<tr>";
             while($i<=count($this->lista_comp[0]))
                        {   
                            $fx .='<td class="rotation" height="200px" width ="5%">'.$this->lista_comp[0][$i]."</td>";
                            $i++;
                        }
             $fx.="</tr>";
             while($j<=count($func->line2))
             {
                 $ava=$func->line2[$j];
                 $fx .="<tr><td>".$ava['us_nomecompleto']."</td>
                        ";
                        $i=1;
                        while($i<=count($this->lista_comp[0]))
                        {   
                            $fx .='<td class="tabela01" width ="5%">'.$this->lista_nota[round($ava['us_cracha'])][round($this->lista_comp[2][$i])]."</td>";
                            $i++;
                        }
                        $fx.="<tr>";
                 $j++;
             }
         }
         $sx .= '<table class=tabela01 width="95%">'.$fx.'</table>';
       
         require("../db_206_rh.php");
         return($sx);
     }  
    function mostra_individual($funcionario='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_fghi.php");
        $func = new funcionario;
        $func->le('',$funcionario,'','');
        $cargo=trim($func->line['us_cargo_avaliacao']);
        $loja=trim($func->line['us_loja']);
        $this->carrega_avaliadores_do_funcionario($funcionario,$cargo,$loja);
        $fx.= $func->mostra();
        $av="";
        while($j<count($this->lista_avaliador))
        {
            $av.='<th class="tabelaH" align="center" width="10%">Av'.($j+1)."</th>";
            $j++;
        }
        
        $fx .='<table width="100%">
        		<tr bgcolor="#FFFFFF">
	        		<th class="tabelaH" align="left" width="5%"></th>
	        		<th class="tabelaH" align="left" width="40%">Competências / Avaliadores</th>
	        		<th class="tabelaH" align="center" width="10%">Auto</th>
	        		<th class="tabelaH" align="center" width="10%">Peso</th>
	        		'.$av.'
	        		<th class="tabelaH" width="10%">Média</th>
        		</tr>';
        $i=1;
		
        while($i<=count($this->lista_comp[0]))
        {
            $competencia=trim($this->lista_comp[2][$i]);
            $nt_auto=$this->lista_nota_auto[$this->lista_comp[2][$i]];   
			$obs=$this->lista_obs[$this->lista_comp[2][$i]];
			$fx .='<tr> <td>'.$obs.'</td>
            			<td class="tabela01">'.$this->lista_comp[0][$i].'</td>
            			<td class="tabela01" align="center">'.$nt_auto."</td>";
            $j=1;
            $div=0;
            $an='';
            $tt=0;
            $this->peso=1;
            $media=0;
			$vld=0;
			
            while($j<=count($this->lista_avaliador))
            {
                $this->valida_aval_competencia_matrix($competencia,0,0,$cargo,$loja);
                $peso=$this->peso;
                $corte=$this->corte;
				$nt= $this->lista_nota[$this->lista_avaliador[$j]][$this->lista_comp[2][$i]];
                if($vld == 0){
                	$an.='<td class="tabela01" align="center">'.$peso.'</td>';
					$vld=1;
                	}
				if(strlen(trim($nt))==0)
				{
					$an.='<td class="tabela01" align="center">--</td>'; 
				}else{
					$an.='<td class="tabela01" align="center">'.$nt."</td>";	
				}
                if($nt>1)
                {
            		$tt = $nt+$tt; $div++;
				}
                
                $j++;
            }
            if($div>0)
            {
                $media=$tt/$div;
            }
           $an.='<td class="tabela01" align="center">'.number_format($media,1)."</td>";
                
            $fx.=$ax.$an."</tr>";
            $i++;
        }
        $fx .="</table>";
        $sx="<center>".$fx;
        return($sx);
    }

	function mostra_simplificado($funcionario='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_fghi.php");
        $func = new funcionario;
        $func->le('',$funcionario,'','');
        $cargo=trim($func->line['us_cargo_avaliacao']);
        $loja=trim($func->line['us_loja']);
        $this->carrega_avaliadores_do_funcionario($funcionario,$cargo,$loja);
        $fx.= $func->mostra();
        $av="";
        $fx .='<table width="100%">
        		<tr bgcolor="#FFFFFF">
	        		<th class="tabelaH" align="left" width="40%">Competências / Avaliadores</th>
	        		<th class="tabelaH" align="center" width="10%">Auto</th>
	        		<th class="tabelaH" width="10%">Média avaliadores</th>
        		</tr>';
        $i=1;
		
        while($i<=count($this->lista_comp[0]))
        {
            $competencia=trim($this->lista_comp[2][$i]);
            $nt_auto=$this->lista_nota_auto[$this->lista_comp[2][$i]];   
            $fx .='<tr><td class="tabela01">'.$this->lista_comp[0][$i].'</td><td class="tabela01" align="center">'.$nt_auto."</td>";
            $j=1;
            $div=0;
            $an='';
            $tt=0;
            $this->peso=1;
            $media=0;
			
            while($j<=count($this->lista_avaliador))
            {
                $this->valida_aval_competencia_matrix($competencia,0,0,$cargo,$loja);
                $peso=$this->peso;
                $corte=$this->corte;
				$nt= $this->lista_nota[$this->lista_avaliador[$j]][$this->lista_comp[2][$i]];
                if($nt>1)
                {
                	$tt = $nt+$tt; 
                	$div++;
				}
                $j++;
            }
            if($div>0)
            {
                $media=$tt/$div;
            }
		 
           $an.='<td class="tabela01" align="center">'.number_format($media,1)."</td>";
                
            $fx.=$an."</tr>";
            $i++;
        }
        $fx .="</table>";
        $sx="<center>".$fx;
        return($sx);
    }
    function relatorio_avaliador($avaliador='')
    {
        $this->lista_lojas();
        $this->carrega_competencias();
        $this->carrega_notas($avaliador);
        $sx=$this->mostra_avaliador($avaliador);

        return($sx);
    }
    
    
    function relatorio_individual($funcionario='')
    {
        
        $this->lista_lojas();
        $this->carrega_auto_avaliacao($funcionario);
        $this->carrega_competencias();
        $this->carrega_avaliadores_do_funcionario($funcionario);
        $sx=$this->mostra_individual($funcionario);
        
        return($sx);
    }
	
	function relatorio_simplificado($funcionario='')
    {
        
        $this->lista_lojas();
        $this->carrega_auto_avaliacao($funcionario);
        $this->carrega_competencias();
        $this->carrega_avaliadores_do_funcionario($funcionario);
        $sx=$this->mostra_simplificado($funcionario);
        
        return($sx);
    }
    
    function carrega_auto_avaliacao($funcionario='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
		
		if(strlen(trim($this->mes))==0)
		{
			$this->mes=date('m');
		}
		if(strlen(trim($this->ano))==0)
		{
			$this->ano=date('Y');
		}
		
       $sql="select * from aval_dados 
       				where avd_avaliador='$funcionario' and
       					  avd_funcionario='$funcionario' and
       					  avd_ref_mes='$this->mes' and
       					  avd_ref_ano='$this->ano'
       				order by avd_ref_ano, avd_ref_mes, id_avd	  
       					  
       			 ";
        $rlt=db_query($sql);
        while($line=db_read($rlt))
        {
           $i++;
           $this->lista_nota_auto[$line['avd_criterio']]= $line['avd_nota'];
		   if(strlen(trim($line['avd_obs']))==0)
		   {
		   	$this->lista_obs[$line['avd_criterio']]= '<a href="javascript:newxy2(\'aval_rel_observacao.php?dd0='.$line['id_avd'].'\',800,300);">
            										<img src="../img/reminders.png" height="20px" title="Clique aqui para adicionar uma anotação."</a>';	
		   }else{
		   	$this->lista_obs[$line['avd_criterio']]= '<a href="javascript:newxy2(\'aval_rel_observacao.php?dd0='.$line['id_avd'].'\',800,300);">
            										<img src="../img/remindersg.png" height="20px" title="Clique aqui para adicionar uma anotação."></a>';
		   }
		   
		   
        }
        
        return(1);
    }
    function carrega_avaliadores_do_funcionario($funcionario='',$cargo='',$loja='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
		if(strlen(trim($this->mes))==0)
		{
			$this->mes=date('m');
		}
		if(strlen(trim($this->ano))==0)
		{
			$this->ano=date('Y');
		}
		
        $sql = "select * from aval_gestor_cargo
                where aagc_cargo='$cargo' and
                      aagc_loja='$loja' and
                      aagc_ativo=1
                ";
        $rlt = db_query($sql);
        while($line=db_read($rlt))
        {
           $i++;
           $this->lista_avaliador[$i]= $line['aagc_gestor'];
        }
		
       $sql = "select * from aval_dados
                where avd_funcionario = '$funcionario' and
                		avd_ref_ano='$this->ano' and
                	  	avd_ref_mes = '$this->mes' 
                ";
        $rlt = db_query($sql);
        while($line=db_read($rlt))
        {
           $this->lista_nota[$line['avd_avaliador']][$line['avd_criterio']]=$line['avd_nota'];
        }
        return(1);
    }
    
    /*Notas avaliações*/
    function carrega_notas($avaliador='',$avaliados='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        if(strlen(trim($avaliador))!=0)
        {
            $tx= " where avd_avaliador='$avaliador' and
            			avd_ref_ano='$this->ano' and
                	  	avd_ref_mes = '$this->mes'				
			 ";    
        }else{
        	$tx= " where avd_ref_ano='$this->ano' and
                	  	 avd_ref_mes = '$this->mes' ";
        }
        $sql = "select * from aval_dados
                $tx 
                order by avd_avaliador,avd_funcionario";
        $rlt = db_query($sql);
        while ($line=db_read($rlt)) 
        {
            $this->lista_nota[round($line['avd_funcionario'])][round($line['avd_criterio'])]=$line['avd_nota'] ;
        }
        return(1);
    }
    
    /*Competências*/
    function carrega_competencias()
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        $sql = "select * from aval_competencias 
                where ac_ativo='1'
                order by ac_ordem
                ";
        $rlt = db_query($sql);
        while ($line=db_read($rlt)) 
        {  $i++;
		   $this->lista_comp[0][$i]=$line['ac_descricao'] ;
           $this->lista_comp[1][$i]=$line['ac_conceito'] ;
           $this->lista_comp[2][$i]=$line['ac_codigo'] ;
           $this->lista_comp[3][$line['ac_codigo']]=$line['ac_descricao'];
        }

        return(1);
    }
    /*Valida se o registro nao esta repetido*/
    function valida_aval_competencia_matrix($competencia='',$peso=0,$corte=0,$cargo='',$loja='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        $ant=0;
        $sx=0;
        if(strlen($competencia)>0)
        {
            if ($ant==1) { $tx.=" and ";}
            $tx.=" acm_competencia='$competencia' ";
            $ant = 1;
        }
        if(strlen($cargo)>0)
        {
            if ($ant==1) { $tx.=" and ";}
            $tx.=" acm_cargo='$cargo' ";
            $ant = 1;
        }
        if(strlen($loja)>0)
        {
            if ($ant==1) { $tx.=" and ";}
            $tx.=" acm_loja='$loja' ";
            $ant = 1;
        }
        if(strlen($tx)>0){$tx= ' where '.$tx;}
        
        $sql = "select * from aval_cargos_matrix
                $tx  
        ";
        $rlt = db_query($sql);
        while($line=db_read($rlt)){
            $this->peso=$line['acm_peso'];
            $this->corte=$line['acm_corte'];
            $sx=1;
        }
        
        return($sx);
    }
    function update_aval_competencia_matrix($competencia='',$peso=0,$corte=0,$cargo='',$loja='')
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        if((strlen($competencia)>0)&&(strlen($corte)>0)&&(strlen($loja)>0))
        {
            $sql = "update aval_cargos_matrix
                    set acm_corte=$corte, 
                        acm_peso=$peso
                    where acm_competencia='$competencia'
                      and acm_cargo='$cargo'
                      and acm_loja='$loja'
            ";
            $rlt = db_query($sql);
            return(1);
        }
        
        return(0);
    }
	
	function peso_competencia_lista()
    {
        global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_206_rh.php");
        $sql = "select * from aval_cargos_matrix 
                order by acm_competencia,acm_loja,acm_cargo
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
        
        $sx .='<TR><TD  colspan=5><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
        $sx .='<TR><td colspan=5 align="right"><a onmouseout="document.images[\'novo\'].src=img1.src;" onmouseover="document.images[\'novo\'].src=img2.src;" alt="Titulo" href="aval_peso_ed_lista.php" title="Novo Registro"><img border="0" name="novo" src="../img/bt_novo.png"></img></a></td></TR>';
        $sx .='<TR><TD colspan=5><img width="100%" border="0" height="4" src="../img/bt_ln_b.png"></TD></TR>';
        $sx .='<TR><TD></BR></TR>';
        
        while ($line = db_read($rlt))
        {
            $this->lista_cargos();  
            $this->lista_lojas();
            $this->carrega_competencias();
            $competencia = trim($line['acm_competencia']);
            $cargo = trim($line['acm_cargo']);
            $lj = $line['acm_loja'];
            $nota= $line['acm_peso'];
            $corte= $line['acm_corte'];
            $comp_nome=$this->lista_comp[3][$competencia];
            if ($competencia != $xcompetencia)
            {
                $tt++;
                $sx .= '<TR bgcolor="#BCBEC0" valign="top" align="left">';
                $sx .= '<TD colspan=2 class="lt0"><font size="3px">';
                $idf = trim($line['acm_competencia']);
                $sx .= $competencia.' - '.$comp_nome;
                $sx .= '<td class="lt0" align="center"><font size="3px">Peso
                        <td class="lt0" align="center"><font size="3px">Corte
                        <td class="lt0" align="center"><font size="3px">Ação';
            }
            $sx .= '<TR bgcolor="#F0F0F0" class="lt1"><TD colspan=2 align="left" width="200">&nbsp&nbsp&nbsp'.$this->loja[round($lj)].'
                                          /'.$this->car[round($cargo)].'
                                          <td align="center">'.$nota.'
                                          <td align="center">'.$corte;
            $sx .= '<td width="20" align="right">
                    <a href="aval_peso_ed_lista.php?dd0='.$line['id_acm'].'"><img width="20" border="0" height="19" alt="" src="../img/icone_editar.gif"></img></a></td>';
            $xcompetencia = $competencia;
                
        }
        $sx .= '<TR><TD><b>Total de '.$tt.' registros.</b></TD></TR>';
        $sx .= '</table>';
        return($sx);
    }

	function competencia_form()
	{ 	global $base_name,$base_server,$base_host,$base_user,$user;
        $cargo = new cargos;
		require("../db_206_rh.php");
		$sx = '<center>';
		$sx .= $this->combo_lojas().'<br>';
		require("../db_fghi.php");
		$sx .= $cargo->combo_cargos().'<br>';
		$sx .= '<a class="botao-geral" href="aval_form_competencia.php?dd0='.$_POST["aval_comp"].'&dd1='.$_POST["aval_loja"].'">Continuar</a>';
		$sx .= '<br><br></center>';
		return($sx);
	}
	 function combo_lojas()
	 {
            global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_fghi.php");       
            $sql = 'select * from empresa where e_cargo=1 order by e_nome';
            $rlt = db_query($sql);
			$op = '<select id="aval_loja" name="aval_loja">';
            while ($line = db_read($rlt)) 
            {
                $loja=trim($line['e_nome']);
                $codigo=trim($line['e_codigo']);
                $op .= '<option value="'.$codigo.'">'.$loja.'</option>';
            }
            $op .= '</select>';
            

            return($op);
     
     }
	  function nome_loja($lj)
	 {
            global $base_name,$base_server,$base_host,$base_user,$user;
            require("../db_fghi.php");       
            $sql = 'select * from empresa where e_cargo=1 order by e_nome';
            $rlt = db_query($sql);
			
            while ($line = db_read($rlt)) 
            {
                $loja=trim($line['e_nome']);
              
            }
            
		return($loja);	
	 }		
	 
	 function save_competencia()
	 	{
            global $base_name,$base_server,$base_host,$base_user,$user,$dd;
            require("../db_drh.php");       
	           echo $sql = "select ac_codigo,ac_descricao,acm_peso,acm_corte, acm_cargo, acm_loja,id_ac from (select acm_competencia as competencia, * from aval_cargos_matrix where acm_loja='".$dd[0]."' and acm_cargo='".$dd[1]."'  ) as tb
						right join aval_competencias on ac_codigo= competencia 
						where ac_ativo=1 
						group by acm_peso,acm_corte, acm_cargo, acm_loja,id_ac, ac_descricao
						order by ac_descricao
         ";
            	$rlt = db_query($sql);
				$lj = $dd[0];
				$cargo = $dd[1];
				while ($line = db_read($rlt))
				{
						
					$codigo=trim($line['ac_codigo']);
	 				$fld = 'aval_comp_'.$codigo;
	 				$fld2 = 'aval_corte_'.$codigo;
	 				$corte = $_POST[$fld2];
	 				if(strlen(trim($corte))==0)
	 				{
	 					$corte = 0;
	 				}
	 				$peso = $_POST[$fld];
					
	 				$comp = $line['ac_codigo'];
					if((strlen(trim($line['acm_cargo']))==0)or(strlen(trim($line['acm_loja']))==0))
					{
						if(strlen(trim($peso))>0)
	 					{
	 						$this->insert_aval_cargo_matrix($corte,$peso,$lj,$cargo,$comp);	
	 					}	
							
					}else{
						if(strlen(trim($peso))>0)
	 					{
							$this->update_aval_cargo_matrix($corte,$peso,$lj,$cargo,$comp);
						}	
					}
					
				}
			
	 	}
		
	 function update_aval_cargo_matrix($corte,$peso,$lj,$cargo,$comp)
	 {
	 	global $base_name,$base_server,$base_host,$base_user,$user,$dd;
        require("../db_drh.php");   
	 	$sql = "update aval_cargos_matrix 
	 			set acm_corte=".$corte.", 
	 				acm_peso =".$peso."
	 			where acm_loja='".$lj."' and 
	 				  acm_cargo='".$cargo."' and
	 				  acm_competencia='".$comp."'
	 			";
	 	if((strlen($comp)==0)or
		   (strlen($peso)==0)or
		   (strlen($corte)==0)or
		   (strlen($cargo)==0)or
		   (strlen($lj)==0))
		{
			echo "<script>alert('Registro nao salvo')</script>";
			return(0); 
		}else{
			$rlt = db_query($sql);
			return(1);
		}
	 }
	 	
	 function insert_aval_cargo_matrix($corte,$peso,$lj,$cargo,$comp)
	 {
	 	global $base_name,$base_server,$base_host,$base_user,$user,$dd;
        require("../db_drh.php");  
		$sql = "insert into aval_cargos_matrix 
	 			(acm_competencia, acm_peso, acm_corte, acm_cargo,acm_loja)
	 			values
	 			('".$comp."',".$peso.",".$corte.",'".$cargo."','".$lj."')
	 	";
		if((strlen($comp)==0)or
		   (strlen($peso)==0)or
		   (strlen($corte)==0)or
		   (strlen($cargo)==0)or
		   (strlen($lj)==0))
		{
			echo "<script>alert('Registro nao salvo')</script>";
			return(0); 
		}else{
			$rlt = db_query($sql);
			return(1);
		}	
	 	
	 }
	 	
	 function combo_competencia()
     {
            global $base_name,$base_server,$base_host,$base_user,$user,$dd;
            require("../db_drh.php");   
			
          $sql = "select ac_codigo,ac_descricao,acm_peso,acm_corte, acm_cargo, acm_loja,id_ac from (select acm_competencia as competencia, * from aval_cargos_matrix where acm_loja='".$dd[0]."' and acm_cargo='".$dd[1]."'  ) as tb
						right join aval_competencias on ac_codigo= competencia 
						where ac_ativo=1 
						group by acm_peso,acm_corte, acm_cargo, acm_loja,id_ac, ac_descricao
						order by ac_descricao
         ";
            $rlt = db_query($sql);
            $op = '<center><table><th class="botao-geral">Competências</th>
            			<th class="botao-geral" width="20px">Peso</th>
            			<th class="botao-geral" width="40px">Corte</th></tr>';
			$op .= '<tr><td colspan="3">
			<form id="formulario" action="'.page().'" method="POST"></td></tr>';
			$op .= '<tr><td colspan="3"><input type="hidden" name="dd0" value="'.$dd[0].'"></input></td></tr>';			
			$op .= '<tr><td colspan="3"><input type="hidden" name="dd1" value="'.$dd[1].'"></input></td></tr>';
            while ($line = db_read($rlt)) 
            {
            	$codigo=trim($line['ac_codigo']);
				$peso = $line['acm_peso'];
				$corte = $line['acm_corte'];
	 			$fld = 'aval_comp_'.$codigo;
				$fld2 = 'aval_corte_'.$codigo;
	 			if(strlen(trim($peso))>0)
	 			{
	 				$vlr = $peso;
	 			}else{
	 				$vlr = '';
	 			}
	 			if(trim(round($corte))>0)
	 			{
	 				$vlr2 = 'checked';
	 			}else{
	 				$vlr2 = '';
	 			}		
	 				
            	$i++;
            	$comp=trim($line['ac_descricao']);
				$codigo=trim($line['ac_codigo']);
  			    $op .= '<tr><td class="botao-geral">'.$comp.' :</td>
  			    			<td align= "center" width="20px"><input name="'.$fld.'" size="2" maxLength="2" type="text" value="'.$vlr.'"></input></td>
  			    			<td align= "center" width="40px"><input name="'.$fld2.'" type="checkbox" value="1" '.$vlr2.'></input></td>
  			    			</tr>';
            }
			$op .= '<tr><td align="right" colspan="3"><input class="botao-geral" type="submit" name="Salvar"></input></td></tr>';
            $op .= '</table>';
			$op .= '</form>';
			if(strlen($_POST['Salvar'])>0)
			{
					
				$this->save_competencia();
				redirecina('aval_peso_ed_lista.php');
			}
			
            return($op);
     }
     
	 function valida_usuario($login='',$pass='')
	 {
	 	global $base_name,$base_server,$base_host,$base_user,$user;
        require("../db_fghi.php");   
		
	 	$sql = "select * from usuario
	 			where us_login='".trim($_SESSION['nw_user'])."'
	 	";
		$rlt = db_query($sql);
		if($line = db_read($rlt))
		{
			$pass_logado = $line['us_senha'];
		}
		if((strtoupper(trim($pass))==strtoupper(trim($pass_logado))) and (strtoupper(trim($login))==trim($_SESSION['nw_user']))){
			return(1);
		}else{
			return(0);
		}
	 }	
			
}