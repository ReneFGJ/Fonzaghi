<?php
    /**
     * Transportadoras
	 * @author Willian Fellipe Laynes <willianlaynes@hotmail.com>
	 * @copyright Copyright (c) 2014 - sisDOC.com.br
	 * @access public
     * @version v.0.14.04
	 * @package transportadoras
	 * @subpackage classe
    */
class transportadora
	{
		function le()
		{
			
			return($sx);
		}
		
		function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_trans','id',False,True,''));
			array_push($cp,array('$S40','trans_descricao','Razão Social',False,True,''));
			array_push($cp,array('$S40','trans_nome_fantasia','Nome Fantasia',False,True,''));
			array_push($cp,array('$S18','trans_cnpj','Cnpj',False,True,''));
			array_push($cp,array('$S100','trans_endereco','Endereço',False,True,''));
			array_push($cp,array('$S12','trans_cep','CEP',False,True,''));
			array_push($cp,array('$S20','trans_contato','Contato 1',False,True,''));
			array_push($cp,array('$S20','trans_telefone','Telefone 1',False,True,''));
			array_push($cp,array('$S20','trans_telefone1','Telefone 2',False,True,''));
			array_push($cp,array('$S50','trans_email','e-mail',False,True,''));
			array_push($cp,array('$S50','trans_site','Site',False,True,''));
			array_push($cp,array('$S150','trans_observacoes','Observações',False,True,''));
			array_push($cp,array('$O S:Ativo&N:Inativo','trans_ativo','Status',False,True,''));
			
			return($cp);
		}
		
		  function row()
            {
                global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
                $this->tabela = "transportadoras";
                $tabela = "transportadoras";
                $label = "Cadastro de transportadoras";
                /* Pï¿½ginas para Editar */
                $http_edit = 'ed_transportadora.php'; 
                $offset = 20;
                $order  = "trans_descricao";
	            $cdf = array('id_trans','trans_descricao','trans_nome_fantasia',
                			'trans_cnpj','trans_contato','trans_telefone',
                			'trans_telefone1','trans_email',
                			'trans_site','trans_cep','trans_endereco',
                			'trans_observacoes','trans_ativo');
                $cdm = array('Cod','Razao Social','Nome Fantasia',
                			'Cnpj','Contato 1','Telefone 1',
                			'Telefone 2','E-mail',
                			'Site','CEP','Endereço','Observações',
                			'Status');
                $masc = array('','','',
                			 '','','',
                			 '','','',
                			 '','','',
                			 '','');
                return(True);
            }

	}
?>
