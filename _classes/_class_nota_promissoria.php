<?php
class nota_promissoria
	{
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
		var $dp_nrfat;
		
		var $logo = '/fonzaghi/img/logo_fonzaghi.png';
		var $tabela = 'duplicata_joias';
		var $loja = 'J';
		var $loja_nome = '';
		
		function le($id=0)
			{
				global $base_name,$base_host,$base_user;
				require('../db_fghi_210.php');
				
				$this->nota_loja();
				if ($id > 0) { $this->id_dp = $id; }
				if (strlen($this->id_dp) > 0)
					{
						$sql = "select * from ".$this->tabela." where id_dp = ".$this->id_dp;
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								$this->dp_cliente = $line['dp_cliente'];
								$this->dp_valor = $line['dp_valor'];
								$this->dp_venc = $line['dp_venc'];
								$this->dp_data = $line['dp_data'];
								$this->dp_doc = trim($line['cx_chq_conta']).trim($line['cx_chq_nrchq']);
								$this->dp_status = $line['dp_status'];
								$this->dp_content = $line['dp_content'];
								$this->id_dp = $line['id_dp'];
								$erro = 0;
							} else {
								$erro = 1;
							}
					} else {
						$erro = 1;
					}
				return($erro);
								
			}

		function le_caixa($id=0,$tabela='')
			{
				$sql = "select * from ".$tabela." where id_cx = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->loja = substr($tabela,strlen($tabela)-2,2);
						$this->dp_cliente = $line['cx_cliente'];
						$this->dp_valor = $line['cx_valor'];
						$this->dp_venc = $line['cx_venc'];
						$this->dp_data = $line['cx_data'];
						$this->dp_doc = trim($line['cx_chq_conta']).trim($line['cx_chq_nrchq']);
						$this->dp_status = $line['cx_status'];
						$this->dp_content = $line['cx_descricao'];
						$this->id_dp = $line['id_cx'];
						$erro = 0;
					} else {
						$erro = 1;
					}
				return($erro);
								
			}

		function nota_dados()
			{
				//$juros = $this->nota_juros(date("Ymd"),$valor);
				$juros = 1.4;
				$total = $this->dp_valor;
				$sx .= '<table width="100%">'.chr(13);
				$sx .= '<TR class="lt0"><TD>venc.';
				$sx .= '<TD>valor';
				$sx .= '<TD>juros';
				$sx .= '<TD>total';
				
				$sx .= '<TR class="lt1">';
				$sx .= '<TD><B>'.stodbr($this->dp_venc);
				$sx .= '<TD><B>'.number_format($this->dp_valor,2);
				$sx .= '<TD><B>'.number_format($juros,2);
				$sx .= '<TD><B>'.number_format($total+$juros,2);
				
				$sx .= '<TR class="lt0">';
				$sx .= '<TD colspan=1>documento';
				$sx .= '<TD colspan=3>historico';
				
				$sx .= '<TR class="lt1"><B>';
				$sx .= '<TD>'.$this->dp_doc;
				$sx .= '<TD colspan=3><B>'.$this->dp_content;
				
				$sx .= '</table>';
				return($sx);
			}
			
		function nota_juros($data,$valor)
			{
				$valor = $valor * 0.02;
				return($valor);
			}
		function nota_loja()
			{
				if ($this->loja == 'J') { $this->tabela = 'duplicata_joias'; $this->loja_nome = 'Fonzaghi Jóias'; }
				if ($this->loja == 'M') { $this->tabela = 'duplicata_modas'; $this->loja_nome = 'Fonzaghi Moda Intima';}
				if ($this->loja == 'O') { $this->tabela = 'duplicata_oculos'; $this->loja_nome = 'Óculos';}
				if ($this->loja == 'U') { $this->tabela = 'duplicata_usebrilhe'; $this->loja_nome = 'Catálogo UseBrilhe';}
				if ($this->loja == 'D') { $this->tabela = 'juridico_duplicata'; $this->loja_nome = 'Jurídico';}
				if ($this->loja == 'S') { $this->tabela = 'duplicata_sensual'; $this->loja_nome = 'Boutique Sensual';}
				if ($this->loja == 'T') { $this->tabela = 'duplicata_teste'; $this->loja_nome = 'Loja Teste';}
			}
		function nota_imprimir()
			{
				global $dd;
				$co = new consultora;
				$co->le($this->dp_cliente);
				
				$sx = '<table width="96%" border=0>';
				// Linha 1
				$sx .= '<TR valign="top">';
				$sx .= '<TD rowspan="2" colspan="1" width="25%">';
				$sx .= '<img src="'.$this->logo.'">';
				$sx .= '<TD align="center" colspan="2" width="50%">';
				$sx .= '<font class="lt4" ><NOBR>NOTA PROMISSÓRIA</font>';
				$sx .= '<BR><font class="lt3" >'.UpperCase($co->nome);
				$sx .= '<BR><font class="lt2" ><NOBR><B>'.$this->loja_nome.'</B></font>';
				$sx .= '<TD align="right" width="25%">';
				$sx .= '<fieldset><legend class="lt0">VENCIMENTO</legend>';
				$sx .= '<center>';
				$sx .= '<font class="lt4"><TT><B>';
				$sx .= stodbr($this->dp_venc);
				$sx .= '</B></font>';
				$sx .= '</center>';
				$sx .= '</fieldset>'; 
				
				// Linha 2
				$sx .= '<TR valign="top">';
				//$sx .= '<TD rowspan="1" width="25%">';
				$sx .= '<TD rowspan="1" width="25%">';
				$sx .= '<fieldset><legend class="lt0">CONSULTORA</legend>';
				$sx .= '<center>';
				$sx .= '<font class="lt4"><TT> ';
				$sx .= $this->dp_cliente;
				$sx .= '</font>';
				$sx .= '</center>';

				$sx .= '<TD rowspan="1" width="25%">';
				$sx .= '<fieldset><legend class="lt0">DOCUMENTO</legend>';
				$sx .= '<center>';
				$sx .= '<font class="lt4"><TT> ';
				$sx .= $this->dp_doc;
				$sx .= '</font>';
				$sx .= '</center>';

				$sx .= '<TD rowspan="1" width="25%">';
				$sx .= '<fieldset><legend class="lt0">VALOR</legend>';
				$sx .= '<center>';
				$sx .= '<font class="lt4"><TT><B>';
				$vlr = number_format($this->dp_valor,2);
				$vlr = troca($vlr,',','#');
				$vlr = troca($vlr,'.',',');
				$vlr = troca($vlr,'#','.');
				$sx .= $vlr;
				$sx .= '</td></tr>';
				
				$sx .= '<TR><TD colspan=4 class="lt3" >';
				$sx .= '<font style="line-height:150%;">';
				$sx .= 'pagar por esta única via de Nota Promissória à sua ordem a quantia de ';
				$sx .= '<B><I>'.extenso($this->dp_valor).'</b></I>';	
				$sx .= ' em moeda corrente deste país até a data de vencimento acima mencionada.';
				$sx .= '<BR>';
				$sx .= 'Emitente: <B>'.$co->nome.'</b>';
				$sx .= ' - CPF: <B>'.$co->cpf.'</B>';
				$sx .= '<BR>';

				$sx .= 'Serão cobrados multa de 2% (R$ '.troca(number_format($this->dp_valor * 0.02,3),'.',',').') para pagamento em atraso, ';
				$sx .= 'mais juros de 0,2% (R$ '.troca(number_format($this->dp_valor * 0.002,3),'.',',').') ao dia de atraso.';
				if ($this->dp_status == 'B')
					{
						$sx .= '<center><img src="../img/img_quitada.png"></center>';	
					} else {
						$sx .= '<BR>';
						$sx .= '<BR>';
					}
				$sx .= '<TR><TD colspan="2"  class="lt3">';
				
				$sx .= '<BR>';
				$sx .= 'Curitiba, ';
				$sx .= substr($this->dp_data,6,2);
				$sx .= ' de ';
				$sx .= nomemes(round(substr($this->dp_data,4,2)));
				$sx .= ' de ';
				$sx .= substr($this->dp_data,0,4).'.';
				$sx .= '<TD align=center class="lt1">';
				$sx .= '_________________________________________';
				$sx .= '<BR><B><font class="lt3">'.UpperCase($co->nome).'</font></B>';
				$sx .= '<TD align="right">';
				$sx .= $this->barcod($this->dp_doc);
				$sx .= '</td></tr>';
				$sx .= '</table>';
				return($sx);
			}

		function le_barcod($cod)
			{
				$bc = substr($cod,0,11);
				$tp = substr($cod,0,1);
				if ($tp == '1') { $this->tabela = 'duplicata_joias'; $this->loja_nome = 'Fonzaghi Jóias'; }
				if ($tp == '2') { $this->tabela = 'duplicata_modas'; $this->loja_nome = 'Fonzaghi Moda Intima';}
				if ($tp == '3') { $this->tabela = 'duplicata_usebrilhe'; $this->loja_nome = 'Catálogo UseBrilhe';}
				if ($tp == '4') { $this->tabela = 'duplicata_sensual'; $this->loja_nome = 'Boutique Sensual';}
				if ($tp == '5') { $this->tabela = 'duplicata_oculos'; $this->loja_nome = 'Óculos';}
				if ($tp == '8') { $this->tabela = 'duplicata_teste'; $this->loja_nome = 'Teste';}
				if ($tp == '9') { $this->tabela = 'juridico_duplicata'; $this->loja_nome = 'Jurídico';}
				$bc = round(substr($bc,1,11));
				$this->le($bc);
				return(1);
			}
		function barcod($vlr)
			{
				global $cliente;
				$vlr = (sonumero($vlr));
		
				while (strlen($vlr) < 15) { $vlr = '0'.$vlr; }
				$ca = array(3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1);
				$to = 0;
				for ($ra=0;$ra < strlen($vlr);$ra++)
					{
						$rb = strlen($vlr)-$ra-1;
						$ta = round(substr($vlr,$rb,1)) * $ca[$ra];
						$to = $to + $ta;
					}
				while ($to > 10) { $to = ($to - 10); }
				$to = 10-$to; if ($to == 10) { $to = 0; }
				
				$vlr .= $to;
				$bar = new WBar;
				
				$sr = $bar->WBarCode($vlr).'<BR>'.$vlr;
				$this->dp_barcod = $vlr;
				return($sr);	
			}
	}
class WBar {
//variaveis privadas
var $_fino;
var $_largo;
var $_altura;

//variaveis publicas
var $BarCodes = array();
var $texto;
var $matrizimg;
var $f1;
var $f2;
var $f;
var $i;

//Construtor da class

function WBarCode($Valor)
{
$this->fino=1;
$this->largo=3;
$this->altura=50;

if (empty($this->BarCodes[0]))
  {

    $this->BarCodes[0]="00110";
    $this->BarCodes[1]="10001";
    $this->BarCodes[2]="01001";
    $this->BarCodes[3]="11000";
    $this->BarCodes[4]="00101";
    $this->BarCodes[5]="10100";
    $this->BarCodes[6]="01100";
    $this->BarCodes[7]="00011";
    $this->BarCodes[8]="10010";
    $this->BarCodes[9]="01010";


	for ($this->f1=9; $this->f1>=0; $this->f1=$this->f1-1)
    {
      for ($this->f2=9; $this->f2>=0; $this->f2=$this->f2-1)
      {
        $this->f=$this->f1*10+$this->f2;
        $this->texto="";
        for ($this->i=1; $this->i<=5; $this->i=$this->i+1)
        {
$this->texto=$this->texto.substr($this->BarCodes[$this->f1],$this->i-1,1).
	substr($this->BarCodes[$this->f2],$this->i-1,1);
        } 
        $this->BarCodes[$this->f]=$this->texto;
      } 

    } 

  } 

//Desenho da barra
// Guarda inicial
$this->matrizimg.= "
<img src=p.gif width=$this->fino height=$this->altura border=0><img 
src=b.gif width=$this->fino height=$this->altura border=0><img
src=p.gif width=$this->fino height=$this->altura border=0><img
src=b.gif width=$this->fino height=$this->altura border=0><img 
";

$this->texto=$Valor;
if (strlen($this->texto)%2<>0)
 {
  $this->texto="0".$this->texto;
  } 
// Draw dos dados
while(strlen($this->texto)>0)
  {
$this->i=intval(substr($this->texto,0,2));
$this->texto=substr($this->texto,strlen($this->texto)-(strlen($this->texto)-2));
$this->f=$this->BarCodes[$this->i];
for ($this->i=1; $this->i<=10; $this->i=$this->i+2)
    {
      if (substr($this->f,$this->i-1,1)=="0")
      {
       $this->f1=$this->fino;
      }
        else
      {

        $this->f1=$this->largo;
      } 

$this->matrizimg.="src=p.gif width=$this->f1 height=$this->altura border=0><img 
	";
   if (substr($this->f,$this->i+1-1,1)=="0")
      {

        $this->f2=$this->fino;
      }
        else
      {

        $this->f2=$this->largo;
      } 

$this->matrizimg.= "src=b.gif width=$this->f2 height=$this->altura border=0><img ";
	}
}

$this->matrizimg.= "src=p.gif width=$this->largo height=$this->altura border=0><img src=b.gif width=$this->fino height=$this->altura border=0><img 
src=p.gif width=1 height=$this->altura border=0>";

//escreve todo o codigo da barra na tela...
return($this->matrizimg);

    }//fim da function
}//fim da Class
?>