<?php
/**
 * Metas
 * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
 * @copyright Copyright (c) 2013 - sisDOC.com.br
 * @access public
 * @version v.0.13.42
 * @package Classe
 * @subpackage melhor_atender
*/

class melhor_atender
{

    var $tabela = '';

    function resultado($loja,$datai='',$dataf='',$field='kh_log_acerto')
    {
        global $base_name,$base_rlineserver,$base_host,$base_user,$user;
        if (strlen(trim($datai)) ==0) { $datai = date("Ymd"); }
        if (strlen(trim($dataf)) ==0) { $dataf = date("Ymd"); }
        
        switch ($loja) {
            case '0':   $db = "../db_fghi_206_joias.php";           $tit = "Jóias";             break;
            case '1':   $db = "../db_fghi_206_modas.php";           $tit = "Modas";             break;
            case '2':   $db = "../db_fghi_206_oculos.php";          $tit = "Óculos";            break;
            case '3':   $db = "../db_fghi_206_ub.php";              $tit = "Use Brilhe";        break;
            case '4':   $db = "../db_fghi_206_sensual.php";         $tit = "Sensual";           break;
            case '5':   $db = "../db_fghi_206_express.php";         $tit = "Express modas";     break;
            case '6':   $db = "../db_fghi_206_express_joias.php";   $tit = "Express Jóias";     break;
            default:                                                                            break;
        }           
        require($db);
        $sql = "select cons.kh_cliente as fl_cliente, acerto.".$field." as jv_log, cons.kh_status as st, ";
        $sql .= " acerto.kh_pago as vlr_pago, acerto.kh_acerto as dtacerto, cons.kh_fornecimento as fornece,  ";
        $sql .= " acerto.kh_cliente, cons.kh_cliente, * from kits_consignado as acerto ";
        $sql .= "left join kits_consignado as cons on acerto.kh_cliente = cons.kh_cliente and cons.kh_status = 'A' ";
        $sql .= "inner join clientes on acerto.kh_cliente = cl_cliente ";
        $sql .= "where acerto.kh_acerto >= ".$datai." and acerto.kh_acerto <= ".$dataf." ";
        $sql .= "order by jv_log ";
        if (strlen(trim(substr($dd[3],0,1))) > 0)
            {
            $sql .= " and cl_clientep = '".$dd[3]."' ";
            }
 //           echo $sql."<br><br><br>";
        $rlt = db_query($sql);
        if (pg_num_rows($rlt)==0){
            $sx .= '<font color="#ff0000" size="+1">Não há registros no período: '.$datai.' à '.$dataf.'.</font>';
        }

        $ate = array('-');
        $to1 = array(0);
        $to2 = array(0);
        $to3 = array(0);
        $key = 1;
        $t1=0;
        $t2=0;
        $s = '';
        $t5 = 0;
        $sqlc = '';
        while ($line = db_read($rlt))
            {
            if (strlen($sqlc) > 0) { $sqlc .= ' or '; }
            $sqlc .= "dp_cliente = '".$line['cl_cliente']."' ";
            }
        
        $sqlx = "select count(*) as tota, sum(dp_valor) as valor, dp_cliente from ( ";
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_modas ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_joias ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_oculos ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_sensual ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_usebrilhe ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        
        $sqlx .= ") as tabela group by dp_cliente";
        require("../db_fghi_210.php");
        $rrr = db_query($sqlx);
        
        $cliex = array();
        $cliey = array();
        while ($rline = db_read($rrr))
            {
            array_push($cliex,$rline['dp_cliente']);
            array_push($cliey,$rline['valor']);
            }
        
        require($db);
        $rlt = db_query($sql);
        $pe = 0;
        $jv_ant='';
        while ($line = db_read($rlt))
            {
            $dt = trim(substr($line['fornece'],6,4).substr($line['fornece'],3,2).substr($line['fornece'],0,2));
            $dc = $line['cl_cliente'];
            $cor = '';
            $pend = '';
            if (strlen($dt) < 8)
                { 
                for ($r=0;$r < count($cliex);$r++)
                    {
                        if ($cliex[$r] == $dc) 
                            {
                                 $pend = number_format($cliey[$r],2); 
                                 $pe++; 
                                 $r= count($cliex); 
                            }
                    }
                $t5++;
                $dt = 'não retirou'; 
                $cor = '<font color="#990000">';
				if ($line['st']=='A') 
					{
						$dt = 'retirou';
						$cor = '<font color="#000099">'; 
					}
                $tot++;
                if (strlen($pend) > 0)
                    {
                    $dt = 'pendência'; 
                    $cor = '<font color="#6600ff">'; }
                    if($line['jv_log']!=$jv_ant)
                    {
                        if (strlen(trim($jv_ant))!=0) 
                            {
                                $tx .='</table></div>';
                            }
                         require('../db_fghi.php');
                         $fc=new funcionario;   
                         $fc->le_login($line['jv_log']);
                         $ln=$fc->line;
                         $us_nome=$ln['us_nomecompleto'];
                         $tot=1;
                         $ed='add';
						 $tx.='<div id="a'.trim($line['jv_log']).'" style="display:none;"><table border="0" width="100%">
                                <TR><TH class="tabelaTH" colspan="7" align="center" bgcolor="#FFFFFF">'.$us_nome.'</TD></TR>
                                <TR>
                                <TH class="tabelaTH" width="3%">N.</TH>
                                <TH class="tabelaTH" width="15%">Código</TH>
                                <TH class="tabelaTH" width="40%" align="left">Nome</TH>
                                <TH class="tabelaTH" width="7%">Atendente</TH>
                                <TH class="tabelaTH" width="10%">Vlr.Acerto</TH>
                                <TH class="tabelaTH" width="10%">Dt. Acerto</TH>
                                <TH class="tabelaTH" width="10%">Status</TH>
                                <TH class="tabelaTH" width="15%">Vlr. Pendente</TH>
                                </TR>';
                    }
                    $tx .= '<TR '.coluna().'>';
                    $tx .= '<TD class="tabela01" align="center">'.$tot.'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$line['cl_cliente'].'</TD>';
                    $tx .= '<TD class="tabela01" align="left">'.$line['cl_nome'].'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$line['jv_log'].'</TD>';
					$tx .= '<TD class="tabela01" align="right">'.number_format($line['vlr_pago'],2,',','.').'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$cor .date('d/m/Y', mktime(0,0,0,substr($line['dtacerto'],4,2),substr($line['dtacerto'],6,2),substr($line['dtacerto'],0,4))).'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$cor . $dt.'</TD>';
                    $tx .= '<TD class="tabela01" align="right">'.$pend.'</TD></TR>';
                    $jv_ant=$line['jv_log'];
                    $jsclose.='document.getElementById("a'.trim($line['jv_log']).'").style.display="none";'; 
                    $jsopen.='document.getElementById("a'.trim($line['jv_log']).'").style.display="inline";';
                    $jsaddallin.='document.getElementById("add'.trim($line['jv_log']).'").style.display="inline";'; 
                    $jscloseallin.='document.getElementById("close'.trim($line['jv_log']).'").style.display="inline";';
                    $jsaddallno.='document.getElementById("add'.trim($line['jv_log']).'").style.display="none";'; 
                    $jscloseallno.='document.getElementById("close'.trim($line['jv_log']).'").style.display="none";';
                }
            $log = trim($line['jv_log']);
            $for = trim($line['fl_cliente']);
            if (!(in_array($log,$ate)))
                    {
                        array_push($ate,$log);
                        array_push($to1,0);
                        array_push($to2,0);
                        array_push($to3,0);
                        $x1 = count($ate)-1;
                        $key++;
                    } else {
                        $x1 = -1;
                        for ($r = 0;$r < count($ate);$r++)
                            { if ($ate[$r] == $log) { $x1 = $r; } }
                    }
                   
                    if ($x1 > 0)
                        {
                        $to1[$x1] = $to1[$x1] + 1;
                        if (strlen($for) == 0)
                            {
                            $to2[$x1] = $to2[$x1] + 1; 
                            if ($pend > 0) { $to3[$x1] = $to3[$x1] + 1; $pe++; }
                            }
                        }
                }
             
        $tx .='</table>';
		
		
        $sx .= '<div>
                <CENTER><H1>Retenção '.$tit.':'.substr($datai,6,2).'/'.substr($datai,4,2).'/'.substr($datai,0,4).' 
                                 até '.substr($dataf,6,2).'/'.substr($dataf,4,2).'/'.substr($dataf,0,4).'<BR>'.$dd[3].'</H1></CENTER>
                <table border="0" width="100%" class="tabela00">
                <TR>
                    <TH class="noprint"  width="5%"></TH>
                    <TH class="tabelaTH" width="40%">Atendente</TH>
                    <TH class="tabelaTH" width="10%">Acertos</TH>
                    <TH class="tabelaTH" width="10%">Devoluções</TH>
                    <TH class="tabelaTH" width="10%">Pendência<BR>financeira</TH>
                    <TH class="tabelaTH" width="15%">Índ. Retenção (com pend)</TH>
                    <TH class="tabelaTH" width="15%">Índ. Retenção (sem pend)</TH>
                </TR>
                ';
        
        for ($r = 1;$r < count($ate);$r++)
            {
                require('../db_fghi.php');
                $fc=new funcionario;   
                $fc->le_login($ate[$r]);
                $ln=$fc->line;
                $us_cracha=$ln['us_cracha'];
                $link1 = '<div id="add'.trim($ate[$r]).'" style="display:inline;" onclick="iconadd'.trim($ate[$r]).'()">';
                $link2 = '<div id="close'.trim($ate[$r]).'" style="display:none;" onclick="iconclose'.trim($ate[$r]).'()">';
                $tem = 0;
                
                $log = trim($ate[$r]);
                if ($to1[$r] > 0)
                    {
                         $tem = round($to2[$r]/$to1[$r]*1000)/10; 
                    }
                if ($to1[$r] > 0)
                    {
                         $tep = round(($to2[$r] - $to3[$r])/$to1[$r]*1000)/10; 
                    }
                $sx .= '<TR '.coluna().'><TD align="center" class="noprint" >'.$link1.'<img src="../ico/add.png"></div>
                                '.$link2.'<img src="../ico/delete.png"></div>
                </TD><TD><img src="http://10.1.1.206/fonzaghi/funcionario/foto/'.$us_cracha.'.JPG" height="40" align="left" border="0"">
                                            '.$ate[$r].' - '.$ln['us_nomecompleto'].'</TD>';
                $sx .= '<TD align="center">'.$to1[$r].'</TD>';
                $sx .= '<TD align="center">'.$to2[$r].'</TD>';
                $sx .= '<TD align="center">'.$to3[$r].'</TD>';
                $sx .= '<TD align="center">'.(100-$tem).'%</TD>';
                $sx .= '<TD align="center"><font color="#ba1818">'.(100-$tep).'%</TD>';
                
                /* Excluir dados da cobranca */
                if(($log!='NANA') and ($log!='CAD2'))
                {
                    $t1 = $t1 + $to1[$r];
                    $t2 = $t2 + $to2[$r];
                    $t3 = $t3 + $to3[$r];
                }
                
                 $js.='<script>
                            function iconadd'.trim($ate[$r]).'()
                            {
                                document.getElementById("add'.trim($ate[$r]).'").style.display="none";
                                document.getElementById("close'.trim($ate[$r]).'").style.display="inline";
                                document.getElementById("a'.trim($ate[$r]).'").style.display="inline"; 
                            }
                            function iconclose'.trim($ate[$r]).'()
                            {
                                document.getElementById("add'.trim($ate[$r]).'").style.display="inline";
                                document.getElementById("close'.trim($ate[$r]).'").style.display="none";
                                document.getElementById("a'.trim($ate[$r]).'").style.display="none";
                            }
                            </script>';
            }
        $sx .= '
                    <TR>
                        <TD class="noprint"></TD>
                        <TD align="center" class="lt5">Total</TD>
                        <TD align="center" class="lt5">'.$t1.'</TD>
                        <TD align="center" class="lt5">'.$t2.'</TD>
                        <TD align="center" class="lt5">'.$t3.'</TD>
                        <TD align="center" class="lt5"><nobr><font color="#808080">'.(100-number_format(100*$t2/$t1,2)).'%</font>
                        <TD align="center" class="lt5"><font color="#000000">'.(100-number_format(100*($t2-$t3)/$t1,2)).'%</font></TD>
                    </TR>
                    </table>
                </div>';
         $sx .= '<div  class="noprint" align="right">Detalhamento<A onclick="openall()"><img src="../ico/add.png"></A><A onclick="closeall()"><img src="../ico/delete.png"></A></div>';   
                
        $js .='<script> function closeall() { '.$jsclose.$jsaddallin.$jscloseallno.' } </script>';
        $js .='<script> function openall() { '.$jsopen.$jsaddallno.$jscloseallin.' } </script>';
        $sx .= $tx."</br>".$js;
        return($sx);
    }

	function resultado_desistencia($loja,$datai='',$dataf='',$field='kh_log_acerto',$ordem=1)
    {
        global $base_name,$base_rlineserver,$base_host,$base_user,$user;
        if (strlen(trim($datai)) ==0) { $datai = date("Ymd"); }
        if (strlen(trim($dataf)) ==0) { $dataf = date("Ymd"); }
        
        switch ($loja) {
            case '0':   $db = "../db_fghi_206_joias.php";           $tit = "Jóias";             break;
            case '1':   $db = "../db_fghi_206_modas.php";           $tit = "Modas";             break;
            case '2':   $db = "../db_fghi_206_oculos.php";          $tit = "Óculos";            break;
            case '3':   $db = "../db_fghi_206_ub.php";              $tit = "Use Brilhe";        break;
            case '4':   $db = "../db_fghi_206_sensual.php";         $tit = "Sensual";           break;
            case '5':   $db = "../db_fghi_206_express.php";         $tit = "Express modas";     break;
            case '6':   $db = "../db_fghi_206_express_joias.php";   $tit = "Express Jóias";     break;
            default:                                                                            break;
        }           
        require($db);
        $sql = "select cons.kh_cliente as fl_cliente, acerto.".$field." as jv_log, cons.kh_status as st, ";
        $sql .= " acerto.kh_pago as vlr_pago, acerto.kh_acerto as dtacerto, cons.kh_fornecimento as fornece,  ";
        $sql .= " acerto.kh_cliente, cons.kh_cliente, * from kits_consignado as acerto ";
        $sql .= "left join kits_consignado as cons on acerto.kh_cliente = cons.kh_cliente and cons.kh_status = 'A' ";
        $sql .= "inner join clientes on acerto.kh_cliente = cl_cliente ";
        $sql .= "where acerto.kh_acerto >= ".$datai." and acerto.kh_acerto <= ".$dataf." ";
		$sql .= "order by vlr_pago desc";		
		
       
	    if (strlen(trim(substr($dd[3],0,1))) > 0)
            {
            $sql .= " and cl_clientep = '".$dd[3]."' ";
            }

        $rlt = db_query($sql);
        if (pg_num_rows($rlt)==0){
            $sx .= '<font color="#ff0000" size="+1">Não há registros no período: '.$datai.' à '.$dataf.'.</font>';
        }

        $ate = array('-');
        $to1 = array(0);
        $to2 = array(0);
        $to3 = array(0);
        $key = 1;
        $t1=0;
        $t2=0;
        $s = '';
        $t5 = 0;
        $sqlc = '';
        while ($line = db_read($rlt))
            {
            if (strlen($sqlc) > 0) { $sqlc .= ' or '; }
            $sqlc .= "dp_cliente = '".$line['cl_cliente']."' ";
            }
        
        $sqlx = "select count(*) as tota, sum(dp_valor) as valor, dp_cliente from ( ";
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_modas ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_joias ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_oculos ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_sensual ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        $sqlx .= " union ";
        
        $sqlx .= "select dp_valor, dp_cliente from  duplicata_usebrilhe ";
        $sqlx .= " where (".$sqlc.") ";
        $sqlx .= " and (dp_status = 'A') and (dp_venc <= ".$dataf.") ";
        
        $sqlx .= ") as tabela group by dp_cliente";
        
        $sqlx .= " order by valor desc ";
	    
        require("../db_fghi_210.php");
        $rrr = db_query($sqlx);
        
        $cliex = array();
        $cliey = array();
        while ($rline = db_read($rrr))
            {
            array_push($cliex,$rline['dp_cliente']);
            array_push($cliey,$rline['valor']);
            }
        
        require($db);
        $rlt = db_query($sql);
        $pe = 0;
        $jv_ant='';
		
		 $tx.=' <TR>
                <TH class="tabelaTH" width="3%">N.</TH>
                <TH class="tabelaTH" width="15%">Código</TH>
                <TH class="tabelaTH" width="40%" align="left">Nome</TH>
                <TH class="tabelaTH" width="7%">Atendente</TH>
                <TH class="tabelaTH" width="10%">Vlr.Acerto</TH>
                <TH class="tabelaTH" width="10%">Dt. Acerto</TH>
                <TH class="tabelaTH" width="10%">Status</TH>
                <TH class="tabelaTH" width="15%">Vlr. Pendente</TH>
                </TR>';

		
        while ($line = db_read($rlt))
            {
            $dt = trim(substr($line['fornece'],6,4).substr($line['fornece'],3,2).substr($line['fornece'],0,2));
            $dc = $line['cl_cliente'];
            $cor = '';
            $pend = '';
            if (strlen($dt) < 8)
                { 
                for ($r=0;$r < count($cliex);$r++)
                    {
                        if ($cliex[$r] == $dc) 
                            {
                                 $pend = number_format($cliey[$r],2); 
                                 $pe++; 
                                 $r= count($cliex); 
                            }
                    }
			    $t5++;
                $dt = 'não retirou'; 
             
                $cor = '<font color="#990000">';
				if ($line['st']=='A') 
					{
						$dt = 'retirou';
						$cor = '<font color="#000099">'; 
					}
                if (strlen($pend) > 0)
                    {
                   
                    $dt = 'pendência'; 
                    $cor = '<font color="#6600ff">'; }
                    if($line['jv_log']!=$jv_ant)
                    {
                        if (strlen(trim($jv_ant))!=0) 
                            {
                                $tx .='</table></div>';
                            }
                         require('../db_fghi.php');
                         $fc=new funcionario;   
                         $fc->le_login($line['jv_log']);
                         $ln=$fc->line;
                         $us_nome=$ln['us_nomecompleto'];
                         $ed='add';
                        
                                                		
                    }
					if($dt<>'retirou')
					{
					switch($dt)
					{
						case 'não retirou':
							$tt_nr++;
							$tt_vlr_nr=$tt_vlr_nr+$pend;
							break;
						case 'pendência':
							$tt_p++;
							$tt_vlr_pend=$pend+$tt_vlr_pend;
							break;
					}	
					$tot++;
                    $tx .= '<TR '.coluna().'>';
                    $tx .= '<TD class="tabela01" align="center">'.$tot.'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$line['cl_cliente'].'</TD>';
                    $tx .= '<TD class="tabela01" align="left">'.$line['cl_nome'].'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$line['jv_log'].'</TD>';
					$tx .= '<TD class="tabela01" align="right">'.number_format($line['vlr_pago'],2,',','.').'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$cor .date('d/m/Y', mktime(0,0,0,substr($line['dtacerto'],4,2),substr($line['dtacerto'],6,2),substr($line['dtacerto'],0,4))).'</TD>';
                    $tx .= '<TD class="tabela01" align="center">'.$cor . $dt.'</TD>';
                    $tx .= '<TD class="tabela01" align="right">'.$pend.'</TD></TR>';
                	}    
			}
            $log = trim($line['jv_log']);
            $for = trim($line['fl_cliente']);
            if (!(in_array($log,$ate)))
                    {
                        array_push($ate,$log);
                        array_push($to1,0);
                        array_push($to2,0);
                        array_push($to3,0);
                        $x1 = count($ate)-1;
                        $key++;
                    } else {
                        $x1 = -1;
                        for ($r = 0;$r < count($ate);$r++)
                            { if ($ate[$r] == $log) { $x1 = $r; } }
                    }
                   
                    if ($x1 > 0)
                        {
                        $to1[$x1] = $to1[$x1] + 1;
                        if (strlen($for) == 0)
                            {
                            $to2[$x1] = $to2[$x1] + 1; 
                            if ($pend > 0) { $to3[$x1] = $to3[$x1] + 1; $pe++; }
                            }
                        }
                }
             
        $tx .='</table>';
		$cab_tot='<center><table>
				<tr>
					<th class="tabelaTH" align="center">Não retirou</th>
					<th class="tabelaTH" align="center">Valor Total</th>
					<th class="tabelaTH" align="center">Pendências</th>
					<th class="tabelaTH" align="center">Valor Total</th>
					<th class="tabelaTH" align="center">Total</th>
				</tr>
				<tr>
					<td class="tabela01" align="center">'.$tt_nr.'</td>
					<td class="tabela01" align="center">'.number_format($tt_vlr_nr,2,',','.').'</td>
					<td class="tabela01" align="center">'.$tt_p.'</td>
					<td class="tabela01" align="center">'.number_format($tt_vlr_pend,2,',','.').'</td>
					<td class="tabela01" align="center">'.$tot.'</td>
				</tr>		
				</table></center>';
        $sx .= $cab_tot.$tx."</br>";
        return($sx);
    }
}
