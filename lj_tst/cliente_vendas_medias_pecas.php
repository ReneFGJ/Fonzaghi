<?
if (strlen($cliente) == 0) { exit; }
$vlr1 = 0;
$vlr2 = 0;
$vlr3 = 0;
$vlr4 = 0;
$vlr5 = 0;
$vlr6 = 0;
$vlr7 = 0;
$vlr8 = 0;
$vlr9 = 0;

$media_m = 40;
$media_o = 20;
if ($dtm != 0)
{
	$data = DateAdd('m',-3,date("Ymd"));
	$data = substr($data,0,6).'01';
	$loja = 'M';

/* MODAS */
if ($loja == 'M')
	{
	require("../db_fghi_206_modas.php");
	$psql = "select round(sum(kh_pago)) as media , count(*) as acertos  from kits_consignado ";
	$psql .= "where kh_cliente = '".$cliente."' and kh_pc_forn > 0 and kh_data >= ".$data;
	$prlt = db_query($psql);
	
	if ($pline = db_read($prlt)) { $media = round('0'.$prlt['media']); }
	
			///////////////////////////// MODAS
			//	até 100				 40
			//	até 200			 	 50
			//	até 400				 70
			//	até 800				120
			//	acima 800			170
			//////////////////////////////////////////////////////////// TABELA MODAS
			
	$media = round('0'.$pline['media']);
	if ($media < 100*3) 						{ $limi = 40; }
	if (($media >= 100*3) and ($media < 200*3)) { $limi = 55; }
	if (($media >= 200*3) and ($media < 400*3)) { $limi = 70; }
	if (($media >= 400*3) and ($media < 800*3)) { $limi = 85; }
	if ($media >= 800*3) 						{ $limi = 120; }
	$media_m = $limi;
	}

/////////////////////////////////////////////////////////////// OCULOS
$loja = 'O';
if ($loja == 'O')
	{
	require("../db_fghi_206_oculos.php");
	$psql = "select round(avg(kh_pc_vend)) as media , count(*) as acertos  from kits_consignado ";
	$psql .= "where kh_cliente = '".$cliente."' and kh_pc_forn > 0 and kh_data >= 20100701 ";
	$prlt = db_query($psql);
	$pline = db_read($prlt);
			///////////////////////////// MODAS
			//	 5				 15
			//	10				 30
			//	20				 50
			//	40				100
			//	60				170
			//	Mais que 60		215
			//////////////////////////////////////////////////////////// TABELA MODAS
			
	$media = round('0'.$pline['media']);
	if ($media < 5) { $limi = 20; }
	if (($media >= 5) and ($media < 10))  { $limi = 30; }
	if (($media >= 10) and ($media < 20)) { $limi = 50; }
	if (($media >= 20) and ($media < 40)) { $limi = 100; }
	if (($media >= 40) and ($media < 60)) { $limi = 170; }
	if ($media >= 60) { $limi = 215; }
	$media_o = $limi;
	}

/////////////////////////////////////////////////////////////////////// 
	require("../db_fghi_210.php");
	$qsql = "select * from clientes_pecas where cp_cliente = '".$cliente."' ";
	$qrlt = db_query($qsql);
	if (!$qline = db_read($qrlt))
		{
			$sql = "insert into clientes_pecas ";
			$sql .= "(cp_cliente, cp_loja_1, cp_loja_2, cp_loja_3, ";
			$sql .= "cp_loja_4, cp_loja_5, cp_loja_6, cp_loja_7, cp_loja_8, cp_loja_9, ";
			$sql .= "cp_lastupdate_1, cp_lastupdate_2, cp_lastupdate_3, ";
			$sql .= "cp_lastupdate_4, cp_lastupdate_5, cp_lastupdate_6, ";
			$sql .= "cp_lastupdate_7, cp_lastupdate_8, cp_lastupdate_9,
					cp_valor_1, cp_valor_2, cp_valor_3,
					cp_valor_4, cp_valor_5, cp_valor_6,
					cp_valor_7, cp_valor_8, cp_valor_9 ";
			$sql .= ") values (";
			$sql .= "'".$cliente."',";
			$sql .= "140,40,15,40,40,40,40,40,40,";
			$sql .= date("Ymd").','.date("Ymd").','.date("Ymd").',';
			$sql .= date("Ymd").','.date("Ymd").','.date("Ymd").',';
			$sql .= date("Ymd").','.date("Ymd").','.date("Ymd").',';
			$sql .= '0,0,0, 0,0,0, 0,0,0 ';
			$sql .= ')';
			$qrlt = db_query($sql);
		}
		$vlr2 = $media_m * 26;
		$vlr3 = $media_o * 40;
		$qsql = "update clientes_pecas set ";
		$qsql .= " cp_valor_2 = ".round('0'.$vlr2).", cp_loja_2 = ".$media_m.",  cp_lastupdate_2 = ".DATE("Ymd"); 
		$qsql .= ",cp_valor_3 = ".round('0'.$vlr3).", cp_loja_3 = ".$media_o.",  cp_lastupdate_3 = ".DATE("Ymd");
		 
		$qsql .= " where cp_cliente = '".$cliente."' ";
		//echo $qsql;
		$qrlt = db_query($qsql);
		$lm[1] = $media_m;
		$lm[2] = $media_o;
		//echo '<BR>Atualizado peças<BR>';
		
}
?>