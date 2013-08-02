<?php
session_start();

if (count($_POST) == 0)
	die("Voce nao pode acessar essa pagina diretamente.");

require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");

$q = new conexao(); global $tabela_PerguntaRespostas;

$questionario = (int) $_POST['idquest'];
$turma = (int) $_POST['turma'];
$usuario = $_SESSION['SS_usuario_id'];

$usuario = new Usuario();
$usuario->openUsuario($_SESSION['SS_usuario_id']);

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

$check = new conexao();
$check->solicitar("SELECT id FROM $tabela_PerguntaRespostas WHERE (questionario = $questionario AND usuario = $usuario)");
if ($check->registros > 0){ // Já respondido?
	die("Voc&ecirc; j&aacute; respondeu esse question&aacute;rio. Por favor <a href=\"planeta_pergunta.php\">clique aqui para voltar</a>.");
}
$check = NULL;

$respostas = "";

for ($i=1; $i <= $_POST['numops']; $i++){
	switch($_POST["tipo$i"]){
	case 1:
		$respostas .= $q->sanitizaString($_POST["opmul_$i"]);
		break;
	case 2:
		$respostas .= $q->sanitizaString(str_replace("¦", "&brvbar;", $_POST["subj_$i"]));
		break;
	case 3:
		for ($j=1; isset($_POST["radio".$j."_$i"]); $j++) {
			$respostas .= $q->sanitizaString($_POST["radio".$j."_$i"]);
			$respostas .= ";";
		}
		$respostas = rtrim($respostas, ";"); // remove o ; final
		break;
	default:
		die("Algum erro aconteceu, não sabemos a causa mas estamos felizes em lhe mostrar esta mensagem e dizer que muito provavelmente algum dado foi corrompido acidentalmente.<br />
Por favor, entre de novo na sua conta e tente novamente. Caso continue a ver esse erro, avise os desenvolvedores.");
	}

	$respostas .= "¦";
}

$respostas = rtrim($respostas, "¦"); // remove o negóciozinho ai do fim

$q->solicitar("INSERT INTO $tabela_PerguntaRespostas (usuario, resposta, questionario) VALUES (".$_SESSION['SS_usuario_id'].", '$respostas', '$questionario')");

magic_redirect("planeta_pergunta.php?turma=$turma");
