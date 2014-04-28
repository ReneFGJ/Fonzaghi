<?php
class cursos
	{
		
 /**
  * Cursos
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @author Willian Fellipe Laynes  
  * @copyright Copyright (c) 2013 - sisDOC.com.br
  * @access public
  * @version v.0.13.33
  * @package Classe
  * @subpackage UC00XX - Classe de Interoperabilidade de dados
 */
	var $cliente;
	var $cr = array('','','','','','','','','');
	
	function le($cliente=''){
/*		global $base_name,$base_host,$base_user;	
		require("../db_fghi_210.php");	
				
		$sql = 'select * from capacitacao_participacao where cp_cliente = '.$cliente;
					
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
		{
		
			$this->id_cp = $line['id_cp'];
			$this->cp_curso = $line['cp_curso'];
			$this->cp_cliente = $line['cp_cliente'];
			$this->cp_data = $line['cp_data'];
			$this->cp_instrutor = $line['cp_instrutor'];
			$this->cp_status = $line['cp_status'];
			$this->cp_turma = $line['cp_turma'];
			$sx = 1;
		} else {
			$sx = 0;
		}
*/					
	return($sx);	
			
	}

	function cursados($cliente=''){
		global $base_name,$base_host,$base_user;
		require("../db_fghi_206_cadastro.php");
		$sql = "select * from capacitacao_participacao
				where cp_cliente='$cliente' and cp_status='B'" ;
		$rlt=db_query($sql);
		while ($line=db_read($rlt)){
				switch (trim($line['cp_curso'])) {
				case 'MKT PESSOAL': 		$this->cr[0]='-g';$this->dt[0]=$line['cp_data']; 	break;
				case 'ATD. CLIENTE':		$this->cr[1]='-g';$this->dt[1]=$line['cp_data'];	break;
				case 'FINANÇAS PESSOAIS':	$this->cr[2]='-g';$this->dt[2]=$line['cp_data']; 	break;
				case 'PRODUTO':				$this->cr[3]='-g';$this->dt[3]=$line['cp_data'];	break;
				case 'MOTIVAÇÃO':			$this->cr[4]='-g';$this->dt[4]=$line['cp_data'];	break;
                default: 										break;
		 		}	
		$sx=1;
		} 

		if($sx!=1){$sx=0;}
		
	return($sx);	
	}	
	
	function tabela(){
		$this->cursados($this->cliente);
		
        $sx='<table><tr>
		     <td>'.tips('<img width="60" src="../ico/mk'.$this->cr[0].'.png" />','<div style="background-color:#FFFFFF"; height:350px;">'.substr($this->dt[0],6,2).'/'.substr($this->dt[0],4,2).'/'.substr($this->dt[0],0,4).'</div>').'</td>
			 <td>'.tips('<img width="60" src="../ico/at'.$this->cr[1].'.png" />','<div style="background-color:#FFFFFF"; height:350px;">'.substr($this->dt[1],6,2).'/'.substr($this->dt[1],4,2).'/'.substr($this->dt[1],0,4).'</div>').'</td>
             <td>'.tips('<img width="60" src="../ico/fp'.$this->cr[2].'.png" />','<div style="background-color:#FFFFFF"; height:350px;">'.substr($this->dt[2],6,2).'/'.substr($this->dt[2],4,2).'/'.substr($this->dt[2],0,4).'</div>').'</td>
             <td>'.tips('<img width="60" src="../ico/ps'.$this->cr[3].'.png" />','<div style="background-color:#FFFFFF"; height:350px;">'.substr($this->dt[3],6,2).'/'.substr($this->dt[3],4,2).'/'.substr($this->dt[3],0,4).'</div>').'</td>
             <td>'.tips('<img width="60" src="../ico/mot'.$this->cr[4].'.png" />','<div style="background-color:#FFFFFF"; height:350px;">'.substr($this->dt[4],6,2).'/'.substr($this->dt[4],4,2).'/'.substr($this->dt[4],0,4).'</div>').'</td>
             </tr></table>';
	return($sx);
	}
		
}
?>