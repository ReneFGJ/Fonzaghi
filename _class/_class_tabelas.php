<?php
class tabela
	{
	function validar($ano='')
		{
			if (strlen($ano)==0) { $ano = ''; }
			
			$this->contabilidade($ano);
			$this->via2($ano);
			$this->historico($ano);
			$this->produtos($ano);
			$this->caixas($ano);
			
		}
	function exists_table($table)
		{
			$sql = "
			select relname from pg_class where relname = '$table' order by relname
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return(1);
				} else {
					return(0);
				}
		}
	function contabilidade($ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			require('db_206_contabilidade.php');

			echo '<h2>Contabilidade</h2>';			
			for ($r=1;$r <= 12;$r++)
				{
					$table = "pc_".$ano.strzero($r,2);
					echo '<BR>'.date("d/m/Y H:i:s:c").' '.$table;
					if ($this->exists_table($table))
						{
							echo ' <font color="green">ok</font>';
						} else {
							$this->structure_contabilidade($table);
							echo ' <font color="Blue">create</font>';
						}
				}			
			return(0);	
		}
	function via2($ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			require('db_2via.php');
			echo '<h2>2. Vias</h2>';
			for ($r=1;$r <= 12;$r++)
				{
					$table = "via_log_".$ano.strzero($r,2);
					echo '<BR>'.date("d/m/Y H:i:s:c").' '.$table;
					if ($this->exists_table($table))
						{
							echo ' <font color="green">ok</font>';
						} else {
							$this->structure_2via($table);
							echo ' <font color="Blue">create</font>';
						}
					$table = "historico_".$ano.strzero($r,2);
					echo '<BR>'.date("d/m/Y H:i:s:c").' '.$table;
					if ($this->exists_table($table))
						{
							echo ' <font color="green">ok</font>';
						} else {
							$this->structure_historico_2($table);
							echo ' <font color="Blue">create</font>';
						}											
				}
		
		}
	function historico($ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			require('db_cadastro.php');
			$table = 'historico_'.$ano;
			echo '<h2>'.$table.'</h2>';
			echo '<BR>'.date("d/m/Y H:i:s:c").' '.$table;
			if ($this->exists_table($table))
				{
					echo ' <font color="green">ok</font>';
				} else {
					$this->structure_historico($table);
					echo ' <font color="Blue">create</font>';
				}	
		}
	function produtos($ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			$db = array('db_fghi_206_joias.php','db_fghi_206_modas.php',
						'db_fghi_206_oculos.php','db_fghi_206_PROMO.php',
						'db_fghi_206_express.php','db_fghi_206_express_joias.php',
						'db_fghi_206_sensual.php','db_fghi_206_TST.php',
						'db_fghi_206_ub.php');
			for ($a=0;$a < count($db);$a++)
				{
					echo '<h2>'.$db[$a].'</h2>';
					require(''.$db[$a]);
					for ($r=1;$r <= 12;$r++)
					{
						$table = 'produto_log_'.$ano.strzero($r,2);
						echo '<BR>'.date("d/m/Y H:i:s:c").' '.$table;
						if ($this->exists_table($table))
						{
							echo ' <font color="green">ok</font>';
						} else {
							$this->structure_produto_log($table);
							echo ' <font color="Blue">create</font>';
						}
					}
				}
			return(0);
		}
	function caixas($ano)
		{
			global $base_name,$base_server,$base_host,$base_user,$user;
			
			require("db_ecaixa.php");
			echo '<h2>Caixa Central</h2>';
			$cx = array('01','02','03','04','05','06','07','08','09','99');
			
			for ($r=1;$r <= 12;$r++)
			
			{
				echo '<h3>'.strzero($r,2).'/'.$ano.'</h3>';
				for ($a=0;$a < count($cx);$a++)
				{
					$table = "caixa_".strzero($ano,4).strzero($r,2).'_'.$cx[$a];
					echo '<BR>'.date("d/m/Y H:i:s:c").' '.$table;
					
					if ($this->exists_table($table))
						{
							echo ' <font color="green">ok</font>';
						} else {
							$this->structure_caixa($table);
							echo ' <font color="Blue">create</font>';
						}		
				}
			}
			return($sx);
		}
	function structure_contabilidade($table)
		{
			$sql = "
				CREATE TABLE ".$table."
				(
					id_pl serial NOT NULL,
  					pl_data integer DEFAULT 0,
  					pl_conta character(15),
  					pl_valor double precision,
  					pl_saldo integer DEFAULT 0,
  					pl_historico character(80)
				)";
				$rlt = db_query($sql);
				
				$sql_i = "CREATE INDEX ".$table."_01 ON ".$table." (pl_conta ASC NULLS LAST);".chr(13).chr(10);
				$sql_i .= "CREATE INDEX ".$table."_02 ON ".$table." (pl_data ASC NULLS LAST);".chr(13).chr(10);
				$rlt = db_query($sql_i);			
							
		}
	function structure_historico_2($table)
		{
			$sql = "
				CREATE TABLE ".$table."
				(
  					id_hi serial NOT NULL,
  					hi_cliente character(7),
    				hi_tipo character(3),
    				hi_data integer,
    				hi_hora character(5),
    				hi_descricao character(80)
  				)			
			";
			$rlt = db_query($sql);
			
			$sql_i = "CREATE INDEX ".$table."_01 ON ".$table." (hi_cliente ASC NULLS LAST);".chr(13).chr(10);
			$sql_i .= "CREATE INDEX ".$table."_02 ON ".$table." (hi_data ASC NULLS LAST);".chr(13).chr(10);
			$rlt = db_query($sql_i);			
		}		
	function structure_2via($table)
		{
			$sql = "
				CREATE TABLE ".$table."
				(
  					id_v serial NOT NULL,
    				v_cliente character(7),
    				v_data integer,
    				v_hora character(5),
    				v_log character(10),
    				v_texto text,
    				v_tipo character(3),
    				v_loja character(1),
    				v_arq character(12)
  				 )			
			";
			$rlt = db_query($sql);
			
			$sql_i = "CREATE INDEX ".$table."_01 ON ".$table." (v_cliente ASC NULLS LAST);".chr(13).chr(10);
			$sql_i .= "CREATE INDEX ".$table."_02 ON ".$table." (v_data ASC NULLS LAST);".chr(13).chr(10);
			$rlt = db_query($sql_i);			
		}
	function structure_historico($table)
		{
			$sql = "
				CREATE TABLE ".$table."
				(
  				id_h serial NOT NULL,
  				h_data integer,
  				h_hora character(5),
  				h_log character(10),
  				h_texto text,
  				h_cliente character(7),
  				h_tipo character(3),
  				h_loja character(1) DEFAULT 'D'::bpchar,
  				CONSTRAINT key_historico_2015 PRIMARY KEY (id_h)
				)";	
			$rlt = db_query($sql);
			
			$sql_i = "CREATE INDEX ".$table."_01 ON ".$table." (pl_ean13 ASC NULLS LAST);".chr(13).chr(10);
			$rlt = db_query($sql_i);
			return(1);		
		}
	function structure_produto_log($table)
		{
			$sql = "CREATE TABLE ".$table."
					(
					id_pl serial NOT NULL,
  					pl_ean13 character(15),
  					pl_data integer DEFAULT 0,
  					pl_hora character(5),
  					pl_cliente character(7),
  					pl_status character(1),
  					pl_kit character(6),
  					pl_produto character(7),
  					pl_log character(10)  					
					)";
			$rlt = db_query($sql);
			
			if (!($this->exists_table($table.'_01')))
				{
				$sql_i = "CREATE INDEX ".$table."_01 ON ".$table." (pl_ean13 ASC NULLS LAST);".chr(13).chr(10);
				$sql_i .= "CREATE INDEX ".$table."_02 ON ".$table." (pl_data ASC NULLS LAST);".chr(13).chr(10);
				$sql_i .= "CREATE INDEX ".$table."_03 ON ".$table." (pl_produto ASC NULLS LAST);".chr(13).chr(10);
				$rlt = db_query($sql_i);
				echo '<font color="blue"> (create index)</font>';
				}			
		}
	function structure_caixa($table)
		{
			$sql = "CREATE TABLE ".$table."
					(
  					id_cx serial NOT NULL,
  					cx_data integer DEFAULT 0,
  					cx_hora character(5),
    				cx_tipo character(3),
    				cx_descricao character(100),
    				cx_valor double precision DEFAULT 0,
    				cx_log integer DEFAULT 0,
    				cx_terminal character(15),
    				cx_cliente character(7),
    				cx_nome character(100),
    				cx_venc integer,
    				cx_doc character(12),
    				cx_parcela character(5),
    				cx_status character(1),
    				cx_lote character(7),
    				cx_chq_banco character(3),
    				cx_chq_conta character(10),
    				cx_chq_agencia character(12),
    				cx_chq_nrchq character(12),
    				cx_proc integer DEFAULT 0,
    				cx_nrop character(7)
  					)";
				$rlt = db_query($sql);
			
			if (!($this->exists_table($table.'_01')))
				{
				$sql_i = "CREATE INDEX ".$table."_01 ON ".$table." (cx_cliente ASC NULLS LAST);".chr(13).chr(10);
				$sql_i .= "CREATE INDEX ".$table."_02 ON ".$table." (cx_data ASC NULLS LAST);".chr(13).chr(10);
				$sql_i .= "CREATE INDEX ".$table."_03 ON ".$table." (cx_lote ASC NULLS LAST);".chr(13).chr(10);
				$rlt = db_query($sql_i);
				echo '<font color="blue"> (create index)</font>';
				}
			
		}
	}
?>
