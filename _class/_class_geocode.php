<?php
 /**
  * Geocode -google maps
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.09
  * @package Classe
  * @subpackage -
 */

 class geocode
	{
		var $include_class= '../';
		var $regional = 0;
		var $regional_array = array(); 
		var $loja = 0;
		var $width = 1000;
		var $height = 800;
		var $query_cliente = '';
		function import_geocodes($qtda)
		{
			global $base_name,$base_server,$base_host,$base_user;
			require($this->include_class.'db_fghi_206_cadastro.php');
			$sql = "select * from cadastro_completo
					where pc_cep<>'' and
						  pc_longitude is null	and
						  pc_latitude is null
					 order by pc_codigo	  
					 limit ".$qtda."
			";
			$rlt = db_query($sql);
			$sx = "<table><tr>
							<th>Cliente</th>
							<th>Nome</th>
							<th>Cep</th>
							<th>Status</th>
							<th>Latitude</th>
							<th>Longitude</th>
							</tr>
			";
			while($line = db_read($rlt))
			{
				$cliente = $line['pc_codigo'];
				$cep = $line['pc_cep'];
				$vld = $this->consulta_api($cep,$cliente);
				$sx .= "<tr>
						<td>".$cliente."</td>
						<td>".$line['pc_nome']."</td>
						<td>".$cep."</td>
						<td>".$this->tipo_status($vld)."</td>
						<td>".$this->lat."</td>
						<td>".$this->lng."</td>
						</tr>";
						
			}
			$sx .= "</table>"; 
			return($sx);
		}
		function tipo_status($vld)
		{
			switch ($vld) {
				case '0':
					return('Não atualizado');
					break;
				case '1':
					return('Atualizado');
					break;
			}
		}
		function consulta_api($cep,$cliente='',$estado='parana',$cidade='curitiba')
		{
				
			$request_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.trim($cep).',+'.trim($cidade).',+'.trim($estado).'&sensor=true&key=AIzaSyAGFlCOEtpfOVimhNEE_7r8BWWdBJECh0g';
    		$json = $this->read_link($request_url) or die("url not loading");
    		$obj = json_decode($json); 
			//imprime o conteúdo do objeto 
			echo "<pre>";
			//print_r($obj);
			echo "</pre>";
			
			 $obj= $obj->results[0];
			//$cliente.'--'.$cep;
			$lat= $obj->geometry->location->lat;
			$lng= $obj->geometry->location->lng;
			$this->lat=$lat;
			$this->lng=$lng;
			
			 if((isset($lat))and(isset($lng)))
			 {
			 	$sx = $this->update_geocode($lat, $lng, $cliente);
				return($sx);
			 }else{
				return(0);			 	
			 }		
		}
		function update_geocode($lat,$lng,$cliente)
		{
			if(isset($cliente))
			{
				$sql = "update cadastro_completo 
						set pc_longitude='".$lng."', 
							pc_latitude='".$lat."'
						where pc_codigo='".$cliente."'	
							";
				$rlt = db_query($sql);
				return(1);			
			}else{
				return(0);
			}
			
		}
		
		function read_link($url)
		{
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BUFFERSIZE, 1024);
			$contents = curl_exec($ch);
			if (curl_errno($ch)) {
			  echo curl_error($ch);
			 echo "<br />";
			  $contents = '';
			} else {
			 curl_close($ch);
			}
			if (!is_string($contents) || !strlen($contents)) {
			echo "Failed to get contents.";
			$contents = '';
			}
			if (strpos($contents,'encoding="UTF-8"') > 0)
			{
			$contents = troca($contents,'encoding="UTF-8"','encoding="ISO-8859-1"');
			$contents = utf8_decode($contents);
			}
		
			return($contents);
		}	
		function gera_google_map()
		{
			
			$sx = '<body onload="initialize()" onunload="GUnload()" style="font-family: Arial;border: 0 none;">
    			   <div id="map_canvas" style="width: 900px; height: 700px"></div>
  				   </body>';
			
			$sx .= '<script src="//maps.google.com/maps?file=api&amp;v=3&amp;sensor=false&amp;key=ABQIAAAAuPsJpk3MBtDpJ4G8cqBnjRRaGTYH6UMl8mADNa0YKuWNNa8VNxQCzVBXTx2DYyXGsTOxpWhvIG7Djw" type="text/javascript"></script>
			<script>
			
			function initialize() {
			  if (GBrowserIsCompatible()) {
			    var map = new GMap2(document.getElementById("map_canvas"));
			    map.setCenter(new GLatLng(-25.433371,-49.27247), 12);
			    
			    var bounds = map.getBounds();
			    var southWest = bounds.getSouthWest();
			    var northEast = bounds.getNorthEast();';
			    
				
			 $sx .= $this->add_map_marker();   
			 $sx .='
				  }
				}
				</script>';
							
			
			
			return($sx);
		}

		function gera_google_mapv3($add=0)
		{
			
			$sx = '<body onload="initialize()" onunload="GUnload()" style="font-family: Arial;border: 0 none;">
    			   <div id="map-canvas" style="width: '.$this->width.'px; height: '.$this->height.'px"></div>
  				   </body>';
			
			
			$sx .= ' <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>
    				<script>
			var map;
		      function initialize() {
		        var mapDiv = document.getElementById(\'map-canvas\');
		        map = new google.maps.Map(mapDiv, {
		          center: new google.maps.LatLng(-25.433371, -49.27247),
		          zoom: 12,
		          mapTypeId: google.maps.MapTypeId.ROADMAP
		        });
		      
		        google.maps.event.addListenerOnce(map, \'tilesloaded\', addMarkers);
		      
		      }';
			 switch ($add) {
				 case 0:
					
					 $sx .= $this->add_map_markerv3();   
					 break;
				 case 1:
					
					 $sx .= $this->add_map_markerv3_regional();   
					 break;
				 
				 default:
					 $sx .= $this->add_map_markerv3();   
					 break;
			 } 
      	 	
			$sx .= '</script>';
			
			
			return($sx);
		}
		
		function add_map_markerv3()
		{
			global $base_name,$base_server,$base_host,$base_user;
			$cons = new consultora;
			
			require($this->include_class.'db_fghi_206_cadastro.php');
			
			 $sx = '
			 		function addMarkers() {
			 	';
				       				       
				$sql = "select pc_codigo,pc_latitude,pc_longitude,pc_nome,pc_cpf, cl_last from 
						cadastro inner join (
							select * from cadastro_completo
							where pc_longitude IS NOT NULL and
							pc_latitude IS NOT NULL
						) as tb on pc_codigo=cl_cliente where cl_last >0
				";
				$rlt = db_query($sql);
				
				
				while($line = db_read($rlt))
				{			
			        $cons->cpf=trim($line['pc_cpf']);
					$sx .="	
					          var latLng = new google.maps.LatLng(".trim($line['pc_latitude']).",".trim($line['pc_longitude']).");
					          var marker = new google.maps.Marker({
					          	position: latLng,
					            map: map,
					            url: 'www.google.com.br',
					            icon: new google.maps.MarkerImage(
							    '".$cons->foto()."', 
								new google.maps.Size(40, 40), 
								new google.maps.Point(1, 1), 
								new google.maps.Point(10, 10), 
								new google.maps.Size(40, 40) ),
								
					            title:'".trim($line['pc_codigo'])." - ".trim($line['pc_nome'])."' 
					          });
					          ";
				 }
				 $sx .=  '}
				 ';
				/* deletar o marcador
				 marker.setMap(null);
				delete marker
				 * */
				 
			return($sx);		
		}
		
		function add_map_markerv3_regional()
		{
			global $base_name,$base_server,$base_host,$base_user;
			$cons = new consultora;
			
			require($this->include_class.'db_fghi_206_cadastro.php');
			
			 $sx = '
			 		function addMarkers() {
			 	';
				
				if ($this->regional==0) {
					$wh = '';
				} else {
					$wh = " and regionais.rg_bairro  = '".$this->regional_array[$this->regional][0]."'";
				}
				$sql = "select regionais.rg_bairro as regional , *  from regionais inner join
										(select *,rg_ref as ref from regionais inner join 
																(select	cl_cliente,cl_latitude,cl_longitude,
																		cl_nome,cl_cpf, cl_last, 
																		cl_bairro, cl_cep 
																from cadastro ) as tb	
										on rg_bairro=cl_bairro) as tb2 
									on ref=regionais.rg_codigo
						 	
						where 	(cl_longitude IS NOT NULL and cl_longitude<>'')and
							(cl_latitude IS NOT NULL  and cl_latitude<>'') and
							cl_last >0 ".$wh."
						order by cl_cep	 
				";
				$rlt = db_query($sql);
				
				while($line = db_read($rlt))
				{
					/*código para não sair marker em cima de marker CEPs iguais*/
					if($line['cl_cep']==$cep){
						$cep = $line['cl_cep'];
						$lt = trim(($line['cl_latitude']));
						$lg = trim($line['cl_longitude']+0.0010000);
					}else{
						$cep = $line['cl_cep'];
						$lt = trim($line['cl_latitude']);
						$lg = trim($line['cl_longitude']);
					}			
			         $cons->cpf=trim($line['cl_cpf']);
					$sx .="	
					          var latLng = new google.maps.LatLng(".$lt.",".$lg.");
					          var marker = new google.maps.Marker({
					          	position: latLng,
					            map: map,
					            url: 'http://www.google.com.br',
					            icon: new google.maps.MarkerImage(
							    '../img/pinkflag.png', 
								new google.maps.Size(40, 40), 
								new google.maps.Point(1, 1), 
								new google.maps.Point(10, 10), 
								new google.maps.Size(40, 40) ),
								
					            title:'".trim($line['cl_cliente'])." - ".trim($line['cl_nome'])." - ".trim($line['cl_bairro'])." - ".trim($line['cl_cep'])."' 
					          });
					          ";
							  
					/*cria query cliente para ser usado com joinner em outros selects*/
					if (strlen($this->query_cliente) > 0) { $this->query_cliente  .= 'union '; }
					$this->query_cliente .= "select '".$line['cl_cliente']."' as cliente,
													'".$line['cl_nome']."' as nome,
													'".$line['regional']."' as regional,
													'".$line['cl_bairro']."' as bairro  
											".chr(13).chr(10);
							  
				 }
				 $sx .=  '}
				 ';
				 
				 
				 
			return($sx);		
		}
		
		
		function add_map_marker()
		{
			global $base_name,$base_server,$base_host,$base_user;
			require($this->include_class.'db_fghi_206_cadastro.php');
			$sql = "select pc_codigo,pc_latitude,pc_longitude,pc_nome,pc_cpf, cl_last from 
						cadastro inner join (
							select * from cadastro_completo
							where pc_longitude IS NOT NULL and
							pc_latitude IS NOT NULL
						) as tb on pc_codigo=cl_cliente where cl_last >0
				";
			$rlt = db_query($sql);
			while($line = db_read($rlt))
			{
				$sx .= '
						var point = new GLatLng('.$line['pc_latitude'].','.$line['pc_longitude'].');
						map.addOverlay(new GMarker(point));
						
						';	
			
			}
				
			return($sx);
		}
		
		
}
?>