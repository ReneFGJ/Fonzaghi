<?php
class estoque
	{
		function insert_log($prod,$ean13,$cliente,$status)
			{
				global $user_log;
				
				$data = date("Ymd");
				$hora = date("H:i");
				$log = $user_log;
				$sql = "insert into produto_log_".date("Ym");
				$sql .= " (pl_ean13, pl_data, pl_hora, 
							pl_cliente, pl_status, pl_kit,
							pl_produto, pl_log
						) values (
						'$ean13',$data,'$hora',
						'$cliente','$status','',
						'$prod','$log'
						)";
				$rlt = db_query($sql);
				return(1);
			}
		function Inventario_pecas_baixar()
			{
				$data = date("Ymd");
				$sql = "select id_pe, pe_produto, p_descricao, pe_ean13, pe_vlr_venda, pe_lastupdate, pe_status from produto_estoque
						inner join produto on pe_produto = p_codigo
						where pe_inventario = 0 and (pe_status ='A' or pe_status = 'B')
						and pe_lastupdate < $data
						order by p_descricao
						 ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$this->insert_log($line['pe_produto'],$line['pe_ean13'],
							'INVENT','T'
						); 
						$sql = "update produto_estoque set 
								pe_lastupdate = $data,
								pe_status = 'T',
								pe_vlr_venda = 0,
								pe_log = 'INVENT'
								where id_pe = ".$line['id_pe'];
						$rrr = db_query($sql);
						echo '.';
					}
				echo 'Baixa efitivada do sucesso!';
			}
		function Inventario_pecas_falta()
			{
				$sql = "select p_descricao, pe_ean13, pe_vlr_venda, pe_lastupdate, pe_status from produto_estoque
						inner join produto on pe_produto = p_codigo
						where pe_inventario = 0 and (pe_status ='A' or pe_status = 'B')
						order by p_descricao
						 ";
				$rlt = db_query($sql);
				
				$sx = '<table width="95%" cellpadding=1 cellspacing=0>
						<TR>
						<TH align="center">EAN13
						<TH align="left">Descrição
						<TH align="center">Ultimo Movimento
						<TH align="center">Status
						<TH align="right">Valor
						
				';
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$sx .= '<TR>';
						$sx .= '<TD class="tabela00" align="center">'.$line['pe_ean13'];
						$sx .= '<TD class="tabela00">'.$line['p_descricao'];
						$sx .= '<TD class="tabela00" align="center">'.stodbr($line['pe_lastupdate']);
						$sx .= '<TD class="tabela00" align="center">'.$line['pe_status'];
						$sx .= '<TD class="tabela00" align="right">'.number_format($line['pe_vlr_custo'],2);
					}
					$sx .= '<TR><TD align="center" colspan=10>Total de '.$tot.' produtos não localizados';
					$sx .= '</table>';
				return($sx);
			}
		function inventario_geral()
			{
				$sql = "update produto_estoque set pe_inventario = 0,
						pe_status = 'B'
						where pe_inventario = 1 and (pe_status = 'A' or pe_status = 'B') ";
				$rlt = db_query($sql);
				
				$sql = "update produto_estoque set pe_status = 'B'
						where pe_status = 'A' ";
				$rlt = db_query($sql);

				$sql = "update produto_estoque set pe_inventario = 1
						where pe_inventario = 0 and (pe_status = 'T' or pe_status = 'F') ";
				$rlt = db_query($sql);				
				return(1);
			}
			
		function inventario_resumo()
			{
				$sql = "
						select count(*) as total, pe_status, pe_inventario from produto_estoque 
						where pe_status = 'A' or pe_status = 'B'
						group by pe_status, pe_inventario
						order by pe_inventario, pe_status
						";
				
				$rlt = db_query($sql);
				$tot = array(0,0,0,0,0,0);
				while ($line = db_read($rlt))
					{
						$status = trim($line['pe_status']);
						$total = $line['total'];
						$inventario = $line['pe_inventario'];
						$id = 0;
						if ($status == 'B') { $id = $id + 1; }
						$id = $id + $inventario*2;
						$tot[$id] = $total;
					}
				$sx = '<table>';
				
				$sx .= '<TR>';
				$sx .= '	<TH colspan=2>Não localizadas
							<TH colspan=2>Inventariadas';
				$sx .= '<TR>
							<TH>Em estoque
							<TH>Retorno (acerto)
							<TH>Localizado
							<TH>Inventariado
							';	
				$sx .= '<TR align="center">
							<TD>'.$tot[0].'
							<TD>'.$tot[1].'
							<TD>'.$tot[2].'
							<TD>'.$tot[3];
				$sx .= '</table>';
				return($sx);
			}
			
		function posicao_estoque()
			{
				global $base_name;
				$data = date("Ymd");
				$sql = "
					select  count(*) as total, sum(round(pe_vlr_custo*100))/100 as valor, p_class_1 from produto_estoque 
					left join produto on p_codigo = pe_produto
					where pe_status = 'A' or pe_status = 'B' or pe_status = 'F'
					group by p_class_1				
				";
				$rlt = db_query($sql);
				$sqli = '';
				$toti = 0;
				$totp = 0;
				while ($line = db_read($rlt))
					{
						$grupo = $line['p_class_1'];
						$total = $line['total'];
						$valor = $line['valor'];
						$toti = $toti + $line['total'];
						$totp = $totp + $line['valor'];
						$sqli .= "insert into indicador_estoque
							(ie_data,ie_grupo,ie_total,ie_valor) 
							values
							($data,'$grupo',$total,$valor);
						".chr(13);
					}
					
				$sx .= '<TABLE width=500 class="lt3"> ';
				$sx .= '<TR><TH colspan=2 class="lt4"><center>'.date("d/m/Y").' - '.$base_name;
				$sx .= '<TR><TH>Total Peças<TH>Valor do Estoque';
				$sx .= '<TR>';
				$sx .= '<TD align="center">'.number_format($toti,0,',','.');
				$sx .= '<TD align="center">'.number_format($totp,2,',','.');
				$sx .= '</TABLE>';
				$sql = "delete from indicador_estoque where ie_data = ".$data;
				$rlt = db_query($sql);
				
				if (strlen($sqli) > 0)
					{
						$rlt = db_query($sqli);
					}
				return($sx);
			}
			
		function strucuture()
			{
				$sql = "
				CREATE TABLE indicador_estoque
					(
					id_ie serial not null,
					ie_data integer,
					ie_grupo char(3),
					ie_total integer,
					ie_valor float
					)
				";
				$rlt = db_query($sql);
			}
	}
?>
