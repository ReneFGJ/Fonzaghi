<?
//************************ UP_load
//************************ by Rene F. Gabriel Junior
//************************
$include = '../';
require("../db.php");

require($include.'sisdoc_debug.php');

$upload_id = "UpLoadX";
$upload_ver = "v0.11.01";

$filename = trim($_FILES['userfile']['name']);
$size = trim($_FILES['userfile']['size']);
$erro = $_FILES['userfile']['error'];

//$xfilename = troca(LowerCaseSQL($filename),' ','_');
$arq = $xfilename;
$uploadfile = $dir.$local.$arq;

///////////////////////////////////////////////////////////// MENSAGENS DO SISTEMA
$txt1 = 'Selecione o arquivo para enviar ao sistema';
$txt_erro_01 = "O sistema não suporte este tamanho de arquivo";
$txt_erro_02 = "O Arquivo enviado é maior que o limite para upload";
$txt_erro_03 = "Extensão inválida, verifique os formatos liberados para <I>upload</I>";

/////////////////////////////////////////////////////////////// CARREGA CONFIGURAÇÔES
require("upload_cfg_default.php");
$tp = "doc";
if (strlen($dd[9]) > 0) { $tp=$dd[9]; }

if (file_exists('upload_cfg_'.$tp.'.php')) { require("upload_cfg_".$tp.".php"); }
if (strlen(trim($titulo)) == 0) { $titulo = 'Submeter arquivo para Upload'; }


//////////////////////////////////////////////////////////////////// Cálculo do Limite
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
////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////// TRATAR ERRO DE SUBMISSAO
 if (strlen($filename) > 0 )
 	{
	if ($erro == '1') { $msg = $txt_erro_01; $filename = ""; }
	if ($erro == '2') { $msg = $txt_erro_02; $filename = ""; }
	$ext = strtolower($filename);
	while (strpos($ext,'.') > 0)
		{ $ext = substr($ext,strpos($ext,'.')+1,strlen($ext)); }
	//////////////////////////////////////////////// VALIDA FORMATO
	$validado = 0;
	for ($re=0;$re < count($fld);$re++)
		{ 
		if ($fld[$re] == $ext) 
			{ 
				$validado = 1; } 
			}
	if ($validado == 0) { $filename = ""; $msg = $txt_erro_03; }
	}
////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////// ESTILOS
?>
<style>
	.lt1 { <?= $lt1;?> }
	.lt1i { <?= $lt1i;?> }
	.lt2 { <?= $lt2;?> }
	body { <?= $body;?> }
</style>
<!------ Submeter arquivo ------->
<TITLE><?=$titulo;?></TITLE>
<BODY topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" <?=$bgcolor;?> class="lt1" >

<TABLE width="100%" align="center" border="0" class="lt1" >
<TR><TD colspan="2" bgcolor="<?=$table_bg;?>" align="center"><font class="lt2"><B><?=$titulo;?> - <?=$classe;?></font></TD>
</TR>
<? if (strlen($filename) == 0 ) { ?>
<?
if (strlen($msg) > 0) ///////////// Mostra mensagem de erro
	{ echo '<TR><TD align="center"><B><font color="red">'.$msg.'</font></TD></TR>'; }
?>
<!----- nova linha ---->
<TR valign="top"><TD align="right">
<form enctype="multipart/form-data" action="upload.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$limit;?>">
<TD rowspan="10" width="64" align="center"><img src="upload_icone.png" width="64" height="64" alt="" border="0">
<font style="font-size:10px;"><?=$upload_id;?><BR><?=$upload_ver;?></font>
</TD>

<!----- nova linha ---->
<TR><TD ><font class="lt1i"><?=$info;?></TD>
<TR><TD>&nbsp;</TD></TR>

<!----- nova linha ---->
<TR><TD>Formatos válidos :<B>
<?
for ($r=0 ; $r < count($fld);$r++)
	{
	if ($r > 0) { echo ', '; }
	echo '.'.$fld[$r];
	}
?>
</B>
</TD></TR>

<!----- nova linha ---->
<TR><TD><font color="#ff8040">Tamanho máximo por arquivo:<B><?=$limit_msk;?> <?=$limit_unidade;?></B> byte<?=$limit_u;?></font></TD></TR>

<!----- nova linha ---->
<TR><TD>&nbsp;</TD></TR>
<TR valign="top"><TD align="left"><?=$txt1;?><BR>
<input name="userfile" type="file" class="lt2">
&nbsp;<input type="submit" value="e n v i a r" class="lt2" <?=$estilo?>>
<input type="hidden" name="dd0" value="<?=$dd[0]?>">
<input type="hidden" name="dd1" value="<?=$dd[1]?>">
<input type="hidden" name="dd2" value="<?=$dd[2]?>">
<input type="hidden" name="dd3" value="<?=$dd[3]?>">
<input type="hidden" name="dd9" value="<?=$dd[9]?>">
<input type="hidden" name="dd10" value="<?=$dd[10]?>">
</form>
</TD>
<TR><TD><BR><BR></TD></TR>
<TR><TD>Controle mês <B><?=sn($controle_mes);?> (<?=$tp;?>)</B></TD></TR>
</TABLE>
<? 
} else {
	echo 'ola';
///////////////////////////////////////// Checa Diretórios
	$filenamex .= strtolower($filename);
	$file = strzero($dd[0],7);
	$ver = 1;
	$filenamex = troca($filenamex,' ','_');

	if ($controle_mes == 1)
		{
			$upload_dir_compl = date("Y");
			checadiretorio($upload_dir.$upload_dir_compl);
			$upload_dir_compl .= '/'.date("m");
			checadiretorio($upload_dir.$upload_dir_compl);
			$uploadfile = $upload_dir.$upload_dir_compl.'/';
			
			while (file_exists($uploadfile.$file.'_'.strzero($ver,2).'_'.substr(md5($secu.$file.$ver.date("Ymd")),2,7).'.'.$ext))
				{ $ver++; }
			$filenamey = $file.'_'.strzero($ver,2).'_'.substr(md5($secu.$file.$ver.date("Ymd")),2,7).'.'.$ext;
		} else {
			$upload_dir_compl = '';
			$uploadfile = $upload_dir;
			if (strlen($dd[0]) > 0) { $filenamey=$dd[0].'.'.$ext; } 
			else { $filenamey = $filename; }
		}
	/////////// se o nome do arquivos é maior que 250 caracteres
	if (strlen($filenamey) > 250)
		{  $filenamey = troca(substr($filenamey,0,250),'.','_').'.'.$ext; }
	$uploadfile .= $filenamey;

	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
		{
		    echo  '<TR><TD align="center" class="lt2"><BR><BR><TT><CENTER><FONT COLOR=GREEN >Arquivo salvo com sucesso !</center>';
			echo '<div align="left">';
			echo "<BR><BR>Arquivo: <B>".$filenamex.'</B>';
			echo "<BR>Tamanho: <B>".number_format($size/1024,1)."k</B> Bytes";
			echo "<BR>Extensão: <B>.".$ext."";
			echo '<center><BR><BR><A HREF="upload_close.php">[Fechar]</A>';
			echo '</div>';
			if (strlen($acesso) == 0) { $acesso = '1'; }
			/////////////////////////////////////////// Gravar Registro
			if (strlen($tabela_ged) > 0)
				{
				$sql = "insert into ".$tabela_ged." (";
				$sql .= "pl_type,pl_filename,pl_texto, ";
				$sql .= "pl_texto_sql,pl_size,pl_data, ";
				$sql .= "pl_hora,pl_versao,pl_acesso, ";
				$sql .= "pl_codigo,pl_tp_doc,pl_tp_projeto, ";
				$sql .= "pl_post,user_id,pl_ativo";
				$sql .= ") values (";
				$sql .= "'".$ext."','".$filenamex."','".$uploadfile."', ";
				$sql .= "'',0".$size.",'".date("Ymd")."', ";
				$sql .= "'".date("H:i")."','".$ver."','".$acesso."', ";
				$sql .= "'".substr(strzero($dd[0],7),0,7)."','".$dd[1]."','".substr($dd[2],0,7)."', ";
				$sql .= "'".substr($dd[3],0,7)."',0".sonumero($user_id).",1 ";
				$sql .= ");";
				$rlt = db_query($sql);
				}
			if (strlen($updatex) > 0) { require($updatex); }
		} else {
		    print "<CENTER><FONT COLOR=RED>ERRO EM SALVAR O ARQUIVO";
			print "<BR>->".$xfilename;
		}
}

function sn($y)
	{
	$yy = "NÃO";
	if ($y=='1') { $yy = 'SIM'; }
	if ($y=='S') { $yy = 'SIM'; }
	return($yy);
	}

function checadiretorio($vdir)
	{
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
			$ss = $ss . '<META HTTP-EQUIV="Refresh" CONTENT="3;URL='.site.'">';
			$rst = $rst . '*';
			fwrite($ourFileHandle, $ss);
			fclose($ourFileHandle);		
		}
//		echo '<BR>'.$vdir.' '.$rst;
		return($rst);
	}
	
/*
CREATE TABLE sis_ged_files
( 
id_pl serial NOT NULL, 
pl_type char(3) DEFAULT 'TXT'::bpchar, 
pl_filename char(255), 
pl_texto text, 
pl_texto_sql text, 
pl_size int8, 
pl_data int8, 
pl_hora char(5), 
pl_versao int8 DEFAULT 1, 
pl_acesso int8 DEFAULT 0, 
pl_codigo char(7), 
pl_tp_doc char(7), 
pl_tp_projeto char(7), 
pl_post char(7), 
pl_ativo char(1) DEFAULT '1', 
user_id int8 
); 

ALTER TABLE sis_ged_files ADD CONSTRAINT id_pl PRIMARY KEY(id_pl);
*/
?>
