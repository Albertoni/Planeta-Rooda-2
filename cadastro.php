<?php
require_once("cfg.php");
require_once("bd.php");
require_once("funcoes_aux.php");

$data="";
$erro=0;

global $tabela_usuarios;
global $tabela_personagens;
global $tabela_terrenos;
global $tabela_nivel_permissoes;

/**************************************************************************************************************************************************
* ATENÇÃO: Comentários de 18/05/12 - sexta-feira - 16h.
* 	Comentei os gotos E seu label, pois impediam o cadastro.
* 	Mesmo comentando os gotos, o label ainda fazia com que este php não funcionasse.
* Diogo.
***************************************************************************************************************************************************/

$registrar = new conexao();		//Conexão para os registros no Bd - Guto - 10.05.10
if($registrar->erro != "") {
	$data .= '{ "valor":"1", "texto":"Erro no servidor"}';
	$erro = 1;
}


/*--------------------------------------------------------------------------
*	Confirmação do formulário de cadastro de clientes e correção do mesmo - Vinadé - 10.05.10
*	É necessário criar o grupo e o terreno padrão para cada usuário, além de inserí-lo 
*	na tabela de personagens e de usuários - Guto - 11.05.10
--------------------------------------------------------------------------*/
$login		= $registrar->sanitizaString($_POST["criar_apelido"]);
$usuario	= $registrar->sanitizaString($_POST['nome_completo']);
$email		= $registrar->sanitizaString($_POST['email']);
$nivel		= $registrar->sanitizaString($_POST['nivel']);
$sexo		= $registrar->sanitizaString($_POST['sexo']);
$password = crypt($_POST['criar_senha'], "$2y$07$".gen_salt(22));


$registrar->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_login='$login'");
if ($registrar->registros != 0) {
	$data .= '{ "valor":"1", "texto":"Este nome de usuário já existe"}';
	$erro = 1;
}

//Checa se o usuário não tá mandando nível bugado pra fazer algo malvado com o sistema.
// START Validação Nivel
function nivel_existe($nivel){
	global $nivelAdmin; global $nivelCoordenador; global $nivelAluno; global $nivelProfessor; global $nivelVisitante; global $nivelMonitor;

	switch($nivel){
		case $nivelAdmin:
		case $nivelCoordenador:
		case $nivelAluno:
		case $nivelProfessor:
		case $nivelVisitante:
		case $nivelMonitor:
			return true;
		default:
			return false;
	}
}
if (!nivel_existe($nivel)){
	$data .= '{ "valor":"1", "texto":"Nível inválido"}';
	$erro = 1;
}
// END Validação Nivel



//Primeiro grava o usuário na tabela personagens, definindo o id que será utilizado na tabela de usuários para referenciar o personagem / avatar. - Guto - 10.05.10 -- João - 09.04.14
if($erro != 1){
	$registrar->solicitar("INSERT INTO $tabela_personagens (personagem_nome, personagem_avatar_1, chat_id)
							VALUES ('$usuario', '$sexo', 0)"); // chat_id = 0, isso é legado do flash e não é mais utilizado, mas não é removido para não quebrar nada
	if($registrar->erro != ""){
		$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 1. Detalhes:'.$registrar->erro.'"}';
		$erro = 1;
	}
	$personagem_id  = $registrar->ultimo_id();
}

// CRIAÇÃO DO USUARIO
if($erro != 1){
	$registrar->solicitar("INSERT INTO $tabela_usuarios
	(usuario_data_criacao,usuario_nome,usuario_login,usuario_senha,usuario_email,usuario_personagem_id)
	VALUES (NOW(),'$usuario','$login','$password','$email','$personagem_id')");
	if($registrar->erro != "") {
		$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 3. Detalhes:'.$registrar->erro.'"}';
		$erro = 1;
	}
	$usuario_id = $registrar->ultimo_id();
}

// Cria-se o terreno do QUARTO DO ALUNO.
if($erro != 1){
	$registrar->solicitar("INSERT INTO $tabela_terrenos (terreno_nome, terreno_solo) VALUES ('$usuario', '6')");
	if($registrar->erro != "") {
		$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 7. Detalhes:'.$registrar->erro.'"}';
		$erro = 1;
	}
	$quarto_id = $registrar->ultimo_id();
}

// Atualiza-se o valor do terreno do aluno na tabela do usuario
if($erro != 1){
	$registrar->solicitar("UPDATE $tabela_usuarios SET quarto_id = $quarto_id WHERE usuario_id='$usuario_id'");
	if($registrar->erro != "") {
		$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 8. Detalhes:'.$registrar->erro.'"}';
		$erro = 1;
	}
}

if($erro != 1){
	$data .= '{ "valor":"0", "texto":"Cadastro efetuado"}';

	/*
	// Atualiza o pedido de troca de nivel no banco de dados. Não é utilizado devido a duvidas sobre o nivel global.
	global $nivelVisitante;
	if ($nivel != $nivelVisitante) {
		$registrar->solicitar("UPDATE $tabela_usuarios SET usuario_troca_nivel=$nivel WHERE usuario_id = $usuario_id");
		if($registrar->erro != "") {
			$data .= '{ "valor":"1", "texto":"Ocorreu um erro na entrada dos dados, código 5. Detalhes:'.$registrar->erro.'"}';
		}
	
		$data .= '{ "valor":"0", "texto":"Cadastro efetuado"}';

		// Isso aqui enviaria um email avisando do cadastro. No momento ninguem confere esse email e não está definido como nivel global vai funcionar, se é que VAI funcionar.
		$registrar->solicitar("SELECT * FROM $tabela_nivel_permissoes WHERE nivel='$nivel'");
		$nomeNivel = $registrar->resultado['nivel_nome'];
		$assunto = "Pedido de nível";
		$mensagem = "O usuário $usuario\n";
		$mensagem .= "solicitou o pedido de nível de $nomeNivel\n";
		$enviar = comum_enviar_email($email_administrador, $assunto, $mensagem, $email);
		
	}
	*/
}

header('Content-type: application/json; charset="utf-8"', true);
echo '{"mensagem":'.$data.'}';