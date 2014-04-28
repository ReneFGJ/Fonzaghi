<?php
class categoria
	{
	var $tabela = "produto_categoria";
	var $tabela_categoria = "produto_categorizacao";
	
		function updatex()
			{
				global $base;
				$c = 'ct';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) "; 
				$rlt = db_query($sql);
				return(0);
			}

	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_ct','',False,True));
			array_push($cp,array('$H8','ct_codigo','',False,True));
			array_push($cp,array('$S50','ct_descricao','Descrição',True,True));
			array_push($cp,array('$C8','ct_main','Grupo principal',False,True));
			array_push($cp,array('$Q ct_descricao:ct_codigo:select * from '.$this->tabela.' where ct_main = \'1\' and ct_ativo=\'1\' order by ct_ordem, ct_descricao','ct_ref','Referência',False,True));
			
			array_push($cp,array('$[1-60]','ct_ordem','Ordem',True,True));
			array_push($cp,array('$O 1:SIM&0:NÃO','ct_ativo','Ativo',False,True));
			return($cp);
		}
	
	function form_categoria($produto)
		{
			global $dd,$acao;
			$sql = "select t2.ct_codigo as codigo, t1.ct_descricao as grupo, t2.ct_descricao as categoria, t3.catp_ativo as catp_ativo
					from ".$this->tabela." as t1
					left join ".$this->tabela." as t2 on t2.ct_ref = t1.ct_codigo
					left join ".$this->tabela_categoria." as t3 on t2.ct_codigo = catp_categoria and catp_produto = '$produto'
					where not t2.ct_codigo isnull
					order by t1.ct_ordem, t2.ct_ordem
			";
			
			if (strlen($acao) > 0)
				{
					$cr = chr(13).chr(10);
					$sqlx = "delete from ".$this->tabela_categoria." where catp_produto = '".$produto."'; ".$cr;
					$rlt = db_query($sql);
					
					while ($line = db_read($rlt))
						{
							$name = 'ddd'.$line['codigo'];
							$catp = trim($line['codigo']);
							$vlr = trim($_POST[$name]);
							
							if ($_POST[$name] == '1')
								{
								$sqlx .= "insert into ".$this->tabela_categoria."
									(
										catp_produto, catp_categoria, catp_ativo
									) values (
										'$produto','$catp',1
									);
									".chr(13);
								}
							
						}
					if (strlen($sqlx) > 0)
						{
							$rlt = db_query($sqlx);		
						}				
					
				}
			
			$rlt = db_query($sql);
			
			$xgrupo = '';
			$cr = chr(13);
			
			$sx = '<form method="post" action="'.page().'">'.$cr;
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">'.$cr;
			$sx .= '<input type="hidden" name="dd1" value="'.$dd[1].'">'.$cr;
			$sx .= '<input type="hidden" name="dd2" value="'.$dd[2].'">'.$cr;
			$sx .= '<input type="hidden" name="dd3" value="'.$dd[3].'">'.$cr;
			$sx .= '<input type="hidden" name="dd4" value="'.$dd[4].'">'.$cr;
			 
			$sx .= '<table width="100%" border=1 class="tabela00">';
			$sx .= '<TR valign="top">';
			$col = 0;
			while ($line = db_read($rlt))
				{
					$checked = '';
					if (trim($line['catp_ativo'])=='1')
						{ $checked = ' checked ';}
													
					$grupo = trim($line['grupo']);
					if ($grupo != $xgrupo)
						{
							if ($col > 2) { $sx .= '<TR valign="top">'; $col=0;}
							$sx .= '<TD>';
							$sx .= '<B>'.$grupo.'</B>';	
							$xgrupo = $grupo;
							$col++;						
						}
					$sx .= '<BR><input name="ddd'.$line['codigo'].'" type="checkbox" value="1" '.$checked.'>';
					$sx .= '&nbsp;';
					$sx .= trim($line['categoria']).$cr;
				}
			$sx .= '<TR><TD colspan=3>
					<input type="submit" name="acao" class="submit-geral" value="gravar">
					';
			$sx .= '</table>';
			return($sx);
		}

	function structure()
		{
			$sql = "
			CREATE TABLE ".$this->tabela."
			(
  				id_ct serial NOT NULL,
  				ct_codigo character(5),
  				ct_descricao character(50),
  				ct_main character(1),
  				ct_ref char(5),
  				ct_ordem integer,
  				ct_ativo integer
			)";
			$rlt = db_query($sql);
			
			$sql = "
			CREATE TABLE ".$this->tabela_categoria."
			(
  				id_catp serial NOT NULL,
  				catp_produto character(7),
  				catp_categoria character(5),
  				catp_ativo integer
			)";
			$rlt = db_query($sql);			
		}
	}
?>
