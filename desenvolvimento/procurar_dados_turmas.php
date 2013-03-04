<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

/*
* Procura nomes de usuários pertencentes às mesmas turmas do usuário que está logado.
*/
$dataRecente = strtotime("-10 seconds");
$dataRecente = date("Y-m-d H:i:s", $dataRecente);
$id_personagem_online = $_SESSION['SS_personagem_id'];
$conexao_usuarios_turmas_usuario = new conexao();
$conexao_usuarios_turmas_usuario->solicitar("SELECT T.nomeTurma, U.usuario_nome, TabelaUsuariosTurma.usuarios_turma AS usuarios_turma, TabelaTodasTurmas.total_turmas AS total_turmas
											FROM Turmas AS T, 
												 usuarios AS U, 
												 personagens AS P,
												 (SELECT codTurma, COUNT(codTurma) AS usuarios_turma
												 FROM TurmasUsuario, usuarios
												 WHERE codUsuario = usuario_id
													AND usuario_personagem_id != 1
													AND usuario_personagem_id IS NOT NULL
												 GROUP BY codTurma) AS TabelaUsuariosTurma,
												 (SELECT COUNT(DISTINCT codTurma) AS total_turmas
												  FROM TurmasUsuario, usuarios
												  WHERE codTurma IN (SELECT codTurma 
																	 FROM TurmasUsuario
																	 WHERE codUsuario = $id_personagem_online
												  )) AS TabelaTodasTurmas,
												  (SELECT codTurma, codUsuario
												  FROM TurmasUsuario
												  WHERE codUsuario != $id_personagem_online
													AND codTurma IN(SELECT codTurma 
																	FROM TurmasUsuario 
																	WHERE codUsuario = $id_personagem_online
												   )) AS TabelaUsuariosMesmaTurma
											WHERE U.usuario_personagem_id != $id_personagem_online
												AND TabelaUsuariosTurma.codTurma = T.codTurma
												AND TabelaUsuariosMesmaTurma.codTurma = T.codTurma
												AND TabelaUsuariosMesmaTurma.codUsuario = U.usuario_id
												AND P.personagem_id = U.usuario_personagem_id
												"./*AND P.personagem_ultimo_acesso > '$dataRecente'*/"
												AND U.usuario_personagem_id IS NOT NULL
											ORDER BY nomeTurma, usuario_nome");
										
/*Neste ponto, temos uma relação do tipo:
	|nomeTurma      |usuario_nome|usuarios_turma|total_turmas|
	--------------------------------------------|------------|
	|turmaA			|Amélia	     |3			    |2           |
	|turmaA			|Bernardo    |3			    |2           |
	|turmaA			|Carlos	  	 |3			    |2           |
	|turmaBBB		|Amélia	     |1			    |2           |
*/										
$numeroUsuarios = $conexao_usuarios_turmas_usuario->registros;

$dados = '';
$numero_turmas = $conexao_usuarios_turmas_usuario->resultado['total_turmas'];
$dados.= '&numeroTurmas='.$numero_turmas;
for($indiceTurma=0; $indiceTurma < $numero_turmas; $indiceTurma++){
	$dados.= '&nomeTurma'.$indiceTurma.'='.$conexao_usuarios_turmas_usuario->resultado['nomeTurma'];
	$numero_usuarios_turma = $conexao_usuarios_turmas_usuario->resultado['usuarios_turma'];
	$dados.= '&numeroUsuariosTurma'.$indiceTurma.'='.$numero_usuarios_turma;
	for($indiceUsuario=0; $indiceUsuario < $numero_usuarios_turma-1; $indiceUsuario++){
		$nomeUsuario = $conexao_usuarios_turmas_usuario->resultado['usuario_nome'];
		$dados.= '&nomeUsuario'.$indiceTurma.','.$indiceUsuario.'='.$nomeUsuario;
		$conexao_usuarios_turmas_usuario->proximo();
	}
}

$erro = '0';
$dados .= '&erro='.$erro;
echo $dados;
?>