<?php
 /**
  * Indicações
  * @author Willian Fellipe Laynes  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2013 - sisDOC.com.br
  * @access public
  * @version v.0.13.51
  * @package Classe
  * @subpackage Indicacoes
 */
class indicacoes
	{
	var $ttln=0;
	var $tt_ind=0;
	var $cliente;
	var $obj;
	var $produto;
	var $classe;
	var $vld=1;
	var $consultora; 
	function link_indicacoes($id)
		{
			global $base_name,$base_server,$base_host,$base_user,$tab_max,$rel;
			$ind=$this->lista_indicados($id);
			require("../db_fghi_206_cadastro.php");
			//$onclick = 'onclick="newxy2(\'indicacoes_baixa.php?dd1='.$id.'\',600,400);"';
			if(strlen(trim($ind))>0)
			{
				$png='logo_amigas_verde.png';
				$st=' style="background-color:#FFEC8B; 
							text-align:center;
							border-style:solid;
							border-color:#FFD700;
							border-radius: 39px;
						    -webkit-border-radius: 15px;
						    -moz-border-radius: 15px;
						    -ms-border-radius: 15px;
						    -o-border-radius: 15px;
						    color:#0f9d88;
							"';
				$img = '<img height="40px" src="../img/'.$png.'" '.$onclick.' >';
				$tips=tips($img,'<div style="background-color:#FFFFFF; height:'.(30+(25*$this->tt_ind)).'px;">'.$ind.'</div>');
				$sx .= '<div '.$st.'><div>'.$tips.'</div><div>'.$this->tt_ind.'</div></div>'.chr(13);				
			}else{
				$png='logo_amigas.png';
				$st='"';
				$img = '<img height="40px" src="../img/'.$png.'" >';
				$sx .= '<div>'.$img.'</div>'.chr(13);
				
			}
			
			return($sx);			
		}
	function valida_dados($promocao,$premio,$consultora)
	{
		global $base_name,$base_server,$base_host,$base_user,$setdp;
		require("../db_fghi_206_PROMO.php");
		$this->vld=1;
		$st='';
		/*verifica ean etiqueta da promocao*/
		$sql = "select * from produto_estoque
				where pe_ean13='$promocao'
				";
		$rlt = db_query($sql);
		while($line=db_read($rlt))
		{
			$st = $line['pe_status'];
		}
		if(strlen($st)>0)
		{
			if($st=='T')
			{
				$this->vld=0;
				$sx.='Erro - Código da etiqueta já baixado<br>';
			}
		}else{
			$this->vld=0;
			$sx.='Erro - Código da etiqueta inválido<br>';
		}
		
		/*verifica ean premio*/
		$st='';
		$sql2 = "select * from produto_estoque
				where pe_ean13='$premio'
				";
		$rlt2 = db_query($sql2);
		while($line2=db_read($rlt2))
		{
			$st = $line2['pe_status'];
		}
		if(strlen($st)>0)
		{
			if($st=='T')
			{
				$this->vld=0;
				$sx.='Erro - Código do prêmio já baixado<br>';
			}
		}else{
			$this->vld=0;
			$sx.='Erro - Código do prêmio inválido<br>';
		}
		
		/*verifica consultora premio*/
		require("../db_fghi_206_cadastro.php");
		$st='';
		$sql3 = "select * from cadastro
					where cl_cliente='$consultora'
				";
		$rlt3 = db_query($sql3);
		while($line3=db_read($rlt3))
		{
			$st = $line3['cl_nome'];
		}
		if(strlen($st)>0)
		{
			$this->consultora = $st;
			
		}else{
			$this->vld=0;
			$sx .= 'Código da consultora inválido<br>';
		}
		
		return($sx);
	}
	function baixa_etiquetas_simples($ean,$consultora)
	{
		global $base_name,$base_server,$base_host,$base_user,$setdp;
		require("../db_fghi_206_PROMO.php");
		$sql = "select * from produto_estoque where pe_ean13='$ean' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{
			$sta = trim($line['pe_status']);
			
			if ($sta=='A')
				{			
					$sql = "update produto_estoque 
					 		set pe_status='T',
						 	 	pe_cliente='$consultora',
					 	 		pe_lastupdate=".date('Ymd')."
					 		where pe_ean13='$ean' and 
							 	(pe_status='@' or pe_status='A') 
					 		";
					$rlt=db_query($sql);
					return(1);
				} else {
					return(0);
				}
			}
	}
	function baixa_etiquetas($promocao,$premio,$consultora)
	{
		global $base_name,$base_server,$base_host,$base_user,$setdp;
		require("../db_fghi_206_PROMO.php");
		$sql = "update produto_estoque 
					 set pe_status='T',
					 	 pe_cliente='$consultora',
					 	 pe_lastupdate=".date('Ymd')."
					 where pe_ean13='$promocao' and 
					 	(pe_status='@' or pe_status='A') 
					 ";
		$rlt=db_query($sql);
		
		$sql = "update produto_estoque 
					 set pe_status='T',
					 	 pe_cliente='$consultora',
					 	 pe_lastupdate=".date('Ymd')."
					 where pe_ean13='$premio' and
					 	   (pe_status='@' or pe_status='A')
					 ";
		$rlt=db_query($sql);		 
		
		
		return(1);
	}
	function lista_indicados($id='0')
	{
		global $base_name,$base_server,$base_host,$base_user;
		require("../db_fghi_210.php");
		$sql ="	select * from clientes_indicacao 
				where ci_cliente='".$id."' and 
					  ci_validacao='1'  and
					  ci_situacao='A'
				";
		$rlt = db_query($sql);
		$vld = 0;
		$tx='';
		while($line=db_read($rlt))
		{
			if($vld==0){ $tx .=' where ';} 
			if($vld>0){ $tx .=' or ';}
			$tx .= " cl_cliente='".$line['ci_indicado']."' ";
			$vld=1; 
		}
		if(strlen(trim($tx))>0)
		{		
			require("../db_fghi_206_cadastro.php");
			$sql2 = " select * from clientes ".$tx;
			$rlt2 = db_query($sql2);
			$vld='0';
			$sx = '<table><tr>
					<th class="tabelaTH">Código</th>
					<th class="tabelaTH">Nome</th></tr>';
			$this->ttln=0;
			while($line2=db_read($rlt2))
			{
				$vld='1';
				$sx .= '<tr>
							 <td class="tabela01">'.$line2['cl_cliente'].'</td>  	
					 		 <td class="tabela01">'.$line2['cl_nome'].'</td>';
				$sx .= '</tr>
				';
				$tt++;
				$this->tt_ind=$tt;
						
			}
			$sx .= "</table>";
			if($vld==0){$sx='';}
		}
		return($sx);	
	}
/*
	function lista_premios($tt_ind='')
	{
		global $base_name,$base_server,$base_host,$base_user;
		require("../db_fghi_206_PROMO.php");
		$sql ="select * from produto 
			   where p_ativo=1
				";
		switch($tt_ind)
		{
			case 1:
				$sql .= " and p_class_1='005'";
				break;
			case 2:
				$sql .= " and p_class_1='004'";
				break;
			case 3:
				$sql .= " and p_class_1='002'";
				break;
			case 4:
				$sql .= " and p_class_1='003'";
				break;
			case 5:
				$sql .= " and p_class_1='001'";
				break;
			default:
				$sql .= " and 1<>1";
				break;
			
		}
		$rlt = db_query($sql);
		$sx = '
		<form action="/fonzaghi/cons/indicacoes_baixa.php" id="form1">
		<center><select name="dd2"  form="form1">';
		while($line=db_read($rlt))
		{
			$sx .='<option value="'.$line['p_codigo'].'">'.trim($line['p_descricao']).'</option>';
		}
		$sx .='</select><br><br>
				<input type="submit" class="botao-submit" name="acao" value="Salvar">
				<input type="hidden" name="dd1" value="'.$this->cliente.'">
				</form>
		';
		return($sx);
	}

	function lista_premios_classe($classe='')
	{
		global $base_name,$base_server,$base_host,$base_user;
		require("../db_fghi_206_PROMO.php");
		$sql ="
			   select distinct(p_descricao),* from produto 
			   where p_class_1='$classe'
				";
		$rlt = db_query($sql);
		while($line=db_read($rlt))
		{
			$sx .=' pe_produto='.$line['p_codigo'];
		}
		
		$sx = '
		<form action="/fonzaghi/cons/indicacoes_baixa.php" id="form1">
		<center><select name="dd2"  form="form1">';
		while($line=db_read($rlt))
		{
			$sx .='<option value="'.$line['p_codigo'].'">'.trim($line['p_descricao']).'</option>';
		}
		$sx .='</select><br><br>
				<input type="submit" class="botao-submit" name="acao" value="Salvar">
				<input type="hidden" name="dd1" value="'.$this->cliente.'">
				</form>
		';
		return($sx);
	}
	
*/	
	function baixa_indicacoes($id='0000000')
	{
		global $base_name,$base_server,$base_host,$base_user;
		require("../db_fghi_210.php");
		$sql = "update clientes_indicacao 
				set ci_situacao='B' 
				where ci_status='1' and
					  ci_cliente='".$id."' and
					  ci_situacao='A'	
				";
		$rlt = db_query($sql);		
		return(1);
	}
	function gerar_recibo($obj='', $produtos='',$indicados='0')
	{
		$codigo=$obj->codigo;
		$nome=$obj->nome;
		$cpf=$obj->cpf;
		$codigo=$obj->codigo;
		echo '<center><table align="center" width="80%" style="font-size:12px;">
				<tr><td align="left"><img src="../img/logo_fonzaghi_vetor.png" height="60px"></td>
					<td align="right" valign="bottom">CÓDIGO CONSULTORA: '.$codigo.'</td>
				</tr>
				<tr><td><br><br></td>
				</tr>
				<tr><td colspan="2" align="center">COMPROVANTE DE ENTREGA</td>
				</tr>
				<tr><td><br><br><br><br></td>
				</tr>
				<tr><td colspan="2" align="left">
				1.	Eu '.$nome.', declaro estar recebendo o(s) prêmio(s) relacionado(s) abaixo, 
				referente a promoção Amigas para Sempre
				</tr>
				<tr><td><br></td>
				<tr><td colspan="2" align="left" style="font-size:9px;">
				 '.$produtos.'
				</td></tr>
				<tr><td><br><br><br><br><br><br></td>
				</tr>
				<tr><td align="left">CPF : '.$cpf.'</td>
					<td align="right">Assinatura :__________________________</td>
					</tr>
				<tr><td><br><br><br><br></td>
				</tr>	
				<tr><td colspan="2" align="center">Curitiba - '.date('d/m/Y').'</td>
				</tr>	
				</table>';
	}


	function indicacoes_validadas($id)
	{
		global $base_name,$base_server,$base_host,$base_user,$setdp;
		require("../db_fghi_210.php");
		setdp();
		//com filtro de consultora 
		if(strlen(trim(round($id)))>0)
		{	
			$sql="	select * from clientes_indicacao
					where ci_validacao='0' and
						  ci_cliente='".$id."'	
					";
			$rlt = db_query($sql);
			while($line=db_read($rlt))
			{
				$i=0;
				$vld=0;
				$sql2='select sum(count) from (';
				while($i<=count($setdp))
				{
					if($vld>0)
					{
						 $sql2 .= " union ";
					}	
					$sql2 .= " select count(dp_cliente) from ".$setdp[2][$i]." 
							   where dp_cliente='".$line['ci_indicado']."' and
							   		 dp_datapaga>='".$line['ci_data']."'
							   ";
					$vld=1;
					$i++;
				}
				$sql2 .=') as tb';
				$rlt2 = db_query($sql2);
				$line2 = db_read($rlt2);
				
				if($line2['sum']>0)
				{
					
					$sql3 = "update clientes_indicacao 
							set ci_validacao='1'
							where ci_status='1' and
								  ci_cliente='".$id."' and
								  ci_indicado='".$line['ci_indicado']."' and
								  ci_situacao='A'";
					$rlt3 = db_query($sql3);
				}
			}		
			
		//sem filtro de consultora
		}else{
			
			$sql="	select * from clientes_indicacao
					where ci_validacao='0'";
			$rlt = db_query($sql);
			while($line=db_read($rlt))
			{
				$i=0;
				$vld=0;
				$sql2='select sum(count) from (';
				while($i<=count($setdp))
				{
					if($vld>0)
					{
						 $sql2 .= " union ";
					}	
					$sql2 .= " select count(dp_cliente) from ".$setdp[2][$i]." 
							   where dp_cliente='".$line['ci_indicado']."' and
							   		 dp_datapaga>='".$line['ci_data']."'
							   ";
					$vld=1;
					$i++;
				}
				$sql2 .=') as tb';
				$rlt2 = db_query($sql2);
				$line2 = db_read($rlt2);
				
				if($line2['sum']>0)
				{
					$sql3 = "update clientes_indicacao 
							set ci_validacao='1'
							where ci_status='1' and
								  ci_cliente='".$id."' and
								  ci_indicado='".$line['ci_indicado']."' and
								  ci_situacao='A'";
					$rlt3 = db_query($sql3);
				}
			}
		}
		
		return(1);			
	}

	}
?>