<?php
/**
 * Regionais
 * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
 * @copyright Copyright (c) 2013 - sisDOC.com.br
 * @access public
 * @version v.0.14.14
 * @package Classe
 * @subpackage Regionais
*/
class regional
{
	var $tabela = "regionais";
	var $include_class = '../';
	var $tabela_categoria = "regionais_categorizacao";
	var $media_lj =  array();
	var $tt_venda_lj = array();
	var	$tt_acertos_lj = array();
	var $tt_cons_lj = array();
	var $media_cons_lj = array();
	var $ticket_medio_lj = array();
	var $dt1 = '';
	var $dt2 = '';
	var $regional = '';
	var $regional_array = array();
	var $loja = ''; 
	var $lojas_array =  array();
	var $query_cliente = '';
	function updatex()
	{
		global $base;
		$c = 'rg';
		$c1 = 'id_'.$c;
		$c2 = $c.'_codigo';
		$c3 = 5;
		$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
		if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
		$sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) "; 
		$rlt = db_query($sql);
		return(0);
	}

	
	function cp()
	{
		$op = $this->lista_bairros_option();
		$cp = array();
		array_push($cp,array('$H8','id_rg','',False,True));
		array_push($cp,array('$H8','rg_codigo','',False,True));
		array_push($cp,array('$O '.$op,'rg_bairro','Bairro',False,True));
		array_push($cp,array('$C8','rg_main','Grupo principal',False,True));
		array_push($cp,array('$Q rg_bairro:rg_codigo:select * from '.$this->tabela.' where rg_main = \'1\' and rg_ativo=1 order by rg_bairro','rg_ref','Referência',False,True));
		array_push($cp,array('$O 1:SIM&0:NÃO','rg_ativo','Ativo',False,True));
		return($cp);
	}
	 
	 
	 function bairros_sem_vinculos()
	 {
	 	global $base_name,$base_server,$base_host,$base_user;
		require('../db_cadastro.php');
        $sql = 'select cl_bairro from cadastro left join 
				(select * from regionais) as tb on cl_bairro=rg_bairro 
				where rg_bairro is null and 
      				cl_bairro is not null 
				group by  cl_bairro
				order by cl_bairro';
        $rlt = db_query($sql);
		$sx  = '<h1>Bairros não vinculados a regionais - VERIFICAR</h1>';
        $sx .= '<table><tr>';
		$i=0;
        while ($line = db_read($rlt)) {
        		
        	if($i==5){
	        	$sx .= '</tr><tr>';
				$i=0;	
        	}
			
            $sx .= '<td>'.trim($line['cl_bairro']).'<td/>';
			$i++;
     	}
        return($sx);
    }
	 
	 function lista_bairros_option()
	 {
	 	global $base_name,$base_server,$base_host,$base_user;
		require('../db_cadastro.php');
        $sql = 'select cl_bairro from cadastro left join 
				(select * from regionais) as tb on cl_bairro=rg_bairro 
				where rg_bairro is null and 
      				cl_bairro is not null 
				group by  cl_bairro
				order by cl_bairro';
        $rlt = db_query($sql);
        $op = ' :Selecione o bairro';
        while ($line = db_read($rlt)) {
            $descricao=trim($line['cl_bairro']);
            $codigo=trim($line['cl_bairro']);
            $op .= '&'.$codigo.':'.$descricao;
     	}
        return($op);
    }
	function lista_regionais()
	{
		global $base_name,$base_server,$base_host,$base_user;
		require('../db_cadastro.php');
		$sql = "select * from regionais
				where rg_ativo=1 and
					  rg_main='1'
				order by rg_bairro
		";
		$rlt =	db_query($sql);
		
		while($line = db_read($rlt)){
			$i++;	
			$sx[$i] .='<table width="100%">';
			$sx[$i] .='<tr><td width="100%" class="botao-geral">'.$line['rg_bairro'].'</td></tr>';
			
			$sql2 = "select * from regionais
					 where rg_main <> '1' and 
					 	   rg_ativo = 1 and
					 	   rg_ref = '".$line['rg_codigo']."'
					 order by rg_bairro	   
			";
			$rlt2 = db_query($sql2);
			while($line2 = db_read($rlt2)){
				$sx[$i] .= '<tr><td width="100%">'.$line2['rg_bairro'].'</td></tr>';
				
			}
			$sx[$i] .='</table>';			
			
		} 
		
		return($sx);
	}
	
	function option_regionais(){
		global $base_name,$base_server,$base_host,$base_user;
		require('../db_cadastro.php');
		$sql = "select distinct(regionais.rg_bairro) 
				from regionais 
					inner join (select * from regionais) as tb 
						on tb.rg_ref=regionais.rg_codigo
				where tb.rg_ativo=1
				order by regionais.rg_bairro
		";
		$rlt =	db_query($sql);
		$i=1;
		$regionais = array();
		array_push($regionais,array('Todos','0'));
		while($line = db_read($rlt)){
			array_push($regionais,array($line['rg_bairro'],$i));
			$i++;
		}	
		
		return($regionais);
	}
	function option_lojas()
	{
		global $base_name,$base_server,$base_host,$base_user, $setlj;
		$lojas = array();
		array_push($lojas,array('Todas',count($setlj[1])+1));
		for ($i=0; $i < count($setlj[1]) ; $i++) { 
			array_push($lojas,array($setlj[1][$i],$i));
		}
		
		return($lojas);
	}

	function vendas_lojas()
	{
		global $base_name,$base_server,$base_host,$base_user, $setlj;
		
			for ($i=0; $i < count($setlj[1]); $i++) { 
				require($this->include_class.$setlj[3][$i]);
				$sql = "select avg(kh_vlr_vend),sum(kh_vlr_vend),count(*),count(distinct(cliente)) as ttcons 
									from kits_consignado inner join 
						(".$this->query_cliente.") as tb_cliente
						on cliente=kh_cliente
						where 	kh_acerto>=".$this->dt1." and
								kh_acerto<=".$this->dt2."
				";
				$rlt = db_query($sql);
				while($line = db_read($rlt)){
					$this->ticket_medio_lj[$i] = $line['sum']/$line['ttcons'];	
					$this->media_lj[$i] =  $line['avg'];
					$this->tt_venda_lj[$i] =  ($line['sum']/1000);
					$this->tt_acertos_lj[$i] =  $line['count'];
					$this->tt_cons_lj[$i] =  $line['ttcons'];
					
				}	
			}
		
		return(1);
	}
	function mostra_vendas_loja()
	{
		global $setlj;
		$sx = '<table>';
		$sx .= '<tr>
					<th class="botao-geral" align="left" width="20%">Lojas</th>
					<th class="botao-geral" align="right" width="20%">Ticket Médio</th>
					<th class="botao-geral" align="right" width="20%">Média Acertos</th>
					<th class="botao-geral" align="right" width="20%">Total Vendido(pontos)</th>
					<th class="botao-geral" align="right" width="20%">Total Acertos</th>
					<th class="botao-geral" align="right" width="20%">Total Consultoras</th>
				</tr>';
		for ($i=0; $i < count($setlj[1]); $i++) 
		{ 	
			$sx .= '<tr>
						<td class="botao-geral" align="left" width="20%">'.$setlj[1][$i].'</td>
						<td class="tabela01" align="right" width="20%">'.number_format($this->ticket_medio_lj[$i],2).'</td>
						<td class="tabela01" align="right" width="20%">'.number_format($this->media_lj[$i],2).'</td>
						<td class="tabela01" align="right" width="20%">'.number_format($this->tt_venda_lj[$i],2).'</td>
						<td class="tabela01" align="center" width="20%">'.$this->tt_acertos_lj[$i].'</td>
						<td class="tabela01" align="center" width="20%">'.$this->tt_cons_lj[$i].'</td>
					</tr>';
					
					$tt2 += $this->tt_venda_lj[$i];
					$tt3 += $this->tt_acertos_lj[$i];
					$tt4 += $this->tt_cons_lj[$i];
		}
	
		$sx .= '<tr>
						<td class="botao-geral" align="left" width="20%">Total</td>
						<td class="tabela01" align="right" width="20%">'.number_format((($tt2/$tt4)*1000),2).'</td>
						<td class="tabela01" align="right" width="20%">'.number_format((($tt2/$tt3)*1000),2).'</td>
						<td class="tabela01" align="right" width="20%">'.number_format($tt2,2).'</td>
						<td class="tabela01" align="center" width="20%">'.$tt3.'</td>
						<td class="tabela01" align="center" width="20%">'.$tt4.'</td>
					</tr>';
		$sx .= '</table>';			
		return($sx);
	}
	function vendas_consultoras_lojas($lj)
	{
		global $base_name,$base_server,$base_host,$base_user;
	
		$sql = "select kh_cliente, count(*), avg(kh_vlr_vend), sum(kh_vlr_vend) from kits_consignado
				where 	kh_acerto>=".$this->dt1." and
						kh_acerto<=".$this->dt2."
				group by kh_cliente
				order by kh_cliente
				";
		$rlt = db_query($sql);
		while($rlt){
			$tt_cons_lj = array();
			$media_cons_lj = array();
		}		
		return($sx);		
	}
	
	
}
	
?>