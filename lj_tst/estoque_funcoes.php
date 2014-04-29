<?
function estoque_senha_gerar($param){
	$senha=md5($param);
	return substr($senha, 4, 4).substr($senha, 12, 4);
}

function estoque_senha_confere($senha){
	global $nloja;
	$chave='fghi';	
	
	$senhaG=md5(date('Ymd').$chave.$nloja); //senha gerada
	$senhaG=substr($senhaG, 4, 4).substr($senhaG, 12, 4);

	if ($senhaG == $senha){
		return 1;
	}

	return 0;
}

function inventarioSenhaGerar($codigo){
	$senha=md5($codigo);
	return substr($senha, 4, 4).substr($senha, 12, 4);
}

function inventarioSenhaConfere($codigo, $senha){
	$senhaG=md5($codigo); //senha gerada
	$senhaG=substr($senhaG, 4, 4).substr($senhaG, 12, 4);

	if ($senhaG == $senha){
		return 1;
	}

	return 0;
}
?>
