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
	array_push($menu,array('Gestão dos Kits','Troca de titular do kits','kit_troca_cliente.php'));
	array_push($menu,array('Gestão dos Kits','Acompanhamento de Acertos','acertos_periodo.php'));
	array_push($menu,array('Gestão dos Kits','__Clientes Novas','clientes_novas_fornecidas.php'));
}

if ($perfil->valid('#GEG#GER#ADM'))
	{
	array_push($menu,array('Gestão dos Kits','Tempo médio de permanencia dos Kits','acertos_tmpk.php'));
	}

array_push($menu,array('Produtos','Cadastro de produtos','produto.php'));

array_push($menu,array('Classificação','Classificação de produtos','produto_grupos.php'));
array_push($menu,array('Classificação','__Atributos do produto','categorizacao_grupos.php')); 
 
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('Consignados','Tabela de produtos consignados','fornecimentos.php')); 
array_push($menu,array('Consignados','Consignações do período','revendedoras.php')); 
array_push($menu,array('Consignados','Data de acerto','revendedoras_previsao_acertos.php'));
if ($nloja=='J')
	{
		array_push($menu,array('Consignados','Alterar comissão','comissao_alterar.php'));
	} 

array_push($menu,array('Acertos','TOP´s','revendedoras_top.php')); 
array_push($menu,array('Acertos','Extrato de acerto','rel_pruduto_cliente_acerto.php')); 
array_push($menu,array('Acertos','Relatório de acerto','tabela_acerto.php')); 
array_push($menu,array('Acertos','Acertos por período (em aberto)','revendedoras_acertos.php')); 
array_push($menu,array('Acertos','Balancete de Acertos','revendedoras_acertos_balancete.php'));


array_push($menu,array('Calendário de acertos','Calendário','calendario_de_acertos.php'));


if ($perfil->valid('#GEG#GER#ADM#COJ#COM#COO#COS'))
	{
		array_push($menu,array('Gestão do Estoque','Produtos mais vendidos','rel_produtos_vendas.php'));
		array_push($menu,array('Gestão do Estoque','Produtos baixados','rel_produtos_baixados.php'));		
		array_push($menu,array('Gestão do Estoque','Rastreio de produto(*)','produtos_rastreio.php')); 
		array_push($menu,array('Gestão do Estoque','Produtos mais consignados(*)','produtos_consignados.php')); 
 
		array_push($menu,array('Gestão do Estoque','Produtos não vendidos(*)','rel_produtos_nao_vendas.php')); 
		array_push($menu,array('Gestão do Estoque','Produtos mais Consignados/Devolvidos(*)','produtos_consignados_devolvido.php')); 
		array_push($menu,array('Gestão do Estoque','Produtos para checkin(*)','produtos_checkin.php'));
}  
		array_push($menu,array('Gestão do Estoque','Produtos para checkin(*)','produtos_checkin.php'));
		
		array_push($menu,array('Produtos','Tabela de Classificação','produtos_classificacao_tabela.php')); 
		array_push($menu,array('Produtos','Reprocessar precos','produtos_reprocecar.php')); 
		array_push($menu,array('Produtos','Tabela de produtos com preço','rel_produto_preco.php')); 
		array_push($menu,array('Produtos','Tabela de produtos por imagem','estoque_imagens.php'));
	

array_push($menu,array('Etiquetagem','Etiquetagem','etiquetagem.php'));
array_push($menu,array('Etiquetagem Promocional','Etiqueta (Vermelha)','etiqueta_vermelha.php'));  

array_push($menu,array('Gestão','Alterar dia de acerto','../coordenadoras/cliente.php')); 

array_push($menu,array('Indicadores Orientativos','Efetividade de vendas','indicador_estoque_1.php')); 
array_push($menu,array('Indicadores Orientativos','Alcance do estoque','indicador_estoque_2.php')); 
array_push($menu,array('Indicadores Orientativos','Giro do estoque','indicador_estoque_3.php')); 
array_push($menu,array('Indicadores Orientativos','Posição temporaria (pedido)','indicador_estoque_4.php')); 
array_push($menu,array('Indicadores Orientativos','Posição temporaria (produto)','indicador_estoque_5.php')); 

array_push($menu,array('Indicadores Orientativos','Vendas por pontos (produto)','indicador_venda_pecas_pts.php'));

array_push($menu,array('Histórico','Histórico das Consultoras','historico_consultoras.php')); 
array_push($menu,array('Histórico','Histórico dos Mostruários','historico_mostruario.php')); 


array_push($menu,array('Estoque','Posição de estoque','estoque_posicao.php'));
array_push($menu,array('Estoque','Posição de estoque por data','estoque_posicao_data.php'));
array_push($menu,array('Estoque Externo','Top Consignações','estoque_top_consignacao.php')); 

array_push($menu,array('Indicadores de Estoque','Relatório de margens de comercialização','estoque_rel_1.php'));
array_push($menu,array('Indicadores de Estoque','Relatório de vendas x estoque (teste)','estoque_rel_2.php'));
array_push($menu,array('Indicadores de Estoque','Vendas por fornecedor/produto','fornecedor_produto_venda.php'));

array_push($menu,array('Estoque','Estoque atual','tabela_estoque_atual.php')); 
array_push($menu,array('Estoque','Cardex','produtos_estoque_grupo.php')); 
array_push($menu,array('Estoque','Cardex (produtos)','ed_produto.php')); 
array_push($menu,array('Estoque','Estoque consolidado (Grupo)','produtos_estoque_grupo_consolidado.php')); 
array_push($menu,array('Estoque','Posição atual do estoque','tabela_estoque_atual.php'));
	 
array_push($menu,array('Estoque','Baixa de estoque de produto danificado/amostra','estoque_baixa.php')); 
if ($perfil->valid("#GER#ADM#GEG"))
	{ 
	array_push($menu,array('Estoque','__Geração de senha para baixa de estoque','estoque_senha_gerar.php'));
	}

//array_push($menu,array('Estoque','Inventariar um produto','estoque_inventario.php')); 
array_push($menu,array('Estoque','Inventário','')); 
array_push($menu,array('Estoque','__Resumo do inventário','inventario_resumo.php'));
array_push($menu,array('Estoque','__Zerar (Iniciar) inventário','inventario_start.php'));
array_push($menu,array('Estoque','__Peças não localizadas','inventario_notfound.php'));
array_push($menu,array('Estoque','Baixar itens não localizados','inventario_down.php'));  
//array_push($menu,array('Estoque','__Lista de ítens inventariados','estoque_inventario_item2.php')); 
//array_push($menu,array('Estoque','__Lista pendentes de aprovação','estoque_inventario_item3.php')); 
//array_push($menu,array('Estoque','__Lista aprovada de inventários','estoque_inventario_item4.php')); 

array_push($menu,array('Fornecimento','Quantidade de forneciomentos','fornecimento_periodo.php'));

array_push($menu,array('Estoque','Entrada de mercadoria','estoque_entrada.php'));
array_push($menu,array('Estoque','__Inventário físico','inventario.php'));

/*Telas utilizadas somente pela Jóias*/
if($nloja=='J')
{
	if ($perfil->valid('#ADM#DIR#MST#CMK#COJ'))
    {
	array_push($menu,array('Auditória','FAudit','../ger/mosturarios_faudit.php'));
	array_push($menu,array('Auditória','JAudit','../ger/mosturarios_cadastrados.php'));
	array_push($menu,array('Auditória','Mostruários','rel_mostruarios.php'));
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