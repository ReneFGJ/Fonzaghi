<?php
class duplicatas
	{
		
 /**
  * Duplicatas
  * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
  * @copyright Copyright (c) 2013 - sisDOC.com.br
  * @access public
  * @version v.0.13.33
  * @package Classe
  * @subpackage UC00XX - Classe de Interoperabilidade de dados
 */
 	
	
	/*atributos dos acertos*/
	var $lojas;
	var $ttdp=6; 	/* são 6 contando apartir do zero: jóias e jóias-ex, modas e modas-ex, oculos, usebrilhe, sensual e juridico*/
	var $tbdp; 		/*atributo como o nome das tabelas das duplicatas*/
	var $dupl;		/*matriz: [][0] - siglas, [][1] - nomes acentuados, [][2] - nome das tabelas,*/
	var $txt;

	
	var $id_dp; 
  	var $dp_pedido;
  	var $dp_doc;
  	var $dp_sync;
  	var $dp_historico;
  	var $dp_cliente;
  	var $dp_valor;
  	var $dp_logemite;
  	var $dp_logpaga;
  	var $dp_status;
  	var $dp_horapaga;
  	var $dp_comissao;
  	var $dp_boleto;
  	var $dp_venc;
  	var $dp_data;
  	var $dp_datapaga;
  	var $dp_chq;
  	var $dp_tipo;
  	var $dp_lote;
  	var $dp_terminal;
  	var $dp_juros;
  	var $dp_juridico;
  	var $dp_content;
  	var $dp_carencia;
  	var $dp_local;
  	var $dp_nr;
  	var $dp_doc_mae;
  	var $dp_cfiscal;
  	var $dp_cobranca_externa;
  	var $dp_cobranca_lote;
  	var $dp_nrop;
/*	
	function set_duplicatas($cliente='',$tb){
		global $base_name,$base_host,$base_user;	
		require("../db_fghi_210.php");	
		$this->set_tabelas($tb);
				
		$sql = 'select * from '.$tb.' where dp_cliente = '.$cliente;
					
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
		{
		
			$this->id_dp = $line['id_dp'];
			$this->dp_pedido = $line['dp_pedido'];
			$this->dp_doc = $line['dp_doc'];
			$this->dp_sync = $line['dp_sync'];
			$this->dp_historico = $line['dp_historico'];
			$this->dp_cliente = $line['dp_cliente'];
			$this->dp_valor = $line['dp_valor'];
			$this->dp_logemite = $line['dp_logemite'];
			$this->dp_logpaga = $line['dp_logpaga'];
			$this->dp_status = $line['dp_status'];
			$this->dp_comissao = $line['dp_comissao'];
			$this->dp_horapaga = $line['dp_horapaga'];
			$this->dp_boleto = $line['dp_boleto'];
			$this->dp_venc = $line['dp_venc'];
			$this->dp_data = $line['dp_data'];
			$this->dp_datapaga = $line[''];
			$this->dp_chq = $line['dp_chq'];
			$this->dp_tipo = $line['dp_tipo'];
			$this->dp_lote = $line['dp_lote'];
			$this->dp_terminal = $line['dp_terminal'];
			$this->dp_juros = $line['dp_juros'];
			$this->dp_content = $line['dp_content'];
			$this->dp_carencia = $line['dp_carencia'];
			$this->dp_local = $line['dp_local'];
			$this->dp_nr = $line['dp_nr'];
			$this->dp_doc_mae = $line['dp_doc_mae'];
			$this->dp_cfiscal = $line['dp_cfiscal'];
			$this->dp_cobranca_externa = $line['dp_cobranca_externa'];
			$this->dp_cobranca_lote = $line['dp_cobranca_lote'];
			$this->dp_nrop = $line['dp_nrop'];
					
			$sx = 1;
		} else {
			$sx = 0;
		}
					
	return($sx);
    }	
*/			
	


    function set_duplicatas_id($id='',$tb){
        global $base_name,$base_host,$base_user;    
        require("../db_fghi_210.php");  
        $this->set_tabelas();
        $sql = 'select * from '.$tb.' where id_dp = '.$id;
                    
        $rlt = db_query($sql);
        if ($line = db_read($rlt))
        {
        
            $this->id_dp = $line['id_dp'];
            $this->dp_pedido = $line['dp_pedido'];
            $this->dp_doc = $line['dp_doc'];
            $this->dp_sync = $line['dp_sync'];
            $this->dp_historico = $line['dp_historico'];
            $this->dp_cliente = $line['dp_cliente'];
            $this->dp_valor = $line['dp_valor'];
            $this->dp_logemite = $line['dp_logemite'];
            $this->dp_logpaga = $line['dp_logpaga'];
            $this->dp_status = $line['dp_status'];
            $this->dp_comissao = $line['dp_comissao'];
            $this->dp_horapaga = $line['dp_horapaga'];
            $this->dp_boleto = $line['dp_boleto'];
            $this->dp_venc = $line['dp_venc'];
            $this->dp_data = $line['dp_data'];
            $this->dp_datapaga = $line[''];
            $this->dp_chq = $line['dp_chq'];
            $this->dp_tipo = $line['dp_tipo'];
            $this->dp_lote = $line['dp_lote'];
            $this->dp_terminal = $line['dp_terminal'];
            $this->dp_juros = $line['dp_juros'];
            $this->dp_content = $line['dp_content'];
            $this->dp_carencia = $line['dp_carencia'];
            $this->dp_local = $line['dp_local'];
            $this->dp_nr = $line['dp_nr'];
            $this->dp_doc_mae = $line['dp_doc_mae'];
            $this->dp_cfiscal = $line['dp_cfiscal'];
            $this->dp_cobranca_externa = $line['dp_cobranca_externa'];
            $this->dp_cobranca_lote = $line['dp_cobranca_lote'];
            $this->dp_nrop = $line['dp_nrop'];
                    
            $sx = 1;
        } else {
            $sx = 0;
        }
                    
    return($sx);    
            
    }

	function set_tabelas(){
				
		$this->dupl[0][0]='J';
		$this->dupl[1][0]='M';
		$this->dupl[2][0]='O';
		$this->dupl[3][0]='C';
		$this->dupl[4][0]='S';
		$this->dupl[5][0]='D';
	
		$this->dupl[0][1]='Jóias';
		$this->dupl[1][1]='Modas';
		$this->dupl[2][1]='Óculos';
		$this->dupl[3][1]='Catálogo';
		$this->dupl[4][1]='Sensual';
		$this->dupl[5][1]='Jurídico';
			
		$this->dupl[0][2]='duplicata_joias';
		$this->dupl[1][2]='duplicata_modas';
		$this->dupl[2][2]='duplicata_oculos';
		$this->dupl[3][2]='duplicata_usebrilhe';
		$this->dupl[4][2]='duplicata_sensual';
		$this->dupl[5][2]='juridico_duplicata';
			
	return(1);
	}
    function lote_arquivo_morto($dt1,$dt2,$lj){
        $tx=''; 
        $dt1=substr($dt1,6,4).substr($dt1,3,2).substr($dt1,0,2);
        $dt2=substr($dt2,6,4).substr($dt2,3,2).substr($dt2,0,2);
        
        if (strlen($dt1)>0) { $tx .= " dp_venc>$dt1 and";}    
        if (strlen($dt2)>0) { $tx .= " dp_venc<$dt2 and";}
        if ($lj=='T') { $tx .= " dp_loja!='ZZZ'"; }else{ $tx .= " dp_loja='$lj'";}
        
        $sql = "select dp_dt_baixa,sum(dp_valor) from duplicata_arq_morto where $tx group by dp_dt_baixa order by dp_dt_baixa";
        
        $rlt = db_query($sql);
        $sx = '<table class="pg_white border padding5 wc80" align="center">';
        $sx .= '<tr><th align="center">Lote</th><th align="right">Total</th></tr>';
        while ($line=db_read($rlt)) {
            $dt=$line['dp_dt_baixa'];
            $dt=substr($dt,6,2)."/".substr($dt,4,2)."/".substr($dt,0,4);
            $sx .= '<tr class="lt1">';
            $sx .= '<td align="center">'.$dt.'</td>';
            $sx .= '<td align="right">R$ '.number_format($line['sum'],2)."</td>";
            $sx .= "</TR>";
        }
        $sx .= "</font></table>";
    return($sx);
    }
    
    function duplicata_arquivo_morto($cons,$lj){
        $tx=''; 
        
        if ($lj=='T') { $tx .= "and dp_loja!='ZZZ'"; }else{ $tx .= "and dp_loja='$lj'";}
        $sql = "select * from duplicata_arq_morto where dp_cliente='$cons' $tx order by dp_venc";
        $rlt = db_query($sql);
        $sx = '<table class="pg_white border padding5 wc80" align="center">';
        $sx .= '<tr><th align="center">Cod cliente<th align="left">Nome<th align="right">Valor<th align="right">Juros<th align="center">Vencimento<th align="center">Lote<th align="center">Loja</tr>';
        
        while ($line=db_read($rlt)) {
            $sx .= '<tr class="lt1">';
            $dt1=$line['dp_venc'];
            $dt2=$line['dp_dt_baixa'];
            $dt1=substr($dt1,6,2)."/".substr($dt1,4,2)."/".substr($dt1,0,4);
            $dt2=substr($dt2,6,2)."/".substr($dt2,4,2)."/".substr($dt2,0,4);
            $vlr=number_format($line['dp_valor'],2);
            $jr=number_format($line['dp_juros'],2);
            $sx .= '<td align="center">'.$line['dp_cliente'].'</td>';
            $sx .= '<td align="left">'.$line['dp_historico'].'</td>';
            $sx .= '<td align="right">R$ '.$vlr."</td>";
            $sx .= '<td align="right">R$ '.$jr."</td>";
            $sx .= '<td align="center">'.$dt1."</td>";
            $sx .= '<td align="center">'.$dt2."</td>";
            $sx .= '<td align="center">'.$line['dp_loja']."</td>";
            $sx .= '</TR>';
        }
        $sx .= "</font></table>";
    return($sx);
    }

    
    
     function lista_lojas_option()
     {
            $this->set_tabelas();
            $op = 'T:Todas as lojas';
            $i=0;
            while ($i<count($this->dupl)) 
            {
                $loja=$this->dupl[$i][1];
                $codigo=$this->dupl[$i][0];
                $op .= '&'.$codigo.':'.$loja;
                $i++;           
            }
            
            $this->op_lojas=$op;
            return($op);
     }
        
		
	function tabelas(){
		global $base_name,$base_host,$base_user;	
		
		$this->set_tabelas();
		require("../db_fghi_210.php");
		$s = '';
		$cod_clie=$this->db_cliente;
		$div = round(100 / ($this->ttdp+1));
		
		for ($r=0;$r < $this->ttdp;$r++)
		{
			$vlr1 = 0;
			$vlr2 = 0;
			$vlr3 = 0;
		
			$sql = "select sum(dp_valor) as total from ".$this->dupl[$r][2];
			$sql .= " where dp_cliente = '".$cod_clie."' and ( dp_status = '@' or dp_status = 'A') and dp_venc >= ".date("Ymd");
			$xrlt = db_query($sql);
			
			if ($xline = db_read($xrlt)){ $vlr1 = $xline['total']; }
	
			$sql = "select sum(dp_valor) as total from ".$this->dupl[$r][2];
			$sql .= " where dp_cliente = '".$cod_clie."' and ( dp_status = '@' or dp_status = 'A') and dp_venc < ".date("Ymd");
			$xrlt = db_query($sql);
		
			if ($xline = db_read($xrlt))	{ $vlr2 = $xline['total']; }
		
			$f1 = '<font class="lt1">'; $f2 = '<font class="lt1">'; $f3 = '<font class="lt1">';
		
			if ($vlr1 > 0) { $f1 = '<font color="GREEN">'; }
			if ($vlr2 > 0) { $f2 = '<font color="RED">'; }
			if ($vlr3 > 0) { $f3 = '<font color="BLUE">'; }
			if ($vlr1==0 and $vlr2==0 and $vlr3==0){$vlr1=0;$vlr2=0;$vlr3=0;$f1='<font color="GRAY">';$f2='<font color="GRAY">';$f3='<font color="GRAY">'; }
			if (strlen($cod_clie)==0){$vlr1=0;$vlr2=0;$vlr3=0;$f1='<font color="GRAY">';$f2='<font color="GRAY">';$f3='<font color="GRAY">'; }
			
			$sx = '<font class="lt0"><fieldset><legend>'.$this->dupl[$r][1].'</legend>';
			$sx .= $f1.'Notas abertas<BR><B>R$ '.number_format($vlr1,2).'<br></B></font>';
		
			$sx .= $f2.'Notas atrasadas<BR><B>R$ '.number_format($vlr2,2).'</B></font>';
	
			
		  	$s .= '<TD width="'.$div.'%">'.$sx.'</TD>';
		
		}
		$tx='<TABLE width="'.$tab_max.'" align="center" border="0"><TR>'.$s.'</TR></TABLE>';
	
		
	return($tx);
	}


    function transf_arquivo_intermediario_morto(){
            $ano = date('Y');    
            $mes = date('m');    
            $dia = date('d');    
            $dt  = date("Ymd", mktime(0, 0, 0, ($mes-6), $dia ,($ano-(4)))); 
            $this->set_tabelas();
            $tot=0;
            echo '<BR>'.date("d/m/Y H:i:s").' ';
            for ($i=0; $i < count($this->dupl); $i++) 
                {
                       $lj=$this->dupl[$i][0];
                       $tb=$this->dupl[$i][2];
                       $sql2 = "select * from $tb where dp_status='A'and dp_valor>0 and dp_venc<=$dt limit 100";
                       $rlt2 = db_query($sql2);
                       echo '<BR>'.$lj.'-'.$tb;
                       
                       while($this->line2 = db_read($rlt2))
                       {
                            $tot++;
                            $id=$this->line2['id_dp'];
                            echo '.';
                            //echo '<BR>Vencimento:'.stodbr($this->line2['dp_venc']);
                            $valida = $this->arquivo_morto_incluir($lj,$id);
                            if($valida==1){
                                $this->arquivo_morto_alterar($tb,$id);
                                $this->msg_restritiva();
                            }
                       }
                }
                $this->txt .= $tot." registros tranferidos da tabela $tb pendetes ";
                return($tot);   
        
           
    }
    
    function arquivo_morto_alterar($tb,$id){
        if(strlen($id)==0){
            return(0);    
        }else{
            $tx="#(Arquivo Morto/R$ ".number_format($this->line2['dp_valor'],2)."/Data-".date('d/m/Y').")#".$this->line2['dp_content'];
            $tx = substr($tx,0,80);
            
                        $sql = "update $tb 
                                set dp_valor=0 , 
                                    dp_status='A' ,
                                    dp_content='$tx' 
                                where id_dp=$id";
                $this->txt2="<br>".$sql;
                //echo '<BR>'.$sql;
                $rlt = db_query($sql);
                  
            return(1); 
        }    
    }

    function msg_restritiva(){
        global $base_name,$base_host,$base_user;    
        require("../db_fghi_206_cadastro.php");
        $cliente = $this->line2['dp_cliente'];
        $dt = $this->line2['dp_venc'];
        $vlr = number_format($this->line2['dp_valor'],2);
        $msg = "****ATENÇÃO**** Entrar em contato com a Cobrança  - restrição devido a duplicata vencida em ".$dt." no valor de R$ ".$vlr." mais juros";
        $sql="INSERT INTO mensagem(
                    msg_cliente, msg_text, msg_data, msg_hora, msg_lido, 
                    msg_data_lido, msg_hora_lido, msg_nivel)
                VALUES ('".$cliente."', '".$msg."', ".date('Ymd').", '".date('H:i')."', 0, 
                    0, '-', 9)";
       
        $rlt=db_query($sql);
        return(1);    
    }
    
    function arquivo_morto_incluir($lj,$id){
        
            $sql = "insert into duplicata_arq_morto(
                         dp_pedido,
                         dp_doc,
                         dp_sync,
                         dp_historico,
                         dp_cliente,
                         dp_valor,
                         dp_logemite,
                         dp_logpaga,
                         dp_status,
                         dp_horapaga,
                         dp_comissao,
                         dp_boleto,
                         dp_venc,
                         dp_data,
                         dp_datapaga,
                         dp_chq,
                         dp_tipo,
                         dp_lote,
                         dp_terminal,
                         dp_juros,
                         dp_juridico,
                         dp_content,
                         dp_carencia,
                         dp_local,
                         dp_nr,
                         dp_cfiscal,
                         dp_doc_mae,
                         dp_cobranca_externa,
                         dp_cobranca_lote,
                         dp_nrop,
                         dp_dt_baixa,
                         dp_log_baixa,
                         dp_loja,
                         dp_old_id
                       )values('".
                         $this->line2['dp_pedido']."','".
                         $this->line2['dp_doc']."','".
                         $this->line2['dp_sync']."','".
                         $this->line2['dp_historico']."','".
                         $this->line2['dp_cliente']."',0".
                         $this->line2['dp_valor'].",'".
                         $this->line2['dp_logemite']."','".
                         $this->line2['dp_logpaga']."','".
                         $this->line2['dp_status']."','".
                         $this->line2['dp_horapaga']."',0".
                         $this->line2['dp_comissao'].",'".
                         $this->line2['dp_boleto']."',0".
                         $this->line2['dp_venc'].",0".
                         $this->line2['dp_data'].",0".
                         $this->line2['dp_datapaga'].",'".
                         $this->line2['dp_chq']."','".
                         $this->line2['dp_tipo']."','".
                         $this->line2['dp_lote']."','".
                         $this->line2['dp_terminal']."',".
                         $this->line2['dp_juros'].",0".
                         $this->line2['dp_juridico'].",'".
                         $this->line2['dp_content']."',0".
                         $this->line2['dp_carencia'].",'".
                         $this->line2['dp_local']."',0".
                         $this->line2['dp_nr'].",'".
                         $this->line2['dp_cfiscal']."','".
                         $this->line2['dp_doc_mae']."','".
                         $this->line2['dp_cobranca_externa']."',0".
                         $this->line2['dp_cobranca_lote'].",'".
                         $this->line2['dp_nrop']."',".
                         date('Ymd').",'".
                         $_SESSION['nw_user']."','".
                         $lj."',".
                         $id."
                         )";
               $rlt = db_query($sql);
               
        return(1);
    }		
}
?>