<?php
class financeiro
	{
	var $tabela = '';
	
	function saldo_notas($dd1)
		{
			$nps = array();
			array_push($nps,'duplicata_joias');
			array_push($nps,'duplicata_modas');
			array_push($nps,'duplicata_oculos');
			array_push($nps,'duplicata_sensual');
			array_push($nps,'duplicata_teste');
			array_push($nps,'duplicata_usebrilhe');
			array_push($nps,'duplicata_extras');
			array_push($nps,'juridico_duplicata');
			
			$npn = array();
			array_push($npn,'Joias');
			array_push($npn,'Modas');
			array_push($npn,'Oculos');
			array_push($npn,'Sensual');
			array_push($npn,'Teste');
			array_push($npn,'UseBrilhe');
			array_push($npn,'Extras');
			array_push($npn,'Juridico');			
			$sx = '<table width="700">';
			$sx .= '<TR>';
			$sx .= '<TH>'.'Loja';
			$sx .= '<TH>'.'Notas';
			$sx .= '<TH>'.'Valor Total';
			$nts = 0;
			$total = 0;
			for ($r=0;$r < count($nps);$r++)
				{
				$table = $nps[$r];
				$sql = "select count(*) as total, sum(round(dp_valor*100))/100 as valor 
						from $table
						where dp_data <= $dd1 and 
						((dp_status = 'A' or dp_status = '@') or (dp_datapaga > $dd1 and dp_status = 'B')); ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$sx .= '<TR>';
				$sx .= '<TD>'.$npn[$r];
				$sx .= '<TD align="center">'.$line['total'];
				$sx .= '<TD align="right">'.number_format($line['valor'],2,',','.');
				$valor = $valor + $line['valor'];
				$total = $total + $line['total'];
				}
			$sx .= '<TR><TD>Total';
			$sx .= '<TD align="center"><B>';
			$sx .= $total;
			$sx .= '<TD align="right"><B>';
			$sx .= number_format($valor,2,',','.');
			$sx .= '</table>';
			return($sx);
		}
	
	function cp()
		{
			global $dd, $user_id, $acao;
			/* seta variaveis se vazia */
			if (strlen($dd[2]) ==0) { $dd[2] = '0.00'; }
			if (strlen($dd[3]) ==0) { $dd[3] = date("d/m/Y"); }
			if (strlen($dd[7]) ==0) { $dd[7] = date("d/m/Y"); }
			if (strlen($dd[12]) ==0) { $dd[12] = '19000101'; }
			$dd[11] = '0000000';
			$dd[15] = UpperCaseSQL($dd[4]);
			$dd[16] = '';
			$dd[17] = date("Ymd");
			$dd[18] = $dd[2];
			$dd[19] = '0'.$user_id;
			$dd[20] = date("H:i"); /* Hora */
			$dd[21] = '0'.$user_id; /* log so usuario */
			
			$cp = array();
			//// dd00
			array_push($cp,array('$H4','id_cr','cod',False,True,''));
			array_push($cp,array('$Q dt_descricao:dt_codigo:select * from documento_tipo where dt_ativo=1 order by dt_ordem','cr_tipo','Tipo Documento ',False,True,''));
			array_push($cp,array('$N10','cr_valor','Valor ',True,True,''));
			array_push($cp,array('$D8','cr_venc','Vencimento ',True,True,''));
			array_push($cp,array('$S80','cr_historico','Histórico ',True,True,''));

			//// dd05
			array_push($cp,array('$S10','cr_pedido','Pedido ',True,True,''));
			array_push($cp,array('$S10','cr_parcela','Parcela ',True,True,''));
			array_push($cp,array('$O 0:Não&1:SIM','cr_previsao','Previsão ',True,True,''));
			array_push($cp,array('$S10','cr_doc','Nº Doc. ',False,True,''));
			array_push($cp,array('$Q e_nome:id_e:select * from empresa where e_ativo=1 order by e_ordem','cr_empresa','Empresa ',False,True,''));

			//// dd10
			array_push($cp,array('$Q ct_descricao:ct_codigo:select * from contas_tipo where ct_ativo=1 and ct_tipo=2 order by ct_descricao','cr_conta','Conta ',False,True,''));
			array_push($cp,array('$H8','cr_cliente','Cliente : ',False,True,''));
			array_push($cp,array('$H8','cr_dt_quitacao','Dt. Quitação : ',False,True,''));
			array_push($cp,array('$O N:Ativo&X:Excluir','cr_status','Situação',False,True,''));
			array_push($cp,array('$H1','cr_img','Img',False,True,''));

			//// dd15
			array_push($cp,array('$H1','cr_historico_asc','',False,True,''));
			array_push($cp,array('$H1','cr_cc','',False,True,''));
			array_push($cp,array('$H1','cr_lastupdate','',False,True,''));
			array_push($cp,array('$H1','cr_valor_original','',False,True,''));
			array_push($cp,array('$H1','cr_log','',False,True,''));

			//// dd20
			array_push($cp,array('$H1','cr_hora','',False,True,''));
			array_push($cp,array('$H1','cr_log_paga','',False,True,''));
			
			if (strlen($dd[0])==0)
				{
				array_push($cp,array('${','','Periodicidade',False,True,''));
				array_push($cp,array('$O d:só este&m:todo mes','','Replicações',False,True,''));
				array_push($cp,array('$[1-48]','','Número de lancamentos',False,True,''));
				array_push($cp,array('$}','','Periodicidade',False,True,''));				
				}	
			//array_push($cp,array('$H8','','',True,True,''));		
			return($cp);
		}	
	}
?>
