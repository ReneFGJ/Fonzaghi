<?
$include = '../';
require("../db.php");
require($include."sisdoc_email.php");
?>
<style>
body {BACKGROUND-POSITION: center 50%; FONT-SIZE: 9px; BACKGROUND-IMAGE: url(/img/bg.gif); MARGIN: 0px; COLOR: ##dfefff; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; font-weight: normal; color: #000000; bgproperties=fixed}
</style><CENTER>
<?
emailcab($http_local);
require("../letras.css");
echo emailcab('tabela_fornecidos_popup.php?dd0='.$dd[0]);
require("tabela_fornecidos_a.php");
echo $hd->foot();	?>