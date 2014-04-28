<?php
/**
 * @Author: Willian Fellipe Laynes <willianlaynes@hotmail.com>
 * Data de criação: 19/06/2013
 * PS035 - Gestão da capacitação Master
 * @Version: v0.13.24
 */

class master
{
	//propriedades
	public $nome='';
	public $codigo='';
	
	var $data;
	var $hora;
	var $turma;
	var $curso;
	var $status;
	var $instrutor;
	
	//métodos
	function recupera_data_hora_turma($turma)
	{
			/* Query */
			$sql = "select * from capacitacao_agenda_master where ca_codigo= '$turma'";
			
			/* Execução */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			
			if ($line = db_read($rlt)) {
						 
					$this->data=$line['ca_data'];
					$this->hora=$line['ca_hora'];
					$this->turma=$line['ca_codigo'];
					$this->curso=$line['ca_curso'];
					
					return(1);
			} else {
					return (0);
			}

		
	}
		
	/*Abre nova turma para agendamento*/		
	function nova_turma($data, $curso,$hora, $instrutor)
		{
			
			/* tratamento dos paremetros de delimitacao por data */
			if ($data<date('Ymd')) {
				return('Data inferior a data atual!!');
			};
			
			
			/* Query */
			$sql = "select * from capacitacao_agenda_master where ca_curso= '$curso' and 
			ca_data='$data' and ca_hora='$hora'";
			
			
			
			/* Execução */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			
			if ($line = db_read($rlt)) {
						 
					echo "Já existe esta Turma!!";
					$sx = '<table width="98%" align="center">';
					$sx .= '<TD> CURSO <TD>  DATA '; 
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>'.$line['ca_codigo'];
					$sx .= '<TD>'.stodbr(sonumero($line['ca_data']));
					
					
				return($sx);
				
				
			} else {
				
				$sql = "insert into capacitacao_agenda_master
						(ca_curso, ca_presenca, ca_data, 
						ca_hora, ca_status, ca_codigo)
						values
						('$curso',0,$data,
						'$hora','A','')";
   
			
			$rlt = db_query($sql);
			
			$this->updatex();
			return('Agendamento concluído com sucesso!!');
			}
			/* Fim */
		
		
		}
	function desmarca_turma_cliente($cliente,$turma)
		{
			/* Busca codigo do curso */
			$sql = "select ca_curso from capacitacao_participacao_master
						inner join capacitacao_agenda_master on ca_codigo = cp_turma
							where cp_cliente = '$cliente' and cp_turma = '$turma' 
						";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					/* recupera o codigo */
					$curso = $line['ca_curso'];
					
					/* lista todos as turmas agendadas deste cliente nos cursos */
					$sql = "select * from capacitacao_participacao_master
								inner join capacitacao_agenda_master on ca_codigo = cp_turma
								where cp_cliente = '$cliente' and ca_curso = '$curso' 
										and cp_status <> '0'
								";			
					$rlt2 = db_query($sql);
					/* mostra todos os cursos marcados */
					while ($line2 = db_read($rlt))
					{
						$sql = "update capacitacao_participacao_master set cp_status = 0 where cp_turma = '".$line2['cp_turma']."' ";
						echo '<BR>'.$sql;
					}
					
				}
			return(0);
		}

	/*Cadastrar consultora em alguma turma*/	
	function agendar($turma,$cliente)
		{
			if ($this->recupera_data_hora_turma($turma)) {
				/* Query */
				$sql="select * from capacitacao_participacao_master 
					  where cp_status	= '1' and
					  		cp_cliente 	= '$cliente' and 
					  		cp_turma 	='$turma'";
				
				 /* Execução */
				$rlt = db_query($sql);
				if($line=db_read($rlt)) {
					
					return("Consultora já cadastra nesta turma!!");
					
				} else {
							$acao="agendar";
							/* Query cancelamento de agendamentos futuros a data cadastrada, caso seja o mesmo curso */
							$this->desmarca_turma_cliente($cliente,$turma);
														
							$this->historia_registrar ($data, $cliente,$acao,$historico);
												
							/* Query agendamento da clinte*/	
							$sql = "insert into capacitacao_participacao_master
										(cp_cliente, cp_data, cp_status,cp_turma)
										values
										('$cliente',".date('Ymd').",'1','$turma')";
   				
   							$rlt = db_query($sql);
							
							$this->atualizabd();
							   						
							return("Agendado com sucesso!!");
					
						
				}
			}
			
		return ("Cadastrado com sucesso!!");
		}
	/*Mostrar um relatório com os agendados em um período $d1 - $d2, sendo o $curso opcional*/	
	function participacao($d1=19000101,$d2=20500101,$curso,$turma)
		{
			$this->status=$status; 
			
			if(strlen($curso)==0) { $st.=''; } else {$st .=" and ca_curso = '$curso'";} 
			if(strlen($turma)==0) { $st.=''; } else {$st .=" and ca_codigo = '$turma'";} 
			
			$sql = "SELECT * FROM ( select * from capacitacao_participacao_master as tb01 
							inner join capacitacao_agenda_master on ca_codigo = cp_turma 
							inner join cadastro on cp_cliente=cl_cliente 
							WHERE 	ca_data >= '$d1' and 
									ca_data <= '$d2' and 
									cp_status <> '0'      and 
									ca_presenca <> '0' $st) as tb03 
									left join( select 	cp_cliente as cliente, 
														ca_data as feito,
														ca_curso as curso, 
														cp_status as participacao 
														from capacitacao_participacao_master 
														inner join capacitacao_agenda_master on ca_codigo = cp_turma 
														where cp_status <> '0') as tb02 on tb02.cliente = tb03.cp_cliente 
														order by ca_data, cl_nome, curso";
			  
			
			/* Execução */
			$rlt = db_query($sql.' limit 1');
			$line = db_read($rlt);
			/* Montagem da tela de saida */
			if (($d1==19000101)or($d2==20500101)) {$d1=$line['ca_data'];$d2=$line['ca_data'];} 
			
			if (strlen($curso)==1) {
			$str = '<h3>Participações no período de '.stodbr(sonumero($d1)).' a '.stodbr(sonumero($d2)).'</h3>';
			} else {
			$str = '<h3>Participações nas turmas de '.$curso.' no período de '.stodbr(sonumero($d1)).' a '.stodbr(sonumero($d2)).'</h3>'; 
			}
			
			$tx  = '<table width="98%" align="center" class="tabela00">';
			$tx .=  $str;
	
			$tx .= '<TH> Código 
					<TH> Consultora                                    
					<TH> Curso
					<TH> Data  
					<TH> Hora
					<TH> Status 
					<TH> Capacitação'; 
			$xnome = '';
			$xdata = 0;
			/* Reconsulta Query */
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					
					$nome = trim($line['cl_nome']);
					$data = $line['ca_data'];
					
					if (($nome != $xnome) or ($xdata != $data))
					{
							$tot++;		
							$tx .= $this->mostra_linha($line);
							$tx .= '<TD class="tabela01" align="center">';
							$xnome = $nome;
							$xdata = $data;
							
					}	
					/*	
						$title = trim($line['curso']).' ';
							switch ($line['participacao'])
							{
								case '1':$title .= ' AGENDADO para ';	break;
								case '2':$title .= ' CONCLUÍDO em ';	break;
								case '3':$title .= ' CONFIRMADO em ';	break;
							}	
							
						$title .= stodbr($line['feito']);
					 
					 
						$tx .='<A HREF="#" title="'.$title.'">';
						$tx .= substr($line['curso'],0,1);
						$tx .= '</A>&nbsp;';
					 */
				}	
			/*Fim */
			
			$tx .= '</table>';
			/* Apresenta somatorias */
			if ($tot==1) {$tx .= '<TR><TD colspan=7><h3>  Total de '.$tot.' consultora.  </h3>';}
			else {$tx .= '<TR><TD colspan=7><h3>  Total de '.$tot.' consultoras.  </h3>';}
			$tx .= '</table>';	
			return($tx);
		
		}
		function cursados($cliente='')
		{
			global $base_name,$base_host,$base_user;
			require("../db_fghi_206_cadastro.php");
			$sql = "select * from capacitacao_participacao
					where cp_cliente='$cliente' and cp_status='B'" ;
			$rlt=db_query($sql);
			while ($line=db_read($rlt)){
					switch (trim($line['cp_curso'])) {
					case 'MKT PESSOAL': 		$tt++; 	break;
					case 'ATD. CLIENTE':		$tt++;	break;
					case 'FINANÇAS PESSOAIS':	$tt++; 	break;
					case 'PRODUTO':				$tt++;	break;
					case 'MOTIVAÇÃO':			$tt++;	break;
			 		}	
			} 
			if($tt==5)
			{
				$sx = 'Capacitação Completa';
			}else{
				$sx = 'Falta(m) '.(5-$tt).' cursos';
		}
		
		return($sx);	
		}

	/*Alterar o status do agendamento*/	
	function alterar_status ($data, $cliente,$curso)
		{
			echo "Ok_alteração";
		}
	/*Insere histórico do agendamento*/	
	function historia_registrar ($data, $cliente,$acao,$historico)
		{
			
			
			$sql = "SELECT * FROM capacitacao_historico_master 
					WHERE h_data = ".date('Ymd')." AND 
						  h_cliente = '$cliente' AND 
						  h_historico = '$historico' AND
						  h_log = '$acao'" ;
						  
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt)!=1)
				{
					$sql = "INSERT INTO capacitacao_historico_master (
										h_cliente, h_data, h_hora, 
										h_log, h_historico, h_status, 
										h_turma, h_curso
										) 
							VALUES ('$cliente',".date('Ymd').",'".date('h:i')."',
									'$acao','$historico','$status',
									'".$this->turma."','".$this->turma."'
							)";
					$rlt = db_query($sql);
		
					return("Registro de log concluído");	
				}	
			
			 /*Fim */
			return("Log já existente");
			

		}
	/*Mostra os 20 últimos historicos da cliente*/	
	function historico_mostrar ($cliente)
		{
				
			//Query
			$sql = "SELECT * FROM capacitacao_historico_master 
					INNER JOIN cadastro on cl_cliente=h_cliente
					WHERE h_cliente = '$cliente' 
					ORDER BY h_data
					LIMIT 20
					";
			
			//Executa query			  
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$cl_nome = $line['cl_nome'];
			
			
			//script para div oculto
			$tx ='	<script type="text/javascript">
				 	function exibe(id) {
						if(document.getElementById(id).style.display=="none") 
						{
							document.getElementById(id).style.display = "inline";
						} else {
							document.getElementById(id).style.display = "none";
						}
					}
					</script>';
					
			$cx .= '<table width="98%" align="center">';
			$cx .= '<TH class="tabelaH" colspan=8>'; 
			
			
			//Coloca link no nome da cliente exibir DIV oculta
			$tx .= '<a href="#" onclick="javascript: exibe(\''.$cliente.'\');">'.$cx.$cl_nome.'</a><br />';
			
			//Cabeçalho da pesquisa
			$sx .= '<table width="98%" align="center" >';
			$sx .= '<TH class="tabelaH"> COD CLIENTE 
					<TH class="tabelaH"> DATA                                    
					<TH class="tabelaH"> HORA 
					<TH class="tabelaH"> LOG  
					<TH class="tabelaH"> HISTORICO 
					<TH class="tabelaH"> STATUS
					<TH class="tabelaH"> TURMA  
					<TH class="tabelaH"> CURSO' 
					; 
			
			//Monta tabela
			while ($line = db_read($rlt))
				{
					
		
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_cliente'];
				$sx .= '<TD class="tabela01" align= "center">'.stodbr(sonumero($line['h_data']));
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_hora'];
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_log'];
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_historico'];
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_status'];
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_turma'];
				$sx .= '<TD class="tabela01" align= "center">'.$line['h_curso'];
					
				}
			
			$sx .= '</table>';
			
			// Todos os dados concatenados na variavel $tx 
			$tx .='<div id="'.$cliente.'" style="display: none;">'.$sx.'</div>';	
			
			// Resposta 
			 /* Fim */
			return($tx);
			
		}

	/* Função dos estatus */
	function status()
		{
			$sta = array('0'=>'Cancelado','1'=>'Agendado','2'=>'Concluído','3'=>'Não compareceu','4'=>'Confirmado');
			return($sta);
		}
	function cp_status()
		{
			$sx = '$O : &1:Agendado&2:Concluído&0:Cancelado&3:Não compareceu&4:Confirmado';
			return($sx);			
		}
	
	/*Mostra uma linha com informações*/	
	function mostra_linha ($line)
		{
				/*$link = '<span onclick="newwin2(\'master_agendamento_alterar.php?dd0='.$line['id_cp'].'&dd1='.stodbr($line['ca_data']).'&dd2='.$line['cp_status'].'\',600,400);">';*/
				$link = '<a href="#" onclick="newwin2(\'master_agendamento_alterar.php?dd0='.$line['id_cp'].'&dd4='.stodbr($line['ca_data']).'&dd5='.$line['cp_status'].'\',600,400);" class="link">';
				
				$st = trim($line['cp_status']);				
				$status = $this->status();
				
				/* Tirar link se cancelado ou concluído */
				if ($st=='0') { $link = ''; }
				if ($st=='2') { $link = ''; }
							
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD class="tabela01" align="center">'.$line['cl_cliente'];
				$sx .= '<TD class="tabela01" align="left">'.$line['cl_nome'];
				$sx .= '<TD class="tabela01" align="left">'.$line['ca_curso'];
				$sx .= '<TD class="tabela01" align="center">'.stodbr(sonumero($line['ca_data']));
				$sx .= '<TD class="tabela01" align="center">'.$line['ca_hora'];
				$sx .= '<TD class="tabela01" align="center">'.$link.$status[$st].'</a>';
				$sx .= '<TD class="tabela01" align="left">'.$this->cursados($line['cl_cliente']).'</a>';
						
			return($sx);


		}
	/*Mostrar em formato calendário as capacitações aegndadas com o número de inscritos agendados que participaram*/	
	function agenda_mes ($mes,$ano)
		{
		
		$diasmes = date('t', mktime(0,0,0,$mes,1,$ano)); 
		$imes 	 = date('w', mktime(0,0,0,$mes,1,$ano));
		$data	 = mktime(0,0,0,$mes,1,$ano);
		
		$cursos = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','');
		
		$sql = "select * from capacitacao_agenda_master 
				where ca_data >= ".strzero($ano,4).strzero($mes,2)."01 and 
					  ca_data <= ".strzero($ano,4).strzero($mes,2)."99";
		
		$rlt = db_query($sql);
		while ($line = db_read($rlt))
		{
			$sql2 = "select cl_nome, cl_cliente from capacitacao_participacao_master 
					left join cadastro on cp_cliente=cl_cliente
					where cp_turma='".$line['ca_codigo']."' and 
						  cp_status <>'0' ";
			
			$rlt2 = db_query($sql2);
			$tx = '';
			$title = '';
			
			while ($line2 = db_read($rlt2))
			{	
				$title .= trim($line2['cl_nome'])." - ";
				$title .= $line2['cl_cliente'].chr(13);
					
			}
			$link = '<A HREF="master_rel_participacao_cod.php?dd1='.stodbr($line['ca_data']).'&dd2='.stodbr($line['ca_data']).'&dd3='.trim($line['ca_codigo']).'&curso='.trim($line['ca_curso']).'&acao=consulta" title="'.$title.'">';
			$tx .= $link;
			$tx .= '</BR>'.$line['ca_hora'].'-'.substr($line['ca_curso'],0,10).' - '.$line['ca_presenca'].$tx;
			$tx .= '</A>&nbsp;';
			
			$stdata = round(substr($line['ca_data'],6,2));	
			$cursos[$stdata] .= $tx; 			
		}

		/* Datas anteriores e posteriores */
		$ano_anterior = $ano; $ano_posterior = $ano;
		$mes_anterior = round($mes) - 1; if ($mes_anterior == 0) { $mes_anterior = 12; $ano_anterior--; }
		$mes_posterior = round($mes) + 1; if ($mes_posterior == 13) { $mes_posterior = 1; $ano_posterior++; }  
		$linka = '<A HREF="'.page().'?dd1='.strzero($mes_anterior,2).'&dd2='.$ano_anterior.'&acao=busca">';
		$linkp = '<A HREF="'.page().'?dd1='.strzero($mes_posterior,2).'&dd2='.$ano_posterior.'&acao=busca">';
					
		/* Monta calendario */
		$sx = "<h3>Calendário  - Cursos agendados no mês ".$mes."/".$ano."</h3>";
		$sx .= '<table width=1000 align="center" border=1>';
		$sx .= '<TR>';
		$sx .= '<TD class="tabela00">'.$linka.'<img src="../img/icone_arrow_calender_left.png" height="20" border=0></A>';
		$sx .= '<TD colspan=5  class="tabela00" >';
		$sx .= '<TD class="tabela00">'.$linkp.'<img src="../img/icone_arrow_calender_right.png" height="20" border=0 align="right"></A>';
		$sx .= '<TR '.coluna().'>';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> DOMINGO';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SEGUNDA';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> TERÇA';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> QUARTA';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> QUINTA';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SEXTA';
		$sx .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SÁBADO';
		$sx .= '<TR '.coluna().'>';
		
		$xmes = date("m",$data);
		$id = 0;
		
		$sx .= '<TR>';
		if (date("w",$data) > 0) { $sx .= '<TD colspan='.date("w",$data).'">'; }
			
		while ($xmes == date("m",$data)) 
			{
				/* Nova turma */
				$linkn = '<A HREF="master_nova_turma.php?dd2='.date("d/m/Y",$data).'">';
				
				/* Se for domingo cria nova linha */
				if ((date("w",$data)==0) and (round(date("d",$data)) > 1)) { $sx .= '<TR '.coluna().'>'; }
				
				/* Insere informacoes do dia*/
				$sx .= '<TD class="tabelaCL" align="center" width = 10% height = 70px>';
				$sx .= $linkn.date("d",$data).'</A>';
				
				/* Mostra cursos já marcados */
				$sx .= $cursos[round(date("d",$data))];
				$sx .= $cursos[$dias];
				
				/* Incrementa um dia */
				$data += 24*60*60; 
			}		
		$sx .= '</table>';	
		
		return($sx);	

		}	
	/*Mostra lista das proximas capacitações abertas*/	
	function proximos ()
		{
			/* Query */
			$sql = "select * from capacitacao_agenda_master 
					where ca_data >= '".date('Ymd')."' and 
						  ca_status='A' order by ca_data, ca_hora";     
			
			/* Execução */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			$sx = '<table width="98%" align="center">';
			$tot = 0;
			$tots = 0;
			$sx .= '<TH class="tabelaH"> Curso 
					<TH class="tabelaH"> Data 
					<TH class="tabelaH"> Hora 
					<TH class="tabelaH"> Quantidade '; 
					
			while ($line = db_read($rlt))
				{
					$tot++;	
					$link = '<span onclick="newwin2(\'master_agendar.php?dd0='.$line['id_cp'].'\',600,400);">';
					$link = '<a href="#" onclick="newwin2(\'master_agendar.php?dd0='.$line['id_cp'].'\',600,400);">';
					
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD class="tabela01" align= "left">'.$link.$line['ca_curso'].'</span>';
					$sx .= '<TD class="tabela01" align= "center">'.stodbr(sonumero($line['ca_data']));
					$sx .= '<TD class="tabela01" align= "center">'.$line['ca_hora'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['ca_presenca'];
					
					$tots= $tots+$line['ca_presenca'];
				}	
			$sx .= '</table>';
			
			
			$sx .= '</table>';
			/* Apresenta somatorias */
			if ($tot==1) {$sx .= '<TR><TD colspan=4><h3>  Total de '.$tot.' capacitação programada.  </h3>';}
			else {$sx .= '<TR><TD colspan=7><h3>  Total de '.$tot.' capacitações programadas.  </h3>';}
			$tx .= '</table>';	
			 
			/* Fim */
			return($sx);
			}
	function participantes_turma()
		{
			
			$sql = "SELECT cl_nome, cp_turma FROM capacitacao_participacao_master 
					INNER JOIN cadastro ON cl_cliente=cp_cliente
					WHERE cp_turma = '$turma' AND cp_status='1'";     
			
			/* Execução */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			
			
			$sx .= '<table width="98%" align="center">';
			$tot = 0;
			$tots = 0;
			$sx .= '<TH class="tabelaH"> CURSO <TH class="tabelaH">  DATA <TH class="tabelaH"> HORA <TH class="tabelaH"> QUANTIDADE '; 
			while ($line = db_read($rlt))
				{
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD class="tabela01" align= "left">'.$link.$line['ca_curso'].'</span>';
					$sx .= '<TD class="tabela01" align= "center">'.stodbr(sonumero($line['ca_data']));
					$sx .= '<TD class="tabela01" align= "center">'.$line['ca_hora'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['ca_presenca'];
					
				}	
			/* Fim */
			
			return($sx);

		}
	function updatex()
		{
			$dx1 = 'ca_codigo';
			$dx2 = 'ca';
			$dx3 = 7;
			$sql = "update capacitacao_agenda_master set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
		}
			
	function atualizabd()
		{
			//Recalcula tabela capacitacao_participacao_master
			$sql = " SELECT * FROM capacitacao_participacao_master
									INNER JOIN capacitacao_agenda_master on ca_codigo=cp_turma
									WHERE 	ca_data>".date('Ymd')." and 
						  					cp_status='2'";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$sql2 = "UPDATE capacitacao_participacao_master 
						 SET cp_status   = '0'
						 WHERE id_cp 	= '".$line['id_cp']."'";
				
				db_query($sql2);
							 
			}
				
			//Recalcula tabela capacitacao_participacao_master, evitando registros replicados	
			$sql = "SELECT cp_cliente, cp_turma,count(*)
					FROM capacitacao_participacao_master
					WHERE cp_status = '1'
					GROUP BY cp_turma,cp_cliente  
					HAVING COUNT(*) > 1	";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{ 
				$sql2 = "SELECT * FROM capacitacao_participacao_master
						 WHERE cp_cliente 	= '".$line['cp_cliente']."' and
						 	   cp_turma 	= '".$line['cp_turma']."'";
				$rlt2 = db_query($sql2);
				
				$count=0;	
				while ($count<$line['count']-1)
				{
					$line2 = db_read($rlt2);
					$sql3 = "UPDATE capacitacao_participacao_master 
						 	SET cp_status   = '0'
						 	WHERE id_cp 	= '".$line2['id_cp']."'";
					db_query($sql3);
					$count++;	
				}
			}	
			
			
			//Recalcula tabela capacitacao_agenda_master	
			$sql3 = "UPDATE capacitacao_agenda_master 
					SET ca_presenca = 0";	
			db_query($sql3);
			
			$sql = "SELECT cp_turma,COUNT(cp_turma) 
					FROM capacitacao_participacao_master 
					WHERE cp_status<>'0' 
					GROUP BY cp_turma ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$sql2 =	"UPDATE capacitacao_agenda_master 
						 SET ca_presenca =".$line['count'].
					 	"WHERE   ca_codigo ='".$line['cp_turma']."'";
				$rlt2 = db_query($sql2);	 					  				   	
			}
			
			return('');
			
		}
		
		/*Devolve a descrição do status*/
		function status_descricao($status,$total)
		{
			switch ($status) {
				
				case '0':
					$st ='Cancelado(s) - '.$total;
					
					break;
				case '1':
					$st ='Agendado(s) - '.$total;
					
					break;
				case '2':
					$st ='Concluído(s) - '.$total;
					
					break;
				case '3':
					$st ='Não Compareceu(ram) - '.$total;
					
					break;
				case '4':
					$st ='Confirmado(s) - '.$total;
					
					break;
				
				default:
					$st ='Sem situação verificar com a T.I.';
					break;
			}
			
			return($st);
		}
		
		function grafico_mes($mes,$ano)
		{
		    /*Modelo do gráfico  ex: PieChart, ColumnChart, BarChart, AreaChart*/
		   	$modelo = 'PieChart';
		   	/*Nome do gráfico*/
		   	$titulo = 'Gráfico mensal das capacitações';
			/*query*/
			$sql = "select distinct count(cp_status),cp_status 
					from capacitacao_participacao_master
					inner join capacitacao_agenda_master on ca_codigo=cp_turma
					where 	ca_data >= ".strzero($ano,4).strzero($mes,2)."01 and 
					  		ca_data <= ".strzero($ano,4).strzero($mes,2)."99  and
					  		cp_status <> '0'
			 		group by cp_status   
					";
			  
			/* Execução */
			$rlt = db_query($sql);
			$sx = "['Situação', 'Quantidade'],";			
			
			/*Construção do gráfico*/
			while ($line = db_read($rlt))
				{
					$tipo = $this->status_descricao($line['cp_status'],$line['count']);
					$total = round($line['count']);
					$sx .="['".$tipo."',  ".$total."],".chr(13);
				}
			
			  
			/* Fim */
			$grx=$sx;
			
			return array ($grx,$modelo,$titulo);
			
		}
		
		function grafico_dia($mes,$ano)
		{
		   	/*Modelo do gráfico  ex: PieChart, ColumnChart, BarChart, AreaChart*/
		   	$modelo = 'ColumnChart';
		   	/*Nome do gráfico*/
		   	$titulo = 'Gráfico diário das capacitações';
			/*query*/
			$sql = "select ca_data, count(cp_status),cp_status 
					from capacitacao_participacao_master 
					inner join capacitacao_agenda_master on ca_codigo=cp_turma  
					where 	ca_data >= ".strzero($ano,4).strzero($mes,2)."01 and 
					  		ca_data <= ".strzero($ano,4).strzero($mes,2)."99  and
					  		cp_status <> '0'
			 		group by ca_data, cp_status 
					order by ca_data, cp_status";
			
			//$agend = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$conc = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$naocomp = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			/* Execução */
			$rlt = db_query($sql);
			/*Construção do gráfico*/
			while ($line = db_read($rlt))
				{
					$tipo = round($line['cp_status']);
					$total = round($line['count']);
					$dia = round(substr($line['ca_data'], 6,2)) ;
					switch ($tipo) {
				//		case '1':
				//			$agend[$dia]=$agend[$dia]+$total;
				//			break;
						case '2':
							$conc[$dia]=$conc[$dia]+$total;
							break;
						case '3':
							$naocomp[$dia]=$naocomp[$dia]+$total;
							break;
						
						default:
							break;
					}
					
				}
				
				
			/* Monta dados para grafico 
			 * A montagem deve ser feita no padrao definido na variavel $modelo
			 * Site com definiciçoes dos modelos : http://code.google.com/apis/ajax/playground
			 * */
			$sx = "['Dia', 'Concluídos', 'Não compareceram'],";	
			$diasmes = date('t', mktime(0,0,0,$mes,1,$ano)); 		
			
			for ($r=1;$r<$diasmes+1;$r++)
				{
					if (($agend[$r]!=0)or($conc[$r]!=0)or($naocomp[$r]!=0)) 
					{
						$sx .="['".$r."/$mes/$ano',".$conc[$r].",   ".$naocomp[$r]."],".chr(13);				
					}
					
				}
			  
			/* Fim */
			$grx=$sx;
			
			return array ($grx,$modelo,$titulo);
		}

		function grafico_comparativo($periodo)
		{
		   	
					switch ($periodo) {
						case '1':
							echo "Últimos 5 anos";
							$dataatual = date('Ymd');
							$data= array(1 =>date("Y") ,2 =>date("Y")-1 ,3 =>date("Y")-2 ,4 =>date("Y")-3 ,5 =>date("Y")-4 , );;
							for ($i=0; $i <= 5 ; $i++) { 
								
							$sql = "select  count(cp_status),cp_status 
									from capacitacao_participacao_master 
									inner join capacitacao_agenda_master on ca_codigo=cp_turma 
									where ca_data >= ".$data[i+1]."0701 and ca_data <=".$data[i]."0799 and cp_status <> '0' 
									group by cp_status, cp_status order by  cp_status";
							
							echo "$sql";
							}
							return(0);
							break;
						case '2':
							echo "Últimos 12 meses";
							return(0);
							break;
						case '3':
							echo "Últimos 6 meses";
							return(0);
							break;
						case '4':
							echo "Último mês";
							return(0);
							break;
						default:
							return(0);
							break;
					}
					
		}
				
			


	/*	
		function grafico_barras($grs) 
		{
				
				$sx = '
		        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
		    	<script type="text/javascript">
			     	google.load("visualization", "1", {packages:["corechart"]});
      	 			google.setOnLoadCallback(drawChart);
			        function drawChart() {
				 	    var data = google.visualization.arrayToDataTable([
				        '.$grs.'   
			        ]);
		        var options = {
          		title: \'Total de Ligações\',
          		hAxis: {title: \'Horário\', titleTextStyle: {color: \'red\'}}
        		};

        		var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div\'));
        		chart.draw(data, options);
      			}
      			</script>    
    			<div id="chart_div" style="width: 1200px; height: 600px;"></div>
    			';
				return($sx);
		}
		
		function grafico_pizza($grs,$titulo)
		{
			
			$sx =' 	
	    	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    		<script type="text/javascript">
      			google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    			</script>
    			<script type="text/javascript">
      			function drawVisualization() {
        			var data = google.visualization.arrayToDataTable([
         			'.$grs.'
          		]);
        		new google.visualization.PieChart(document.getElementById(\'visualization\')).
            	draw(data, {title:"'.$titulo.'"});
      			}
            
            	google.setOnLoadCallback(drawVisualization);
    		</script>
  			<body style="font-family: Arial;border: 0 none;">
    		<div id="visualization" style="width: 1400px; height: 800px;"></div>
  			';
			return($sx);
			
		}*/

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