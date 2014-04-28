<?php


require("../db_fghi.php");
	class cargos
		{
			var $id_car;
			var $car_cod;
			var $car_nome;
            var $car_descricao;
			var $car_ativo;
			
			var $tabela = 'cargos';
			
	
		function cp()
			{
				global $dd;
				$cp = array();
				
				array_push($cp,array('$H8','id_car','',false,True));
                array_push($cp,array('$S60','car_nome','Nome',false,True));
                array_push($cp,array('$S80','car_descricao','Descrição',false,True));
				array_push($cp,array('$O 1:Ativo&0:Inativo','car_ativo','Status',False,True,''));
				return($cp);
			}
		function le($id)
			{
				if (strlen($id) > 0) { $this->id_car = $id; }
				$sql = "select * from ".$this->tabela;
				$sql .= " where id_car = ".$this->id_car;
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$this->id_car = $line['id_car'];
					$this->car_cod = $line['car_cod'];
					$this->car_nome = $line['car_nome'];
					$this->car_descricao = $line['car_descricao'];
					$this->car_ativo = $line['car_ativo'];
					return(1);					
				} else {
					return(0);
				}
			}
		
			
		function mostra_cargos()
			{
				global $coluna,$user_nivel;
					$sql ='select * from '.$this->tabela;
					$rlt = db_query($sql);
					$line = db_read($rlt);
					$sx .='<TR><TH>Codigo<TH>Descrição<TH>Status<TH>Editar';
					
					
					
					while($line = db_read($rlt)){
							
							
							$sx .= '<TR '.coluna().'>';
							$sx .= '<TD align="center">';
							$sx .= $line['id_car'].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $line['car_descricao'].'&nbsp;';
							$sx .= '<TD align="center">';
							$sx .= $line['car_ativo'].'&nbsp;';
							
						//	if (($tp == '1') and ($cp_nivel > 7))
						//		{
							$link2 = '<A HREF="#" onclick="newxy2('.chr(39).'funcionario_cargos.php?dd0='.$line['id_car'].'&dd1='.$line['car_descricao'].'&dd2='.$line['car_ativo'].chr(39).',820,500);">';
							//$onclick = 'onclick="newwin(\'funcionario_cargos.php?dd0='.$line['id_car'].'&dd1=\''.$line['car_descricao'].'\',600,400);"';
							$link = $link2.'<IMG SRC="../img/icone_editar.gif" ></a>';
							$sx .= '<TD align="center">';
							$sx .= $link; 
						//		}
					
				//  <a class="deleta_produtos" id="23">
				//	$('.deleta_produto').ajaxDelete()
				
}	
					
				return($sx);
			}
			
		 function delete_cargo(){
		 	
			
			return($sx);
		 }	
         
            function cp_cargo()
        {
            $cp = array();
            array_push($cp,array('$H8','id_car','',false,True));
            array_push($cp,array('$S60','car_nome','Nome do Cargo',false,True));
            array_push($cp,array('$S80','car_descricao','Descrição do Cargo',false,True));
            array_push($cp,array('$O 1:Ativo&0:Não ativo','car_ativo','Status',False,True));
            array_push($cp,array('$H8','car_cod','',false,True));
            return($cp);
        }
    function updatex_cargo()
        {
            $dx1 = 'car_cod';
            $dx2 = 'car';
            $dx3 = 4;
            $sql = "update cargos set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
            $rlt = db_query($sql);
            print_r($sql);
            return(1);
        }
 
 function row_cargo()
        {
        global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
        $this->tabela = "cargos";
        $tabela = "cargos";
        $label = "Cadastro de Cargos";
        /* Páginas para Editar */
        $http_edit = 'aval_cargos_ed.php'; 
        $offset = 20;
        $order  = "car_descricao";
        
        $cdf = array('id_car','car_cod','car_descricao','car_ativo','car_nome');
        $cdm = array('ID','Codigo','Descrição','Status','Nome');
        $masc = array('','','','','','','','','','','','','');
        return(True);
        }
    
         
         
}
