<?
global $cnn;

$sqldia="extract(day from ";
$sqlmes="extract(month from ";
$sqlano="extract(year from ";

		$base = "pgsql";
		$base_user="postgres";
		$base_port = '5432';
		$base_host="10.1.1.210";
		$base_name="FGHI";
		$base_pass="448545ct";
		$ftp_img  = 'www.egg.com.br/ic.php?dd99=upload&';
		$ftp_host = '10.1.1.210';
		$ftp_user = 'rene';
		$ftp_pass = '448545';
		$ftp_path = 'httpdocs/img/ic';
$ok = db_connect();
?>
