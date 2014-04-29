<?
$breadcrumbs=array();

array_push($breadcrumbs, array('/fonzaghi/main.php','Inicial'));
array_push($breadcrumbs, array('index.php','Loja'));
$include = '../';
require('../cab_novo.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_windows.php');
require($include."sisdoc_colunas.php");

$tabela = "fornecedores";
$sql = "select * from ".$tabela." where id_fo = ".$dd[0];
$rlt = db_query($sql);
require('../db_fghi.php');
while ($line = db_read($rlt))
	{   
		$razao=$line['fo_razaosocial'];
		$fantazia=$line['fo_nomefantasia'];
		$cod = $line['fo_codfor'];
		
	}
require('db_temp.php');
$sql = "select * from produto where p_cod_fornecedor = '".$cod."' ";
$rlt = db_query($sql);

echo '<table class="pg_white border padding5 wc80" align="center"  width="'.$tab_max.'">';
echo '<TR><TD colspan="4" align="center"><H2>'.$cod.' - '.$razao.'<br>'.$fantazia.'</br></h2>';

while ($line = db_read($rlt))
{
    $prod = $line['p_codigo'];   
    require('db_temp.php');
    $sql2 = "select count(*) as total, pe_status, sum(pe_vlr_vendido) as vendido 
            from produto_estoque where pe_fornecedor = '".$cod."' and 
                                       pe_produto = '".$prod."' and 
                                       pe_status <> 'X' 
            group by pe_status order by pe_status ";
    $rlt2 = db_query($sql2);
    $link = '<A HREF="produtos_estoque_individual.php?dd0='.$line['id_p'].'&dd90='.checkpost($line['id_p']).'" target="NW'.$line['id_p'].'">';
    echo '<TR bgcolor="#F1F1F1" align="left"><TD colspan="4" ><b>';
    echo $link.$line['p_descricao'];
    echo '<b><TR align="left"class="lt2"><TH><TH align="left">Status<TH align="center">Quantidade<TH align="right">Valor';
    $tt=0;
    while ($line2 = db_read($rlt2))
        {
            
            if ($line2['pe_status']=='T') {
            echo '<TR class="lt1"><TD><TD align="left">Vendido';       
            echo '<TD align="center">'.$line2['total'];
            echo '<TD align="right">'.number_format($line2['vendido'],2);}
            if ($line2['pe_status']=='A') {
            echo '<TR class="lt1"><TD><TD align="left">Na Loja';       
            echo '<TD  align="center">'.$line2['total'];
            echo '<TD  align="right">'.number_format($line2['vendido'],2);
            $tt=$tt+$line2['total'];}
            if ($line2['pe_status']=='F') {
            echo '<TR class="lt1"><TD><TD align="left">Fornecido';       
            echo '<TD  align="center">'.$line2['total'];
            echo '<TD  align="right">'.number_format($line2['vendido'],2);
            $tt=$tt+$line2['total'];}
        }
     echo '<TR bgcolor="#F1F1F1" align="left"><TD colspan="4" >Total em estoque '.$tt.'<b>';
}
echo '</table>';
echo $hd->foot();
?>