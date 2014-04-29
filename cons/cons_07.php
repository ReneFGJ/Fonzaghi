<div id="cons07" class="contentbox"></div>	
<?
/* Montagem do Ajax */
if (strlen($pag)==0){ $pag = 1; }
 
 /* Chamada Ajax */
$sx = '
<script>
		/* Ajax Inicial */
		var checkpost="'.checkpost($dd[0].$secu).'";
		$.ajax({
			type: "POST",
			url: "cons_ajax.php",
			data: { dd0:"'.$dd[0].'", dd1: "senff", dd2: "1", dd90: checkpost }
		}).done(function( data ) { $("#cons07").html( data ); });
</script>
';

/* Displat Script */
echo $sx;
?>