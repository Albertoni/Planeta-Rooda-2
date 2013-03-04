<?php
session_start();

if (count($_POST) == 0)
	die("Voce nao pode acessar essa pagina diretamente.");

require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");

$q = new conexao(); global $tabela_PerguntaPerguntas;
global $tabela_PerguntaRespostas;

$questionario = mysql_real_escape_string($_POST['idquest']);
$usuario = $_SESSION['SS_usuario_id'];

$permissoes = checa_permissoes(TIPOPERGUNTA, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}

if(!$usuario->podeAcessar($permissoes['pergunta_criarPergunta'], $turma)){
	die("Voce nao pode inserir questoes nessa turma.");
}

$q->solicitar("SELECT * FROM $tabela_PerguntaPerguntas WHERE id_questionario = $questionario AND id_usuario = $usuario");
if($q->registros != 0){
	die("Você já inseriu questões nesse questionario. <a href=\"planeta_pergunta.php\">Por favor, volte.</a>");
}

/*echo"<pre>";
print_r($_POST);
echo "</pre>";*/
$consulta = new conexao();
$questId=$questionario;

switch($_POST['tipo']){
	case 1:
		$i = $_POST['id-hidden'];
		
		$numops		= str_replace("<", "&lt;", $_POST['numop_'.$i]);
		$questao	= conversor_pergunta($_POST['pergmul_'.$i], "Pergunta em branco."); // Texto da questão
		$correta	= $_POST['radio_'.$i]; // Posicao da correta
		$respostas	= array();
		
		for ($j=1; $j <= $numops; $j++) { // 1 e <= porque começa no 1, não no 0. Belê?
			$respostas[] = conversor_pergunta($_POST['opmul'.$j.'_'.$i], "Opção em branco.");
		}
		
		$resposta_parseada = implode("¦", $respostas); // Junta tudo numa só delimitada por ¦
		
		$consulta->solicitar("INSERT INTO $tabela_PerguntaPerguntas
							(id_questionario, tipo, questao,	correta, 	respostas,			id_usuario) VALUES
							($questId,		1,		'$questao',	'$correta',	'$resposta_parseada',$usuario)");
		
		break;
	case 2:
		$i = $_POST['id-hidden'];
		$questao	= conversor_pergunta($_POST['pergsubj_'.$i], "Pergunta em Branco"); // Texto da questão
		$resposta	= conversor_pergunta($_POST['respsubj_'.$i], "Resposta em Branco"); // acho que a resposta vai aqui
		
		$consulta->solicitar("INSERT INTO $tabela_PerguntaPerguntas
							(id_questionario,	tipo, questao,		respostas,	id_usuario) VALUES
							($questId,			2,	'$questao',		'$resposta',$usuario)");
		break;
	case 3:
		$id = $_POST['id-hidden'] + 1; // ALERTA DE GAMBIARRA AQUI POR FAVOR PRESTAR ATENÇÃO NESSA LINHA
		
		$questao	= conversor_pergunta($_POST['pergvf_'.$id], "Pergunta em Branco");
		$numops = $_POST['numop_'.$id];
		
		for ($j=1; $j <= $numops; $j++) { // Para cada resposta
			$respostas[]	= conversor_pergunta($_POST['radio'.$j.'_'.$id], "Deixado em branco"); // Converte os negóciozinhos aí pra entidade HTML deles pra não fufu depois
			$textos[]		= conversor_pergunta($_POST['opvf'.$j.'_'.$id], "Opção em branco.");
		}
		$resposta_parseada	= implode("¦", $respostas); // Junta tudo numa só delimitada por ¦
		$textos_parseados	= implode("¦", $textos); // Junta tudo numa só delimitada por ¦
		
		$id -= 1; // SEGUNDO ALERTA DE GAMBIARRA AQUI POR FAVOR PRESTAR ATENÇÃO NESSA LINHA
		
		$consulta->solicitar("INSERT INTO $tabela_PerguntaPerguntas
							(id_questionario,	tipo,questao,		correta,				respostas,			id_usuario) VALUES
							($questId,			3,	'$questao',		'$resposta_parseada',	'$textos_parseados',$usuario)");
		
		break;
	default:
		die("<script>window.history.back();</script>");
}

magic_redirect("planeta_pergunta.php");
