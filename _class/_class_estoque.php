<?php
class estoque
	{
		var $db;
	    /*Insere produto no estoque*/         
		function inserir_produto_estoque($id_prod,$ref_forn,$forn,$venc,$qtd,$tam,$user,$preco,$custo,$comissao,$earn,$pedido)
    		{
    			
			    $dt=date('Ymd');    
			    if(strlen($venc)<8){$venc=0;} 
               $sql="insert into produto_estoque(
				        pe_data,pe_status, pe_vlr_venda,
                        pe_vlr_custo,pe_tipo_entrada,pe_nrped,
                        pe_ean13,pe_comissao,pe_produto,
                        pe_fornecedor,pe_lastupdate,pe_tam,
                        pe_doc,pe_vlr_vendido,pe_desconto,
                        pe_inventario,pe_promo,pe_fornecimento,
                        v_ref,pe_log_eti,
                        pe_validade) 
                    values (
                        $dt,'@',$preco,
                        $custo,'C','$pedido',
                        '$earn',$comissao,'$id_prod',
                        $forn,$dt,'$tam',
                        0,0,0,
                        1,0,0,
                        1,'$user',
                        $venc)";
                        
               $rlt = db_query($sql);
              return(1);
     		}
     	/*Atualiza tabela pedido conforme quantidade estocada*/	
     	function atualiza_pedido($pedido,$ref_forn,$qtd)
     	  {
     	      global $base_name,$base_server,$base_host,$base_user, $dd;
     	      require("../db_caixa_central.php");
     	      
     	      $sql = "update pedido_item set 
     	                  pedi_proc = pedi_proc+".$qtd."
                      where 
                          id_pedi='".$pedido."'";
              $rlt = db_query($sql);
              return(1);  
     	  }
     	  /*Atualiza tabela pedido conforme quantidade estocada*/	
     	function atualiza_estoque_atual($lj,$ean)
     	  {
     	      global $base_name,$base_server,$base_host,$base_user, $dd;
     	      require("../db_fghi.php");
     	      
     	     echo  $sql = "update estoque_atual set 
     	                  esta_atual = ".$ean."
                      where 
                          esta_loja='".$lj."'";
              $rlt = db_query($sql);
              return(1);  
     	  }
     	  
     	 function lista_produtos_estoque($pedido,$forn){
     	    $sql = "select * from produto_estoque
     	          where pe_nrped='$pedido' and
     	                pe_fornecedor='$forn' order by id_pe";         
     	    $rlt = db_query($sql); 
            $sx = '<table align="center" width="95%" class="tabela00" cellpadding=1 cellspacing=0>
                   <TR>
                   <TH>Emissão
                   <TH>Pedido
                   <TH>Descrição
                   <TH>EAN13
                   <TH>Validade
                   <TH>Tamanho
                   <TH>Custo
                   <TH>Venda
                   <TH>Status
                ';
            $tot = 0;
            while ($line = db_read($rlt))
                  {
                      $sql2="select * from produto where p_codigo='".$line['pe_produto']."'";
                      $rlt2=db_query($sql2);
                      $line2=db_read($rlt2);                           
                      $tot++;
                      $sx .= '<TR>';
                      $sx .= '<TD class="tabela01" align="center">'.stodbr($line['pe_data']);
                      $sx .= '<TD class="tabela01" align="center">'.$line['pe_nrped'];
                      $sx .= '<TD class="tabela01" align="center">'.$line2['p_descricao'];
                      $sx .= '<TD class="tabela01" align="center">'.$line['pe_ean13'];
                      $sx .= '<TD class="tabela01" align="center">'.stodbr($line['pe_validade']);
                      $sx .= '<TD class="tabela01" align="center">'.$line['pe_tam'];
                      $sx .= '<TD class="tabela01" align="right">'.number_format($line['pe_vlr_custo'],2);
                      $sx .= '<TD class="tabela01" align="right">'.number_format($line['pe_vlr_venda'],2);
                      $sx .= '<TD class="tabela01" align="center">'.$line['pe_status'];
                  }
            $sx .= '<TR><TD class="tabelaT" colspan=10>Total de '.$tot.' produtos';
            $sx .= '</table>';
            return($sx);      
     	 } 
     	  
     	/*Acha a sequencia do codigo de barras - sem o DV*/ 
     	function ultimo_registro()
     	  {
     	   global $base_name,$base_server,$base_host,$base_user, $dd;
           require($this->db);
              
     	   $sql="select max(pe_ean13) from produto_estoque";         
     	   $rlt= db_query($sql);
     	   $line=db_read($rlt);    
     	   $sx=(substr($line['max'],0,11))+1;
     	   return($sx); 
     	}  	
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
						and pe_lastupdate <= $data
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
				
				$sx = '<table width="100%" class="lt1" cellpadding=1 cellspacing=0>
						<TR><TH>EAN13
						<TH>Descriï¿½ï¿½o
						<TH>Ult. Movimentaï¿½ï¿½o
						<TH>Status
				';
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$sx .= '<TR>';
						$sx .= '<TD align="center">'.$line['pe_ean13'];
						$sx .= '<TD>'.$line['p_descricao'];
						$sx .= '<TD align="center">'.stodbr($line['pe_lastupdate']);
						$sx .= '<TD align="center">'.$line['pe_status'];
						$sx .= '<TD align="right">'.number_format($line['pe_vlr_custo'],2);
					}
					$sx .= '<TR><TD colspan=10>Total de '.$tot.' produtos nï¿½o localizados';
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
				$sx .= '	<TH colspan=2>Nï¿½o localizadas
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
				$sx .= '<TR><TH>Total Peï¿½as<TH>Valor do Estoque';
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
