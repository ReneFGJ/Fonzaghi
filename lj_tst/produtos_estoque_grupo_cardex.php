<?
 /**
  * Cardex de produtos
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.34
  * @package Classe
  * @subpackage UC0019 - Cardex de produtos
 */
 
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/ed_produto.php','Cadastro de produtos'));
array_push($breadcrumbs, array('/fonzaghi/sensual/produtos_estoque_grupo_cardex.php','Visualizar item'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_debug.php');
require($include.'sisdoc_data.php');

require("../_classes/_class_produto.php");

$produto = produto new;
?>
<table width="<?=$tab_max;?>" class="lt1">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?

$saldo_ini = 0;
$valor_ini = 0;

$da1 = "20100201";
$da2 = date("Ymd");
$view = $dd[2];
if (strlen($view) == 0) { $view = '0'; } else {$view = '1'; }

$sql = "select sum(pe_vlr_custo) as vlr, count(*) as total, pl_status, pl_produto, pl_data from (";
for ($r=201002;$r <= date("Ym");$r++)
	{
	if (substr($r,4,2) == '13') { $r = $r + 100 - 12; }
	if ($r <> 201002) { $sql .= ' union '; }
	$sql .= "select * from produto_log_".$r.' ';
	}

$sql .= ") as tabela ";
$sql .= "inner join produto on pl_produto = p_codigo ";
$sql .= "inner join produto_estoque on pe_ean13 = pl_ean13 ";
$sql .= "where ";

if (strlen($dd[0]) < 4)
	{ $sql .= " p_class_1 like '".$dd[0]."%' "; }
	else
	{ $sql .= " p_codigo = '".$dd[0]."' "; }
$sql .= " and pl_data >= ".$da1;
$sql .= " and pl_data <= ".$da2;
$sql .= "group by pl_produto, pl_data, pl_status ";
$sql .= "order by pl_data ";

echo $sql;
exit;

$rlt = db_query($sql);

$sld = 0;
$sn = '';
$x = 'X';
$saldo = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$pos = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$cus = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

$tpos = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$tcus = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

$data = 19000101;
$qi = 0;
$vi = 0;

while ($line = db_read($rlt))
	{
	$sta = $line['pl_status'];
	$valor = $line['total'];
	$custo = $line['vlr'];

	if ($data != $line['pl_data'])
		{
		if ($data > 20000101)
			{
			if ($view == 1)
				{
				$sa .= '<TR '.coluna().' class="lt2">';
				$sa .= '<TD align="center">'.stodbr($data).'</TD>';
				$sa .= '<TD align="center">'.$pos[0].'<TD align="right">'.number_format($cus[0],2).'</TD>';
				$sa .= '<TD align="center">'.$pos[1].'<TD align="right">'.number_format($cus[1],2).'</TD>';
				$sa .= '<TD align="center">'.$pos[2].'<TD align="right">'.number_format($cus[2],2).'</TD>';
				$sa .= '<TD align="center">'.$pos[3].'<TD align="right">'.number_format($cus[3],2).'</TD>';
				$sa .= '<TD align="center">'.$pos[4].'<TD align="right">'.number_format($cus[4],2).'</TD>';
				$sa .= '<TD align="center">'.$pos[5].'<TD align="right">'.number_format($cus[5],2).'</TD>';
//				$sa .= '<TD align="center">'.$qi.'<TD align="right">'.number_format($vi,2).'</TD>';
				}
			}
			$pos = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$cus = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$data = $line['pl_data'];
		}
	

	if ($sta == 'H') { $pos[0] = $pos[0] + $valor; $qi = $qi + $valor; $tpos[0] = $tpos[0]+ $valor; }
	if ($sta == 'X') { $pos[0] = $pos[0] - $valor; $qi = $qi - $valor; $tpos[0] = $tpos[0]- $valor; } // ITEM CANCELADO
	if ($sta == 'F') { $pos[1] = $pos[1] + $valor; $tpos[1] = $tpos[1]+ $valor; }
	if ($sta == 'D') { $pos[2] = $pos[2] + $valor; $tpos[2] = $tpos[2]+ $valor;}
	if ($sta == 'T') { $pos[5] = $pos[5] + $valor; $qi = $qi - $valor; $tpos[5] = $tpos[5]+ $valor;}
//	if ($sta == 'U') { $pos[5] = $pos[5] + $valor; } // venda funcionário
//	if ($sta == 'M') { $pos[5] = $pos[5] + $valor; } // venda mostruario
//	if ($sta == 'Z') { $pos[5] = $pos[5] + $valor; } // Ajuste de Inventário
//	if ($sta == 'U') { $pos[5] = $pos[5] + $valor; } // perda por furto
//	if ($sta == 'U') { $pos[5] = $pos[5] + $valor; } // perda por doacao

	if ($sta == 'H') { $cus[0] = $cus[0] + $custo; $vi = $vi + $custo; $tcus[0] = $tcus[0]+ $custo;}
	if ($sta == 'F') { $cus[1] = $cus[1] + $custo; $tcus[1] = $tcus[1]+ $custo;}
	if ($sta == 'D') { $cus[2] = $cus[2] + $custo; $tcus[2] = $tcus[2]+ $custo;}
	if ($sta == 'T') { $cus[5] = $cus[5] + $custo; $vi = $vi - $custo; $tcus[5] = $tcus[5]+ $custo;}
	}
	
if ($view == 1)
		{
		$sa .= '<TR '.coluna().' class="lt2">';
		$sa .= '<TD align="center">'.stodbr($data).'</TD>';
		$sa .= '<TD align="center">'.$pos[0].'<TD align="right">'.number_format($cus[0],2).'</TD>';
		$sa .= '<TD align="center">'.$pos[1].'<TD align="right">'.number_format($cus[1],2).'</TD>';
		$sa .= '<TD align="center">'.$pos[2].'<TD align="right">'.number_format($cus[2],2).'</TD>';
		$sa .= '<TD align="center">'.$pos[3].'<TD align="right">'.number_format($cus[3],2).'</TD>';
		$sa .= '<TD align="center">'.$pos[4].'<TD align="right">'.number_format($cus[4],2).'</TD>';
		$sa .= '<TD align="center">'.$pos[5].'<TD align="right">'.number_format($cus[5],2).'</TD>';
		}

		$sa .= '<TR '.coluna().' class="lt2">';
		$sa .= '<TD align="center">Sub-total</TD>';
		$sa .= '<TD align="center"><B>'.$tpos[0].'<TD align="right"><B>'.number_format($tcus[0],2).'</TD>';
		$sa .= '<TD align="center"><B>'.$tpos[1].'<TD align="right"><B>'.number_format($tcus[1],2).'</TD>';
		$sa .= '<TD align="center"><B>'.$tpos[2].'<TD align="right"><B>'.number_format($tcus[2],2).'</TD>';
		$sa .= '<TD align="center"><B>'.$tpos[3].'<TD align="right"><B>'.number_format($tcus[3],2).'</TD>';
		$sa .= '<TD align="center"><B>'.$tpos[4].'<TD align="right"><B>'.number_format($tcus[4],2).'</TD>';
		$sa .= '<TD align="center"><B>'.$tpos[5].'<TD align="right"><B>'.number_format($tcus[5],2).'</TD>';

		
$produto->p_codigo = $dd[0];
?>
<TABLE width="<?=$tab_max;?> align="center" border="1" class="lt1">
<TR><TD colspan="10" class="lt4"><?=$dd[0];?> - Posição consolidada</TD>
	<TD>
	<?
	if (strlen($dd[2]) == 0) { $lk = '<A HREF="produtos_estoque_grupo_cardex.php?dd0='.$dd[0].'&dd2=1">DETALHADO</A>'; }
	else { $lk = '<A HREF="produtos_estoque_grupo_cardex.php?dd0='.$dd[0].'&dd2=">RESUMIDO</A>'; }
	echo $lk;
	?>
	</TD>
	<TD><?=$produto->mostrar_imagem(); ?>xxx</TD>
</TR>
<TR class="lt4"><TH colspan="13">SALDO INICIAL</TH></TR>
<TR class="lt2"><TH>&nbsp;</TH>
<TH colspan="6">Quantidade de Itens: <?=$saldo_ini;?></TH>
<TH colspan="6">Valor do estoque:  <?=number_format($valor_ini,2);?></TH>

</TR>
<TR>
	<TH rowspan="2">Data</TH>
	<TH colspan="2">Entrada</TH>
	<TH colspan="2">Consignada</TH>
	<TH colspan="2">Devolvida</TH>
	<TH colspan="2">Outras Entradas</TH>
	<TH colspan="2">Outras Saídas</TH>
	<TH colspan="2">Faturada</TH>
</TR>

<TR>
	<TH colspan="1" width="7%">Q.</TH>
	<TH colspan="1" width="7%">Valor</TH>
	<TH colspan="1" width="7%">Q.</TH>
	<TH colspan="1" width="7%">Valor</TH>
	<TH colspan="1" width="7%">Q.</TH>
	<TH colspan="1" width="7%">Valor</TH>
	<TH colspan="1" width="7%">Q.</TH>
	<TH colspan="1" width="7%">Valor</TH>
	<TH colspan="1" width="7%">Q.</TH>
	<TH colspan="1" width="7%">Valor</TH>
	<TH colspan="1" width="7%">Q.</TH>
	<TH colspan="1" width="7%">Valor</TH>
</TR>
<?=$sa;?>

<TR class="lt4"><TH colspan="13">SALDO FINAL</TH></TR>
<TR class="lt2"><TH>&nbsp;</TH>
<TH colspan="6">Quantidade de Itens: <?=($saldo_ini + $qi);?></TH>
<TH colspan="6">Valor do estoque:  <?=number_format($vi - $valor_ini,2);?></TH>
<? if ($tpos[5] >0) { ?>
<TR><TD>&nbsp;</TD><TD colspan="12" class="lt3"><B>IEP = <?=number_format($tpos[5] / ($tpos[5] + $tpos[2])*100,1).'%';?> (VENDAS/(VENDAS+DEVOLVIDA)</TD></TR>
<? } ?>
<?=$tpos[2];?> - <?=$tpos[5];?>
</TABLE>
<?
//////////////////////////////// ANTERIOR E PROXIMO
if (strlen($dd[0]) == 6)
	{
	?>
	<TABLE><TR><TD><A HREF="produtos_estoque_grupo_cardex.php?dd0=<?=strzero(round($dd[0])-1,6);?>"><< ANTERIOR</A></TD>
	<TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
	<TD><A HREF="produtos_estoque_grupo_cardex.php?dd0=<?=strzero(round($dd[0])+1,6);?>">PRÓXIMO >></A></TD></TR></TABLE>
	<?
	}
///////////////////////////////////////////////////////////////////////////////////////
$sql = "select count(*) as total, pe_status from produto_estoque ";
$sql .= "inner join produto on pe_produto = p_codigo ";



if (strlen($dd[0]) == 6)
	{
		$sql .= "where pe_produto = '".$dd[0]."' ";
	} else {
		$sql .= "where p_class_1 = '".$dd[0]."' ";
	}
$sql .= "group by pe_status ";
$sql .= " order by pe_status ";

//echo '<br>'.$sql;

$rlt = db_query($sql);

$e1 = 0;
$e2 = 0;
while ($line = db_read($rlt))
	{
	$tp = $line['pe_status'];
	if ($tp == 'F') { $tp = 'Fornecido'; $e1 = $e1 + $line['total']; }
	if ($tp == 'T') { $tp = 'Vendido'; }
	if ($tp == '@') { $tp = 'Para realizar <I>Check-in</I>'; }
	if ($tp == 'X') { $tp = 'Cancelado'; }
	if ($tp == 'B') { $tp = 'Disponível em estoque - Devolvido';  $e2 = $e2 + $line['total']; }
	if ($tp == 'C') { $tp = 'Disponível em estoque - Checkin';  $e2 = $e2 + $line['total']; }
	if ($tp == 'A') { $tp = 'Disponível em estoque';  $e2 = $e2 + $line['total']; }
	$sr .= '<TR>';
	$sr .= '<TD>';
	$sr .= $tp;
	$sr .= '<TD align="right">';
	$sr .= number_format($line['total'],0);
	}

	$sr .= '<TR>';
	$sr .= '<TD>';
	$sr .= '<B>Total em estoque</B>';
	$sr .= '<TD align="right">';
	$sr .= number_format($e2,0);

	$sr .= '<TD>';
	$sr .= '<B>Total em estoque</B>';
	$sr .= '<TD align="right">';
	$vvv = $e2 + $saldo_ini +$e1;
	if ($vvv != ($saldo_ini + $qi)) { $sr .= '<A HREF="#" onclick="newxy2('.chr(39).'produtos_estoque_cancel_checkin.php?dd0='.$dd[0].chr(39).',200,200);"><font color="RED"><B>'; }
	$sr .= number_format($vvv ,0);
?>

<table width="<?=$tab_max;?>">
<?=$sr;?>
</table>

<?
require("produtos_estoque_checkin_auto.php");
require("produtos_estoque_cancel_auto.php");

if ($red == 1)
	{
	redirecina("produtos_estoque_grupo_cardex.php?dd0=".$dd[0]);
	}
?>

<? echo $hd->foot();	?>
