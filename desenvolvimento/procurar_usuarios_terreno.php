<?php
session_start();
//arquivos necessários para o funcionamento
require_once("../cfg.php");
require_once("../bd.php");
require("../funcoes_aux.php");

/*
* Procura nomes de usuários que estão no mesmo terreno do usuário logado, retornando-os.
*/
$dataRecente = strtotime("-10 seconds");
$dataRecente = date("Y-m-d H:i:s", $dataRecente);
$id_personagem_online = $_SESSION['SS_personagem_id'];
$conexao_usuarios_terreno = new conexao();
$conexao_usuarios_terreno->solicitar("SELECT personagem_nome 
									FROM personagens 
									WHERE personagem_terreno_id IN (SELECT personagem_terreno_id 
																	FROM personagens 
																	WHERE personagem_id = $id_personagem_online)
										AND personagem_nome NOT IN (SELECT personagem_nome 
																	FROM personagens 
																	WHERE personagem_id = $id_personagem_online)
										AND personagem_ultimo_acesso > '$dataRecente'");
$numeroUsuariosMesmoTerreno = $conexao_usuarios_terreno->registros;

$dados = '';
for($indice=0; $indice < $numeroUsuariosMesmoTerreno; $indice++){
	$dados .= '&nomeUsuario'.$indice.'='.$conexao_usuarios_terreno->resultado['personagem_nome'];
	$conexao_usuarios_terreno->proximo();
}

$erro = '0';
$dados .= '&numeroUsuariosRecebidos='.$numeroUsuariosMesmoTerreno;
$dados .= '&erro='.$erro;
echo $dados;
?>