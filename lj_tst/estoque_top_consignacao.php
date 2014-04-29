<?

$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/revendedoras_acertos.php','Revendedoras (acertos)'));

$include = '../';
require($include."cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");

require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");

require("db_temp.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR></table>
<?

$tabela = "";
$cp = array();
array_push($cp,array('$H4','','',False,True,''));
array_push($cp,array('$A','','Top Estoque - Revendedoras',False,True,''));
array_push($cp,array('$D8','','Fornecimento de ',True,True,''));
array_push($cp,array('$D8','','até',True,True,''));
require('../coordenadoras/equipe_form.php');
array_push($cp,array('$O '.$sq,'','Equipe',False,True,''));

if (strlen($dd[2]) ==0) { $dd[2] = stodbr(DateAdd("m",-1,date("Ymd"))); }
if (strlen($dd[3]) ==0) { $dd[3] = date("d/m/Y"); }
/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';
	
if ($saved < 1) {  exit; }	

$sql = "select * from kits_consignado ";
$sql .= " left join clientes on kh_cliente = cl_cliente ";
$sql .= "where kh_status = 'A' ";
$sql .= "and (kh_fornecimento >= ".brtos($dd[2]).' and kh_fornecimento <= '.brtos($dd[3]).')';
if (strlen(trim($dd[4])) > 0)
	{ $sql .= " and cl_clientep = '".$dd[4]."' "; }
$sql .= " order by kh_pc_forn desc, kh_vlr_forn desc";
$rlt = db_query($sql);
$tot1=0;
$tot2=0;
$tot3=0;
$tot4=0;
$tot5=0;
$tot6=0;
$tot7=0;
$tot8=0;
$tot9=0;

while($line = db_read($rlt))
	{
	$tot1=$tot1+1;
	$tot2=$tot2+$line['kh_pc_forn'];
	$tot3=$tot3+$line['kh_pc_vend'];
	$tot4=$tot4+$line['kh_vlr_forn'];
	$tot5=$tot5+$line['kh_vlr_vend'];
	$tot7=$tot7+$line['kh_pago'];
	
	$prev = $line['kh_fornecimento'];
	
	$sa .= '<TR '.coluna().'>';
	$sa .= '<TD align="right">';
	$sa .= $tot1.'.';
	$sa .= '</TD>';
	

	$sa .= '<TD align="center">';
	$sa .= stodbr($line['kh_fornecimento']);
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= stodbr($line['kh_previsao']);
	$sa .= '</TD>';

	$ddias = DiffDataDias($line['kh_fornecimento'],$line['kh_previsao']);
	$sa .= '<TD align="center">'.$ddias.'</TD>';
	$tot6=$tot6+$ddias;

	$sa .= '<TD align="center">';
	$sa .= trim($line['kh_cliente']);
	$sa .= '</TD>';
	
	$sa .= '<TD align="left">';
	$sa .= substr($line['cl_nome'],0,25);
	$sa .= '</TD>';

	$sa .= '<TD align="left">';
	$sa .= $line['cl_clientep'];
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= number_format($line['kh_pc_forn'],0);
	$sa .= '</TD>';

	$sa .= '<TD align="right"><B>';
	$sa .= number_format($line['kh_vlr_forn'],2);
	$sa .= '</TD>';

	$sa .= '</TR>';
	}
$tot9 = brtos($dd[3]);
$tot8 = brtos($dd[2]);
echo '<H1>KITS CONSIGNADOS DE '.$dd[2].' até '.$dd[3].'</H1>';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2" border="1">';
echo '<TR align="center" class="lt0">';
echo '<TH>Acertos</TH>';
echo '<TH>Peças fornecidas</TH>';
//echo '<TH>Peças vendidas</TH>';
//echo '<TH>% vendas peças</TH>';
echo '<TH>Valor fornecido</TH>';
//echo '<TH>Valor faturado</TH>';
//echo '<TH>Valor liquido faturado</TH>';
//echo '<TH>% vendas valor</TH>';
//echo '<TH>média vendas</TH>';
//echo '<TH>média dias</TH>';
echo '<TH>média R$ por peças</TH>';
echo '<TH>média peça (qt)/cliente</TH>';
echo '<TH>média/cliente</TH>';
echo '</TR>';
echo '<TR class="lt1">';
echo '<TD align="center">'.$tot1.'</TD>';
echo '<TD align="center">'.number_format($tot2,0).'</TD>';
//echo '<TD align="center">'.number_format($tot3,0).'</TD>';
//echo '<TD align="center">'.number_format($tot3/$tot2*100,1).'%'.'</TD>';
echo '<TD align="center">'.number_format($tot4,2).'</TD>';
//echo '<TD align="center">'.number_format($tot5,2).'</TD>';
//echo '<TD align="center">'.number_format($tot7,2).'</TD>';
//echo '<TD align="center">'.number_format($tot5/$tot4*100,1).'%'.'</TD>';
//echo '<TD align="center">'.number_format($tot5/$tot1,2).'</TD>';
//echo '<TD align="center">'.number_format($tot6/$tot1,2).'</TD>';
echo '<TD align="center">';
if ($tot2 > 0) { echo number_format($tot4 / $tot2,2).'</TD>'; }
echo '<TD align="center">';
if ($tot1 > 0) { echo number_format($tot2 / $tot1,2).'</TD>'; }
echo '<TD align="center">';
if ($tot1 > 0) { echo number_format($tot4 / $tot1,2).'</TD>'; }
echo '</TR>';
echo '<TR><TD colspan="11">* Valores BRUTO</TD></TR>';
echo '</table>';

$sql = "update ind_kits set i_ativo = 0 where i_tipo = '00003' and i_ativo=1 and i_data = ".date("Ymd").'; ';
$rlt = db_query($sql);

$sql .= "insert into ind_kits ";
$sql .= "(i_data,i_hora,i_log,i_tipo,i_loja,i_ativo,";
$sql .= "i_v1,i_v2,i_v3,";
$sql .= "i_v4,i_v5,i_v6,";
$sql .= "i_v7,i_v8,i_v9";
$sql .= ") values (";
$sql .= "'".date("Ymd")."','".date("H:i")."','".substr($user_log,0,10)."','00003','".$loja."',1,";
$sql .= (round(100*$tot1)/100).",".(round(100*$tot2)/100).",".(round(100*$tot3)/100).",";
$sql .= (round(100*$tot4)/100).",".(round(100*$tot5)/100).",".(round(100*$tot6)/100).",";
$sql .= (round(100*$tot7)/100).",".(round(100*$tot8)/100).",".(round(100*$tot9p)/100).")";
$rlt = db_query($sql);

echo '<TABLE width="'.$tab_max.'" align="center" class="lt1">';
echo '<TR valign="bottom">';
echo '<TH>pos.</TH>';
echo '<TH>data<BR>fornecimento</TH>';
echo '<TH>previsao<BR>acerto</TH>';
echo '<TH>dias</TH>';
echo '<TH>código</TH>';
echo '<TH>nome</TH>';
echo '<TH>equipe</TH>';

echo '<TH>peças</TH>';
echo '<TH>vlr.forn.</TH>';
echo '</TR>';
echo $sa;
echo '</table>';

require("../foot.php");
?>