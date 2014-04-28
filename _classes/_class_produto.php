<?
Class produto
	{
 	var $id_p;
	var $p_codigo;
	var $p_ean13;
	var $p_descricao;
	var $p_preco;
	var $p_ativo;
	var $p_comissao;
	var $p_custo;
	var $p_fornecedor;
	var $p_marcap;
	var $p_cod_fornecedor;
	var $p_content;
	var $p_class_1;
	var $p_class_2;
	var $p_promo;
	
	var $tabela = 'produto';
	
	function produto_acompanhamento()
		{
			$sql = "select avg(total) as media, count(*) as cliente, sum(total) as pecas
						from (
						select pe_cliente, count(*) as total from produto_estoque 
							where pe_sem_dev = 'S' and (pe_status = 'T' or pe_status = 'F') and substr(pe_cliente,1,1) <> 'F'
							group by pe_cliente
						) as tabela";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				$forn1a = $line['media'];
				$forn2a = $line['cliente'];
				$forn3a = $line['pecas'];					
				}			
			
			$sql = "select avg(total) as media, count(*) as cliente, sum(total) as pecas
						from (
						select pe_cliente, count(*) as total from produto_estoque 
						inner join produto on pe_produto = p_codigo
						inner join produto_grupos on p_class_1 = pg_codigo
						where (pe_status = 'T' or pe_status = 'F') and substr(pe_cliente,1,1) <> 'F'
						and pe_promo = 20 and pe_lastupdate > 20130700 and pg_ref = 20
						group by pe_cliente
						) as tabela";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				$forn1b = $line['media'];
				$forn2b = $line['cliente'];
				$forn3b = $line['pecas'];					
				}	
				
			$sx .= '<table class="tabela00" width="600">
					<TR><TH>Tipo<TH>Peças forn.<TH>Clientes<TH>Média/Clie.
					<TR><TD class="tabela01">Prod. sem devolução
					<TD align="center">'.$forn3a;
			$sx .= '<TD align="center">'.$forn2a;
			$sx .= '<TD align="center">'.number_format($forn1a,2,',','.');
					
			$sx .= '<TR><TD class="tabela01">Prod. com devolução
					<TD align="center">'.$forn3b;
			$sx .= '<TD align="center">'.$forn2b;
			$sx .= '<TD align="center">'.number_format($forn1b,2,',','.');
			$sx .= '</table>';
			return($sx);		
		}

	function cp()
		{
		$this->tabela = "produto";
		$cp = array();
		array_push($cp,array('$H4','id_p','id_p',False,True,''));
		array_push($cp,array('$A','','Informações do Produto',False,True,''));
		array_push($cp,array('$S7','p_codigo','Codigo',False,False,''));
		array_push($cp,array('$S13','p_ean13','Codigo EAN13',False,True,''));
		array_push($cp,array('$S15','p_fornecedor','Fornecedor (cod. pedido)',False,True,''));
		array_push($cp,array('$Q pg_descricao:pg_codigo:select pg_descricao || chr(32) || pg_codigo as pg_descricao,pg_codigo from produto_grupos where pg_ativo=1 order by pg_codigo','p_class_1','Classificação',False,True,''));
		//array_push($cp,array('$S13','p_cod_fornecedor','Codigo (fornecedor)',False,True,''));
		array_push($cp,array('$O '.$this->op_fornecedor,'p_cod_fornecedor','Fornecedor',False,True,''));		
		require("db_temp.php");
		array_push($cp,array('$S50','p_descricao','Descrição',False,True,''));
		array_push($cp,array('$T50:4','p_content','Informações',False,True,''));
		array_push($cp,array('$N8','p_preco','Preco',False,True,''));
		array_push($cp,array('$N8','p_custo','Custo',False,True,''));
		array_push($cp,array('$O 0:0%&10:10%&20:20%&25:25%&30:30%&40:40%&50:50%','p_comissao','Comissao',False,True,''));
		array_push($cp,array('$O 1:SIM&2:NÃO','p_ativo','Ativo',False,True,''));
		array_push($cp,array('$[O-50]','p_promo','Desconto Promocional %',False,True,''));
		array_push($cp,array('$O  : &A:A&B:B&C:C&D:D','p_class_abcd','Classificação ABC-D',False,True,''));		
		return($cp);
		}
	function option_fornecedor()
	{
		global $base_name,$base_server,$base_host,$base_user;  
		require("../db_caixa_central.php");
		
		$sql = "select * from fornecedores where fo_status = 'S' order by fo_nomefantasia";
        $rlt = db_query($sql);
        $op = ' :Selecione o cargo';
        while ($line = db_read($rlt)) {
            $nome=trim($line['fo_nomefantasia']);
            $codigo=trim($line['fo_codfor']);
            $op .= '&'.$codigo.':'.$nome;
        }
         $this->op_fornecedor=$op;     
        return($op);
	}


	function consulta_codigos($sta)
		{
			$sql = "select * from produto_estoque 
					inner join produto on pe_produto = p_codigo
					where pe_ean13 = '".$sta."' ";
					
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
			{
				$sx = '<table>';
				$sx .= '<TR><TD>status<br><b>';
				$sx .= $line['pe_status'];
				$sx .= '<TR><TD>codigo<br><b>';
				$sx .= $line['pe_produto'];
				$sx .= '<TR><TD>ean13<br><b>';
				$sx .= $line['pe_ean13'];
				$sx .= '<TR><TD>descricao<br><b>';
				$sx .= $line['p_descricao'];
				$sx .= '<TR><TD>TAMANHO<br><b>';
				$sx .= $line['pe_tam'];
				$sx .= '<TR><TD>VALOR ATUAL<br><b>';
				$sx .= number_format($line['pe_vlr_venda'],2);
				$sx .= '<TR><TD>VALOR DE ETIQUETA<br><b>';
				$sx .= number_format($line['p_preco'],2);
				
				$sx .= '</table>';
			}
			echo $sx;
			
			return(True);
		}

	function troca_codigos($cod_de,$cod_para,$cod_tam_de,$cod_tam_para)
		{
			$sql = "update produto_estoque 
					set pe_produto  = '".$cod_para."' 
					, pe_tam = '".$cod_tam_para."' 
					where pe_produto = '".$cod_de."' 
						and pe_tam = '".$cod_tam_de."'
						and pe_status = '@' 
			";
			$rlt = db_query($sql);
			return(True);
		}

	function le($id)
		{
			if (strlen($id) > 0) { $this->id_p = $id; }
			$sql = "select * from ".$this->tabela;
			$sql .= " where id_p = ".$this->id_p;
			$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
					$this->id_p=$line['id_p'];
					$this->p_codigo=$line['p_codigo'];
					$this->p_ean13=$line['p_ean13'];
					$this->p_descricao=$line['p_descricao'];
					$this->p_preco=$line['p_preco'];
					$this->p_ativo=$line['p_ativo'];
					$this->p_comissao=$line['p_comissao'];
					$this->p_custo=$line['p_custo'];
					$this->p_fornecedor=$line['p_fornecedor'];
					$this->p_marcap=$line['p_marcap'];
					$this->p_cod_fornecedor=$line['p_cod_fornecedor'];
					$this->p_content=$line['p_content'];
					$this->p_class_1=$line['p_class_1'];
					$this->p_class_2=$line['p_class_2'];
					$this->p_promo=$line['p_promo'];
					}
			return(1);
		}
		
	function estoque_produto($ped)
		{
			$sql = "select * from pedido where ped_nrped = '".$ped."'";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$status = $line['ped_status'];
					$nrped = $line['ped_nrped'];
					$dtchq = round($line['ped_nf_conf']);
					$forne = $line['ped_fonecedor'];
					if ($status != 'F')
						{
							$sx = 'Pedido não finalizado';
							return(0);
						}
					if ($dtchq < 20000101)
						{
							$sx = 'Pedido não conferido';
							return(0);
						}
					$this->estoque_produto_entrada($ped);
				}
		}
	function estoque_produto_entrada($ped)
		{
			$sql = "select * from pedido_item where pedi_nrped = '".$ped."' ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					
				}
			return($sx);
		}
		
	
	function row()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "produto";
		$tabela = "produto";
		$label = "Cadastro de Produtos";
		/* Páginas para Editar */
		$http_edit = 'ed_edit.php'; 
		$http_edit_para = '&dd99='.$tabela;
		$offset = 20;
		$order  = "p_codigo";
		
		$cdf = array('id_p','p_codigo','p_descricao','p_preco');
		$cdm = array('ID','Codigo','Descrição','Preço');
		$masc = array('','','','','','','','','');
		return(True);
		}
		
	function mostrar_itens($line)
		{
			global $coluna;
			$stx = '<TR '.coluna().'>';
			$stx .= '<TD>';
			$stx .= $cor.$line['p_descricao'];
			$stx .= '<TD align="center">';
			$stx .= $cor.($line['p_codigo']);
			$stx .= '<TD align="center">';
			$stx .= $cor.$line['p_cod_fornecedor'].'';
			$stx .= '<TD align="right">';
			$stx .= $cor.number_format($line['p_preco'],1);
			return($stx);
		}
		
	function mostrar_imagem($upload=1)
		{
		$upload_dir = $_SERVER['SCRIPT_FILENAME'];
		$upload_dir = troca($upload_dir,'produtos_estoque_individual.php','img_produto/');
		$upload_dir = troca($upload_dir,'estoque_imagens.php','img_produto/');
		$file = $upload_dir . $this->p_codigo. '.jpg';
		$size = 120;
		if ($upload!=1) { $size=200; }
		if (file_exists($file))
			{
				//$up = '<A HREF="#" onclick="';
				//$up .= "newxy2('upload.php?dd0=".$this->p_codigo."',500,300);";
				//$up .= '">';
				//if ($upload!=1) { $up = ''; }
				$up = '<A HREF="img_produto/'.$this->p_codigo.'.jpg" target="nwn">';
				$img_src = '<img src="img_produto/'.$this->p_codigo.'.jpg" width="'.$size.'" alt="" border="1">';
				$rs .= $up.$img_src.'</A>';
			} else {
				$up = '<A HREF="#" onclick="';
				$up .= "newxy2('upload.php?dd0=".$this->p_codigo."',500,300);";
				$up .= '">';
				if ($upload!=1) { $up = ''; }
				$img_src = '<img src="../img/icone_sem_imagem.png" width="'.$size.'" alt="" border="1">';
				$rs .= $up.$img_src.'</A>';
			}
		return($rs);
		}

//	function
	function produto_log($ean13,$produto,$kit,$cliente)
		{
			global $user_log;
			$data = date("Ymd");
			$hora = date("H:i");
			$log = $user_log;
			
			$sql = "insert into produto_log_".date("Ym")." 
				(pl_ean13, pl_data, pl_hora,
				pl_cliente, pl_status, pl_kit, 
				pl_produto, pl_log )
				values
				('$ean13','$data','$hora',
				'$cliente','G','$kit',
				'$produto','$log') ";
			$rlt = db_query($sql);
		} 
	function produto_desconto($ean13,$desconto,$tipo='P',$just)
		{
			global $user_log;
			$log = $user_log;
			echo '<TR><TD>'.$ean13.'<TD>Alterado de';
			$sql = "select * from produto_estoque where pe_ean13 = '".$ean13."' ";
			$xrlt = db_query($sql);
			if ($xline = db_read($xrlt))
				{
					$preco = $xline['pe_vlr_venda'];
					$produto = $xline['pe_produto'];
					echo '<TD align="right">'.number_format($xline['pe_vlr_venda'],2);
					if ($tipo == 'P')
						{ $preco = ($preco - $desconto); } else {
							$preco = (round($preco*100 - $preco * $desconto)/100);
						}
					if ($preco < $xline['pe_custo'])
						{
							echo '<TD>Abaixo do preço de custo';
						    return(-2); 
						}
						
					if ($xline['pe_lastupdate'] == date("Ymd"))
						{
							echo '<TD>Preço deste produto já foi alterado hoje';
						    return(-3); 							
						}

					if (($xline['pe_status'] != 'A') and ($xline['pe_status'] != 'D') and ($xline['pe_status'] != '@'))
						{
							echo '<TD>Peça não disponível no estoque';
						    return(-4); 							
						}

					$this->produto_log($ean13,$produto,$tipo.$desconto,$just);
					$sql = "update produto_estoque set 
						pe_vlr_venda = ".$preco.",
						pe_lastupdate = ".date("Ymd").",
						pe_status = '@',
						pe_log_eti = '$log'
						where id_pe = ".$xline['id_pe'];
					echo '<TD align="center">para';
					echo '<TD align="right">'.number_format($preco,2);
										
					$rlt = db_query($sql);
					return(1);
				} else {
					echo '<TD>Erro de localização';
					return(-1);
				}
		}		

	function mostra_produto()
		{
		global $tab_max;
		$sql = "select * from produto where (p_codigo = '".$this->p_codigo."') or id_p = (".$this->p_codigo.")";
		$xrlt = db_query($sql);
		$to1 = 0;
		$to2 = 0;
		$to3 = 0;
		if ($line = db_read($xrlt))
			{
			$this->p_codigo = $line['p_codigo'];
			$this->p_descricao = $line['p_descricao'];
			$this->p_preco = number_format($line['p_preco'],2);
			$this->p_promo = $line['p_promo'];
			$this->p_fornecedor = $line['p_fornecedor'];
			if ($this->promo > 0)
				{
				$this->preco = '<S>'.$this->preco.'</S> por '.number_format($this->preco * (1 - $this->promo/100),2);
				}
				
//			require('../db_fghi.php');
//			$sql = "select * from fornecedores where fo_codfor = '".$this->p_fornecedor."' ";
//			$xrlt = db_query($sql);
//			if ($fline = db_read($xrlt))
//				{
//				$this->p_fornecedor = $fline['fo_nomefantasia'];
//				}
			$sa = "<table width=$tab_max border=0 >";
			$sa .= "<TR class=lt0 ><TD width=20% >codigo</TD><TD>produto</TD><TD align=right>imagem</TD></TR>";
			$sa .= "<TR class=lt2 ><TD>".$this->p_codigo."</TD><TD><B>".$this->p_descricao."</B></TD>";
			$sa .= '<TD rowspan="10" align=right>'.$this->mostrar_imagem()."</TD></TR>";
			$sa .= "<TR class=lt0 ><TD>Preço</TD><TD>fornecedor</TD></TR>";
			$sa .= "<TR class=lt2 ><TD>".$this->p_preco."</TD><TD><B>".$this->p_fornecedor."</B></TD></TR>";
			$sa .= "<TR class=lt0 ><TD>-</TD><TD>-</TD></TR>";
			$sa .= "<TR class=lt2 ><TD height=80% >-</TD><TD><B>-</B></TD></TR>";
			$sa .= '</table>';
			}
			return($sa);
		}

		function updatex()
			{
			$dx1 = 'p_codigo';
			$dx2 = 'p';
			$dx3 = 6;
			$sql = "update ".$this->tabela." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
			}
}
?>