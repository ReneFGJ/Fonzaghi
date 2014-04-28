<?php
class iso
	{
	
	function pyramid()
		{
			$sx .= '
			<center><div id="pyr">
				<div id="pyr01" ><img src="img/img_piramide_01.png" border=0></div>
				<div id="pyr03">
					<div id="pyr03a" style="float:left;"><img src="img/img_piramide_03a.png" border=0></div>
					<div id="pyr03b" style="float:right;"><img src="img/img_piramide_03b.png" border=0></div>
				</div>
				<div id="pyr04" ><img src="img/img_piramide_04.png" border=0></div>
			</div></center>
			
			
			<style>
				#pyr  {
						 background-color: #FFFFFF;
						 width: 800px; 
						 height: 500px;
						}
				#pyr01:hover { background-color: #A0A0FF; }
				#pyr02:hover { background-color: #A0A0FF; }
				#pyr03a:hover { background-color: #A0A0FF; } 
				#pyr03b:hover { background-color: #A0A0FF; }
				#pyr04:hover { background-color: #A0A0FF; }
				#pyr05:hover { background-color: #A0A0FF; }  					
				
				#pyr01, #pyr04, #pyr03 
					{
						margin: 0px 0px 0px 0px;
						background-color: #E0E0FF; 
						height: 81px;
						width: 452px; 
					}
				#pyr03a, #pyr03b 
					{
						background-color: #E0E0FF; 
						height: 81px;
						width: 226px; 
					}					
				#pyr01 
					{
						background-color: #E0E0FF; 
						height: 166px;
						width: 452px; 
					}					
			</style>
			<script>
				$("#pyr01").click(function() {
					alert("01");
				});
			</script>
			
			';
			
			return($sx);
		}	
	}
?>
