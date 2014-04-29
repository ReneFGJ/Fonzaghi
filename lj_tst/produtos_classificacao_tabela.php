<?

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('produtos_classificacao_tabela.php','Tabela de Classificação'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
require($include.'sisdoc_colunas.php');
?>
<table width="<?=$tab_max;?>" class="lt1">
<TR><TD>
<img src="img/logo_empresa.png" width="231" height="79" alt="" border="0">
</TD></TR>
</table>
<?
$sql = "select * from produto_grupos ";
$sql .= " order by pg_codigo ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$cf = $line['pg_codigo'];
	$cd = $line['pg_descricao'];
	
	if (strlen($line['pg_g3']) == 0)
		{
			if (strlen($line['pg_g2']) == 0)
			{
				$sn .= '<TR>';
				$sn .= '<TD class="lt4" colspan="5"><B>'.$cd.' ('.$line['pg_g1'].')';
			} else {
				$sn .= '<TR>';
				$sn .= '<TD width="20">&nbsp;</TD><TD class="lt3" colspan="4"><B><I>'.$cd.' ('.$line['pg_g1'].'-'.$line['pg_g2'].')';
			}
		} else {
			$sn .= '<TR '.coluna().' class="lt2">';
			$sn .= '<TD>';
			$sn .= $cf;
			$sn .= '</TD>';
		
			$sn .= '<TD>';
			$sn .= $line['pg_g1'];
			$sn .= '</TD>';
		
			$sn .= '<TD>';
			$sn .= $line['pg_g2'];
			$sn .= '</TD>';
		
			$sn .= '<TD>';
			$sn .= $line['pg_g3'];
			$sn .= '</TD>';

			$sn .= '<TD>';
			$sn .= $cd;
			$sn .= '</TD>';
			$sn .= '</TR>';
		}
	}
?><center>
<h1>Tabela de classificação</h1>	
<TABLE width="710" align="center" border="0">
<?=$sn;?>
</TABLE>
<? echo $hd->foot();	?>