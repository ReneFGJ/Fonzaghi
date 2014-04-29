<?
class etiqueta
	{
	var $ean13;
	var $codigo;
	var $tam;
	var $preco;
	var $img;
	var $nome;
	
	function etiqueta_total_imprimir()
		{
			$sql = "select count(*) as total from produto_estoque ";
			$sql .= " where pe_status = '@' ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}
	function etiquetas_imprimir_lista()
		{
			$sql = "select * from produto_estoque ";
			$sql .= " inner join produto on pe_produto = p_codigo ";
			//$sql .= " where pe_status = '@' ";
			$sql .= " order by p_codigo, pe_tam, pe_ean13 ";
			$rlt = db_query($sql);
			$eti = array();
			while ($line = db_read($rlt))
				{
					array_push($eti,array(
					$line['pe_ean13'],$line['pe_produto'],
					$line['p_descricao'],$line['pe_vlr_venda'],
					$line['pe_promo'],$line['pe_tam'],
					trim($line['p_descricao'])));
				}
			return($eti);
		}
	
	/* Nova Página */
	function np()
		{
			$sx = '<p style="page-break-before: always;"></p>';
			return($sx);
		}
	function etiqueta($ep)
		{
			$se = '';
			if (strlen($ep->img) > 0)
				{ $sx .= '<img src="'.$ep->img.'" height=60><BR>'; }
			$se .= $e1->nome;
			$se .= '<BR><nobr>'.$this->barcod($ep->ean13);
			$se .= '<BR><nobr>';
			$se .= '<font style="font-size:10px;">R$</font>';
			$se .= '<B>'.number_format($ep->preco,2).'</B>';
			return($se);
		}
	function etiqueta_linha_3x1($e1,$e2,$e3)
		{
			//$vlr1 = $e1->ean13;
			$sx = '<table width="100%" cellpadding=5 cellspacing=0 >';
			$sx .= '<TR>';
			$sx .= '<TD width="33%" align="center">';
			$sx .= $this->etiqueta($e1);

			$sx .= '<TD width="33%" align="center">';
			$sx .= $this->etiqueta($e2);

			$sx .= '<TD width="33%" align="center">';
			$sx .= $this->etiqueta($e3);

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

				$sr = '<font face="EanP36Tt" style="font-size: 36px;">';
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
