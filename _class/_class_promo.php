<?
    /**
     * Promoções
	 * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
	 * @copyright Copyright (c) 2014 - sisDOC.com.br
	 * @access public
     * @version v.0.14.05
	 * @package promoções
	 * @subpackage classe
    */
    
class promo
{
 
	function cp()
	{
		
	}
		
    function op_categorias()
    {
        $sql = 'select p_class_1, p_descricao from produto where p_promo=1 group by p_descricao,p_class_1 order by p_class_1';
        $rlt = db_query($sql);
        $op = ' :Selecione a categoria';
        while ($line = db_read($rlt)) {
            $descricao=trim($line['p_descricao']);
            $codigo=trim($line['p_class_1']);
            $op .= '&'.$codigo.':'.$descricao;
        }
        return($op);
    }
	
	function op_premios()
    {
		$sql = 'select p_ean13, p_descricao from produto where p_promo=0 group by p_descricao,p_ean13 order by p_descricao';
        $rlt = db_query($sql);
        $op = ' :Selecione a Prêmio';
        while ($line = db_read($rlt)) {
            $descricao=trim($line['p_descricao']);
            $codigo=$line['p_ean13'];
            $op .= '&'.$codigo.':'.$descricao;
        }
        
        $this->op=$op;
        return($op);
    }
	
	function lista_premios($consultora='',$ean13='', $dt1='', $dt2='',$ordem='')
	{
		if(strlen($consultora)>0){ $tx .= " and pe_cliente = '".$consultora."'";	}
		if(strlen($ean13)>0){ $tx .= " and pe_ean13 = '".$ean13."'";	}
		if(strlen($dt1)>0){	 $tx .= " and pe_data >= ".$dt1;	}
		if(strlen($dt2)>0){	 $tx .= " and pe_data <= ".$dt2;	}
		switch ($ordem) {
			case '0':
				$ordem = 'pe_cliente';
				break;
			case '1':
				$ordem = 'pe_ean13';
				break;
			case '2':
				$ordem = 'pe_data';
				break;
			default:
				$ordem = 'pe_data';
				break;
		}
		$sql = 'select * from produto_estoque 
				where 1=1 '.$tx.' 
				order by '.$ordem;
		$rlt = db_query($sql);
		$sx = '<center><table><tr>
				<th class="tabelaTH" width="20%" align="center">Data</th>
				<th class="tabelaTH" width="20%" align="left">Consultora</th>
				<th class="tabelaTH" width="20%" align="center">Produto</th>
				<th class="tabelaTH" width="20%" align="center">Ean13</th>
				';
		while($line=db_read($rlt))
		{
			$link = '<A HREF="#" onclick="newxy2('.chr(39).'rel_promo_show.php?dd0='.trim($line['pe_cliente']).'&dd1='.trim($line['pe_produto']).'&dd2='.trim($line['pe_ean13']).chr(39).',820,700);">';
			$sx .= '<tr>';
			$sx .= '<td class="tabela00" width="20%" align="center">'.$link.$line['pe_data'].'</a></td>';
			$sx .= '<td class="tabela00" width="20%" align="left">'.$line['pe_cliente'].'</td>';
			$sx .= '<td class="tabela00" width="20%" align="center">'.$line['pe_produto'].'</td>';
			$sx .= '<td class="tabela00" width="20%" align="center">'.$line['pe_ean13'].'</td>';
			$sx .= '</tr>';
			
		}
		$sx .= '</table>'; 
		return($sx);
	}

	function lista_premios2($dt1='', $dt2='',$ordem='')
	{
		if(strlen($dt1)>0)
		{
			$tx .= ' and pe_data>='.$dt1.' ';
		}
		if(strlen($dt2)>0)
		{
			$tx .= ' and pe_data<='.$dt2.' ';
		}
		switch ($ordem) {
			case '0':
				$ordemx = 'pe_produto';
				$th = '<tr><th class="tabelaTH" align="left">Produto</th><th class="tabelaTH" align="left">Grupo</th><th class="tabelaTH" align="center">Quantidade</th></tr>';
				break;
			case '1':
				$ordemx = 'p_class_1';
				$th = '<tr><th class="tabelaTH" align="left">Grupo</th><th class="tabelaTH" align="center">Quantidade</th></tr>';
				break;
			default:
				$ordemx = 'p_class_1';
				break;
		}
		$sql = "select * from (
		
							  select * from produto_estoque 
							  inner join produto 
							  on pe_produto=p_codigo
							  ) as tabela 
			  inner join produto_grupos 
			  on p_class_1 = pg_codigo
				where 1=1 ".$tx." and p_promo=0 and pe_status='T'
				order by ".$ordemx;
				
		$rlt = db_query($sql);
		$sx = '<table>'.$th;
		while($line=db_read($rlt))
		{
			$produto = $line['pe_produto'];
			$grupo = $line['p_class_1'];
			
			switch ($ordem) {
			case '0':
			if(($produto<>$produtox) and (strlen(trim($produtox))>0))
			{
				$sx .= '<tr><td class="tabela00" align="left">'.$prod_descricao.'</td>
							<td class="tabela00" align="left">'.$grupo_descricao.'</td>
							<td class="tabela00" align="center">'.$tt.'</td></tr>';	
				$tt=0;	
			}

			$prod_descricao=$line['p_descricao'];
			$grupo_descricao=$line['pg_descricao'];
			$tt++;		
			$ttt++;	
			$produtox=$produto;
				
			break;
			case '1':
			if($grupo<>$grupox )
			{
				$sx .= '<tr><td class="tabela00" align="left">'.$prod_descricao.'</td><td class="tabela00" align="center">'.$tt.'</td></tr>';	
				$tt=0;	
			}
			$prod_descricao=$line['pg_descricao'];
			$tt++;	
			$ttt++;		
			$grupox=$grupo;
				
				break;
			
		}
			$grupo = $line['p_class_1'];
				
			
			
		}
		if($ordem==1)
			{
				$sx .= '<tr><td class="tabela00" align="left">'.$prod_descricao.'</td><td class="tabela00" align="center">'.$tt.'</td></tr>';	
				$sx .= '<tr><td class="tabelaTH" align="left">Total</td><td class="tabelaTH" align="center">'.$ttt.'</td></tr>';
			}
		if($ordem==0)
			{
				$sx .= '<tr><td class="tabela00" align="left">'.$prod_descricao.'</td>
							<td class="tabela00" align="left">'.$grupo_descricao.'</td>
							<td class="tabela00" align="center">'.$tt.'</td></tr>';	
				$sx .= '<tr><td class="tabelaTH" align="left" colspan="2">Total</td>
							<td class="tabelaTH" align="center">'.$ttt.'</td></tr>';
			}
		$sx .= '</table>'; 
		return($sx);
	}
	
}
?>