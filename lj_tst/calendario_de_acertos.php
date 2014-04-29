<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));
array_push($breadcrumbs, array('calendario_de_acertos.php','Calendario'));

$include = '../';
require($include."cab_novo.php");

require("../_class/_class_calendario.php");
$cal = new calendario;
require("db_temp.php");

echo '<h1>Mês - '.(date("m")-3).'</h1>';
echo $cal->calendario_acerto(date("Y"),(date("m")-3));

echo '<h1>Mês - '.(date("m")-2).'</h1>';
echo $cal->calendario_acerto(date("Y"),(date("m")-2));

echo '<h1>Mês - '.(date("m")-1).'</h1>';
echo $cal->calendario_acerto(date("Y"),(date("m")-1));

echo '<h1>Mês - '.(date("m")+0).'</h1>';
echo $cal->calendario_acerto(date("Y"),date("m"));

echo '<h1>Mês - '.(date("m")+1).'</h1>';
echo $cal->calendario_acerto(date("Y"),(date("m")+1));


echo '</table>';
?>