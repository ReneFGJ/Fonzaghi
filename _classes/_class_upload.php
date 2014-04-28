<?
class upload
	{
		var $id;
		var $table_bg;
		var $titulo;
		var $classe;
		var $fld;
		var $limit;
		var $upload_dir;
		var $controle_mes;
		var $info;
		var $lt1;
		var $lt2;
		var $lt1i;
		var $body;
		var $tabela_ged;
		var $updatex;
		var $filename;
		var $ext;
		var $data;
		
		var $msg;
		
		
		function upload_config()
		{
			$this->table_bg = "#c0c0c0";
			$this->titulo = "Submeter arquivos (todos os tipos)";
			$this->classe = "Documentos";
			$this->fld = array("pdf",'jpg','png');
			$this->limit = 2 * 1024 * 1024;
	
			////////////// Pasta para gravar imagens
			$this->upload_dir = '/dados/imagens/cadastro/';
			$this->controle_mes = 1; // abre nova pasta para cada ano / mês
			
			$this->info = "";
			
			$this->lt1  = "font-family : Arial, Helvetica, sans-serif; font-size: 12px; color : Black; ";
			$this->lt2  = "font-family : Arial, Helvetica, sans-serif; font-size: 14px; color : Black; ";
			$this->lt1i = "font-family : Arial, Helvetica, sans-serif; font-size: 12px; color : Blue; ";
			$this->body = "background-image : url(upload_bg.png); background-position : center; background-repeat : repeat;";
		
			$this->tabela_ged = ''; // nome da tabela que salva os arquivos GED
			$this->updatex = ''; // arquivo que chama quando salva corretamente
		}
		function download_documento($file,$name,$ext)
			{
				$fsize = filesize($file); 
				$img = 0;
    			switch ($ext) {
      				case "pdf": $ctype="application/pdf"; break;
      				case "exe": $ctype="application/octet-stream"; break;
      				case "zip": $ctype="application/zip"; break;
      				case "doc": $ctype="application/msword"; break;
      				case "xls": $ctype="application/vnd.ms-excel"; break;
      				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      				case "gif": $ctype="image/gif"; $img = 1; break;
      				case "png": $ctype="image/png"; $img = 1; break;
      				case "jpeg": $ctype="image/jpg"; $img = 1; break;
      				case "jpg": $ctype="image/jpg"; $img = 1; break;
      				default: $ctype="application/force-download";
      				} 
					header("Content-type: $ctype");
					header("Pragma: public"); 
					header("Expires: 0"); 
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
					header("Cache-Control: private",false);
					header("Content-Disposition: attachment; filename=\"".basename($name)."\";" );
					header("Content-Transfer-Encoding: binary"); 
					header("Content-Length: ".$fsize); 
					ob_clean();
					flush();
					readfile( $file );
				return(1);  
			}
		function mostra_documento()
			{
				global $user_nivel,$secu;
				$tp = $this->fld;
				$lfile = $this->upload_dir;
				$file .= $this->filename.'-'.substr(md5($secu.$this->filename),5,8);
				$xfile = '';
				$ext = '';
				for ($r=0;$r < count($tp);$r++)
					{
						$vfile = $lfile.$file.'.'.$tp[$r];
						if (file_exists($vfile))
							{ $xfile = $vfile; $ext = $tp[$r]; }
					}
					
				/* Com controle de mes */
				for ($r=0;$r < count($tp);$r++)
					{
						$comp = substr($this->data,0,4).'/'.substr($this->data,4,2).'/';						
						$vfile = $lfile.$comp.$file.'.'.$tp[$r];
						if (file_exists($vfile))
							{ $xfile = $vfile; $ext = $tp[$r]; }
					}				
				/* */
				if (strlen($xfile) > 0)
					{
						$this->download_documento($xfile,$this->filename.'.'.$ext,$ext);
					} else {
						echo 'Arquivo não localizado';
						return(0);
					}
				return(1);
				
			}
			
		function arquivo_salva()
			{
				global $_FILES,$secu;
					$filename = trim($_FILES['userfile']['name']);
					$size = trim($_FILES['userfile']['size']);
					$erro = $_FILES['userfile']['error'];				
					$filenamex .= strtolower($filename);
					$file = strzero($dd[0],7);
					$ver = 1;
					$filenamex = troca($filenamex,' ','_');
					if (strlen($filename) > 0)
						{
							/* Valida Formato */
							$vld = $this->valida_formato($filename);
							if ($vld == 1)
								{
									
								if (strlen($this->filename) == 0)
									{ $file = $filename; } else {
									{ $file = $this->filename; }
									}
								/* Controle de Mês */
								$upload_dir_compl = '';
								if ($this->controle_mes == 1)
									{
									$upload_dir_compl = date("Y");
									$this->checadiretorio($this->upload_dir.$upload_dir_compl);
									$upload_dir_compl .= '/'.date("m");
									$this->checadiretorio($this->upload_dir.$upload_dir_compl);
									$upload_dir_compl .= '/';
									}
								/* ***/
								$uploadfile = $this->upload_dir.$upload_dir_compl.$file.'-'.substr(md5($secu.$file),5,8).'.'.$this->ext;
								if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
									{
										if (file_exists('../close.php'))
											{ require("../close.php"); }
										echo 'Salvo';
										exit;
									} else {
										echo 'ERRO';
									}
								}
						}
			}
		function valida_formato($file)
			{
				$ext = strtolower($file);
				while (strpos($ext,'.') > 0)
					{ $ext = substr($ext,strpos($ext,'.')+1,strlen($ext)); }
				$this->ext = $ext;
				//////////////////////////////////////////////// VALIDA FORMATO
				$validado = 0;
				for ($re=0;$re < count($this->fld);$re++)
					{ if ($this->fld[$re] == $ext) { $validado = 1; } }
				if ($validado == 0) { $filename = ""; $this->msg = "Extensão inválida, verifique os formatos liberados para <I>upload</I>"; }
				return($validado);				
			}
		function mostra_formulario()
		{
			global $dd;
			$this->arquivo_salva();
			
			/* Recupera nome da página que chamou o formulario */
			$post = $_SERVER['SCRIPT_FILENAME'];
			while (strpos(' '.$post,'/'))
				{ $post = substr($post,strpos($post,'/')+1,200); }
	
			$sx .= '<style>'.chr(13);
			$sx .= '	.lt1 { '.$this->lt1.' }'.chr(13);
			$sx .= '	.lt1i { '.$this->lt1i.' }'.chr(13);
			$sx .= '	.lt2 { '.$this->lt2.''.chr(13);
			$sx .= '	body { '.$this->body.' }'.chr(13);
			$sx .= '</style>'.chr(13);
			$sx .= '<TITLE>'.$this->titulo.'</TITLE>'.chr(13);
			$sx .= '<BODY topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" '.$this->bgcolor.' class="lt1" >'.chr(13);
			$sx .= '<TABLE width="100%" align="center" border="0" class="lt1" >'.chr(13);
			$sx .= '<TR><TD colspan="2" bgcolor="<?=$table_bg;?>" align="center"><font class="lt2"><B><'.$this->titulo.' - '.$this->classe.'</font></TD>'.chr(13);
			$sx .= '</TR>'.chr(13);
			if (strlen($filename) == 0 ) {
				if (strlen($this->msg) > 0) /* Mostra mensagem de erro */
				{ $sx .= '<TR><TD align="center"><B><font color="red">'.$this->msg.'</font></TD></TR>'; }
				$sx .= '<TR valign="top"><TD align="right">'.chr(13);
				$sx .= '<form enctype="multipart/form-data" action="'.$post.'" method="POST">'.chr(13);
				$sx .= '<input type="hidden" name="MAX_FILE_SIZE" value="<?=$limit;?>">'.chr(13);
				$sx .= '<TD rowspan="10" width="64" align="center"><img src="../include/img/upload_icone.png" width="64" height="64" alt="" border="0">'.chr(13);
				$sx .= '<font style="font-size:10px;"><?=$upload_id;?><BR><?=$upload_ver;?></font>'.chr(13);
				$sx .= '</TD>'.chr(13);
				$sx .= ''.chr(13);
				/* Nova Linha */
				$sx .= '<TR><TD ><font class="lt1i"><?=$info;?></TD>'.chr(13);
				$sx .= '<TR><TD>&nbsp;</TD></TR>'.chr(13);
				$sx .= ''.chr(13);
				$sx .= '<!----- nova linha ---->'.chr(13);
				$sx .= '<TR><TD>Formatos válidos :<B>'.chr(13);
				for ($r=0 ; $r < count($this->fld);$r++)
					{
					if ($r > 0) { $sx .= ', '; }
						$sx .= '.'.$this->fld[$r];
					}

				$sx .= '</B>'.chr(13);
				$sx .= '</TD></TR>'.chr(13);

				/* Nova Linha */
				$sx .= '<TR><TD><font color="#ff8040">Tamanho máximo por arquivo:<B>';
				$sx .= $this->format_limit($this->limit).'</B></font></TD></TR>'.chr(13);
	
				/* Nova Linha */
				$sx .= '<TR><TD>&nbsp;</TD></TR>'.chr(13);
				$sx .= '<TR valign="top"><TD align="left">'.$this->txt1.'><BR>'.chr(13);
				$sx .= '<input name="userfile" type="file" class="lt2">'.chr(13);
				$sx .= '&nbsp;<input type="submit" value="e n v i a r" class="lt2" <?=$estilo?>>'.chr(13);
				$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">'.chr(13);
				$sx .= '<input type="hidden" name="dd1" value="'.$dd[1].'">'.chr(13);
				$sx .= '<input type="hidden" name="dd2" value="'.$dd[2].'">'.chr(13);
				$sx .= '<input type="hidden" name="dd3" value="'.$dd[3].'">'.chr(13);
				$sx .= '<input type="hidden" name="dd9" value="'.$dd[9].'"">'.chr(13);
				$sx .= '<input type="hidden" name="dd10" value="'.$dd[10].'">'.chr(13);
				$sx .= '</form>'.chr(13);
				$sx .= '</TD>'.chr(13);
				$sx .= '<TR><TD><BR><BR></TD></TR>'.chr(13);
				$sx .= '<TR><TD>Controle mês <B>'.dsp_sn($this->controle_mes).'</B></TD></TR>'.chr(13);
				$sx .= '</TABLE>'.chr(13);
				}
			return($sx);
		}
		
		function format_limit($limit)
			{
				if ($limit >= (1024 * 1024))
				{
					$limit_u = 's';
					$limit_msk = round(10 * $limit / (1024*1024))/10;
					$limit_unidade = "Mega"; 
					if ($limit_msk == 1) { $limit_u = ''; }
				} else {
					$limit_u = 's';
					$limit_msk = round(10 * $limit / (1024))/10;
					$limit_unidade = "k";
					if ($limit_msk == 1) { $limit_u = ''; }
				}
				return($limit_msk.$limit_unidade.' Byte'.$limit_u);
			}
			
		function checadiretorio($vdir)
			{
			global $site;
			if(is_dir($vdir))
				{ $rst =  '<FONT COLOR=GREEN>OK';
				} else { 
					$rst =  '<FONT COLOR=RED>NÃO OK';	
					mkdir($vdir, 0777);
					if(is_dir($vdir))
						{
						$rst =  '<FONT COLOR=BLUE>CRIADO';	
						}
				}
				$filename = $vdir."/index.htm";	
				if (!(file_exists($filename)))
				{
					$ourFileHandle = fopen($filename, 'w') or die("can't open file");
					$ss = "<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.01 Transitional//EN><html><head><title>404 : Page not found</title></head>";
					$ss = $ss . '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.$site.'">';
					$rst = $rst . '*';
					fwrite($ourFileHandle, $ss);
					fclose($ourFileHandle);		
				}
		//		echo '<BR>'.$vdir.' '.$rst;
			return($rst);
	}		
		
	
}
?>