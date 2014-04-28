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
	
Class cartao_venda_digitada
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
		$this->tabela = "cartao_credito_tipo";
		$cp = array();
		array_push($cp,array('$H5','id_cct','',False,True,''));
		array_push($cp,array('$H7','cct_codigo','Cód',False,True,''));
		array_push($cp,array('$S40','cct_descricao','Descrição',True,True,''));
		array_push($cp,array('$S3','cct_codigo_cx','Código no Caixa',True,True,''));
		return($cp);
		}

	function row()
		{
		global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;

		$tabela = "cartao_credito_tipo";
		$label = "Plano de Contas";
		/* Páginas para Editar */
		$http_edit = 'ed_edit.php'; 
		$http_edit_para = '&dd99='.$tabela;
		$offset = 20;
		$order  = "cct_descricao";
		
		$cdf = array('id_cct','cct_descricao','cct_codigo_cx','cct_codigo');
		$cdm = array('ID','Descrição','Cód.Caixa','Código');
		$masc = array('','','','','','','','','');
		return(True);
		}
		
	function mostrar_vendas_itens($line)
		{
			global $coluna;
			$stx = '<TR '.coluna().'>';
			$stx .= '<TD>';
			$stx .= $cor.$line['cct_descricao'];
			$stx .= '<TD align="center">';
			$stx .= $cor.$line['ccv_documento'];
			$stx .= '<TD align="center">';
			$stx .= $cor.$line['ccv_num_autorizacao'];
			$stx .= '<TD align="center">';
			$stx .= $cor.$line['ccv_qtd_parcelas'].'x';
			$stx .= '<TD align="right">';
			$stx .= $cor.number_format($line['ccv_valor_venda'],2);
		
			$stx .= '<TD>';
			$stx .= $cor.substr($line['ccv_numero_cartao'],12,8);
			$stx .= '<TD>';
			$stx .= $cor.$line['ccv_nome_cartao'];
		
			$stx .= '<TD>';
			$stx .= $cor.substr($line['ccv_validade_cartao'],4,2).'/'.substr($line['ccv_validade_cartao'],0,4);
			$stx .= '<TD align="center">';
			$stx .= $cor.stodbr($line['ccv_data']);
			$stx .= '<TD>';
			$stx .= $cor.$line['ccv_hora'];
			$stx .= '<TD>';
			$stx .= $cor.$line['ccv_log'];
			$stx .= '<TD>';
			$stx .= $cor.$sa;
			$stx .= '</TR>';
			return($stx);
		}
	function mostrar_vendas($clie_)
		{
		
		/* Cabeçalho */
			$st .= '<BR><BR>';
			$st .= '<CENTER><font class="lt4">Vendas de cartão de crédito digitada</font></CENTER>';
			$st .= '<table class="lt1" width="820">';
			$st .= '<TR>';
			$st .= '<TH>Bandeira</TH>';
			$st .= '<TH>Documento</TH>';
			$st .= '<TH>Autorização</TH>';
			$st .= '<TH>Parc.</TH>';
			$st .= '<TH>Valor</TH>';
			$st .= '<TH>Cartão</TH>';
			$st .= '<TH>Nome do Cartão</TH>';
			$st .= '<TH>Validade</TH>';
			$st .= '<TH>Data</TH>';
			$st .= '<TH>Hora</TH>';
			$st .= '<TH>Log</TH>';
			$st .= '<TH>Status</TH>';
			$st .= '</TR>';
		
		/* Ultimos lançamentos de venda por cartão */
		$sql = "select * from cartao_credito_vendas ";
		$sql .= " inner join cartao_credito_tipo on cct_codigo = ccv_tipo_cartao ";
		$sql .= " where ccv_codigo_cliente = '".$clie_."' ";
		$sql .= " order by ccv_data desc ";
		$sql .= " limit 10 ";
		$rlt = db_query($sql);
		while ($line = db_read($rlt))
			{
			$sa = $line['ccv_status'];
			$cor = '';
			if ($sa == 'A') { $sa = 'Aberto'; $cor = '<font color="green"><B>'; }
			if ($sa == 'B') { $sa = 'Retirado'; $cor = '<font color="#ff8040">';}
			if ($sa == 'C') { $sa = 'Cancelado'; $cor = '#808080'; }			
			$st .= $this->mostrar_vendas_itens($line);
			}
		$st .= '</table>';
		return($st);
		}
	}
?>