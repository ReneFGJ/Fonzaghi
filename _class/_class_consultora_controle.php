<?php
/**
 * Controle consultoras
 * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
 * @copyright Copyright (c) 2013 - sisDOC.com.br
 * @access public
 * @version v.0.13.42
 * @package Classe
*/
class consultora_controle
{
	
	function verifica_consultora($id='000000')
		{
			global $base_name,$base_server,$base_host,$base_user,
					$user,$setft,$setdp;
			require('../db_fghi_210.php');
			setdp();
			
			$i=0;
			$xvld=0;
			$sx='';
			$sql='';
			
			while($i<count($setdp[0]))
			{
				if (strlen($setdp[2][$i]) > 0)
				{
				if(strlen(trim($sql))!=0)
				{ $sql .=" union "; }
				$sql .= "select * from ".$setdp[2][$i]."
					   where dp_status='@' and dp_cliente= '".$id."' 
					   ";
				}	
				$i++;		
			}
			
			$rlt = db_query($sql);
				while($line=db_read($rlt))
				{
					$xvld = 1;
					$tx .="</br>".$line['dp_content']."";
				}	
				
			if($xvld==0)
				{
					$sx .= '<div style="background-color:#00CF00;""><center><h1>Liberada';
				}else{
					$sx .= '<div style="background-color:#FF0000;"><center><h1>Pendência</h1><br><br>'.$tx;
				}   
			
			$sx .='</div>';

			return($sx);
		}		

	}

	
?>