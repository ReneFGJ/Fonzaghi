<?php
class apps
	{
		
	function show($tela='')
		{
			$sx = '';
			$sx .= '<div class="balloon" id="onkey" style="z-index:1000;">';
			$sx .= '<div class="arrow"></div>';
			$sx .= $tela;
			$sx .= '</div>';
			return($sx);
		}
	}
?>
