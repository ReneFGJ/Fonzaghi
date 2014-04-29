<?
    /**
     * Sistema de avaliação de competências
     * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v0.13.24
     * @package avaliacao
     * @subpackage operacional
    */

$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Home'));
    
$include = '../';
require("../cab_novo.php");

?>
<style>
	.tips { background: #FFFFFF; border: solid 2px #FFFFFF; margin: 1px; padding: 5px; width: 400px; }
	.link { color: #202020; text-decoration: none; }
	.link:hover{ color: #0000FF; text-decoration: none; }
</style>
<?
//* conteudo */
require($include."sisdoc_data.php");
require($include."sisdoc_tips.php");
require($include."sisdoc_windows.php");
require($include."sisdoc_colunas.php");
require("../_class/_class_avaliacao_competencia.php");
$aval = new avaliacao;
$cp = array();
	
require("../db_drh.php");
ob_start();
$avaliador= $dd[1]=$_SESSION['nw_cracha'];
$funcionario=$dd[2]=$dd[1];

echo '<h1>Auto-avaliação</h1>';
echo '<table width="700" align="center">
        <tr><td class="pg_white border padding5 wc80" align="center">'.$_SESSION['nw_cracha'].' - '.$_SESSION['nw_user_nome'].'</div>
        <tr><td><tr><td>
        <tr><td class="pg_white border padding5 wc80" align="center">'.$aval->avaliacao_individual($funcionario,$avaliador).'
     </table';		

?>

