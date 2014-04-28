<?
    /**
     * Bussiness Inteligence
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package BI
	 * @subpackage classe
    */
    
class bi
	{
	
	
	function fluxo($dd1=20130101,$dd2=20130101,$lj='')	
		{
			global $divs;
			if (!isset($divs)) { $divs = '0'; }
			else { $divs++; }
			$avgs = array(0,0,0,0,0);
			$ids = 0;
			
			$sql = "
				select count(*) as total, rc_date, rc_loja, rc_status from recepcao
				where rc_date >= $dd1 and rc_date <= $dd2 
						and rc_status = 'C' and rc_loja = '$lj'
				group by rc_date, rc_loja, rc_status
				order by rc_date
				";
			$rlt = db_query($sql);
	
			$sa = ''; 
			while ($line = db_read($rlt))
				{
					$ids++;
					$avgs[5] = $avgs[4];
					$avgs[4] = $avgs[3];
					$avgs[3] = $avgs[2];
					$avgs[2] = $avgs[1];
					$avgs[1] = $avgs[0];					 
					$avgs[0] = $line['total'];
					
					$mgv = 4;
					$tot = 0;
					for ($r=0; $r < $mgv;$r++)
						{
							$tot = $tot + $avgs[$r];
						}
					
					if ($ids < $mgv)
						{
							$avg = $tot / $ids;
						} else {
							$avg = $tot / $mgv;
						}
					if (strlen($sa) > 0) { $sa .= ', '.chr(13); }
					$sa .= "['".stodbr($line['rc_date'])."',".$line['total'].",".$avg."] ";
				}
				
			$sx = '
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    		<script type="text/javascript">
      				google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    		</script>
    		<script type="text/javascript">
      			function drawVisualization() {
        		// Some raw data (not necessarily accurate)
        		var data = google.visualization.arrayToDataTable([	
        		[\'Data\', \'Consultoras\',\'Média\'],						
			';
			$sx .= $sa;
			$sx .= '
				]);
				
		        var options = {
          		title : \'Número de consultoras na recepção\',
          			vAxis: {title: "Consultoras"},
          			hAxis: {title: "Dias"},
          			seriesType: "bars",
          			series: {1: {type: "line"}}
        		};
        		var chart = new google.visualization.ComboChart(document.getElementById(\'chart_div_'.$divs.'\'));
        		chart.draw(data, options);
      		}
      		google.setOnLoadCallback(drawVisualization);
    		</script>
    		<div id="chart_div_'.$divs.'" style="width: 900px; height: 500px;"></div>
			';				
				
			return($sx);
		}
	}
?>
