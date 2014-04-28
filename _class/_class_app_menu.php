<?php
 /**
  * apps
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.18
  * @package Classe
  * @subpackage -
 */
 

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
