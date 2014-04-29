<?
$include = '../';
require("../db.php");
require("../db_fghi_206_2via.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
?>
<style>
h1 {
	font-family : "Courier New", Courier, monospace;
	font-size : 15px;
	font-style : normal;
}
h2 {
	font-family : "Courier New", Courier, monospace;
	font-size : 17px;
	font-style : normal;
}
h3 {
	font-family : "Courier New", Courier, monospace;
	font-size : 19px;
	font-style : normal;
}
h4 {
	font-family : "Courier New", Courier, monospace;
	font-size : 21px;
	font-style : normal;
}
</style>
<?
$tabela = 'via_log_'.substr($dd[1],0,6);
if (tableexist($tabela) == 1)
	{
	$sql = "select * from ".$tabela." where id_v = ".$dd[0];
	$rlt = db_query($sql);
	if ($line = db_read($rlt))
		{
		$txt = $line['v_texto'];
		$data = $line['v_data'];
		$hora = $line['v_hora'];
		$log = $line['v_log'];
		$loja = $line['v_loja'];
		$tipo = $line['v_tipo'];
		echo '<PRE>';
		echo $txt;
		echo '</PRE>';
		}
	} else {
		echo 'Erro na consulta '.$tabela;
	}
?>