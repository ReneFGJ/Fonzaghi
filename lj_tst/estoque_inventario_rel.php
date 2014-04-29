<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_inventario_rel.php','Relatório do inventário'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");

require($include."biblioteca.php");
require("estoque_funcoes.php");
require("db_temp.php");

$cp=array();
array_push($cp,array('$S6','','Digite a referência do produto',True,True,''));

echo '<CENTER><font class="lt5">Relatório do Inventário</font></CENTER>';
if ($dd[0]==''){
	echo '<TABLE border="0" align="center" width="30%">';
	echo '<TR><TD>';
	editar();
	echo '</TD></TR>';
	echo '</TABLE>';
	echo $hd->foot();	
	exit;
}

//Peças inventariadas	
$sql="SELECT pe_inventario, count(*) as qtd
  FROM produto_estoque
  where pe_produto='".$dd[0]."'
	and pe_status <> 'T' and pe_status <> 'F' and pe_status <> 'X'
	group by pe_inventario
	order by pe_inventario";
$rlt=db_query($sql);

$naoInv=0;
$inv=0;
while($line=db_read($rlt)){
	if ($line['pe_inventario']==0){
		$naoInv=$line['qtd'];
	}
	if ($line['pe_inventario']==1){
		$inv=$line['qtd'];
	}
}

$sql="SELECT count(*) as baixadas
  FROM produto_estoque
  where pe_produto='".$dd[0]."'
	and (pe_status = 'T' and pe_cliente='8284371')
	group by pe_inventario
	order by pe_inventario";
$rlt=db_query($sql);
$line=db_read($rlt);
$baixadas=$line['baixadas'];

if (!isset($baixadas)){
	$baixadas=0;
}

$sql="SELECT p_descricao
  FROM produto
  where p_codigo='".$dd[0]."'";
$rlt=db_query($sql);
$line=db_read($rlt);

echo '<br>';
echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
echo '<tr><th class="legenda" height="20" width="260">Referência do produto</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$dd[0].' '.$line['p_descricao'].'</font></td></tr>';
echo '<tr><th class="legenda" height="20" width="260">Peças não inventariadas</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$naoInv.'</font></td></tr>';
echo '<tr><th class="legenda" height="20" width="260">Peças inventariadas</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$inv.'</font></td></tr>';
echo '<tr><th class="legenda" height="20" width="260">Peças baixadas do estoque</th><td bgcolor="#F0F0F0" class="1_td">&nbsp;<font class="lt4">'.$baixadas.'</font></td></tr>';
echo '</table>';

$sql="SELECT pe_lastupdate, pe_ean13, pe_produto, p_descricao, pe_status, pe_inventario
  FROM produto_estoque
  left join produto on p_codigo=pe_produto
  where pe_produto='".$dd[0]."'
	and (pe_status = 'T' and pe_cliente='8284371')
	order by pe_ean13";
$rlt=db_query($sql);

if ($baixadas > 0){
	echo '<br><br>';
	echo '<table border="0"  class="1_naoLinhaVertical" width="'.$tab_max.'" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">';
	echo '<th colspan="7" class="1_th">Listagem de peças inventariadas que foram baixadas do estoque</th>';
	
	$naoInventariadas=0;
	$coluna=1;
	while ($line = db_read($rlt)){
		if ($coluna==1){echo '<tr '.coluna().'>';}
			
		echo '<td class="1_td" align="center" width="100">'.$line['pe_ean13'].'</td>';
		
		if ($coluna==7){
			$lista .='</tr>';
			$coluna=0;
		}
		$coluna++;
		$naoInventariadas++;
	}
		
	while($coluna <= 7){
		echo '<td class="1_td" align="center" width="100">&nbsp;&nbsp;</td>';
		$coluna++;
	}
	if ($coluna==8){$lista .='</tr>';}
	echo '<tr><td colspan="7" class="rodapetotal">'.$naoInventariadas.' ítens</td></tr>';
	echo '</table>';
}

echo $hd->foot();
	
?>
