<?
$include = '../';
require("../db.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_ro8.php");

require('../db_206_telemarket.php');
$encode = "UTF-8";

$dbbase =  trim(strtolower($vars['base']));

if (strlen($dbbase) > 0)
	{
	if (file_exists($dbbase))
		{ require($dbbase); } else { echo 'Arquivo "<B>'.$dbbase.'</B>" n�o existe'; exit; }
	}

echo ro8();
?>