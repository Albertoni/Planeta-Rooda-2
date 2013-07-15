<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("../../usuarios.class.php");
    require("../../login.class.php");	
	require("../../file.class.php");
	require("../../link.class.php");
	$usuario_nivel 	= $_SESSION['SS_usuario_nivel_sistema'];
	
	if ($usuario_nivel > 10) { // Se for aluno ou visitante
		die ("Você não tem nada a fazer aqui. A direção foi informada de sua tentativa de acesso. Tenha um bom dia.");
		// LOGAR O ERRO AQUI?
	}
	
	function retornaNomeNivel($nivel){
		global $nivelAdmin;
		global $nivelCoordenador;
		global $nivelProfessor;
		global $nivelAluno;
		global $nivelVisitante;
		
		global $admin;
		global $coordenador;
		global $professor;
		global $aluno;
		global $visitante;
		
		switch ($nivel){
			case $nivelAdmin:
				return $admin;
				break;
			case $nivelCoordenador: 
				return $coordenador;
				break;
			case $nivelProfessor:
				return $professor;
				break;
			case $nivelAluno:
				return $aluno;
				break;
			case $nivelVisitante:
				return $visitante;
				break;
			default:
				return 'ENTRE EM PÂNICO';
		}
	}
	
	$consulta = new conexao();
	$usuario_id 	= $_SESSION['SS_usuario_id'];
	$consulta->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = '$usuario_id'");
	$nome = $consulta->resultado['usuario_nome'];	// Sabe como é, coordenador/diretor velho acha mágico ler "Bem vindo, Fulanante".
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Administração do Planeta ROODA - Aprovação de usuários novos</title>
</head>

<body>
	Bem vindo, <?=$nome?>!
	<form method="post" action="processa_aprovacao.php">
<?php	
	$consulta->solicitar("SELECT usuario_nome, usuario_email , usuario_nivel, usuario_troca_nivel, usuario_id FROM $tabela_usuarios WHERE usuario_troca_nivel != 0");
	// Pega os que não estão com a conta confirmada
	
	for ($i=0; $i<count($consulta->itens); $i++){
		// print em todos os usuários que faltam confirmar
		
		if ($i%2){ // Se for impar
			echo '	<li class="enviado2">';}
		else { // É par. Derp.
			echo '	<li class="enviado1">';}
		
		//echo "testagem: ".print_r($consulta->resultado);
		echo "\n		<input type=\"checkbox\" name=\"usuario".$i."\" value=\"".$consulta->resultado['usuario_id']."\" />Nome: ".$consulta->resultado['usuario_nome']." --- Email: ".$consulta->resultado['usuario_email']." --- Nivel preterido: ";
		echo retornaNomeNivel($consulta->resultado['usuario_troca_nivel']);
		echo " --- Nivel atual: ";
		echo retornaNomeNivel($consulta->resultado['usuario_nivel']);
		echo "</li>\n";
		
		$consulta->proximo();
	}
	
	$consulta->fechar();
?>
	<input type="submit" name="confirmar" value="Confirmar" />
	</form>
</body>
</html>