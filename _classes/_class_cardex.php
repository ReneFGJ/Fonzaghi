<?
class cardex
{
	var $codpod;

function lista()
	{
	$sql = "select * from produto_estoque ";
	$sql .= " inner join clientes on cl_cliente = pe_cliente ";
	$sql .= "where pe_produto = '".$this->codpod."' ";
	$sql .= " order by pe_status, pe_fornecimento ";
	$xrlt = db_query($sql);
	$tot = 0;
	$sx .= '<TR><TH>Data</TH><TH>Nome</TH><TH>EAN13</TH><TH>Cliente</TH><TH>Status</TH><TH>Log</TH></TR>';
	while ($xline = db_read($xrlt))
		{
		$sx .= '<TR>';
		$sx .= '<TD align="center">';
		$sx .= stodbr($xline['pe_fornecimento']);
		$sx .= '<TD>';
		$sx .= ($xline['cl_nome']);	
		$sx .= '<TD align="center">';
		$sx .= ($xline['pe_ean13']);
		$sx .= '<TD align="center">';
		$sx .= ($xline['pe_cliente']);
		$sx .= '<TD align="center">';
		$sx .= ($xline['pe_status']);
		$sx .= '<TD align="center">';
		$sx .= ($xline['pe_log']);
		$sx .= '</TR>';
		$tot = $tot + 1;
		}
	$sx .= '<TR><TD colspan="10" align="right">Total '.$tot.'</TD></TR>';
	return($sx);
	}
}
?>