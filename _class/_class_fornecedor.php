<?php
    /**
     * Fornecedor
     * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> Willian Fellipe Laynes <willianlaynes@gmail.com>
     * @copyright Copyright (c) 2013 - sisDOC.com.br
     * @access public
     * @version v.0.14.08
     * @package Fornecedor
     * @subpackage classe
    */
    


class fornecedor
    {
          var $loja;
          var $tabela = 'fornecedores';
          var $line;
          var $parametros='';
		  var $sty='tabela01';
         function le($id)
            {
                $sql = "select * from ".$this->tabela."
                        where fo_codigo = '$id'
                        limit 1";
                $rlt = db_query($sql);
                $line = db_read($rlt);
                $this->line = $line;
                $this->razaosocial = trim($line['fo_razaosocial']);
                $this->fantasia = trim($line['fo_nomefantasia']);
                $this->cnpj = trim($line['fo_cgc']);
                $this->razaosocial = trim($line['fo_razaosocial']);
                
                $this->fornecedor_nome = trim($line['fo_nomefantasia']);
                $this->fornecedor_razao = trim($line['fo_razaosocial']);
                
                $this->fo_cep = trim($line['fo_cep']);
                $this->fo_endereco = trim($line['fo_endereco']);
                $this->fo_complemento = trim($line['fo_complemento']);
                $this->fo_estado = trim($line['fo_estado']);
                $this->fo_cgc = trim($line['fo_cgc']);
                $this->fo_ie = trim($line['fo_ie']);
                $this->fo_fone = trim($line['fo_fone']);
                $this->fo_fax = trim($line['fo_fax']);
                $this->fo_celular = trim($line['fo_celular']);
                $this->fo_fone_ramal = trim($line['fo_fone_ramal']);
                $this->fo_contato = trim($line['fo_contato']);
                $this->fo_representante = trim($line['fo_representante']);
                $this->fo_cidade = trim($line['fo_cidade']);
                $this->fo_bairro = trim($line['fo_bairro']);
                $this->fo_tipopessoa = trim($line['fo_tipopessoa']);
                $this->fo_codigo = trim($line['fo_codigo']);
                $this->fo_codigo = trim($line['fo_codfor']);
                    
                
                
                return(true);
            }

            function le2($id)
            {
                $sql = "select * from ".$this->tabela."
                        where id_fo = '$id'
                        limit 1";
                $rlt = db_query($sql);
                $line = db_read($rlt);
                $this->line = $line;
                $this->razaosocial = trim($line['fo_razaosocial']);
                $this->fantasia = trim($line['fo_nomefantasia']);
                $this->cnpj = trim($line['fo_cgc']);
                $this->razaosocial = trim($line['fo_razaosocial']);
                
                $this->fornecedor_nome = trim($line['fo_nomefantasia']);
                $this->fornecedor_razao = trim($line['fo_razaosocial']);
                
                $this->fo_cep = trim($line['fo_cep']);
                $this->fo_endereco = trim($line['fo_endereco']);
                $this->fo_complemento = trim($line['fo_complemento']);
                $this->fo_estado = trim($line['fo_estado']);
                $this->fo_cgc = trim($line['fo_cgc']);
                $this->fo_ie = trim($line['fo_ie']);
                $this->fo_fone = trim($line['fo_fone']);
                $this->fo_fax = trim($line['fo_fax']);
                $this->fo_celular = trim($line['fo_celular']);
                $this->fo_fone_ramal = trim($line['fo_fone_ramal']);
                $this->fo_contato = trim($line['fo_contato']);
                $this->fo_representante = trim($line['fo_representante']);
                $this->fo_cidade = trim($line['fo_cidade']);
                $this->fo_bairro = trim($line['fo_bairro']);
                $this->fo_tipopessoa = trim($line['fo_tipopessoa']);
                $this->fo_codigo = trim($line['fo_codigo']);
                $this->fo_codigo = trim($line['fo_codfor']);
                    
                
                
                return(true);
            }
         
            function le3($id)
            {
                $sql = "select * from ".$this->tabela."
                        where fo_codfor = '$id'
                        limit 1";
                $rlt = db_query($sql);
                $line = db_read($rlt);
                $this->line = $line;
                $this->razaosocial = trim($line['fo_razaosocial']);
                $this->fantasia = trim($line['fo_nomefantasia']);
                $this->cnpj = trim($line['fo_cgc']);
                $this->razaosocial = trim($line['fo_razaosocial']);
                
                $this->fornecedor_nome = trim($line['fo_nomefantasia']);
                $this->fornecedor_razao = trim($line['fo_razaosocial']);
                
                $this->fo_cep = trim($line['fo_cep']);
                $this->fo_endereco = trim($line['fo_endereco']);
                $this->fo_complemento = trim($line['fo_complemento']);
                $this->fo_estado = trim($line['fo_estado']);
                $this->fo_cgc = trim($line['fo_cgc']);
                $this->fo_ie = trim($line['fo_ie']);
                $this->fo_fone = trim($line['fo_fone']);
                $this->fo_fax = trim($line['fo_fax']);
                $this->fo_celular = trim($line['fo_celular']);
                $this->fo_fone_ramal = trim($line['fo_fone_ramal']);
                $this->fo_contato = trim($line['fo_contato']);
                $this->fo_representante = trim($line['fo_representante']);
                $this->fo_cidade = trim($line['fo_cidade']);
                $this->fo_bairro = trim($line['fo_bairro']);
                $this->fo_tipopessoa = trim($line['fo_tipopessoa']);
                $this->fo_codigo = trim($line['fo_codigo']);
                $this->fo_codigo = trim($line['fo_codfor']);
                    
                
                
                return(true);
            }
            
         /*Carrega boxlist*/    
        function lista_fornecedor_option(){
     
            $sql = 'select * from fornecedores order by fo_nomefantasia';
            $rlt = db_query($sql);
            $op = ' :Selecione o pedido';
         
            while ($line = db_read($rlt)) 
            {
                $pedido=trim($line['ped_nrped']);
                $op .= '&'.$pedido.':'.$pedido;
            }
            
            $this->op_pedidos=$op;
            return($op);
     
        }
        
         function lista_lojas_option(){
     
            $this->set_tabelas();
            $op = ' 7:Selecione a loja';
            $lj=$this->loja;         
            $i=0;
            while ($i<=count($lj)-1) 
            {
               
                $op .= '&'.$lj[$i][0].':'.$lj[$i][1];
                $i++;
            }
            return($op);
        }
        
        function set_tabelas(){
                
        $this->loja[0][0]=0;
        $this->loja[1][0]=1;
        $this->loja[2][0]=2;
        $this->loja[3][0]=3;
        $this->loja[4][0]=4;
        $this->loja[5][0]=5;
        $this->loja[6][0]=6;
        $this->loja[7][0]=7;
    
        $this->loja[0][1]='Express Jóias';
        $this->loja[1][1]='Express Modas';
        $this->loja[2][1]='Jóias';
        $this->loja[3][1]='Modas';
        $this->loja[4][1]='Óculos';
        $this->loja[5][1]='Sensual';
        $this->loja[6][1]='Catálogo';
        $this->loja[7][1]='Todas';
            
        $this->loja[0][2]='../db_fghi_206_express_joias.php';
        $this->loja[1][2]='../db_fghi_206_express.php';
        $this->loja[2][2]='../db_fghi_206_joias.php';
        $this->loja[3][2]='../db_fghi_206_modas.php';
        $this->loja[4][2]='../db_fghi_206_oculos.php';
        $this->loja[5][2]='../db_fghi_206_sensual.php';
        $this->loja[6][2]='../db_fghi_206_ub.php';
                
        return(1);
    }
        
        function form_busca()
        {
            global $dd;
            $bt1 = 'localizar >>';
            $msg = 'Informe parte do nome do fornecedor ou seu código';
            $sx .= '
            <table align="center"><TR><TD>
            <form method="get" action="'.page().'">
            <div id="search">
                '.$msg.'
                <input type="text" name="dd1" id="form_search" value="'.$dd[1].'">
                <BR>
                <input type="submit" name="dd50" id="form_button" value="'.$bt1.'">
                <BR>
                <input type="hidden" name="dd80" id="form_button" value="'.$dd[80].'">
            </div>
            </form>
            </td></tr></table>
            ';
            return($sx);
        }   
        
        /* Mostra dados do fornecedor */
        function mostra()
           {
            $line = $this->line; 
			$sty = $this->sty;   
            $sx = '<center>';
            $sx .= '<table width="90%" class='.$sty.' align="center"><tr><td>';
            $sx .= '<TABLE width="90%" cellpadding=1 cellspacing=0  class=lt1  align="center" >';
            $sx .= '                <TR>
                                        <TD class="tabela00"  colspan=3>';
            $sx .= '                <TR class="tabela00 lt0" >';
            $sx .= '                    <TD class="tabela00" colspan="3">Razão social/Nome fantasia</TD>';
            $sx .= '                <TR class="tabela00 lt0">
                                        <TD colspan=3>&nbsp;<b>'.trim($line['fo_nomefantasia']);
            
                                    if (strlen(trim($line['fo_razaosocial'])) > 0)
                                        { $sx .= ' / '.trim($line['fo_razaosocial']); }
            $sx .= '</b>';
                    
            /////// Endereço
            $sx .= '                <TR class="tabela00 lt0">
                                        <TD COLSPAN=2 width="60%">Endereço';
            $sx .= '                    <TD class="tabela00" COLSPAN=1>Representante';
            $sx .= '                <TR class="tabela00 lt0">
                                        <TD width="60%">&nbsp;<b>'.$line['fo_endereco'];
                                        if (strlen($line['fo_complemento']) > 0) { $sx .= '&nbsp;'.$line['fo_complemento']; }
                                        $sx .= '</b>';
            $sx .= '                    <TD>&nbsp;';
            $sx .= '                    <TD class="tabela00" colspan=1>&nbsp;<b>'.$line['fo_representante'].'</b>';
            $sx .= '</TABLE>';
            
            //////////////// parte II
            $sx .= '<TABLE width="90%" cellpadding=1 cellspacing=0  class=lt1  align="center" >';
            $sx .= '    <TR class="tabela00 lt0">';
            $sx .= '        <TD class="tabela00" class="tabela00" >Bairro<TD>';
            $sx .= '        <TD class="tabela00" >Cidade-UF-CEP<TD>';
            $sx .= '        <TD class="tabela00" >Fone/Fax/Celular';
            $sx .= '    <TR class="tabela00 lt0">
                            <TD width=15%>&nbsp;<b><NOBR>'.$line['fo_bairro'].'</b>'; // Bairro
            $sx .= '        <TD class="tabela00" >&nbsp;';
            $sx .= '        <TD class="tabela00" >&nbsp;<b>'.$line['fo_cidade']; // Cidade
                            if (strlen($line['fo_estado']) > 0) { $sx .= '&nbsp;/&nbsp;'; }
            $sx .= $line['fo_estado']; // Bairro
                            if (strlen($line['fo_cep']) > 0) { $sx .= '&nbsp;-&nbsp;'; }
            $sx .= $line['fo_cep']; // Bairro
            $sx .= '';
            $sx .= '</b>';
            $sx .= '        <TD  class="tabela00" width=5>';
            $sx .= '        <TD  class="tabela00" align=center>&nbsp;<b>';
            $sx .= $line['fo_fone'];
            if (strlen($line['fo_fax']) > 0) { $sx .= '&nbsp;fax: '.$line['fo_fax']; }
            $sx .= '</b>';
            $sx .= '</TABLE>';
           
            //////////////// parte III
            $sx .= '<TABLE width="90%" cellpadding=1 cellspacing=0  class=lt1  align="center" >';
            $sx .= '    <TR class="tabela00 lt0">
                            <TD  class="tabela00" COLSPAN=2>CNPJ/CPF</TD>
                            <TD COLSPAN=2>Ie / RG</TD>
                            <TD COLSPAN=1>Contato</TD>
                        </TR>';
            $sx .= '    <TR class="tabela00 lt0">
                            <TD>&nbsp;<b>';
            $sx .= $line['fo_cgc'];
            $sx .= '</b></TD><TD width=5></TD>';
            $sx .= '<TD>&nbsp;<b>';
            $sx .= $line['fo_ie'];
            $sx .= '</b></TD><TD width=5></TD>';
            $sx .= '<TD  class="tabela00" align=center>&nbsp;<b>';
            $sx .= $line['fo_contato'];
            $sx .= '</b></TD></TR></TABLE>';
            $sx .= '</td></tr></table>';
            
            return($sx);
        }
        
        function resultado_mostra($page='')
        {
            global $dd;
            $codigo = trim(sonumero($dd[1]));
            
            /*Caso libere o codigo comentado a pesquisa irá mostrar todos os resultado se não fdor informado parametros*/
            //if (strlen($dd[1])>0)
            //{
              $whx = ' where ';   
            //}
            /* busca pelo codigo */
            if (strlen($codigo)==7)
                {
                    $sql = "select * from fornecedores where fo_codigo = like '%".$codigo."%' ";
                    $sql .= " order by fo_nomefantasia limit 100 ";
                    $rlt = db_query($sql);
                    $sx .= $this->mostra_fornecedores($rlt,$page);
                } else {
                    $st = UpperCaseSql($dd[1]).' ';
                    $st = troca($st,' ',';');
                    $st = splitx(';',$st);
                    $sh = '';
                    for ($r=0; $r < count($st);$r++)
                        {
                            if (strlen($wh) > 0)
                                { $wh .= ' and '; }
                            $wh .= " (fo_nomefantasia like '%".$st[$r]."%' ) ";
                        }
                    $sql = "select * from fornecedores $whx ".$wh." ";
                    $sql .= " order by fo_nomefantasia ";
                    $sql .= " limit 100";
                    $rlt = db_query($sql);  
                    
                    $sx .= $this->mostra_fornecedores($rlt,$page);               
                }   
            return($sx);        
        }
        
        function mostra_fornecedores($rlt,$page='')
        {
            $pa = $this->parametros;
			if (strlen($page)==0) { $page = 'cx_fornecedores.php'; }
            $sx = '<table width="90%" class="tabela01" align="center">';
            $sx .= '<TR class="tabela01h"><TH>Código<TH align="left" >Nome fantasia/Razão social<TH>Status';
            while ($line = db_read($rlt))
                {
                    //$tipo = trim($line['cl_autorizada']);
                    //if (strlen($tipo)==0) { $tipo = 'titular'; }
                    
                    $link = '<A HREF="'.$page.'?dd0='.trim($line['fo_codigo']).'&dd1='.trim($line['fo_codfor']).'&ddx='.trim(date("YmdHis")).$pa.'">';
                    $sx .= '<TR>';
                    $sx .= '<TD class="tabela01" align="center">';
                    $sx .= $link;
                    $sx .= trim($line['fo_codigo']);
                    $sx .= '</A>';
                    $sx .= '<TD  class="tabela01">';
                    $sx .= $link;
                    $sx .= trim($line['fo_nomefantasia'])." / ".trim($line['fo_razaosocial']);
                    $sx .= '</A>';
                    $sx .= '<TD class="tabela01" align="center">';
                    $sx .= $link;
                    $sx .= $line['fo_status'];
                    $sx .= '</A>';
                }
            $sx .= '</table>';
            return($sx);
        }       
        
        function mostra_fornecedor_notas($id)
        {
                $this->le($id);
                            //////// Dados do cliente
                $sx .= '<center><TABLE width="98%" class="lt1" border="0">';
                $sx .= '<TR><TD colspan=3><fieldset><legend>';
                $sx .= '<B>Dados do Cliente</B>';
                $sx .= '</legend>';
        
                $sx .= '<TABLE width=98% cellpadding=1 cellspacing=0  class=lt1 border=0  align="center">';
                $sx .= '<TR>';
                /////// Identidicação do cliente
                $sx .= '<TD colspan="3">Razão social/Nome fantasia</TD>';
                $sx .= '</TR>';
                $sx .= '<TR><TD bgcolor=#c0c0c0 colspan=3>&nbsp;<b>';
                $sx .= $this->fornecedor_nome;
                if (strlen($this->fornecedor_razao) > 0)
                    { $sx .= ' / '.$this->fornecedor_razao; }
                $sx .= '</b></TD></TR>';
                        
                /////// Endereço
                $sx .= '<TR><TD COLSPAN=2 width="60%">Endereço</TD>';
                $sx .= '<TD COLSPAN=1>Representante</TD></TR>';
                $sx .= '<TR><TD bgcolor=#c0c0c0 width="60%">&nbsp;<b>';
                $sx .= $this->fo_endereco;
                if (strlen($this->fo_complemento) > 0) { $sx .= '&nbsp;'.$this->fo_complemento; }
                $sx .= '</b></TD>';
                $sx .= '<TD>&nbsp;</TD>';
                $sx .= '<TD colspan=1 bgcolor=#c0c0c0>&nbsp;<b>';
                $sx .= $this->fo_representante;
                $sx .= '</b></TD></TR>';
                $sx .= '</TABLE>';
                //////////////// parte II
                $sx .= '<TABLE width=98% cellpadding=1 cellspacing=0  class=lt1 border=0 align="center" >';
                $sx .= '<TR>';
                $sx .= '<TD>Bairro</TD><TD></TD>';
                $sx .= '<TD>Cidade-UF-CEP</TD><TD></TD>';
                $sx .= '<TD>Fone/Fax/Celular</TD></TR>';
                $sx .= '<TR><TD width=15% bgcolor=#c0c0c0>&nbsp;<b><NOBR>';
                $sx .= $this->fo_bairro; // Bairro
                $sx .= '</b></TD>';
                $sx .= '<TD>&nbsp;</TD>';
                $sx .= '<TD bgcolor=#c0c0c0>&nbsp;<b>';
                $sx .= $this->fo_cidade; // Cidade
                if (strlen($this->fo_estado) > 0) { $sx .= '&nbsp;/&nbsp;'; }
                $sx .= $this->fo_estado; // Bairro
                if (strlen($this->fo_cep) > 0) { $sx .= '&nbsp;-&nbsp;'; }
                $sx .= $this->fo_cep; // Bairro
                $sx .= '';
                $sx .= '</b></TD>';
                $sx .= '<TD width=5><img src=images/nada.gif width=5 height=1 alt= border=0></TD>';
                $sx .= '<TD bgcolor=#c0c0c0 align=center>&nbsp;<b>';
                $sx .= $this->fo_fone;
                if (strlen($this->fo_fax) > 0) { $sx .= '&nbsp;fax: '.$this->fo_fax; }
                $sx .= '</b></TD></TR>';
                $sx .= '</TABLE>';
                //////////////// parte III
                $sx .= '<TABLE width=98% cellpadding=1 cellspacing=0  class=lt1 border=0 align="center" >';
                $sx .= '<TR><TD COLSPAN=2>CNPJ/CPF</TD><TD COLSPAN=2>Ie / RG</TD><TD COLSPAN=1>Contato</TD></TR>';
                $sx .= '<TR><TD bgcolor=#c0c0c0>&nbsp;<b>';
                $sx .= $this->fo_cgc;
                $sx .= '</b></TD><TD width=5><img src=images/nada.gif width=5 height=1 alt="" border=0 ></TD>';
                $sx .= '<TD bgcolor=#c0c0c0>&nbsp;<b>';
                $sx .= $this->fo_ie;
                $sx .= '</b></TD><TD width=5><img src=images/nada.gif width=5 height=1 alt= border=0 ></TD>';
                $sx .= '<TD bgcolor=#c0c0c0 align=center>&nbsp;<b>';
                $sx .= $this->fo_contato;
                $sx .= '</b></TD></TR></TABLE>';
                $sx .= '</fieldset></TR><TR>';
            
            return($sx);            
            
            
        }
        
        function lista_produtos_fornecedor($id='',$lj=7){
           global $acao,$dd,$avaliador,$base_name,$base_host,$base_user;    
           $this->set_tabelas();
           $tabela = "fornecedores";
           $ttlj=1;
           if($lj==7){ $ttlj=count($this->loja)-1;}
           $a=0;
           if($lj!=7){$ljdb=$this->loja[$lj][2];};  
           $js = '';
           while ($a < $ttlj) 
            {
             
                if($lj==7){$ljdb=$this->loja[$a][2];}
                
                require('../db_caixa_central.php');               
                $sql = "select * from ".$tabela." where fo_codigo = '".$id."'";
                $rlt = db_query($sql);

                while ($line = db_read($rlt))
                    {   
                        $razao=$line['fo_razaosocial'];
                        $fantazia=$line['fo_nomefantasia'];
                        $cod = $line['fo_codfor'];
                        
                    }
				
                require($ljdb);
                
                $prod = new produto;
                $prod->consulta_codigos('',$cod);
                $sql = "select * from produto where p_cod_fornecedor = '".$cod."' ";
                $rlt = db_query($sql);
                
                $f=0;
                while ($line = db_read($rlt))
                {
                   
                    $prodi = $line['p_codigo'];   
                    $prod->p_codigo=$prodi;                    
                    require($ljdb);
                    $sx .= $prod->mostra_produto($ljdb);
                    $sx .= '<div id="p'.$prodi.'a" style="display: none;">';
					
                    $sx .=$prod->mostra_estoque_produtos($cod,$prodi);
                    $js .= ' $("#p'.$prodi.'").click(function() {
                                 $("#p'.$prodi.'a").animate({  height:\'toggle\', },300);
                                 $("#p'.$prodi.'i").animate({  height:\'toggle\', },300);
                                });'.chr(13);
                    $sx.= '</div>';
                    $sx.='</tr></table>
                    ';  
                }
                $a++;
            }
            $sx .= '
               <script>
                 '.$js.'
               </script>';               
                
            return($sx);
        }

		function updatex()
			{
			$dx1 = 'fo_codigo';
			$dx2 = 'fo';
			$dx3 = 7;
			$sql = "update ".$this->tabela." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";
			$rlt = db_query($sql);
			
			return(1);
			}
			
		function updatex1()
			{
			$dx1 = '';
			$sql = "update ".$this->tabela." set fo_codfor = id_fo where fo_codfor =''";
			$rlt = db_query($sql);
			return(1);
			}	
		
		function cp()
		{
			$cp = array();
			array_push($cp,array('$H4','id_fo','',False,True,''));
			array_push($cp,array('$H4','fo_codfor','em_codigo',False,True,''));
			
			array_push($cp,array('$A','','Dados do fornecedor',False,True,''));
			array_push($cp,array('$O J:Juridica&F:Física','fo_tipo','Tipo',False,True,''));
			array_push($cp,array('$S70','fo_nomefantasia','Nome empresa (Fantasia)',False,True,''));
			array_push($cp,array('$S70','fo_razaosocial','Razão Social',False,True,''));
			array_push($cp,array('$S50','fo_endereco','Endereco',False,True,''));
			array_push($cp,array('$S50','fo_bairro','Bairro',False,True,''));
			array_push($cp,array('$UF','fo_estado','Estado',False,True,''));
			array_push($cp,array('$S40','fo_cidade','Cidade',False,True,''));
			array_push($cp,array('$S11','fo_cep','CEP',False,True,''));
			
			array_push($cp,array('$A','','Dados fiscais',False,True,''));
			array_push($cp,array('$S20','fo_cgc','CNPJ',False,True,''));
			array_push($cp,array('$S18','fo_ie','I.E.',False,True,''));
			
			array_push($cp,array('$A','','Contato',False,True,''));
			array_push($cp,array('$S70','fo_representante','representante',False,True,''));
			array_push($cp,array('$S15','fo_fone','Fone',False,True,''));
			array_push($cp,array('$S15','fo_fax','FAX',False,True,''));
			array_push($cp,array('$S15','fo_celular','Celular',False,True,''));
			array_push($cp,array('$S60','fo_contato','Pessoa de contato',False,True,''));
			array_push($cp,array('$S100','fo_email','e-mail',False,True,''));
			
			array_push($cp,array('$A','','Conta Corrente',False,True,''));
			array_push($cp,array('$S20','fo_cc_banco','Banco',False,True,''));
			array_push($cp,array('$S15','fo_cc_agencia','Agência',False,True,''));
			array_push($cp,array('$S15','fo_cc_conta','Conta corrente',False,True,''));
			array_push($cp,array('$S20','fo_cc_titularcpf','CNPJ/CPF do titular',False,True,''));
			array_push($cp,array('$S50','fo_cc_titular','Nome titular',False,True,''));
			array_push($cp,array('$T60:5','fo_cc_obs','Observação',False,True,''));
			
			array_push($cp,array('$O S:SIM&N:NÃO','fo_status','Ativo',False,True,''));
			return($cp);
		}	
}

?>
