<?
$include = '../';
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/coordenadoras/index.php','Coordenadora'));
array_push($breadcrumbs, array('/fonzaghi/coordenadoras/cliente.php','Clientes'));
array_push($breadcrumbs, array('/fonzaghi/coordenadoras/cliente_ver.php','Dados do Cliente'));

require($include."cab.php");
require($include."sisdoc_data.php");
require($include."sisdoc_grafico.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_tips.php");

require("../_classes/_class_estoque_perfil.php");
$estoq = new perfil_vendas;

require("../_classes/_class_consultora.php");
$cq = new consultora;

//require("../db_fghi_210.php");
require("../db_fghi_206_cadastro.php");

$sql = "select * from cadastro where id_cl = 0".sonumero($dd[0]);

if (strlen($dd[1]) == 7)
	{
	$sql = "select * from cadastro where cl_cliente = '".$dd[1]."' ";
	}
$rlt = db_query($sql);

require("cliente_ver_dados_basicos.php");
/*** Recupera Classe */
$dd[1] = $cod_clie;
$cliente = $dd[1];

///////////////////////// CONSIGNAï¿½OES
require("../db_fghi_210.php");

$lm = array(0,0,0,0,0,0,0,0,0,0,0);
$vm = array(0,0,0,0,0,0,0,0,0,0,0);
$sql = "select * from clientes_pecas where cp_cliente = '".$cliente."' ";
$rlt = db_query($sql);
$dtm = DateAdd("m",-1,date("Ymd"));

if ($line = db_read($rlt))
	{
	$lm[0] = $line['cp_loja_1'];
	$lm[1] = $line['cp_loja_2'];
	$lm[2] = $line['cp_loja_3'];
	$lm[3] = $line['cp_loja_4'];
	$lm[4] = $line['cp_loja_5'];
	$lm[5] = $line['cp_loja_6'];
	$lm[6] = $line['cp_loja_7'];
	
	$lm[0] = $line['cp_vlr_1'];
	$lm[1] = $line['cp_vlr_2'];
	$lm[2] = $line['cp_vlr_3'];
	$lm[3] = $line['cp_vlr_4'];
	$lm[4] = $line['cp_vlr_5'];
	$lm[5] = $line['cp_vlr_6'];
	$lm[6] = $line['cp_vlr_7'];	
	
	if ($line['cp_lastupdate_1'] > $dtm) { $dtm = 0; }
	if ($line['cp_lastupdate_2'] > $dtm) { $dtm = 0; }
	if ($line['cp_lastupdate_3'] > $dtm) { $dtm = 0; }
	if ($line['cp_lastupdate_4'] > $dtm) { $dtm = 0; }
	if ($line['cp_lastupdate_5'] > $dtm) { $dtm = 0; }
	if ($line['cp_lastupdate_6'] > $dtm) { $dtm = 0; }
	if ($line['cp_lastupdate_7'] > $dtm) { $dtm = 0; }
	}

//if ($dtm != 0) 
	{
		$file = '../TST/cliente_vendas_medias_pecas.php';
		require($file); 
	}
	
$ano = date("Y");
$mes = date("m")-1;
if ($mes == 0) { $mes = 12; $ano--; }
$mes = strzero($mes,2);

///////////////////////////////////////////////// Busca peças sensual
require("../db_fghi_206_sensual.php");
$sql = "select *,'S' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[4] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[4] .= $link.stodbr($prev).'</A>';
		} else {
			$ac[4] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[4] .= $link.stodbr($prev).'</A>';
		}
	}
///////////////////////////////////////////////// Busca peças ï¿½culos
require("../db_fghi_206_oculos.php");
$sql = "select *,'O' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[2] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[2] = $link.stodbr($prev).'</A>';
		} else {
			$ac[2] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[2] = $link.stodbr($prev).'</A>';
		}
	}	

///////////////////////////////////////////////// Busca peças Catï¿½logo
require("../db_fghi_206_ub.php");
$sql = "select *,'U' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";

$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[3] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[3] = $link.stodbr($prev).'</A>';
		} else {
			$ac[3] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[3] = $link.stodbr($prev).'</A>';
		}
	}
	
///////////////////////////////////////////////// Busca peças ï¿½culos
require("../db_fghi_206_modas.php");
$sql = "select *,'M' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[1] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[1] = $link.stodbr($prev).'</A>';
		} else {
			$ac[1] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[1] = $link.stodbr($prev).'</A>';
		}
	}	

///////////////////////////////////////////////// Busca peças Joias
require("../db_fghi_206_joias.php");
$sql = "select *,'J' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[0] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[0] = $link.stodbr($prev).'</A>';
		} else {
			$ac[0] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[0] = $link.stodbr($prev).'</A>';
		}
	}	

///////////////////////////////////////////////// Busca peças Modsa Express
require("../db_fghi_206_express.php");
$sql = "select *,'E' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[5] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[5] = $link.stodbr($prev).'</A>';
		} else {
			$ac[5] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[5] = $link.stodbr($prev).'</A>';
		}
	}
	
///////////////////////////////////////////////// Busca peças Joias Express
require("../db_fghi_206_express_joias.php");
$sql = "select *,'G' as lj from kits_consignado where kh_cliente = '".$cod_clie."' ";
$sql .= " and kh_status = 'A' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
		$prev = $line['kh_previsao'];
		$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_alterar_data.php?dd2='.$cod_clie.'&dd1='.$line['lj'].chr(39).',400,450);">';
		if ($prev > date("Ymd"))
		{
			$ac[6] ='<font color=blue>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[6] = $link.stodbr($prev).'</A>';
		} else {
			$ac[6] ='<font color=red>Ativo ('.$line['kh_pc_forn'].' peças)'; 
			$ad[6] = $link.stodbr($prev).'</A>';
		}
	}

$ljs = array('J','M','O','U','S','E','G');
for ($rq=0;$rq < count($lm);$rq++)
	{
	$link = '<A HREF="" onclick="newxy2('.chr(39).'cliente_acerto_pecas_numero.php?dd2='.$cod_clie.'&dd1='.$ljs[$rq].chr(39).',400,450);">';
	$lm[$rq] = $link.$lm[$rq];
	
	}

require("cliente_email.php");
echo $email;
?>

<TABLE width="<?=$tab_max;?>" align="center" border="0">
<TR class="lt0" align="center">
<TD width="20%"><fieldset><legend>Jóias</legend><center><font class="lt2"><?=$ac[0];?><BR>&nbsp;<?=$ad[0];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[0];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
<TD width="20%"><fieldset><legend>Modas</legend><center><font class="lt2"><?=$ac[1];?><BR>&nbsp;<?=$ad[1];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[1];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
<TD width="20%"><fieldset><legend>Óculos</legend><center><font class="lt2"><?=$ac[2];?><BR>&nbsp;<?=$ad[2];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[2];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
<TD width="20%"><fieldset><legend>Catálogo</legend><center><font class="lt2"><?=$ac[3];?><BR>&nbsp;<?=$ad[3];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[3];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
<TD width="20%"><fieldset><legend>Sensual</legend><center><font class="lt2"><?=$ac[4];?><BR>&nbsp;<?=$ad[4];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[4];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
</TR>
<TR class="lt0" align="center">
<TD width="20%"><fieldset><legend>Express Modas</legend><center><font class="lt2"><?=$ac[5];?><BR>&nbsp;<?=$ad[5];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[5];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
<TD width="20%"><fieldset><legend>Express Jóias</legend><center><font class="lt2"><?=$ac[6];?><BR>&nbsp;<?=$ad[6];?>&nbsp;<BR><fonts class="lt0">limite&nbsp;<?=$lm[6];?>&nbsp;peças(s)<BR>R$ <?=$vm[0];?></center></TD>
</TABLE>
<?
$_cliente=$cod_clie;
$_loja=$loja;
require("cliente_vendas_loja.php");
?>
<TABLE width="<?=$tab_max;?>" align="center" border="0">
<TR class="lt0" align="center">
<TD width="20%"><fieldset><legend>Jóias</legend><center><?$loja='J'; require("../db_fghi_206_joias.php"); loja_grafico($_cliente); ?></center></TD>
<TD width="20%"><fieldset><legend>Modas</legend><center><?$loja='M'; require("../db_fghi_206_modas.php"); loja_grafico($_cliente); ?></center></TD>
<TD width="20%"><fieldset><legend>Óculos</legend><center><?$loja='O'; require("../db_fghi_206_oculos.php"); loja_grafico($_cliente); ?></center></TD>
<TD width="20%"><fieldset><legend>Catálogo</legend><center><?$loja='U'; require("../db_fghi_206_ub.php"); loja_grafico($_cliente); ?></center></TD>
<TD width="20%"><fieldset><legend>Sensual</legend><center><?$loja='S'; require("../db_fghi_206_sensual.php"); loja_grafico($_cliente); ?></center></TD>
</TR>

<TR class="lt0" align="center">
<TD width="20%"><fieldset><legend>Jóias Express</legend><center><?$loja='G'; require("../db_fghi_206_express_joias.php"); loja_grafico($_cliente); ?></center></TD>
<TD width="20%"><fieldset><legend>Modas Express</legend><center><?$loja='E'; require("../db_fghi_206_express.php"); loja_grafico($_cliente); ?></center></TD>
</TABLE>

<?	
$lojas = array();
array_push($lojas,array('Joias','duplicata_joias',''));
array_push($lojas,array('Modas','duplicata_modas',''));
array_push($lojas,array('Oculos','duplicata_oculos',''));
array_push($lojas,array('UseBrilhe','duplicata_usebrilhe',''));
array_push($lojas,array('Sensual','duplicata_sensual',''));
array_push($lojas,array('Juridico','juridico_duplicata',''));

//array_push($lojas,array('Sensual','joias_duplicata'));
require("../db_fghi_210.php");
$s = '';
$div = round(100 / (count($lojas)+1));
for ($r=0;$r < count($lojas);$r++)
	{
	$vlr1 = 0;
	$vlr2 = 0;
	$vlr3 = 0;
	
	$sql = "select sum(dp_valor) as total from ".$lojas[$r][1];
	$sql .= " where dp_cliente = '".$cod_clie."' and ( dp_status = '@' or dp_status = 'A') and dp_venc >= ".date("Ymd");
	$xrlt = db_query($sql);
	if ($xline = db_read($xrlt))
		{ $vlr1 = $xline['total']; }

	$sql = "select sum(dp_valor) as total from ".$lojas[$r][1];
	$sql .= " where dp_cliente = '".$cod_clie."' and ( dp_status = '@' or dp_status = 'A') and dp_venc < ".date("Ymd");
	$xrlt = db_query($sql);
//	echo '<HR>'.$sql;
	if ($xline = db_read($xrlt))
		{ $vlr2 = $xline['total']; }
//	echo '--->'.$vlr2;	
	$f1 = '<font class="lt1">'; $f2 = '<font class="lt1">'; $f3 = '<font class="lt1">';
	if ($vlr1 > 0) { $f1 = '<font color="GREEN">'; }
	if ($vlr2 > 0) { $f2 = '<font color="RED">'; }
	if ($vlr3 > 0) { $f3 = '<font color="BLUE">'; }
	$sx = '<font class="lt0"><fieldset><legend>';
	$sx .= $lojas[$r][0];
	$sx .= '</legend></font>';
	//$sx .= '<legend class="lt0" style="text-align=right;">'.$f1.'Notas abertas<BR><B>R$ '.number_format($vlr1,2).'</font></B></legend>';
	$sx .= '<font class="lt1">'.$f1.'Notas abertas<BR><B>R$ '.number_format($vlr1,2).'<br></B></font>';
	
	//$sx .= '<legend class="lt0" style="text-align=right;">'.$f2.'Notas atrasadas<BR><B>R$ '.number_format($vlr2,2).'</font></B></legend>';
	$sx .= '<font class="lt1">'.$f2.'Notas atrasadas<BR><B>R$ '.number_format($vlr2,2).'</B></font>';

	$sx .= '</fieldset>';

	$s .= '<TD width="'.$div.'%">'.$sx.'</TD>';
	}
?>
<TABLE width="<?=$tab_max;?>" align="center" border="0">
<TR><?=$s;?></TR>
</TABLE>
<?

/* Perfil de vendas estoque modas */
echo '<table border=0>';
echo '<TR><TD>';
require("../db_fghi_206_modas.php");
echo $estoq->gerar_perfil_venda($cliente);
echo '<TD>';
echo $estoq->gerar_perfil_fornecidos($cliente);
echo '</table>';
require("cliente_caixa.php");

$s = '';
$sx = '';
///////////////////////////////////////////////////////////////////////////

//require("cliente_ver_modas.php");

require("cliente_indicacao_calcular.php");
//exit;
//echo cliente_indicacao($cliente);

///////////////////////////////////
require("../cobranca/notas_resumo.php");



echo $hd->foot();
echo $scr;
///////////////////////////////////////////////////////////////////////////
exit;
///////////////////////// JOIAS
$table = "joias";
$dti = date("Y")-1;
$dti = $dti.'-'.date("m").'-01';
$sql = "select dp_data,sum(dp_valor) as valor from (";
$sql .= "select dp_valor,dp_data from ".$table."_duplicata where dp_cliente = '".$cod_clie."' and dp_valor > 0 and dp_data >= '".$dti."' ";
$sql .= " union ";
$sql .= "select dp_valor,dp_data from ".$table."_duplicata_a where dp_cliente = '".$cod_clie."' and dp_data >= '".$dti."' ";
$sql .= ") as resultado ";
$sql .= " group by dp_data ";
$sql .= " order by dp_data ";
$sql .= " limit 15 ";
$rlt = db_query($sql);

$mi = -1;
$mesini=date("Ym");
$mesatu=date("Ym");
$max = 200;
$vv = array();
$t1 = 0;
$t2 = 0;
while ($line = db_read($rlt))
	{
	$mes = substr($line['dp_data'],0,4).substr($line['dp_data'],5,2);
	if ($mes < $mesini) 
		{ $mesini = $mes; $mesatu = $mes; }
	$ma1 = intval(substr($mesatu,4,2));
	$ma2 = intval(substr($mesatu,0,4));
	$mb1 = intval(substr($mes,4,2));
	$mb2 = intval(substr($mes,0,4));
	$mm = 0;
	$t1++;
	$t2 = $t2 + $line['valor'];
	while (($ma1 < $mb1) or ($ma2 < $mb2))
		{
		if ($mm > 0)
			{ array_push($vv,array(0.00,nomemes_short($ma1))); }
		$ma1++;
		$mm++;
		if ($ma1 > 12) { $ma1 = 1; $ma2++; }
		}
	$mesatu = $mes;	
	array_push($vv,array($line['valor'],nomemes_short(intval(substr($mes,4,2))).'<BR>'.substr($mes,0,4)));		
	}
	if ($t1 > 0)
		{
		echo gr_barras($vv,'Vendas em Jï¿½ias (acerto / mes) - mï¿½dia '.Number_format($t2/($t1),2).' ('.$t1.' <=> '.$t2.')',200);
		}
	
///////////////////////// MODAS
$table = "modas";
$dti = date("Y")-1;
$dti = $dti.'-'.date("m").'-01';
$sql = "select dp_data,sum(dp_valor) as valor from (";
$sql .= "select dp_data,dp_valor from ".$table."_duplicata where dp_cliente = '".$cod_clie."' and dp_valor > 0 and dp_data >= '".$dti."' ";
$sql .= " union ";
$sql .= "select dp_data,dp_valor from ".$table."_duplicata_a where dp_cliente = '".$cod_clie."' and dp_data >= '".$dti."' ";
$sql .= ") as resultado ";
$sql .= " group by dp_data ";
$sql .= " order by dp_data ";
$sql .= " limit 15 ";
$rlt = db_query($sql);

$mi = -1;
$mesini=date("Ym");
$mesatu=date("Ym");
$max = 200;
$vv = array();
$t1 = 0.01;
$t2 = 0.01;
while ($line = db_read($rlt))
	{
	$mes = substr($line['dp_data'],0,4).substr($line['dp_data'],5,2);
	if ($mes < $mesini) 
		{ $mesini = $mes; $mesatu = $mes; }
	$ma1 = intval(substr($mesatu,4,2));
	$ma2 = intval(substr($mesatu,0,4));
	$mb1 = intval(substr($mes,4,2));
	$mb2 = intval(substr($mes,0,4));
	$mm = 0;
	$t1++;
	$t2 = $t2 + $line['valor'];	
	while (($ma1 < $mb1) or ($ma2 < $mb2))
		{
		if ($mm > 0)
			{ array_push($vv,array(0.00,nomemes_short($ma1))); }
		$ma1++;
		$mm++;
		if ($ma1 > 12) { $ma1 = 1; $ma2++; }
		}
	$mesatu = $mes;	
	array_push($vv,array($line['valor'],nomemes_short(intval(substr($mes,4,2))).'<BR>'.substr($mes,0,4)));		
	}
	echo gr_barras($vv,'Vendas em Modas (acerto / mes) - mï¿½dia '.Number_format($t2/($t1),2).' ('.$t1.' <=> '.$t2.')',200);
	
?>

