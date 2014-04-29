<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('revendedoras_acertos.php','Revendedoras (acertos)'));

$include = '../';
require($include."cab_novo.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_data.php");

require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");

require("db_temp.php");
$loja = 'S';


$tabela = "";
$cp = array();
array_push($cp,array('$H4','','',False,True,''));
array_push($cp,array('$A','','Balancete de Acertos pela Loja',False,True,''));
array_push($cp,array('$D8','','Data ',True,True,''));
array_push($cp,array('$D8','','até',True,True,''));
if (strlen($dd[2]) ==0) { $dd[2] = date("d/m/Y"); }
if (strlen($dd[3]) ==0) { $dd[3] = date("d/m/Y"); }
/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<center><TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';
	
if ($saved < 1) {  exit; }	

$tabela = "kits_historico";
$sql = "select * from kits_consignado ";
$sql .= " left join clientes on kh_cliente = cl_cliente ";
$sql .= "where kh_status = 'B' ";
$sql .= "and (kh_previsao >= ".brtos($dd[2]).' and kh_previsao <= '.brtos($dd[3]).')';
$sql .= " order by kh_previsao ";
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
	
	$prev = $line['kh_previsao'];
	$sa .= '<TR '.coluna().'>';
	$sa .= '<TD align="right">';
	$sa .= $tot1.'.';
	$sa .= '</TD>';
	

	$sa .= '<TD align="center">';
	$sa .= stodbr($line['kh_fornecimento']);
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= stodbr($line['kh_acerto']);
	$sa .= '</TD>';

	$ddias = DiffDataDias($line['kh_fornecimento'],$line['kh_acerto']);
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
	$sa .= '/';
	$sa .= number_format($line['kh_pc_vend'],0);
	$sa .= '</TD>';

	$sa .= '<TD align="right">';
	$sa .= number_format($line['kh_vlr_forn'],2);
	$sa .= '<TD align="right"><b>';
	$sa .= number_format($line['kh_vlr_vend'],2);
	$sa .= '</TD>';

		$v1 = $line['kh_vlr_forn'];
		$v2 = $line['kh_vlr_vend'];
		$vp = 0;
		if ($v1 > 0) { $vp = $v2/$v1*100; }
	$sa .= '<TD align="center">';
	$sa .= number_format($vp,1).'%';
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
echo '<TH>Peças vendidas</TH>';
echo '<TH>% vendas peças</TH>';
echo '<TH>Valor fornecido</TH>';
echo '<TH>Valor faturado</TH>';
echo '<TH>Valor liquido faturado</TH>';
echo '<TH>% vendas valor</TH>';
echo '<TH>média vendas</TH>';
echo '<TH>média dias</TH>';
echo '<TH>média/vr.pago</TH>';
echo '</TR>';
echo '<TR class="lt1">';
echo '<TD align="center">'.$tot1.'</TD>';
echo '<TD align="center">'.number_format($tot2,0).'</TD>';
echo '<TD align="center">'.number_format($tot3,0).'</TD>';
echo '<TD align="center">'.number_format($tot3/$tot2*100,1).'%'.'</TD>';
echo '<TD align="center">'.number_format($tot4,2).'</TD>';
echo '<TD align="center">'.number_format($tot5,2).'</TD>';
echo '<TD align="center">'.number_format($tot7,2).'</TD>';
echo '<TD align="center">'.number_format($tot5/$tot4*100,1).'%'.'</TD>';
echo '<TD align="center">'.number_format($tot5/$tot1,2).'</TD>';
echo '<TD align="center">'.number_format($tot6/$tot1,2).'</TD>';
echo '<TD align="center">'.number_format($tot7 / $tot1,2).'</TD>';
echo '</TR>';
echo '<TR><TD colspan="11">* Valores BRUTO</TD></TR>';
echo '</table>';

$sql = "update ind_kits set i_ativo = 0 where i_tipo = '00001' and i_ativo=1 and i_data = ".date("Ymd").'; ';
$rlt = db_query($sql);

$sql .= "insert into ind_kits ";
$sql .= "(i_data,i_hora,i_log,i_tipo,i_loja,i_ativo,";
$sql .= "i_v1,i_v2,i_v3,";
$sql .= "i_v4,i_v5,i_v6,";
$sql .= "i_v7,i_v8,i_v9";
$sql .= ") values (";
$sql .= "'".date("Ymd")."','".date("H:i")."','".substr($user_log,0,10)."','00001','".$loja."',1,";
$sql .= $tot1.",".$tot2.",".$tot3.",";
$sql .= $tot4.",".$tot5.",".$tot6.",";
$sql .= $tot7.",".$tot8.",".$tot9.")";
$rlt = db_query($sql);

echo '<TABLE width="'.$tab_max.'" align="center" class="lt1">';
echo '<TR valign="bottom">';
echo '<TH>pos.</TH>';
echo '<TH>data<BR>fornecimento</TH>';
echo '<TH>data<BR>acerto</TH>';
echo '<TH>dias</TH>';
echo '<TH>código</TH>';
echo '<TH>nome</TH>';
echo '<TH>equipe</TH>';

echo '<TH>peças</TH>';
echo '<TH>vlr.forn.</TH>';
echo '<TH>vlr.vend.</TH>';
echo '<TH>vendas</TH>';
echo '</TR>';
echo $sa;
echo '</table>';
?>