<?
class argox
	{
	var $id;
	var $sx;
	function ppla_start()
		{
			$s = '';
			$cr = chr(13).chr(10);
			$s .= chr(2).'c0000'.$cr;
			$s .= chr(2).'KI503'.$cr;
			$s .= chr(2).'O0220'.$cr;
			$s .= chr(2).'f220'.$cr;
			$s .= chr(2).'KW0399'.$cr;
			$s .= chr(2).'KI7'.chr(1).$cr;
			$s .= chr(2).'V0'.$cr; // 
			$s .= chr(2).'L'.$cr;; // Layout "LandScape"
			$s .= 'H12'.$cr; // Temperatura
			$s .= 'PC'.$cr; // Print Speed
			$file = 'img_loja/logo.argox';
			if (file_exists($file))
				{
					$s .= chr(2).'xAGPB9'.$cr;
					$s .= chr(2).'IAPPB9'.$cr;
					$fil = fopen($file,'r');
					while (!(feof($fil)))
						{
							$s .= fread($fil,1024);
						}
					fclose($fil);
				}
			return($s);
		}
		
	function ppla_start_row()
		{
			$cr = chr(13).chr(10);
			$s .= chr(2).'L'.$cr;
			$s .= 'A2'.$cr; 
			$s .= 'D11'.$cr; 
			return($s);
		}
	function ppla_end()
		{
			return('');
		}
	function ppla_end_row()
		{
			$cr = chr(13).chr(10);
			$s .= '^99'.$cr;
			$s .= 'Q0001'.$cr;
			$s .= 'E'.$cr;
			return($s);
		}
	function ppla_rotacao($grau)
		{
			$gr = array('0' => 1,'90' => 2, '270' => 4, 
			'-90' => 4, '180' => 3, '-270' => 2, 
				'1' => 1, '2' => 2, '3' => '3');
			$in = round($gr[$grau]);
			return($in);
		}
	function ppla_texto($txt,$x,$y,$rot,$font)
		{
			$s .= $this->ppla_rotacao($rot); // rotacao
			if (strlen($font)==0) { $font = '311000'; }
			$s .= $font; // Tipo de Barra
			$s .= strzero($x,4);
			$s .= strzero($y,4);
			$s .= $txt;
			$s .= chr(13).chr(10);
			return($s);
		}
	function ppla_barras_upca($bar,$x,$y,$rot)
		{
			$s .= $this->ppla_rotacao($rot); // rotacao
			$s .= 'B22026'; // Tipo de Barra
			$s .= strzero($x,4);
			$s .= strzero($y,4);
			$s .= strzero($bar,11);
			$s .= chr(13).chr(10);
			return($s);
		}
	function ppla_barras_upca2($bar,$x,$y,$rot)
		{
			$s .= $this->ppla_rotacao($rot); // rotacao
			$s .= 'B22026'; // Tipo de Barra
			$s .= strzero($x,4);
			$s .= strzero($y,4);
			$s .= strzero($bar,11);
			$s .= chr(13).chr(10);
			return($s);
		}
    function ppla_barras_upca3($bar,$x,$y,$rot)
        {
            $s .= $this->ppla_rotacao($rot); // rotacao
            $s .= 'B22022'; // Tipo de Barra
            $s .= strzero($x,4);
            $s .= strzero($y,4);
            $s .= strzero($bar,11);
            $s .= chr(13).chr(10);
            return($s);
        } 
	//joias	
	function ppla_barras_upca4($bar,$x,$y,$rot)
        {
            $s .= $this->ppla_rotacao($rot); // rotacao
            $s .= 'C22024'; // Tipo de Barra
            $s .= strzero($x,4);
            $s .= strzero($y,4);
            $cd = strzero($bar,12);
			$s .= substr($cd,5,6);
            $s .= chr(13).chr(10);
            return($s);
        }    
	        	 	    	          		
	function ppla_import()
		{
			$rst = fopen('img_loja/logo.argox.hex','r');
			$s = '';
			while (!(feof($rst)))
				{
					$s .= fread($rst,1024);
				}
			fclose($rst);
			$s = troca($s,' ','');
			$s = troca($s,chr(13),'');
			$s = troca($s,chr(10),'');
			$he = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,
						'5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9,
						'a'=>10, 'b'=>11, 'c'=>12, 'd'=>13, 'e'=>14,
						'f'=>15);
			$sr = '';
		$s = trim($s);
		$rst = fopen('img_loja/logo.argox','w');
		for ($rx=0;$rx < strlen($s);$rx=($rx+2))
				{
					$v1 = substr($s,$rx,1);
					$v2 = substr($s,$rx+1,1);
					$sr = $he[$v1]*16+$he[$v2];
					$sh = chr($sr);
					//echo '<BR>>'.$v1.$v2.'=='.$sr.'=='.$sh;
					fwrite($rst,$sh);	
				}
		fclose($rst);
		exit;

			$this->sx = $sr;
			return(1);
		}
	function ppla_find_imagem()
		{
			$s = $this->sx;
			$xa = strpos($s,chr(2).'IAP');
			if ($xa > 0)
				{
					$s = substr($s,$xa+4,strlen($s));
					$xnome = trim(substr($s,0,strpos($s,chr(13))));
					$s = substr($s,strpos($s,chr(13))+1,strlen($s));
					$img1 = substr($s,0,strpos($s,chr(13).chr(10))-2);
					$img2 = substr($s,0,strpos($s,chr(2).'L'));

					echo '<BR>Nome:'.$xnome.' = '.strlen($xnome).'<HR>';
					echo strlen($img1).'=='.strlen($img2);
					echo '<HR>';
					$rst = fopen('img_loja/'.$xnome.'.argox','w');
					fwrite($rst,$img1);
					fclose($rst);
				}
			return(1);
		}
		
	}
?>
