<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');

	require("../../cfg.php");
	require("../../bd.php");

	$conexao_escola = new conexao();
	$conexao_escola->solicitar("SELECT * FROM Escolas");
	$nomeEscola = '';
	if(0 < $conexao_escola->registros and $conexao_escola->erro == ''){
		$nomeEscola = $conexao_escola->resultado['nome'];
	}
    echo '&nome='.$nomeEscola;
//A partir do fim do php, não escrever absolutamente nada. Nem código.
?>
