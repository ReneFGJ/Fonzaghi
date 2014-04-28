<?
class telemarketing
	{
	var $codigo;
	var $nome;
	var $login;
	
  	var $id_pc;
  	var $pc_codigo;
  	var $pc_cpf;
  	var $pc_nome;
  	var $pc_mae;
  	var $pc_pai;
  	var $pc_rg;
  	var $pc_dt_nasc;
  	var $pc_naturalidade;
  	var $pc_endereco;
  	var $pc_bairro;
  	var $pc_cidade;
  	var $pc_estado;
  	var $pc_cep;
  	var $pc_empresa_trabalha;
  	var $pc_empresa_endereco;
  	var $pc_funcao;
  	var $pc_salario;
  	var $pc_estado_civil;
  	var $pc_nome_conj;
  	var $pc_dt_nasc_conj;
  	var $pc_empresa_trabalha_conj;
  	var $pc_funcao_conj;
  	var $pc_salario_conj;
  	var $pc_resi_propria;
  	var $pc_vlr_aluguel;
  	var $pc_fone1;
  	var $pc_fone2;
  	var $pc_obs;
  	var $pc_lista_codigo;
  	var $pc_log;
  	var $pc_data_cadastro;
	var $pc_hora_cadastro;
  	var $pc_status;
  	var $pc_fone_cliente;
  	var $pc_fone_conjuge;
  	var $pc_propaganda_1;
  	var $pc_propaganda_2;
  	var $pc_empresa_fone;
  	var $pc_empresa_endereco_conj;
  	var $pc_empresa_fone_conj;
  	var $pc_casa_obs;
  	var $pc_analise;
  	var $pc_update;
	var $pc_email;	
		
	var $tabela = 'pre_cadastro';

	function cp()
		{	
		$this->tabela = "pre_cadastro";
		$cp = array();
		array_push($cp,array('$H4','id_pc','id_pc',False,True,''));
		array_push($cp,array('$A','','Informações da consultora',False,True,''));
		array_push($cp,array('$S100','pc_nome','Nome da consultora',False,False,''));
		array_push($cp,array('$S10','pc_log','Operadora Tele',False,True,''));
		return($cp);
		}
		
	function row()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "pre_cadastro";
		$tabela = "pre_cadastro";
		$label = "Cadastro de Consultoras (Pré-Cadastro)";
		/* Páginas para Editar */
		$http_edit = 'ed_edit.php'; 
		$http_edit_para = '&dd99='.$tabela;
		$offset = 20;
		$order  = "pc_nome";
		
		$cdf = array('id_pc','pc_nome','pc_log','pc_cpf');
		$cdm = array('ID','Nome','Descrição','CPF');
		$masc = array('','','','','','','','','');
		return(True);
		}
		
	function le()
		{
			$sql = "select * from ".$this->tabela." where id_pc = ".$this->id_pc;
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->id_pc = $line['id_pc'];
					$this->pc_log = $line['pc_log'];
					$this->pc_codigo = $line['pc_codigo'];
					$this->pc_cpf = $line['pc_cpf'];
					$this->pc_nome = $line['pc_nome'];
					$this->pc_mae = $line['pc_mae'];
					$this->pc_propaganda_1 = $line['pc_propaganda_1'];
					$this->pc_propaganda_2 = $line['pc_propaganda_2'];
					$this->pc_email = $line['pc_email'];
					$this->pc_status = $line['pc_status'];
					$this->pc_obs = $line['pc_obs'];
					return(1);
				} else { return(0); }
		}
	function troca_login($cliente,$log)
		{
			global $user_log;
			$sql = "update ".$this->tabela." set pc_log = '".$log."' ";
			$sql .= " where pc_codigo = '".$cliente."' ";
			$rlt = db_query($sql);
						
			$sql = "insert into pre_historico ";
			$sql .= "(h_cliente,h_data,h_hora,h_log,h_status,h_historico) ";
			$sql .= " values ";
			$sql .= "('".$cliente."','".date("Ymd")."','".date("H:i")."','".$user_log."',";
			$sql .= "'T','Troca de login para ".$log."')";
			$rlt = db_query($sql);
			
			return(1);
		}		
	}