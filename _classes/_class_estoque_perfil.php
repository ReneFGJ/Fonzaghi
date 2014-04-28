<?php
class perfil_vendas
	{
		function tipo_pecas()
			{
				$ar = array(
					'CF'=>'Calcinha Feminina',
					'CI'=>'Calcinha/Cueca Infantil',
					'CC'=>'Cueca Masculina',
					'CJ'=>'Conjunto Lingerie',
					'CH'=>'Chinelo',
					'CP'=>'Calçola',
					'LR'=>'Linha Reforço',
					'PP'=>'Peça Promocional',
					'SU'=>'Sutiã Avulso',
					'LP'=>'Linha Praia',
					'MI'=>'Meias Infantil',
					'MF'=>'Meias Feminina',
					'ME'=>'Meias Masc.',
					'OF'=>'Outros Femininos',
					'PJ'=>'Pijamas',
					'RM'=>'Roupa Masculina',
					'RF'=>'Roupa Feminina',
					'RJ'=>'Roupa Juvenil',
					'RI'=>'Roupa Infantil',
					''=>'Não categorizado'
				);
				return($ar);
			}
			
		function gerar_historico_venda($cliente = '',$dd1=20121001,$dd2=20990101,$tp=0)
			{
				$ar = $this->tipo_pecas();
				$wh = '';
				if (strlen($cliente) > 0) { $wh = " and pe_cliente = '".$cliente."'"; }
				$sql = "
					select data, count(*) as total, pg_gm, sum(pg_venda) as pg_venda, pg_gm from (
					select round(pe_lastupdate/100) as data, pg_gm, round(pe_lastupdate/100) as pg_data, pe_vlr_vendido as pg_venda from produto_estoque 
					inner join produto on pe_produto = p_codigo
					left join produto_grupos on p_class_1 = pg_codigo
					where pe_status = 'T' $wh
					and pe_lastupdate >= $dd1 and pe_lastupdate <= $dd2
					) as tabela group by data, pg_gm
					order by  pg_gm, data 			
				";
				echo $sql;
				$rlt = db_query($sql);
				
				
				
			}
		function gerar_perfil_venda($cliente = '8291101',$dd1=20121001,$dd2=20990101,$tp=0)
			{
				$ar = $this->tipo_pecas();
				$wh = '';
				if (strlen($cliente) > 0) { $wh = " and pe_cliente = '".$cliente."'"; }
				$sql = "
					select count(*) as total, pg_gm, sum(pg_venda) as pg_venda, pg_gm from (
					select pg_gm, round(pe_lastupdate/100) as pg_data, pe_vlr_vendido as pg_venda from produto_estoque 
					inner join produto on pe_produto = p_codigo
					left join produto_grupos on p_class_1 = pg_codigo
					where pe_status = 'T' $wh
					and pe_lastupdate >= $dd1 and pe_lastupdate <= $dd2
					) as tabela group by pg_gm
					order by total desc, pg_gm 			
				";
				$rlt = db_query($sql);
				$datas = "['Categoria', 'Vendas'] ";
				while ($line = db_read($rlt))
				{
					$tt = trim($line['pg_gm']);
					$tipo  = $ar[$tt].' ('.$tt.')';
					//$total = trim($line['pg_venda']);
					$total = trim($line['total']);
					if ($tp==1) { $total = round($line['pg_venda']); }
					$datas .= ", ".chr(13)."['$tipo', $total] ";
				}
			$sx = '
    			<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    			<script type="text/javascript">
      				google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
			    </script>
    			<script type="text/javascript">
      				function drawVisualization() {
        				// Create and populate the data table.
        				var data = google.visualization.arrayToDataTable([
        				'.$datas.'
	        			]);
      	        	// Create and draw the visualization.
			        	new google.visualization.PieChart(document.getElementById(\'visual2'.$tp.'\')).
			            draw(data, {title:"Perfil de vendas por tipos de peças"});
      				}    
					google.setOnLoadCallback(drawVisualization);
    			</script>
  			</head>
  		    <div id="visual2'.$tp.'" style="width: 420px; height: 400px;"></div>
  			';			
			return($sx);
			}
		function gerar_perfil_fornecidos($cliente = '8291101',$tp=0)
			{
				$ar = $this->tipo_pecas();
				$wh = '';
				if (strlen($cliente) > 0) { $wh = " and pe_cliente = '".$cliente."'"; }

				$sql = "
					select count(*) as total, pg_gm, sum(pg_venda) as pg_venda, pg_gm from (
					select pg_gm, round(pe_lastupdate/100) as pg_data, pe_vlr_custo as pg_venda from produto_estoque 
					inner join produto on pe_produto = p_codigo
					left join produto_grupos on p_class_1 = pg_codigo
					where pe_status = 'F' $wh
					) as tabela group by pg_gm
					order by total desc, pg_gm 			
				";
				
				$rlt = db_query($sql);
				$datas = "['Categoria', 'Vendas'] ";
				while ($line = db_read($rlt))
				{
					$tt = trim($line['pg_gm']);
					$tipo  = $ar[$tt].' ('.$tt.')';
					//$total = trim($line['pg_venda']);
					$total = trim($line['total']);
					if ($tp==1) { $total = round($line['pg_venda']); }
					$datas .= ", ".chr(13)."['$tipo', $total] ";
				}
			$sx = '
    			<script type="text/javascript">
      				google.load(\'visualization2\', \'1\', {packages: [\'corechart\']});
			    </script>
    			<script type="text/javascript">
      				function drawVisualization2() {
        				// Create and populate the data table.
        				var data = google.visualization.arrayToDataTable([
        				'.$datas.'
	        			]);
      	        	// Create and draw the visualization.
			        	new google.visualization.PieChart(document.getElementById(\'visual3'.$tp.'\')).
			            draw(data, {title:"Perfil das peças fornecidas"});
      				}    
					google.setOnLoadCallback(drawVisualization2);
    			</script>
  			</head>
  		    <div id="visual3'.$tp.'" style="width: 420px; height: 400px;"></div>
  			';			
			return($sx);
			}
	}
?>
