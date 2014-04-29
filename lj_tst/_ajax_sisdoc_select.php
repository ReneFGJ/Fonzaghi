<?php
require("db.php");
require($include.'sisdoc_debug.php');
$p = array();
$s = $_POST['para'];
$s .= $_GET['para'].':';
while (strpos($s,':') > 0)
	{
	$ps = strpos($s,':');
	$sx = trim(substr($s,0,$ps));
	$s = ' '.substr($s,$ps+1,strlen($s));
	array_push($p,$sx);
	}
$filtro = ($_POST['filtro']).($_GET['filtro']);
$db = $p[3];
if (strlen($db) > 0) { echo '==='; require($db); }
/**
* Esta classe é a responsável pela conexão com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.11.43
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_ajax_select
*/
?>
<option value="">::Selecione</option>
<?
$sql = trim($p[2]);
$sqll = lowercase($p[2]);
print_r($p);
echo '<HR>==>'.$sqll.'<HR>';
$sqlo = '';
if (strpos($sqll,' order ') > 0)
	{
	$pos = strpos($sqll,'order by');
	$sql = substr($sql,0,$pos);
	$sqlo = substr($sql,$pos,strlen($sql));
	}
	
/* Where */
if (strpos($sqll,'where') > 0)
	{ 
		$sql .= " and ".$p[0]." like '%".$filtro."%' ";
	} else {
		$sql .= " where ".$p[0]." like '%".$filtro."%' ";
	}
$sql .= $sqlo;
$sql .= " limit 20";
$sql = troca($sql,chr(92),'');
echo '<HR>'.$sql.'<HR>';
$rlt = db_query($sql);
	$cp1 = $p[0];
	$cp2 = $p[1];

while ($line = db_read($rlt))
	{
	echo '<option value="'.$line[$cp2].'">';
	echo $line[$cp1];
	echo '</option>';
	}
?>


