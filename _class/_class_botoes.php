<?php
echo '
		<script type="text/javascript" src="'.http.'include/js/jquery-calender_'.$LANG.'.js"></script>
		<script type="text/javascript" src="'.http.'include/js/jquery.maskedit.js"></script>
		<script type="text/javascript" src="'.http.'include/js/jquery.maskmoney.js"></script>
		<script type="text/javascript" src="'.http.'include/js/jquery.tagsinput.js"></script>
		<link rel="stylesheet" href="'.http.'include/css/calender_data.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="'.http.'include/css/style_keyword_form.css" type="text/css" media="screen" />
	';
class form_botoes
	{
		
		var $name;
		var $cl;
		var $pag;
		var $style=0;
		var $js='';
	function controle()
	{
		global $i;
		if (!isset($i)) { $i = 1; } else { $i++; }
	}	
	function mostrar_botoes($b)
		{
			global $dd,$i,$http;
			switch($this->style)
			{
				case '0':
				case '1':
					$this->controle();
					//array_multisort($b);
					$ids = array();
					$val = trim($dd[$i]);
					$js = ''.chr(13).chr(10);
					$sx.= '<input	type="hidden" 
								id="dd'.$i.'" 
								name="dd'.$i.'" 
								value="'.$val.'" />';
					
					if($this->style==0)
					{
						$tx ="<td>";
					}
					if($this->style==1)
					{
						$tx ="<tr><td>";
					}
					
					for ($r=0;$r < count($b);$r++)
						{
							$i_next = ($r+1);	
		
							/* Ativa class */
							$at = 'bt1';
							$atx=$at;
							if ($i_next >= count($b)) { $i_next = 0; }
							/* se dd estï¿½ vazio, marca o primeiro item */
							if ((strlen($val)==0) and ($r==0)) 
							{
								$at .= '_ativo';
							}
							/* se dd for diferente de nada, marca o item ativo */
							if (($b[$r][1]==$val) and (strlen($val) > 0))
							{
								 $at .= '_ativo'; 
							}
							/*armazena as ids para montar o Jquery toggle no final do laÃ§o;*/
							if($atx==$at)
							{
								$id='bt'.$i.'_'.$r;
								array_push($ids,array($id));
							}
							
							if($this->style==0)
							{
								$tx .= '<div id="bt'.$i.'_'.$r.'" class="'.$at.'" style="float:left;" width="500px">'.$b[$r][0].'</div>'.chr(13).chr(10);
							}
							if($this->style==1)
							{
								$tx .= '<div id="bt'.$i.'_'.$r.'" class="'.$at.'" width="500px">'.$b[$r][0].'</div>'.chr(13).chr(10);
							}	
							$js .= '$("#bt'.$i.'_'.$r.'").click(function() 
										{
											$("#bt'.$i.'_'.$r.'").removeClass("bt1_ativo").addClass("bt1");
											$("#dd'.$i.'").val("'.$b[$i_next][1].'");
											$("#bt'.$i.'_'.$i_next.'").removeClass("bt1").addClass("bt1_ativo");
										}); '.chr(13).chr(10);	
						}
						 $sx.=$tx;
						 
						 $this->js.=$js.$jq;
						/*$sx .= 	$tx.'
								<script>
								'.$js.$jq.'
								</script>
								';	
						*/		
						return($sx);
						break;
				case '2':
					$this->controle();
					array_multisort($b);
					$ids = array();
					$ids2 = array();
					$val = trim($dd[$i]);
					$sx.= '<input	type="hidden" 
								id="dd'.$i.'" 
								name="dd'.$i.'" 
								value="'.$val.'" />';
					
							for ($r=0;$r < count($b);$r++)
								{
									$i_next = ($r+1);	
									/* Ativa class */
									$at = 'bt1';
									$atx=$at;
									if ($i_next >= count($b)) { $i_next = 0; }
									/* se dd estï¿½ vazio, marca o primeiro item */
									if ((strlen($val)==0) and ($r==0)) 
									{
										$at .= '_ativo';
									}
									/* se dd for diferente de nada, marca o item ativo */
									if (($b[$r][1]==$val) and (strlen($val) > 0))
									{
										 $at .= '_ativo'; 
									}
										$id='bt'.$i.'_'.$r;
										$id2='$("#dd'.$i.'").val("'.$b[$r][1].'");';
										array_push($ids,array($id));
										array_push($ids2,array($id2));
									if($this->style==2)
									{
										$tx .= '<div id="bt'.$i.'_'.$r.'" class="'.$at.'">'.$b[$r][0].'</div>'.chr(13).chr(10);
									}
									
								}
								$jq=$this->jquery_toggle($ids,$ids2);
								$sx .=$tx;
								$this->js.=$jq;
								 /*$sx .= 	$tx.'
										<script>
										'.$jq.'
										</script>
										';
								 */	
						return($sx);
				break;
				case '3':
					$this->controle();
					array_multisort($b);
					$ids = array();
					$ids2 = array();
					$val = trim($dd[$i]);
					$sx.= '<input	type="hidden" 
								id="dd'.$i.'" 
								name="dd'.$i.'" 
								value="'.$val.'" />';
							$at= "bt1_select";
							for ($r=0;$r < count($b);$r++)
								{
									/* se dd estï¿½ vazio, marca o primeiro item */
									if ((strlen($val)==0) or ($val==0)) 
									{
										$at = 'bt1_ativo';
									}
										$id='bt'.$i.'_'.$r;
										$id2='dd'.$i;
										array_push($ids,array($id));
										array_push($ids2,array($id2));

										$tx .= '<div id="bt'.$i.'_'.$r.'" class="'.$at.'">'.$b[$r][0].'</div>'.chr(13).chr(10);
									
								}
								$jq=$this->jquery_toggle($ids,$ids2);
								$sx .=$tx;
								$this->js.=$jq;
								 /*$sx .= 	$tx.'
										<script>
										'.$jq.'
										</script>
										';
								*/
						return($sx);
				break;
				}		
		}
	function jquery_toggle($ids,$ids2)
	{
			
		$k=0;
		switch($this->style){
			case 0:
			case 1:
			case 2:
				while($k<count($ids))
				{
					$jq.='$("#'.$ids[$k][0].'").addClass("bt1_ativo");'.chr(13).chr(10);	
					$jq.='$("#'.$ids[$k][0].'").click(function() {'.chr(13).chr(10);
					$jq.='$("#'.$ids[$k][0].'").toggle().addClass("bt1_ativo");'.chr(13).chr(10);
					$jq.=$ids2[$k][0].chr(13).chr(10);
					$j=0;
					while($j<count($ids))
					{
							$jq.='$("#'.$ids[$j][0].'").toggle().addClass("bt1_ativo");'.chr(13).chr(10);
							$j++;
					}
					$jq.='});'.chr(13).chr(10);
					$k++;
				}
			case 3:
				while($k<count($ids))
				{
					$jq.='$("#'.$ids[$k][0].'").click(function() {
									$("#'.$ids[$k][0].'").toggleClass("bt1_ativo");	
									$("#'.$ids[$k][0].'").toggleClass("bt1_select");
									if($("#'.$ids[$k][0].'").hasClass("bt1_ativo"))
									{
										$("#'.$ids2[$k][0].'").val(0);
									}
									if($("#'.$ids[$k][0].'").hasClass("bt1_select"))
									{
										$("#'.$ids2[$k][0].'").val(1);
									}
							});';
					$k++;
				}
			break;	
		}	
		return($jq);
	}
	function action($link='',$style=0)
	{
			global $http;
			$this->style=$style;
			switch($this->style)
			{
				case 0:
					$sx = '<center><form action="'.$http.$link.'">';
					$sx .='<table><tr><td colspan="3" style="font-size: 10px;">Opções de filtro:</td></tr><tr>';
				break;
				case 1:
					$sx = '<center><form action="'.$http.$link.'">';
					$sx .='<table><tr><td style="font-size: 10px;">Opções de filtro:</td></tr>';
				break;
				case 2:
				case 3:	
					$sx = '<center><form action="'.$http.$link.'">';
					$sx .='<table><tr><td colspan="3" style="font-size: 10px;">Opções de filtro:</td></tr><tr>';
				break;
			}
			return($sx);
	}
	function submit($nome='filtrar',$cl='botao-submit')
	{
			
			switch($this->style)
			{
				case 0:
					$sx = '<div style="float:left;">';
					$sx .= '<input type="submit" class="'.$cl.'" name="acao" value="'.$nome.'">';
					$sx .= '</div></td></tr></table>';
					$sx .= '</form><script>'.$this->js.'</script>';
				break;
				case 1:
					$sx = '<tr><td>';
					$sx .= '<input type="submit" class="'.$cl.'" name="acao" value="'.$nome.'">';
					$sx .= '</td></tr></table>';
					$sx .= '</form><script>'.$this->js.'</script>';
				break;
				case 2:
				case 3:	
					$sx = '<div>';
					$sx .= '<input type="submit" class="'.$cl.'" name="acao" value="'.$nome.'">';
					$sx .= '</div></td></tr></table>';
					$sx .= '</form><script>'.$this->js.'</script>';
				break;	
			}
			return($sx);
	}
	function data($nome='')
			{
				global $dd,$i;
				$this->controle();
				$val = trim($dd[$i]);
				$sx = '
				<input 
					type="text" name="dd'.$i.'" size="13"
					value = "'.$val.'"
					maxlength="10"  
					id="dd'.$i.'"
				/>'.chr(13).chr(10);
				switch($this->style)
				{
					case 0:
						$sx ='<td>'.$sx.'</td>';
					break;
					case 1:
						$sx ='<tr><td>'.$sx.'</td></tr>';
					break;
					case 2:
						$sx .='<br>';
					break;
					case 3:
						$sx .='<br>';
					break;
				
				}
				/* SCRIPT */
				$this->js .= '
					$("#dd'.$i.'").mask("99/99/9999");
					$("#dd'.$i.'").datepicker({
							showOn: "button",
							buttonImage: "'.http.'include/img/icone_calender.gif",
							buttonImageOnly: true,
							showAnim: "slideDown"	 
					});
				'.chr(13).chr(10);
				return($sx);				
			}
	function campo($nome='',$tamanho=10)
	{
		global $dd,$i;
				$this->controle();
				$val = trim($dd[$i]);
				$sx = $nome.'<br>
				<input 
					type="text" name="dd'.$i.'" size="'.$tamanho.'"
					value = "'.$val.'"
					maxlength="'.$tamanho.'"  
					id="dd'.$i.'"
				/>'.chr(13).chr(10);
				
				switch($this->style)
				{
					case 0:
						$sx ='<td>'.$sx.'</td>';
					break;
					case 1:
						$sx ='<tr><td>'.$sx.'</td></tr>';
					break;
					case 2:
						$sx .='<br>';
					break;
					case 3:
						$sx .='<br>';
					break;
				
				}
	return($sx);
	}
	
	function hidden($tamanho)
	{
		global $dd,$i;
				$this->controle();
				$val = trim($dd[$i]);
				$sx = '
				<input 
					type="hidden" name="dd'.$i.'" size="'.$tamanho.'"
					value = "'.$val.'"
					maxlength="'.$tamanho.'"  
					id="dd'.$i.'"
				/>'.chr(13).chr(10);
				
				switch($this->style)
				{
					case 0:
						$sx ='<td>'.$sx.'</td>';
					break;
					case 1:
						$sx ='<tr><td>'.$sx.'</td></tr>';
					break;
					case 2:
						$sx .='<br>';
					break;
					case 3:
						$sx .='<br>';
					break;
				
				}
				
		return($sx);
	}
		
		
	}

	