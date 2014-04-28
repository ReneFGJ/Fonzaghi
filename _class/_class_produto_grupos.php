<?
Class produto_grupos
	{
 	var $id_pg;
	var $pg_g1;
	var $pg_g2;
	var $pg_g3;
	var $pg_descricao;
	var $pg_loja;
	var $pg_ativo;
	var $pg_codigo;
	var $pg_ref;
	
	var $tabela = 'produto_grupos';

	function cp()
		{
			global $dd,$acao;
			$cp = array();
			array_push($cp,array('$H4','id_pg','id_pg',False,True,''));
			array_push($cp,array('$H8','pg_loja','Loja',False,True,''));
			array_push($cp,array('$A','','Informaчѕes do Produto',False,True,''));
			array_push($cp,array('$s3','pg_codigo','Codigo',False,True,''));
			array_push($cp,array('$H1','pg_g1','G1',False,False,''));
			array_push($cp,array('$H1','pg_g2','G2',False,True,''));
			array_push($cp,array('$H1','pg_g3','G3',False,True,''));
			array_push($cp,array('$S50','pg_descricao','Descriчуo',False,True,''));
			array_push($cp,array('$O 1:SIM&2:NУO','pg_ativo','Ativo',False,True,''));
			array_push($cp,array('$[0-40]','pg_ref','% Desconto promocional',False,True,''));
			array_push($cp,array('$S3','pg_class','Classe Nova',False,True,''));
			//$dd[1] = $nloja;
			$dd[4] = substr($dd[3],0,1);
			$dd[5] = substr($dd[3],1,1);
			$dd[6] = substr($dd[3],2,1);
			return($cp);
		}
		
	function le($id)
		{
			if (strlen($id) > 0) { $this->id_pg = round($id); }
			$sql = "select * from ".$this->tabela;
			$sql .= " where id_pg = ".$this->id_pg;
			$xrlt = db_query($sql);
			if ($xline = db_read($xrlt))
				{
				$this->id_pg=$xline['id_pg'];
				$this->pg_g1=$xline['pg_g1'];
				$this->pg_g2=$xline['pg_g2'];
				$this->pg_g3=$xline['pg_g3'];
				$this->pg_descricao=$xline['pg_descricao'];
				$this->pg_loja=$xline['pg_loja'];
				$this->pg_ativo=$xline['pg_ativo'];
				$this->pg_codigo=$xline['pg_codigo'];
				$this->pg_ref=$xline['pg_ref'];
				return(1);
				}
		}

	function row()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$label = "Cadastro de Grupos de Produtos";
		/* Pсginas para Editar */
		$http_edit = 'produto_grupos_ed.php'; 
		$http_edit_para = '';
		$offset = 40;
		$order  = "pg_descricao";
		
		$cdf = array('id_pg','pg_codigo','pg_descricao','pg_ref');
		$cdm = array('ID','Codigo','Descriчуo','Ref.');
		$masc = array('','','','','','','','','');
		return(True);
		}
		
		function updatex()
			{
				
			}
}
?>