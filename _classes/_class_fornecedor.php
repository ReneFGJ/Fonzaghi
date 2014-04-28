<?
class fornecedor
	{
		
	function export_tabela()
		{
			$sql = "select fo_razaosocial, fo_nomefantasia, fo_ativo, fo_codfor ";
			$sql .= " from fornecedores ";
			$sql .= " group by fo_razaosocial, fo_nomefantasia, fo_ativo, fo_codfor ";
			$rlt = db_query($sql);
			
			$sql = "";
			$sql = "DROP TABLE fornecedor; ";
			$sql .= " CREATE TABLE fornecedor (";
			$sql .= "id_fo serial NOT NULL, fo_nomefantasia character(70),fo_nome_asc character(140), fo_codfor character(10), fo_ativo integer DEFAULT 1 ); ";
			
			while ($line = db_read($rlt))
				{
					$nome = LowerCase($line['fo_nomefantasia']);
					$nome = trim(Uppercase(substr($nome,0,1)).substr($nome,1,100));
					$sql .= "insert into fornecedor ";
					$sql .= "(fo_nomefantasia, fo_nome_asc, fo_codfor, fo_ativo)";
					$sql .= " values ";
					$sql .= "('".$nome."','".trim(UpperCaseSql(trim($line['fo_nomefantasia']).'-'.trim($line['fo_razaosocial'])))."','".$line['fo_codfor']."',1); ".chr(13);
				}
			return($sql);
		}
	}
