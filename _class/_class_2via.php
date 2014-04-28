<?php
class segunda_via
	{
		var $cliente;
		var $data_ini;
		var $data_fim;
		var $loja;
		
		var $tabela = '';
		
	function segunda_via_mostra_dados($rst)
		{
		global $tab_max;
		$sx .= '<table width="'.$tab_max.'" cellspacing=8 cellpadding=0 class="lt1">';
		$col = 99;
		$colm = 5;
		$xdia = 19000101;
		$lojas = array(
				'M'=>'Modas', 'J'=>'Joias','O'=>'Óculos',
				'S'=>'Sensual', 'E'=>'Modas Express',
				'G'=>'Jóias Express', 'U'=>'UseBrilhe'
		);
		for ($r=0;$r < count($rst);$r++)
			{
			$line = $rst[$r];
			$mes = substr($line[4],0,6);
			$dia = $line[4];
				
			$link = '<A HREF="#" onclick="newxy2('.chr(39).'2via_visualizar.php?dd0=';
			$link .= $line[0].'&dd1='.$mes.chr(39).',820,500);">';
			
			if ($dia != $xdia)
				{
					$sx .= '<TR><TD>=';
					$sx .= '<TR><TD colspan=12 style="border-bottom: 1px solid Black; line-height: 150%;">';
					$sx .= '<font class="lt4">';
					$sx .= substr($dia,6,2);
					$sx .= ' ';
					$sx .= nomemes(round(substr($dia,4,2)));
					$sx .= ' '.substr($dia,0,4).'.';
					$xdia = $dia;
					$col = 99;
				}	
			
			if ($col >= $colm)
				{$sx .= '<TR>'; $col = 0; }
			$sx .= '<TD align="center" bgcolor="#F0F0F0">';
			$sx .= $link;
			$sx .= $lojas[$line[3]];
			$sx .= '(';
			$sx .= $line[2];
			$sx .= ')';
			$sx .= '<BR>';
			$sx .= $line[5].' '.$line[6];
			$col++;
			}
		$sx .= '</table>';
		return($sx);
		}
		
	function segunda_via_busca()
		{
			
			$rst = array();
			$mes_b = substr($this->data_ini,0,6).'01';
			$mes_a = substr($this->data_fim,0,6).'99';
			
			$max = 60;
			while (($mes_a >= $mes_b) and ($max > 0))
				{
					$max--;
					$rst = $this->segunda_via_mes($mes_a,$rst);
					$mes_a = DateAdd('m',-1,$mes_a);
				}
			
			return($rst);
		}
		
	function segunda_via_mes($mes,$rst)
		{
			global $base_name,$base_host,$base_user;	
			require("../db_2via.php");			
			
			$tab = 'via_log_'.substr($mes,0,6);
			$sql = "select * from ".$tab." where v_cliente = '".$this->cliente."' ";
			if ((strlen($this->loja) > 0)&&($this->loja!='T'))
				{ $sql .= " and v_loja = '".$this->loja."' "; }
			$sql .= " order by v_data desc, v_hora desc ";
			
			/* Executa Query */
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					array_push($rst,array($line['id_v'],$tab,$line['v_tipo'],$line['v_loja'],$line['v_data'],$line['v_hora'],$line['v_log']));
				}
			return($rst);
		}
	}
