<?
class propagandas
	{
		var $id_prop;
		var $prop_codigo;
		var $prop_descricao;
		var $prop_ativo_1;
		
		var $tabela = "propagandas";

		function le($id='')
			{
				if (strlen($id) > 0) { $this->prop_codigo = $id; }
				$sql = "select * from ".$this->tabela." where prop_codigo = '".$this->prop_codigo."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->id_prop = $line['$id_prop'];
						$this->prop_codigo = $line['prop_codigo'];
						$this->prop_descricao = $line['prop_descricao'];
						$this->prop_ativo_1 = $line['prop_ativo_1'];
						return(1);
					}
				return(0);
			}
			
		function propaganda_troca($clie,$propaganda)
			{
				$sql = "select * from cadastro where cl_cliente = '".$clie."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						if ($line['cl_propaganda'] != $propaganda)
						{
							$sql = "update cadastro set cl_propaganda = '".$propaganda."' ";
							$sql .= " where cl_cliente = '".$clie."' ";
							$rlt = db_query($sql);
							$ok = 1;
							
							/* Grava Log */
							/* Reservado */
						} else {
							$ok = 0;
						}
					} else {
						$ok = 0;
					}
				return($ok);
			}
		
		function mostra()
			{
				$sx .= '<div class="lt4"><CENTER>';
				$sx .= $this->prop_codigo.'-'.$this->prop_descricao;
				$sx .= '</div>';
				return($sx);
			}
		function consultoras_notas_abertas($mtz)
			{
				global $conn,$db,$ok,$wh,$wp;
				/* Joias */
				$sql = "select 'DJ' as loja, sum(round(dp_valor*100))/100 as valor, dp_cliente ";
				$sql .= " from duplicata_joias where (".$wp.") and dp_status = 'A' group by dp_cliente";
				/* Modas */
				$sql .= " union ";
				$sql .= "select 'DM' as loja, sum(round(dp_valor*100))/100 as valor, dp_cliente ";
				$sql .= " from duplicata_modas where (".$wp.") and dp_status = 'A' group by dp_cliente";
				/* Óculos */
				$sql .= " union ";
				$sql .= "select 'DO' as loja, sum(round(dp_valor*100))/100 as valor, dp_cliente ";
				$sql .= " from duplicata_oculos where (".$wp.") and dp_status = 'A' group by dp_cliente";
				/* Sensual */
				$sql .= " union ";
				$sql .= "select 'DS' as loja, sum(round(dp_valor*100))/100 as valor, dp_cliente ";
				$sql .= " from duplicata_sensual where (".$wp.") and dp_status = 'A' group by dp_cliente";
				/* UB */
				$sql .= " union ";
				$sql .= "select 'DU' as loja, sum(round(dp_valor*100))/100 as valor, dp_cliente ";
				$sql .= " from duplicata_usebrilhe where (".$wp.") and dp_status = 'A' group by dp_cliente";
				/* Juridico */
				$sql .= " union ";
				$sql .= "select 'DD' as loja, sum(round(dp_valor*100))/100 as valor, dp_cliente ";
				$sql .= " from juridico_duplicata where (".$wp.") and dp_status = 'A' group by dp_cliente";
		
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$vlr = ((-1)*$line['valor']);
						$lx = array('loja'=>$line['loja'],'kh_cliente'=>trim($line['dp_cliente']),'kh_fornecimento'=>19000102,'kh_acerto'=>19000102,'kh_status'=>'A','kh_pago'=>$vlr,'kh_vlr_vend'=>$vlr);
						array_push($mtz,$lx);
					}			
				return($mtz);
			}
		
		function consultaras_vendas_loja($loja,$mtz)
			{
				global $conn,$db,$ok,$wh,$wp;
				$cp = "'".$loja."' as loja,kh_cliente, kh_fornecimento, kh_acerto, kh_status, ";
				$cp .= 'kh_pago, kh_vlr_vend ';
				$sql = "select $cp from kits_consignado where ";
				$sql .= " (kh_status <> 'X') and (".$wh.") ";
				$sql .= " order by kh_acerto ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$lx = array('loja'=>$line['loja'],'kh_cliente'=>trim($line['kh_cliente']),'kh_fornecimento'=>$line['kh_fornecimento'],'kh_acerto'=>$line['kh_acerto'],'kh_status'=>$line['kh_status'],'kh_pago'=>$line['kh_pago'],'kh_vlr_vend'=>$line['kh_vlr_vend']);						
						array_push($mtz,$lx); }				
				return($mtz);
			}
		function consultoras_vendas($rrr)
			{
				global $conn,$db,$ok,$wh,$wp;
				$wh = '';
				$wp = '';
				$rs = array();
				for ($r=0;$r < count($rrr);$r++)
					{
						$line = $rrr[$r];
						if (strlen($wh) > 0) { $wh .= ' or '; $wp .= ' or '; }
						$wh .= "kh_cliente = '".$line['codigo']."' ";
						$wp .= "dp_cliente = '".$line['codigo']."' ";
					}
					
					/*** Consulta Modas **/
				if (strlen($wp)==0) { $wp = '(1=2)'; $wh = '(1=2)'; }
					
				return($rs);
			}
		function consultoras_propaganda_periodo($prop='',$data1='',$data2='')
			{
//				$sql = "select * from cadastro_completo where pc_propaganda_1 = '018' ";
//				$rlt = db_query($sql);
//				while ($line = db_read($rlt))
//					{
//						$sql = "update cadastro set cl_propaganda = '".$line['pc_propaganda_1'].$line['pc_propaganda_2']."' ";
//						$sql .= ", cl_update = ".round($line['pc_update'])." where cl_cliente = '".$line['pc_codigo']."' ";
//						$xrlt = db_query($sql);
//					}
				
				$sql = "select cl_cliente as codigo,cl_cliente,cl_dtcadastro as data,
						cl_nome as nome				 
						from cadastro 
						where (cl_propaganda like '$prop%' or
						cl_propaganda like '%$prop') and
						(cl_dtcadastro >= $data1 and cl_dtcadastro <= $data2) 
						order by cl_nome ";
				$rlt = db_query($sql);
				$rsp = array();
				while ($line = db_read($rlt))
					{
						array_push($rsp,$line);
					}					
				return($rsp);
				
			}
		function consultoras_motra($rsp=array())
			{
				global $colunas;
				for ($r=0;$r < count($rsp);$r++)
					{
						$line = $rsp[$r];
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD align="center">'.$line['codigo'];
						$sx .= '<TD>'.$line['nome'];
						$sx .= '<TD align="center">'.stodbr($line['data']);
					}
					
				if (strlen($sx) > 0)
					{
						 $st = '<TR><TD colspan=10><B><I>total de '.count($rsp).' consultora(s)';
						 $sh = '<TR><TH>código<TH>nome<TH>atualizado';	
						 $sx = '<table width="100%" class="lt1">'.$sh.$sx.$st.'</table>'; 
					}
				return($sx);
			}
	}

?>