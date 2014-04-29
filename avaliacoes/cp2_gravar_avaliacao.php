<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0c                       18/05/2008 //
// 0.0b                       09/04/2008 //
// 0.0a                                  //
///////////////////////////////////////////

function cp2_gravar()
{
global $acao,$dd,$cp,$tabela;
$saved = 0;
if (!isset($acao) and isset($dd[0]) and (strlen(trim($dd[0]))>0) and (strlen($tabela) > 0) and (strlen($cp[0][1]) > 0)) 
	{
	$sql = "select * from ".$tabela." where ".$cp[0][1]."=".$dd[0];
	$result = db_query($sql);
	
    if ($line = db_read($result))
		{
		for ($k=1;$k<100;$k++)
			{
			$dd[$k]=trim($line[$cp[$k][1]]); 
			if (substr($cp[$k][0],0,2) == '$D')
				{
				$dd[$k] = stodbr($dd[$k]);
				}
			if (substr($cp[$k][0],0,2) == '$N')
				{
				if ($dd[$k] > 1)
					{
					$SS = $dd[$k] * 100;
					$SS = substr($SS,0,strlen($SS)-2).'.'.substr($SS,strlen($SS)-2,2);
					$dd[$k] = $SS;
					}
				else
					{
					$dd[$k] = ($dd[$k]+1) * 100;
					$dd[$k] = '0.'.substr($dd[$k],strlen($dd[$k])-2,2);
					}
				}
			};
		}
	}
	/********************************************************** GRAVAR */
	//if (strlen(trim($dd[1])) > 0) 
	{
	if (isset($acao))
		{
		$ok=1;
		for ($p=1;$p <100;$p++)
			{
			if ($cp[$p][3]==True)
				{
					if (isset($dd[$p]))
					{ 
						if (strlen(trim($dd[$p])) < 1) { $ok=-1; }
						} else { $ok=0; }
				}
	//		echo "<BR>".$cp[$p][5].'='.$cp[$p][6].'='.$p."=[".$dd[$p]."]==>".$ok."==";
			}
		if ($ok==1)
		{
		if (isset($dd[0]) and (strlen($dd[0]) > 0) and (strlen($cp[0][1]) > 0)) 
			{
	//		echo "==gravado";
			$sql = "update ".$tabela." set ";
			$cz=0;
			for ($k=1;$k<100;$k++)
				{
					if ((strlen($cp[$k][1])>0) && ($cp[$k][4]==True))
					{
						if ($cz++>0) {$sql = $sql . ', ';}
						if (substr($cp[$k][0],0,2) == '$D') { $dd[$k] = brtos($dd[$k]); }
						$sql = $sql . $cp[$k][1].'='.chr(39).$dd[$k].chr(39).' ';
					}
				}
			$sql = $sql .' where '.$cp[0][1].'='.$dd[0];
			if (strlen($tabela) >0)
				{ $result = db_query($sql) or die("<P><FONT COLOR=RED>ERR 002:Query failed : " . db_error()); }
	//		$dd[1] = NULL;
			$acao=null;
			$saved=1;
			}
		else
			{
			$sql = "insert into ".$tabela." (";
			$sql2= "";
			$tt=0;
			for ($k=1;$k<100;$k++)
				{
					if (strlen(trim(($cp[$k][1]))))
					{
						if ($tt++ > 0) { $sql = $sql . ', '; $sql1 = $sql1 .', ';}
						$sql = $sql . $cp[$k][1];
						if (substr($cp[$k][0],0,2) == '$D') { $dd[$k] = brtos($dd[$k]); }
						$sql1= $sql1. chr(39).$dd[$k].chr(39);
					}
				}
			$sql = $sql . ') values ('.$sql1.')';
	//		echo $sql;

			if (strlen($tabela) > 0)
			{ $result = db_query($sql); }
//			$dd[1] = null;
			$acao=null;
			$saved=2;
			}
		}
		}
		else
		{
		$saved=-1;
		}
		//echo $sql;
	}
	if ($saved > 0) 
		{ return(true); } else { return(false); }
}
//////////////////////////////////////////////
function gets_fld($capac)
	{
	global $acao,$dd,$cp,$tabela,$estilo,$qtd,$lin;
	$tt='<table cellpadding="0" cellpadding="2" border="0" class="ed" width="100%" '.$estilo.'>';
	$tt.="<TR ".coluna().">";
	$k=0;
	$c=0;
	if (isset($cp[$k]))
		
			{
				
			$tt.=''.gets('dd'.$k,$dd[$k],$cp[$k][0],CharE($cp[$k][2]),$cp[$k][3],$cp[$k][4]);
			
			}
	
	for ($k=1;$k<$qtd;$k++)
		{
			
		if ($k==$q) {$tt.="</TR >";}	
		
		if ((($k-1)%$lin==0 and $k-1!=1)) {$tt.="<TR ".coluna()."><TD>".$capac[$c]."</TD>"; $q=$k+$lin; $c=$c+1;}
			
		if (isset($cp[$k]))
		
			{
				
			$tt.=''.gets('dd'.$k,$dd[$k],$cp[$k][0],CharE($cp[$k][2]),$cp[$k][3],$cp[$k][4]);
			
			}
			
	
	}
	$tt.="</TR>";
	$tt .= '</table>';
	return($tt);
	}
///////////////////////////////////////////////
function editar2($capac)
{
	global $acao,$dd,$cp,$saved,$http_redirect,$tabela,$http_edit,$http_edit_para;$capac;
	$bt = 0;
	$saved = 0;
	for ($k=0;$k < count($cp);$k++)
		{ if (substr($cp[$k][0],0,2) == '$B') { $bt = 1; } }
	if ($bt ==0) { /*array_push($cp,array('$B8','','gravar',False,False,''));*/ }
	if (cp2_gravar())
		{
		$saved = 1;
		if (strlen($http_redirect) > 0)
			{
			header("Location: ".$http_redirect);
			echo 'Stoped'; exit;		
			}
		} else {
			echo '<form id="fl" name="fl" method="post" ';
			if ((strlen($pagina_form) > 0) or (strlen($http_edit) > 0)) { echo 'action="'.$http_edit.$http_edit_para.'"'; }
			echo ' >';
			echo gets_fld($capac);
			echo '</form>';
		}
		return($saved);
}
?>