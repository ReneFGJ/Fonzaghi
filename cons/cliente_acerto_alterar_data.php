<?
 /**
  * Alterar data de acerto
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2011 - sisDOC.com.br
  * @access public
  * @version v0.11.41
  * @package Classe
  * @subpackage UC0029 - Alterar data de acerto
 */
$include = '../';
$nocab=1;
require("../cab_novo.php");
require("../_class/_class_form.php");
$form = new form;
require($include."sisdoc_data.php");
require($include.'sisdoc_colunas.php');
require("../css/letras.css");

if ($dd[1] == 'S')
	{ 
	$dba = "../db_fghi_206_sensual.php"; 
	$titulo = "Sensual";
	$loja = "Boutique Sensual";
	}
	
if ($dd[1] == 'O')
	{ 
	$dba = "../db_fghi_206_oculos.php"; 
	$titulo = "Óculos";
	$loja = "Óculos";
	}	
	
if ($dd[1] == 'U')
	{ 
	$dba = "../db_fghi_206_ub.php"; 
	$titulo = "Use Brilhe";
	$loja = "Use Brilhe";
	}	
	
if ($dd[1] == 'M')
	{ 
	$dba = "../db_fghi_206_modas.php"; 
	$titulo = "Intima";
	$loja = "Intima";
	}	
	
if ($dd[1] == 'J')
	{ 
	$dba = "../db_fghi_206_joias.php"; 
	$titulo = "Jóias";
	$loja = "Jóias";
	}	

if ($dd[1] == 'E')
	{ 
	$dba = "../db_fghi_206_express.php"; 
	$titulo = "Jóias";
	$loja = "Jóias";
	}
	
if ($dd[1] == 'G')
	{ 
	$dba = "../db_fghi_206_express_joias.php"; 
	$titulo = "Jóias Express";
	$loja = "Jóias Express";
	}	
	
require($dba);
$tab_max = '100%';
if (strlen($dd[0]) == 0)
	{
	$sql = "select * from kits_consignado ";
	$sql .= " where kh_cliente = '".$dd[2]."' and kh_status = 'A' ";
	$rlt = db_query($sql);
	
	if ($line = db_read($rlt))
		{
			$dd[0] = $line['id_kh'];
		} else {
			echo 'Cliente não tem consignado nesta loja';
			exit;
		}
	}


$tabela = "kits_consignado";
$cp = array();
$opx="";
/*0*/array_push($cp,array('$H4','id_kh','id_kh',False,True,''));
/*1*/array_push($cp,array('$HV','',$dd[1],False,True,''));
/*2*/array_push($cp,array('$HV','',$dd[2],False,True,''));
/*3*/array_push($cp,array('$A8','','Data atual de acerto',False,True,''));
/*4*/array_push($cp,array('$D8','kh_previsao','Atual',False,False,''));
/*5*/array_push($cp,array('$A8','','Alterar para',False,True,''));
/*6*/array_push($cp,array('$D8','kh_previsao','Para',True,True,''));


$http_edit = 'cliente_acerto_alterar_data.php';
$http_redirect = '';
$tit = strtolower(troca($dd[99],'_',' '));
$tit = strtoupper(substr($tit,0,1)).substr($tit,1,strlen($tit));
echo '<CENTER><font class=lt5>Alteração de dia de acerto</font></CENTER>';
?><TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
echo $form->editar($cp, $tabela);
?></TD></TR></TABLE><?

/*Fazer validação para datas maiores e menores que 40 dias*/
//$dt1 = date("Ymd", mktime(0, 0, 0, date('m'), date('d')-40, date('Y')));
//$dt2 = date("Ymd", mktime(0, 0, 0, date('m'), date('d')+40, date('Y')));

if ($form->saved > 0)
	{
		if ($dd[6] != $dd[4]){
			require("../db_fghi_206_cadastro.php");
			$sql = "insert into historico_".date('Y')." ";
			$sql .= "(h_data,h_hora,h_log,h_texto,h_cliente,h_tipo) ";
			$sql .= " values ";
			$sql .= "('".date("Ymd")."','".date("H:i")."','".$user->user_log."','Alteração dia de acerto de ".$dd[4]." para ".$dd[6]." na loja ".$loja."',";
			$sql .= "'".$dd[2]."','".$dd[1]."01');";
			$rlt = db_query($sql);
		}
	require("../close.php");
	echo 'SALVO';
	}
?>