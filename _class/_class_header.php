<?php
    /**
     * Cabeï¿½alho
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.46
	 * @package header
	 * @subpackage classe
    */
class header
	{
	var $js='';
	var $css='';
	var $title = '::Fonzaghi::';
	var $char_set = 'ISO-8859-1';
	var $http = '/fonzaghi/';
	
	
	function mostra_nome_login($log)
		{
			global $base_name,$base_server,$base_host,$base_user;
			if (strlen($id) > 0) {$this->codigo = $id; }
			/* abre banco das consultoras */
			require("../db_fghi.php");
						
			$sql = "select * from usuario where us_login = '".$log."' ";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$nome = trim($line['us_nomecompleto']);
					$nome .= '<BR>'.$log;
				}
			return($nome);
		}
	
	function icone_page()
		{
			$file = 'icone_favorito.png';
			if (file_exists($file))
				{
					$sx = '';
				}
			return($sx);
		}
	
	function breadcrumbs()
		{
			global $breadcrumbs;
			$sx = '<div id="breadcrumbs">';
			for ($r=0;$r < count($breadcrumbs);$r++)
				{
					if ($r > 0)
						{ $sx .= ' '; }
					$sx .= '&raquo;<a HREF="'.$breadcrumbs[$r][0].'" class="breadlink">';
					$sx .= $breadcrumbs[$r][1];
					$sx .= '</A>';
				}
			$sx .= '</div>';
			return($sx);			
		}
	function cab_noshow()
        {
            global $pref;
            header("Content-Type: text/html; charset=".$this->char_set,true);
			$cr = chr(13).chr(10);
            $sx = '<!DOCTYPE html>'.$cr;
            $sx .= '<html lang="pt-BR">'.$cr;
            $sx .= '<head>'.$cr;
            $sx .= '<title>'.$this->title.'</title>'.$cr;
            $sx .= '<meta http-equiv="Content-Type" content="text/html; charset='.$this->char_set.'" />'.$cr;
            $sx .= '<meta name="expires" content="never" />'.$cr;
            $sx .= '<link rel="shortcut icon" type="img/favicone.png" />'.$cr;
            $sx .= '<!-- STYLES // -->  '.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_cab.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_fonts.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_botao.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_table.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_lts.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_consultora.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_menus.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_ajax.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/_class_style.css" />'.$cr;
            $sx .= '<link rel="stylesheet" type="text/css" media="print" href="'.$this->http.'css/style_print.css" />'.$cr;         
            $sx .= $this->js.$cr;
            $sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery.js"></script>'.$cr;
            $sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery.corner.js"></script>'.$cr;
            $sx .= '</head>'.$cr;
            $sx .= '<body leftmargin="0" topmargin="0" >'.$cr;
            return($sx);
        }
	function cab()
		{
			global $pref;
			$LANG = 'pt_BR';
			$cr = chr(13).chr(10);
			header("Content-Type: text/html; charset=".$this->char_set,true);
			$sx = '<!DOCTYPE html>'.$cr;
			$sx .= '<html lang="pt-BR">'.$cr;
			$sx .= '<head>'.$cr;
			$sx .= '<title>'.$this->title.'</title>'.$cr;
			$sx .= '<meta http-equiv="Content-Type" content="text/html; charset='.$this->char_set.'" />'.$cr;
			$sx .= '<meta name="expires" content="never" />'.$cr;
			$sx .= '<link rel="shortcut icon" type="img/favicone.png" />'.$cr;
			$sx .= '<!-- STYLES // -->	'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_cab.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_fonts.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_table.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_lts.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_consultora.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_ballon.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_menus.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_botao.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/style_ajax.css" />'.$cr;
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/calender_data.css" />'.$cr;	
			$sx .= '<link rel="stylesheet" type="text/css" href="'.$this->http.'css/_class_style.css" />'.$cr;		
			$sx .= '<link rel="stylesheet" type="text/css" media="print" href="'.$this->http.'css/style_print.css" />'.$cr;			
			$sx .= $this->js.$cr;
			
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery.js"></script>'.$cr;
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery-ui.js"></script>'.$cr;			
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery.corner.js"></script>'.$cr;
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/calender_data.css"></script>'.$cr;
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery.maskedit.js"></script>'.$cr;
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery.maskmoney.js"></script>'.$cr;
			$sx .= '<script language="JavaScript" type="text/javascript" src="'.$this->http.'include/js/jquery-ui-datepicker-localisation/jquery.ui.datepicker-'.str_replace('_', '-', $LANG).'.js"></script>'.$cr;
			
			$sx .= '</head>'.$cr;
			$sx .= '<body leftmargin="0" topmargin="0" >'.$cr;
			$sx .= $this->icone_page();
			
			$sx .= '<div id="header">';
				//$sx .= $this->short();
				//$sx .= $this->user_id();
				//$sx .= '<center>';
				//$sx .= '<IMG SRC="/fonzaghi/img/bg_cab.png">';
				//$sx .= '</center>';
				
				$sx .= '

				<div style="z-index:999;width:100%; background-color:#141a18; background-image: url('.$this->http.'img/cab_header_01.png); background-repeat: no-repeat; background-position:top">
  <div id="menu" style="position:relative; width:100%; height:78px; margin:0 auto">
    <div>
    	'.$this->short().'
		'.$this->user_id().'
    </div>
    
    <div style="position:relative; margin:0px 0 0 10px; height:auto; float:left;">
      <div><span class="logo_intranet">FONZAGHI Intranet</span></div>
      <div class="left"><a href="'.$this->http.'main.php" class="menu">Home</a></span></div>
      <div style="position:relative;float:left; padding:0px 15px 0 15px"><span style="color:#FFFFFF;">|</span></div>
      <div class="left"><a href="'.$this->http.'my_account.php" class="menu">Meus dados</a></span></div>
      <div style="position:relative;float:left; padding:0px 15px 0 15px"><span style="color:#FFFFFF;">|</span></div>
      <div class="left"><a href="'.$this->http.'call/ramais.php" target="_blank" class="menu">Ramais</a></span></div>
      <div style="position:relative;float:left; padding:0px 15px 0 15px"><span style="color:#FFFFFF;">|</span></div>
      <div class="left"><a href="'.$this->http.'logout.php" class="menu">Sair</a></span></div>
    </div>
  </div>
</div>';				
				
			$sx .= '</div>';
			
			$sx .= $this->breadcrumbs();
			return($sx);
		}
	function user_id()
		{
			global $user;
			$nm = trim($user->user_nome);
			$nn = '';
			$mm = 1;
			for ($r=0;$r < strlen($nm);$r++)
				{
					$cha = substr($nm,$r,1);
					if ($mm==1)
						{ $nn .= substr($nm,$r,1); $mm=0; }
					else
						{ $nn .= lowercase(substr($nm,$r,1)); }
						
					if (substr($nm,$r,1)==' ') { $nn .= ' '; $mm=1; }
				}
			
			$img = $this->http.'img/foto/'.$user->user_cracha.'.JPG';
			$img = '/fonzaghi/img/foto/'.$user->user_cracha.'.JPG';
			$img_gear = $this->http.'img/icone_gear.png';
			$http_gear = '<A HREF="'.$this->http.'my_account.php">';
			
			$sx .= '<div id="user_id" style="text-align: right; float: right; padding: 5px 5px 5px 5px; ">
					<img src="'.$img.'" height="40" align="right" border=0 style="padding: 5px; border: 0px solid #404040; ">
					<nobr><span id="user_name">'.$nn.'</span></nobr>
					<BR><span id="user_log">'.$user->user_log.'</span>
					<BR>'.$http_gear.'<img src="'.$img_gear.'" title="configuraï¿½ï¿½es" border=0 height="20"></A>
					</div>
			';
			return($sx);
		}
	function short()
		{
			global $user,$perfil;
	
			$app = new apps;
			$sx .= '<div id="shortkey" class="left">';
			$sx .= '<img src="'.$this->http.'img/icone_shortcut.png" height="75" border=0 align="left" alt="atalho de acesso" title="atalho de acesso">';
			$sx .= '</div>'.chr(13);
			
			$basic = $this->menus_basic();
			$basic = 'Sistemas<BR>'.$basic.'<BR>';
			
			$appt = $this->menus_apps();
			$appt = 'Lojas<BR>'.$appt.'<BR>';
			
			if ($perfil->valid('#DIR#ADM#CMK#AUF#CCC#SSS'))
				{
				$gest = $this->menus_gestao();
				$gest = 'Gestão<BR>'.$gest.'<BR>';
				}
			if ($perfil->valid('#ADM#FIN#AUF#CAI'))
				{
				$fina = $this->menus_financeiro();
				$fina = 'Financeiro<BR>'.$fina.'<BR>';
				}			
			if ($perfil->valid('#ADM#MAR#CMK'))
				{
				$makt = $this->menus_marketing();
				$makt = 'Marketing<BR>'.$makt.'<BR>';
				}	
			
			if ($perfil->valid('#ADM#DRH#FIL#AVA'))
				{
				$drh = $this->menus_drh();
				$drh = 'DRH<BR>'.$drh.'<BR>';
				}
			if ($perfil->valid('#ADM#CMK#AUF#COJ#COM#COO#COS#GER#GEG#GEC'))
				{
				$ger = $this->menus_gerencial();
				$ger = 'Gerencial<BR>'.$ger.'<BR>';
				}
			if ($perfil->valid('#ADM#CMK#AUF#COJ#COM#COO#COS#GER#GEG#GEC#CPR'))
				{
				$comp = $this->menus_compras();
				$comp = 'Compras/Pedidos<BR>'.$comp.'<BR>';
				}	
			
				$cat = $this->menus_catalogo();
				$cat = 'Catálogo Use Brilhe<BR>'.$cat.'<BR>';
				
				$recep = $this->menus_recepcao();
				$recep = 'Recepção<BR>'.$recep.'<BR>';
			
			if ($perfil->valid('#ADM#AUF#GER#GEG#GEC'))
				{
				$cont = $this->menus_contabilidade();
				$cont = 'Contabilidade<BR>'.$cont.'<BR>';
				}
			//if ($perfil->valid('#DIR#ADM#MAR#CMK#SSS#CCC'))
			//	{
				$ged = $this->menus_ged();
				$ged = 'GED<BR>'.$ged.'<BR>';
			//	}
			if ($perfil->valid('#DIR#ADM#CMK#AUF#CCC#SSS'))
				{
				$coo = $this->menus_coordenadoras();
				$coo = 'Coordenação<BR>'.$coo.'<BR>';
				}
			if ($perfil->valid('#ADM#CAD#TEL#MAR#CMK#COJ#COM#COO#COS#GER#GEG#GEC#REC'))
				{
				$cad = $this->menus_cadastro();
				$cad = 'Cadastro<BR>'.$cad.'<BR>';
				}
			if ($perfil->valid('#ADM#CMK#AUF#COJ#COM#COO#COS#GER#GEG#GEC'))
				{
				$bi = $this->menus_bi();
				$bi = 'Business Intelligence<BR>'.$bi.'<BR>';
				}	
			if ($perfil->valid('#ADM'))
				{
				$adm = $this->menus_adm();
				$adm = 'Suporte<BR>'.$adm.'<BR>';
				}
			if ($perfil->valid('#ADM#COB#SSS#CCC'))
				{
				$cob = $this->menus_cobranca();
				$cob = 'Cobrança<BR>'.$cob.'<BR>';
				}		
			
			
				$uti = $this->menus_utilitarios();
				$uti = 'Utilitários<BR>'.$uti.'<BR>';
				
			$cab = '<table width="99%" border=0><TR valign="top">';
			$foot = '</table>';
			
			$sx .= $app->show($cab.'<td align="left">'.
							  $basic
							  .$appt
							  .$gest
							  .$fina
							  .$makt
							  .$drh
							  .'<TD align="left">'
							  .$ger
							  .$recep
							  .$cont
							  .$cat
							  .$ged
							  .$coo
							  .'<TD align="left">'
							  .$cad
							  .$bi
							  .$adm
							  .$comp
							  .$cob
							  .$uti
							  .$foot
							  );
			$sx .=  '
			<script language="JavaScript" src="../js/shortcut.js"></script>
			<script>
					$("#shortkey").click(function() {
						var lf = $("#onkey").offset().left;
						if (lf > 0) 
							{ lp = "-900px"; } 
							else 
							{ lp = "50px"; }			
						$("#onkey").animate({ left: lp });
					});
					</script>
			';
			$sx.='<script>
					shortcut.add("CTRL+0", function() {
						var lf = $("#onkey").offset().left;
						if (lf > 0) 
							{ lp = "-900px"; } 
							else 
							{ lp = "50px"; }			
						$("#onkey").animate({ left: lp });
					});
					</script>';
			return($sx);
		}
	function menus_basic()
		{
			global $http;
			$link = array('','','','','','','','','','');
			$title = array('Consultoras','','','');
			$link[0] = $http.'consultora.php';
			for ($r=0;$r <= 0;$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_p_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_p_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_p_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	function menus_cobranca()
		{
			global $http;
			$link = array($http.'cobranca/index.php','','','','','','');
			$title = array('Cobrança','','','','','','');
			
			for ($r=0;$r <= 0;$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_cob_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_cob_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_cob_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}

		function menus_utilitarios()
		{
			global $http;
			$link = array($http.'sms/index.php','','','','','','');
			$title = array('SMS','','','','','','');
			
			for ($r=0;$r <= 0;$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_uti_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_uti_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_uti_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}

	function menus_compras()
		{
			global $http;
			$link = array($http.'pedidos/index.php','','','','','','');
			$title = array('Pedidos','','','','','','');
			
			for ($r=0;$r <= 0;$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_comp_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_comp_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_comp_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	
	function menus_adm()
		{
			global $http;
			$link = array($http.'admin/index.php',$http.'call/index.php',$http.'ger/impressora.php','','','','');
			$title = array('Perfil','Telefonia','Impressoras','','','','');
			
			for ($r=0;$r <= 2; $r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_admin_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_admin_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_admin_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}

	function menus_bi()
		{
			global $http;
			$link = array($http.'bi/index.php','','','','','','');
			$title = array('Business Intelligence');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_bi_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_bi_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_bi_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}

	function menus_cadastro()
		{
			global $http;
			$link = array($http.'tele2/login.php',$http.'cadastro/index.php',$http.'cadastro/acp_consulta.php');
			$title = array('Telemarketing','Cadastro','Consulta ACP');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_cad_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_cad_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_cad_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}



	function menus_ged()
		{
			global $http;
			$link = array($http.'iso/iso.php');
			$title = array('GED');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_ged_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_ged_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_ged_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}

	function menus_coordenadoras()
		{
			global $http;
			$link = array($http.'coordenadoras/index.php');
			$title = array('Coordenção');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_coo_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_coo_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_coo_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}



	function menus_contabilidade()
		{
			global $http;
			$link = array($http.'contabilidade/index.php');
			$title = array('Contas');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_cont_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_cont_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_cont_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}

	function menus_recepcao()
		{
			global $http;
			$link = array($http.'recepcao/main.php" target="_blank',$http.'campanha/login.php" target="_blank');
			$title = array('Recepção','Lançamento de Créditos Senff');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_recep_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_recep_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_recep_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	
	
	function menus_catalogo()
		{
			global $http;
			$link = array($http.'usebrilhe/index.php');
			$title = array('Catálogo');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_cat_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_cat_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_cat_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}


	function menus_gerencial()
		{
			global $http;
			$link = array($http.'ger/ger.php',$http.'ger/fat.php',$http.'ger/acertos_lojas.php');
			$title = array('Faturamento','Recebimentos','Análise de acertos');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_ger_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_ger_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_ger_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	
	function menus_drh()
		{
			global $http,$perfil;
		
			$link = array();
			$title = array();


			if ($perfil->valid('#ADM#DRH'))
				{
					array_push($link,$http.'drh/index_drh_funcionarios.php');
					array_push($title,'Funcionários');					
				}
				
			if ($perfil->valid('#ADM#FIL'))
				{
				array_push($link,$http.'drh/index_compras_funcionarios.php');
				array_push($title,'Compras de Lojas');
				}	
								
			if ($perfil->valid('#ADM#DRH#AVA'))
				{
					array_push($link,$http.'avaliacoes/index.php');
					array_push($title,'Avaliações');					
				}	
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_drh_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_drh_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_drh_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	function menus_financeiro()
		{
			global $http,$perfil;
			
			$link = array();
			$title = array();
			
			
			if ($perfil->valid('#ADM#FIN#AUF'))
				{
					array_push($link,$http.'financeiro/caixa_central.php');
					array_push($title,'Caixa Central');					
				}
			if ($perfil->valid('#ADM#FIN#AUF'))
				{
					array_push($link,$http.'financeiro/index.php');
					array_push($title,'Financeiro');					
				}
			if ($perfil->valid('#ADM#FIN#AUF#CAI'))
				{
					array_push($link,$http.'caixa/" target="_blank');
					array_push($title,'Caixa');					
				}
			if ($perfil->valid('#ADM#FIN#AUF'))
				{
					array_push($link,$http.'financeiro/lotes.php');
					array_push($title,'Lotes de Caixa');					
				}			
			if ($perfil->valid('#ADM#FIN#AUF'))
				{
					array_push($link,$http.'financeiro/bancos.php');
					array_push($title,'Bancos');					
				}
			if ($perfil->valid('#ADM#FIN#AUF'))
				{
					array_push($link,$http.'eNF/index.php');
					array_push($title,'eNF');					
				}		
			if ($perfil->valid('#ADM#FIN#AUF'))
				{
					array_push($link,$http.'financeiro/index_caixa.php');
					array_push($title,'Cartões');					
				}	
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_fin_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_fin_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_fin_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	
	function menus_marketing()
		{
			global $http;
			$link = array($http.'marketing/campanhas.php',$http.'senff',$http.'cursos/index.php',$http.'cursos/index_master.php',$http.'campanha/login.php" target="_blank',$http.'lj_promo"');
			$title = array('Campanhas','Cartão Senff','Cursos de Capacitação','Cursos de Master','Lançamento de Créditos Senff','Promo');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_mkt_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_mkt_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_mkt_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}		
		
	function menus_gestao()
		{
			global $http;
			$link = array($http.'melhor_atender',$http.'bi/consultoras.php',$http.'calendario.php',$http.'bi/desistencia.php');
			$title = array('Melhor Atender','Consultoras por bairro','Calendário','Relatório de desistência');
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_ges_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_ges_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_ges_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
	
		}
	function menus_apps()
		{
			global $http,$user,$perfil;
			$link = array('','','','','','','','','','','','');
			$title = array('Jóias','Modas','Óculos','UseBrilhe','Sensual',
							'Modas Express','Jóias Express');
						
			$link[0] = $http.'lj_joias/';
			$link[1] = $http.'lj_modas/';
			$link[2] = $http.'lj_oculos/';
			$link[3] = $http.'lj_ub/';
			$link[4] = $http.'lj_sensual/';
			$link[5] = $http.'lj_modas_ex/';
			$link[6] = $http.'lj_joias_Ex/';
			if ($perfil->valid("#ADM")) 
				{
				array_push($title,'TST'); 
				$link[7] = $http.'lj_tst/'; 
				}
			
			
			for ($r=0;$r < count($title);$r++)
				{
				$xlink = trim($link[$r]);
				$xlinka = '';
				if (strlen($xlink) > 0)
					{
						$xlink = '<A HREF="'.$link[$r].'">';
						$xlinka = '</A>';
					}
				$sx .= $xlink;
				$sx .= ' <img src="'.$http.'img/icone_lj_'.$r.'a.png" height="45" border=0 
							onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_lj_'.$r.'.png\');" 
							onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_lj_'.$r.'a.png\');"
							title = "'.$title[$r].'"
							>';
				$sx .= $xlinka;							
				}
			
			return($sx);
			
		}	
	function top_menu()
		{
			global $user;
			$log = $user->user_login;

				$sx = '<div id="top_menu">';
					$sx .= '<UL>';
					$sx .= '<LI><a href="'.$this->http.'main.php"><img src="'.$this->http.'img/icone_home.png" height=15 border=0></A></LI>';
					$sx .= '<LI><a href="'.$this->http.'main.php">home</A></LI>';
					$sx .= '<LI><a href="'.$this->http.'my_account.php">meus dados</A></LI>';
					$sx .= '<LI><a href="'.$this->http.'logout.php">sair</A></LI>';
					$sx .= '<LI id="li_right"><a href="'.$this->http.'_logout.php">sair</A></LI>';					
					$sx .= '</UL>';
				$sx .= '</div>'.chr(13);
			return($sx);
		}	
		
	function foot()
		{
			global $pREF;
			$sx = '<center>&copy 2004-'.date("Y").'</center>';
			$sx .= '<center><font style="font-size:10px">'.$pREF.'</font></center>';
			return($sx);
		}
	}
?>