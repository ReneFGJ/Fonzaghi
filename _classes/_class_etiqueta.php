<?
class etiqueta
	{
	var $ean13;
	var $codigo;
	var $tam;
	var $preco;
	var $img;
	var $nome;
	var $comissao;
        var $validade=0;
	
	function etiqueta_total_imprimir()
		{
			$sql = "select count(*) as total from produto_estoque ";
			$sql .= " where pe_status = '@' ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}

	function etiqueta_total_imprimir_login()
		{
			$sql = "select count(*) as total, pe_log_eti from produto_estoque ";
			$sql .= " where pe_status = '@' ";
			$sql .= " group by pe_log_eti ";
			$rlt = db_query($sql);
			$rst = array();
			while ( $line = db_read($rlt))
				{
					array_push($rst,$line);
				}
			return($rst);
		}

	function etiquetas_imprimir_lista($xlog='')
		{
			$xlog = trim($xlog);
			$sql = "select * from produto_estoque ";
			$sql .= " inner join produto on pe_produto = p_codigo ";
			$sql .= " where pe_status = '@' ";
			if (strlen($xlog) > 0)
				{ $sql .= " and pe_log_eti = '".$xlog."' "; }
			$sql .= " order by p_codigo, pe_tam, pe_ean13 ";
			$rlt = db_query($sql);
			$eti = array();
			while ($line = db_read($rlt))
				{
					array_push($eti,array(
					$line['pe_ean13'],
					$line['pe_produto'],
					$line['p_descricao'],
					$line['pe_vlr_venda'],
					$line['pe_promo'],
					$line['pe_tam'],
					trim($line['p_descricao']),
					$line['p_comissao'],
					$line['p_ean13'],
					$line['pe_validade'],
					$line['v_ref']
					));
				}
				
			return($eti);
		}
		/*join com categoria*/
		function etiquetas_imprimir_lista2($xlog='')
		{
			$xlog = trim($xlog);
			$sql = "select * from (";
			$sql .= "select * from produto_estoque ";
			$sql .= " inner join produto on pe_produto = p_codigo ";
			$sql .= " where pe_status = '@' ";
			if (strlen($xlog) > 0)
				{ $sql .= " and pe_log_eti = '".$xlog."' "; }
			$sql .= ")as tb inner join produto_grupos on pg_codigo=p_class_1";
			echo $sql .= " order by p_codigo, pe_tam, pe_ean13 ";
			$rlt = db_query($sql);
			$eti = array();
			while ($line = db_read($rlt))
				{
					array_push($eti,array(
					$line['pe_ean13'],
					$line['pe_produto'],
					$line['p_descricao'],
					$line['pe_vlr_venda'],
					$line['pe_promo'],
					$line['pe_tam'],
					trim($line['p_descricao']),
					$line['p_comissao'],
					$line['p_ean13'],
					$line['pe_validade'],
					$line['v_ref'],
					$line['pg_descricao']
					));
				}
				
			return($eti);
		}
	
	/* Nova Página */
	function np()
		{
			$sx = '<p style="page-break-before: always;"></p>';
			return($sx);
		}
	function etiqueta_mst_txt($ep)
		{
			$cr = chr(10).chr(13);
			$sr = '';
			$sr .= chr(13);
			return($sr);
		}
		
	function etiqueta_mst($ep)
		{
			$se = '';
			if (strlen($ep->ean13) > 0)
			{
				$img = $ep->img;
				if (strlen($ep->img) > 0)
					{ $se .= '<img src="'.$ep->img.'" height=60>'; }
	
				$se .= '<BR><nobr>'.$this->barcod($ep->ean13).'</nobr>';
				$se .= '<div style="width: 130px; height:60px; background-color: #F0F0F0;">';
				$se .= '<font class="lt1"><center><B>';
				$se .= 'TAM:'.$ep->tam.'<BR>';
				$se .= $ep->nome;
				$se .= '</div>';
				$se .= '<nobr>';
				$se .= '<font style="font-size:10px;">R$</font>';
				$se .= '<B><font style="font-size:30px;">'.number_format($ep->preco,2).'</font></B>';
				$se .= '<BR><font class="lt0">';
				if ($ep->comissao > 0)
					{ $se .= '<B>'.$ep->comissao.'</B>'; }
				$se .= '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);
				}
			return($se);
		}

	function etiqueta_argox_mst($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			/* Logo */
			//$st .= $argox->ppla_texto('PB9',($posx+180),($posy+24),180,'Y11000');
			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+115),($posy+22),1);

			/* Tamanho - 0131-0100 */ 
			$st .= $argox->ppla_texto('TAM. '.$ex->tam,($posx+78),($posy+22),270,'311000');

			/* Preco */
			$vlr = format_fld($ex->preco,'2');			
			$st .= $argox->ppla_texto('R$ '.$vlr,($posx+77),($posy+27),1,'411000');
			
			/* Descricao */
			$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+166),($posy+20),'1','911001');
			$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+157),($posy+20),'1','911001');
			$st .=  $argox->ppla_texto(substr($ex->nome,50,25),($posx+148),($posy+20),'1','911001');
			
			/* Referência */
			$st .=  $argox->ppla_texto('REF.:'.$ex->codigo,($posx+175),($posy+20),'1','911001');
			
			/* Comissão */
			$se = '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);
			$st .=  $argox->ppla_texto($ex->comissao.$se,($posx+104),($posy+40),'1','911001');

			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+4),($posy+17),1);

			/* Descricao */
			$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+48),($posy+24),'1','911001');
			$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+39),($posy+24),'1','911001');

			/* Tamanho - 0131-0100 */ 
			$st .= $argox->ppla_texto('TAM. '.$ex->tam,($posx+30),($posy+16),270,'211000');

			return($st);
		}

	function etiqueta_argox_mst_a($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			/* Logo */
			//$st .= $argox->ppla_texto('PB9',($posx+180),($posy+24),180,'Y11000');
			/* Codigo de Barras */
//			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+115),($posy+22),1);

			/* Tamanho - 0131-0100 */ 
//			$st .= $argox->ppla_texto('TAM. '.$ex->tam,($posx+78),($posy+22),270,'311000');

			/* Preco */
			$vlr = format_fld($ex->preco,'2');			
			$st .= $argox->ppla_texto('R$ '.$vlr,($posx+53),($posy+27),1,'411000');
			
			/* Descricao */
//			$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+166),($posy+20),'1','911001');
//			$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+157),($posy+20),'1','911001');
//			$st .=  $argox->ppla_texto(substr($ex->nome,50,25),($posx+148),($posy+20),'1','911001');
			
			/* Referência */
//			$st .=  $argox->ppla_texto('REF.:'.$ex->codigo,($posx+175),($posy+20),'1','911001');
			
			/* Comissão */
			$se = '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);
//			$st .=  $argox->ppla_texto($ex->comissao.$se,($posx+104),($posy+40),'1','911001');

			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+3),($posy+17),1);

			/* Descricao */
			$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+44),($posy+24),'1','911001');
			$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+37),($posy+24),'1','911001');

			/* Tamanho - 0131-0100 */ 
			if($ex->validade<19720101)
			{
			 $st .= $argox->ppla_texto('TAM.'.$ex->tam,($posx+30),($posy+16),270,'211000');
			}else{
			 $st .= $argox->ppla_texto('VAL.'.substr($ex->validade,4,2).'/'.substr($ex->validade,2,2),($posx+10),($posy+16),270,'211000');
			}

			return($st);
		}

	function etiqueta_argox_mst_b($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			/* Preco */
			$vlr = format_fld($ex->preco,'2');			
			$st .= $argox->ppla_texto('R$ '.$vlr,($posx+00),($posy+15),1,'411000');
			
			/* Comissão */
			$se = '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);

			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+34),($posy+15),1);

			/* Referência */
			$st .=  $argox->ppla_texto('REF.:'.$ex->codigo,($posx+20),($posy+20),'1','911001');

			/* Descricao */
			$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+85),($posy+15),'1','911001');
			$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+75),($posy+15),'1','911001');

			/* Tamanho - 0131-0100 */ 
			$st .= $argox->ppla_texto(''.$ex->tam,($posx+10),($posy+128),270,'311000');

			return($st);
		}
		
	function etiqueta_argox_mst_c($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			
			/* Comissão */
			$se = '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);

			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+3),($posy),1);

			/* Referência */
			$st .=  $argox->ppla_texto('REF.:',($posx+37),($posy+15),'1','911001');
			$st .=  $argox->ppla_texto(''.$ex->codigo,($posx+37),($posy+32),'1','311000');

			/* Descricao */
			//$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+45),($posy+10),'1','911001');
			//$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+60),($posy+15),'1','911001');

			/* Tamanho - 0131-0100 */
			//$st .=  $argox->ppla_texto('TAM.',($posx+37),($posy+75),'1','911001'); 
			$st .= $argox->ppla_texto(''.$ex->tam,($posx+37),($posy+80),1,'311000');

			return($st);
		}		
	function etiqueta_argox_mst_d($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			
			/* Comissão */
			$se = '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);

			/* Codigo de Barras */
			

			/* Referência */
			$st .=  $argox->ppla_texto(''.$ex->preco,($posx+37),($posy+30),'1','311000');
			$st .=  $argox->ppla_texto('R:'.$ex->codigo,($posx+8),($posy+0),'1','111001');
			$st .=  $argox->ppla_texto('V'.$ex->validade,($posx+0),($posy+0),'1','111000');
			$st .=  $argox->ppla_texto(''.$ex->nome,($posx+20),($posy+5),'1','011000');

			$st .= $argox->ppla_barras_upca2($ex->ean13,($posx+80),($posy+0),1);

			return($st);
		}
    function etiqueta_argox_mst_e($ex,$posx,$posy)
        {
            $argox = new argox;
            $st = '';
	    	    
            /* Preco */
            $vlr = format_fld($ex->preco,'2');          
            $st .= $argox->ppla_texto('R$ '.$vlr,($posx),($posy+33),1,'311000');
        
            /*Vencimento e Tamanho - 0131-0100 */ 
            if($ex->validade<19720101)
            {
             $st .= $argox->ppla_texto(''.$ex->tam,($posx),($posy+6),1,'111000');
            }else{
             $st .= $argox->ppla_texto(''.substr($ex->validade,4,2).'/'.substr($ex->validade,2,2),($posx),($posy+6),1,'111000');
            }

            /* Descricao */
            $st .=  $argox->ppla_texto(substr($ex->nome,0,20),($posx+16),($posy+6),'1','111001');

            /* Codigo de Barras */
            $st .= $argox->ppla_barras_upca3($ex->ean13,($posx+25),($posy),1);

                  return($st);
        }
	//sem valor utilizado na promoção estoura balão	
	function etiqueta_argox_mst_f($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			/* Preco */
			$vlr = format_fld($ex->preco,'2');			
			//$st .= $argox->ppla_texto('R$ '.$vlr,($posx+00),($posy+15),1,'411000');
			
			/* Comissão */
			$se = '-'.date('Hi').'-'.date("d-m").'-'.substr(date("Y"),3,1);

			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx+34),($posy+15),1);

			/* Referência */
			$st .=  $argox->ppla_texto('REF.:'.$ex->codigo,($posx+20),($posy+20),'1','911001');

			/* Descricao */
			$st .=  $argox->ppla_texto(substr($ex->nome,0,25),($posx+85),($posy+15),'1','911001');
			$st .=  $argox->ppla_texto(substr($ex->nome,25,25),($posx+75),($posy+15),'1','911001');

			/* Tamanho - 0131-0100 */ 
			$st .= $argox->ppla_texto(''.$ex->tam,($posx+10),($posy+128),270,'311000');

			return($st);
		}
		
	function etiqueta_argox_mst_g($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca($ex->ean13,($posx),($posy),1);

			return($st);
		}		
			
	function etiqueta_argox_mst_h($ex,$posx,$posy)
		{
			$argox = new argox;
			$st = '';
			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca4($ex->ean13,($posx),($posy),1);
			
			$st .= $argox->ppla_texto($ex->codigo,($posx+35),($posy+95),'90','911001');
			
			$cla = $ex->classe;
			$st .= $argox->ppla_texto(substr($cla, 10,10),($posx+5),($posy+110),'1','911001');
			$st .= $argox->ppla_texto(substr($cla, 0,10),($posx+20),($posy+110),'1','911001');
			
			$st .= $argox->ppla_texto($ex->preco,($posx+35),($posy+160),'90','911004');
			
			return($st);
		}
	function etiqueta_argox_mst_i($ex,$posx,$posy)
        {
            $argox = new argox;
            $st = '';
	    	    
            /* Preco */
            $vlr = format_fld($ex->preco,'2');          
            //$st .= $argox->ppla_texto('R$ '.$vlr,($posx),($posy+33),1,'311000');
        
            /*Vencimento e Tamanho - 0131-0100 */ 
            
            $st .= $argox->ppla_texto('TAM: '.$ex->tam,($posx+45),($posy+4),1,'211001');
            
            
            /* Codigo de Barras */
            $st .= $argox->ppla_barras_upca3($ex->ean13,($posx+5),($posy),1);
            
            /* Descricao */
            $st .=  $argox->ppla_texto(substr($ex->nome,0,10),($posx+45),($posy+50),'1','211001');


                  return($st);
        }

	function etiqueta_argox_mst_j($ex,$posx,$posy)
		{
			/* Metodo para etiquetas da Joias
			 * foi utilizado variaveis já existentes
				pontos completo		
				$e1->ean13  = $num1.$num2.$num3.$num4;
	        	primeiro numero
			    $e1->codigo = $num1;
			 	peso 
	        	$e1->preco  = $num2.','.$num3;
			    validador
	        	$e1->tam    = $num4;
			 	modelo da peça
	        	$e1->nome   = $nome1;
			 	material da peça 
	        	$e1->comissao   = $nome2;
			 	$e1->validade   = 'Fonzaghi Joias';
			*/
			
			$argox = new argox;
			$st = '';
			/* Codigo de Barras */
			$st .= $argox->ppla_barras_upca4($ex->ean13,($posx),($posy),1);
			$st .= $argox->ppla_texto($ex->validae,($posx+10),($posy),'1','911001');
			
			$st .= $argox->ppla_texto($ex->codigo,($posx+25),($posy+110),'1','911001');
			$st .= $argox->ppla_texto($ex->preco,($posx+35),($posy+110),'1','911001');
			$st .= $argox->ppla_texto($ex->tam,($posx+45),($posy+110),'1','911001');
			
			$st .= $argox->ppla_texto($ex->comissao,($posx+5),($posy+110),'1','911001');
			$st .= $argox->ppla_texto($ex->nome,($posx+20),($posy+110),'1','911001');
			
			
			return($st);
		}
	 

		
	function etiqueta_argox_1x1j($e1)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 17;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_j($e1,$posx,$posy);
			$st .= $argox->ppla_end_row();
			return($st);
		}	    	
        
	function etiqueta_argox_3x1($e1,$e2,$e3)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 0;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst($e1,$posx,$posy);
			$st .= $this->etiqueta_argox_mst($e2,$posx,$posy+133);
			$st .= $this->etiqueta_argox_mst($e3,$posx,$posy+266);
			$st .= $argox->ppla_end_row();
			return($st);
		}
		
	function etiqueta_argox_3x1a($e1,$e2,$e3)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 0;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_a($e1,$posx,$posy);
			$st .= $this->etiqueta_argox_mst_a($e2,$posx,$posy+138);
			$st .= $this->etiqueta_argox_mst_a($e3,$posx,$posy+275);
			$st .= $argox->ppla_end_row();
			return($st);
		}
		
	function etiqueta_argox_3x1b($e1,$e2,$e3)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 0;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_b($e1,$posx,$posy);
			$st .= $this->etiqueta_argox_mst_b($e2,$posx,$posy+135);
			$st .= $this->etiqueta_argox_mst_b($e3,$posx,$posy+270);
			$st .= $argox->ppla_end_row();
			return($st);
		}
		
	function etiqueta_argox_3x1c($e1,$e2,$e3)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 0;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_c($e1,$posx,$posy);
			$st .= $this->etiqueta_argox_mst_c($e2,$posx,$posy+115);
			$st .= $this->etiqueta_argox_mst_c($e3,$posx,$posy+230);
			$st .= $argox->ppla_end_row();
			return($st);
		}
	function etiqueta_argox_3x1d($e1,$e2,$e3)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 0;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_d($e1,$posx,$posy);
			$st .= $this->etiqueta_argox_mst_d($e2,$posx,$posy+120);
			$st .= $this->etiqueta_argox_mst_d($e3,$posx,$posy+235);
			$st .= $argox->ppla_end_row();
			return($st);
		}	
    function etiqueta_argox_3x1e($e1,$e2,$e3)
        {
            global $ppla_start;
            $posx = 0;
            $posy = 0;
            $argox = new argox;
            /* Codigo de Barras - 0211-0117*/ 
            $st .= $argox->ppla_start_row();
            $st .= $this->etiqueta_argox_mst_e($e1,$posx,$posy);
            $st .= $this->etiqueta_argox_mst_e($e2,$posx,$posy+114);
            $st .= $this->etiqueta_argox_mst_e($e3,$posx,$posy+229);
            $st .= $argox->ppla_end_row();
            return($st);
        }    
    //sem valor
	function etiqueta_argox_3x1f($e1,$e2,$e3)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 0;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_f($e1,$posx,$posy);
			$st .= $this->etiqueta_argox_mst_f($e2,$posx,$posy+135);
			$st .= $this->etiqueta_argox_mst_f($e3,$posx,$posy+270);
			$st .= $argox->ppla_end_row();
			return($st);
		}
		
	function etiqueta_argox_1x1g($e1)
		{
			global $ppla_start;
			$posx = 150;
			$posy = 215;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_g($e1,$posx,$posy);
			$st .= $argox->ppla_end_row();
			return($st);
		}	
		
	function etiqueta_argox_1x1h($e1)
		{
			global $ppla_start;
			$posx = 0;
			$posy = 17;
			$argox = new argox;
			/* Codigo de Barras - 0211-0117*/ 
			$st .= $argox->ppla_start_row();
			$st .= $this->etiqueta_argox_mst_h($e1,$posx,$posy);
			$st .= $argox->ppla_end_row();
			return($st);
		}	    	
	
			
	function etiqueta_argox_3x1i($e1,$e2,$e3)
        {
            global $ppla_start;
            $posx = 0;
            $posy = 0;
            $argox = new argox;
            /* Codigo de Barras - 0211-0117*/ 
            $st .= $argox->ppla_start_row();
            $st .= $this->etiqueta_argox_mst_i($e1,$posx,$posy);
            $st .= $this->etiqueta_argox_mst_i($e2,$posx,$posy+114);
            $st .= $this->etiqueta_argox_mst_i($e3,$posx,$posy+229);
            $st .= $argox->ppla_end_row();
            return($st);
        }    
	
				
	function etiqueta_linha_3x1($e1,$e2,$e3)
		{
			//$vlr1 = $e1->ean13;
			$sx = '<table width="100%" cellpadding=5 cellspacing=0 >';
			$sx .= '<TR>';
			$sx .= '<TD width="33%" align="center">';
			$sx .= $this->etiqueta_mst($e1);

			$sx .= '<TD width="33%" align="center">';
			$sx .= $this->etiqueta_mst($e2);

			$sx .= '<TD width="33%" align="center">';
			$sx .= $this->etiqueta_mst($e3);

			$sx .= '</table>';
		return($sx);
		}
		
				
	function barcod($vlr)
			{
				$i = 0;
				$vlr = sonumero(substr($vlr,0,11));
								
				while (strlen($vlr) < 11) { $vlr = '0'.$vlr; }
				$ca = array(3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1);
				$to = 0;
				for ($ra=0;$ra < strlen($vlr);$ra++)
					{
						$rb = strlen($vlr)-$ra-1;
						$ta = round(substr($vlr,$rb,1)) * $ca[$ra];
						$to = $to + $ta;
					}
				while ($to > 10) { $to = ($to - 10); }
				$to = 10-$to; if ($to == 10) { $to = 0; }
				
				$vlr .= $to;

				$sr = '<font face="EanP36Tt" style="font-size: 48px;">';
				$sr .= '!'.substr($vlr,0,6).'-';
				$cc = array('0'=>'a', '1'=>'b','2'=>'c','3'=>'d','4'=>'e','5'=>'f','6'=>'g','7'=>'h','8'=>'i','9'=>'j');
				for ($ra=6;$ra < 12;$ra++)
					{ $sr .= $cc[substr($vlr,$ra,1)]; }
				$sr .= '!'; 
				$sr .= '</font>';

				return($sr);	
			}
	}
?>
