<?php
    /**
     * Senff - update
	 * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.30
	 * @package senff
	 * @subpackage classe
    */
    
class senff_update
	{
	var $include_class='../';
	
	function senff_phase_i()
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$dd;
		$dia = trim(round(date('d')));
		while($dia>=1)
		{
			$data = date("Ym").substr('0'.$dia,-2);
			
			//require("../db_fghi_210_modas.php");
			require($this->include_class."db_fghi_206_joias.php");
			$descricao = 'Acerto na data Joias';
			$lj = 'J';
			$this->senff_phase_ia($descricao,$lj,$data);
			
			//echo '<BR>Joias Express';
			require($this->include_class."db_fghi_206_express_joias.php");
			$descricao = 'Acerto na data Joias Express';
			$lj = 'F';
			$this->senff_phase_ia($descricao,$lj,$data);
			//echo '<BR>Modas';
			require($this->include_class."db_fghi_206_modas.php");
			$descricao = 'Acerto na data Modas';
			$lj = 'M';
			$this->senff_phase_ia($descricao,$lj,$data);
			//echo '<BR>Modas Express';
			require($this->include_class."db_fghi_206_express.php");
			$descricao = 'Acerto na data Modas Express';
			$lj = 'E';
			$this->senff_phase_ia($descricao,$lj,$data);
			//echo '<BR>Oculos';
			require($this->include_class."db_fghi_206_oculos.php");
			$descricao = 'Acerto na data Oculos';
			$lj = 'O';
			$this->senff_phase_ia($descricao,$lj,$data);
			//echo '<BR>UB';
			require($this->include_class."db_fghi_206_ub.php");
			$descricao = 'Acerto na data UB';
			$lj = 'U';
			$this->senff_phase_ia($descricao,$lj,$data);
			//echo '<BR>Sensual';
			require($this->include_class."db_fghi_206_sensual.php");
			$descricao = 'Acerto na data Sensual';
			$lj = 'S';
			$this->senff_phase_ia($descricao,$lj,$data);
			
			$dia--;
		}

		return(1);
	}

	function lojas_medias()
		{
			$cp = array('J'=>150,
						'F'=>45,
						'M'=>215,
						'E'=>60,
						'S'=>65,
						'U'=>110,
						'O'=>60
			);
			return($cp);
		}
	
	function senff_phase_ia($descricao,$lj,$data)
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$dd;
		$sql = "select * from kits_consignado
			where kh_acerto = $data
			and kh_previsao = $data 
			";
		//echo '<BR>'.$sql;
		$rlt = db_query($sql);	
		$rs = array();
		$desc = $descricao;
		
		$medias = $this->lojas_medias();
		$media = $medias[$lj];
		
		while ($line = db_read($rlt))
		{ array_push($rs,$line); }
		
		require($this->include_class."db_206_telemarket.php");
		
		for ($r=0; $r < count($rs);$r++)
			{
				$line = $rs[$r];
				
				$id = $line['id_kh'];
				$idr = round($id / 100000);
				if ($idr > 0)
					{
						$id = $id - $idr * 100000;
					}
				$doc = $lj.strzero($id,6);
				$valor = 100;
				$cliente = $line['kh_cliente'];
				$valor = $line['kh_pago'];
				if (date("Ymd") < 20140201) { $media = 0; }
				if ($valor >= $media)
					{
						$descricao = $desc;
						if (date("Ymd") >= 20140201) 
							{ $descricao .= ' (acima da média)'; }
						$valor = 100;
						$this->inserir_lancamento($data,$descricao,$valor,$cliente,$doc);
					} else {
						$valor = 10;
						$descricao = $desc.' (abaixo da média)';
						$this->inserir_lancamento($data,$descricao,$valor,$cliente,$doc);
					}
			}
		return(1);
	}
	
	function senff_phase_ii()
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$dd;
		$dia=trim(round(date('d')+1));
		$i=1;
		while($i<=$dia)
		{
			$data = date("Ym").substr('0'.$i,-2);
			require($this->include_class."db_206_telemarket.php");	
			$sql = "select * from sempre_fonzaghi_2012_saldo 
						where sfs_credito > 0 and sfs_data = ".$data;
			$rlt = db_query($sql);
			//echo '<HR>'.$sql;
			while ($line = db_read($rlt))
				{	
					$id = $line['id_sfs'];
					$doc = 'PG'.strzero($id,6);
					$valor = $line['sfs_credito'];
					$cliente = $line['sfs_cliente'];
					$descricao = 'Credito de pagamento';
					$this->inserir_lancamento($data,$descricao,$valor,$cliente,$doc);
				}
			$i++;		
		}		
	
		
		return(1);
	}
	
	function senff_phase_iii()
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$dd;
		if (strlen($dd[0]) > 0)
			{
				$data = ($dd[0]+1);
			} else {
				$data = date("Ym").'01';
			}
		require($this->include_class."db_fghi_210.php");	
		$sql = "select * from clientes_indicacao 
					where ci_data >= 20130301 ";
		$rlt = db_query($sql);
		$rgs = array();
		while ($line = db_read($rlt))
			{	
				$id = $line['id_ci'];
				$data = $line['ci_data'];
				$doc = 'I'.trim($line['ci_indicado']);
				$valor = 1000;
				$cliente = $line['ci_cliente'];
				$descricao = 'Credito de indicacao';
				$indicedo = $line['ci_indicado'];
				//echo '<BR>'.$doc.' '.$cliente.' '.$descricao;
				array_push($rgs,array($data,$descricao,$valor,$cliente,$doc));
			}
		require($this->include_class."db_206_telemarket.php");	
		for ($r=0;$r < count($rgs);$r++)
			{
				$data = $rgs[$r][0];	
				$descricao = $rgs[$r][1];
				$valor = $rgs[$r][2];
				$cliente = $rgs[$r][3];
				$doc = $rgs[$r][4];
		
				$this->inserir_lancamento($data,$descricao,$valor,$cliente,$doc);
				//echo '<BR>'.$cliente.'-->'.$doc.'--'.$data;
			}
		return(1);
	}
	
	function senff_phase_iv()
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$dd;
		$mes = date("m");
		
		if (strlen($dd[0]) > 0)
			{
				$data = ($dd[0]+1);
			} else {
				$data = date("Ym").'01';
			}
		
		$datay = date("Y").$mes.'01';
		$datax = date("Y").$mes.'99';
		
		$sql = "select kh_cliente from kits_consignado where kh_acerto >= ".$datay." and kh_acerto <= ".$datax." and kh_status = 'B' ";
		
		$dbs = array();
		array_push($dbs,'db_fghi_206_joias.php');
		array_push($dbs,'db_fghi_206_express_joias.php');
		array_push($dbs,'db_fghi_206_modas.php');
		array_push($dbs,'db_fghi_206_express.php');
		array_push($dbs,'db_fghi_206_oculos.php');
		array_push($dbs,'db_fghi_206_sensual.php');
		array_push($dbs,'db_fghi_206_ub.php');
		
		$clie = array();
		
		for ($r=0;$r < count($dbs);$r++)
			{
				require($this->include_class.$dbs[$r]);
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						array_push($clie,$line['kh_cliente']);
					}
			}	
		
		require($this->include_class."db_fghi_206_cadastro.php");
		for ($r=0;$r < count($clie);$r++)
			{
				if (strlen($wh) > 0) { $wh .= ' or ';}
				$wh .= " (cl_cliente = '".$clie[$r]."') ";
			}
		$wh = '( '.$wh.' )';
		$wh .= " and (cl_nasc like '%/".$mes."/%') ";
		
		$sql = "select cl_cliente, cl_dtnascimento from cadastro where ".$wh;
		$clie = array();
		$rlt = db_query($sql);
		while ($line = db_read($rlt))
		{
			array_push($clie,array($line['cl_cliente'],$line['cl_dtnascimento']));
		}
		
		require($this->include_class."db_206_telemarket.php");
		for ($r=0;$r < count($clie);$r++)
		{
			$line = $clie[$r];
			$data = date("Y").substr($clie[$r][1],4,4);
			$descricao = 'Pontos pelo aniversario '.substr(stodbr($clie[$r][1]),0,5);
			$doc = 'DN'.substr($data,2,6);
			$cliente = $clie[$r][0];
			$valor = 1000;
			//echo $data.' '.$cliente;	
			$xrlt = $this->inserir_lancamento($data,$descricao,$valor,$cliente,$doc);
		}
				
		
		
		return(1);
	}
	
	function inserir_lancamento($data,$descricao,$valor,$cliente,$doc)
	{
		$sql = "select * from senff_extrato 
				where ex_doc = '$doc' and ex_cliente = '$cliente' ";
		$rlt = db_query($sql);
		if (!($line = db_read($rlt)))
			{
				$sql = "insert into senff_extrato
						(
							ex_data, ex_descricao, ex_valor,
							ex_cliente, ex_doc
						) values (
							$data,'$descricao',$valor,
							'$cliente','$doc'						
						)
				";
				$xrlt = db_query($sql);
			}
		return(1);		
	}
	
	}
?>
