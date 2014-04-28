<?php

class funcionario
	{
	var $nome;
	var $vale = 0;
	var $cracha;
	var $pf;
	var $compras;
	var $compras_lista;
	var $salario;
	var $tabela = 'usuario';
	var $line;
    var $line2;
	var $desconto;
	var $desconto_descricao; 
	
	function aniversariantes($mes=1)
		{
			$sql = "select us_nomecompleto, us_cracha, us_dtnasc,
					substr(to_char(us_dtnasc,'00000000'),8,2) as dia
					from ".$this->tabela." 
					where us_status = 'A'
					order by dia ";
			$rlt = db_query($sql);

			$sx = '<table width="100%" class="tabela00">';
			$ndia = 0;			
			$col = 0;
			while ($line = db_read($rlt))
				{
					$size=60;
					$xdia = round(substr($line['us_dtnasc'],6,2));
					$xmes = round(substr($line['us_dtnasc'],4,2));					
					$xnome = trim($line['us_nomecompleto']);
					$cracha = trim($line['us_cracha']);
					if ($mes == $xmes)
						{
						if ($ndia != $xdia)
							{
								$sx .= '<TR class="tabela00" 
											style="border-bottom: 1px solid #000000;">
											<TD colspan=1 class="tabela00" >';
								$sx .= 'Dia '.$xdia;
								$col = 0;
								$ndia = $xdia;
							}
						if (date("d") > $xdia) { $size = "40"; }
						if (date("d") == $xdia) { $size = "80"; }
						if (date("d") < $xdia) { $size = "60"; }
						if ($col > 0)
							{ $sx .= '<TR><TD>&nbsp;'; }
						$sx .= '<TD width="10">';
						$sx .= $this->mostra_foto($cracha,$size);
						$sx .= '<TD width="*" class="tabela01">';
						$sx .= $cracha.'<BR>'.$xnome;
						$col++;
						}
				}
				$sx .= '</table>';
				return($sx);		
		}
	
	function mostra_foto($cracha,$height=60)
		{
			$sx = '<img src="/fonzaghi/img/foto/NOIMAGE.JPG" height="'.$height.'">';
			$file = array();
			array_push($file,'img/foto/'.$cracha.'.JPG');
			
			for ($r=0;$r < count($file);$r++)
				{
					if (file_exists($file[$r]))
						{
							$sx = '<img src="/fonzaghi/'.$file[$r].'" height="'.$height.'">';
							return($sx);
						}
				}
			
			
			return($sx);
		}
	function mostra()
		{
		global $http_public;
		$line = $this->line;
		$nome = $line['us_nomecompleto'];

		$nome = $line['us_nomecompleto'];
		$cpf = $line['us_cpf'];
		$rg = $line['us_rg'];
		$ct = $line['us_ct'];
		$pis = $line['us_pis'];
		$dta = $line['us_dtadmissao'];
		$dtd = $line['us_dtdemissao'];
		$cracha = $line['us_cracha'];
		$login = $line['us_login'];
		$sexo = $line['us_sexo'];
		$cargo = $line['us_cargo'];
		$funcao = $line['us_funcao'];
		$pai = $line['us_nomepai'];
		$mae = $line['us_nomemae'];
		
		$bairro = $line['us_bairro'];
		$cidade = $line['us_cidade'];	
		$estado = $line['us_estado'];
		$cep = $line['us_cep'];
		$dtd = $line['us_dtdemissao'];
		$dtd = $line['us_dtdemissao'];
		$ndep = $line['us_ndep'];
		$estc = $line['us_estadocivil'];
		$emp = $line['us_empresa'];
		$fon1 = $line['us_fone1'];
		$fon2 = $line['us_fone2'];
		$fon3 = $line['us_fone3'];
		$nasc = $line['us_dtnascimento'];		
		$endereco = $line['us_endereco'];	
		
		$desconto_descricao = $line['us_desconto_regular'];
		$desconto = $line['us_desconto_regular_vlr'];
		
		if ($dtd = '1900-01-01') { $dtd = ''; }
		
		$img = trim($line['us_cracha']).'.JPG';
		$dir_img = '../img/foto/'.$img;
		if (!(file_exists($dir_img))) 
			{
			$img = trim($line['us_cracha']).'.jpg';
			$dir_img = '../img/foto/'.$img;
			}
		if (!(file_exists($dir_img)))
				{ 
				$img = '99998.JPG';
				}
	
		$img = '<img src="../img/foto/'.trim($this->line['us_cracha']).'.JPG" height="150"></A>';

	$sx .= '<TABLE width="'.$tab_max.'>" class="lt1">
			<TR><TD><?=$emp?></TD></TR>
			<TR><TD>
			<fieldset><legend><B>Dados Profissionais</B></legend>
			<TABLE width="100%" class="lt1">
			<TR valign="top"><TD width="20%" align="right"><NOBR>Nome completo</TD>
			<TD colspan="3" class="lt3"><B>'.$nome.'</B></TD>
			<TD width="120" rowspan="12" align="center">
			'.$img.'<NOBR>
			</TD>
			</TR>
			<TR><TD width="20%" align="right"><NOBR>Login</TD><TD><B>'.$login.'</B></TD>
			<TD width="20%" align="right"><NOBR>Cracha</TD><TD width="20%"><B>'.$cracha.'</B></TD>
			<TR><TD width="20%" align="right"><NOBR>Cargo</TD><TD><B>'.$cargo.'</B></TD>
			<TD width="20%" align="right"><NOBR>Função</TD><TD width="20%"><B>'.$funcao.'</B></TD>
			<TR><TD width="20%" align="right"><NOBR>CPF</TD><TD><B>'.$cpf.'</B></TD>
			<TD width="20%" align="right"><NOBR>RG</TD><TD width="20%"><B>'.$rg.'</B></TD>
			<TR><TD width="20%" align="right"><NOBR>C.T.</TD><TD><B>'.$ct.'</B></TD>
			<TD width="20%" align="right"><NOBR>PIS</TD><TD><B>'.$pis.'</B></TD>
			<TR><TD width="20%" align="right"><NOBR>Nascimento</TD><TD><B>'.$nasc.'</B></TD>
			<TD width="20%" align="right"><NOBR>Admissão</TD><TD><B>'.$dta.'</B></TD>
			</TABLE>
			</fieldset>';		
		return($sx);
		}
	
	function le($id='',$cracha='',$cargo='',$lj='')
		{
			$sql = "select * from ".$this->tabela." 
					where us_login = '".$id."' 
					and us_status = 'A'
					";
			$ant=0;
			
			if(strlen($cracha)!=0  || strlen($cargo)!=0 || strlen($lj)!=0)
			{		
    			$sql = "select * from ".$this->tabela." where "; 
    			if(strlen($cracha)!=0)
    			{
                    if($ant!=0){$tx.=" and ";}    
        			$tx .= " us_cracha = '$cracha' ";
        			$ant=1; 
                }
                
                if(strlen($cargo)!=0)
                {
                    if($ant!=0){$tx.=" and ";}    
                    $tx .= " us_cargo_avaliacao='$cargo' ";
                    $ant=1;
                }
                if(strlen($lj)!=0)
                {
                    if($ant!=0){$tx.=" and ";}    
                    $tx .= " us_loja='$lj' ";
                    $ant=1;
                }
                $sql.= $tx." and us_status = 'A' ";
                $rlt = db_query($sql);
                while($line = db_read($rlt))
                {
                    $j++;
                    $this->line2[$j] = $line;
                    $this->line = $line;
                }
                return(1);
            }
            
                
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
				}
			return(1);
		}
		function le_id($id='')
		{
			$sql = "select * from ".$this->tabela." 
					where id_us = '".$id."' 
					and us_status = 'A'
					";
                $rlt = db_query($sql);
                while($line = db_read($rlt))
                {
                    $j++;
                    $this->line2[$j] = $line;
                    $this->line = $line;
                }
                return(1);
            }
     
	
	function cp_lanca()
		{
			global $dd,$ip;
			$cp = array();
			array_push($cp,array('$H8','id_pl','',False,True));
			array_push($cp,array('$S5','pl_codfun','Funcionário	',True,True));
			array_push($cp,array('$H8','pl_data','',False,True));
			array_push($cp,array('$O SAL:Salário','pl_cod','Código',True,True));
			array_push($cp,array('$S40','pl_descricao','Descrição',True,True));
			
			array_push($cp,array('$N8','pl_valor','Valor',True,True));
			array_push($cp,array('$HV','pl_terminal',$ip,False,True));
			array_push($cp,array('$H8','pl_log','',False,True));
			array_push($cp,array('$U8','pl_datalanca','',False,True));
			return($cp);
		}
	function lista_lanca($cracha='0')
	{
		if (date("d") > 12)
		{
			$dtp = date("Ym")+1;
			if (date("m") == 12) {
				$dtp = (date("Y")+1).'01'; 
			}
		} else {
			$dtp = date("Ym");
		}
		$sql = "select * from usuarios_planilha where pl_codfun = '$cracha' and pl_data = $dtp ";
		$rlt = db_query($sql);
		$sx .='<center><table><tr><th class="tabelaTH" width="40%" align="left">Descrição</th>
								  <th class="tabelaTH" width="10%" align="center">Valor</th>
								  <th class="tabelaTH" width="5%" align="center">Editar</th>';
		while($line=db_read($rlt))
		{
			$sx.='<tr>';
			$sx.='<td class="tabela00" align="left">'.$line['pl_descricao'].'</td>';
			$sx.='<td class="tabela00" align="center">'.$line['pl_valor'].'</td>';
			$sx.='<td class="tabela00" align="center">
				<A HREF="rel_funcionario_folha_pop.php?&dd0='.$line['id_pl'].'">
				<img src="../img/icone_editar.gif"></a></td>';
			$sx.='</tr>';
			
		}
		$sx.='</table>';
		return($sx);
	}

	function fmtnr($vl)
		{
			$sig = '';
			if ($vl < 0) { $vl = $vl * (-1); $sig = ''; }
			$vl = trim(number_format(round('0'.($vl*100)/100),2,',','.'));
			while (strlen($vl) < 12) { $vl = ' '.$vl; }
			return($vl.$sig);
		}
		
	function cp_planilha()
		{
			global $dd,$ip;
			$cp = array();
			$fl = date("Ym").':'.date("Ym");
			array_push($cp,array('$H8','id_pl','',False,False));
			array_push($cp,array('$H8','pl_data','',True,True));
			array_push($cp,array('$H8','pl_codfun','',True,True));
			array_push($cp,array('$N8','pl_valor','Valor',True,True));
			array_push($cp,array('$S40','pl_descricao','Descrição',True,True));
			array_push($cp,array('$O : '.$fl,'pl_data','Folha',True,True));
			array_push($cp,array('$HV','pl_log','',True,True));
			array_push($cp,array('$HV','pl_terminal',$ip,True,True));
			array_push($cp,array('$U8','pl_dtcompra','',True,True));
			array_push($cp,array('$HV','pl_horacompra','',True,True));
			array_push($cp,array('$U8','pl_datalanca','',True,True));
			array_push($cp,array('$O '.$op,'pl_cod','Tipos',True,True));
			return($cp);				
			
		}	
	function planilha_usuario($codfun='',$data=19000101)
		{
			$data = substr($data,0,6);
			$data01 = $data.'00';
			$data02 = $data.'99';
			$sql = "select * from usuarios_planilha where pl_codfun = '$codfun' and pl_data = $data ";
			$rlt = db_query($sql);
			$sal = 0;
			while ($line = db_read($rlt))
				{
					$vlr = $line['pl_valor'];
					$tp = trim($line['pl_cod']);
					if ($tp == 'SAL') { $sal = $sal + $vlr; }
				}
			$this->salario = $sal;
			
			/* Compras */
			$sql = "select * from usuario_compras where uc_cracha = '$codfun' and (us_venc >= $data01 and us_venc <= $data02) ";
			$rlt = db_query($sql);
			$com = 0;
			$compras_lista = '';
			while ($line = db_read($rlt))
				{
					$vlr = $line['us_valor_parcela'];
					$com = $com + $vlr;
					$compras_lista .= stodbr($line['us_data']);
					$compras_lista .= ' ';
					$compras_lista .= $line['us_loja'];
					$compras_lista .= ' ';
					$compras_lista .= $line['us_doc'];
					$compras_lista .= ' ';
					$compras_lista .= $this->fmtnr($line['us_valor_parcela']);
					$compras_lista .= ' ';
					$compras_lista .= $line['us_documento'];
					$compras_lista .= '<BR>';
				}
			$this->compras_lista = $compras_lista;
			$this->compras = $com;
			
			$this->desconto_descricao = '';
			$this->desconto = 0;

			$sql = "select * from usuario where us_cracha = '".$codfun."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->desconto_descricao = $line['us_desconto_regular'];
					$this->desconto = $line['us_desconto_regular_vlr'];
				}
			return(1);
		}
	function planilha()
		{
			global $tab_max;
			if (date("d") > 12)
				{
				$dtp = date("Ym")+1;
				if (date("m") == 12) { $dtp = (date("Y")+1).'01'; }
				} else {
					$dtp = date("Ym");
				}
			$this->planilha_usuario($this->cracha,$dtp);

			$sal = $this->fmtnr($this->salario);
			$out = $this->fmtnr($this->pf);
			$atr = $this->fmtnr($this->vales);
			$com = $this->fmtnr($this->compras);
			$val = $this->fmtnr($this->vale);
			$deb = $this->fmtnr($this->vales);
			$sub = ($this->salario + $this->pf) - ($this->vale) - ($this->compras);
			$sub = $sub - round(100*$this->desconto)/100;
			if ($sub >=0) { $sg = '+'; } else { $sg = '-'; }
			$sub = $this->fmtnr($sub);
			
			$page = page();
			$page = troca($page,'.php','_pop@php');
			$page = troca($page,'@','.');
			$page .= '?dd1='.$this->cracha.'&dd2='.$dtp.'&dd3=SAL&dd4=Salário';
			$link = '<A HREF="javascript:newxy2(\''.$page.'\',600,600);">';
			
			$sx .= '<table width="'.$tab_max.'" border=1>';
			$sx .= '<TR><TD colspan=3><TT><B>';
			$sx .= $this->cracha;
			$sx .= ' ';
			$sx .= $this->nome;
			
			$sx .= '<TR valign="top">';
			$sx .= '<TD width="66%"><PRE>';
			$sx .= $this->compras_lista;
			$def = 0;
			if ($this->desconto > 0)
				{
					$sx .= substr($this->desconto_descricao,0,50);
					$sx .= number_format($this->desconto,2,',','.');
				}
			$sx .= '</pre>';
			$def = number_format($this->desconto,2,',','.');
			while (strlen($def) < 12) { $def = ' '.$def; }
			
			$sx .= '<TD width="33%">';			
			$sx .= '<PRE>';
			$sx .= "Salários....:$link $sal</A>+<BR>";
			$sx .= "Outros......: $out+<BR>";
			$sx .= "Atrasos/H.E.: $atr <BR>";
			$sx .= "Vales.......: $val-<BR>";
			$sx .= "Compras.....: $com-<BR>";
			$sx .= "Extornos....: $deb+<BR>";
			$sx .= "Débito mens.: $def-<BR>";
			$sx .= "<B>Sub-Total...: $sub$sg</B> </PRE>";
			$sx .= '<TR><TD colspan=3 class=lt2 align="right">
						<TT>Total.......:___________________';
			$sx .= '<TR><TD colspan=3>';
			$sx .= '<HR width="50%" size=1>';
			$sx .= '</table>';
			return($sx);
		}
	function mostra_dados_simples()
		{
			$cod = $this->cracha;
			$sql = "select * from usuario where us_cracha = '$cod' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
			{
			$nome = $line['us_nomecompleto'];
			$cpf = $line['us_cpf'];
			$rg = $line['us_rg'];
			$ct = $line['us_ct'];
			$pis = $line['us_pis'];
			$dta = $line['us_dtadmissao'];
			$dtd = $line['us_dtdemissao'];
			$cracha = $line['us_cracha'];
			$login = $line['us_login'];
			$sexo = $line['us_sexo'];
			$cargo = $line['us_cargo'];
			$funcao = $line['us_funcao'];
			$pai = $line['us_nomepai'];
			$mae = $line['us_nomemae'];
			
			$bairro = $line['us_bairro'];
			$cidade = $line['us_cidade'];	
			$estado = $line['us_estado'];
			$cep = $line['us_cep'];
			$dtd = $line['us_dtdemissao'];
			$dtd = $line['us_dtdemissao'];
			$ndep = $line['us_ndep'];
			$estc = $line['us_estadocivil'];
			$emp = $line['us_empresa'];
			$fon1 = $line['us_fone1'];
			$fon2 = $line['us_fone2'];
			$fon3 = $line['us_fone3'];
			$nasc = $line['us_dtnascimento'];		
			$endereco = $line['us_endereco'];	
			if ($dtd = '1900-01-01') { $dtd = ''; }
			
			$img = trim($line['us_cracha']).'.JPG';
			$dir_img = '../img/foto/'.$img;
			if (!(file_exists($dir_img))) 
				{
				$img = trim($line['us_cracha']).'.jpg';
				$dir_img = '../img/foto/'.$img;
				}
				
			$sx = "<TABLE width=100% class=lt1>
					<TR><TD>$emp</TD></TR>
					<TR><TD>
					<fieldset><legend><B>Dados Profissionais</B></legend>
					<TABLE width=100% class=lt1 >
					<TR valign=top ><TD width=20% align=right><NOBR>Nome completo</TD>
					<TD colspan=3 class=lt3><B>$nome</B></TD>
					<TD width=120 rowspan=12 align=center>$img<NOBR>
					<BR><font class=lt0><font color=silver>small</A></font></font>
					&nbsp;&nbsp;&nbsp;
					</TD>
					</TR>
					<TR><TD width=20% align=right><NOBR>Login</TD><TD><B>$login</B></TD>
					<TD width=20% align=right><NOBR>Cracha</TD><TD width=20%><B>$cracha</B></TD>
					<TR><TD width=20% align=right><NOBR>Cargo</TD><TD><B>$cargo</B></TD>
					<TD width=20% align=right><NOBR>Função</TD><TD width=20%><B>$funcao</B></TD>
					<TR><TD width=20% align=right><NOBR>CPF</TD><TD><B>$cpf</B></TD>
					<TD width=20% align=right><NOBR>RG</TD><TD width=20%><B>$rg</B></TD>
					<TR><TD width=20% align=right><NOBR>C.T.</TD><TD><B>$ct</B></TD>
					<TD width=20% align=right><NOBR>PIS</TD><TD><B>$pis</B></TD>
					<TR><TD width=20% align=right><NOBR>Nascimento</TD><TD><B>$nasc</B></TD>
					<TD width=20% align=right><NOBR>Admissão</TD><TD><B>$dta</B></TD>
					</TABLE>";
				return($sx);
				}	
					
		}
	function produtos_comprados($d1,$d2)
		{
			global $loja;
			$sql = "select * from produto_estoque ";
			$sql .= " inner join produto on pe_produto = p_codigo ";
			$sql .= " where pe_cliente = 'F".trim($this->cracha)."' ";
			$sql .= " and pe_lastupdate >= ".$d1;
			$sql .= " and pe_lastupdate <= ".$d2;
			$sql .= " order by pe_lastupdate desc ";
			$rlt = db_query($sql);
			$top = 0;
			while ($line = db_read($rlt))
				{
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>'.$loja.'</TD>';
				$sx .= '<TD>'.stodbr($line['pe_lastupdate']).'</TD>';
				$sx .= '<TD>'.$line['pe_tam'].'</TD>';
				$sx .= '<TD align="right">'.number_format($line['pe_vlr_vendido'],2).'</TD>';
				$sx .= '<TD>'.$line['p_ean13'].'</TD>';
				$sx .= '<TD align="left">'.$line['p_descricao'].'</TD>';
				$sx .= '</TR>';
				$tot = $tot + $line['pe_vlr_vendido'];
				$top = $top + $line['pe_vlr_vendido'];
				}
			if ($top > 0)
			{
				$sx .= '<TR><TD colspan="5" align="right" class="lt1"><B>Sub-total '.number_format($top,2).'</B></TD></TR>';
			}
			return($sx);
		}
	
	function cp()
		{
			$cp = array();
			//$grau = "1 GRAU:1º GRAU&2 GRAU:2 GRAU&3§GRAU COM:3§GRAU COM";
			array_push($cp,array('$H8','id_us','Login',False,True,''));
			array_push($cp,array('$S5','us_cracha','Nº cracha',True,True,''));
			array_push($cp,array('$S100','us_nomecompleto','Nome Completo',True,True,''));
			array_push($cp,array('$O M:Masculino&F:Feminino','us_sexo','Sexo',False,True,''));

			array_push($cp,array('$A8','','Login e senha',False,True,''));
			array_push($cp,array('$S20','us_login','Login',False,True,''));
			array_push($cp,array('$P20','us_senha','Senha',False,True,''));

			array_push($cp,array('$A8','','Endereço e contato',False,True,''));
			array_push($cp,array('$S70','us_endereco','Endereço',False,True,''));
			array_push($cp,array('$S20','us_bairro','Bairro',False,True,''));
			array_push($cp,array('$S20','us_cidade','Cidade',False,True,''));
			array_push($cp,array('$S2','us_estado','Estado',False,True,''));
			array_push($cp,array('$S10','us_cep','CEP',False,True,''));
			array_push($cp,array('$S20','us_fone1','Fone Residencial',False,True,''));
			array_push($cp,array('$S20','us_fone2','Fone Recado',False,True,''));
			array_push($cp,array('$S20','us_fone3','Celular',False,True,''));


			array_push($cp,array('$H5','us_last_hora','us_last_hora',False,True,''));
			array_push($cp,array('$D8','us_dtadm','Dt. Admissão',False,True,''));
			array_push($cp,array('$D8','us_dtdem','Dt. Demissão',False,True,''));
			array_push($cp,array('$D8','us_dtnasc','Dt. Nascimento',False,True,''));

			array_push($cp,array('$A8','','Contratação',False,True,''));
			array_push($cp,array('$Q e_nome:e_codigo:select * from empresa  where e_rh=1  order by e_nome','us_empresa','Empresa',False,True,''));
			array_push($cp,array('$O A:Ativo&B:Desligado(a)','us_status','Estatus',True,True,''));
			array_push($cp,array('$S30','us_funcao','Função',False,True,''));

			array_push($cp,array('$A8','','Atribuição da função na empresas',False,True,''));
			array_push($cp,array('$Q e_nome:e_codigo:select * from empresa where e_cargo=1 order by e_nome','us_loja','Loja',False,True,''));
			array_push($cp,array('$Q dp_descricao:dp_cod:select * from departamento order by dp_descricao','us_departamento','Departamento',False,True,''));
			array_push($cp,array('$Q car_nome:car_cod:select * from cargos where car_ativo=1 order by car_nome','us_cargo_avaliacao','Cargo/Atividade',False,True,''));


			array_push($cp,array('${','','Vencimentos',False,True,''));
			array_push($cp,array('$N8','us_salario','Salário',False,True,''));
			array_push($cp,array('$N8','us_outros','Outros',False,True,''));
			array_push($cp,array('$N8','us_vale','Vale',False,True,''));
			array_push($cp,array('$}','','Vencimentos',False,True,''));
			
			array_push($cp,array('${','','Limites de crédito',False,True,''));
			array_push($cp,array('$N8','us_credito','Limite de crédito',True,True,''));
			array_push($cp,array('$}','','Vencimentos',False,True,''));

			array_push($cp,array('${','','Descontos Regulares',False,True,''));
			array_push($cp,array('$S100','us_desconto_regular','Descrição',False,True,''));
			array_push($cp,array('$N8','us_desconto_regular_vlr','Valor Mensal',True,True,''));
			array_push($cp,array('$}','','Descontos Regulares',False,True,''));

			array_push($cp,array('$A8','','Documentos',False,True,''));
			array_push($cp,array('$S15','us_cpf','CPF',False,True,''));
			array_push($cp,array('$S15','us_rg','RG',False,True,''));
			array_push($cp,array('$S15','us_ct','Carteira Trabalho',False,True,''));
			array_push($cp,array('$H8','us_sta','us_sta',False,True,''));
			array_push($cp,array('$S15','us_pis','Nº PIS',False,True,''));
			array_push($cp,array('$S80','us_nomepai','Nome pai',False,True,''));
			array_push($cp,array('$S80','us_nomemae','Nome mae',False,True,''));

			//array_push($cp,array('$O '.$grau,'us_instrucao','Gráu de instrucao',False,True,''));
			array_push($cp,array('$S20','us_instrucao','Gráu de instrucao',False,True,''));
			array_push($cp,array('$S20','us_estadocivil','Estado civil',False,True,''));
			array_push($cp,array('$S5','us_ndep','Nº dependentes',False,True,''));
			array_push($cp,array('$S3','us_vt','Vale transporte',False,True,''));
			array_push($cp,array('$S3','us_vt2','Vale transporte 2',False,True,''));
			array_push($cp,array('$S3','us_vr','Vale refeição',False,True,''));
			array_push($cp,array('$S15','us_cbo','Nº CBO',False,True,''));
		
			array_push($cp,array('$[0-9]','us_nivel','Nivel',False,True,''));

			array_push($cp,array('$S70','us_obs1','OBS1',False,True,''));
			array_push($cp,array('$S70','us_obs2','OBS2',False,True,''));
			array_push($cp,array('$S70','us_obs3','OBS3',False,True,''));
			return($cp);			
		}

	function perfil_funcionario($lj='',$dp='',$cargo='',$ano='',$mes=''){
		global $dd;
		if (trim($dd[1])==04) {} else {$tx .= ' and us_loja like \'%'.$dd[1].'%\'';}
		if (strlen(trim($dd[2]))==0) {} else {$tx .= ' and us_departamento like \'%'.$dd[2].'%\'';}
		if (strlen(trim($dd[3]))==0) {} else {$tx .= ' and us_cargo_avaliacao like \'%'.$dd[3].'%\'';}
		
		
		
		$sql = "select *, us_dtadm as data from usuario where  ";
		$sql .= " us_status ='A' ".$tx;
		$sql .= " order by us_nomecompleto ";
		$pra = db_query($sql);
		
		$ss='<FONT FACE="Tahoma, Geneva, sans-serif" size=7  color=#000000>'.nomemes($dd[1]);
		$ss.='<TABLE width="100%" class="lt1" cellpadding=0 cellspacing=0>';
		$ss.='<TR>';
		$ss.='<TD colspan="2" width="33%"><img src="../images/nadap.gif" width=1 height=1 border=0></TD>';
		$ss.='<TD colspan="2" width="33%"><img src="../images/nadap.gif" width=1 height=1 border=0></TD>';
		$ss.='<TD colspan="2" width="33%"><img src="../images/nadap.gif" width=1 height=1 border=0></TD>';
		$ss.='</TR>';
		$col=0;
		while ($line = db_read($pra))
			{
				
				if (($col==0) or ($col==3))
					{	
					$ss=$ss."<TR><TD>&nbsp;</TD></TR>";
					$ss=$ss.'<TR><TD colspan=9 bgcolor="#000000"><img src="../images/nadap.gif" width=1 height=1 border=0></TD></TR>';
					//$ss=$ss.'<TR valign="top"><img src="../images/nadap.gif" width=1 height=1 border=0></TD></TR>';
					$col=0;
					}
				$nome=nbr_autor(trim($line['us_nomecompleto']),7);
				$nnome='';
				$xop=0;
				
				/* Fotografia do funcionario */
				$img = '../img/foto/'.$line["us_cracha"].'A.JPG';
				if (file_exists($img))
					{
					$img = '../img/foto/'.$line["us_cracha"].'A.JPG';
					} else {
					$img = '../img/foto/NOIMAGE.JPG';
					}
				$col = $col + 1;
				$ss.='<TD width="50"><img src="'.$img.'" width=100 border=0></TD>';
				$ss.="<TD>";
				$ss.='<FONT FACE="Tahoma, Geneva, sans-serif" size=3 color=#808080>Admissão ';
				//$ss.=substr(stodbr(sonumero($line['us_dtadm'])),0,5).'<BR>';
				$ss.=stodbr(sonumero($line['us_dtadm'])).'<BR>';
				$ss.='<FONT FACE="Tahoma, Geneva, sans-serif" size=2 color=#000000>';
				//$ss.='<B>'.$anos.' ano(s)</B><BR>';
				$ss.='<B>'.$nome.'</B><BR>';
				$ss.= $line['us_cracha'];
				$ss.='</TD>';
			}
		$ss=$ss.'<TR><TD colspan="9" bgcolor="#000000">';
		$ss=$ss.'<img src="../images/nadap.gif" width=1 height=1 border=0></TD></TR>';
		
		$ss=$ss."</TABLE>";
		
		return($ss);
	}

	function perfil_admissao_demissao($loja='',$dpto='',$adm='',$inicio='',$fim=''){
		global $dd;
		switch($adm)
		{
			case 0:
				//admissao
				$adm = ' us_dtadm ';
				$st='A';
				$tit='<center><h2>Admissões<h2></center>';
				break;
			case 1:
				//demissao
				$adm = ' us_dtdem ';
				$st='B';
				$tit='<center><h2>Demissões<h2></center>';
				break;
		}
		if (trim($dd[1])==04) {} else {$tx .= ' and us_empresa like \'%'.$dd[1].'%\'';}
		if (strlen(trim($dd[2]))==0) {} else {$tx .= ' and us_departamento like \'%'.$dd[2].'%\'';}
		if(strlen($inicio)>0)
		{
			$tx .= ' and '.$adm.'>='.$inicio;
		}
		if(strlen($fim)>0)
		{
			$tx .= ' and '.$adm.'<='.$fim;
		}
		
		$sql = "select *, ".$adm." as data from usuario where  ";
		$sql .= " us_status ='".$st."' ".$tx;
		$sql .= " order by us_nomecompleto ";
		$pra = db_query($sql);
		
		$ss='<FONT FACE="Tahoma, Geneva, sans-serif" size=7  color=#000000>'.nomemes($dd[1]);
		$ss=$tit;
		$ss.='<TABLE width="100%" class="lt1" cellpadding=0 cellspacing=0>';
		$ss.='<TR>';
		$ss.='<TD colspan="2" width="33%"><img src="../images/nadap.gif" width=1 height=1 border=0></TD>';
		$ss.='<TD colspan="2" width="33%"><img src="../images/nadap.gif" width=1 height=1 border=0></TD>';
		$ss.='<TD colspan="2" width="33%"><img src="../images/nadap.gif" width=1 height=1 border=0></TD>';
		$ss.='</TR>';
		$col=0;
		while ($line = db_read($pra))
			{
				
				if (($col==0) or ($col==3))
					{	
					$ss=$ss."<TR><TD>&nbsp;</TD></TR>";
					$ss=$ss.'<TR><TD colspan=9 bgcolor="#000000"><img src="../images/nadap.gif" width=1 height=1 border=0></TD></TR>';
					//$ss=$ss.'<TR valign="top"><img src="../images/nadap.gif" width=1 height=1 border=0></TD></TR>';
					$col=0;
					}
				$nome=nbr_autor(trim($line['us_nomecompleto']),7);
				$nnome='';
				$xop=0;
				
				/* Fotografia do funcionario */
				$img = '../img/foto/'.$line["us_cracha"].'A.JPG';
				if (file_exists($img))
					{
					$img = '../img/foto/'.$line["us_cracha"].'A.JPG';
					} else {
					$img = '../img/foto/NOIMAGE.JPG';
					}
				$col = $col + 1;
				$ss.='<TD width="50"><img src="'.$img.'" width=100 border=0></TD>';
				$ss.="<TD>";
				$ss.='<FONT FACE="Tahoma, Geneva, sans-serif" size=2 color=#808080>Admissão '.stodbr(sonumero($line['us_dtadm']));
				$ss.='<br><FONT FACE="Tahoma, Geneva, sans-serif" size=2 color=#808080>Demissão '.stodbr(sonumero($line['us_dtdem']));
				$ss.='<br><FONT FACE="Tahoma, Geneva, sans-serif" size=2 color=#000000>';
				//$ss.='<B>'.$anos.' ano(s)</B><BR>';
				$ss.='<br><B>'.$nome.'</B><BR>';
				$ss.='<br><FONT FACE="Tahoma, Geneva, sans-serif" size=2 color=#000000>'. $line['us_cracha'];
				$ss.='</TD>';
			}
		$ss=$ss.'<TR><TD colspan="9" bgcolor="#000000">';
		$ss=$ss.'<img src="../images/nadap.gif" width=1 height=1 border=0></TD></TR>';
		
		$ss=$ss."</TABLE>";
		
		return($ss);
	}
    function le_login($login='')
    {
        $sql = "select * from usuario
                where us_login='".$login."'";
        $rlt = db_query($sql);
        while($line=db_read($rlt))
        {
           $this->line=$line;
        }
        return($line);
    }
    function lista_funcionario()
    {
           $sql = "select * from ".$this->tabela." 
                   where us_status = 'A'
                   order by us_nomecompleto
                   ";
                
           $rlt = db_query($sql);
            
           $op = '::Selecione o funcionário::';
            while ($line = db_read($rlt)) 
            {
                $cracha=trim($line['us_cracha']);
                $nome=trim($line['us_nomecompleto']);
                $op .= '&'.$cracha.':'.$nome;
            }
            
            $this->op_funcionarios=$op;
            return($op);
    }
			
}
?>
