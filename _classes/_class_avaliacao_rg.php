<?php
class avaliacao_rh
	{
		var $formulario;
		var $avaliador;
		var $avaliado;
		var $motivo;
		var $line;
		
		var $tabela =  "avaliacao";	
		
		function cp_conceitos()
			{
				$cp = array();
				array_push($cp,array('$H8','id_c','',False,True));
				array_push($cp,array('$H8','c_codigo','',True,True));
				array_push($cp,array('$S100','c_nome','Critério',False,True));
				array_push($cp,array('$T60:5','c_descricao','Descrição',False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','c_ativo','Ativo',False,True));
				return($cp);
			}		
		
		function cp_form()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ff','',False,True));
				array_push($cp,array('$Q c_nome:c_codigo:select * from criterios order by c_nome','ff_campo','Critério',True,True));
				array_push($cp,array('$I8','ff_peso','Peso',False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','ff_ativo','Ativo',True,True));
				array_push($cp,array('$[1-99]','ff_ordem','Ordem',False,True));
				array_push($cp,array('$C1','ff_corte','Corte',False,True));
				return($cp);
			}

		function row_form()
			{
				global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;

				$tabela = "(select * from formulario_campos
							inner join criterios on ff_campo = c_codigo
							) as tabela
				";
				$label = "Formulários Campos";
				/* Páginas para Editar */
	
				$cdf = array('id_ff','c_nome','ff_campo','ff_ativo','ff_peso','ff_ordem');
				$cdm = array('ID','Codigo','nome','Ativo','ordem');
				$masc = array('','','','','','','','','');
				return(True);	
			}

		function row()
			{
				global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;

				$tabela = "formulario";
				$label = "Formulários";
				/* Páginas para Editar */
	
				$cdf = array('id_fm','fm_codigo','fm_nome','fm_ativo');
				$cdm = array('ID','Codigo','nome','Ativo');
				$masc = array('','','','','','','','','');
				return(True);	
			}
		
		function row_criterios()
			{
				global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;

				$tabela = "criterios";
				$label = "Critérios";
				/* Páginas para Editar */
	
				$cdf = array('id_c','c_codigo','c_nome','c_ativo');
				$cdm = array('ID','Codigo','nome','Ativo');
				$masc = array('','','','','','','','','');
				return(True);	
			}
		
		function mostra_ficha()
			{
				global $tab_max;
				$log = $this->avaliado;						
				$sql = "select *, a_avaliado = a_avaliador as tpx from avaliacao
						where a_avaliado = '$log' and a_status = 'B'
						order by tpx desc, a_formulario ";					
				$rlt = db_query($sql);
				$ar = array();
				$obs = '<UL>';
				while ($line = db_read($rlt))
					{
						$form = $line['a_formulario'];
						if (strlen(trim($line['a_obs1'])) > 0)
							{
							$obs .= '<LI>'.$line['a_obs1'].'</LI>';
							}
						array_push($ar,$line);
					}
				$obs .= '</UL>';
					
				$sql = "select * from formulario 
						inner join formulario_campos on ff_formulario = fm_codigo
						inner join criterios on c_codigo = ff_campo
						where ff_ativo=1 
						and ff_formulario = '$form'
						order by ff_ordem
				";
				$rlt = db_query($sql);
				
				$sx = '<table width="'.$tab_max.'" class="lt1">';
				$av = 0;
				$sx .= '<TR><TH>it<TH>Critério<TH>peso';
				$sx .= '<TH align="center">auto avaliação';
				for ($rr=0;$rr < count($ar);$rr++)
					{
						$line = $ar[$rr];
						$avi = trim($line['a_avaliador']);
						$log = trim($line['a_avaliado']);
						if ($avi != $log)
						{
							$av++;
							$sx .= '<TH align="center" colspan=1>avaliador<BR>#'.$av.'#';
						}
					}
				$sx .= '<TH>Notas';
				$m1 = 0;
				$m2 = 0;
				$m3 = 0;
				$mf = 0;
				$totpi = 0;
				$totpa = 0;
				$toti = 0;
				$medg = 0;
				while ($line = db_read($rlt))
					{
						$m3++;
						$m4 = 0;
						$m5 = 0;
						$m6 = 0;
						$m7 = 0;
						$formn = $line['fm_nome'];
						$ord = $line['ff_ordem'];
						$sx .= '<TR>';
						$sx .= '<TD align="right" width="5%">';
						$sx .= $ord;
						$sx .= '<TD>';
						$sx .= $line['c_nome'];
						//$sx .= $line['c_codigo'];
						$sx .= '<TD align="center">';
						$sx .= $line['ff_peso'].'x';
						
					
						
						for ($rr=0;$rr < count($ar);$rr++)
							{
								$log1 = trim($ar[$rr]['a_avaliado']);
								$log2 = trim($ar[$rr]['a_avaliador']);
						 
								if (($rr==0) and ($log1 != $log2))
									{ $sx .= '<TD align="center">-'; }	

									$m4 = $m4 + $ar[$rr]['a_av'.round($ord)]; 
									$m6++; 
									$m7 = $m7 + ($ar[$rr]['a_av'.round($ord)] * $line['ff_peso']); 
									
									$sx .= '<TD align="center">';
									$sx .= $ar[$rr]['a_av'.round($ord)];
							}
						$sx .= '<TD align="center">';
						
						$sx .= '<B>'.number_format($m4 / $m6,1,',','.').'</B>';
						$medg = $medg + ($m4 / $m6) * $line['ff_peso'];
						$totpi = $totpi + $m7;
						$toti = $toti + $line['ff_peso'];
						$totpa = $totpa + $m5;
					}
				$sx .= '<TR><TD colspan=10><BR><BR>';
				
				$sx .= '<table border=1 width="100%" class="lt1">';
				$sx .= '<TR align="center"><TD>Média Geral: <B>'.number_format($medg / $toti,1,',','.').'</B>';
				
				$av = count($ar) -2;
				
				if ($toti > 0)
					{
					$sx .= '<TD width="50%">Pontos total: <B>'.number_format($medg,2,',','.').'</B>';
					} else {
						$sx .= '<TD width="50%">NA';
					}
				$sx .= '</table>';
				$sx .= '<H3>Comentários</h3>';
				$sx .= $obs;
				$sx .= '</table>';
				$sa = '<h2>'.$formn.'</h2>';
				$sa .= $sx;
				return($sa);
			}
		function nomes()
			{
				$nome = array();
				$sql = "select * from usuario where us_status = 'A' order by us_login ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						array_push($nome,array(trim($line['us_login']),trim($line['us_nomecompleto'])));
					}
				return($nome);
			}
		function mostra_nome($array,$log)
			{
				$nome = 'not found';
				for ($r=0;$r < count($array);$r++)
					{
						if (trim($array[$r][0]) == trim($log))
							{ $nome = trim($array[$r][1]); }
					}
				return($nome);
			}
		function resumo_avaliados()
			{
				global $dd,$nome;
				$sql = "select count(*) as total, a_status from avaliacao
						group by a_status
						order by a_status ";
				$rlt = db_query($sql);
				$sx .= '<H2>Resumo das Avaliações</H2>';
				$sx .= '<table width="400" border=1>';
				while ($line = db_read($rlt))
					{
						$sta = trim($line['a_status']);
						if ($sta == '@') { $sta = 'Não avaliado'; }
						if ($sta == 'B') { $sta = 'Finalizado'; }
						$sx .= '<TR><TD>'.$line['total'].'</td>';
						$sx .= '<TD>'.$sta;
					}
				$sx .= '</table>';
				/* */
				$wh = '';
				if ($dd[10]==1)
					{ $wh = " where a_status = '@' "; }
				if ($dd[10]==2)
					{ $wh = " where a_status = 'B' "; }
									/* AVALIADORES */
				$sx .= '<H2>Avaliadores</H2>';
				$sql = "select count(*) as total, a_status, a_avaliado from avaliacao
						$wh
						group by a_status, a_avaliado
						order by a_avaliado, a_status ";
				$rlt = db_query($sql);
				$sx .= '<H2>Resumo dos avaliados</H2>';
				$sx .= '<table width="704" border=1 class="lt0">';
				$xlog = "x";
				
				while ($line = db_read($rlt))
					{
						$ate = $line['a_avaliado'];
						if ($xlog != $ate)
							{
								$xlog = $ate;
								$sx .= '<TR>
									<TD>'.$line['a_avaliado'].'</td>
									<TD>'.$this->mostra_nome($nome,$line['a_avaliado']).'</TD>';
							}
						$sta = trim($line['a_status']);
						if ($sta == '@') {
											 $sta = 'Não avaliado';
											 $link = ''; 
										  }
						if ($sta == 'B') {
											$sta = 'Finalizado';
											$link = '<A HREF="avaliacao_fichas.php?dd0='.trim($line['a_avaliado']).'" target="_new">'; 
										  }
						
						$sx .= '<TD>'.$link.$line['total'].' '.$sta.'</A>';
					}
				$sx .= '</table>';				
				return($sx);
			}		
		
		function resumo_avaliacaoes()
			{
				global $dd,$nome;
				$sql = "select count(*) as total, a_status from avaliacao
						group by a_status
						order by a_status ";
				$rlt = db_query($sql);
				$sx .= '<H2>Resumo das Avaliações</H2>';
				$sx .= '<table width="400" border=1>';
				while ($line = db_read($rlt))
					{
						$sta = trim($line['a_status']);
						if ($sta == '@') { $sta = 'Não avaliado'; }
						if ($sta == 'B') { $sta = 'Finalizado'; }
						$sx .= '<TR><TD>'.$line['total'].'</td>';
						$sx .= '<TD>'.$sta;
					}
				$sx .= '</table>';
				/* */
				$wh = '';
				if ($dd[10]==1)
					{ $wh = " where a_status = '@' "; }
				if ($dd[10]==2)
					{ $wh = " where a_status = 'B' "; }
									/* AVALIADORES */
				$sx .= '<H2>Avaliadores</H2>';
				$sql = "select count(*) as total, a_status, a_avaliador from avaliacao
						$wh
						group by a_status, a_avaliador
						order by a_avaliador, a_status ";
				$rlt = db_query($sql);
				$sx .= '<H2>Resumo das Avaliações</H2>';
				$sx .= '<a href="'.page().'?dd10=1">Não Avaliados</A>';
				$sx .= '&nbsp;|&nbsp;';
				$sx .= '<a href="'.page().'?dd10=2">Avaliados</A>';
				
				$sx .= '<table width="704" border=1 class="lt0">';
				while ($line = db_read($rlt))
					{
						$sta = trim($line['a_status']);
						if ($sta == '@') { $sta = 'Não avaliado'; }
						if ($sta == 'B') { $sta = 'Finalizado'; }
						$sx .= '<TR>
								<TD>'.$this->mostra_nome($nome,$line['a_avaliador']).'('.trim($line['a_avaliador']).')</TD>';
						$sx .= '<TD>'.$line['a_avaliado'].'</td>';
						$sx .= '<TD>'.$line['total'].'</td>';
						$sx .= '<TD>'.$sta;
					}
				$sx .= '</table>';				
				return($sx);
			}

		function indicacao_avaliacao_excluir($id)
			{
				$sql = "delete from ".$this->tabela." where id_a = ".round($id);
				$rlt = db_query($sql);
			}
		
		function lista_avaliacoes_aberta($funcionario)
			{
				global $dd;
				$sql = "select * from avaliacao where a_avaliado = '$funcionario'  ";
				$rlt = db_query($sql);
				
				$sx = '<table width="700" class="lt1">';
				$sx .= '<TR><TH>Indicado<TH>Avaliador<TH>Funcionário<TH>Status';
				while ($line = db_read($rlt))
					{
						$sta = $line['a_status'];
						$href="";
						
						if ($sta == '@')
							{
								$href = '<A HREF="avaliacao_indicacao.php?dd1='.trim($dd[1]).'&dd20='.$line['id_a'].'"><font color="red">[EXCLUIR]</A>';
							}
						if ($sta == '@') { $sta = 'Indicado'; }
						if ($sta == 'B') { $sta = 'Avaliado'; }
						
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>'.stodbr($line['a_data']);
						$sx .= '<TD>'.$line['a_avaliador'];
						$sx .= '<TD>'.$line['a_avaliado'];
						$sx .= '<TD>'.$sta;
						$sx .= '<TD>'.$href;
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function indicacao_avaliacao_save($dd)
			{
				global $dd;
				$x1 = trim($dd[1]);
				$x2 = trim($dd[2]);
				$x3 = trim($dd[10]);
				$x4 = trim($dd[5]);
				
				$sql = "select * from avaliacao where a_avaliador = '$x2' and a_avaliado = '$x1' and a_status = '@' ";
				$rlt = db_query($sql);
				
				if (!($line = db_read($rlt)))
				{				
					$sql = "insert into avaliacao (a_data, a_hora, a_avaliador, a_avaliado, 
							a_status, a_motivo, a_avaliado_nome, a_formulario
					) values (".date("Ymd").",'','$x2','$x1','@','','$x3','$x4')";
					$rlt = db_query($sql);
					return(1);
				}
				return(0);
				
			}
		
		function indicacao_avaliacao()
			{
				$cp = array();
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$Q us_nomecompleto:us_login:select * from usuario where us_status = \'A\' order by us_nomecompleto ','','Funcinário','',False,True));
				array_push($cp,array('$Q us_nomecompleto:us_login:select * from usuario where us_status = \'A\' order by us_nomecompleto ','','Avaliador',False,True));
				return($cp);
			}
			
		function indicacao_avaliacao_ext()
			{
				$sql = "select * from formulario where fm_ativo= 1";
				$rlt = db_query($sql);
				
				$op = ' : ';
				while ($line = db_read($rlt))
					{
						$op .= '&'.trim($line['fm_codigo']).':'.trim($line['fm_nome']);
					}
				$cx = array('$O '.$op,'','Formulário',True,True);
				return($cx);
				
			}
			
		function le($id)
			{
				$sql = "select * from ".$this->tabela." where id_a = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->avaliado_nome = trim($line['a_avaliado_nome']); 
						$this->motivo = trim($line['a_motivo']);
						$this->formulario = trim($line['a_formulario']);
						$this->line = $line;
					} else {
						echo 'ERRO DE ACESSO AO FORMULÁRIO';
						exit;
					}
			}
		function minhas_avaliacoes($login)
			{
				$sql = "select * from avaliacao 
						where a_avaliador = '$login' and a_status = '@' order by a_avaliado_nome";
				//echo $sql;
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1">';
				$sx .= '<TR><TD><img src="img/imagem_avalicao.png">';
				$sx .= '<TR><TH>avaliações em aberto';
				$id = 0;
				while ($line = db_read($rlt))
					{
						$id++;
						$link = '/fonzaghi/funcionario/avaliacao.php?dd0='.$line['id_a'];
						$link = '<A HREF="'.$link.'">';
						
						$sx .= '<TR><TD>'.$link.$line['a_avaliado_nome'].'</A>';		
					}
				$sx .= '</table>';
				$sx .= '<BR><BR>';
				if ($id ==0)
					{
						$sx = '';
					}
				
				return($sx);	
			}
		function ficha_avaliador()
			{
				$sx .= '<table width="704" class="lt0">';
				$sx .= '<TR><TD colspan=4>NOME DO FUNCIONÁRIO';
				$sx .= '<TR class="lt4"><TD colspan=4>';
				$sx .= $this->avaliado_nome;
				$sx .= '<TR><TD colspan=4>MOTIVO DA AVALIAÇÃO:';
				$sx .= '<TR class="lt4"><TD colspan=4>';
				$sx .= $this->motivo;
				$sx .= '<TR><TD colspan=4>CARGO:';
				
				$sx .= '</table>';
				return($sx);
			}
		
		function mostra_formulario()
			{
				global $dd,$acao;
				$this->le($dd[0]);
				
						
				$sx .= $this->ficha_avaliador();
				$sql = "
					select * from formulario_campos 
					inner join criterios on c_codigo = ff_campo
					where ff_ativo = 1 and ff_formulario = '".$this->formulario."'
					order by ff_corte, ff_ordem
				";
				$rlt = db_query($sql);
				$sx .= '<form method="get" action="avaliacao.php">';
				$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
				$sx .= '<table class="lt2" width="704">';
				
				$sx .= '<TR class="lt0"><TH colspan=2>';
				$sx .= '<TH colspan=4 align="center">SOFRIVEL';
				$sx .= '<TH colspan=2 align="center">REGULAR';
				$sx .= '<TH colspan=2 align="center">BOM';
				$sx .= '<TH colspan=2 align="center">ÓTIMO';
				$sx .= '<TR><TH>it<TH>Critério';
				$sx .= '<TH>1<TH>2<TH>3<TH>4<TH>5<TH>6<TH>7<TH>8<TH>9<TH>10';
				$id = 0;
				$sqlu = '';
				$corte = 0;
				while ($line = db_read($rlt))
					{
						$cor = '';
						if ($line['ff_corte']==1)
							{
								$cor = '<B>'; 
								if ($corte == 0)
									{ $sx .= '<TR><TD colspan=15><HR><center>Corte<HR>';	}
								$corte = 1; 
							}
						$id++;
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD align="center">';
						$sx .= $id.'.';
						$sx .= '<TD>'.$cor;
						$sx .= tips(trim($line['c_nome']),$line['c_descricao']);
					
						//$sx .= tips('ola');
						if (strlen($acao) > 0)
							{
								$val = trim($_GET['dd'.$line['c_codigo']]);
							} else {
								$val = trim($this->line['a_av'.$id]);
							}
						$sqlu .= ", a_av".$id." = ".round($val);
						$zero = 0;
						for ($r=1;$r <= 10;$r++)
							{
								$check = '';
								if (($val == $r) and (strlen($val) > 0)) { $check = 'checked'; }
								if ($val == 0) {$zero = 1;}
								$sx .= chr(13);
								$sx .= '<TD align="center" width="5">';
								$sx .= '<input type="radio" value="'.$r.'" name="dd'.trim($line['c_codigo']).'" '.$check.'>';
								//$sx .= '&nbsp;';
								//$sx .= $r;
							}
					}
					$sx .= '<TR><TD colspan=20>';
					$sx .= '<textarea cols=60 rows=6 name="dd10">'.$dd[10].'</textarea>';
					$sx .= '</table>';
					$sx .= '<input type="submit" name="acao" value="salvar >>>">';
					$sx .= '<BR><BR><BR>';
				if (strlen($acao) > 0)
					{
		
						$sql = "update ".$this->tabela." ";
						$sql .= " set a_data = ".date("Ymh").", ";
						$sql .= " a_hora = '".date("H:i")."', ";
						$sql .= " a_obs1 = '".$dd[10]."' ";
						if ($zero == 0)
							{ $sql .= ", a_status = 'B' "; } else 
							{ $sql .= ", a_status = '@' "; } 
							
						$sql .= $sqlu;
						$sql .= " where id_a = ".round($dd[0]); 
						$rlt = db_query($sql);
						if ($zero==0)
							{
								redirecina('avaliacao_minhas.php');
							}
						echo 'Salvando...';
					}					
				return($sx);
			}
	}
