<?
$include = '../';
require("../db.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_ro8.php");
require('db_temp.php');
$encode = "UTF-8";

$dbbase =  trim(strtolower($vars['base']));

if (strlen($dbbase) > 0)
	{
	if (file_exists($dbbase))
		{ require($dbbase); } else { echo 'Arquivo "<B>'.$dbbase.'</B>" não existe'; exit; }
	}

echo ro8();
?>