<?php
 /**
  * Pedidos
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2014 - sisDOC.com.br
  * @access public
  * @version v.0.14.06
  * @package Classe
  * @subpackage Classe de Interoperabilidade de dados
 */

 class pedidos
	{
		var $pedido_nr = '';
		var $forn_id='';
		var $item_vetor=array('');
		var $title;
		var $lj_db;
		var $lj='';
		
		var $it_qtda;
		var $it_vlr_rep;
		var $it_vlr_eti;
		var $it_obs;
		
		function le_nrped($nrped)
			{
				global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
				 
				$sql = "select * from pedido
						inner join empresa on ped_empresa = id_e 
						left join fornecedores on ped_fornecedor = fo_codfor
						where id_ped = '".round($nrped)."'";
				$rlt = db_query($sql);
				$this->line = array();
				if ($line = db_read($rlt))
					{
						$this->pedido = $line['ped_nrped'];		
						$this->id = $line['id_ped'];		
						$this->line = $line;
                        return(1);
					} else {
						return(0);
					}
			}
		
		function tabela_de_items()
			{
            	global $base_name,$base_server,$base_host,$base_user;
				$pedido_nr = $this->pedido_nr;
				require("../db_caixa_central.php");
                global $dd;
                $sql = "select * from pedido_item 
                		where pedi_nrped = '".$pedido_nr."'
                		order by id_pedi ";
                $rlt = db_query($sql);
                $it = 0;
                $tt = 0;
								
				$sx = '<table width="100%">';
				$sx .= $this->item_th();
				while ($line = db_read($rlt))
                    {
                    $tt++;	
                    $id_item = $line['id_pedi'];
                    $pedi_ref_item = trim($line['pedi_ref_item']);
                    $pedi_ref_forn = trim($line['pedi_ref_forn']);
                    $pedi_descricao = trim($line['pedi_descricao']);
                    $pedi_quan = trim($line['pedi_quan']);
                    $pedi_vlrunit = trim($line['pedi_vlrunit']);
                    $pedi_tipo = trim($line['pedi_tipo']);
                    $pedi_preco2 = trim($line['pedi_preco2']);
                    
                    $it = $it + 1;
                    $tt_vlr = $tt_vlr + ($pedi_vlrunit*$pedi_quan);
                    $tt_vlr_unit = $tt_vlr_unit + $pedi_vlrunit;
					$tt_qtda = $tt_qtda + $pedi_quan;  
					$tt_vlr_etiq = $tt_vlr_etiq + $pedi_preco2;
                    $pedi_obs = trim($line['pedi_obs']);
                    $pedi_opc = trim($line['pedi_opc']);
                    $pedi_nrdoc = trim($line['pedi_nrdoc']);
                    
                  //  $link  = 'onclick="item_excluir(\''.$id_item.'\',\'item_del\')"';
					$link2 = 'onclick="item_ed(\''.trim($this->pedido_nr).'\',\'item_ed\',\''.trim($dd[80]).'\',\''.trim($dd[81]).'\',\''.$id_item.'\');"';
                    
                    $cl = ' class="tabela00" ';
					$sx .= chr(13).chr(10);
					$sx .= '<tr id="t'.$id_item.'">';
					$sx .= '<td '.$cl.$bg.' width="10px" align="center">'.$it.'</td>';					
					$sx .= '<td '.$cl.$bg.' width="5%">&nbsp;'.$pedi_ref_forn.'</td>';
					$sx .= '<td '.$cl.$bg.' width="5%">&nbsp;'.$pedi_ref_item.'</td>';
					$sx .= '<td '.$cl.$bg.' width="30%"><b>'.$pedi_descricao.'</td>';
					$sx .= '<td '.$cl.$bg.' width="10%" align=center>'.$pedi_quan.'</td>';
					$sx .= '<td '.$cl.$bg.' width="10%" align=right>'.number_format($pedi_vlrunit,2).'</td>';
                    $sx .= '<td '.$cl.$bg.' width="10%" align=right>'.number_format($pedi_vlrunit*$pedi_quan,2).'</td>';
                    $sx .= '<td '.$cl.$bg.' width="10%" align=right><b>'.number_format($pedi_preco2,2).'</td>';
                    $sx .= '<td '.$cl.$bg.' width="10" align ="center">';
				//	$sx .= '<img '.$link2.' height="20px" width="30px" src="../img/edit.png">';
					$sx .= '<img '.$link.' height="20px"  src="../img/delete.png">';
                    $sx .= '</td></tr>';
                    if (strlen($pedi_obs) > 0)
                        {
                            $sx .= '<tr  id="tt'.$id_item.'" class="tabela00" valign="top"><TD colspan="2">&nbsp;</td>';
                            $sx .= '<td colspan="5">';
                            $sx .= troca($pedi_obs,chr(10),'<BR>');
                            $sx .= '</td></tr>';
                        }
					$sx .= '<tr id="ttt'.$id_item.'"><td colspan="9" width="100%"><img height="2px" width="100%" src="../img/nada.jpg"></td></tr>';	
                    }
					$sty = 'style="font-size: large;
							background-color: rgb(0, 153, 161);
							color: rgb(255, 255, 255);
							font-family: RobotoRegular, arial, sans-serif;"';
							
					$sx .= '<tr><td colspan="4" align="center" '.$sty.'>Total :</td>';
					$sx .= '<td align="center" '.$sty.'>'.$tt_qtda.'</td>';
					$sx .= '<td align="right" '.$sty.'>'.number_format($tt_vlr_unit,2).'</td>';
					$sx .= '<td align="right" '.$sty.'>'.number_format($tt_vlr,2).'</td>';
					$sx .= '<td align="right" '.$sty.'>'.number_format($tt_vlr_etiq,2).'</td>';
					$sx .= '<td '.$sty.'> </td>';
					$sx .= '</table>';
					return($sx);
			}
		function item_deletar($id=0)
			{
            	global $base_name,$base_server,$base_host,$base_user;
				$pedido_nr = $this->pedido_nr;
				require("../db_caixa_central.php");
								
				$sql = "select * from pedido_item where id_pedi = ".round($id);
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$pedi = $line['pedi_nrped'];
						$pedi = troca($pedi,'/','_');
						$sql = "update pedido_item set pedi_nrped = '".$pedi."' 
									where id_pedi = ".round($id);
						 
						$rltt = db_query($sql);
					} else {
						return("registro nao localizado");
					}
				return(1);
			}
		function produto_novo_form()
			{
				global $dd;
				$this->lojas();
				$cb_status = $this->produto_status_combo();
				$cb_comissao =  $this->produto_comissao_combo();
				$cb_promo = $this->produto_promo_combo();
				$cb_abcd = $this->produto_abcd_combo();
				$cb_grupo = $this->produto_grupo_combo();
				
				$sx .= '<table width="100%" height="100%" align="center" valign="top">';
				$sx .= '<tr><td class="botao-geral" colspan="2" align="center"><font size="4">Cadastro de produto - '.$this->title[$this->lj].'</font></td></tr>';
				$sx .= '<form id="form_id_produto">';
				$sx .= '<tr><td>Código Fornecedor: </td><td><input size="13%" maxlength="7" id="prod_cod_forn" type="field" name="prod_cod_forn"></input></td></tr>';
				$sx .= '<tr><td>Classificação de grupo: </td><td>'.$cb_grupo.'</td></tr>';
				$sx .= '<tr><td>Descrição: </td><td><input size="13%" maxlength="60" id="prod_desc" type="field" name="prod_desc"></input></td></tr>';
				$sx .= '<tr><td>Informações: </td><td><input id="prod_info" type="text" name="prod_info"></input></td></tr>';
				$sx .= '<tr><td>Preço: </td><td><input size="13%" id="prod_preco" type="field" name="prod_preco"></input></td></tr>';
				$sx .= '<tr><td>Custo: </td><td><input size="13%" id="prod_custo" type="field" name="prod_custo"></input></td></tr>';
				$sx .= '<tr><td>Comissão: </td><td>'.$cb_comissao.'</td></tr>';
				$sx .= '<tr><td>Ativo: </td><td>'.$cb_status.'</td></tr>';
				$sx .= '<tr><td>Desconto Promocional: </td><td>'.$cb_promo.'</td></tr>';
				$sx .= '<tr><td>Classificação ABCD: </td><td>'.$cb_abcd.'</td></tr>';
				$sx .= '<tr><td><input  class="botao-geral" type="button" value="Adicionar" onclick="produto_novo_grava(\''.$this->pedido_nr.'\',\'produto_novo_grava\',\''.$dd[12].'\',\''.$dd[30].'\');">
								<input  class="botao-geral" type="button" value="Fechar" onclick="produto_novo_fechar();">
						</td></tr>';
				$sx .= '</form>';
				$sx .= '</table>
				';
				return(utf8_encode($sx));
			}

		function produto_grupo_combo()
		{
			global $base_name,$base_server,$base_host,$base_user;
			$this->lojas();
			$lj = $this->lj;
			$vld = 0;
			
			if(isset($this->lj_db[$lj]))
			{
				require($this->lj_db[$lj]);
				$pedido_nr = $this->pedido_nr;
				$sql = "select * from produto_grupos";
				$rlt = db_query($sql);
				
				$sx = '<select id="prod_grupo">';	
				while($line = db_read($rlt))
				{
					$vld = 1;
					$sx .= '<option value="'.$line['pg_codigo'].'">'.$line['pg_descricao'].'</option>';
				}
					if($vld==0)
					{
						$sx = 'Não há grupos cadastrados';
						return($sx);
					}
				$sx .= '</select>';
				return($sx);	
			}else{
				echo "<script>alert('Banco de dados da loja não setado!')</script>";
				return(false);
			}	
		}			

		function produto_status_combo()
		{
			$sx = '<select id="prod_ativo">';	
			$sx .= '<option value="1">SIM</option>';
			$sx .= '<option value="2">NÃO</option>';
			$sx .= '</select>';
			return($sx);
		}

		function produto_abcd_combo()
		{
			$sx = '<select id="prod_abcd">';	
			$sx .= '<option value="X">Sem Classificação</option>';
			$sx .= '<option value="A">A</option>';
			$sx .= '<option value="B">B</option>';
			$sx .= '<option value="C">C</option>';
			$sx .= '<option value="D">D</option>';
			$sx .= '</select>';
			return($sx);
		}

		function produto_promo_combo()
		{
			$sx = '<select id="prod_promo">';	
			for ($i=0; $i <= 50; $i++) 
			{ 
				$sx .= '<option value="'.$i.'">'.$i.'</option>';
			}	
			$sx .= '</select>';
			return($sx);
		}
		function produto_comissao_combo()
		{
			$sx = '<select id="prod_comissao">';	
			$sx .= '<option value="0">0%</option>';
			$sx .= '<option value="10">10%</option>';
			$sx .= '<option value="20">20%</option>';
			$sx .= '<option value="25">25%</option>';
			$sx .= '<option value="30">30%</option>';
			$sx .= '<option value="40">40%</option>';
			$sx .= '<option value="50">50%</option>';
			$sx .= '</select>';
			return($sx);
		}
		function le_produto($ped_ref_item){
			global $base_name,$base_server,$base_host,$base_user;
			$this->lojas();
			$lj = $this->lj;
			
			require($this->lj_db[$lj]);
			
			$sql = "select * from produto
					where p_ean13='".trim($ped_ref_item)."'
					";
			$rlt = db_query($sql);
			
			if($line = db_read($rlt)){
				$this->line_prod = $line;
			}
			return(1);
		}
		function le_item_pedido($id_item){
			global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
			
			$sql = 'select * from pedido_item
					where id_pedi='.$id_item.'
					';
			$rlt = db_query($sql);
			
			if($line = db_read($rlt)){
				$this->line_item = $line;
			}
			return(1);		
			
		}	
			
		function item_novo_form($id_item='')
			{
				global $dd;
				
				$verb = 'item_novo_grava';
				
				$cb_item_forn = $this->item_fornecedor_combo();
				
				$sx .= '<table width="100%" height="100%" align="center" valign="top">';
				$sx .= '<tr><td class="botao-geral" colspan="2" align="center"><font size="4">Cadastro de ítem</font></td></tr>';
				$sx .= '<form id="form_id_item">';
				$sx .= '<tr><td>Produto: </td><td>'.$cb_item_forn.'</td></tr>';
				$sx .= '<tr><td>Quantidade: </td><td><input size="13%" id="qtda" type="field" name="qtda" value="'.$val_qtda.'"></input></td></tr>';
				$sx .= '<tr><td>Valor Repasse: </td><td><input size="14%" id="vlr_rep" type="text" name="vlr_rep" value="'.$val_vlr_rep.'"></input></td></tr>';
				$sx .= '<tr><td>Valor Etiqueta: </td><td><input size="15%" id="vlr_eti" type="text" name="vlr_eti" value="'.$val_vlr_eti.'"></input></td></tr>';
				$sx .= '<tr><td>Observação: </td><td><textarea size="15%"  id="obs" rows="5" cols="40"  maxlength="200" name="obs" value="'.$val_obs.'"></textarea></td></tr>';
				$sx .= '<tr><td><input  class="botao-geral" type="button" value="Adicionar" onclick="item_novo_grava(\''.$this->pedido_nr.'\',\''.$verb.'\',\''.$dd[12].'\',\''.$dd[30].'\',\''.$dd[82].'\');">
								<input  class="botao-geral" type="button" value="Fechar" onclick="item_novo_fechar();"></td></tr>';
				$sx .= '</form>';
				$sx .= '</table>
				';
				return(utf8_encode($sx));
				
			}
		function item_fornecedor_combo()
		{
			global $base_name,$base_server,$base_host,$base_user;
			$this->lojas();
			$lj = $this->lj;
			$vld = 0;
		
			if(isset($this->lj_db[$lj]))
			{
					
				require($this->lj_db[$lj]);
				$pedido_nr = $this->pedido_nr;
				$sql = "select * from produto
						where p_cod_fornecedor ='".$this->forn_id."'
						";
				$rlt = db_query($sql);
				
				$sx = '<select id="cod_prod">';	
				while($line = db_read($rlt))
				{
					$vld = 1;
					$sx .= '<option value="'.$line['p_codigo'].'">('.$line['p_fornecedor'].') - '.$line['p_descricao'].' - R$ '.$line['p_custo'].'</option>';
				}
					if($vld==0)
					{
						$sx = 'Fornecedor sem produto cadastrado';
						return($sx);
					}
				$sx .= '</select>';
				return($sx);	
			}else{
				echo "<script>alert('Banco de dados da loja não setado!')</script>";
				return(false);
			}	
			
			
		}			
		function mostra_item()
            {
            	global $dd;
            	$pedido_nr = $this->pedido_nr;
				$sx = '<div id="item_novo"
						style="
							padding: 0px;
							width: 0px;
							height: 0px;
							border: 1px solid #00000;
							top: 5px;
							left: 5px;
							background-color: #FFFFFF;
							z-index:999;
							position: absolute;
							"
							>
							</div>';
				$sx .= '<div id="produto_novo"
						style="
							padding: 0px;
							width: 0px;
							height: 0px;
							border: 1px solid #00000;
							top: 5px;
							left: 5px;
							background-color: #FFFFFF;
							z-index:1000;
							position: absolute;
							"
							>
							</div>';
				
				/* recupera os items */
				$sx .= '<div id="lista_de_itens">';
				$sx .= $this->tabela_de_items();
				$sx .= '</div>';
	 			$sx .='<div align="center">
							<input style="font-size:15px" class="botao-geral" size="20" title="CTRL+ALT+A" type="button" value="Atualizar" onclick="mostra_produtos( \''.trim($this->pedido_nr).'\', \'mostra_itens\',\''.$dd[12].'\',\''.$dd[30].'\');"></font>
							<input style="font-size:15px" class="botao-geral" title="CTRL+ALT+N" type="button" value="Novo item" onclick="item_novo(\''.trim($this->pedido_nr).'\',\'item_novo\',\''.trim($dd[12]).'\',\''.trim($dd[30]).'\');">
							<input style="font-size:15px" class="botao-geral" title="CTRL+ALT+P" type="button" value="Novo produto" onclick="produto_novo(\''.trim($this->pedido_nr).'\',\'produto_novo\',\''.trim($dd[12]).'\',\''.trim($dd[30]).'\');">
						</div>';
						    
                return($sx);
            }	
			function item_th()
			{
				$sty = 'style="font-size: large;
							background-color: rgb(0, 153, 161);
							color: rgb(255, 255, 255);
							font-family: RobotoRegular, arial, sans-serif;"';
				$sx = '<tr>
						<th '.$sty.' align="center">Item</th>
						<th '.$sty.' align="center">Ref.Forn.</th>
						<th '.$sty.' align="center">Ref.Interna</th>
						<th '.$sty.' align="left">Descrição</th>
						<th '.$sty.' align="center">Quantidade</th>
						<th '.$sty.' align="right">Vlr.Unit.</th>
						<th '.$sty.' align="right">Sub-total.</th>
						<th '.$sty.' align="right">Vlr.Etiquetas</th>
						<th '.$sty.' align="center">Ações</th>
						</tr>
						';
				return($sx);		
			}

			function menu($id_ped,$cod_forn,$lj)
			{
				global $dd;
				$link1 = 'HREF="ped_novo_ed.php?dd0='.$id_ped.'&dd50=0&dd12='.$lj.'&dd30='.$cod_forn.'"';
				if(strlen(trim($dd[0]))>0)
				{
					$link2 = 'HREF="ped_novo_ed.php?dd0='.$id_ped.'&dd50=2&dd12='.$lj.'&dd30='.$cod_forn.'"';
					$link3 = 'HREF="ped_novo_ed.php?dd0='.$id_ped.'&dd50=1&dd12='.$lj.'&dd30='.$cod_forn.'"';	
				}
				$sx = '<table cellpadding="0" cellspacing="2" width="100%" class="lt1">
						<TR align="center" bgcolor="#c0c0c0">
							<TD class="botao-geral" width="33%"><A '.$link1.'><font size="4" color="#FFFFFF">Dados de Faturamento</A></TD>
							<TD class="botao-geral" width="33%"><A '.$link2.'><font size="4" color="#FFFFFF">Itens</A></TD>
							<TD class="botao-geral" width="34%"><A '.$link3.'><font size="4" color="#FFFFFF">Resumo</A></TD>
						</TR>
						</table>';
				return($sx);
			}
			/*Grava produto novo*/
			function produto_novo_grava()
			{
				global $base_name,$base_server,$base_host,$base_user,$dd,$include;
				$this->lojas();
				require($this->lj_db[$dd[12]]);
				
				
				$prod_ean13 = $this->gera_ean13_produto($dd[12]);
				$prod_cod_produto= $dd[2];
				$prod_grupo= $dd[3];
				$prod_desc = $dd[4];
				$prod_info = $dd[5];
				$prod_preco = $dd[6];
				$prod_custo = $dd[7];
				$prod_comissao = $dd[8];
				$prod_ativo = $dd[9];
				$prod_promo = $dd[10];
				$prod_abcd = $dd[11];
				$prod_forn = $dd[30];
				$prod_marcap = '0'; 
				$prod_class_2 = '0';
				$vld = $this->valida_codigo_produto($prod_cod_produto, $prod_forn);
				if($vld==0)
				{
					echo $sql = "insert into produto 
								(p_ean13, p_descricao,
					  			 p_preco, p_ativo, p_comissao,
					  			 p_custo, p_fornecedor, p_marcap,
					  			 p_cod_fornecedor, p_content, p_class_1,
					  			 p_class_2, p_promo, p_class_abcd
					  			 )values(
					  			 '".$prod_ean13."','".$prod_desc."',
					  			 ".$prod_preco.",".$prod_ativo.",".$prod_comissao.",
					  			 ".$prod_custo.",'".$prod_cod_produto."',".$prod_marcap.",
					  			 '".$prod_forn."','".$prod_info."','".$prod_grupo."',
					  			 '".$prod_class_2."',".$prod_promo.",'".$prod_abcd."'
							  )";
	
					echo "<script>alert('".$prod_ean13."-".$prod_desc."-".$prod_preco."-".$prod_ativo."-".
					$prod_comissao."-".$prod_custo."-".$prod_cod_produto."-".$prod_marcap."-".
					$prod_forn."-".$prod_info."-".$prod_grupo."-".$prod_class_2."-".$prod_promo."-".$prod_abcd."');</script>";	  
					$rlt = db_query($sql);
					$this->updatex();
					return(1);
				}else{
					echo utf8_encode("<script>alert('Já existe um produto com o código ".$prod_cod_produto." cadastrado neste fornecedor. Dados não salvos, tente novamente!')</script>");
					return(0);
				}
			}

	function valida_codigo_produto($cod_prod,$cod_forn)
	{
		global $base_name,$base_server,$base_host,$base_user,$dd;
		$this->lojas();
		require($this->lj_db[$dd[12]]);
		$sql = "select * from produto
				where p_fornecedor='".$cod_prod."' and
					  p_cod_fornecedor='".$cod_forn."'	
		";
		$rlt = db_query($sql);
		if($line = db_read($rlt))
		{
			return(1);
		}else{
			return(0);	
		}
		
	}	
	function updatex()
		{
				$dx1 = 'p_codigo';
				$dx2 = 'p';
				$dx3 = 6;
				$sql = "update produto 
				set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) 
				where (length(trim(".$dx1.")) < ".$dx3.") or 
				(".$dx1." isnull);";
				$rlt = db_query($sql);
				return(1);
		}
	function updatex_pedido()
		{
				$dx1 = 'ped_nrped';
				$dx2 = 'ped';
				$dx3 = 5;
				$sql = "update pedido 
				set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."/".date('y')."')) 
				where (length(trim(".$dx1.")) < ".$dx3.") or 
				(".$dx1." isnull);";
				$rlt = db_query($sql);
				return(1);
		}	
	function gera_ean13_produto($lj)
	{
		global $base_name,$base_server,$base_host,$base_user;
		$this->lojas();
		
		require($this->lj_db[$lj]);
		$sql = 'select * from produto  
				order by p_codigo desc limit 1
		';
		$rlt = db_query($sql);
		if($line=db_read($rlt))
		{
			$sx = $this->sigla[$lj].substr('000000'.($line['p_codigo']+1),-6);
		}		
		return($sx);
	}
		
	
				
	function item_novo_grava()
	{
		global $base_name,$base_server,$base_host,$base_user,$dd;
		$this->lojas();
		require($this->lj_db[$dd[12]]);
		$prod = new produto;
		//echo '<script>alert("'.$this->forn_id.'")</script>';
	
		$prod->le($dd[2]);
		
		$pedi_ped = substr($dd[0],0,5); 
		$pedi_ref_item = $prod->p_ean13; 
		$pedi_ref_forn = $prod->p_fornecedor;
		$pedi_descricao =$prod->p_descricao;
		$pedi_nrped =trim($dd[0]);
		$pedi_quan =$dd[3];
		$pedi_vlrunit =$prod->p_custo;
		$pedi_subtotal =($dd[3]*$pedi_vlrunit);
		
		$pedi_tipo ='0000';
		$pedi_preco2 =$dd[5];
		$pedi_obs =$dd[7];
		$pedi_opc =$prod->p_comissao;
		
		$pedi_proc =0;
		
		require('../db_caixa_central.php');
		
		echo $sql = "insert into pedido_item ( pedi_ped ,pedi_ref_item, pedi_ref_forn, 
											  pedi_descricao, pedi_subtotal, pedi_nrped,
											  pedi_quan, pedi_vlrunit, pedi_tipo,
											  pedi_preco2, pedi_obs, pedi_opc,
											  pedi_proc 
											  )values(".
											    $pedi_ped.",'". $pedi_ref_item."','".$pedi_ref_forn."',
												'".$pedi_descricao."',".$pedi_subtotal.",'".$pedi_nrped."',
												".$pedi_quan.",".$pedi_vlrunit.",'".$pedi_tipo."',
												".$pedi_preco2.",'".$pedi_obs."','".$pedi_opc."',
												".$pedi_proc."
											  )";
		$rlt = db_query($sql);
		return(1);
	}
	
/*					
	function item_update($id_item)
	{
		global $base_name,$base_server,$base_host,$base_user,$dd;
		$this->lojas();
		require($this->lj_db[$dd[80]]);
		$prod = new produto;
		
		$prod->le($dd[2]);
		
		$pedi_ped = substr($dd[0],0,5); 
		$pedi_ref_item = $prod->p_ean13; 
		$pedi_ref_forn = $prod->p_fornecedor;
		$pedi_descricao =$prod->p_descricao;
		$pedi_nrped =trim($dd[0]);
		$pedi_quan =$dd[3];
		$pedi_vlrunit =$prod->p_custo;
		$pedi_subtotal =($dd[3]*$pedi_vlrunit);
		
		$pedi_tipo ='0000';
		$pedi_preco2 =$dd[5];
		$pedi_obs =$dd[7];
		$pedi_opc =$prod->p_comissao;
		
		$pedi_proc =0;
		
		require('../db_caixa_central.php');
		echo '<script>alert("'.$id_item.'")</script>';
		if(isset($id_item))
		{
			echo '<script>alert("'.$id_item.'222")</script>';
		if(isset($id_item))
			echo $sql = "update pedido_item set pedi_ped =".$pedi_ped.", 
												pedi_ref_item='".trim($pedi_ref_item)."', 
												pedi_ref_forn='".trim($pedi_ref_forn)."', 
												pedi_descricao='".trim($pedi_descricao)."', 
												pedi_subtotal=".trim($pedi_subtotal).", 
												pedi_nrped='".trim($pedi_nrped)."',
												pedi_quan=".trim($pedi_quan).", 
												pedi_vlrunit=".trim($pedi_vlrunit).", 
												pedi_tipo='".trim($pedi_tipo)."',
												pedi_preco2=".trim($pedi_preco2).", 
												pedi_obs='".trim($pedi_obs)."', 
												pedi_opc='".trim($pedi_opc)."',
												pedi_proc=".trim($pedi_proc)."
												where id_pedi=".trim($id_item)."
												";
			$rlt = db_query($sql);
		}else{	
			echo '<script>alert("Não gravado, item a ser alterado não setado")</script>';								  
		}
										  
		return(1);
	}
 */ 
	function lojas()
	{
			//Nome das lojas
			$this->title[0] ='Joias'; 	
			$this->title[1] ='Modas';
			$this->title[2] ='Óculos';
			$this->title[3] ='Use Brilhe';
			$this->title[4] ='Sensual';
			$this->title[5] ='Modas Express';
			$this->title[6] ='Joias Express';
			$this->title[7] ='TST';
			
			$this->title['J'] ='Joias'; 	
			$this->title['M'] ='Modas';
			$this->title['O'] ='Óculos';
			$this->title['C'] ='Use Brilhe';
			$this->title['S'] ='Sensual';
			$this->title['F'] ='Modas Express';
			$this->title['G'] ='Joias Express';
			$this->title['T'] ='TST';
			
			//BD das lojas
			$this->lj_db[0] ='../db_fghi_206_joias.php'; 
			$this->lj_db[1] ='../db_fghi_206_modas.php';
			$this->lj_db[2] ='../db_fghi_206_oculos.php';
			$this->lj_db[3] ='../db_fghi_206_ub.php';
			$this->lj_db[4] ='../db_fghi_206_sensual.php';
			$this->lj_db[5] ='../db_fghi_206_express.php';
			$this->lj_db[6] ='../db_fghi_206_express_joias.php';
			$this->lj_db[7] ='../db_fghi_206_TST.php';
			//Sigla das empresas
			$this->sigla[0] ='J'; 	
			$this->sigla[1] ='M';
			$this->sigla[2] ='O';
			$this->sigla[3] ='C';
			$this->sigla[4] ='S';
			$this->sigla[5] ='F';
			$this->sigla[6] ='G';
			$this->sigla[7] ='T';
			
			$this->sigla[J] = 0; 	
			$this->sigla[M] = 1;
			$this->sigla[O] = 2;
			$this->sigla[C] = 3;
			$this->sigla[S] = 4;
			$this->sigla[F] = 5;
			$this->sigla[G] = 6;
			$this->sigla[T] = 7;
			
			//ID das empresas
			$this->empresa[0] ='1'; 
			$this->empresa[1] ='2';
			$this->empresa[2] ='8';
			$this->empresa[3] ='10';
			$this->empresa[4] ='16';
			$this->empresa[5] ='2';
			$this->empresa[6] ='1';
			$this->empresa[7] ='4';
	}
	function lista_lojas()
	{
		global $http;
		$this->lojas();
		$sx = '<h1>Selecione a loja em que o pedido será gerado</h1>';
		$sx .= '<center>';
		for ($r=0;$r <= 7;$r++)
			{
				
				$sx .= '<A HREF="ped_novo.php?dd80='.$r.'">';
				$sx .= '<img title="'.$this->title[$r].'"width="60px" src="'.$http.'/img/icone_lj_'.$r.'a.png" 
									onmouseover="$(this).attr(\'src\',\''.$http.'img/icone_lj_'.$r.'.png\');" 
									onmouseout="$(this).attr(\'src\',\''.$http.'img/icone_lj_'.$r.'a.png\');"
							width="100" border=0>';
				$sx .= '</A>';
				$sx .= '&nbsp;';
			}
		
		$sx .= '<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>';
		return($sx);
	}

        function busca_pedido_aberto($cod_forn,$lj)
        {
        	$this->lojas();
        	$sql = "select * from pedido ";
			$sql .= " where ped_fornecedor = '".substr($cod_forn,-4)."' and 
					  		ped_status = 'T'";
					  
			/*$sql .=	 " and ped_loja = '".$this->sigla[$lj]."'";*/
			 
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
				$nrped = $line['ped_nrped'];
				$id_ped = $line['id_ped'];
				$this->le_nrped($nrped);
				}

		    return($id_ped);
        }

 		function lista_pedidos_fornecedor($fo_codigo,$lj)
        {
        	$this->lojas();
        	$sx ='<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<th class="botao-geral">Ped.</th>
						<th class="botao-geral">Data</th>
						<th class="botao-geral">Ítens</th>
						<th class="botao-geral">Vlr. Total</th>
						<th class="botao-geral">Status</th>
					</tr>
					<tr>
					';
		  	$sql = "select * from pedido 
					where ped_fornecedor = '".$fo_codigo."' and 
						  ped_loja = '".$this->sigla[$lj]."'	 
					order by ped_nrped desc 
					limit 20 ";
			$rlt = db_query($sql);
			$this->temp = 0;
			while ($line = db_read($rlt))
			{
				$link = '<A HREF="javascript:newxy2(\'pedido_mostra.php?dd1='.trim($line['ped_nrped']).chr(39).',800,800);">';
				if ($line['ped_status']=='T') 
					{ 
					$this->temp = $temp++; 
					$link = '<A HREF="ped_novo_ed.php?dd0='.trim($line['id_ped']).'&dd30='.trim($line['ped_fornecedor']).'&dd12='.trim($lj).'&dd80='.trim($lj).'">';
					}
				$sx .= '<tr '.coluna().'>';
				$sx .= '<td class="tabela00" align="center">'.$link.$line['ped_nrped'].'</td>';
				$sx .= '<td class="tabela00" align="center">'.$link.$line['ped_data'].'</td>';
				$sx .= '<td class="tabela00" align="center">'.$link.$line['ped_items'].'</td>';
				$sx .= '<td class="tabela00" align="right">'.$link.number_format($line['ped_valortotal'],2).'</td>';
				$sx .= '<td class="tabela00" align="center">'.$link.$line['ped_status'].'</td>';
				$sx .= '</tr>';
			}
			$sx .= '</tr></table>';
		    return($sx);
        }

 		function botao_novo_pedido($cod_forn,$lj)
        {
        	
		$bb1 = 'Abrir novo pedido';
		if ($this->temp == 0)
			{
			$sx .= '<div width="100%">';	
			$sx .= '<BR><CENTER>';
			$sx .= '<form method="post" action="ped_novo_ed.php?dd12='.$lj.'&dd30='.$cod_forn.'">';
			$sx .= '<input type="submit" name="acao" value="'.$bb1.'" style="width:300; height:40;">';
			$sx .= '</form>';
			$sx .= '</div>';
		     
		} else {
			$sx .= 'Existe '.$this->temp.' pedidos temporários deste cliente, não é possível abrir um novo';
		}
        	return($sx);
        }
		
		function cp_faturamento()
		{
			global $user,$lj,$dd;	
			$cp = array();
			
			/*0*/array_push($cp,array('$H8','id_ped','',false,True));
			/*1*/array_push($cp,array('$Q e_nome:id_e:select * from empresa where e_ativo=1','ped_empresa','Empresa',True,True,''));
			/*2*/array_push($cp,array('$D','ped_previsao','Data entrega',false,True));
			/*3*/array_push($cp,array('$T70:3','ped_obs','Observação',false,True));
			/*4*/array_push($cp,array('$O CIF:CIF (Fornecedor paga)&FOB:FOB (Frete a pagar)&1/2:Dividido&NA:Não aplicado','ped_frete','Frete',True,True,''));
			/*5*/array_push($cp,array('$Q fpt_descricao:id_fpt:select * from forma_paga_tipo order by fpt_descricao','ped_fp_tipo','Forma pagamento',True,True,''));
			/*6*/array_push($cp,array('$Q fpv_descricao:id_fpv:select * from forma_paga_vezes order by fpv_descricao','ped_fp_vezes','Tipo pagamento',True,True,''));
			/*7*/array_push($cp,array('$Q trans_descricao:trans_descricao:select * from transportadoras order by trans_descricao','ped_fretetransportadora','Transportadora',True,True,''));
			/*8*/array_push($cp,array('$HV','ped_login',$user->user_log,false,True,''));
			/*9*/array_push($cp,array('$HV','ped_status','T',false,True,''));
			/*10*/array_push($cp,array('$HV','ped_data',date('Y-m-d'),false,True,''));
			/*11*/array_push($cp,array('$HV','ped_hora',date('H:i'),false,True,''));
			/*12*/array_push($cp,array('$HV','ped_loja',$this->sigla[$lj],false,True,''));
			/*13*/array_push($cp,array('$HV','ped_fornecedor',$dd[30],false,True,''));
			
			return($cp);
		}  
		
		function dados_faturamento()
		{
			global $dd;
			$nrped = $dd[0];
			$this->le_nrped($nrped);
			$emp = $this->line['ped_empresa'];
			$dt = $this->line['ped_previsao'];
			$dt = substr($dt,-2).'/'.substr($dt,4,2).'/'.substr($dt,0,4);
			if($this->le_nrped($nrped))
			{
				$this->tp = $this->line['ped_fp_tipo'];
				$this->fp = $this->line['ped_fp_vezes'];
				$sx ='<center><table class="tabela01" width="90%">
				<tr><td class="tabela01" width="300px">Empresa pagadora:</td>
					<td class="tabela01" width="300px">'.$this->empresa($emp).'</td><tr>
				<tr><td class="tabela01" width="300px">Data de entrega:</td>
					<td class="tabela01" width="300px">'.$dt.'</td><tr>
				<tr><td class="tabela01" width="300px">Observações:</td>
					<td class="tabela01" width="300px">'.$this->line['ped_obs'].'</td><tr>
				<tr><td class="tabela01" width="300px">Frete:</td>
					<td class="tabela01" width="300px">'.$this->line['ped_frete'].'</td><tr>
				<tr><td class="tabela01" width="300px">Forma de pagamento:</td>
					<td class="tabela01" width="300px">'.$this->forma_pagamento_vezes().'</td><tr>				
				<tr><td class="tabela01" width="300px">Tipo de pagamento:</td>
					<td class="tabela01" width="300px">'.$this->forma_pagamento_tipo().'</td><tr>
				<tr><td class="tabela01" width="300px">Transportadora:</td>
					<td class="tabela01" width="300px">'.$this->line['ped_fretetransportadora'].'</td><tr>
				</table>';	
					
				return($sx);				
			}else{
				return(0);
			}
						
		}

		function resumo_pedido()
		{
			global $dd;
			$nrped = $this->line['ped_nrped'];
			$forn = new fornecedor;
			$forn->le3($dd[30]);
			$sx = '<table width="100%">
					<tr><td class="botao-geral" width="100%">Fornecedor</td></tr>
					<tr><td>'.$forn->mostra().'</td></tr>
					<tr><td class="botao-geral" width="100%">Dados Faturamento</td></tr>
					<tr><td>'.$this->dados_faturamento().'</td></tr>
					<tr><td class="botao-geral" width="100%">Itens</td></tr>
					<tr><td>'.$this->itens_total($nrped).'</td></tr>
					<tr><td><br><br><br></td></tr>
					<tr><td align="center">
						<input style="width:300px; 
									  height:50px; 
									  font-size:20px; 
									  background-color:green" 
									  type="button" 
								class="botao-geral" 
								name="Concluir" 
								value="CONCLUIR" 
								onclick="concluir_pedido(\''.$nrped.'\',\''.$forn->fantasia.'\')"></input>
						<input style="width:300px; 
										height:50px; 
										font-size:20px; 
										background-color:red" 
								type="button" 
								class="botao-geral" 
								name="Cancelar" 
								value="CANCELAR" 
								onclick="cancelar_pedido(\''.$nrped.'\')"></input>
					</td></tr>
					</table>
					<div id="concluir_pedido"></div>
					<div id="cancelar_pedido"></div>
					';
			return($sx);
		}
		
		function itens_total($nrped='')
		{
			$sql = "select count(*) as produtos, sum(pedi_quan) as itens, sum(pedi_subtotal) as total from pedido_item
					where pedi_nrped='".$nrped."'
			";
			$rlt = db_query($sql);
			
			while($line = db_read($rlt))
			{
				$sx = '<center><table class="tabela01" width="90%">
						<tr><td class="tabela01" align="center">Produtos</td>
							<td class="tabela01" align="center">Quantidade de itens</td>
							<td class="tabela01" align="center">Total faturado</td></tr>
						<tr><td class="tabela01" align="center">'.$line['produtos'].'</td>
							<td class="tabela01" align="center">'.$line['itens'].'</td>
							<td class="tabela01" align="center">'.number_format($line['total'],2).'</td></tr>
						</table>';
			}
			return($sx);
		}

 		function forma_pagamento_tipo()
        {
        	global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
                
            $sql = "select * from forma_paga_tipo where id_fpt = ".$this->tp;
            $rlt = db_query($sql);
                if ($line = db_read($rlt))
                    {
                         $tp=$this->tp = $line['fpt_descricao'];
                    } 
            return($tp);    
            
        }

        function forma_pagamento_vezes()
        {
        		global $base_name,$base_server,$base_host,$base_user;
				require("../db_caixa_central.php");
                
                $sql = "select * from forma_paga_vezes where id_fpv = ".$this->fp;
                $rlt = db_query($sql);
                if ($line = db_read($rlt))
                    {
                        $fp= $this->fp = $line['fpv_descricao'];
                    } 
                    
            return($fp);    
            
        }
		function empresa($id)
		{
			$sql = 'select * from empresa 
					where id_e='.$id.' 
			';
			$rlt = db_query($sql);
			
			while($line = db_read($rlt))
			{
				$sx = $line['e_nome'];
			}
			
			return($sx);
		}
		
		function fecha_pedido($nrped,$forn_nome)
		{
			global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
			
			$sql1 = "select sum(pedi_quan) as itens, 
								 sum(pedi_subtotal) as total 
						  from pedido_item
						  where pedi_nrped='".$nrped."'
			";
			$rlt1 = db_query($sql1);
			if($line1 = db_read($rlt1))
			{
				$ped_itens = $line1['itens'];
				$ped_valortotal = $line1['total'];
				
				if(isset($nrped))
				{
					echo $sql = "update pedido 
							set ped_status='F',
								ped_items=".$ped_itens.",
								ped_valortotal=".$ped_valortotal.",
								ped_nomefornecedor='".trim($forn_nome)."' 
							where ped_nrped='".trim($nrped)."'
							";
					$rlt = db_query($sql);
					return(1);
				}else{
					return(0);	
				}
			}else{
				return(0);
			}	
		}
		
		function valida_dados_pedido($nrped)
		{
			
			$this->le_nrped($nrped);
			$sx=1;
			if(strlen(trim(round($this->line['ped_data'])))==0){ $sx.='Data\n';}
			if(strlen(trim(round($this->line['ped_hora'])))==0){ $sx.='Hora\n';}
			if(strlen(trim(round($this->line['ped_status'])))==0){ $sx.='Status\n';}
			if(strlen(trim(round($this->line['ped_previsao_1'])))==0){ $sx.='Previsão1\n';}
			if(strlen(trim(round($this->line['ped_items'])))==0){ $sx.='Itens\n';}
			if(strlen(trim(round($this->line['ped_valortotal'])))==0){ $sx.='Valor Total\n';}
			if(strlen(trim(round($this->line['ped_nrped'])))==0){ $sx.='Nrped\n';}
			if(strlen(trim(round($this->line['ped_login'])))==0){ $sx.='Login\n';}
			if(strlen(trim(round($this->line['ped_frete'])))==0){ $sx.='Frete\n';}
			if(strlen(trim(round($this->line['ped_loja'])))==0){ $sx.='Loja do produto\n';}
			if(strlen(trim(round($this->line['ped_nomefornecedor'])))==0){ $sx.='Nome do fornecedor\n';}
			if(strlen(trim(round($this->line['ped_fretetransportadora'])))==0){ $sx.='Frete transportadora\n';}
			if(strlen(trim(round($this->line['ped_empresa'])))==0){ $sx.='Empresa pagadora\n';}
			if(strlen(trim(round($this->line['ped_fp_tipo'])))==0){ $sx.='Tipo de pagamento\n';}
			if(strlen(trim(round($this->line['ped_fp_vezes'])))==0){ $sx.='Forma do pagamento\n';}
			if(strlen(trim(round($this->line['ped_fornecedor'])))==0){ $sx.='Fornecedor\n';}
			if(strlen(trim(round($this->line['ped_chegada'])))==0){ $sx.='Chegada\n';}
			if(strlen(trim(round($this->line['id_ped'])))==0){ $sx.='Id do pedido\n';}
			if(strlen(trim(round($this->line['ped_previsao'])))==0){ $sx.='Previsão\n';}
			
			if(strlen(trim($sx))==1)
			{
				return($sx);
			}else{
				return($sx);
			}
		}

 		function cancelar_pedido($nrped)
 		{
 			global $base_name,$base_server,$base_host,$base_user;
			require("../db_caixa_central.php");
			if(isset($nrped))
			{
				$sql = "update pedido 
							set ped_status='X' 
							where 	ped_nrped='".trim($nrped)."'
							";
				$rlt = db_query($sql);
				return(1);
			}else{
				return(0);
			}
 		}

}
?>
