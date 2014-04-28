<?php
/*
 * Class Form
 */
 
echo '
		<script type="text/javascript" src="'.http.'include/js/jquery-calender_'.$LANG.'.js"></script>
		<script type="text/javascript" src="'.http.'include/js/jquery.maskedit.js"></script>
		<script type="text/javascript" src="'.http.'include/js/jquery.maskmoney.js"></script>
		<script type="text/javascript" src="'.http.'include/js/jquery.tagsinput.js"></script>
		<link rel="stylesheet" href="'.http.'include/css/calender_data.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="'.http.'include/css/style_keyword_form.css" type="text/css" media="screen" />
	';

class form
	{
		var $size=10;
		var $maxlength = 10;
		var $name='';
		var $caption='';
		var $required=0;
		var $rq = '';
		var $readonly=0;	
		var $fieldset='';
		var $class='';
		var $value='';
		var $line;
		var $par;
		var $js = '';
		var $cols=80;
		var $rows=5;
		var $js_valida = '';
		
		var $saved = 0;
		
		function editar($cp,$tabela,$post='')
			{
				global $dd,$acao,$path;
				array_push($cp,array('$B8','',msg('save'),false,false));
				/**
				 * Recupera informacoes
				 */
				$recupera = 0;
				if ((strlen($tabela) > 0) and 
						(strlen($acao)==0) and 
						(strlen($dd[0]) > 0) and 
						(strlen($cp[0][1]) > 0))
							{
								$sql = "select * from ".$tabela." where ".$cp[0][1]." = '".$dd[0]."'";
								$rrr = db_query($sql);
								if ($line = db_read($rrr)) { $this->line = $line; }
								$recupera = 1;							
							}
				/**
				 * Processa
				 */
				$this->js_submit = '<script>';
				if (strlen($post)==0) { $post = page(); }
				
				$this->saved = 1;
				$this->rq = '';
				$sx .= '<form id="formulario" method="post" action="'.$post.'">'.chr(13);
				$sh .= '<table class="tabela00" width="100%">';
				
				for ($r=0;$r < count($cp);$r++)
					{
						if ($recupera == 1) 
							{
								$fld = $cp[$r][1]; 
								$dd[$r] = trim($this->line[$fld]);
								if (substr($cp[$r][0],0,2)=='$D')
									{
										$dd[$r] = stodbr($this->line[$fld]);		
									} 
							}
						$this->name = 'dd'.$r;
						$this->value = $dd[$r];
						$sx .= $this->process($cp[$r]);

						if (($this->required == 1) and (strlen($this->value) == 0))
							{
								$this->rq .= msg('campo').' '.$this->caption.' '.msg('requirido').'(dd'.$r.')<BR>';
								$this->saved = 0; 
							}
					}
				$sx .= '<input type="hidden" name="dd99" id="dd99" value="'.$dd[99].'">'.chr(13);
				$sx .= '<input type="hidden" name="acao" id="acao" value="">'.chr(13);
				$sx .= chr(13).'</table>';
				$sx .= '</form>';
				$this->js_submit .= chr(13).'</script>';
				
				$sx .= $this->js;
				$sx .= $this->js_submit;
				
				if ((strlen($this->rq) > 0) and (strlen($acao) > 0))
					{
						$sa = '<TR><TD colspan=2 bgcolor="#FFC0C0">';
						$sa .= '<img src="'.http.'/img/icone_alert.png" height="50" align="left">';
						$sa .= '<font class="lt1">';
						$sa .= $this->rq;
						$sa .= '</font>';
						$sa .= $sx; 
						$sx = $sa;
					}
				
				if (($this->saved > 0) and (strlen($acao) > 0))
					{
						if (strlen($tabela) > 0)
							{ $this->save_post($cp,$tabela); }
						//$sx = 'SAVED TABLE '.$tabela.' id = '.$dd[0];
					} else {
						$this->saved = 0;
					}
				return($sh.$sx);
			}
		function save_post($cp,$tabela)
			{
				global $dd,$acao,$path;
				if (isset($dd[0]) and (strlen($dd[0]) > 0) and (strlen($cp[0][1]) > 0)) 
					{
			//		echo "==gravado";
					$sql = "update ".$tabela." set ";
					$cz=0;
					for ($k=1;$k<100;$k++)
						{
							if ((strlen($cp[$k][1])>0) and ($cp[$k][4]==True))
							{
								if (($cz++)>0) {$sql = $sql . ', ';}
								if (substr($cp[$k][0],0,2) == '$D') 
									{
										//echo '<BR>===>'.$dd[$k];	
								 		$dd[$k] = brtos($dd[$k]); 
									}
								$sql = $sql . $cp[$k][1].'='.chr(39).$dd[$k].chr(39).' ';
							}
						}
						$sql = $sql .' where '.$cp[0][1]."='".$dd[0]."'";
					if (strlen($tabela) >0)
						{ $result = db_query($sql) or die("<P><FONT COLOR=RED>ERR 002:Query failed : " . db_error()); }
					$acao=null;
					$saved=1;
					}
				else
					{
					$sql = "insert into ".$tabela." (";
					$sql2= "";
					$tt=0;
					for ($k=1;$k<100;$k++)
						{
							if (strlen(trim(($cp[$k][1]))))
							{
								if ($tt++ > 0) { $sql = $sql . ', '; $sql1 = $sql1 .', ';}
								$sql = $sql . $cp[$k][1];
								if (substr($cp[$k][0],0,2) == '$D') { $dd[$k] = brtos($dd[$k]); }
								$sql1= $sql1. chr(39).$dd[$k].chr(39);
							}
						}
					$sql = $sql . ') values ('.$sql1.')';
			//		echo $sql;
					$sqlc = $sql;
		
					if (strlen($tabela) > 0)
						{ $result = db_query($sql); }
		//				$dd[1] = null;
						$acao=null;
						$saved=2;
					}
				return($saved);
				
			}
		
		function process($cp)
			{
				global $dd,$acao,$ged,$http;
				
				$i = UpperCaseSql(substr($cp[0],1,5));
				if (strpos($i,' ') > 0) { $i = substr($i,0,strpos($i,' ')); }
				$this->required = $cp[3];
				$this->caption = $cp[2];
				$this->fieldset = $cp[1];
				$size = sonumero($cp[0]);
				$this->maxlength = $size;
				$this->caption = $cp[2];
				
				if ((strlen($acao) > 0) and ($this->required==1) and (strlen($this->value)==0))
					{ $this->caption = '<font color="red">'.$this->caption.'</font>'; }
					
				if ($size > 80) { $size = 80; }
				$this->size = $size;
				$i = troca($i,'&','');
				$i = troca($i,':','');
				$sn = sonumero($i);
				$i = troca($i,$sn,'');
				//echo '['.$i.']';
				if (substr($i,0,1)=='T') { $i = 'T'; }
				
				$sx .= chr(13).'<TR valign="top"><TD align="right">';
				$sh .= $this->caption.'<TD>';
				
				switch ($i) 
				{
					/* Field Sets */
					case '{':  $sx .= $this->type_open_field(); break;	
					case '}':  $sx .= $this->type_close_field(); break;	
										
					/* Sequencial */
					case '[':
						$this->par = substr($cp[0],2,strlen($cp[0]));  
						$sx .= $sh. $this->type_seq(); break;	

					/* Author Qualificação */
					case 'AUTOR':  $sx .= '<TR><TD colspan=2>'.$this->type_Autor(); break;	
					/* Caption */
					case 'A':  $sx .= '<TR><TD colspan=2>'.$this->type_A(); break;	
					/* Alert */
					case 'ALERT':  $sx .= '<TR><TD><TD colspan=1>'.$this->type_ALERT(); break;
					/* Button */	
					case 'B':  $sx .= '<TD>'.$this->type_B(); break;	
					/* City, State, Country */
					case 'CITY':  $sx .= $sh. $this->type_City(); break;
					
					/* Date */
					case 'DECLA':  $sx .= $this->type_DECLA(); break;
										
					/* Date */
					case 'D':  $sx .= $sh. $this->type_D(); break;
					
					/* Funcoes adicionais */
					case 'FC':				
						$this->par = substr($cp[0],3,strlen($cp[0])); 
						
						if ($this->par == '001') { $sx .= function_001(); } 
						if ($this->par == '002') { $sx .= function_002(); }
						if ($this->par == '003') { $sx .= function_003(); }
						if ($this->par == '004') { $sx .= function_004(); }
						if ($this->par == '005') { $sx .= function_005(); }
						if ($this->par == '006') { $sx .= function_006(); } 
						
						break;		
					/* Files */
					case 'FILES':
						$http = http;
						$sx .= '<TD>';
						$sx .= $ged->file_list();
						$sx .= $ged->upload_botton_with_type($ged->protocolo,'','');
						break;
					/* KeyWord */
					case 'KEYWO':  $sx .= $sh. $this->type_KEYWORDS(); break;						
					/* Hidden */
					case 'H':  $sx .= $this->type_H(); break;
					/* Hidden with value */
					case 'HV':  $sx .= $this->type_HV(); break;					
					/* Inteiro */
					case 'I':  $sx .= $sh. $this->type_I(); break;	
					/* MEnsagens */
					case 'M':  $sx .= $this->type_M(); break;
					/* Valor com dias casas */
					case 'N':  $sx .= $this->type_N(); break;
					/* Options */
					case 'O':  
						$this->par = substr($cp[0],2,strlen($cp[0]));
						$sx .= $sh. $this->type_O(); break;					
					/* String Simple */
					case 'P':  $sx .= $sh. $this->type_P(); break;					
					/* Query */
					case 'Q':
						$this->par = splitx(':',substr($cp[0],2,strlen($cp[0])));  
						$sx .= $sh. $this->type_Q(); 
						break;										
					/* String Simple */
					case 'S':  $sx .= $sh. $this->type_S(); break;
					/* String Simple */
					case 'T':
						$this->cols = sonumero(substr($cp[0],0,strpos($cp[0],':')));
						$this->rows = sonumero(substr($cp[0],strpos($cp[0],':'),100));
						$sx .= $sh. $this->type_T(); 
						break;
					/* String Ajax */
					case 'SA': $sx .= $sh. $this->type_SA(); break;
					/* Update */
					case 'U':  $sx .= $sh. $this->type_U(); break;
					/* Estados */
					case 'UF': $sx .= $sh. $this->type_UF(); break;					
				}
				return($sx);
			}

		/**
		 * {
		 */
		 function type_open_field()
		 	{
				$sx = "";
				if (strlen($this->caption) > 0) 
					{ 
					$vcol = 0;
					$sx .= '<TR><TD colspan="2">';
					$sx .= '<fieldset '.$this->class.'>';
					$sx .= '<legend><font class="lt1"><b>'.$this->caption.'</b></legend>';
					$sx .= '<table cellpadding="0" cellspacing="0" class="lt2" width="100%">';
					$sx .= '<TR valign="top">';
					}
				return($sx);
		 	}
		/**
		 * {
		 */
		 function type_close_field()
		 	{
				$sx = "";
				$sx .= '</fieldset>';
				$sx = '</table>';
				return($sx);
		 	}
		/**
		 * Function Sequencial
		 */	
		 function type_seq()
		 	{
		 		$par = $this->par;
				$dec = strpos($par,']D');
				if ($dec > 0) { $dec = 1; }
				$par = substr($par,0,strpos($par,']'));
				$par = splitx('-',$par);


				$sx = '
				<select name="'.$this->name.'" id="'.$this->name.'" size="1" '.$this->class.'>
					'.$this->class.' 
					id="'.$this->name.'" >';
				$sx .= '<option value="">'.msg('select_option').'</option>';
				if ($dec==0)
					{									
						for ($nnk=round($par[0]);$nnk <= round($par[1]);$nnk++)
						{
							$sel = '';
							if ($nnk==$txt) {$sel="selected";}
							$sx= $sx . "<option value=\"".$nnk."\" ".$sel.">".$nnk."</OPTION>";
						}
					} else {
						for ($nnk=round($par[1]);$nnk >= round($par[0]);$nnk--)
						{
							$sel = '';
							if ($nnk==$txt) {$sel="selected";}
							$sx= $sx . "<option value=\"".$nnk."\" ".$sel.">".$nnk."</OPTION>";
						} 
					}
				$sx = $sx . "</select>" ;
				return($sx);	
			}
					
		/***
		 * type_Autor
		 */
		function type_Autor()
			{
				global $dd,$ged;
				$sx = '<div id="autores">
				carregando.... aguarde...
				</div>';
				
				$link = http.'pb/ajax_autores.php?dd1='.$ged->protocolo;
				echo $link;
				$sx .= '
				<script>
					$.post(\''.$link.'\', function(data) {
					$("#autores").html(data);
					alert("load...");
					});
				</script>
				';
				return($sx);
			}
		/**
		 * Hidden
		 */	
		function type_A()
			{
				$sx = '
				<HR>
				<h2>'.$this->caption.'</h2>				
				';
				return($sx);
			}
		/**
		 * Hidden
		 */	
		function type_ALERT()
			{
				if (strlen($this->caption) > 0)
				{
					$sx = '<img src="'.http.'/img/icone_alert.png" height=40 align="left">';
					$sx .= $this->caption;
				}
				return($sx);
			}			
		/***
		 * Hidden
		 */	
		function type_B()
			{
				$sx = '
				<input 
					type="button" name="'.$this->name.'" value="'.$this->caption.'" 
					id="'.$this->name.'" class="bottom_submit" />';
				$this->js .= '
				<script>
					$("#'.$this->name.'").click(function() 
						{
							var vlr = $("#'.$this->name.'").val();
							$("#acao").val(vlr);
							$("#formulario").submit();						
						});
				</script>
				';
				/* $('#target').submit();*/ 
				return($sx);
			}
		/***
		 * City
		 */
		function type_City()
			{
				global $LANG;

				$sql = "Select * from ajax_pais where pais_ativo > 0 order by pais_prefe desc, pais_ativo desc, pais_nome ";
				$rrr = db_query($sql); 
				$opt = '<option value="">'.msg('select_your_country').'</option>';
				while ($line = db_read($rrr))
				{
					$check = '';
					$opv = trim($line['pais_codigo']);
					$opd = trim($line['pais_nome']);
					if (trim($this->value)==$opv) { $check = 'selected'; }
					$opt .= chr(13);
					$opt .= '			<option value="'.$opv.'" '.$check.'>';
					$opt .= $opd;
					$opt .= '</option>';
				}
				/* Script dos estados */
				$js = '';
				$sx = '
				<select name="'.$this->name.'" id="'.$this->name.'" size="1" '.$this->class.'>
					'.$this->class.' 
					id="'.$this->name.'" >';
				$sx .= $opt.chr(13);
				$sx .= '</select>';
				return($sx);

			}
			
		/*********************************
		 * Data
		 */
		function type_D()
			{
				global $include,$acao;
				$sx = '
				<input 
					type="text" name="'.$this->name.'" size="13"
					value = "'.$this->value.'"
					maxlength="10" '.$this->class.' 
					id="'.$this->name.'"
					'.$msk.' />&nbsp;';
				$sx .= $this->requerido();

				/* SCRIPT */
				$gets = '
				<script>
					$("#'.$this->name.'").mask("99/99/9999");
					$("#'.$this->name.'").datepicker({
							showOn: "button",
							buttonImage: "'.http.'include/img/icone_calender.gif",
							buttonImageOnly: true,
							showAnim: "slideDown"	 
					});
				</script>
				';
				$this->js .= $gets;
				return($sx);				
			}

		/* Declaracao */
		function type_DECLA()
			{
				global $include,$acao;
				$sx ='<TR><TD colspan=2>';
				$sx .= $this->caption;
				$sx .= '<BR><BR>';
				$sx .= '
				<select name="'.$this->name.'" >
					<option value=""></option>
					<option value="SIM">SIM</option>
				</select>
				, concordo.
				';
				$sx .= $this->requerido();
				return($sx);				
			}

		/***
		 * Hidden
		 */	
		function type_H()
			{
				$sx = '
				<input 
					type="hidden" name="'.$this->name.'" 
					value="'.$this->value.'" id="'.$this->name.'" />';
				return($sx);
			}
		/***
		 * Hidden with value
		 */	
		function type_HV()
			{ 
				$sx = '
				<input 
					type="hidden" name="'.$this->name.'" 
					value="'.$this->caption.'" id="'.$this->name.'" />';
				return($sx);
			}

		/**
		 * KEYWORD
		 */
		function type_KEYWORDS()
			{
			$sx = '
				<input 
					type="text" name="'.$this->name.'" value="'.$this->value.'" 
					id="'.$this->name.'" '.$this->class.' />';
				$this->js .= '
				<script>
					$(function() {
						$("#'.$this->name.'").tagsInput({width:\'auto\'});
					});
				</script>
				';
				/* $('#target').submit();*/ 
				return($sx);
			}
		/***
		 * Valores Interiors
		 */
		function type_I()
			{
				global $include;
				$sx = '
				<input 
					type="text" name="'.$this->name.'" size="18"
					value = "'.$this->value.'"
					maxlength="15" '.$this->class.' 
					id="'.$this->name.'"
					'.$msk.' />&nbsp;';
				
				/* SCRIPT */
				$gets = '
				<script>
					$(document).ready(function(){
						$("#'.$this->name.'").maskMoney({precision:0, thousands:""});
					});
				</script>
				';
				$this->js .= $gets;
				return($sx);				
			}
		/* Mensagem */
		function type_M()
			{
				global $include,$acao;
				$sx ='<TR><TD colspan=2>';
				$sx .= $this->caption;
				return($sx);				
			}			
			
		/***
		 * Valor com duas casa decimais
		 */
		function type_N()
			{
				global $include;
				$sx = '
				<input 
					type="text" name="'.$this->name.'" size="18"
					value = "'.$this->value.'"
					maxlength="15" '.$this->class.' 
					id="'.$this->name.'"
					'.$msk.' />&nbsp;';
				
				/* SCRIPT */
				$gets = '
				<script>
					$("#'.$this->name.'").maskMoney();
				</script>
				';
				$this->js .= $gets;
				return($sx);				
			}


		/***
		 * String
		 */			
		function type_Q()
			{
				$sql = $this->par[2];
				$rrr = db_query($sql);
				$opt = '<option value="">'.msg('Selecione uma opção').'</option>';
				while ($line = db_read($rrr))
				{
					$check = '';
					$opd = trim($line[$this->par[0]]);
					$opv = trim($line[$this->par[1]]);
					if ($this->value==$opv) { $check = 'selected'; }
					$opt .= chr(13);
					$opt .= '			<option value="'.$opv.'" '.$check.'>';
					$opt .= $opd;
					$opt .= '</option>';
				}
				$sx = '
				<select name="'.$this->name.'" size="1" '.$this->class.'>
					'.$this->class.' 
					id="'.$this->name.'" >';
				$sx .= $opt.chr(13);
				$sx .= '</select>';
				return($sx);
			}
		/***
		 * String
		 */			
		function type_S()
			{
				if ($this->size > 70) { $style = ' size="70" style="width: 90%;" ';}
				else { $style = 'size="'.$this->size.'" '; }
				$sx = '
				<input 
					type="text" name="'.$this->name.'" 
					value = "'.$this->value.'"
					maxlength="'.$this->maxlength.'" '.$this->class.' '.$style.' 
					id="'.$this->name.'" />'.chr(13);
				$sx .= $this->requerido();
				return($sx);
			}
		/***
		 * Options
		 */			
		function type_O()
			{
				$ops = splitx('&',$this->par);
				
				$sx = '
				<select name="'.$this->name.'"
					'.$this->class.' '.$style.' 
					id="'.$this->name.'" />'.chr(13);
				for ($r=0;$r < count($ops);$r++)
					{
						$so = $ops[$r];
						$check = '';
						
						$vl = substr($so,0,strpos($so,':'));
						if ($this->value==$vl) { $check = 'selected'; }
						$sx .= '<option value="'.$vl.'" '.$check.'>';
						$sx .= trim(substr($so,strpos($so,':')+1,strlen($so)));
						$sx .= '</option>'.chr(13);
					}
				$sx .= '</select>';
				return($sx);
			}
		/***
		 * String
		 */			
		function type_P()
			{
				if ($this->size > 70) { $style = ' size="70" style="width: 90%;" ';}
				else { $style = 'size="'.$this->size.'" '; }
				$sx = '
				<input 
					type="password" name="'.$this->name.'" 
					value = "'.$this->value.'"
					maxlength="'.$this->maxlength.'" '.$this->class.' '.$style.' 
					id="'.$this->name.'" />'.chr(13);
				$sx .= $this->requerido();
				return($sx);
			}
			
		/**
		 * String Ajax
		 */	
		function type_SA()
			{
				if ($this->size > 70) { $style = ' size="70" style="width: 90%;" ';}
				else { $style = 'size="'.$this->size.'" '; }				
				$sx = '
				<input 
					type="text" name="'.$this->name.'" 
					value = "'.$this->value.'"
					maxlength="'.$this->maxlength.'" '.$this->class.' '.$style.' 
					id="'.$this->name.'" />';
				
				$gets = '
				<script>
					$("#'.$this->name.'").autocomplete({
						source: "/reol/pb/ajax_instituicao.php",
   						minLength: 1,
   						matchContains: true,
        				selectFirst: false
					});				
				</script>';
				$this->js .= $gets;
				return($sx);
			}
		/***
		 * String
		 */			
		function type_T()
			{
				if (round($this->cols)==0) { $this->cols = 80; }
				if (round($this->rows)==0) { $this->rows = 5; }
				$sx = '
				<TEXTAREA 
					type="text" name="'.$this->name.'" size="'.$this->size.'"
					cols="'.$this->cols.'"
					rows="'.$this->rows.'" '.$this->class.' 
					id="'.$this->name.'" />';
				$sx .= $this->value;
				$sx .= '</textarea>';
				$sx .= $this->requerido();
				return($sx);
			}
		/***
		 * Hidden
		 */	
		function type_U()
			{
				$sx = '
				<input 
					type="hidden" name="'.$this->name.'" 
					value="'.date("Ymd").'" id="'.$this->name.'" />';
				return($sx);
			}			
		/***
		 * Estado
		 */
		function type_UF()
			{
				global $LANG;

				$estados = array("99"=>"Outside Brazil","AC"=>"Acre","AL"=>"Alagoas","AM"=>"Amazonas","AP"=>"Amapá",
					"BA"=>"Bahia","CE"=>"Ceará","DF"=>"Distrito Federal","ES"=>"Espírito Santo",
					"GO"=>"Goiás","MA"=>"Maranhão","MT"=>"Mato Grosso","MS"=>"Mato Grosso do Sul",
					"MG"=>"Minas Gerais","PA"=>"Pará","PB"=>"Paraíba","PR"=>"Paraná",
					"PE"=>"Pernambuco","PI"=>"Piauí","RJ"=>"Rio de Janeiro","RN"=>"Rio Grande do Norte",
					"RO"=>"Rondônia","RS"=>"Rio Grande do Sul","RR"=>"Roraima","SC"=>"Santa Catarina",
					"SE"=>"Sergipe","SP"=>"São Paulo","TO"=>"Tocantins");

				$opt = '<option value="">'.msg('select_state').'</option>';
				foreach (array_keys($estados) as $key=>$value) {
					$check = '';
					$opv = $value;
					$opd = $estados[$opv];
					if ($this->value == $opv) { $check = 'selected'; }
					$opt .= chr(13);
					$opt .= '			<option value="'.$opv.'" '.$check.'>';
					$opt .= $opd;
					$opt .= '</option>';
					}				
				$sx = '
				<select name="'.$this->name.'" id="'.$this->name.'" size="1" '.$this->class.'>
					'.$this->class.' 
					id="'.$this->name.'" >';
				$sx .= $opt.chr(13);
				$sx .= '</select>';
				
				return($sx);

			}
		function requerido()
			{
				$sx = '';
				if ($this->required == 1)
					{
						if (strlen($this->value) == 0 )
						{ 
							$sx .= '<div style="color: red">'.msg('campo_requirido').'</div>'.chr(13);
						}
						
					}
				return($sx);
			}		
	}
?>