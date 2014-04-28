<?
/**
* Esta classe seta as lojas.
* @author Willian Fellipe Laynes <willianlaynes@gmail.com>
* @version v.0.13.39
* @copyright Copyright © 2013, Rene F. Gabriel Junior.
* @access public
* @package INCLUDEs
* @subpackage sisdoc_lojas
*/
    function setdp()
    {
        global $setdp;
        
		$setdp = array();
		for ($r=0;$r <= 2;$r++)
			{
				array_push($setdp,array('','','','','','','',''));
			}
        /*siglas lojas*/        
        $setdp[0][0]='J';
        $setdp[0][1]='M';
        $setdp[0][2]='O';
        $setdp[0][3]='C';
        $setdp[0][4]='S';
        $setdp[0][5]='D';
    
        /*nomes lojas*/
        $setdp[1][0]='Jóias';
        $setdp[1][1]='Modas';
        $setdp[1][2]='Óculos';
        $setdp[1][3]='Catálogo';
        $setdp[1][4]='Sensual';
        $setdp[1][5]='Jurídico';
            
        /*siglas tabelas*/
        $setdp[2][0]='duplicata_joias';
        $setdp[2][1]='duplicata_modas';
        $setdp[2][2]='duplicata_oculos';
        $setdp[2][3]='duplicata_usebrilhe';
        $setdp[2][4]='duplicata_sensual';
        $setdp[2][5]='juridico_duplicata';
 
    return(1);
    }
    
    function setlj()
    {
        global $setlj;
        /*siglas lojas*/        
        $setlj[0][0]='J';
        $setlj[0][1]='M';
        $setlj[0][2]='O';
        $setlj[0][3]='UB';
        $setlj[0][4]='S';
        $setlj[0][5]='EXM';
        $setlj[0][6]='EXJ';
    
        /*nomes lojas*/
        $setlj[1][0]='Jóias';
        $setlj[1][1]='Modas';
        $setlj[1][2]='Óculos';
        $setlj[1][3]='Use Brilhe';
        $setlj[1][4]='Sensual';
        $setlj[1][5]='Ex Modas';
        $setlj[1][6]='Ex Jóias';
        
        /*banco de dados lojas*/
        $setlj[3][0]='db_fghi_206_joias.php';
        $setlj[3][1]='db_fghi_206_modas.php';
        $setlj[3][2]='db_fghi_206_oculos.php';
        $setlj[3][3]='db_fghi_206_ub.php';
        $setlj[3][4]='db_fghi_206_sensual.php';
        $setlj[3][5]='db_fghi_206_express.php';
        $setlj[3][6]='db_fghi_206_express_joias.php';
    return(1);
    }

    function setft()
    {
        global $setft;
        
        /*siglas lojas*/        
        $setft[0][0]='J';
        $setft[0][1]='M';
        $setft[0][2]='O';
        $setft[0][3]='C';
        $setft[0][4]='S';
		
        /*nomes lojas*/
        $setft[1][0]='Joias';
        $setft[1][1]='Modas';
        $setft[1][2]='Óculos';
        $setft[1][3]='Catálogo';
        $setft[1][4]='Sensual';
		    
        /*siglas tabelas*/
        $setft[2][0]='duplicata_joias';
        $setft[2][1]='duplicata_modas';
        $setft[2][2]='duplicata_oculos';
        $setft[2][3]='duplicata_usebrilhe';
        $setft[2][4]='duplicata_sensual';
        
        /*banco de dados lojas*/
        $setft[3][0]='db_fghi_206_joias.php';
        $setft[3][1]='db_fghi_206_modas.php';
        $setft[3][2]='db_fghi_206_oculos.php';
        $setft[3][3]='db_fghi_206_ub.php';
        $setft[3][4]='db_fghi_206_sensual.php';
 
    return(1);
    }
	
	/*Com siglas diferentes devido aos db_temp.php*/
	
    function setlj2()
    {
        global $setlj;
        /*siglas lojas*/        
        $setlj[0][0]='J';
        $setlj[0][1]='M';
        $setlj[0][2]='O';
        $setlj[0][3]='U';
        $setlj[0][4]='S';
        $setlj[0][5]='E';
        $setlj[0][6]='G';
    
        /*nomes lojas*/
        $setlj[1][0]='Jóias';
        $setlj[1][1]='Modas';
        $setlj[1][2]='Óculos';
        $setlj[1][3]='Use Brilhe';
        $setlj[1][4]='Sensual';
        $setlj[1][5]='Ex Modas';
        $setlj[1][6]='Ex Jóias';
        
        /*banco de dados lojas*/
        $setlj[3][0]='db_fghi_206_joias.php';
        $setlj[3][1]='db_fghi_206_modas.php';
        $setlj[3][2]='db_fghi_206_oculos.php';
        $setlj[3][3]='db_fghi_206_ub.php';
        $setlj[3][4]='db_fghi_206_sensual.php';
        $setlj[3][5]='db_fghi_206_express.php';
        $setlj[3][6]='db_fghi_206_express_joias.php';
    return(1);
    }
	
	
    function setlj_option()
    {
        $setop_lj='&0:Jóias&1:Modas&2:Óculos&3:Catálogo&4:Sensual&5:Jurídico';
        return($setop_lj);
    }
    function setft_option()
    {
        $setop_lj='&J:Jóias&M:Modas&O:Óculos&C:Catálogo&S:Sensual';
        return($setop_lj);
    }
    
?>
