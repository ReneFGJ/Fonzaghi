
<?php
    /**
     * Metas
     * @author Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v.0.13.42
     * @package Classe
     * @subpackage Metas
    */
    require_once($include.'sisdoc_lojas.php');
    require_once($include.'sisdoc_icon.php');
    require_once($include."sisdoc_colunas.php");
    class metas
        {
            var $tabela = 'metas';
            var $op = array('','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',);
            var $loja;
            var $saldo= array(0,0,0,0,0,0,0,0,0);
            var $saldo_dia= array(0,0,0,0,0,0,0,0,0);
            var $vlr_max= array(0,0,0,0,0,0,0,0,0);
            var $vlr_min= array(0,0,0,0,0,0,0,0,0);
            var $qtda_zerados= array(0,0,0,0,0,0,0,0,0);
            var $qtda_acertos= array(0,0,0,0,0,0,0,0,0);
            var $vlr_medio= array(0,0,0,0,0,0,0,0,0);
            var $qtda_acima_dobro= array(0,0,0,0,0,0,0,0,0);
            var $qtda_abaixo_dobro= array(0,0,0,0,0,0,0,0,0);
            var $qtda_abaixo_media= array(0,0,0,0,0,0,0,0,0);
            var $qtda_abaixo_metade_media= array(0,0,0,0,0,0,0,0,0);
            var $data;
            var $indice;
            var $per_dia= array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $per_dia_meta= array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $meta=0;
            var $dias_uteis;
            var $cont=0;
            var $include_class='../';
            var $meta1=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $meta2=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $meta3=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $meta4=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $media=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $valor=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $acertos=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $tt_cons=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
            var $ret=array('','','','','','','');
			var $ttdias_mes;
                        
            var $indice_mt= array('Meta Geral','M�dia Acerto','Consultoras','Acertos','Retenção Consultoras');
             
            /* var indice  -  untilizada para rodar a posi��o y  
             * 
             * include sisdoc_lojas - biblioteca
             * function setft() - fun��o que carrega indice
             * var setft[x][y]
            ---X----------------------------------------------------------------            ----Y---------------------------------
            0 sigla                     '0 J�ias';                  
            1 nome loja                 '1 Modas';
            3 banco de dados            '2 �culos';
                                        '3 ubCat�logo';
                                        '4 Sensual';
                                        '5 Express Modas';
                                        '6 Express J�ias';
              */        
        function lista_metas_option()
            {
                $op = ' :Selecione meta';
                 $sql="select * from metas 
                       where mt_ativo=1 
                       order by mt_dt_implementacao
                       ";
                 $rlt=db_query($sql);
                 while($line=db_read($rlt))
                 {
                    $op .= '&'.trim($line['mt_codigo']).':'.trim($line['mt_contexto']);
                 }   
                $this->op=$op;
                return($op);
            }
/*******cadastro metas*/
        function cp_metas()
            {
                global $dd;
                $cp = array();
                array_push($cp,array('$H8','id_mt','',false,True));
                array_push($cp,array('$H8','mt_codigo','',false,True));
                array_push($cp,array('$S80','mt_descricao','Descri��o',false,True));
                array_push($cp,array('$S80','mt_contexto','Contexto',false,True));
                array_push($cp,array('$D ','mt_dt_implementacao','Data implementa��o',false,True));
                array_push($cp,array('$O &1:Sim&1:N�o','mt_ativo','Status',false,True));
                return($cp);
            }
        function updatex_metas()
            {
                $dx1 = 'mt_codigo';
                $dx2 = 'mt';
                $dx3 = 4;
                $sql = "update metas set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
                $rlt = db_query($sql);
                return(1);
            }
        function row_metas()
            {
                global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
                $this->tabela = "metas";
                $tabela = "metas";
                $label = "Cadastro de Metas";
                /* P�ginas para Editar */
                $http_edit = 'coordenacao_metas_ed.php'; 
                $offset = 20;
                $order  = "mt_dt_implementacao";
                $cdf = array('id_mt','mt_codigo','mt_descricao','mt_contexto','mt_dt_implementacao','mt_ativo');
                $cdm = array('Cod','Ordem','Descricao','Contexto','Implementacao','Status','Edicao');
                $masc = array('','','','#','#','#','','','','','','','');
                return(True);
            }
            
/*******cadastro metas valores*/
        function cp_metas_geral()
            {
                global $dd,$setop_ft;
                $op=setft_option();
                $cp = array();
                $this->lista_metas_option();
                /*dd0*/array_push($cp,array('$H8','id_mtg','',false,True));
                /*dd1*/array_push($cp,array('$S20','mtg_valor','Valor Meta',false,True));
                /*dd2*/array_push($cp,array('$O '.date("m").':'.date("M").'&'.
                                           (date("m",mktime(0,0,0,1,0,0))).':'.(date("M",mktime(0,0,0,1,0,0))).'&'.
                                           (date("m",mktime(0,0,0,2,0,0))).':'.(date("M",mktime(0,0,0,2,0,0))).'&'.
                                           (date("m",mktime(0,0,0,3,0,0))).':'.(date("M",mktime(0,0,0,3,0,0))).'&'.
                                           (date("m",mktime(0,0,0,4,0,0))).':'.(date("M",mktime(0,0,0,4,0,0))).'&'.
                                           (date("m",mktime(0,0,0,5,0,0))).':'.(date("M",mktime(0,0,0,5,0,0))).'&'.
                                           (date("m",mktime(0,0,0,6,0,0))).':'.(date("M",mktime(0,0,0,6,0,0))).'&'.
                                           (date("m",mktime(0,0,0,7,0,0))).':'.(date("M",mktime(0,0,0,7,0,0))).'&'.
                                           (date("m",mktime(0,0,0,8,0,0))).':'.(date("M",mktime(0,0,0,8,0,0))).'&'.
                                           (date("m",mktime(0,0,0,9,0,0))).':'.(date("M",mktime(0,0,0,9,0,0))).'&'.
                                           (date("m",mktime(0,0,0,10,0,0))).':'.(date("M",mktime(0,0,0,10,0,0))).'&'.
                                           (date("m",mktime(0,0,0,11,0,0))).':'.(date("M",mktime(0,0,0,11,0,0)))
                                           ,'mtg_mes','Mes :',False,True));
                /*dd3*/array_push($cp,array('$O '.date("Y").':'.date("Y").'&'.
                                           (date("Y")+1).':'.(date("Y")+1).'&'.
                                           (date("Y")+2).':'.(date("Y")+2).'&'.
                                           (date("Y")+3).':'.(date("Y")+3)
                                           ,'mtg_ano','Ano :',False,True));
                /*dd4*/array_push($cp,array('$O 1:Ativo&0:Inativo','mtg_status','',false,True));
                /*dd5*/array_push($cp,array('$H8','mtg_log','',false,True));
                /*dd6*/array_push($cp,array('$H8','mtg_data','',false,True));
                
                return($cp);
            }
        function row_metas_geral()
            {
                global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
                $tabela = "metas_geral";
                $label = "Cadastro de Metas Gerais";
                /* P�ginas para Editar */
                $http_edit = 'coordenacao_metas_geral_ed.php'; 
                $offset = 20;
                $order  = "mtg_data";
                $cdf = array(id_mtg,mtg_mes,mtg_ano,mtg_valor,'mtg_log',mtg_data,'mtg_status');
                $cdm = array('Cod'   ,'Mes    ','Ano    ','Valor    ','Log    ','Data    ','Status    ','Edicao');
                $masc = array('','#','#','$R','#','D','#');
                return(True);
            }
            
        function cp_metas_indice()
            {
                global $dd;
                $op=utf8_encode(setft_option());
                $cp = array();
                
                /*dd0*/array_push($cp,array('$H8','id_mti','',False,False));
                /*dd1*/array_push($cp,array('$O '.$op,'mti_loja','Loja :',True,True));
                /*dd2*/array_push($cp,array('$O '.date("m").':'.date("M").'&'.
                                           (date("m",mktime(0,0,0,1,0,0))).':'.(date("M",mktime(0,0,0,1,0,0))).'&'.
                                           (date("m",mktime(0,0,0,2,0,0))).':'.(date("M",mktime(0,0,0,2,0,0))).'&'.
                                           (date("m",mktime(0,0,0,3,0,0))).':'.(date("M",mktime(0,0,0,3,0,0))).'&'.
                                           (date("m",mktime(0,0,0,4,0,0))).':'.(date("M",mktime(0,0,0,4,0,0))).'&'.
                                           (date("m",mktime(0,0,0,5,0,0))).':'.(date("M",mktime(0,0,0,5,0,0))).'&'.
                                           (date("m",mktime(0,0,0,6,0,0))).':'.(date("M",mktime(0,0,0,6,0,0))).'&'.
                                           (date("m",mktime(0,0,0,7,0,0))).':'.(date("M",mktime(0,0,0,7,0,0))).'&'.
                                           (date("m",mktime(0,0,0,8,0,0))).':'.(date("M",mktime(0,0,0,8,0,0))).'&'.
                                           (date("m",mktime(0,0,0,9,0,0))).':'.(date("M",mktime(0,0,0,9,0,0))).'&'.
                                           (date("m",mktime(0,0,0,10,0,0))).':'.(date("M",mktime(0,0,0,10,0,0))).'&'.
                                           (date("m",mktime(0,0,0,11,0,0))).':'.(date("M",mktime(0,0,0,11,0,0)))
                                           ,'mti_mes','Mês :',False,True));
                
                /*dd3*/array_push($cp,array('$O '.date("Y").':'.date("Y").'&'.
                                           (date("Y")-1).':'.(date("Y")-1).'&'.
                                           (date("Y")-2).':'.(date("Y")-2).'&'.
                                           (date("Y")-3).':'.(date("Y")-3).'&'.
                                           (date("Y")-4).':'.(date("Y")-4).'&'.
                                           (date("Y")-5).':'.(date("Y")-5).'&'.
                                           (date("Y")-6).':'.(date("Y")-6).'&'.
                                           (date("Y")-7).':'.(date("Y")-7)
                                           ,'mti_ano','Ano :',False,True));
                /*dd4*/array_push($cp,array('$O 1:Ativo&0:Inativo','mti_status','Status :',False,True));
                /*dd5*/array_push($cp,array('$S6','mti_meta1','Media Fat/Acertos (R$)',True,True));
                /*dd6*/array_push($cp,array('$S5','mti_meta2','Número Acertos (Qtda)',True,True));
                /*dd7*/array_push($cp,array('$S5','mti_meta3','Número Consultoras (Qtda)',True,True));
                /*dd8*/array_push($cp,array('$S3','mti_meta4','Retenção Consultoras (%)',True,True));
                /*dd9*/array_push($cp,array('$S10','mti_meta5','Faturamento (R$)',True,True));
                /*dd10*/array_push($cp,array('$H20','mti_log','',False,True));
                /*dd11*/array_push($cp,array('$H8','mti_data','',False,True));
                
                return($cp);
            }            
            
        function row_metas_indice()
            {
                global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
                $tabela = "metas_indice";
                $label = "Cadastro de Metas";
                /* P�ginas para Editar */
                $http_edit = 'coordenacao_metas_indice_ed.php'; 
                $offset = 20;
                $order  = "id_mti";
                $cdf = array(id_mti,
                             mti_data,
                             mti_loja,
                             mti_meta1,
                             mti_meta2,
                             mti_meta3,
                             mti_meta4,
                             mti_meta5,
                             mti_mes,
                             mti_ano,
                             mti_log,
                             mti_status
                             );
                $cdm = array('Cod',
                            'Data',
                            'Loja',
                            'Media Fat/Acerto(R$)',
                            'Numero Acertos(Qtd)',
                            'Numero Consultoras(Qtd)',
                            'Retencao(%)',
                            'Faturamento',
                            'Mes',
                            'Ano',
                            'Log',
                            'Status',
                            'Edicao'
                            );
                $masc = array('','D','#','$R','#','#','#','$R','#','#');
                return(True);
            }
/********Metas Diario***************/
        function pesquisa_datas()
        {
            global $base_name,$base_server,$base_host,$base_user,$user,$setft;
             $i=0;
             /*Pesquisa ultima data cadastrada*/
             $sql ='select * from metas_diario order by mtd_data desc limit 1';
             $rlt=db_query($sql);
             while($line=db_read($rlt))
             {
                $dt=$line['mtd_data'];   
             }
             /*Pesquisa registros com ultima data cadastrado*/
           //  $sql ='select * from metas_diario where mtd_data='.$dt;
           //  $rlt=db_query($sql);
            // while($line=db_read($rlt))
            // {
            //    $dt=$line['mtd_data'];
             //   if(strlen(round($dt))>0){
             //       $sql ='delete from metas_diario where mtd_data='.$dt;
              //      $rlt = db_query($sql);
              //  }
            // }
			 
             while($i<count($setft[0]))
                {
                    $this->indice=$i;
                    require($this->include_class.'db_fghi_210.php');
                    $sql="select distinct(dp_data) from ".$setft[2][$i]."  
                    where dp_data>=".$dt."  and 
                          dp_cfiscal like '3.1%' and 
                          dp_status <> 'X'  and 
                          dp_lote <> '' 
                          order by dp_data";
                    $rlt=db_query($sql);
                    while($line=db_read($rlt))
                    {
                        $data=$line['dp_data'];
                        $this->registra_metas_diario($data);
                    }
                    $i++;
                }
            return(1);
        }
        function registra_metas_diario($data=0)
        {
           global $setft;
           $i=$this->indice;
            if($data==0)
            {
                $this->ano=date('Y');
                $this->mes=date('m');
                $this->dia=date('d');
                $this->data=date("Ymd", mktime(0, 0, 0, $this->mes, $this->dia, $this->ano));
            }else{
                $this->ano=substr($data,0,4);
                $this->mes=substr($data,4,2);
                $this->dia=substr($data,6,2);
                $this->data=date("Ymd", mktime(0, 0, 0, $this->mes, $this->dia, $this->ano));
            }
            require($this->include_class.'db_fghi_210.php');
            $this->media_mes();
            $this->insert_metas_diario();
            return(1);
        }
        function media_mes()
        {
            global $base_name,$base_server,$base_host,$base_user,$user,$setft;
            $indice = $this->indice;
            $acima_dobro=0;
            $abaixo_dobro=0;
            $abaixo_media=0;
            $abaixo_metade_media=0;
            $zerados=0;
            $saldo_dia=0;
            require($this->include_class.'db_fghi_210.php');
            $sql1 = "select sum(dp_valor) 
                    from ".$setft[2][$indice]."  
                    where dp_data>".$this->ano.$this->mes."00 and
                          dp_data<=".$this->data."  and 
                          dp_boleto = '' 
                          and dp_status <> 'X'  
                          and dp_lote <> '' ";
            $rlt1 = db_query($sql1);
            if ($line1=db_read($rlt1)) 
            {
                $total=$line1['sum'];
                $this->saldo[$indice]=(round($total*100)/100);
            }
            $sql2 = "select min(dp_valor),max(dp_valor), count(dp_valor),sum(dp_valor) 
                    from ".$setft[2][$indice]."  
                    where dp_data=".$this->data." and 
                             dp_boleto = '' and 
                             dp_status <> 'X' and 
                             dp_lote <> '' and  
                             (dp_cfiscal = '3.1.1.1.01-M' or dp_cfiscal = '3.1.1.1.01-J' or dp_cfiscal = '3.1.1.1.01-S' or dp_cfiscal = '3.1.1.1.01-O' or dp_cfiscal = '3.1.1.1.01-U')
                             ";
            $rlt2 = db_query($sql2);
            while ($line2=db_read($rlt2)) 
            {
               $qtd=$line2['count'];
               $soma=$line2['sum'];
               if ($qtd==0) {
               }else{ $media=number_format($soma/$qtd,2,'.','');}
                $this->qtda_acertos[$indice]=$qtd;
                $this->vlr_max[$indice]=(round($line['max']*100)/100);
                $this->vlr_min[$indice]=(round($line['min']*100)/100);
                $this->vlr_medio[$indice]=(round($media*100)/100);
                $this->saldo_dia[$indice]=(round($soma*100)/100);
                $media=troca($media,',','');
            }
            $sql3 = "select * 
                    from ".$setft[2][$indice]." 
                    where dp_data=".$this->data."  and dp_boleto = '' and dp_status <> 'X'  and dp_lote <> '' ";
            $rlt3 = db_query($sql3);
            while($line3=db_read($rlt3)) 
            {
               if($line3['dp_valor']>=($media*2))
               {
                    $acima_dobro++;
               }
               if(($line3['dp_valor']<($media*2))&&($line3['dp_valor']>=($media)))
               {
                    $abaixo_dobro++;
               }
               if(($line3['dp_valor']<$media)&&($line3['dp_valor']>=($media/2)))
               {
                    $abaixo_media++;
               }
               if(($line3['dp_valor']<($media/2))&&($line3['dp_valor']>0))
               {
                    $abaixo_metade_media++;
               }
               if($line3['dp_valor']==0)
               {
                    $zerados++;
               }
            }
           $this->qtda_acima_dobro[$indice]=$acima_dobro;
           $this->qtda_abaixo_dobro[$indice]=$abaixo_dobro;
           $this->qtda_abaixo_media[$indice]=$abaixo_media;
           $this->qtda_abaixo_metade_media[$indice]=$abaixo_metade_media;
           $this->qtda_zerados[$indice]=$zerados;
           return(1);
        }
        function calcula_consultoras()
        {
        	global $base_name,$base_server,$base_host,$base_user,$user;
                    /**
                    * Modas
                    */
                    require($this->include_class."db_fghi_206_modas.php");
                    $sql = "select kh_cliente from kits_consignado where kh_status = 'A'";
                    $rlt = db_query($sql);
                    $sqlx = '';
                    while ($line = db_read($rlt))
                        {
                            if (strlen($sqlx) > 0) { $sqlx .= 'union '.chr(13); }
                            $sqlx .= "select '".$line['kh_cliente']."' as cliente , 1 as a0, 1 as a1, 0 as a2, 0 as a3, 0 as a4, 0 as a5 ".chr(13);
                        }
                    /**
                    * Jóias
                    */
                    require($this->include_class."db_fghi_206_joias.php");
                    $sql = "select kh_cliente from kits_consignado where kh_status = 'A'";
                    $rlt = db_query($sql);
                    while ($line = db_read($rlt))
                        {
                            if (strlen($sqlx) > 0) { $sqlx .= 'union '.chr(13); }
                            $sqlx .= "select '".$line['kh_cliente']."' as cliente , 1 as a0, 0 as a1, 1 as a2, 0 as a3, 0 as a4, 0 as a5 ".chr(13);
                        }
                        
                    /**
                    * Óculos
                    */
                    require($this->include_class."db_fghi_206_oculos.php");
                    $sql = "select kh_cliente from kits_consignado where kh_status = 'A'";
                    $rlt = db_query($sql);
                    while ($line = db_read($rlt))
                        {
                            if (strlen($sqlx) > 0) { $sqlx .= 'union '.chr(13); }
                            $sqlx .= "select '".$line['kh_cliente']."' as cliente , 1 as a0, 0 as a1, 0 as a2, 1 as a3, 0 as a4, 0 as a5 ".chr(13);
                        }
                    
                    /**
                     * UseBrilhe
                    */
                    require($this->include_class."db_fghi_206_ub.php");
                    $sql = "select kh_cliente from kits_consignado where kh_status = 'A'";
                    $rlt = db_query($sql);
                    while ($line = db_read($rlt))
                        {
                            if (strlen($sqlx) > 0) { $sqlx .= 'union '.chr(13); }
                            $sqlx .= "select '".$line['kh_cliente']."' as cliente , 1 as a0, 0 as a1, 0 as a2, 0 as a3, 1 as a4, 0 as a5 ".chr(13);
                        }
                    
                    /**
                    * Sensual
                    */
                    require($this->include_class."db_fghi_206_sensual.php");
                    $sql = "select kh_cliente from kits_consignado where kh_status = 'A'";
                    $rlt = db_query($sql);
                    while ($line = db_read($rlt))
                        {
                            if (strlen($sqlx) > 0) { $sqlx .= 'union '.chr(13); }
                            $sqlx .= "select '".$line['kh_cliente']."' as cliente , 1 as a0, 0 as a1, 0 as a2, 0 as a3, 0 as a4, 1 as a5 ".chr(13);
                        }
                    
                    /** Calculo da densidade **/
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $acertos = $line['total'];
                    
                    /** Gera tabela de dados */
                    $sql1 = "select cliente, max(a0) as a0, max(a1) as a1, max(a2) as a2, max(a3) as a3, max(a4) as a4, max(a5) as a5 from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela group by cliente ";
                    $rlt = db_query($sql1);
                    
                    $sqlx = '';
                    while ($line = db_read($rlt))
                        {
                            if (strlen($sqlx) > 0) { $sqlx .= 'union '.chr(13); }
                            $sqlx .= "select '".$line['cliente']."' as cliente , 
                                ".$line['a0']." as a0, 
                                ".$line['a1']." as a1, 
                                ".$line['a2']." as a2, 
                                ".$line['a3']." as a3, 
                                ".$line['a4']." as a4,
                                ".$line['a5']." as a5 ".chr(13);                
                        }
                    /** A1-A2 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a1=1 and a2 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a1_a2 = $line['total'];
                    /** A1-A3 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a1=1 and a3 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a1_a3 = $line['total'];
                    
                    /** A1-A4 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a1=1 and a4 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a1_a4 = $line['total'];
                    
                    
                    /** A1-A5 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a1=1 and a5 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a1_a5 = $line['total'];
                    
                    
                    /** A2-A3 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a2=1 and a3 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a2_a3 = $line['total'];
                    
                    /** A2-A4 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a2=1 and a4 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a2_a4 = $line['total'];
                    
                    /** A2-A5 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a2=1 and a5 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a2_a5 = $line['total'];
                    
                    /** A3-A4 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a3=1 and a4 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a3_a4 = $line['total'];
                    
                    /** A3-A5 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a3=1 and a5 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a3_a5 = $line['total'];
                    
                    /** A4-A5 */
                    $sql1 = "select count(*) as total from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela where a4=1 and a5 = 1 ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a4_a5 = $line['total'];
                    
                    /** A1,A2,A3,A4,A5 */
                    $sql1 = "select count(*) as total, sum(a1) as a1,
                        sum(a2) as a2, sum(a3) as a3, sum(a4) as a4, sum(a5) as a5 from (";
                    $sql1 .= $sqlx;
                    $sql1 .= ") as tabela ";
                    $rlt = db_query($sql1);
                    $line = db_read($rlt);
                    $a1 = $line['a1'];
                    $a2 = $line['a2'];
                    $a3 = $line['a3'];
                    $a4 = $line['a4'];
                    $a5 = $line['a5'];
                    $revendedoras = $line['total'];
                    
                    /** A1,A2,A3,A4,A5 */
                    $sql1 = "select count(*) as total, tt from (
                        select sum(a1+a2+a3+a4+a5) as tt, cliente from (";
                        $sql1 .= $sqlx;
                        $sql1 .= ") as tabela group by cliente ) as tabela2 group by tt 
                        order by tt
                        ";
                    $rlt = db_query($sql1);
                    $pcs = array(0,0,0,0,0,0);
                    while ($line = db_read($rlt))
                        {
                        $pcs[$line['tt']] = $line['total'];
                        }
                    $cd1m = $pcs[1];
                    $cd2m = $pcs[2];
                    $cd3m = $pcs[3];
                    $cd4m = $pcs[4];
                    $cd5m = $pcs[5];
                    $data = date("Ymd");
                
					require($this->include_class."db_fghi_206_cadastro.php");
	                $sqlx = "select * from cadastro_densidade where cd_data = ".date("Ymd");
	                $rltx = db_query($sqlx);
	                
	                if (!($line = db_read($rltx))) 
	                {
                    $sql = "insert into cadastro_densidade (
                            cd_a1,cd_a2,cd_a3,cd_a4,cd_a5,
                            cd_a1_a2,cd_a1_a3,cd_a1_a4,cd_a1_a5,
                            cd_a2_a3,cd_a2_a4,cd_a2_a5,
                            cd_a3_a4,cd_a3_a5,
                            cd_a4_a5,
                            cd_data,cd_revendedoras,cd_acertos,
                            cd_1m,cd_2m,cd_3m,cd_4m,cd_5m 
                            ) values (
                            $a1,$a2,$a3,$a4,$a5,
                            $a1_a2,$a1_a3,$a1_a4,$a1_a5,
                            $a2_a3,$a2_a4,$a2_a5,
                            $a3_a4,$a3_a5,
                            $a4_a5,
                            $data, $revendedoras, $acertos,
                            $cd1m,$cd2m,$cd3m,$cd4m,$cd5m
                            )";
                    $rlt = db_query($sql);
	                } else {
	                	$id=$line['id_cd'];
	                    $sql = "update cadastro_densidade set 
	                            cd_a1=$a1,
	                            cd_a2=$a2,
	                            cd_a3=$a3,
	                            cd_a4=$a4,
	                            cd_a5=$a5,
	                            cd_a1_a2=$a1_a2,
	                            cd_a1_a3=$a1_a3,
	                            cd_a1_a4=$a1_a4,
	                            cd_a1_a5=$a1_a5,
	                            cd_a2_a3=$a2_a3,
	                            cd_a2_a4=$a2_a4,
	                            cd_a2_a5=$a2_a5,
	                            cd_a3_a4=$a3_a4,
	                            cd_a3_a5=$a3_a5,
	                            cd_a4_a5=$a4_a5,
	                            cd_data=$data,
	                            cd_revendedoras=$revendedoras,
	                            cd_acertos=$acertos,
	                            cd_1m=$cd1m,
	                            cd_2m=$cd2m,
	                            cd_3m=$cd3m,
	                            cd_4m=$cd4m,
	                            cd_5m=$cd5m
	                             where id_cd=$id";
                    $rlt = db_query($sql);
                
                }   
                return(1);
        }

        function total_consultoras($data)
        {
           global $base_name,$base_server,$base_host,$base_user,$user,$setft;
           setft();
           $i=0;
           $tt=0;
                require('../db_cadastro.php');
                $sql ="select cd_a1 as modas,cd_a2 as joias,cd_a3 as oculos,cd_a4 as ub,cd_a5 as sensual,cd_data
                		from cadastro_densidade where cd_data<='".$data."' order by cd_data desc limit 1";
                		 
                $rlt = db_query($sql);
                while($line=db_read($rlt))
                {
                   //joias
                   $this->tt_cons[$setft[0][0]]=$line['joias'];
                   //modas
                   $this->tt_cons[$setft[0][1]]=$line['modas'];
                   //oculos
                   $this->tt_cons[$setft[0][2]]=$line['oculos'];
                   //ub
                   $this->tt_cons[$setft[0][3]]=$line['ub'];
                   //sensual
                   $this->tt_cons[$setft[0][4]]=$line['sensual'];
                }
                return(1);
        }
        function insert_metas_diario()
        {
            global $base_name,$base_server,$base_host,$base_user,$user,$setft;
            require($this->include_class."db_bi.php");
            $i=$this->indice;
           $sql2 = "	select * from metas_diario 
            			where mtd_loja ='".$setft[0][$i]."' and 
            				  mtd_data =".$this->data."
            			";
			$rlt2 = db_query($sql2);
			 if (!($line2 = db_read($rlt2))) 
	                {
                          
	                  $sql = "insert into metas_diario (mtd_codigo,
	                                                      mtd_loja,
	                                                      mtd_saldo,
	                                                      mtd_vlr_max,
	                                                      mtd_vlr_min,
	                                                      mtd_qtda_zerados,
	                                                      mtd_qtda_acertos,
	                                                      mtd_vlr_medio,
	                                                      mtd_qtda_acima_dobro,
	                                                      mtd_qtda_abaixo_dobro,
	                                                      mtd_qtda_abaixo_media,
	                                                      mtd_data,
	                                                      mtd_qtda_abaixo_metade_media,
	                                                      mtd_saldo_dia)
	                            values (
	                                    '0',
	                                    '".$setft[0][$i]."',
	                                    ".$this->saldo[$i].",
	                                    ".$this->vlr_max[$i].",
	                                    ".$this->vlr_min[$i].",
	                                    ".$this->qtda_zerados[$i].",
	                                    ".$this->qtda_acertos[$i].",
	                                    ".$this->vlr_medio[$i].",
	                                    ".$this->qtda_acima_dobro[$i].",
	                                    ".$this->qtda_abaixo_dobro[$i].",
	                                    ".$this->qtda_abaixo_media[$i].",
	                                    ".$this->data.",
	                                    ".$this->qtda_abaixo_metade_media[$i].",
	                                    ".$this->saldo_dia[$i]."
	                            )";
	                    $rlt = db_query($sql);
                    
                    } else {
                    	
	                	$id=$line2['id_mtd'];
	                    $sql = "update metas_diario set 
	                            mtd_codigo='".$line2['mtd_codigo']."',
	                            mtd_loja='".$line2['mtd_loja']."',
	                            mtd_saldo=".$this->saldo[$i].",
	                            mtd_vlr_max=".$this->vlr_max[$i].",
	                            mtd_vlr_min=".$this->vlr_min[$i].",
	                            mtd_qtda_zerados=".$this->qtda_zerados[$i].",
	                            mtd_qtda_acertos=".$this->qtda_acertos[$i].",
	                            mtd_vlr_medio=".$this->vlr_medio[$i].",
	                            mtd_qtda_acima_dobro=".$this->qtda_acima_dobro[$i].",
	                            mtd_qtda_abaixo_dobro=".$this->qtda_abaixo_dobro[$i].",
	                            mtd_qtda_abaixo_media=".$this->qtda_abaixo_media[$i].",
	                            mtd_data=".$this->data.",
	                            mtd_qtda_abaixo_metade_media=".$this->qtda_abaixo_metade_media[$i].",
	                            mtd_saldo_dia=".$this->saldo_dia[$i]."
	                            where id_mtd=$id";
	                	$rlt = db_query($sql);
            		}   
            $sql3 = "update metas_diario set mtd_codigo=trim(to_char(id_mtd,'".strzero(0,7)."')) where (length(trim(mtd_codigo)) < 4) or (mtd_codigo isnull)";
            $rlt3 = db_query($sql3);
            return(1);
        }
        function le_diario($mes,$ano)
        {
            global $base_name,$base_server,$base_host,$base_user,$user,$setft;
            require("../db_bi.php");
            $sql="select * from metas_diario 
                  where mtd_data<".$ano.$mes."99 and 
                        mtd_data>".$ano.$mes."00 
                        order by mtd_loja, mtd_data
                  ";
            $rlt=db_query($sql);
            while($line=db_read($rlt))
            {
            	$lj=$line['mtd_loja'];
                $dia=substr($line['mtd_data'],6,2);  
                $this->per_dia[$dia]=$line['mtd_saldo_dia']+$this->per_dia[$dia];
                $this->saldo[$dia]=$line['mtd_saldo']+$this->saldo[$dia];
                $this->mtd_saldo[trim($lj)][round($dia)]=$line['mtd_saldo'];
            }
            return(1);
        }
        function calcula_desafio($data)
        {
            global $base_name,$base_server,$base_host,$base_user,$user;
            $ano=substr($data,0,4);
            $mes=substr($data,4,2);
            $dia=substr($data,6,2);
            $this->total_consultoras($ano.$mes.$dia);
            $this->calcula_retencao($dia,$mes, $ano);
            require('../db_fghi_210.php');
            $sql = "select round(sum(dp_valor*100))/100 as valor, count(*) as acertos, 'J' as loja
                                            from duplicata_joias 
                                            where (dp_data >= ".$ano.$mes."00 and dp_data <=".$data.")
                                            and dp_cfiscal='3.1.1.1.01-J' 
                                            union
                    select round(sum(dp_valor*100))/100 as valor, count(*) as acertos, 'M' as loja
                                            from duplicata_modas 
                                            where (dp_data >= ".$ano.$mes."00 and dp_data <=".$data.")
                                            and dp_nr>0 and dp_cfiscal='3.1.1.1.01-M' 
                                            union
                    select round(sum(dp_valor*100))/100 as valor, count(*) as acertos, 'O' as loja
                                            from duplicata_oculos 
                                            where (dp_data >= ".$ano.$mes."00 and dp_data <=".$data.")
                                            and dp_nr>0 and dp_cfiscal='3.1.1.1.01-O'
                                            union
                    select round(sum(dp_valor*100))/100 as valor, count(*) as acertos, 'S' as loja
                                            from duplicata_sensual 
                                            where (dp_data >= ".$ano.$mes."00 and dp_data <=".$data.")
                                            and dp_nr>0  and dp_cfiscal='3.1.1.1.01-S'
                                            union
                   select round(sum(dp_valor*100))/100 as valor, count(*) as acertos, 'G' as loja
                                            from duplicata_joias 
                                            where (dp_data >= ".$ano.$mes."00 and dp_data <=".$data.")
                                            and dp_cfiscal='3.1.1.1.01-G' 
                                            union
                    select round(sum(dp_valor*100))/100 as valor, count(*) as acertos, 'E' as loja
                                            from duplicata_modas 
                                            where (dp_data >= ".$ano.$mes."00 and dp_data <=".$data.")
                                            and dp_nr>0 and dp_cfiscal='3.1.1.1.01-E' 
                    ";

            $rlt = db_query($sql);
                while($line=db_read($rlt)){
                    $lj=$line['loja']; 
                    
                    switch ($lj)
                    	{
							case 'E': $this->valor['M']= $this->valor['M'] + $line['valor']; break;
							case 'G': $this->valor['J']= $this->valor['J'] + $line['valor']; break;
							default:
                    				$this->valor[$lj]=$line['valor'];
                    				$this->acertos[$lj]=$line['acertos'];														
							 break;
                    	}
                }
                    
            return(1);
        }
        
        function calcula_retencao($dia,$mes,$ano)
        {
           global $base_name,$base_server,$base_host,$base_user,$user,$setft;
           setft();
           $i=0;
           $this->total_consultoras($ano.$mes.$dia);
           while($i<(count($setft[3])))
           {
                require('../'.$setft[3][$i]);
                $tt=0;
                /*Conta as consultoras sem kit mas com duplicatas em aberto*/
               $sql = "select cons.kh_cliente as fl_cliente, 
                            acerto.kh_log_acerto as jv_log, 
                            cons.kh_status as st, 
                            acerto.kh_pago as vlr_pago, 
                            acerto.kh_acerto as dtacerto, 
                            cons.kh_fornecimento as fornece, 
                            acerto.kh_cliente as cliente, 
                            cons.kh_cliente, * 
                        from kits_consignado as acerto 
                        left join kits_consignado as cons on acerto.kh_cliente = cons.kh_cliente and cons.kh_status = 'A' 
                        inner join clientes on acerto.kh_cliente = cl_cliente 
                        where acerto.kh_acerto >= ".$ano.$mes."00 and 
                        acerto.kh_acerto <= ".$ano.$mes.$dia." order by jv_log 
                        ";
                $tt=0; 
                $ttkit=0;
                $rlt = db_query($sql);
                $sx='';
                $devolucao=0;
                $pendencia=0;
               // echo $sql;
                while($line=db_read($rlt))
                {
                   if((trim($line['jv_log'])!='NANA') and (trim($line['jv_log'])!='CAD2'))
                   {
                       if ($line['st']!='A') 
                       {
                                $devolucao++;
                                if(strlen(trim($sx))>0)
                                {
                                    $sx .= " or ";
                                }
                                $sx .= " dp_cliente='".$line['cliente']."' ";
                       }
                       $ttkit++;
                   }
                   
                }
                $pendencia = $this->verifica_duplicata($sx,$ano.$mes.$dia);
                $this->ret[$setft[0][$i]]=((($ttkit-($devolucao-$pendencia))*100)/$ttkit);
                $i++;
            }
            return(1);
        }

        function verifica_duplicata($sx,$dt)
        {
            global $base_name,$base_server,$base_host,$base_user,$user,$setft;
            require('../db_fghi_210.php');
			$tx=0;
            $sql = "    select count(*) as tota, sum(dp_valor) as valor, dp_cliente from ( 
                        select dp_valor, dp_cliente from duplicata_sensual where dp_venc<=".$dt." and dp_status='A' and (".$sx.") union
                        select dp_valor, dp_cliente from duplicata_joias where dp_venc<=".$dt." and  dp_status='A' and (".$sx.") union
                        select dp_valor, dp_cliente from duplicata_modas where dp_venc<=".$dt." and  dp_status='A' and (".$sx.") union
                        select dp_valor, dp_cliente from duplicata_oculos where dp_venc<=".$dt." and  dp_status='A' and (".$sx.") union
                        select dp_valor, dp_cliente from juridico_duplicata where dp_venc<=".$dt." and  dp_status='A' and (".$sx.")
                        ) as tabela group by dp_cliente
                    ";
            $rlt = db_query($sql);
            while($line=db_read($rlt))
            {
                 if($line['valor'])
                 {
                    $tx++;
                 }
                  
            }
            return($tx);
        }
         
        function le_meta($mes,$ano)
        {
          global $base_name,$base_server,$base_host,$base_user,$user,$setft;
          require($this->include_class."db_bi.php");
            $dia=$this->calcula_dias_uteis($mes, $ano);
            $sql="select * from metas_geral
                  where 
                  mtg_mes =".$mes." and 
                  mtg_ano =".$ano." and 
                  mtg_status = '1'"
            ;
            $rlt=db_query($sql);
            while($line=db_read($rlt))
            {
               $this->meta=$line['mtg_valor'];
            }
            
            $sql="select * from metas_indice
                  where 
                  mti_mes =".$mes." and 
                  mti_ano =".$ano." and 
                  mti_status = '1'"
            ;
            $rlt=db_query($sql);
            
            while($line=db_read($rlt))
            {
               $lj=$line['mti_loja'];
              
               $this->meta1[trim($lj)]=$line['mti_meta1'];
               $this->meta2[trim($lj)]=$line['mti_meta2'];
               $this->meta3[trim($lj)]=$line['mti_meta3'];
               $this->meta4[trim($lj)]=$line['mti_meta4'];
               $this->meta5[trim($lj)]=$line['mti_meta5'];
            }
            
            return(1);
        }
       
        function calcula_dias_uteis($mes,$ano)
        {
            $ttdias=cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
			$this->ttdias_mes=$ttdias;
            for ($i=1; $i <=$ttdias ; $i++) 
            {
               $dia=substr('0'.$i,-2);
               $dt=date("w",mktime(0, 0, 0, $mes, $dia, $ano));
               if($dt!=0 && $dt!=6){$sx++; }
               $this->dias_uteis=$sx;
            }
            return($sx);
        }
		/*//Ir� substituir o metodo calcula_dias_uteis, este j� esta utilizando o calendario do RENE
		function calcula_dias_uteis2($mes,$ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			require($this->include_class."db_bi.php");
			$ttdias=cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
			$this->ttdias_mes=$ttdias;
			
			$sql = "select count(*) from calendario
					where cal_ativo = 1 and
						  cal_data >= '".$ano.$mes."00' and
						  cal_data <= '".$ano.$mes."99' 
					";
			$rlt = db_query($sql);
			if($line = db_read($rlt))
			{
				$this->dias_uteis=$line['count'];
			}		
			echo "<br>--".$sx = $this->dias_uteis;
			return($sx);
		}
		 */
        function calendario_metas($mes,$ano)
        {
            global $setft;
            $diasmes = date('t', mktime(0,0,0,$mes,1,$ano)); 
            $imes    = date('w', mktime(0,0,0,$mes,1,$ano));
            $data    = mktime(0,0,0,$mes,1,$ano);
            $ttdias=$this->calcula_dias_uteis($mes, $ano);
            $ttperdiameta=0;
            $ttperdia=0;
            $ttmeta=0;
            $ttsaldo=0;

            $this->le_meta($mes, $ano, $lj);
            $this->le_diario($mes,$ano,$lj);
            $ttmeta=$this->meta;

            /* Datas anteriores e posteriores */
            $ano_anterior = $ano; $ano_posterior = $ano;
            $mes_anterior = round($mes) - 1; if ($mes_anterior == 0) { $mes_anterior = 12; $ano_anterior--; }
            $mes_posterior = round($mes) + 1; if ($mes_posterior == 13) { $mes_posterior = 1; $ano_posterior++; }  
            $linka = '<A HREF="'.page().'?dd1='.strzero($mes_anterior,2).'&dd2='.$ano_anterior.'&dd3='.$lj.'&acao=busca">';
            $linkp = '<A HREF="'.page().'?dd1='.strzero($mes_posterior,2).'&dd2='.$ano_posterior.'&dd3='.$lj.'&acao=busca">';

            if(round($ttmeta)!=0){
                $xmes = date("m",$data);
                $id = 0;
                if (date("w",$data) > 0) { $sx .= '<TD colspan='.date("w",$data).'">'; }
                $j=1;  
                $grs="['Dia','Realizado','Meta'],";  
                while ($xmes == date("m",$data)) 
                    {
                        /* Se for domingo cria nova linha */
                        if ((date("w",$data)==0) and (round(date("d",$data)) > 1)) { $sx .= '<TR '.coluna().'>'; }
                        /* Se for domingo ou sabado não calcula dados*/
                        if ((date("w",$data)!=0 &&date("w",$data)!=6)) 
                        {
                            $ttperdiameta=((($ttmeta/$ttdias)*100)/$ttmeta)+$ttperdiameta;
                            $ttperdia=(($this->per_dia[date("d",$data)]*100)/$ttmeta)+$ttperdia;    
                            $ttdia=($this->per_dia[date("d",$data)]*100)/$ttmeta;
                            $ttsaldo=$ttperdia-$ttperdiameta;
                        }
                        if ($ttsaldo>=0) { $bg="#1FD916";}
                        if (($ttsaldo<0) and ($ttsaldo>(-0.5))) { $bg="#FFF203";}
                        if ($ttsaldo<(-0.5)) { $bg="#FF0000";}
                        
                         /* Insere informacoes do dia*/
                        $sx .= '<TD class="tabelaCL" width = 10% height = 90px>';
                        $sx .= '<div  align="center" style="background-color: #FFFFFF; float:left;width:20%;height:30%;margin:0px;">'.date("d",$data).'</div>
                                <div align="center" style="background-color: #8C94A1; float:right; width:40%; height:30%; margin:0px;">
                                '.number_format($ttperdia,1).'%
                                </div>
                                <div align="center" style="background-color: #BDC7D6; float:right; width:40%; height:30%; margin:0px;" >
                                '.number_format($ttperdiameta,1).'%
                                </div>
                                <div align="center" style="background-color: #DFE2FF; width:100%; height:70%;">
                                '.number_format($ttdia,1).'%</div>
                                <div align="center" style="background-color: '.$bg.'; width:100%; height:30%;">
                                '.number_format($ttsaldo,1).'%
                                </div>';
                        /* Incrementa um dia */
                        $data += 24*60*60; 
                        $grs.="['".date("d/m/Y",$data)."',".$ttdia.",".((($ttmeta/$ttdias)*100)/$ttmeta)."],
                        ";
                    } 
             }else{$msg= ' - Necessario cadastrar meta para este mês.';}  
            /* Monta calendario */
            $sx1 = "<h3>Calendário  - Metas -  ".$mes."/".$ano.$msg."</h3>";
            $sx1 .= '<table width=800 align="center" border=1>';
            $sx1 .= '<TR>';
            $sx1 .= '<TD class="tabela00">'.$linka.'<img src="../img/icone_arrow_calender_left.png" height="20" border=0></A>';
            $sx1 .= '<TD colspan=2  class="tabela00" >';
            $sx1 .= '<TD colspan=1  class="tabela00" ><a href="coordenacao_metas_grafico.php?dd0='.$grs.'" ><img src="../ico/chart1.png" height="20" border=0 align="center"></a>';
            $sx1 .= '<TD colspan=2  class="tabela00" >';
            $sx1 .= '<TD class="tabela00">'.$linkp.'<img src="../img/icone_arrow_calender_right.png" height="20" border=0 align="right"></A>';
            $sx1 .= '<TR '.coluna().'>';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> DOMINGO';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SEGUNDA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> TERÇA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> QUARTA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> QUINTA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SEXTA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SÁBADO';
            $sx1 .= '<TR '.coluna().'>';
            $sx1 .= '<TR>';
            $sx = $sx1.$sx;
            $sx .= '</table>';  
            $sx .='<table width=800 align="center" border=0 ><TR>
                        <TD align="center" style="background-color: #FFFFFF; width:20%; height:30%">Dia</TD>
                        <TD align="center" style="background-color: #BDC7D6; width:20%; height:30%">Meta proposta saldo</TD>
                        <TD align="center" style="background-color: #8C94A1; width:20%; height:30%">Meta alcançada saldo</TD>
                        <TD align="center" style="background-color: #DFE2FF; width:20%; height:30%">Saldo do dia</TD>
                        <TD align="center" style="background-color: #D4D7F2; width:20%; height:30%">Diferença proposta X alcançada</TD>
                   </TR></table>';
            $sx = '<div>'.$sx.'</div>';
            $sx=utf8_decode($sx);
            return($sx);
        }
	/*Alterar este codigo para utilizar calendario RENE*/
		function calendario_metas2($mes,$ano)
        {
            global $setft;
            $diasmes = date('t', mktime(0,0,0,$mes,1,$ano)); 
            $imes    = date('w', mktime(0,0,0,$mes,1,$ano));
            $data    = mktime(0,0,0,$mes,1,$ano);
            $ttdias=$this->calcula_dias_uteis($mes, $ano);
            $ttperdiameta=0;
            $ttperdia=0;
            $ttmeta=0;
            $ttsaldo=0;

            $this->le_meta($mes, $ano, $lj);
            $this->le_diario($mes,$ano,$lj);
            $ttmeta=$this->meta;

            /* Datas anteriores e posteriores */
            $ano_anterior = $ano; $ano_posterior = $ano;
            $mes_anterior = round($mes) - 1; if ($mes_anterior == 0) { $mes_anterior = 12; $ano_anterior--; }
            $mes_posterior = round($mes) + 1; if ($mes_posterior == 13) { $mes_posterior = 1; $ano_posterior++; }  
            $linka = '<A HREF="'.page().'?dd1='.strzero($mes_anterior,2).'&dd2='.$ano_anterior.'&dd3='.$lj.'&acao=busca">';
            $linkp = '<A HREF="'.page().'?dd1='.strzero($mes_posterior,2).'&dd2='.$ano_posterior.'&dd3='.$lj.'&acao=busca">';

            if(round($ttmeta)!=0){
                $xmes = date("m",$data);
                $id = 0;
                if (date("w",$data) > 0) { $sx .= '<TD colspan='.date("w",$data).'">'; }
                $j=1;  
                $grs="['Dia','Realizado','Meta'],";  
                while ($xmes == date("m",$data)) 
                    {
                        /* Se for domingo cria nova linha */
                        if ((date("w",$data)==0) and (round(date("d",$data)) > 1)) { $sx .= '<TR '.coluna().'>'; }
                        /* Se for domingo ou sabado não calcula dados*/
                        if ((date("w",$data)!=0 &&date("w",$data)!=6)) 
                        {
                            $ttperdiameta=((($ttmeta/$ttdias)*100)/$ttmeta)+$ttperdiameta;
                            $ttperdia=(($this->per_dia[date("d",$data)]*100)/$ttmeta)+$ttperdia;    
                            $ttdia=($this->per_dia[date("d",$data)]*100)/$ttmeta;
                            $ttsaldo=$ttperdia-$ttperdiameta;
                        }
                        if ($ttsaldo>=0) { $bg="#1FD916";}
                        if (($ttsaldo<0) and ($ttsaldo>(-0.5))) { $bg="#FFF203";}
                        if ($ttsaldo<(-0.5)) { $bg="#FF0000";}
                        
                         /* Insere informacoes do dia*/
                        $sx .= '<TD class="tabelaCL" width = 10% height = 90px>';
                        $sx .= '<div  align="center" style="background-color: #FFFFFF; float:left;width:20%;height:30%;margin:0px;">'.date("d",$data).'</div>
                                <div align="center" style="background-color: #8C94A1; float:right; width:40%; height:30%; margin:0px;">
                                '.number_format($ttperdia,1).'%
                                </div>
                                <div align="center" style="background-color: #BDC7D6; float:right; width:40%; height:30%; margin:0px;" >
                                '.number_format($ttperdiameta,1).'%
                                </div>
                                <div align="center" style="background-color: #DFE2FF; width:100%; height:70%;">
                                '.number_format($ttdia,1).'%</div>
                                <div align="center" style="background-color: '.$bg.'; width:100%; height:30%;">
                                '.number_format($ttsaldo,1).'%
                                </div>';
                        /* Incrementa um dia */
                        $data += 24*60*60; 
                        $grs.="['".date("d/m/Y",$data)."',".$ttdia.",".((($ttmeta/$ttdias)*100)/$ttmeta)."],
                        ";
                    } 
             }else{$msg= ' - Necessario cadastrar meta para este mês.';}  
            /* Monta calendario */
            $sx1 = "<h3>Calendário  - Metas -  ".$mes."/".$ano.$msg."</h3>";
            $sx1 .= '<table width=800 align="center" border=1>';
            $sx1 .= '<TR>';
            $sx1 .= '<TD class="tabela00">'.$linka.'<img src="../img/icone_arrow_calender_left.png" height="20" border=0></A>';
            $sx1 .= '<TD colspan=2  class="tabela00" >';
            $sx1 .= '<TD colspan=1  class="tabela00" ><a href="coordenacao_metas_grafico.php?dd0='.$grs.'" ><img src="../ico/chart1.png" height="20" border=0 align="center"></a>';
            $sx1 .= '<TD colspan=2  class="tabela00" >';
            $sx1 .= '<TD class="tabela00">'.$linkp.'<img src="../img/icone_arrow_calender_right.png" height="20" border=0 align="right"></A>';
            $sx1 .= '<TR '.coluna().'>';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> DOMINGO';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SEGUNDA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> TERÇA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> QUARTA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> QUINTA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SEXTA';
            $sx1 .= '<TH class="tabelaHCL" align= "center" width = 10% height = 50px> SÁBADO';
            $sx1 .= '<TR '.coluna().'>';
            $sx1 .= '<TR>';
            $sx = $sx1.$sx;
            $sx .= '</table>';  
            $sx .='<table width=800 align="center" border=0 ><TR>
                        <TD align="center" style="background-color: #FFFFFF; width:20%; height:30%">Dia</TD>
                        <TD align="center" style="background-color: #BDC7D6; width:20%; height:30%">Meta proposta saldo</TD>
                        <TD align="center" style="background-color: #8C94A1; width:20%; height:30%">Meta alcançada saldo</TD>
                        <TD align="center" style="background-color: #DFE2FF; width:20%; height:30%">Saldo do dia</TD>
                        <TD align="center" style="background-color: #D4D7F2; width:20%; height:30%">Diferença proposta X alcançada</TD>
                   </TR></table>';
            $sx = '<div>'.$sx.'</div>';
            $sx=utf8_decode($sx);
            return($sx);
        }
		
        function desafios_setor($data)
        {
            $i=0;
            $ano=substr($data,6,4);
            $mes=substr($data,3,2);
            $dia=substr($data,0,2);
            $this->le_diario($mes, $ano);
            $this->le_meta($mes, $ano);
            $this->calcula_desafio($ano.$mes.$dia);
            //campos de media
            /* Calcula Joias */
            $med1A=number_format($this->meta1['J'],2);
			if ($this->acertos['J'] > 0)
				{ $med = $this->valor['J'] / $this->acertos['J']; }
				else { $med = 0; }
            $med1B=number_format($med,2);
            
			/* Calcula Modas */
            $med2A=number_format($this->meta1['M'],2);
			if ($this->acertos['M'] > 0)
				{ $med = $this->valor['M'] / $this->acertos['M']; }
				else { $med = 0; }
            $med2B=number_format($med,2);
            
			/* Calcula Oculos */
            $med3A=number_format($this->meta1['O'],2);
			if ($this->acertos['O'] > 0)
				{ $med = $this->valor['O'] / $this->acertos['O']; }
				else { $med = 0; }
            $med3B=number_format($med,2);
            
			/* Calcula Sensual */
            $med4A=number_format($this->meta1['S'],2);
			if ($this->acertos['S'] > 0)
				{ $med = $this->valor['S'] / $this->acertos['S']; }
				else { $med = 0; }
            $med4B=number_format($med,2);
            
            //campos de consultoras
            $con1A=round($this->meta3['J']);
            $con1B=round($this->tt_cons['J']);
            
            $con2A=round($this->meta3['M']);
            $con2B=round($this->tt_cons['M']);
            
            $con3A=round($this->meta3['O']);
            $con3B=round($this->tt_cons['O']);
            
            $con4A=round($this->meta3['S']);
            $con4B=round($this->tt_cons['S']);
            
            //campos de retenção
            $ret1A=number_format($this->meta4['J'],2);
            $ret1B=number_format($this->ret['J'],2);
            
            $ret2A=number_format($this->meta4['M'],2);
            $ret2B=number_format($this->ret['M'],2);
            
            $ret3A=number_format($this->meta4['O'],2);
            $ret3B=number_format($this->ret['O'],2);
            
            $ret4A=number_format($this->meta4['S'],2);
            $ret4B=number_format($this->ret['S'],2);
            
            //UB
            
            $ub1A=round($this->meta5['C']);
            $ub1B=round($this->mtd_saldo['C'][round($dia)]);
		           
            $bg1=$this->bg_color($med1A, $med1B);
            $bg2=$this->bg_color($med2A, $med2B);
            $bg3=$this->bg_color($med3A, $med3B);
            $bg4=$this->bg_color($med4A, $med4B);
            
            $bg5=$this->bg_color($con1A, $con1B);
            $bg6=$this->bg_color($con2A, $con2B);
            $bg7=$this->bg_color($con3A, $con3B);
            $bg8=$this->bg_color($con4A, $con4B);

            $bg9=$this->bg_color($ret1A, $ret1B);
            $bg10=$this->bg_color($ret2A, $ret2B);
            $bg11=$this->bg_color($ret3A, $ret3B);
            $bg12=$this->bg_color($ret4A, $ret4B);
            
            $bg13=$this->bg_color($ub1A, $ub1B);
			
			$ub1C=0;
			if(($ub1A!=0)and($ub1B!=0))
			{
				$ub1C=(($ub1B+100)/$ub1A)*100;
				$ub1C=number_format($ub1C,2);
			}else{
				$ub1C="Cadastrar";
			}
			$grs=$this->dados_grafico($ano.$mes.$dia);
			$titulo="Desafio metas - ".$mes."/".$ano;
            $sx .='<center><table>
            		<tr>
            		<td colspan="6"><a href="coordenacao_metas_grafico2.php?dd0='.$grs.'&dd1='.$titulo.'" ><img src="../ico/chart1.png" height="20" border=0 align="center"></a></td>
            		</tr>
                    <tr>
                    <th class="tabelaHCL" align= "center" width = 100px height = 50px></th>
                    <th class="tabelaHCL" align= "center" width = 50px height = 50px></th>
                    <th class="tabelaHCL" align= "center" width = 100px height = 50px>Jóias</th>
                    <th class="tabelaHCL" align= "center" width = 100px height = 50px>Modas</th>
                    <th class="tabelaHCL" align= "center" width = 100px height = 50px>Óculos</th>
                    <th class="tabelaHCL" align= "center" width = 100px height = 50px>Sensual</th>
                    <th width = 10px height = 50px></th>
                    <th class="tabelaHCL" align= "center" width = 50px height = 50px></th>
                    <th class="tabelaHCL" align= "center" width = 100px height = 50px>UB</th>
                    </tr>
                    <tr>
                    <td align="center" style="background-color: #BDC7D6; height:30%">MEDIA FAT/ACERTO</td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: #BDCFFF;">Meta</div>
                    <div style="background-color: #FFFFFF;">Realizado</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg1.';">'.$med1A.'</div>
                    <div style="background-color: '.$bg1.';">'.$med1B.'</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg2.';">'.$med2A.'</div>
                    <div style="background-color: '.$bg2.';">'.$med2B.'</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg3.';">'.$med3A.'</div>
                    <div style="background-color: '.$bg3.';">'.$med3B.'</div>
                    </td>
                    
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg4.';">'.$med4A.'</div>
                    <div style="background-color: '.$bg4.';">'.$med4B.'</div>
                    </td>
                    <td height:30%"></td>
                    <td align="center" style="background-color: #BDC7D6; height:30%"></td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg13.';">100%</div>
                    <div style="background-color: '.$bg13.';">'.$ub1C.'%</div>
                    </td>
                    </tr>
                    <tr>
                    <td align="center" style="background-color: #BDC7D6; height:30%">N. CONSULTORAS.</td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: #BDCFFF; height:30%">Meta</div>
                    <div style="background-color: #FFFFFF; height:30%">Realizado</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg5.';">'.$con1A.'</div>
                    <div style="background-color: '.$bg5.';">'.$con1B.'</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg6.';">'.$con2A.'</div>
                    <div style="background-color: '.$bg6.';">'.$con2B.'</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg7.';">'.$con3A.'</div>
                    <div style="background-color: '.$bg7.';">'.$con3B.'</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg8.';">'.$con4A.'</div>
                    <div style="background-color: '.$bg8.';">'.$con4B.'</div>
                    </td>
                    </tr>
                    <tr>
                    <td align="center" style="background-color: #BDC7D6; height:30%">% RETENCAO</td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: #BDCFFF; height:30%">Meta</div>
                    <div style="background-color: #FFFFFF; height:30%">Realizado</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg9.';">'.$ret1A.'%</div>
                    <div style="background-color: '.$bg9.';">'.$ret1B.'%</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg10.';">'.$ret2A.'%</div>
                    <div style="background-color: '.$bg10.';">'.$ret2B.'%</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg11.';">'.$ret3A.'%</div>
                    <div style="background-color: '.$bg11.';">'.$ret3B.'%</div>
                    </td>
                    <td align="center" style="background-color: #BDC7D6; height:30%">
                    <div style="background-color: '.$bg12.';">'.$ret4A.'%</div>
                    <div style="background-color: '.$bg12.';">'.$ret4B.'%</div>
                    </td>
                    </tr>
                  </table>';
            
            return($sx);
        }
        function bg_color($nA,$nB)
        {
            if(($nA!=0)and($nB!=0))
            {	
            $n = (round($nB) / round($nA) * 100);
			}
            if ($n>=100) {$bg = "#30E800";}
            
            if (($n>=90)&&($n<100)) {$bg = "#FFFDA2";}
            
            if ($n<90) {$bg = "#ED5951";}
			
            return($bg);
        }

        function metas_atualizada($lj='')
        {
           global $base_name,$base_server,$base_host,$base_user,$user,$setft;
           if(strlen(trim($lj))==0)
           {
                $this->ano=date('Y');
                $this->mes=date('m');
                $this->dia=date('d');
                $this->data=date("Ymd");
                $i=0;
                while($i<count($setft[0]))
                {
                        $metadiaria=0;
                        $percentual=0;
                        $ttacertos=0;
                        $this->indice=$i;
                        $lj=$i;
                        require('../db_fghi_210.php');
                        
                        $sql="select * from ".$setft[2][$indice]."  
                              where dp_data=".$this->data."  and 
                                    dp_boleto = '' and 
                                    dp_status <> 'X'  
                                    and dp_lote <> ''
                             order by dp_data ";
                        $rlt=db_query($sql);
                        while($line=db_read($rlt))
                        {
                            $ttacertos=$line['dp_valor']+$ttacertos;
                        }
                        $this->media_mes();    
                        $this->le_meta($this->mes, $this->ano, $i);
                        $this->calcula_dias_uteis($this->mes, $this->ano);
                        $metadiaria=$this->meta/$this->dias_uteis;
                        $percentual = $ttacertos*100/$metadiaria;
                        $titulo[0]=$setft[1][$i];
                        $vlr[0]=number_format($percentual,1);
                        $sx .= '<div id="shortkey'.$lj.'">';
                        $sx.=$this->indicador_vendas($titulo,$vlr);
                        $sx .= '</div>'.chr(13);
                        $mes=date(m);
                        $ano=date(Y);
                        $sx .=$this->calendario_metas_indicador($mes, $ano, $lj,'none');
                        $sx .= '<script>
                                $("#shortkey'.$lj.'").click(function(){
                                    var $posicao = document.getElementById("onkey'.$lj.'");
                                    var posx = $posicao.style.absolute;
                                     $("#mask").fadeIn({ left:"0px",marge:"0px"});
                                     $("#onkey'.$lj.'").fadeIn({left:"0px",marge:"0px"});
                                });
                                $("#sair'.$lj.'").click(function(){
                                    var $posicao = document.getElementById("onkey'.$lj.'");
                                    var posx = $posicao.style.absolute;
                                     $("#onkey'.$lj.'").fadeOut({left:"0px",marge:"0px"});
                                     $("#mask").fadeOut({left:"0px",marge:"0px"});
                                });
                                </script>';
                        $i++;
                }
           }
           return($sx);
        }
		function dados_grafico($data)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			require($this->include_class."db_bi.php");
			$sql = "select mtd_data, sum(mtd_saldo) saldo from metas_diario 
					where mtd_data > ".substr($data,0,6)."00 and mtd_data < ".substr($data,0,6)."99
					group by mtd_data
					order by mtd_data";
					$rlt=db_query($sql);
					$grs="['Dia','Realizado','Meta'],".chr(13).chr(10);
					while($line=db_read($rlt))
					{
						
						$dia=round(substr($line['mtd_data'],6,2));
						$valor[$dia]=$line['saldo'];
					}
					$this->calcula_dias_uteis(substr($data,4,2), substr($data,0,4));
					$last_vlr=0;
					$this->meta;
					while($i<=$this->ttdias_mes)
					{
						if(strlen($valor[$i])==0)
						{
							$valor[$i]=$last_vlr;
						}else{
							$last_vlr=$valor[$i];
						}
						$grs.="['".$i."',".(($valor[$i]/$this->meta)*100).",".(((($this->meta*$i)/$this->ttdias_mes)/$this->meta)*100)."],";
						$i++;
					}
					 
					
					

			return($grs);
		}

        function grafico_dinamico($grs,$modelo,$titulo)
        {       
            $sx =   '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                    google.load(\'visualization\', \'1\', {packages: [\'charteditor\']});
                    </script>
                    <script type="text/javascript">
                    var wrapper;
            
                    function init() {
                    wrapper = new google.visualization.ChartWrapper({
                    chartType: \''.$modelo.'\',
                    dataTable: ['.$grs.'],
                    options: {\'title\': \''.$titulo.'\'},
                    containerId: \'vis_div\'
                    });
                    wrapper.draw();
                    }
    
                    function openEditor() {
                    // Handler for the "Open Editor" button.
                    var editor = new google.visualization.ChartEditor();
                    google.visualization.events.addListener(editor, \'ok\',
                    function() {
                    wrapper = editor.getChartWrapper();
                    wrapper.draw(document.getElementById(\'visualization\'));
                    });
                    editor.openDialog(wrapper);
                    }
    
    
                    google.setOnLoadCallback(init);
    
                    </script>
                    </head>
                    <body onload="openEditor()">
                    <center><div id=\'visualization\' style="width:95%;height:400px"></center>
                    </body> ';
            return ($sx);
            
        }

}