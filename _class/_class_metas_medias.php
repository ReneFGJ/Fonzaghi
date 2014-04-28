<?php
class meta_medias
	{
		var $vld_meta=0;
		var $tt_meta='';
		var $tt_meta_atingidas = 0;
		var $sty=" background-color:#FFEC8B; 
					text-align:center;
					border-style:solid;
					border-color:#FFD700;
					border-radius: 39px;
				    -webkit-border-radius: 15px;
				    -moz-border-radius: 15px;
				    -ms-border-radius: 15px;
				    -o-border-radius: 15px;
					color:#0f9d88;
					";
				
		
		
	function mostra_metas($cliente='')
		{
			global $setlj,$base_name,$base_server,$base_host,$base_user,$user,$setft;
			
			$ljs = setlj();
			
			for ($r=0;$r <= 6;$r++)
				{
					$db = $setlj[3][$r];
					$loja = $setlj[1][$r].': ';
					require('../'.$db);					
				$sa = '<TD align="center">';
				$sb = trim($this->recupera_meta($cliente,$loja));
				if (strlen(trim($sb)) > 0) 
					{
						if ($tot == 3)
							{ $tx .= '<TR>'; }
					$tx .= $sa.$sb; 
					$tot++;
					}
				}
			if ($tot == 0)
				{
					 $tx .= '<TD align="right">Sem valores'; 
				}
			$sx = '<table width="100%" border=0 cellpadding=0 cellspacing=0 >';
			$sx .= '<TR>';
			$this->calcula_premios($cliente);
			if($this->tt_meta_atingidas>0)
				{
					$sx .= '<TD rowspan=2 align="center" width="10"  style="'.$this->sty.'" >';
					$sx .= '<img src="../img/logo_balao_verde.png" height="60" title="Total de prêmios ganhos já lançados pela coordenadora"><br>'.round($this->tt_meta_atingidas);
				}else{
					$sx .= '<TD rowspan=2 align="center" width="10">';
					$sx .= '<img src="../img/logo_balao.png" height="60"  title="Total de prêmios ganhos já lançados pela coordenadora">';
				}
			
			$sx .= $tx.'</table>';
			return($sx);
			
		}
	/*Carrega prêmios cadastrados*/	
	function calcula_premios($cliente)
	{
		global $base_name,$base_server,$base_host,$base_user,$user;
		require("../db_fghi_206_PROMO.php");
		$sql = "select count(*) from produto_estoque 
					where pe_cliente = '$cliente'
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sx = $line['count']/2;
					$this->tt_meta_atingidas = $sx;
					return($sx);
				}
			return('');
	}	
	/*recupera meta e verifica se foi obtida*/
	function recupera_meta($cliente,$loja)
		{
			$sql = "select * from metas 
					where mt_cliente = '$cliente' and 
						  mt_previsao = 1
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$data = $line['mt_data'];
					$vlr_meta=$line['mt_valor'];
						$this->vld_meta=1;
						$this->tt_meta++;
						$sx .= '<TD style="font-size:14px;" align ="center" title="Meta a ser atingida na loja '.$loja.'">';
						$sx .= $loja.'<br>'.number_format($vlr_meta,2,',','.');
					return($sx);
				}
			return('');
		}
	}
?>
