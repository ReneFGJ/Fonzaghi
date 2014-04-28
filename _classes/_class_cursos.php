<?
class cursos
	{
	var $curso;
	var $cliente;
	
	function calcula()
		{
		
		}
	
	function mostrar()
		{
		if (strlen($this->cliente) > 0)
			{
			$cus = array(0,0,0,0,0,0,0);
			$cun = array('','Marketing Pessoal','Finanças Pessoais','Atendimento ao Cliente','Produto Módulo','Motivação Módulo');
			$sql = "select * from capacitacao_participacao ";
			$sql .= " left join capacitacao_curso on cs_codigo = cp_curso ";
			$sql .= " where cp_cliente = '".$this->cliente."' ";
			$sql .= " and cp_status = 'B' ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
				$tc = round($line['cs_img']);
				if ($tc > 0)
					{ $cus[$tc] = 1; }
				}
			
			if (($cus[1] + $cus[2] + $cus[3] + $cus[4] + $cus[5]) == 5)
				{
					$src  = '<img src="img/icone_curso_06.png" width="24" height="24" alt="" border="0">';
					$src .= '<img src="img/icone_curso_06.png" width="24" height="24" alt="" border="0">';
					$src .= '<img src="img/icone_curso_06.png" width="24" height="24" alt="" border="0">';
					$src .= '<img src="img/icone_curso_06.png" width="24" height="24" alt="" border="0">';
					$src .= '<img src="img/icone_curso_06.png" width="24" height="24" alt="" border="0">';
				} else {
					for ($r=1;$r < 6;$r++)
						{
						$tit = 'title="'.$cun[$r].'" ';
						if ($cus[$r] == '1')
							{ $src .= '<img src="img/icone_curso_'.strzero($r,2).'.png" width="24" height="24" alt="" border="0" '.$tit.'>'; }
							else 
							{ $src .= '<img src="img/icone_curso_00.png" width="24" height="24" alt="" border="0" '.$tit.'>'; }
						}
				}
			}
		return($src);
		}
	}

?>