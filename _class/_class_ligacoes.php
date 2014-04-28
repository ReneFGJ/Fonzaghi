<?php
    /**
     * Telefonia - Calls
	 * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package telefonia
	 * @subpackage classe
    */

	class ligacoes
	{
		//propriedades
		public $ramal='';
		public $telefone='';
		public $tipo='';
		
		//metodos
		
		/* Lista todas as ligacoes realizadas por um ramal especifico,
		 * devendo cruzar os numeros com os dados da tabela "agenda".
		 * O resultado deve ser em formato tabela */

	function cp_departamento()
		{
			$cp = array();
			array_push($cp,array('$H8','id_dp','',false,True));
			array_push($cp,array('$S80','dp_descricao','Nome do departamento',false,True));
			array_push($cp,array('$H8','dp_codigo','',false,True));
			return($cp);
		}
	function updatex_departamento()
			{
			$dx1 = 'dp_codigo';
			$dx2 = 'dp';
			$dx3 = 4;
			$sql = "update departamento set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
			}
		 
	//departamento
	function row_departamento()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "departamento";
		$tabela = "departamento";
		$label = "Cadastro de Departamentos";
		/* Páginas para Editar */
		$http_edit = 'departamento_ed.php'; 
		$offset = 20;
		$order  = "dp_descricao";
		
		$cdf = array('id_dp','dp_codigo','dp_descricao');
		$cdm = array('ID','Codigo','Descrição');
		$masc = array('','','','','','','','','');
		return(True);
		}
		
	//departamento
	function row_ramais()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "telefone_ramal";
		$tabela = "telefone_ramal";
		$label = "Cadastro de Ramis";
		/* Páginas para Editar */
		$http_edit = 'ramais_ed.php'; 
		$offset = 20;
		$order  = "tr_ramal";
		
		$cdf = array('id_tr','tr_ramal','tr_descricao');
		$cdm = array('ID','Ramal','Local');
		$masc = array('','','','','','','','','');
		return(True);
		}	
		
	function cp_ramais()
		{
			$cp = array();
			array_push($cp,array('$H8','id_tr','',false,True));
			array_push($cp,array('$S80','tr_descricao','Nome do departamento',false,True));
			array_push($cp,array('$S4','tr_ramal','Ramal',false,True));
			array_push($cp,array('$Q dp_descricao:dp_codigo:select * from departamento order by dp_descricao','tr_departamento','Departamento',false,True));
			return($cp);
		}			
	
	function ramais_lista()
		{
			$sql = "select count(*) as total from telefone_ramal 
					where tr_ativo = 1
			";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];

			$sql = "select * from telefone_ramal 
					left join departamento on dp_codigo = tr_departamento 
					where tr_ativo = 'S'
					order by dp_descricao, tr_ramal
			";
			$rlt = db_query($sql);
			$c1='';$c2='';$c3='';
			$sx = '<table width="100%" class="tabela00">';
			$sh = '<TR><TH width="80">Ramal<TH>Descrição';
			$id = 0;
			$dpx = '';
			while ($line = db_read($rlt))
				{
					$dp = trim($line['dp_descricao']);
					if ($dp != $dpx)
						{
							$sx .= '<TR><TD colspan=2 class="lt2">';
							$sx .= $dp;
							$sx .= $sh;
							$dpx = $dp;
						}
					$id++;
					$c = '<TR>';
					$c .= '<TD class="tabela01" align="center">';
					$c .= $line['tr_ramal'];
					$c .= '<TD class="tabela01">';
					$c .= trim($line['tr_descricao']);
					$sx .= $c;
				}
			//$sx .= $c;
			$sx .= '</table>';
			return($sx);
		}
	
	function format_telefone($nr)
		{
			$nr = sonumero($nr);
			
			if ((strlen($nr) == 11 ) and (substr($nr,3,1)=='9')) 
				{ return('<font color="red">('.substr($nr,1,2).') '.substr($nr,3,4).'-'.substr($nr,7,4)).'</font>'; }
			if (strlen($nr) == 8) { return('(41) '.substr($nr,0,4).'-'.substr($nr,4,4)); }
			if (strlen($nr) == 11) { return('('.substr($nr,1,2).') '.substr($nr,3,4).'-'.substr($nr,7,4)); }
			return($nr);
		}
		
	/*
	 * RelatÃ³rio das ligaÃ§Ãµes de um ramal por perÃ­odo
	 * @param integer $d1 Data Inicial
	 * @param int $d2 Data Final
	 * @param string $ramal NÃºmero do Ramal (opcional)
	 * @param string $tipo Tipo de ligaÃ§Ã£o (A-Ativa, R-Receptiva) (opcional)
	 * @result text $result Tela com o resultado
	 */
	function chamada_ramal($d1=0,$d2=0,$ramal='',$tipo='')
		{
			if (strlen($ramal) == 0) { $ramal = $this->ramal; } 
			
			
			$wh = '';
			/* tratamento dos paremetros de delimitacao por data */
			if ($d1 > 0) { $wh .= " and call_data >= '".substr($d1,0,4).'-'.substr($d1,4,2).'-'.substr($d1,6,2)."'"; }
			if ($d2 > 0) { $wh .= " and call_data <= '".substr($d2,0,4).'-'.substr($d2,4,2).'-'.substr($d2,6,2)."'"; }

			/* Query */
			$sql = "select * from calls 
					where call_ramal = '$ramal'
					$wh
			";
			
			/* Execuï¿½ï¿½o */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			$sx = '<table width="700" align="center">';
			$tot = 0;
			$tots = 0;
			//Ligaï¿½ï¿½es efetuadas no período de data1 a data2 pelo ramal.
			
			$sx .= "<h1>Relatï¿½rio de Chamadas por Ramal </h1>";
			$sx .="<h3>Ligaï¿½ï¿½es efetuadas no período de ".stodbr(sonumero($d1))." a ".stodbr(sonumero($d2))." pelo ramal ".$ramal.".</h3>"; 
			$sx .= '<TH class="tabelaH"> RAMAL 
					<TH class="tabelaH"> DATA 
					<TH class="tabelaH"> HORï¿½RIO  
					<TH class="tabelaH"> TELEFONE 
					<TH class="tabelaH"> TEMPO';
					 
			while ($line = db_read($rlt))
				{
					$tot++;
					$tots = $tots + $line['call_seconds'];

					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_ramal'];
					$sx .= '<TD class="tabela01" align= "center">'.stodbr(sonumero($line['call_data']));
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_hora'];
					$sx .= '<TD class="tabela01" align= "center">'.$this->format_telefone($line['call_numero']);
					$sx .= '<TD class="tabela01" align= "right">'.$line['call_seconds'].' seg.';					
								
				}	
			/* Apresenta somatorias */		
			$sx .= '<TR><TD class="tabelaT" colspan=5>Total '.$tot.' com '.$tots.' seg. ';
			if ($tot > 0) { $sx .= ', com '.number_format($tots/$tot,1,',','.').' mï¿½dio por ligaï¿½ï¿½o.'; }
			$sx .= '</table>';
			
			/* Fim */
			return($sx);
		}
		
		
		
/*********Lista todos os nï¿½meros mais discados, sendo o nï¿½mero do ramal opcional.
		 * Deve-se destacar o ramal(opcional) somar a quantidade de ligaï¿½ï¿½es e tempo
		 * e relacionar com a tabela "agenda"*/
		function numeros_mais_discados($d1=0,$d2=0,$ramal='',$tipo)
		{
			/*caso nï¿½o seja digitado o ramal ï¿½ feito a pesquisa geral*/
		
		   $wh = '';
		   if (strlen($ramal) == 0) {  } 
				else {$wh .=" call_ramal='$ramal' and "; } 
			
			if ($tipo == 'T') {  } 
				else {$wh .=" call_tipo='$tipo' and "; } 	
			/* tratamento dos paremetros de delimitacao por data */
			
			if ($d1 > 0) { $wh .= " call_data >= '".substr($d1,0,4).'-'.substr($d1,4,2).'-'.substr($d1,6,2)."'"; }
			if ($d2 > 0) { $wh .= " and call_data <= '".substr($d2,0,4).'-'.substr($d2,4,2).'-'.substr($d2,6,2)."' 
									group by  call_tipo, calls.call_numero ,agenda.age_nome order by count desc"; }

			
			/* Query*/ 
			$sql = "select call_tipo, age_nome, call_numero , count(*) from calls 
					left join agenda on agenda.age_numero=calls.call_numero
					where 
					$wh
			";
		
			/* Execuï¿½ï¿½o */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			$sx  = '<table width="700" align="center">';
			$sx .= "<h1>Relatï¿½rio de Chamadas por Ramal </h1>";
			
			if($tipo=='T'){$st='ativas e receptivas';}
			if($tipo=='A'){$st='ativas';}
			if($tipo=='R'){$st='receptivas';}
			if($ramal==''){$st2='';}else{$st2=' no ramal '.$ramal;}
						
			$sx .= "<h3>Ligaï¿½ï¿½es ".$st." no período de ".stodbr(sonumero($d1))." a ".stodbr(sonumero($d2)).$st2.".</h3>"; 
			$sx .= '<TH class="tabelaH"> TELEFONE <TH class="tabelaH"> QUANTIDADE  <TH class="tabelaH"> STATUS  '; 
		
			$tot = 0;
			$tots = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
			
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD class="tabela01" align= "center">'.$this->format_telefone($line['call_numero']);
					$sx .= '<TD class="tabela01" align= "center">'.$line['count'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_tipo'];
										
				}	
			/* Apresenta somatorias */		
			$sx .= '</table>';
			
			/* Fim */
			return($sx);
		}
		
		
/*******************Mostra em um período todas as ligaï¿½ï¿½es para um determinado nï¿½mero*/
		function relacionamento_telefone($d1,$d2,$telefone,$tipo)
		{
			if (strlen($telefone) == 0) { $telefone = $this->telefone; } 
			
			$wh = '';
			if ($tipo == 'T') {$wh .= "";} else {$wh .="and call_tipo='$tipo' "; } 	
			
			/* tratamento dos paremetros de delimitacao por data */
			if ($d1 > 0) { $wh .= " and call_data >= '".substr($d1,0,4).'-'.substr($d1,4,2).'-'.substr($d1,6,2)."'"; }
			if ($d2 > 0) { $wh .= " and call_data <= '".substr($d2,0,4).'-'.substr($d2,4,2).'-'.substr($d2,6,2)."'"; }

			/* Query */
			$sql = "select call_tipo, call_data, call_numero, age_nome, call_ramal, call_hora, call_duracao, call_seconds  
			  		from calls left join agenda on agenda.age_numero=calls.call_numero 
			  		where call_numero like '%$telefone%' 
			  		$wh
			  		order by call_data desc
			";
			
			/* Execuï¿½ï¿½o */
			$rlt = db_query($sql);
			
			/* Montagem da tela de saida */
			$sx = '<table width="700" align="center">';
			$sx .= "<h1>Relatï¿½rio de Chamadas por Telefone </h1>";
			$sx .= "<h3>Ligaï¿½ï¿½es ".$st." no período de ".stodbr(sonumero($d1))." a ".stodbr(sonumero($d2)).$st2.".</h3>"; 
			$sx .= '<TH class="tabelaH" align= "center"> DATA 
					<TH class="tabelaH" align= "center"> Nï¿½MERO  
					<TH class="tabelaH" align= "center"> CONTATO 
					<TH class="tabelaH" align= "center"> RAMAL  
					<TH class="tabelaH" align= "center"> HORA  
					<TH class="tabelaH" align= "center"> DURAï¿½ï¿½O  
					<TH class="tabelaH" align= "center"> STATUS  '; 
		
			
			$tot = 0;
			$tots = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
					$tots = $tots + $line['call_seconds'];

					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD class="tabela01" align= "center">'.stodbr(sonumero($line['call_data']));
					$sx .= '<TD class="tabela01" align= "center">'.$this->format_telefone($line['call_numero']);
					$sx .= '<TD class="tabela01" align= "center">'.$line['age_nome'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_ramal'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_hora'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_duracao'];
					$sx .= '<TD class="tabela01" align= "center">'.$line['call_tipo'];
										
				}	
			/* Apresenta somatorias */		
			$sx .= '<TR><TD colspan=5>Total '.$tot.' com '.$tots.' seg. ';
			if ($tot > 0) { $sx .= ', com '.number_format($tots/$tot,1,',','.').' mï¿½dio por ligaï¿½ï¿½o.'; }
			$sx .= '</table>';
			
			/* Fim */
			return($sx);
		
			
		}
		
		
/*******************Mostra graficamente o nï¿½mero de ligaï¿½ï¿½es por hora cheia*/
		function ligacoes_por_hora($d1,$d2)
		{
			
		
			$wh = '';
			/* tratamento dos paremetros de delimitacao por data */
			if ($d1 > 0) { $wh .= " call_data >= '".substr($d1,0,4).'-'.substr($d1,4,2).'-'.substr($d1,6,2)."'"; }
			if ($d2 > 0) { $wh .= " and call_data <= '".substr($d2,0,4).'-'.substr($d2,4,2).'-'.substr($d2,6,2)."'"; }

			
			/* Montagem da tela de saida */
			$tot = 0;
			$tots = 0;
			
			/*query*/
			$sql = "select  call_tipo, substr(call_hora,1,2) as hora,  count(*) as total from calls where   
			$wh
			group by  hora, call_tipo
			order by hora 
			";
						  
			 
			/* Execuï¿½ï¿½o */
			$rec = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$ati = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			
			$rlt = db_query($sql);

			while ($line = db_read($rlt))
				{
					$tipo = $line['call_tipo'];
					$lig = $line['total'];
					$hor = round($line['hora']);
					
					if ($tipo == 'R') { $rec[$hor] = $rec[$hor] + $lig;  }
					if ($tipo == 'A') { $ati[$hor] = $ati[$hor] + $lig;  }
				}
				
			/* Monta dados para grafico */
			$sx = "['HORA', 'Ativas', 'Recebidas'],";			
			for ($r=0;$r < 24;$r++)
				{			
					$sx .="['".strzero($r,2).":00',   ".$rec[$r].",   ".$ati[$r]."],".chr(13);
				}
			/* Fim */
			
			return($sx);
			
		}
/**********Telefones mais ligados*/
		function top_ligacoes($d1=0,$d2=0,$ramal='',$tipo='',$top='10')
		{
			/*caso nï¿½o seja digitado o ramal ï¿½ feito a pesquisa geral*/
		
		   $wh = '';
		   if (strlen($ramal) == 0) {  } 
				else {$wh .=" call_ramal='$ramal' and "; } 
		
		   if ($tipo == 'T') {  } 
				else {$wh .=" call_tipo='$tipo' and "; } 
		
			
			/* tratamento dos paremetros de delimitacao por data */
			if ($d1 > 0) { $wh .= " call_data >= '".substr($d1,0,4).'-'.substr($d1,4,2).'-'.substr($d1,6,2)."'"; }
			if ($d2 > 0) { $wh .= " and call_data <= '".substr($d2,0,4).'-'.substr($d2,4,2).'-'.substr($d2,6,2).
								  "' and call_numero<>'' group by calls.call_numero ,agenda.age_nome order by count desc limit ".$top; }

			
			
			/*query*/
			$sql = "select age_nome, call_numero , count(*) from calls left join agenda on agenda.age_numero=calls.call_numero 
			where   
			$wh 
			";
						  
			$rec = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$ati = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			
			  
			/* Execução */
			$rlt = db_query($sql);

			$sx = "['Telefone', 'Percentual'],";			
			
			/*Construï¿½ï¿½o do grï¿½fico*/
			while ($line = db_read($rlt))
				{
					$numero = $this->format_telefone($line['call_numero']);
					$nome = $line['age_nome'];
					$total = round($line['count']);
					
					$sx .="['".$nome.$numero."',  ".$total."],".chr(13);
				}
				
			return($sx);
			
			
			 
			
		}
/*********Telefonemas divididos entre Ativos e Receptivos */

		function ligacoes_tipos($d1=0,$d2=0,$ramal='',$tipo=''){
				
						/*caso nï¿½o seja digitado o ramal ï¿½ feito a pesquisa geral*/
		
		   $wh = '';
		   if (strlen($ramal) == 0) {  } 
				else {$wh .=" call_ramal='$ramal' and "; } 
		
		   if ($tipo == 'T') {  } 
				else {$wh .=" call_tipo='$tipo' and "; } 
		
			
			/* tratamento dos paremetros de delimitacao por data */
			if ($d1 > 0) { $wh .= " call_data >= '".substr($d1,0,4).'-'.substr($d1,4,2).'-'.substr($d1,6,2)."'"; }
			if ($d2 > 0) { $wh .= " and call_data <= '".substr($d2,0,4).'-'.substr($d2,4,2).'-'.substr($d2,6,2).
								  "' and call_numero<>'' group by call_tipo"; }

			
			
			/*query*/
			$sql = "select call_tipo as tipo, count(call_tipo) from calls 
			where   
			$wh 
			";
			 
						  
			$rec = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$ati = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			
			  
			/* Execuï¿½ï¿½o */
			$rlt = db_query($sql);

			$sx = "['Tipo', 'Quantidade'],";			
			
			/*Construï¿½ï¿½o do grï¿½fico*/
			while ($line = db_read($rlt))
				{
					$tipo = $line['tipo'];
					$total = round($line['count']);
					
					$sx .="['".$tipo."',  ".$total."],".chr(13);
				}
				
			return($sx);
			
			
				
			
		}

		
		
/*********Grafico de barras, ligaï¿½ï¿½es divididas por hora do dia*/		
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
          		title: \'Total de Ligaï¿½ï¿½es\',
          		hAxis: {title: \'Horï¿½rio\', titleTextStyle: {color: \'red\'}}
        		};

        		var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div\'));
        		chart.draw(data, options);
      			}
      			</script>    
    			<div id="chart_div" style="width: 1200px; height: 800px;"></div>
    			';
				return($sx);
		}
		
		
/*********Grafico de pizza, ligações divididas por hora do dia*/

			
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
    		<div id="visualization" style="width: 1200px; height: 800px;"></div>
  			';
			return($sx);
			
		}


		
		
}
		
?>

