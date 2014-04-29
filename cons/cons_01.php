<div id="cons01" class="contentbox">

<?
echo '<table border=0 class="tabela00" cellpadding="0" cellspacing="0" width="900">
		<tr valign="top">
			<td>'.$cons->mostra_dados_pessoais().'<BR>'.
				  /* Mostra icone das mensagens */
				  $messa->mini_msg().''.
				  /* Mostra tabela de cursos */
				  '<table width="100%" border=0 cellpadding=0 cellspacing=0>
				  	<TR valign="top"><TD width="45%">'.$cursos->tabela().'
				  	</TD><TD>'.$ind->link_indicacoes($cliente).'</td><td>'.$meta_medias->mostra_metas($cliente)
				  	.'</table>				  
				  </td> 
			<td align="center">'.$consignado->produtos_consigandos_consultora().'<BR>'.
				  $rel->duplicatas_total().'<BR>'.
				 '<span id="senff_a" style="cursor: pointer;">'.$senff->saldos().'<BR>   </span>
			
			<script>
			$("#senff_a").click(function() {goto(\'#cons07\', this)});
			</script>'.
				  $consignado->creditos_cliente().'
			</td>
		</tr>		
	</table>';
?>							
</div>
