<?php
/**
 * Consultoras ativas
 * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
 * @copyright Copyright (c) 2013 - sisDOC.com.br
 * @access public
 * @version v.0.14.14
 * @package Classe
 * @subpackage consultora_ativa
*/
class consultora_ativa
{
	var $include='../';
	 
	function atualiza_status($id='')
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			if(strlen(trim($id))!=0)
			{
				require($this->include."db_cadastro.php");
				$sql = "update cadastro set cl_status = 'A' where cl_cliente = '".$id."'";
				$rlt = db_query($sql);
				return(1);	
			}
			
			return(0);
		}		
		
	function inativa_status_rotina()
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$setft;
		require($this->include."db_cadastro.php");
		$sql = "update cadastro set cl_status = 'I' where cl_status='A'";
		$rlt = db_query($sql);
		return(1);
	}	
	function atualiza_status_rotina()
	{
		global $base_name,$base_server,$base_host,$base_user,$user,$setft;
		$i=0;
		$xvld=0;
		require($this->include."db_fghi_210.php");
		while($i<count($setft))
		{
			if($xvld==1)
			{
				 $sql.= " union ";
			}
			$data = date('Ymd',mktime(0,0,0,date('m')-2,date('d'),date('Y')));
			$sql .= 'select distinct(dp_cliente) from '.$setft[2][$i].' where dp_data>='.$data."";
			$xvld = 1;	
			$i++;
		}
		$rlt = db_query($sql);
		$xvld=0;
		while($line=db_read($rlt))
		{
			if($xvld==1)
			{
				 $tx.= " or ";
			}
			$tx .=	"cl_cliente = '".$line['dp_cliente']."'";
			$xvld=1;
		}
		require($this->include."db_cadastro.php");
		$sql = "update cadastro set cl_status='A' where ".$tx;
		$rlt = db_query($sql);
		return(1);
	}
	
	function import_cadastro_completo($limite){
		global $base_name,$base_server,$base_host,$base_user;
		require($this->include."db_cadastro.php");
		$sql = 'select * from cadastro_completo 
				inner join (select * from cadastro) as tb 
				on cl_cliente = pc_codigo 
				where cl_cep is null
				limit '.$limite.'
				';
				
		$rlt = db_query($sql);
		$sx = '<table><th>Cliente</th>
		<th>Cep</th>
		<th>Bairro</th>
		<th>Latitude</th>
		<th>longitude</th></tr>
		';
		while($line = db_read($rlt)){
			$sql2 = "update cadastro set cl_cep = '".trim($line['pc_cep'])."',
									   cl_bairro = '".trim($line['pc_bairro'])."',
									   cl_latitude = '".$line['pc_latitude']."',
									   cl_longitude = '".$line['pc_longitude']."' 	 
					where cl_cliente = '".$line['cl_cliente']."'";
			$rlt2 = db_query($sql2);
			
			$sx .= '<tr><td>'.$line['cl_cliente'].'</td>
						<td>'.trim($line['pc_cep']).'</td>
						<td>'.trim($line['pc_bairro']).'</td>
						<td>'.trim($line['pc_latitude']).'</td>
						<td>'.trim($line['pc_longitude']).'</td>
						</tr>';	
					 
			
		}		
		return($sx);			
	}
	


	}
	
?>