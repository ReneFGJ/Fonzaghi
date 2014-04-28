<?
    /**
     * Produtos
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package produto
	 * @subpackage classe
    */
    
    require_once($include."sisdoc_icon.php");
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
	var $line;
	var $tabela = 'produto';
    var $ico;
	
	function etiqueta_vermelha($ean,$desconto)
		{
			global $user;
			$sql = "select * from produto_estoque
						where pe_ean13 = '".$ean."' 
					";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sta = trim($line['pe_status']);
					$sta = trim($line['pe_status']);
					$sd = trim($line['pe_sem_dev']);
					$er = '';
					if ($sta == 'T')
						{ $er = '<font color="red">produto já foi faturado</font>'; }
					if ($sta == 'F')
						{ $er = '<font color="red">produto fornecido</font>'; }
					if ($sd == 'S')
						{ $er = '<font color="red">produto já esta com etiqueta vermelha</font>'; }
					if (strlen($er) == 0)
						{
							$vlr = $line['pe_vlr_venda'];
							$vlr = (round($vlr * $desconto) / 100);
							$sql = "update produto_estoque set
										pe_vlr_venda = ".$vlr.",
										pe_log_eti = '".$user->user_log."',
										pe_sem_dev = 'S',
										pe_status = '@'
									where pe_ean13 = '".$ean."' 
							";
							$rrr = db_query($sql);
							$er = '<font color="green">OK - Aplicado '.round(100-$desconto).'% OFF</font>';
						}
				} else {
					$er = '<font color="red">produto não localizado</font>';
				}
			return($er);
		}
	
	function inventario_razao_faltas()
		{
			$sql = "select * from produto_estoque 
					inner join produto on p_codigo = pe_produto
					where pe_inventario = 0
					and (pe_status = 'B' or pe_status = 'A' or pe_status = 'C')
					order by pe_produto, pe_lastupdate
			";
			$rlt = db_query($sql);
			$sx .= '<H1>Razão do estoque</h1>';
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH>Código<TH>Status<TH>EAN13<TH>Descrição
						<TH>Valor<TH>Ult.Atual.';
			$ti = 0;
			while ($line = db_read($rlt))
			{
				$it++;
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $line['pe_produto'];
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $line['pe_status'];
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $line['pe_ean13'];
				$sx .= '<TD class="tabela01">';
				$sx .= $line['p_descricao'];
				$sx .= '<TD class="tabela01" align="right" >';
				$sx .= number_format($line['pe_vlr_venda'],2,',','.');
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= stodbr($line['pe_lastupdate']);
			}
			$sx .= '<TR><TD colspan=5><I>Total de '.$it.' produtos faltantes';
			$sx .= '</table>';
			return($sx);
		}
	
	function inventario_desmarcar_produtos($id)
		{
			$sql = "update produto_estoque set pe_inventario = 0
					where (pe_status = 'B' or pe_status = 'A' or pe_status = 'C' or pe_status = 'F')
					and pe_inventario = 1 and pe_produto = '$id'
			";
			$rlt = db_query($sql);
			return(1);
		}	
	
	function inventario_todos_produtos()
		{
			$sql = "update produto_estoque set pe_inventario = 1
					where (pe_status = 'B' or pe_status = 'A' or pe_status = 'C' or pe_status = 'F')
					and pe_inventario = 0
			";
			$rlt = db_query($sql);
			return(1);
		}
	
	function inventario_resumo()
		{
			$sql = "select count(*) as total, pe_inventario from produto_estoque
						where (pe_status = 'B' or pe_status = 'A' or pe_status = 'C')
						group by pe_inventario";
			$rlt = db_query($sql);
			$iv1 = 0;
			$iv2 = 0;
			while ($line = db_read($rlt))
				{
					$ln = $line['total'];
					$to = round($line['pe_inventario']);
					if ($to==1) { $iv1 = $iv1+ $ln; }
					if ($to==0) { $iv2 = $iv2+ $ln; }
				}
			$sx .= '<table width="500" class="tabela00" align="center">';
			$sx .= '<TR><TD colspan=2><h1>Resumo do Inventário</h1>';
			$sx .= '<TR><TH>Invetariados<TH>Inventariar';
			$sx .= '<TR><TD align="center" class="tabela01 lt5"><B>'.$iv1.'</B>';
			$sx .= '    <TD align="center" class="tabela01 lt5"><B>'.$iv2.'</B>';
			$sx .= '</table>';
			$sx .= '<BR><BR>';
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
		array_push($cp,array('$S7','p_fornecedor','Fornecedor (cod. pedido)',False,True,''));
		array_push($cp,array('$Q pg_descricao:pg_codigo:select pg_descricao || chr(32) || pg_codigo as pg_descricao,pg_codigo from produto_grupos where pg_ativo=1 order by pg_codigo','p_class_1','Classificação',False,True,''));
		//array_push($cp,array('$S13','p_cod_fornecedor','Codigo (fornecedor)',False,True,''));
		array_push($cp,array('$QA fo_nomefantasia:fo_codfor:select * from fornecedor where fo_ativo = 1 order by fo_nomefantasia','p_cod_fornecedor','Fornecedor',False,True,''));		
		array_push($cp,array('$S50','p_descricao','Descrição',False,True,''));
		array_push($cp,array('$T50:4','p_content','Informações',False,True,''));
		array_push($cp,array('$N8','p_preco','Preco',False,True,''));
		array_push($cp,array('$N8','p_custo','Custo',False,True,''));
		array_push($cp,array('$O 0:0%&10:10%&20:20%&25:25%&30:30%&40:40%&50:50%','p_comissao','Comissao',False,True,''));
		array_push($cp,array('$O 1:SIM&2:NÃO','p_ativo','Ativo',False,True,''));
		array_push($cp,array('$[O-50]','p_promo','Desconto Promocional %',False,True,''));		
		return($cp);
		}

      /* Método - consulta e mostra codigo
       * @var sta string    - Código Ean13 do Fornecedor
       * @var codigo string - Código do produto (seis digitos)
       * @result bollean
       */
	function consulta_codigos($sta,$codigo='')
		{
			$sql = "select * from produto_estoque 
					inner join produto on pe_produto = p_codigo
					where pe_ean13 = '".$sta."' ";
			if (strlen($codigo) > 0)
			 {
			       $sql = "select * from produto_estoque 
                    inner join produto on pe_produto = p_codigo
                    where pe_produto = '".$codigo."' ";			     
			 }
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
					$this->line = $line;
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
		$upload_dir = troca($upload_dir,'../produtos_estoque_individual.php','../img/img_produto/');
		$upload_dir = troca($upload_dir,'estoque_imagens.php','../img/img_produto/');
		$file = $upload_dir . $this->p_codigo. '.jpg';
		$size = 120;
		if ($upload!=1) { $size=200; }
		if (file_exists($file))
			{
				//$up = '<A HREF="#" onclick="';
				//$up .= "newxy2('upload.php?dd0=".$this->p_codigo."',500,300);";
				//$up .= '">';
				//if ($upload!=1) { $up = ''; }
				$up = '<A HREF="../img/img_produto/'.$this->p_codigo.'.jpg" target="nwn">';
				$img_src = '<img src="../img/img_produto/'.$this->p_codigo.'.jpg" width="'.$size.'" alt="" border="1" id="p'.$this->p_codigo.'i" style="display: none;">';
				$rs .= $up.$img_src.'</A>';
			} else {
				$up = '<A HREF="#" onclick="';
				$up .= "newxy2('upload.php?dd0=".$this->p_codigo."',500,300);";
				$up .= '">';
				if ($upload!=1) { $up = ''; }
				$img_src = '<img src="../img/icone_sem_imagem.png" width="'.$size.'" alt="" border="1" id="p'.$this->p_codigo.'i" style="display: none;">';
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

	function mostra_produto($ljdb='')
		{
		global $tab_max;
        $sql = "select * from produto where (p_codigo = '".$this->p_codigo."') or id_p = (".$this->p_codigo.")";
		$xrlt = db_query($sql);
		$to1 = 0;
		$to2 = 0;
		$to3 = 0;
		if ($line = db_read($xrlt))
			{
			$this->p_codigo = trim($line['p_codigo']);
			$this->p_descricao = $line['p_descricao'];
			$this->p_preco = number_format($line['p_preco'],2);
			$this->p_custo = number_format($line['p_custo'],2);
            $this->p_comissao = $line['p_comissao']."%";
            $this->p_promo = $line['p_promo'];
			$this->p_fornecedor = $line['p_fornecedor'];
            $this->p_cod_fornecedor = $line['p_cod_fornecedor'];
            $this->p_ean13=$line['p_ean13'];
			if ($this->promo > 0)
				{
				$this->preco = '<S>'.$this->preco.'</S> por '.number_format($this->preco * (1 - $this->promo/100),2);
				}
            $idc=$this->p_codigo;
            /*Icones*/
            $this->ico[0][0]='chart';
            $this->ico[0][1]='../pedidos/produtos_estoque_individual.php?dd0='.$idc.'&dd80='.$ljdb;
            
            $spa1 = '<span id="p'.$this->p_codigo.'">';
            $sa .= '<center><table width="90%" class="tabela01" align="center">
			         <TR class=lt0 ><TD width=10%>codigo</TD>
			                        <TD width=10%>ean</TD>
			                        <TD width=40%>produto</TD>
			                        <TD width=10%>preço</TD>
			                        <TD width=10%>custo</TD>
			                        <TD width=10%>comissão</TD>
			                        <TD width=10% align=right>imagem</TD>
			                        <TD width=10% align=right>ações</TD>
			         </TR>
			         <TR class=lt2 >
			                         <TD width=10%>'.$this->p_codigo.'</TD>
			                         <TD width=10%>'.$this->p_ean13.'</TD>
                                     <TD width=40%><B>'.$spa1.$this->p_descricao.'</span></B></TD>
			                         <TD width=10%>'.$this->p_preco.'</TD>
			                         <TD width=10%>'.$this->p_custo.'</TD>
			                         <TD width=10%>'.$this->p_comissao.'</TD>
			                         <TD width=10% rowspan="10" align=right>'.$this->mostrar_imagem().'</TD>
			                         <TD width=10% rowspan="10" align=right>'.multiple_ico($idc,$this->ico,40).'</TD>
			                         </TR>
			     </table>';
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
        
       /* Método - consulta e mostra estoque em formato fieldset
       * @var p_codigo string    - Código produto
       * @result string
       */
        function mostra_estoque_produtos($forn='',$prod=''){
                
            $sql = "select count(*) as total, pe_status, sum(pe_vlr_vendido) as vendido, sum(pe_vlr_venda) as venda, sum(pe_vlr_custo) as custo 
                            from produto_estoque where pe_fornecedor = '".$forn."' and 
                                                       pe_produto = '".$prod."' and 
                                                       pe_status <> 'X' 
                            group by pe_status order by pe_status ";
            $rlt = db_query($sql);
            //$link = '<A HREF="produtos_estoque_individual.php?dd0='.$line['id_p'].'&dd90='.checkpost($line['id_p']).'" target="NW'.$line['id_p'].'">';
            //$sx.= $link.$line['p_descricao'];
            $tt=0;
            $ttf=0;
            $tta=0;
            $ttt=0;
            while ($line = db_read($rlt))
                {
                            if ($line['pe_status']=='T') {
                            $mx.= '<tr class="lt1"><TD align="left">Quantidade';       
                            $ttm=$ttm+$line['total'];
                            $mx.= '<td align="center">'.$ttm;
                            $mx.= '<tr class="lt1"><td align="left">Custo : ';
                            $mx.= '<td align="right">'.number_format(($line['custo'])/$ttm,2);
                            $mx.= '<tr class="lt1"><td align="left">Venda bruta';
                            $mx.= '<TD  align="right">'.number_format(($line['venda'])/$ttm,2);
                            $mx.= '<tr class="lt1"><td align="left">Venda líquida';
                            $mx.= '<td align="right">'.number_format(($line['vendido'])/$ttm,2);
                            }
                               
                            if ($line['pe_status']=='T') {
                            $tx.= '<tr class="lt1"><TD align="left">Quantidade';       
                            $ttt=$ttt+$line['total'];
                            $tx.= '<td align="center">'.$ttt;
                            $tx.= '<tr class="lt1"><td align="left">Custo';
                            $tx.= '<td align="right">'.number_format($line['custo'],2);
                            $tx.= '<tr class="lt1"><td align="left">Venda bruta';
                            $tx.= '<TD  align="right">'.number_format($line['venda'],2);
                            $tx.= '<tr class="lt1"><td align="left">Venda líquida';
                            $tx.= '<td align="right">'.number_format($line['vendido'],2);
                            
                  
                            }
                            
                            if ($line['pe_status']=='A') {
                            $ax.= '<TR class="lt1"><TD align="left">Quantidade';       
                            $tta=$tta+$line['total'];
                            $ax.= '<TD  align="center">'.$tta;
                            $ax.= '<tr class="lt1"><td align="left">Custo';
                            $ax.= '<td align="right">'.number_format($line['custo'],2);
                            $ax.= '<tr class="lt1"><td align="left">Valor Venda';
                            $ax.= '<TD  align="right">'.number_format($line['venda'],2);
                            
                            
                  
                            }
                            
                            if ($line['pe_status']=='F') {
                            $fx.= '<tr class="lt1"><TD align="left">Quantidade';       
                            $ttf=$ttf+$line['total'];
                            $fx.= '<TD  align="center">'.$ttf;
                            $fx.= '<tr class="lt1"><td align="left">Custo';
                            $fx.= '<td align="right">'.number_format($line['custo'],2);
                            $fx.= '<tr class="lt1"><td align="left">Valor Venda';
                            $fx.= '<TD  align="right">'.number_format($line['venda'],2);
                            
                            
                            
                            }
                 }

            $sx .='<table width=90% class=tabela01  align="center" ><tr><td>';     
            
            $sx .='<fieldset><legend>Vendido (Média)</legend><table>';
            $sx .=$mx; 
            $sx.= '</table></fieldset>';
            
            $sx.='<td>';          
            
            $sx .='<fieldset><legend>Vendido (Total)</legend><table>';
            $sx .=$tx; 
            $sx.= '</table></fieldset>';
            
            $sx.='<td>';          
            
            $sx .='<fieldset><legend>Loja</legend><table>';
            $sx .=$ax;
            $sx.= '</table></fieldset>';
            
            $sx.='<td>';          
            
            $sx .='<fieldset><legend>Consultora</legend><table>';
            $sx .=$fx;
            $sx.= '</table></fieldset>';
            
            $sx.='<td>';          
            
            $sx .='<fieldset><legend>Pedidos Abertos</legend><table>';
            $prod=$this->p_ean13;
            $sx .=$this->produtos_pendentes($forn,$prod);
            $sx.= '</table></fieldset>';
            $sx.= '</td></tr></table>';   

            return($sx);
        }

        function produtos_pendentes($forn,$prod)
        {
            global $acao,$dd,$avaliador,$base_name,$base_host,$base_user;       
            require('../db_caixa_central.php'); 
            
            $dt = date("Ymd",mktime(0, 0, 0, date("m")-6, date("d"), date("Y"))); 
            $sql="select * from pedido 
                  left join pedido_item on (pedido_item.pedi_ped=pedido.id_ped) 
                  where pedido.ped_status='F' and 
                        pedido.ped_data>'".$dt."' and 
                        pedido.ped_chegada='19000101' and
                        pedido.ped_fornecedor='".$forn."' and
                        pedido_item.pedi_ref_item='".$prod."'
                  order by pedido.ped_data ";
            $rlt = db_query($sql);
            
           $sx .= '<script>
                        <!--
                        function goto(choose){
                        var selected=choose.options[choose.selectedIndex].value;
                            if(selected != ""){
                                newxy2(selected,800,600);
                            }
                        }
                        //-->
                        </script>';
            
            $sx.='<table>';
            $sx .= '<select size="4" onClick="goto(this);"  name="selectionField" multiple="yes" >'; 
            $sx .='>';
            while ( $line = db_read($rlt)) 
            {   
                $sx .= '<option value="../pedidos/pedido_notas.php?dd95='.$line['id_ped'].'&dd96='.trim($forn).'&dd97='.$line['ped_nrped'].'" >'.$line['ped_nrped'].' - Qtda : '.$line['pedi_quan'].'</option>';
            }
            $sx .= '</select>';
            $sx.='</table>';
            
  
            return($sx);
        }

		function produto_atendente($id_prod){
			$sql = "select count(*) as total, pe_log, pe_status from produto_estoque
					where pe_produto='".$id_prod."' and
						  (pe_status = 'T' or pe_status='F')
							
					group by pe_log, pe_status
					order by pe_log
			";
			$rlt = db_query($sql);
			$sx = '<table><tr><th align="center">Atendente</th>
						 	 <th align="center">Quantidade</th>
						 	 <th align="center">Status</th>
				';
			while($line = db_read($rlt)){
				$sx .= '<tr>
						<td class="tabela01" align="center">'.$line['pe_log'].'</td>
						<td class="tabela01" align="center">'.$line['total'].'</td>
						<td class="tabela01" align="center">'.$this->status($line['pe_status']).'</td>
						</tr>';	
				switch($line['pe_status']){
					case 'F':
						$ttf = $line['total'] + $ttf;
						break;	
					case 'T':
						$ttt = $line['total'] + $ttt;
						break;
				}
			}
			$sx .= '<tr><td>Total Vendido</td><td>'.$ttt.'</td></tr>
					<tr><td>Total Fornecido</td><td>'.$ttf.'</td></tr>
			';
			
			return($sx);
		}
		function status($st){
			switch($st){
				case 'T':
					$sx = 'VENDIDO';
					break;
				case 'F':
					$sx = 'FORNECIDO';
					break;
				case '@':
					$sx = 'TEMPORARIO';
					break;
				case 'X':
					$sx = 'CANCELADO';
					break;			
				case 'A':
					$sx = 'ESTOQUE';
				break;
			}
			return($sx);
		}


}
?>