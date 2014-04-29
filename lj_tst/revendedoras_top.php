<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('revendedoras_top.php','TOP´s'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_data.php");


require($include."sisdoc_form2.php");
require($include."cp2_gravar.php");

require("db_temp.php");
$loja = $nloja;
$tabela = "";
$cp = array();
array_push($cp,array('$H4','','',False,True,''));
array_push($cp,array('$A','','TOP´s Revendedoras de ',False,True,''));
array_push($cp,array('$D8','','Data ',True,True,''));
array_push($cp,array('$D8','','até',True,True,''));
if (strlen($dd[2]) ==0) { $dd[2] = date("d/m/Y"); }
if (strlen($dd[3]) ==0) { $dd[3] = date("d/m/Y"); }
/// Gerado pelo sistem "base.php" versao 1.0.2
	echo '<h1>Kits consignados por acerto - '.$nloja_nome.'</h1>';
	echo '<center><TABLE width="'.$tab_max.'">';
	echo '<TR><TD>';
		editar();
	echo '</TABLE>';
	
if ($saved < 1) {  exit; }	

$tabela = "kits_historico";
$sql = "select * from kits_consignado where kh_status = 'B' and kh_pc_forn > 0 ";
$sql .= "and (kh_acerto >= ".brtos($dd[2]).' and kh_acerto <= '.brtos($dd[3]).')';
$sql .= " order by kh_vlr_vend desc ";
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
	$tot3=$tot3+$line['kh_vlr_forn'] * (1-$line['kh_comissao']/100);
	$tot4=$tot4+$line['kh_vlr_comissao'];
	$tot5=$tot5+$line['kh_pc_vend'];
	$tot6=$tot6+$line['kh_vlr_vend'] * (1-$line['kh_comissao']/100);
	
	$prev = $line['kh_acerto'];
	$sa .= '<TR '.coluna().'>';
	$sa .= '<TD align="right">';
	$sa .= $tot1.'.';
	$sa .= '</TD>';
	
	$sa .= '<TD align="center">';
	$sa .= stodbr($line['kh_previsao']);
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= stodbr($line['kh_fornecimento']);
	$sa .= '</TD>';


	$sa .= '<TD align="center">';
	$sa .= number_format($line['kh_pc_forn'],0);
	$sa .= '&nbsp;/&nbsp;';
	$sa .= number_format($line['kh_pc_vend'],0);
	$sa .= '</TD>';
	
	$sa .= '<TD align="center">';
	$sa .= number_format(100*($line['kh_pc_vend']/$line['kh_pc_forn']),1).'%';
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= number_format($line['kh_vlr_vend'] * (1-$line['kh_comissao']/100),2);
	$sa .= '&nbsp;/&nbsp;';
	$sa .= number_format($line['kh_vlr_forn'] * (1-$line['kh_comissao']/100),2);
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$vv1 = $line['kh_vlr_forn'];
	$vv2 = $line['kh_vlr_vend'];
	if ($vv1 > 0)
		{
			$vv3 = $vv2/$vv1;
			$sa .= number_format(100*($vv3),1).'%';
		} else {
			$sa .= ' - ';
		}
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= number_format($line['kh_vlr_comissao'],0).'%';
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= number_format($line['kh_pago'],2).'';
	$sa .= '</TD>';

	$sa .= '<TD align="center">';
	$sa .= trim($line['kh_cliente']);
	$sa .= '</TD>';

	$sa .= '</TR>';
	}
	
$tot9 = brtos($dd[3]);
$tot8 = brtos($dd[2]);
	
echo '<center><font class="lt5">TOP REVENDEDORAS / ACERTO</font><BR>';
echo $dd[2].' até '.$dd[3].'</center>';
echo '<TABLE width="'.$tab_max.'" align="center" class="lt2" border="1">';
echo '<TR align="center" class="lt0" valign="bottom">';
echo '<TH>Rev.ativas</TH>';
echo '<TH>Peças fornecidas</TH>';
echo '<TH>Peças vendidas</TH>';
echo '<TH>%<BR>vendas</TH>';
echo '<TH>Vendas peças <BR>por Cliente</TH>';
echo '<TH>Valor fornecido</TH>';
echo '<TH>Valor vendido</TH>';
echo '<TH>Valor vendido</TH>';
echo '<TH>Média acerto</TH>';
echo '<TH>Comissão Média</TH>';

echo '</TR>';
echo '<TR class="lt3">';
echo '<TD align="center">'.$tot1.'</TD>';
echo '<TD align="center">'.number_format($tot2,0).'</TD>';
echo '<TD align="center">'.number_format($tot5,0).'</TD>';
echo '<TD align="center">'.number_format($tot5/$tot2*100,1).'%</TD>';
echo '<TD align="center">'.number_format($tot5/$tot1,1).'</TD>';

echo '<TD align="center">'.number_format($tot3,2).'</TD>';
echo '<TD align="center">'.number_format($tot6,2).'</TD>';
echo '<TD align="center">'.number_format($tot6/$tot3*100,1).'%</TD>';
echo '<TD align="center">'.number_format($tot6/$tot1,2).'</TD>';

echo '<TD align="center">'.number_format($tot4/$tot1,0).'%'.'</TD>';
echo '</TR>';
echo '</table>';

$sql = "update ind_kits set i_ativo = 0 where i_tipo = '00002' and i_ativo=1 and i_data = ".date("Ymd").'; ';
$rlt = db_query($sql);

$sql .= "insert into ind_kits ";
$sql .= "(i_data,i_hora,i_log,i_tipo,i_loja,i_ativo,";
$sql .= "i_v1,i_v2,i_v3,";
$sql .= "i_v4,i_v5,i_v6,";
$sql .= "i_v7,i_v8,i_v9";
$sql .= ") values (";
$sql .= "'".date("Ymd")."','".date("H:i")."','".substr($userlog,0,10)."','00002','".$loja."',1,";
$sql .= number_format($tot1, 2, '.', '').",".number_format($tot2, 2, '.', '').",".number_format($tot3, 2, '.', '').",";
$sql .= number_format($tot4, 2, '.', '').",".number_format($tot5, 2, '.', '').",".number_format($tot6, 2, '.', '').",";
$sql .= number_format($tot7, 2, '.', '').",".$tot8.",".$tot9.")";
$rlt = db_query($sql);

echo '<TABLE width="'.$tab_max.'" align="center" class="lt1">';
echo '<TR valign="bottom">';
echo '<TH>pos.</TH>';
echo '<TH>data<BR>acerto</TH>';
echo '<TH>data<BR>fornecimento</TH>';
echo '<TH>peças</TH>';
echo '<TH>% venda</TH>';
echo '<TH>vlr.venda</TH>';
echo '<TH>% venda</TH>';
echo '<TH>comissão</TH>';
echo '<TH>Acerto R$</TH>';
echo '<TH>cliente</TH>';
echo '</TR>';
echo $sa;
echo '</table>';
?>