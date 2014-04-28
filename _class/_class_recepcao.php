<?php
class recepcao
	{
		var $id;
		var $data;
		var $loja;
		var $tipo;
		
		var $nome;
		var $hora;
		var $cliente;
		
		var $chamada;
		
		var $tabela = 'recepcao';
		
		function update()
			{
				
			}
		
		function chamada_acionada()
			{
				$sql = "select count(*) as total ";
				$sql .= " from recepcao ";
				$sql .= " where rc_status = 'A' ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$this->chamada = $line['total'];
				return($line['total']);
			}
			
		function chamada_mostra_dados()
			{
				global $dd;
				$sx .= 'Chamada em espera '.$this->chamada;
				if ($dd[2] >= $this->chamada) { $dd[2] = ''; }
				
				echo '<META HTTP-EQUIV=Refresh CONTENT="9; URL=main.php?dd1='.$dd[1].'&dd2='.(round($dd[2])+1).'">';

				$sql .= "select * from recepcao where rc_status = 'A' offset ".round($dd[2])." limit 1 ";
				
				$rlt = db_query($sql);		
				$line = db_read($rlt);
				$id = trim($line['rc_cliente']);
				$mesa = trim($line['rc_mesa']);
				$loja = trim($line['rc_loja']);
//				if (strlen($id)==0)
//					{ redirecina(page()); }
				$sx .= '<font color="white">XXXX</font><EMBED SRC="sons/nome.mid" AUTOSTART="True" LOOP="false" WIDTH=145 HEIGHT=20></EMBED>';
				$sx = mostra_cadastro($id,$mesa,$loja);
				
				
				return($sx);
			}
		
		function campanha_mostra($id)
			{
				if ($this->chamada_acionada()==0)
					{
						$sx = '<center>';
						$sx .= '<img id="img" src="img_campanha/camp_'.strzero($id,4).'.jpg" width="95%" style="display: none;" >';
						$sx .= '<script>
									var tela = $("#img").fadeIn("slow");
								</script>';
						$sx .= '<source src="sons/nome.mp3" type="audio/mpeg">';							
					} else {
						$sx = $this->chamada_mostra_dados();
					}
						
				return($sx);
			}
		function busca_form()
			{
				global $dd;
				$sx = '<TABLE>';
				$sx .= '<TR><TD><form method="get" action="'.page().'">';
				$sx .= '<TR><TD>';
				$sx .= '<input type="text" name="dd1" value="'.$dd[1].'" id="busca_form">';
				$sx .= '<TD>';
				$sx .= '<input type="submit" name="acao" value="Busca" id="busca_form_submit">';
				$sx .= '<TR><TD></form>';
				$sx .= '</table>';
				return($sx);
			}
		function busca_resultado()
			{
				global $dd;
				if (strlen($dd[1])==0) { $sx = $this->busca_form(); return($sx); }
			}
		function calcula_tempo($ti,$ta)
			{
				$mm1=round(substr($ti,0,2))*60;
				$mm2=round(substr($ti,0,2));
				
				$mn1=round(substr($ta,3,2))*60;
				$mn2=round(substr($ta,3,2));
				
				$mm = $mm1 + $mm2;
				$mn = $mn1 + $mn2;
				
				$tn = ($mm - $mn);
				$hr = 0;
				while ($tn >= 60)
					{
						$hr++;
						$tn = $tn - 60;
					}		
				$hh = strzero($tn,1);
				$hh .= ':'.strzero($tn,2);
				return($hh);
			}
		function inserir_nome()
			{
				$nome = substr($this->nome,0,40);
				$cliente = $this->cliente;
				$hora = date("H:i:s");
				$data = date("Ymd");
				$tipo = $this->tipo;
				$hora_min = round(date("H"))*60+round(date("i"));
				$loja = $this->loja;
				
				$sql = "select * from ".$this->tabela." 
					where rc_cliente = '$cliente' and rc_date = $data 
					and rc_status = '@' and rc_loja = '$loja' ";
				$rlt = db_query($sql);
				
				if (!($line = db_read($rlt)))
					{
					$sql = "insert into ".$this->tabela."( 
					rc_cliente, rc_date , rc_hora, rc_hora_min, 
					rc_loja, rc_hora_ate_1, rc_hora_ate_2, rc_hora_ate_3,
					rc_hora_ate_4, rc_hora_ate_5, rc_status,
					rc_nome, rc_tipo, rc_tempo_medio, 
					rc_log_1, rc_log_2, rc_log_3, rc_log_4, rc_log_5
					) values (
					'$cliente',$data,'$hora','$hora_min',
					'$loja','','','',
					'','','@',
					'$nome','$tipo',0,
					'','','','','')				
					";
					$rlt = db_query($sql);
					}
				return(1);				
			}	
		function acertos_pendentes($db,$cliente)
		{
			global $base_name,$base_server,$base_host,$base_user;
			require($db);
			$sx=0;
			$dt = '';
			$sql = " select * from kits_consignado
					 where kh_cliente = '".$cliente."' and 
					 	   kh_acerto < 19800101  and 
					 	   kh_status <>'x'
					 order by kh_data desc limit 1
					";
			$rlt = db_query($sql);
			//echo $sql;
			while($line=db_read($rlt))
			{
				$dt = $line['kh_previsao'];
			}
			if(strlen($dt)>0)
			{
				if($dt>date('Ymd')){ $sx = 1;}
				if($dt<=date('Ymd')){ $sx = 2;}
			}
			return($sx);
		}	
		
		function acertos_pendentes_lista($db,$cliente)
		{
			global $base_name,$base_server,$base_host,$base_user;
			require($db);
			$vld=0;
			$dt = '';
			$sql = " select * from kits_consignado
					 where kh_cliente = '".$cliente."' and 
					 	   kh_acerto < 19800101 and 
					 	   kh_status <>'X'
					 order by kh_data desc limit 1
					";
			$rlt = db_query($sql);
			$sx = "<table>";
			while($line=db_read($rlt))
			{
				$sx .= "<tr><td>".substr($line['kh_previsao'],6,2).'/'.substr($line['kh_previsao'],4,2).'/'.substr($line['kh_previsao'],0,4)."</td></tr>";
				$vld = 1;
			}
			$sx .= "</table>";
			if($vld==0){$sx='';}
			
			return($sx);
		}	
			
	}
?>
