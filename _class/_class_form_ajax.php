<?

class form_ajax
{
	var $js='';
	var $id='';
	var $url='';
	var $type='POST';
	var $string='';
	var $align=0;
	var $align_item=0;
	var $submit='submit';
	
	function id()
	{
		if(strlen($this->idx)>0)
		{
			$id = $this->idx=0;
		}else{
			$id = $this->idx=$this->idx+1;
		}
		return($id);
	}	
	
	
	function gravar()
	{
		global $dd;
		$this->js .= 'id='.$this->id.';
					 function gravar(id)
						{
						var obj = "#dd" + id;
						var str = $(obj).val();
						
							$(obj).addClass("noborder_ajax");
							$(obj).attr("readonly", true);
							$(obj + "b").hide();
							$(obj + "a").show();		
						 
						 	$.ajax({
								type: "'.$this->type.'",
								url: "'.$this->url.'",
								data: { dd50: id, dd51: str, dd60: "atualizar_email" }
							}).done(function( data_retr ) { $("#msg").html( data_retr ); 
							});
					}';
		return(1);			
	}
	
	function mostrar($aj='')
	{
		if(count($aj)>0)
		{
			$this->html .= $this->monta_string($aj);
		}
		$sx = $this->html;
		$sx .= '<div id="msg" style="display: inline;"></div>';
		$sx .= '<script>';	
		$sx .= $this->js;
		$sx .= '</script>';
		return($sx);
	}	
	
	function monta_string($aj)
	{
		for ($i=0; $i < count($aj); $i++)
		{
			$sx .= $this->field($aj[$i][0],$aj[$i][1]);	
		}
		return($sx);
	}
	//identifica o tipo dos campos do vetor cp 
	function editar($cp)
	{
		$this->open_form();
		for ($i=0; $i < count($cp); $i++)
		{
			$this->id();
			//1 - dados
			$this->string = $cp[$i][1];
			$this->process_block();
			//2 - nome do campo
			$this->name = $cp[$i][2];
			//3 - campo na tabela
			$this->table = $cp[$i][3]; 
			//4 - obrigatorio
			$this->required = $cp[$i][4];
			//5 - visivel
			$this->visible = $cp[$i][5];
			//6 - alinhamento 1(vertical) 0(horizontal)
			$this->align = $cp[$i][6];	
			
			//0 - tipo do campo
			$field = $cp[$i][0];
			$this->switch_field($field);
		}
		$this->close_form();
		echo '<script>'.$this->js.'</script>';
		echo $this->html;
		return(1);
	}
	function switch_field($field)
	{
		switch($field)
		{
			case '{':
			break;
			case '}':
			break;
			//Autor qualificação	
			case 'A':
			break;
			//Alert	
			case 'ALERT':
			break;
			//Button	
			case 'B':
				$this->html.= $this->type_button();
			break;
			//City, State, country	
			case 'CITY':
			break;
			//Date	
			case 'D':
			break;
			//Files	
			case 'FILES':
			break;
			//Keyword	
			case 'KEYWO':
			break;
			//Hidden	
			case 'H':
			break;
			//Hidden with value	
			case 'HV':
			break;	
			//Integer	
			case 'I':
			break;
			//Msg	
			case 'M':
			break;
			//Value with 2 decimal	
			case 'N':
			break;
			//Select with combobox	
			case 'O':
				$this->html .= $this->type_select();
			break;	
			//Regular string
			case 'P':
			break;
			//Query	
			case 'Q':
			break;
			//Regular string	
			case 'S':
				$this->html .= $this->field();
			break;
			//Text field	
			case 'T':
			break;
			//States	
			case 'U':
			break;
			//Select with multiple itens	
			case 'OM':
				$this->html .= $this->type_multiple();
			break;	
			//Checkbox	
			case 'CB':
				$this->html .= $this->type_checkbox();
			break;
			//radio	
			case 'R':
				$this->html .= $this->type_radio();
			break;
			//	
			case '':
			break;			
																
			
		}
		return(1);
	}
	function atualiza()
	{
		$this->js .= 'id='.$this->id.';
					function atualiza(id)
					{
						var obj = "#dd" + id;
							$(obj).removeClass("noborder_ajax");
							$(obj).removeAttr("readonly");	
							$(obj + "a").hide();
							$(obj + "b").show();
					}
					';
		return(1);			
	}	

	function field($string='',$id='')
		{
			if(strlen($id)>0){ $this->id=$id; }
			if(strlen($string)>0){ $this->string=$string; }
			if($this->vertical==1){ $br='<br>'; $brx='</br>';}	
			if($this->vertical==0){ $br=''; $brx='';}
			$sx = $br.'<nobr>
				<input type="texte" class="email_ajax noborder_ajax" name="dd'.$this->id.'" id="dd'.$this->id.'" readonly="yes" value="'.$this->string.'">
				<img width="20" heigth="20" src="/fonzaghi/img/icone_editar.gif" id="dd'.$this->id.'a" onclick="atualiza(\''.$this->id.'\');">
				<img width="20" heigth="20" src="/fonzaghi/img/icone_add.png" id="dd'.$this->id.'b" onclick="gravar(\''.$this->id.'\');" style="display: none;">
				</nobr>'.$brx;
			$this->atualiza($this->id);
			$this->gravar($this->id);
			return($sx);
		}
	
	//Processa as strings com multiplos dados gravando em um vetor
	function process_block()
	{
		$string = $this->string;
		$t=0;
		
		for ($i=0; $i < strlen(trim($string)); $i++) 
		{
			$caracter = substr($string, $i,1);
			switch($caracter)
			
			{
				case ':':
					if(strlen($bloco)>0)
					{
					 $this->valor[$t]=$bloco;
					 $bloco='';
					}
				break;
				case '&';
					if(strlen($bloco)>0)
					{
					$this->nome[$t]=$bloco;
					$bloco='';
					$t++;	
					
					}
				break;	 
				default:
					 $bloco .= $caracter; 
				break;	
			
			}
		}
		return(1);
	}

	function type_button()
	{
		if($this->align==1){ $br='<br>';}
		$sx .= $br.'
				<input 
					type="submit" name="'.$this->button.'" value="'.$this->name.'" 
					id="dd'.$this->idx.'" class="bottom_submit" />';
		$this->js .= '
				<script>
					$("#dd'.$this->idx.'").click(function() 
						{
							var vlr = $("#dd'.$this->idx.'").val();
							$("#acao").val(vlr);
							$("#formulario").submit();						
						});
				</script>
				';
				/* $('#target').submit();*/ 
		return($sx);
	}
	//campo tipo select com uma opção visivel na lista
	function type_select()
	{
		$sx = $this->type_label();
		for ($j=0; $j < count($this->nome) ; $j++) 
		{ 
			$tx .= '<option value="'.$this->valor[$j].'">'.$this->nome[$j].'</option>';
		}
		$sx .='<select name="'.$this->idx.'">'.$tx.'</select>';
		return($sx);
	}
	//campo tipo select com multiplas opções visiveis na lista
	function type_multiple()
	{
		$sx = $this->type_label();
		for ($j=0; $j < count($this->nome) ; $j++) 
		{ 
			$tx .= '<option value="'.$this->valor[$j].'">'.$this->nome[$j].'</option>';
		}
		$sx .='<select selected="selected" name="'.$this->idx.'" multiple="multiple">'.$tx.'</select>';
		return($sx);
			 
	}
	//campo tipo checkbox 
	function type_checkbox()
	{
		$sx = $this->type_label();
		for ($j=0; $j < count($this->nome) ; $j++) 
		{
			$sx .= ' <input type="checkbox" value="'.$this->valor[$j].'"  name="'.$this->nome[$j].'"  id="'.$this->id().'">';
			$sx .= $this->type_label($this->nome[$j]);
		}
		return($sx);			 
	}
	//campo tipo radio
	function type_radio()
	{
		$sx = $this->type_label();
		for ($j=0; $j < count($this->nome) ; $j++) 
		{
			$sx .= ' <input type="radio" value="'.$this->valor[$j].'"  name="'.$this->nome[$j].'"  id="'.$this->idx.'">';
			$sx .= $this->type_label($this->nome[$j]);
		}
		return($sx);	  
	}
	function type_label($name='')
	{
		/*echo $this->align;
		if($this->align==1){ $br='<br>';}
		else{ $br='<nobr>';}
		if(strlen($name)==0)
		{
			$name=$this->name;  
		}else{
			if($this->align_item==1){$br='<br>';}else{$br='';}	
		}*/
		$sx = $br.' <label for="'.$this->idx.'">'.$name.'</label>';
		return($sx);
	}

	function open_form()
	{
		$this->html .= '<form id="formulario" action="'.$this->htmlx.'" method="post">'; 
		return(1);
	}
	
	function close_form()
	{
		$this->html .= '</form>';
		return(1);
	}
	
	
	
}
