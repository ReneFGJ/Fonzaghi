<?
class cep
	{
		var $id_c;
		var $uf;
		var $key_dne;
		var $bair_dne;
		var $nbairro;
		var $proposicao;
		var $logradouro;
		var $adicional;
		var $cep;
		
		var $endereco;
		var $bairro;
		var $cidade;
		var $cidade_cod;
		
		var $erro;
		
		var $tabela = 'cep';
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_c','',False,True));
				return($cp);
			}
		function monta_rua()
			{
				$rua = "";
				$rua_nome = trim($this->logradouro);
				$rua_pre  = trim($this->nbairro);
				$rua_add  = trim($tris->adicional);
				
				$rua = trim(trim($rua_pre).' '.trim($rua_nome));
				$rua2 = LowerCase(trim($rua.' '.$rua_add));
				$rua = '';
				$sp = 1;
				for ($ra=0;$ra < strlen($rua2);$ra++)
					{
						if ($sp==1) 
							{$rua .= UpperCase(substr($rua2,$ra,1)); }
						else
							{$rua .= substr($rua2,$ra,1); }
						if (substr($rua2,$ra,1) == ' ') { $sp = 1; }
						else { $sp = 0; }
					}				
				return($rua);				
			}
		
		function consulta_cep($cep)
			{
				global $base_name,$base_server,$base_host,$base_user;
				require('../db_fghi_206_cep.php');
				
				$sql = "select *,cidade.key_dne as cidade_cod ";
				$sql .= ", cidade.cidade as cidade_nome from cep ";
				$sql .= " left join cidade on cidade.key_dne = cep.key_dne ";
				$sql .= " left join bairro on bairro.key_dne = cep.bair_dne ";
				$sql .= " where cep.cep = '".sonumero($cep)."' ";
				$rlt = db_query($sql);

				if ($line = db_read($rlt))
					{
						$this->uf = $line['uf'];
						$this->key_dne = $line['key_dne'];
						$this->bair_dne = $line['bair_dne'];
						$this->nbairro = $line['nbairro'];
						$this->proposicao = $line['proposicao'];
						$this->logradouro = $line['logradouro'];
						$this->adicional = $line['adicional'];
						$this->cep = $line['cep'];
						
						$this->endereco = $this->monta_rua();
						$this->bairro = $line['bairron'];
						$this->cidade = $line['cidade_nome'];
						$this->cidade_cod = $line['cidade_cod'];
						$this->erro = 0;
						return(1);
					} else {
						$this->erro = 1;
						return(0);
					}
			}
		function updatex()
			{
				return(1);
			}
	}
