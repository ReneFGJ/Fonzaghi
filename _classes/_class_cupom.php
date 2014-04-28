<?
Class editar
	{
	var $tabela = 'cartao_credito_tipo';
	var $cp;
	var $titulo = 'Tipos de Cartões de Crédito';
	var $db = 'db_ecaixa.php';
	var $http_redirect = 'cartao_credito_tipo_ed.php';
	function cps()
		{
		$ccc = new cartao_venda_digitada;
		$this->cps = $ccc->cp();
		return($this->cps);
		}
	}
	
Class cupom
	{
	var $cliente;
	var $tabela;
	var $cp;
	var $id_cct;
	var $cct_codigo;
	var $cct_descricao;
	var $cct_codigo_cx;

	function cp()
		{
		$this->tabela = "campanha_cupom";
		$cp = array();
		$lj = 'J:Joias&M:Modas&S:Sensual&O:Oculos&U:Catalogo';
		array_push($cp,array('$H5','id_cp','',False,True,''));
		array_push($cp,array('$S8','cp_nr','Nº cupom',False,True,''));
		array_push($cp,array('$D8','cp_revendedora','Código da revendedora',True,True,''));
		array_push($cp,array('$S3','cp_nome','Nome da Cliente',True,True,''));
		array_push($cp,array('$O '.$lj,'cp_loja','Lojas',True,True,''));
		array_push($cp,array('$N8','cp_valor','Valor da venda',True,True,''));

		array_push($cp,array('$H8','cp_cliente','',True,True,''));
		array_push($cp,array('$U8','cp_data','Data',True,True,''));

		array_push($cp,array('$D8','cp_niver','Dt. Nascimento',True,True,''));

		array_push($cp,array('$S20','cp_fone','Telefone',True,True,''));
		array_push($cp,array('$S20','cp_celular','Celular',True,True,''));
		array_push($cp,array('$S80','cp_email','e-mail',True,True,''));
		return($cp);
		}

	function row()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		$this->tabela = "campanha_cupom";
		$tabela = "campanha_cupom";
		$label = "Campanha de Cupons";
		/* Páginas para Editar */
		$http_edit = 'ed_edit.php'; 
		$http_edit_para = '&dd99='.$tabela;
		$offset = 20;
		$order  = "cct_descricao";
		
		$cdf = array('id_cp','cp_nr','cp_nome','cp_niver','cp_valor','cp_loja');
		$cdm = array('ID','Cupom','Nome da compradora','Dt.Aniv.','Valor','loja');
		$masc = array('','','','D','','','','','');
		return(True);
		}
		
	function mostrar_itens($line)
		{
			global $coluna;
			$stx = '<TR '.coluna().'>';
			$stx .= '<TD>';
			$stx .= $cor.$line['cp_nome'];
			$stx .= '<TD align="center">';
			$stx .= $cor.stodbr($line['cp_niver']);
			$stx .= '<TD align="center">';
			$stx .= $cor.$line['cp_loja'].'';
			$stx .= '<TD align="right">';
			$stx .= $cor.number_format($line['cp_valor'],2);
			return($stx);
		}
		
	function mailin_exportar($offset='0',$limit='50')
		{
			global $base;
			$sql = "select id_c,id_cl, c_nome, c_email, c_cliente, cl_nome from campanha ";
			$sql .= "inner join clientes on cl_cliente = c_cliente ";
			$sql .= "where c_email <> '' ";
			$sql .= " and id_c >= ".$offset;
			$sql .= "order by id_c ";
			$sql .= "limit ".$limit;		
			$rltt = db_query($sql);
			while ($xline = db_read($rltt))
				{
					$sx .= strzero($xline['id_c'],10).';';
					$sx .= trim($xline['c_email']).';';
					$sx .= trim($xline['c_nome']).';';
					$sx .= trim($xline['c_cliente']).';';
					$sx .= trim($xline['cl_nome']).';';
					$sx .= chr(13).chr(10);					
				}
			return($sx);
		}
	function mostrar($data_ini,$data_fim)
		{
		
		/* Cabeçalho */
			$st .= '<BR><BR>';
			$st .= '<CENTER><font class="lt4">Vendas de cartão de crédito digitada</font></CENTER>';
			$st .= '<table class="lt1" width="820">';
			$st .= '<TR>';
			$st .= '<TH>Nome</TH>';
			$st .= '<TH>Nasc.</TH>';
			$st .= '<TH>Loja</TH>';
			$st .= '<TH>Valor</TH>';
			$st .= '</TR>';
		
		/* Ultimos lançamentos de venda por cartão */
		$sql = "select * from campanha_cupom ";
		$sql .= " where cp_data >= ".$data_ini." and cp_data <= ".$data_fim;
		$sql .= " order by cp_nr desc ";
//		$sql .= " limit 10 ";
		$rlt = db_query($sql);
		while ($line = db_read($rlt))
			{
			$st .= $this->mostrar_itens($line);
			}
		$st .= '</table>';
		return($st);
		}
	}
?>