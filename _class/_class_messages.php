<?

class message
	{
		
 /**
  * Relatórios
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2013 - sisDOC.com.br
  * @access public
  * @version v.0.13.33
  * @package Classe
  * @subpackage UC00XX - Classe de Interoperabilidade de dados
 */
	var $msg=array();	
	var $cliente; 
 	var $nome;
 	var $tipo;
	var $ttln;
	var $tx;
	
	var $total_message; /* Total de mensagens */
	
 	function set_msg_inf_rest()
 		{ 			
			//mensagens
			array_push($this->msg, "0-Recepção, utilizar para informações direcionadas a recepção");//posição 0 - nenhum
			array_push($this->msg, "1-Informativa, e removido automaticamente após primeira leitura");
			array_push($this->msg, "2-Informativa, removida somente manualmente");
			array_push($this->msg, "3-Informativa, removida somente pelas coordenadoras ou supervisora");
			array_push($this->msg, "4-Informativa, removida somente pela supervisora");
			array_push($this->msg, "5-Informativa, removida somente pelo jurídico");
			array_push($this->msg, "6-Restritiva, removida pelas coordenadoras");
			array_push($this->msg, "7-Restritiva, removida pela supervisora");
			array_push($this->msg, "8-Restritiva, removida pelo jurídico, com possibilidade de liberar");
			array_push($this->msg, "9-Restritiva, Bloqueio total, removido pelo jurídico");
			return(1);
 		}
 	
	function msg_recepcao($cliente)
	{
		$sql = "SELECT id_msg, msg_cliente, msg_text,
						   msg_hora, msg_lido, msg_data_lido,
						   msg_hora_lido, msg_nivel, msg_data
	  				FROM mensagem 
	  				where msg_cliente = '".$cliente."' and 
	  					  msg_status <> 'X' and
	  					  (msg_nivel = 0 or
	  					  msg_nivel >= 5)
	  				order by msg_nivel
	  					  ";
	  	$rlt = db_query($sql);
	  	$sx = '<table width="90%"><th class="1_td" width="10%" align="center">Data</th>
	  							  <th class="1_td" width="10%" align="center">Hora</th>
	  							  <th class="1_td" width="70%" align="left">Mensagem</th>
	  							  <th class="1_td" width="10%" align="center">Nível</th>';
	  	while($line=db_read($rlt))
	  	{
	  		$dia=substr($line['msg_data'],-2);
	  		$mes=substr($line['msg_data'],4,2);
			$ano=substr($line['msg_data'],0,4);
			if($line['msg_nivel']==0){
				$sty='style="background-color:yellow; color:black;"';
			}else{
				$sty='';
			}
	  		$sx .= '<tr '.$sty.' ><td align="center">'.$dia.'/'.$mes.'/'.$ano.'</td>
	  					<td align="center">'.$line['msg_hora'].'</td>
	  					<td align="left">'.$line['msg_text'].'</td>
	  					<td align="center">'.$line['msg_nivel'].'</td>
	  				</tr>
	  		';
	  	}
	  	$sx .= '</table>';
		
		return($sx);
	}	
	function set_inf_rest($tipo){
			
			$this->set_msg_inf_rest();
			$cliente=$this->cliente;
			$sql = "SELECT id_msg, msg_cliente, msg_text,
						   msg_hora, msg_lido, msg_data_lido,
						   msg_hora_lido, msg_nivel, msg_data
	  				FROM mensagem where msg_cliente = '".$cliente."' and msg_status <> 'X'";
		
			switch ($tipo) {
			case 'informativa':
				$sql .= " and msg_nivel < 6 ";
				$sx	= '<CENTER><font class="lt5">Lista de mensagens informativas</font></CENTER>';
			break;
			case 'restritiva':
				$sql .= " and msg_nivel >= 6 ";
				$sx	= '<CENTER><font class="lt5">Lista de mensagens restritivas</font></CENTER>';
			break;	
			default:
			break;
			}
			$sql .= " order by msg_data desc, msg_hora";
			$rlt = db_query($sql);
			$i=0;

			while ($line = db_read($rlt)){
			$i++;
			$tx .= '<TR>
				<TD class="1_td" width="10" align="center">['.$i.']
				<TD class="1_td" align="center">'.stodbr($line['msg_data']).'   '.$line['msg_hora'].'
				<TD width="200" class="1_td">'.$line['msg_text'].'
				<TD width="300" class="1_td" align="left">'.$this->msg[$line['msg_nivel']].'</tr>';
			}
			$this->tx=$sx.$tx;
			$this->ttln=$i;
			return(1);
		}
		
 		function informativa(){
			$cliente=$this->cliente;
			$this->set_inf_rest('informativa');
			$ttln=$this->ttln;
			$sx= '<td class="tabela00 lt1" width="20" height="20" align="center">Informativa : <a href="javascript:newxy2(\'mensagens_visualizar.php?dd99='.$cliente.'&dd98=informativa\',640,480);">'.$ttln.'</a></td>';
			return($sx);
		}
		
		function restritiva(){
			$cliente=$this->cliente;
			$this->set_inf_rest('restritiva');
			$ttln=$this->ttln;
			$sx= '<td class="tabela00 lt1" width="20" height="20" align="center">Restritiva : <a href="javascript:newxy2(\'mensagens_visualizar.php?dd99='.$cliente.'&dd98=restritiva\',640,480);">'.$ttln.'</a></td>';
			return($sx);
		}
	
		function msg_incluir(){
			global $cons;	
			$cliente=$this->cliente;	
			$nome=$cons->nome;
			$sx='<td class="tabela00" width="20" height="20" align="center"><a href="javascript:newxy2(\'mensagens_incluir.php?dd99='.$cliente.'&dd98='.$nome.'\',640,480);">
				 <img src="../img/icone_add.png" width="32" border="0" height="32" alt="" title="Novas mensagens"></img></a></td>';
			return($sx);
		}
		
		function msg_excluir(){
			$cliente=$this->cliente;	
			session_start();
			ob_start();
			$nivel=$_SESSION['nw_nivel'];
			$sx='<td class="tabela00" width="20" height="20" align="center"><a href="javascript:newxy2(\'mensagens_excluir.php?dd99='.$cliente.'&dd98='.$nivel.'\',640,480);">
				 <img src="../img/icone_remover.png" width="32" border="0" height="32" alt="" title="Excluir mensagens"></img></a></td>';
			
			return($sx);
		}
		
		function mini_msg(){
			global $dd,$http;
				$nome=$this->nome;
				$cliente=$this->cliente;
				$lj = array('T','D','J','M','O','U','S');		
				
				$link = $this->msg_excluir();
				$link1= $this->msg_incluir(); 				
				$link2=$this->informativa();
				$link3=$this->restritiva();
				
				
						
				$sx = '<table class="tabela00" width="80%" height="10"  >';
				
				$sx .= '<TR bgcolor="FFFFFF" cellpadding="0" border="0">'.$link.$link1.$link2.$link3;


				$sx .= '<IMG if="messa" SRC="../img/icone_message_red.png" height="30" valign="middle">';
				$sx .= '<font color="black">'.$this->total_message.'</font><font class="lt0" color="grey"><SUP>mail.</SUP></font>';

				$sx .= '<IMG if="messa" SRC="../img/icone_message_green.png" height="30" valign="middle">';
				$sx .= '<font color="black">1</font><font class="lt0" color="grey"><SUP>mail.</SUP></font>';

				$sx .= '<IMG if="messa" SRC="../img/icone_message.png" height="30" valign="middle">';
				$sx .= '<font color="black">1</font><font class="lt0" color="grey"><SUP>mail.</SUP></font>';


				$sx .= '</tr></table>';
		return($sx);
		}

		function mensagens_count()
			{
			$sql = "select count(*) as total from (
					select 1 from historico_".date("Y")." where h_cliente = '".$this->cliente."'
					union 	
					select 1 from historico_".(date("Y")-1)." where h_cliente = '".$this->cliente."'
					) as tabela
					";
			$rlt = db_query($sql);
			$tot = 0;
			if ($line = db_read($rlt))
				{ $tot = $line['total']; }
			$this->total_message = $tot;
			}
		
		function consultora_status($sta)
		{
			switch ($sta)	
				{
					case 'A': { $sta = '<font color="green">Ativa'; break; }
					case 'I': { $sta = '<font color="red">Inativa'; break; }
					case '@': { $sta = 'Em cadastro'; break; }
				}
				return($sta); 
		}

	function le_completo($id='')
		{
			global $base_name,$base_server,$base_host,$base_user;
			if (strlen($id) > 0) {$this->codigo = $id; }
			/* abre banco das consultoras */
			require("../db_fghi_206_cadastro.php");
			
				$cp .= '*';			
				$sql = "select $cp from cadastro_completo where pc_codigo = '".$id."' or id_pc = ".round($id);
				
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$this->pc_codigo = $line['pc_codigo'];
						$this->pc_cpf = $line['pc_cpf'];
						$this->pc_nome = $line['pc_nome'];
						$this->pc_pai = $line['pc_pai'];
						$this->pc_rg = $line['pc_rg'];
						$this->pc_dt_nasc = $line['pc_dt_nasc'];
						$this->pc_naturalidade = $line['pc_naturalidade'];
						$this->pc_endereco = substr($line['pc_endereco'],0,3);
						$this->pc_bairro = substr($line['pc_bairro'],3,3);
						$this->pc_cidade = $line['pc_cidade'];
						$this->pc_estado = $line['pc_estado'];
						$this->pc_cep = $line['pc_cep'];
						$this->pc_empresa_trabalha = $line['pc_empresa_trabalha'];
						$this->pc_empresa_endereco = $line['pc_empresa_endereco'];
						$this->pc_funcao = $line['pc_funcao'];
						$this->pc_salario = $line['pc_salario'];
						$this->pc_estado_civil = $line['pc_estado_civil'];
						$this->pc_nome_conj = $line['pc_nome_conj'];
						$this->pc_dt_nasc_conj = $line['pc_dt_nasc_conj'];
						$this->pc_empresa_trabalha_conj = $line['pc_empresa_trabalha_conj'];
						$this->pc_funcao_conj = $line['pc_funcao_conj'];
						$this->pc_salario_conj = $line['pc_salario_conj'];
						$this->pc_resi_propria = $line['pc_resi_propria'];
						$this->pc_vlr_aluguel = $line['pc_vlr_aluguel'];
						$this->pc_fone1 = $line['pc_fone1'];
						$this->pc_fone2 = $line['pc_fone2'];
						$this->pc_obs = $line['pc_obs'];
						$this->pc_lista_codigo = $line['pc_lista_codigo'];
						$this->pc_log = $line['pc_log'];
						$this->pc_data_cadastro = $line['pc_data_cadastro'];
						$this->pc_hora_cadastro = $line['pc_hora_cadastro'];
						$this->pc_status = $line['pc_status'];
						$this->pc_fone_cliente = $line['pc_fone_cliente'];
						$this->pc_fone_conjuge = $line['pc_fone_conjuge'];
						$this->pc_propaganda_1 = $line['pc_propaganda_1'];
						$this->pc_propaganda_2 = $line['pc_propaganda_2'];
						$this->pc_empresa_fone = $line['pc_empresa_fone'];
						$this->pc_empresa_endereco_conj = $line['pc_empresa_endereco_conj'];
						$this->pc_empresa_fone_conj = $line['pc_empresa_fone_conj'];
						$this->pc_casa_obs = $line['pc_casa_obs'];
						$this->pc_analise = $line['pc_analise'];
						$this->pc_update = $line['pc_update'];
						$this->pc_autorizada = $line['pc_autorizada'];
						$this->pc_tipo = $line['pc_tipo'];
						$this->line = $line;						
						$ok = 1;
					} else {
						$ok = 0;
					}
				return($ok);				
		}		
	
 
 
}
?>		