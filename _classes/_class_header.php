<?php
/**
 * @author: Rene Faustino Gabriel Junior
 * @version: v0.13.35
 */
class header
	{
	var $js='';
	var $css='';
	var $title = '::Fonzaghi::';
	var $char_set = 'ISO-8859-1';
	var $http = '/fonzaghi/';
	
	function cab()
		{
			header("Content-Type: text/html; charset=".$this->char_set,true);
			$sx = '<!DOCTYPE html>'.chr(13);
			$sx .= '<html lang="pt-BR">'.chr(13);
			$sx .= '<head>'.chr(13);
			$sx .= '<title>'.$this->title.'</title>'.chr(13);
			$sx .= '<meta http-equiv="Content-Type" content="text/html; charset='.$this->char_set.'" />'.chr(13);
			$sx .= '<meta name="expires" content="never" />'.chr(13);
			$sx .= '<link rel="shortcut icon" type="img/favicone.png" />'.chr(13);
			$sx .= '<!-- STYLES // -->	'.chr(13);
			$sx .= '<link rel="stylesheet" type="text/css" media="screen" href="'.$this->http.'css/style_cab.css" />'.chr(13);
			$sx .= '<link rel="stylesheet" type="text/css" media="screen" href="'.$this->http.'css/style_fonts.css" />'.chr(13);
			$sx .= $this->js.chr(13);
			$sx .= '</head>'.chr(13);
			$sx .= '<body leftmargin="0" topmargin="0" >'.chr(13);

			$sx .= '<div style="background-color: #000000">';
				$sx .= $this->short();
				$sx .= $this->user_id();
				$sx .= '<center>';
				$sx .= '<IMG SRC="/fonzaghi/img/bg_cab.png">';
				$sx .= '</center>';
				
			$sx .= '</div>';
			
			$sx .= $this->top_menu();
						
			return($sx);
		}
	function user_id()
		{
			global $user,$user_login;
			$login_name = $user->user_name;
			$login_name .= $user_login;
			$sx .= '<div id="user_id" style="float: right; padding: 5px 5px 5px 5px; ">
					<nobr><font color="white">'.$login_name.'</font></nobr>
					</div>
			';
			return($sx);
		}
	function short()
		{
			$sx .= '<div class="left">';
			$sx .= '&nbsp;&nbsp;&nbsp;<input type="text" size=3 maxsize=3 id="onclick">';
			$sx .= '</div>'.chr(13);
			return($sx);
		}
		
	function top_menu()
		{
			global $user;
			$log = $user->user_login;

				$sx = '<div id="top_menu" style="background-color: #8080FF;">';
					$sx .= '<UL>';
					$sx .= '<LI><a href="#"><img src="'.$this->http.'img/icone_home.png" height=15 border=0></A></LI>';
					$sx .= '<LI><a href="#">menu</A></LI>';
					$sx .= '<LI id="li_right"><a href="#">sair</A></LI>';					
					$sx .= '</UL>';
				$sx .= '</div>'.chr(13);
			return($sx);
		}	
		
	function foot()
		{
			return($sx);
		}
	}
?>