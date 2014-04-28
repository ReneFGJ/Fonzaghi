<?php
    /**
     * Férias
     * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v.0.14.05
     * @package Classe
     * @subpackage Férias
    */
    

class ferias
{
	var $cracha;
	var $id_us; 
	
	var $tabela = "usuario_ferias";
	var $tabela_saldo = "usuario_ferias_saldo";
	
	function funcionarios_ativos()
	{
		global $base_name,$base_server,$base_host,$base_user,$user;
		require("../db_fghi.php");
		$sql = " select * from usuario
				where us_status='A'		
			 ";
		$rlt = db_query($sql);
		while($line=db_read($rlt))
		{
			$admissao=$line['us_dtadm'];
			$saldo=30;
			$status='1';
			$cracha=$line['us_cracha'];
			$sx .= $this->insere_saldos_ferias($admissao, $saldo,'','',$status, $cracha);
		}
		return($sx);
	}
	
	function insere_saldos_ferias($admissao='', $saldo='',$aquis_i='',$aquis_f='',$status='', $cracha='')
	{
		global $base_name,$base_server,$base_host,$base_user,$user;
		require("../db_fghi.php");
		if((strlen(trim($aquis_i))==0) and(strlen(trim($aquis_f))==0))
		{
			$ano=date('Y');
			$mes=substr($admissao, 4,2);
			$dia=substr($admissao, 6,2);
			$aquis_i = date('Ymd',mktime(0,0,0,$mes,$dia,($ano-1)));
			$aquis_f = date('Ymd',mktime(0,0,0,$mes,($dia-1),$ano));
			$ano = $ano-1;
			
		}
		$sql1 = "select * from usuario_ferias_saldo 
				where fes_cracha='$cracha' and 
						fes_ano='$ano'
						";
		$rlt1 = db_query($sql1);
		if(!($line1=db_read($rlt1)))
		{	
				$sql = "insert into usuario_ferias_saldo 
					   (fes_saldo,fes_aquis_inicial,fes_aquis_final,fes_status,fes_cracha,fes_ano) 
				values (".$saldo.",".$aquis_i.",".$aquis_f.",'".$status."','".$cracha."',".$ano.")
				";
				$rlt = db_query($sql);
				$this->updatex();
				return(1);
		}
		return(0);
	}
	function updatex()
	{
			$dx1 = 'fes_cod';
			$dx2 = 'fes';
			$dx3 = 8;
			$sql = "update usuario_ferias_saldo 
			set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) 
			where (length(trim(".$dx1.")) < ".$dx3.") or 
			(".$dx1." isnull);";
			$rlt = db_query($sql);
			return(1);
	}
	
    function cp()
        {
        	global $dd;
            $cp = array();
            array_push($cp,array('$H8','id_fe','',false,True));//0
            array_push($cp,array('$HV','',$dd[1],false,True));//1
            array_push($cp,array('$HV','',$dd[2],false,True));//2
            array_push($cp,array('$HV','fe_cod_fes',$dd[3],false,True));//3
            array_push($cp,array('$S2','fe_dias_ferias','Qtd. dias ',false,True));//4
            
            array_push($cp,array('$D','fe_concessao','Concessão ',false,True));//5
			array_push($cp,array('$O F:Férias normais&A:Abono&C:Coletivas&R:Recissão','fe_tipo','Tipo',False,True));//6
            array_push($cp,array('$O E:Efetivada&P:Previsão&I:Inativo','fe_status','Status',False,True));//7
			array_push($cp,array('$H8','fe_log','',false,True));//8
			/* */
			$cod_ferias = $dd[0];
			
			$valida = $this->valida_saldo($cod_ferias,$dd[3],$dd[4]);
			$dd[9] = $valida;
			array_push($cp,array('$H8','','',True,True));//8
			array_push($cp,array('$M','',$this->erro_valida,false,True));//9
			
            return($cp);
        }
	function calcula_saldos($fe_cod_fes)
	{
		$sql = "select sum(fe_dias_ferias), fes_abono 
					from (select * from usuario_ferias) as tb 
					inner join usuario_ferias_saldo on fe_cod_fes=fes_cod 
					where fe_cod_fes='".$fe_cod_fes."' and
						  fe_status<>'I'
					group by fes_abono 
		";
		$rlt = db_query($sql);
		
		if($line = db_read($rlt))
		{
			$saldo = $line['fes_abono']-$line['sum'];	
		}else{
			$saldo = 30;
		}
		
		$sql1 = " update usuario_ferias_saldo set fes_saldo=".$saldo."
				where fes_cod='".$fe_cod_fes."'
		";
		
		$rlt1 = db_query($sql1);
		
		return($sx);
	}	
    function valida_saldo($id_fe,$id_fes,$valor=0)
		{
			global $acao,$dd;
			$valor = round('0'.$valor);
			$sd1=$sd2=0;
			/* calcula saldo em a posicao atual */
			if (strlen($acao)==0) { return(''); }
			$sql = "select fe_cod_fes , sum(fe_dias_ferias) as saldo 
									from usuario_ferias 
									where fe_cod_fes ='".$id_fes."' 
										and id_fe <> ".round($id_fe)."
									group by fe_cod_fes				
						";
			$rlt = db_query($sql);

			if($line = db_read($rlt)) { $sd2 = $line['saldo']; }
			
			/* Consulta saldo original total */
			$sql = " select * 
									from usuario_ferias_saldo
									where fes_cod ='".$id_fes."' 
						";
			$rlt = db_query($sql);

			if($line = db_read($rlt))
			{ $sd1 = $line['fes_abono']; }
						
			
			$saldo = ($sd1-$sd2-$valor);
						
			if($saldo>=0)
			{
				$this->erro_valida = '<font color="red">Valor salvo</font>';
				return(1);	
			}else{
				
				$this->erro_valida = '<font color="red">Valor superior ao saldo ('.($sd1-$sd2).' dias)</font>';
				return('');
			}
			
			
		}
		
 	function row()
        {
        global $editar,$busca,$tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
        $this->tabela = "usuario_ferias";
        $tabela = "usuario_ferias";
        $label = "Cadastro de férias";
        /* Páginas para Editar */
        $http_edit = 'ferias_ed.php'; 
        $offset = 20;
		$editar=true;
        $order  = "fe_cod_fes";
		$busca = true;        
        $cdf = array('id_fe','fe_dias_ferias','fe_log','fe_status','fe_concessao','fe_tipo');
        $cdm = array('Dias','Log','Status','Concessao','Tipo');
        $masc = array('#','#','#','#','#','#','#','#','','','','','');
        return(True);
        }
	function lista_ferias()
        {
        	 $cracha = $this->cracha;
			  $sql = "select * from usuario_ferias_saldo 
			  		left join usuario_ferias on fes_cod = fe_cod_fes
		   			where fes_status='1' and
		   				  fes_cracha='".$cracha."'
		   			order by fes_ano desc";
		   			
            $rlt = db_query($sql);
			$sx .= '<h2>Histórico de férias</h2><br>';
			$sx .= '<table width="100%"><tr>
					<th class="tabelaTH" width="20%">Período de aquisição</th>
					<th class="tabelaTH" width="5%">Concessão </th>
					<th class="tabelaTH" width="5%">Saldo</th>
					<th class="tabelaTH" width="5%">Ação</th>					
					<th class="tabelaTH" width="15%">Adquirido em:</th>
					<th class="tabelaTH" width="5%">Tempo (dias)</th>
					<th class="tabelaTH" width="5%">Tipo</th>
					<th class="tabelaTH" width="5%">Status</th>
					<th class="tabelaTH" width="5%">Ação</th>			
			';
			/* Marcador Sessao */
			$xferias = '';
			while($line=db_read($rlt))
			{
				/* Altera cor para previsao */
				$bgp = '';
				if (trim($line['fe_status'])=='P') { $bgp=' bgcolor="#FFD080" '; }
				if (trim($line['fe_status'])=='I') { $bgp=' bgcolor="#EB9C86" '; }
				if (trim($line['fe_status'])=='E') { $bgp=' bgcolor="#20C8A7" '; }
				$ferias = $line['fes_cod'];
				if ($ferias != $xferias)
					{
						$xferias = $ferias;
						$saldo = $line['fes_abono'];
						
						$id_us = $this->id_us;
						$id = $line['fes_cod'];
						$sd = $line['fes_saldo'];
						$sa = $line['fes_abono'];
						$ano = $line['fes_ano'];
						$ai =  $line['fes_aquis_inicial'];
						$ai = stodbr($ai);
						$af =  $line['fes_aquis_final'];
						$af = stodbr($af);
							
						$sx .= '<tr><td COLSPAN=10><h2>'.$ano.'</h2>';
						if ($sd > 0) { $cor = ' bgcolor="#80FF80" '; } else { $cor = ''; }
						$sx .= '<tr>
								<td align="center" class="tabela01">'.$ai.' a '.$af.'</a></td>
								
								<td align="center" class="tabela01">'.$sa.'</a></td>
								<td align="center" class="tabela01" '.$cor.'>'.$sd.'</a></td>
								';
						/* Link - Lancar novo */
						/* $link = '<A HREF="ferias_ed.php?dd1='.$cracha.'&dd0='.$id.'" class="botao-geral">Lançar</A>';*/
						$link = '<A HREF="ferias_ed.php?dd0=&dd1='.$cracha.'&dd3='.$id.'" class="botao-geral">Lançar</A>';
						/* se nao tiver horas, retira o link */
						if ($sd == 0) { $link = ''; }
						$sx .= '<td align="center" class="tabela01">'.$link.'</td>';								
					} else {
						$sx .= '<TR><TD colspan=4>&nbsp;';
					}

					$saldo = $saldo - round($line['fe_dias_ferias']);
					$sx .= '	
							<td '.$bgp.' align="center" class="tabela01">'.stodbr($line['fe_concessao']).'
							('.$line['fe_dias_ferias'].' dias)</td>
							<td '.$bgp.' align="center" class="tabela01">'.$line['fe_dias_ferias'].'</td>
							<td '.$bgp.' align="center" class="tabela01">'.$this->ferias_tipo($line['fe_tipo']).'</td>
							<td '.$bgp.' align="center" class="tabela01">'.$this->ferias_status($line['fe_status']).'</td>';
					
					/* Link - Lancar novo */
					$link = '<A HREF="ferias_ed.php?dd0='.$line['id_fe'].'&dd1='.$cracha.'&dd3='.$id.'" class="botao-geral">Editar</A>';
					/* se nao tiver horas, retira o link */
					if (strlen(trim($line['fe_tipo'])) == 0) { $link = ''; }
					$sx .= '<td align="center" class="tabela01">'.$link.'</td>';								
					
							
					$sx .= '</tr>';
			}
			$sx .= '</table>';
        	return($sx);
        
        }
        
		function ferias_tipo($tipo='')
			{
				switch ($tipo)
					{
					case 'F': $sx = 'Normal'; break;
					case 'R': $sx = 'Recisão'; break;
					case 'C': $sx = 'Coletivas'; break;
					case 'A': $sx = 'Abono'; break;
					default:
						$sx = 'Não definida';
					}
				return($sx);
			}
		function ferias_status($tipo='')
			{
				switch ($tipo)
					{
					case 'I': $sx = 'Cancelada'; break;
					case 'P': $sx = 'Previsão'; break;
					case 'E': $sx = 'Efetivada'; break;
					default:
						$sx = 'Não definida';
					}
				return($sx);
			}
        function lista_itens_ferias($cod_fes,$cracha)
        {
			 $sql = "select * from usuario_ferias 
		   			where fe_status='1' and
		   				  fe_cod_fes='".$cod_fes."'
		   			order by fe_concessao";

		   	$rlt = db_query($sql);
			$sx .= '<center><h2>Resumo </h2><br>';
			$sx .= '<table width="80%"><tr>
					<th class="tabelaTH" width="25%">Inicío Concessão</th>
					<th class="tabelaTH" width="25%">Dias</th>
					<th class="tabelaTH" width="25%">Tipo</th>
					<th class="tabelaTH" width="25%">Log</th></tr>
			
			';
			while($line=db_read($rlt))
			{
				$id=$line['id_fe'];
				$co=$line['fe_concessao'];
				$di=$line['fe_dias_ferias'];
				$ti=$line['fe_tipo'];
				$lo=$line['fe_log'];
				$link = '<A HREF="ferias_ed.php?dd0='.$cracha.'&dd2='.$id.'&dd3='.$cod_fes.'">';	
				$sx .= '<tr><td class="tabela00" align="center" width="25%">'.$link.$co.'</a></td>
							<td class="tabela00" align="center" width="25%">'.$link.$di.'</a></td>
							<td class="tabela00" align="center" width="25%">'.$link.$ti.'</a></td>
							<td class="tabela00" align="center" width="25%">'.$link.$lo.'</a></td>
						</tr>';
			}
			$sx .= '</table>';
        	return($sx);
        }
			

}			
?>