<?
class fatura
	{
	 var $id_f;
	 var $f_cliente;
  	 var $f_valor;
  	 var $f_data;
  	 var $f_hora;
  	 var $f_doc;
  	 var $f_loja;
  	 var $f_tipo;
  	 var $f_status;
  	 var $f_desconto;
  	 var $f_total;
	 var $cpf;
	 var $nome;
	 
	 var $tabela = 'fatura';
	 
	 function cp()
	 	{ }
	 function gravar()
	 	{
	 		if (strlen($this->id_f) == 0)
				{
					$sql = "insert into ".$this->tabela;
					$sql .= " (f_cliente, f_valor, f_data, ";
					$sql .= " f_hora, f_doc, f_loja, ";
					$sql .= " f_tipo, f_status, f_desconto, ";
					$sql .= " f_total ";
					$sql .= ") values (";
					$sql .= "'".$this->f_cliente."','".$this->f_valor."',";
					$sql .= "'".$this->f_data."','".$this->f_hora."',";
					$sql .= "'".$this->f_doc."','".$this->f_loja."',";
					$sql .= "'".$this->f_tipo."','".$this->f_status."',";
					$sql .= "'".$this->f_desconto."','".$this->f_total."',";
					$sql .= ");";
					$rlt = db_query($sql);
				} else {
					$sql = "update ".$this-tabela." set ";
					$sql .= "f_valor = '".$this->f_valor."',";
					$sql .= "where id_f = ";
				}
	 	}
		
		function notas_abertas($venc,$valor_mini)
			{
				
			}
		function folha_rosto($nome,$clie)
			{
				$sx .= '<Table width="100%">';
				$sx .= '<TR><TD width="80%" class="lt2"><B>';
				$sx .= $nome;
				$sx .= '<TD align="left" width="20%">';
				$sx .= '<fieldset><legend>CÓDIGO</legend><NOBR>';
				$sx .= '<font class="lt5">';
				$sx .= $clie;
				$sx .= '</fieldset>';
				$sx .= '<TR><TD>';
				$sx .= '</table>';
				return($sx);
			}
		
	 function updatex()
	 	{}	
	}
?>
