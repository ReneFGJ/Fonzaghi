<?
$breadcrumbs=array();
array_push($breadcrumbs, array('/fonzaghi/sensual/index.php','Sensual'));
array_push($breadcrumbs, array('/fonzaghi/sensual/estoque_baixa.php','Baixa de estoque de produto danificado/amostra'));

$include = '../';
require("../cab_novo.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_form2.php");
require($include."sisdoc_data.php");
require($include."sisdoc_debug.php");
require($include."cp2_gravar.php");
require("db_temp.php");

$df = round((date("Y")+1).'12');

for ($r=201002;$r <= $df;$r++)
	{
	if (substr($r,4,2) == '13') { $r = $r + 100 - 12; }
	$sql = "select * from pg_tables where schemaname='public'";
	$sql .= " and tablename = 'produto_log_".$r."' ";
	$rlt = db_query($sql);

	echo '<BR>'.'produto_log_'.$r;
	if (!($line = db_read($rlt)))
		{
		$sql = "CREATE TABLE produto_log_".$r."
		(
		  pl_ean13 character(15),
		  pl_data integer DEFAULT 0,
		  pl_hora character(5),
		  pl_cliente character(7),
		  pl_status character(1),
		  pl_kit character(6),
		  pl_produto character(7),
		  pl_log character(10),
		  id_pl serial NOT NULL,
		  CONSTRAINT key_produto_log_".$r." PRIMARY KEY (id_pl)
		)";
		echo '<font color="#008000">Criado</font>';
		$rlt = db_query($sql);
		}
	
	
	}