<div id="cons04" class="contentbox"></div>	
<?
/* Montagem do Ajax */
if (strlen($pag)==0){ $pag = 1; }
if (strlen($ljx)==0){ $ljx='T'; $lj='Todas as Lojas';}
$lb=$dd[8];
 
 /* Chamada Ajax */
$sx = '
<script>
		/* Ajax Inicial */
		var checkpost="'.checkpost($dd[0].$secu).'";
		$.ajax({
			type: "POST",
			url: "cons_ajax.php",
			data: { dd0:"'.$dd[0].'", dd1: "mensagens", dd2: "1",dd7:"'.$ljx.'",dd8:"'.$lb.'", dd90: checkpost }
		}).done(function( data ) { $("#cons04").html( data ); });
</script>
';

/* Displat Script */
echo $sx;
?>
	
