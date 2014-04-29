<?php
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require("../_classes/_class_produto.php");
$pt = new produto;

require("db_temp.php");
?>
<table width="<?=$tab_max;?>">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?php
$cp = array();
array_push($cp,array('$H8','','',True,True,''));
array_push($cp,array('$I8','','Número do mostruario',True,True,''));
array_push($cp,array('$O : &S:SIM, zerar mostruario','','Confirmação',True,True,''));

echo '<CENTER><font class="lt5">Zerar peças do mostruario</font></CENTER>';
echo '<TABLE align="center" width="'.$tab_max.'">';

echo '<TR><TD>';
editar();
echo '</TABLE>';	

if ($saved > 0)
	{
		require("db_temp.php");
		$sql = "select * from kits_consignado where kh_kits = '".strzero($dd[1],5)."' and (kh_status = '@' or kh_status = 'A') ";
		$rlt = db_query($sql);
		$ok = 1;
		if ($line = db_read($rlt))
			{
				$cliente = trim($line['kh_cliente']);
				$status = trim($line['kh_status']);
				if ((strlen($cliente) == 0) and ($status == '@'))
					{
						$sql = "delete from produto_consignado where pe_mostruario = '".strzero($dd[1],5)."' ";
						$rlt = db_query($sql);
												
						$sql = "update kits_consignado set kh_cliente = '', kh_status = '@', kh_dif = 1 where kh_kits = '".strzero($dd[1],5)."' and (kh_status = 'A' or kh_status = '@') ";
						$rlt = db_query($sql);	
					
						echo '<CENTER>Acerto Zerado com Sucesso!</center>';
					} else {
						$sql = "delete from produto_consignado where pe_mostruario = '".strzero($dd[1],5)."' ";
						$rlt = db_query($sql);
												
						$sql = "update kits_consignado set kh_cliente = '', kh_status = '@', kh_dif = 1 where kh_kits = '".strzero($dd[1],5)."' and (kh_status = 'A' or kh_status = '@') ";
						$rlt = db_query($sql);	
											
						echo 'Mostruario fornecido para '.$cliente.', porém zerado com sucesso!';
					}
			} else {
				$sql = "insert into kits_consignado 
					(kh_data,kh_fornecimento,kh_previsao,
					kh_acerto, kh_log, kh_pc_forn, 
					kh_pc_vend, kh_vlr_forn, kh_vlr_vend,
					kh_vlr_comissao, kh_vlr_comissao_repre, kh_kits,
					kh_cliente, kh_comissao, kh_pago,
					kh_status, kh_dif, kh_nr_consignacao,
					kh_log_acerto, kh_monta)
					values 
					(".date("Ymd").",19000101,19000101,
					19000101,'',0,
					0,0,0,
					0,0,'".strzero($dd[1],5)."',
					'',0,0,
					'@',0,'',
					0,0
					)";
					$rlt = db_query($sql);
					echo '<CENTER>Acerto Zerado com Sucesso!</center>';
			}

	}

require("../foot.php");	?>