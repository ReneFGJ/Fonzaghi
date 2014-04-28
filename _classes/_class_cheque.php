<?
 /**
  * Classe Cheque
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.42
  * @package Classe
  * @subpackage UC0031 - Classe Cheque
 */
 
class cheque
	{
	var	$id_chq;
	var $chq_dig_1;
	var $chq_dig_2;
	var $chq_dig_3;
	
	var $chq_data;
	var $chq_hora;

	var $chq_valor;
	var $chq_conta;

	var $chq_nrdep;
	var $chq_ip;
	var $chq_tipo;

	var $chq_cliente;
	var $chq_nr;
	var $chq_status;
	var $chq_dp_hora;
	var $chq_pre;
	var $chq_motivo;
	
	var $erro;
	var $ok;
//	var $;
//	var $;
//	var $;

	var $tabela = "cheque_2009";
	
	function detalhe()
		{
			global $tab_max;
			$sx .= '<table width="'.$tab_max.'"><TR><TD>';
			$sx .= '<fieldset><legend>CHEQUE</legend>';
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR>';
			$sx .= '<TD>'.substr($this->chq_dig_1,0,3);
			$sx .= '<TD>'.$this->chq_conta;
			$sx .= '<TD>'.$this->chq_nr;
			
			$sx .= '<TD align="right" class="lt3"><B>'.number_format($this->chq_valor,2,',','.');
			
			$sx .= '<TR>';
			$sx .= '<TD colspan=3>Cliente:'.$this->chq_cliente;

			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= '<TD>';
			$sx .= '<TD align="right"><B>'.stodbr($this->chq_data);
			$sx .= '<TD align="right" class="lt3"><B>'.stodbr($this->chq_pre);
			
			$sx .= '<TR><TD colspan=5>'.$this->chq_dig_1.' '.$this->chq_dig_2.' '.$this->chq_dig_3;
			$sx .= '</table>';
			$sx .= '</fieldset>';
			$sx .= '</table>';
			
			return($sx);
		}
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_chq','',False,True));
			array_push($cp,array('$S8','chq_dig_1','Bloco Digitável 1',False,True));
			array_push($cp,array('$S10','chq_dig_2','Bloco Digitável 2',False,True));
			array_push($cp,array('$S12','chq_dig_3','Bloco Digitável 3',False,True));
			array_push($cp,array('$S8','chq_nr','Nº cheque',False,True));
			array_push($cp,array('$D8','chq_pre','Pré-datado',False,True));
			return($cp);
		}

	function cheque_mostrar()
		{
		global $tab_max;
		$sx = '<fieldset><legend>Dados do Cheque</legend>';
		$sx .= '<table width="'.$tab_max.'" border=0>';
		$sx .= '<TR><TD width="40%"></TD><TD width="30%"></TD><TD align="right" class="lt4" width="30%">'.number_format($this->chq_valor,2).'</TD><TD>&nbsp;</TD></TR>';
		$sx .= '<TR><TD>Cód. Cliente:'.$this->chq_cliente.'</TD></TR>';
		$sx .= '<TR><TD colspan="2">Data de emissão '.stodbr($this->chq_data).'</TD>';
		$sx .= '<TD align="right">PRÉ '.stodbr($this->chq_pre).'</TD></TR>';
		$sx .= '<TR><TD></TD></TR>';
		$sx .= '</table>';
		$sx .= '</fieldset>';
		return($sx);
		}

	function cheque_log($acao,$nrdep)
		{
		$sql = "insert into cheque_log ";
		$sql .= "(cl_data,cl_bco,cl_nr,";
		$sql .= "cl_id_chq,cl_conta,cl_status,";
		$sql .= "cl_nrdep )";
		$sql .= " values ";
		$sql .= "('".date("Ymd")."','".substr($this->chq_dig_1,0,3)."','".$this->chq_nr."',";
		$sql .= "'".$this->id_chq."','','A',";
		$sql .= "'".$nrdep."')";
		$rlt  = db_query($sql);
		return(true);
		}
	
	function cheque_alterar_vencimento()
		{
		global $cp,$dd,$acao,$saved;
		$this->le();
		$ok = '1';
		
		$dd[0] = $this->id_chq;
		$dd[2] = stodbr($this->chq_pre);
		if (strlen($acao) == 0)
			{
			$dd[3] = stodbr($this->chq_pre);
			}
		$cp = array();
		$tabela = 'access_perfil';
		$cp = array();
		array_push($cp,array('$H8','id_chq','',False,True,''));		
		array_push($cp,array('$H8','','',True,True,''));		
		array_push($cp,array('$D8','chq_pre','Cheque pré (de)',False,False,''));		
		array_push($cp,array('$D8','chq_pre','Cheque pré (alterar para)',True,True,''));
		array_push($cp,array('$HV','',$ok,True,True,''));		
		array_push($cp,array('$B8','','Atualizar data >>',False,True,''));		

		echo '<Table width="600">';
		echo '<TR><TD colspan="2" class="lt4" align="center">';
		echo 'Alteração de vencimento de cheque';
		echo '<TR><TD colspan="2" class="lt4" align="center">';
		echo $this->cheque_mostrar();
		echo '<TR><TD>';
		editar();
		echo '</TD>';
		echo '</table>';

		if ($saved > 0)
			{
			/* Garva log */
			$this->cheque_log('data',substr($dd[3],6,2).'/'.substr($dd[3],4,2));
			/* altera vencimento */
			$sql = "update cheque_2009 set chq_pre = '".$dd[3]."' ";
			$sql .= " where id_chq = ".$this->id_chq;
			$rlt = db_query($sql);
			}
		return(true);
		}
		
	function le($id)
		{
			if (strlen($id) > 0) { $this->id_chq = $id; }
			$id = $this->id_chq;
			if (strlen($id) > 0)
				{
					require("../db_ecaixa.php");
					$sql = "select * from cheque_2009 ";
					$sql .= " where id_chq = ".$id;
					
					$rlt = db_query($sql);
					if ($line = db_read($rlt))
						{
							$this->id_chq = $line['id_chq'];
							$this->chq_dig_1 = $line['chq_dig_1'];
							$this->chq_dig_2 = $line['chq_dig_2'];
							$this->chq_dig_3 = $line['chq_dig_3'];
							
							$this->chq_data = $line['chq_data'];
							$this->chq_hora = $line['chq_hora'];
						
							$this->chq_valor = $line['chq_valor'];
							$this->chq_conta = $line['chq_conta'];
						
							$this->chq_nrdep = $line['chq_nrdep'];
							$this->chq_ip = $line['chq_ip'];
							$this->chq_tipo = $line['chq_tipo'];
					
							$this->chq_cliente = $line['chq_cliente'];
							$this->chq_nr = $line['chq_nr'];
							$this->chq_status = $line['chq_status'];
							$this->chq_dp_hora = $line['chq_dp_hora'];
							$this->chq_pre = $line['chq_pre'];
							$this->chq_motivo = $line['chq_motivo'];
							$this->erro = '000';
							$this->ok = 1;
						} else {
							$this->erro = '900';
							$this->ok = 0;
						}
				} else {
					$this->erro = '999';
					$this->ok = 0;
				}
			return($this->ok);
		}
	function cheque_depositar()
		{
			
		}
	function cheque_localizar_cliente($cliente)
		{
			global $dd,$conn,$base,$base_name,$tab_max;
			require("../db_ecaixa.php");
			$sql = "select * from cheque_2009 ";
			$sql .= " where chq_cliente = '$cliente'
						order by chq_pre desc ";
					echo $sql;	
			$sx = '<table width="'.$tab_max.'" class="lt1">';
			$sx .= '<TR><TH>DIG1<TH>DIG2<TH>DIG3<TH>Cliente<TH>Data<TH>Pre-datado<TH>Status';
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="../cheque/cheque_ver.php?dd0='.$line['id_chq'].'">';
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>'.$link.$line['chq_dig_1'];
					$sx .= '<TD>'.$link.$line['chq_dig_2'];
					$sx .= '<TD>'.$link.$line['chq_dig_3'];
					$sx .= '<TD>'.$link.$line['chq_cliente'];
					$sx .= '<TD align="center">'.$link.stodbr($line['chq_data']);
					$sx .= '<TD align="center">'.$link.stodbr($line['chq_pre']);
					$status = $line['chq_status'];
					if ($status=='A') { $status = 'Em contas a receber'; }
					if ($status=='D') { $status = 'Depositado'; }
					$sx .= '<TD>'.$link.$status;
				}
			return($sx);			
		}		
	function cheque_localizar()
		{
		global $dd,$conn,$base,$base_name;
		$this->erro = '999';
		
		$sx = '<form method="post">';
		$sx .= '<Table width="600">';
		$sx .= '<TR>';
		$sx .= '<TD class="lt0">Informe o número do cheque (DG1-DG2-DG3)</TD>';
		$sx .= '</TR>';
		$sx .= '<TR>';
		$sx .= '<TD class="lt4"><input type="text" name="dd1" value="'.$dd[1].'" size="36" maxlength="30"></TD>';
		$sx .= '</TR>';
		
		$dg = sonumero($dd[1]);
		if ((strlen($dd[1]) > 0) and (strlen($dg) == 30))
			{
			$dg1 = substr($dg,0,8);
			$dg2 = substr($dg,8,10);
			$dg3 = substr($dg,18,12);
			
			require("../db_ecaixa.php");
			$sql = "select id_chq from cheque_2009 ";
			$sql .= " where chq_dig_1 = '".$dg1."' and chq_dig_2 = '".$dg2."' and chq_dig_3 = '".$dg3."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->erro = '000';
					$this->ok = '1';
					$this->id_chq = $line['id_chq'];
				} else {
					$sx .= '<TR><TD><font color="red">Cheque não localizado</font></TD></TR>';
					$this->erro = '001';
				}
			} else {
				if (strlen($dd[1]) > 0)
					{
					$sx .= '<TR><TD><font color="red">Código do cheque inválido</font></TD></TR>';
					$this->erro = '002';
					}
			}
		$sx .= '<TR>';
		$sx .= '<TD class="lt0"><input type="submit" name="dd10" value="buscar cheque >>>"></TD>';
		$sx .= '</TR>';
		$sx .= '</TABLE>';
		$sx .= '</form>';
		
		if ($this->erro == '000') { $sx = ''; }
		return($sx);
		}
	function updatex()
		{
			return(1);
		}
	}
?>