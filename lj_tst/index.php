<?
$breadcrumbs=array();
array_push($breadcrumbs, array('index.php','Loja'));

$include = '../';
require("../cab_novo.php");
require("db_temp.php");
?>
<img src="img/logo_empresa.png" alt="" border="0" align="right">
<h1>Menu Principal</h1>
<h2>Loja - <?=$nloja_nome;?></h2>
<?

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';
$menu = array();
if ($perfil->valid('#GEG#GER#ADM#SSS#CCC'))
{
	array_push($menu,array('Gest�o dos Kits','Troca de titular do kits','kit_troca_cliente.php'));
	array_push($menu,array('Gest�o dos Kits','Acompanhamento de Acertos','acertos_periodo.php'));
	array_push($menu,array('Gest�o dos Kits','__Clientes Novas','clientes_novas_fornecidas.php'));
}

if ($perfil->valid('#GEG#GER#ADM'))
	{
	array_push($menu,array('Gest�o dos Kits','Tempo m�dio de permanencia dos Kits','acertos_tmpk.php'));
	}

array_push($menu,array('Produtos','Cadastro de produtos','produto.php'));

array_push($menu,array('Classifica��o','Classifica��o de produtos','produto_grupos.php'));
array_push($menu,array('Classifica��o','__Atributos do produto','categorizacao_grupos.php')); 
 
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('Consignados','Tabela de produtos consignados','fornecimentos.php')); 
array_push($menu,array('Consignados','Consigna��es do per�odo','revendedoras.php')); 
array_push($menu,array('Consignados','Data de acerto','revendedoras_previsao_acertos.php'));
if ($nloja=='J')
	{
		array_push($menu,array('Consignados','Alterar comiss�o','comissao_alterar.php'));
	} 

array_push($menu,array('Acertos','TOP�s','revendedoras_top.php')); 
array_push($menu,array('Acertos','Extrato de acerto','rel_pruduto_cliente_acerto.php')); 
array_push($menu,array('Acertos','Relat�rio de acerto','tabela_acerto.php')); 
array_push($menu,array('Acertos','Acertos por per�odo (em aberto)','revendedoras_acertos.php')); 
array_push($menu,array('Acertos','Balancete de Acertos','revendedoras_acertos_balancete.php'));


array_push($menu,array('Calend�rio de acertos','Calend�rio','calendario_de_acertos.php'));


if ($perfil->valid('#GEG#GER#ADM#COJ#COM#COO#COS'))
	{
		array_push($menu,array('Gest�o do Estoque','Produtos mais vendidos','rel_produtos_vendas.php'));
		array_push($menu,array('Gest�o do Estoque','Produtos baixados','rel_produtos_baixados.php'));		
		array_push($menu,array('Gest�o do Estoque','Rastreio de produto(*)','produtos_rastreio.php')); 
		array_push($menu,array('Gest�o do Estoque','Produtos mais consignados(*)','produtos_consignados.php')); 
 
		array_push($menu,array('Gest�o do Estoque','Produtos n�o vendidos(*)','rel_produtos_nao_vendas.php')); 
		array_push($menu,array('Gest�o do Estoque','Produtos mais Consignados/Devolvidos(*)','produtos_consignados_devolvido.php')); 
		array_push($menu,array('Gest�o do Estoque','Produtos para checkin(*)','produtos_checkin.php'));
}  
		array_push($menu,array('Gest�o do Estoque','Produtos para checkin(*)','produtos_checkin.php'));
		
		array_push($menu,array('Produtos','Tabela de Classifica��o','produtos_classificacao_tabela.php')); 
		array_push($menu,array('Produtos','Reprocessar precos','produtos_reprocecar.php')); 
		array_push($menu,array('Produtos','Tabela de produtos com pre�o','rel_produto_preco.php')); 
		array_push($menu,array('Produtos','Tabela de produtos por imagem','estoque_imagens.php'));
	

array_push($menu,array('Etiquetagem','Etiquetagem','etiquetagem.php'));
array_push($menu,array('Etiquetagem Promocional','Etiqueta (Vermelha)','etiqueta_vermelha.php'));  

array_push($menu,array('Gest�o','Alterar dia de acerto','../coordenadoras/cliente.php')); 

array_push($menu,array('Indicadores Orientativos','Efetividade de vendas','indicador_estoque_1.php')); 
array_push($menu,array('Indicadores Orientativos','Alcance do estoque','indicador_estoque_2.php')); 
array_push($menu,array('Indicadores Orientativos','Giro do estoque','indicador_estoque_3.php')); 
array_push($menu,array('Indicadores Orientativos','Posi��o temporaria (pedido)','indicador_estoque_4.php')); 
array_push($menu,array('Indicadores Orientativos','Posi��o temporaria (produto)','indicador_estoque_5.php')); 

array_push($menu,array('Indicadores Orientativos','Vendas por pontos (produto)','indicador_venda_pecas_pts.php'));

array_push($menu,array('Hist�rico','Hist�rico das Consultoras','historico_consultoras.php')); 
array_push($menu,array('Hist�rico','Hist�rico dos Mostru�rios','historico_mostruario.php')); 


array_push($menu,array('Estoque','Posi��o de estoque','estoque_posicao.php'));
array_push($menu,array('Estoque','Posi��o de estoque por data','estoque_posicao_data.php'));
array_push($menu,array('Estoque Externo','Top Consigna��es','estoque_top_consignacao.php')); 

array_push($menu,array('Indicadores de Estoque','Relat�rio de margens de comercializa��o','estoque_rel_1.php'));
array_push($menu,array('Indicadores de Estoque','Relat�rio de vendas x estoque (teste)','estoque_rel_2.php'));
array_push($menu,array('Indicadores de Estoque','Vendas por fornecedor/produto','fornecedor_produto_venda.php'));

array_push($menu,array('Estoque','Estoque atual','tabela_estoque_atual.php')); 
array_push($menu,array('Estoque','Cardex','produtos_estoque_grupo.php')); 
array_push($menu,array('Estoque','Cardex (produtos)','ed_produto.php')); 
array_push($menu,array('Estoque','Estoque consolidado (Grupo)','produtos_estoque_grupo_consolidado.php')); 
array_push($menu,array('Estoque','Posi��o atual do estoque','tabela_estoque_atual.php'));
	 
array_push($menu,array('Estoque','Baixa de estoque de produto danificado/amostra','estoque_baixa.php')); 
if ($perfil->valid("#GER#ADM#GEG"))
	{ 
	array_push($menu,array('Estoque','__Gera��o de senha para baixa de estoque','estoque_senha_gerar.php'));
	}

//array_push($menu,array('Estoque','Inventariar um produto','estoque_inventario.php')); 
array_push($menu,array('Estoque','Invent�rio','')); 
array_push($menu,array('Estoque','__Resumo do invent�rio','inventario_resumo.php'));
array_push($menu,array('Estoque','__Zerar (Iniciar) invent�rio','inventario_start.php'));
array_push($menu,array('Estoque','__Pe�as n�o localizadas','inventario_notfound.php'));
array_push($menu,array('Estoque','Baixar itens n�o localizados','inventario_down.php'));  
//array_push($menu,array('Estoque','__Lista de �tens inventariados','estoque_inventario_item2.php')); 
//array_push($menu,array('Estoque','__Lista pendentes de aprova��o','estoque_inventario_item3.php')); 
//array_push($menu,array('Estoque','__Lista aprovada de invent�rios','estoque_inventario_item4.php')); 

array_push($menu,array('Fornecimento','Quantidade de forneciomentos','fornecimento_periodo.php'));

array_push($menu,array('Estoque','Entrada de mercadoria','estoque_entrada.php'));
array_push($menu,array('Estoque','__Invent�rio f�sico','inventario.php'));

/*Telas utilizadas somente pela J�ias*/
if($nloja=='J')
{
	if ($perfil->valid('#ADM#DIR#MST#CMK#COJ'))
    {
	array_push($menu,array('Audit�ria','FAudit','../ger/mosturarios_faudit.php'));
	array_push($menu,array('Audit�ria','JAudit','../ger/mosturarios_cadastrados.php'));
	array_push($menu,array('Audit�ria','Mostru�rios','rel_mostruarios.php'));
	}
}
	
    
    


///////////////////////////////////////////////////// redirecionamento
if ((isset($dd[1])) and (strlen($dd[1]) > 0))
	{
	$col=0;
	for ($k=0;$k <= count($menu);$k++)
		{
		 if ($dd[1]==CharE($menu[$k][1])) {	header("Location: ".$menu[$k][2]); } 
		}
	}
?>

<TABLE width="710" align="center" border="0">
<TR><TD colspan="4">
<FONT class="lt3">
</FONT><FORM method="post" action="index.php">
</TD></TR>
</TABLE>
<TABLE width="710" align="center" border="0">
<TR>
<?
$tela = menus($menu,"3");
echo $hd->foot();
?>