<?
$table_bg = "#c0c0c0";
$titulo = "Submeter arquivos (todos os tipos)";
$classe = "Todos";
$fld = array("jpg","png");
$limit = 2 * 1024 * 1024;

////////////// Pasta para gravar imagens
$upload_dir = '/dados/imagens/scanner/';
$upload_dir = $_SERVER['SCRIPT_FILENAME'];
$upload_dir = troca($upload_dir,'upload.php','img_produto/');
$controle_mes = 0; // abre nova pasta para cada ano / mês

$info = "";

$lt1  = "font-family : Arial, Helvetica, sans-serif; font-size: 12px; color : Black; ";
$lt2  = "font-family : Arial, Helvetica, sans-serif; font-size: 14px; color : Black; ";
$lt1i = "font-family : Arial, Helvetica, sans-serif; font-size: 12px; color : Blue; ";
$body = "background-image : url(upload_bg.png); background-position : center; background-repeat : repeat;";

$tabela_ged = ''; // nome da tabela que salva os arquivos GED
$updatex = ''; // arquivo que chama quando salva corretamente
?>