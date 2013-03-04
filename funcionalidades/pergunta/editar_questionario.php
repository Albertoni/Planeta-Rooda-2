<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

session_start();

if (isset($_POST['editar']) and $_POST['editar'] == 1){

$turma = (isset($_GET['turma']) and is_numeric($_GET['turma'])) ? $_GET['turma'] : die("Favor voltar e tentar novamente.");

$perm = checa_permissoes(TIPOPERGUNTA, $turma);
if($perm == false){
	die("Desculpe, mas o Planeta Pergunta esta desabilitado para esta turma.");
}
if(!$_SESSION['user']->podeAcessar($perm['pergunta_editarQuestionario'], $turma)){
	die("Desculpe, voce nao pode editar questionários nessa turma.");
}

	echo "<pre>";
	print_r($_POST);
	print_r($_SESSION);

	function validaData($ano, $mes, $dia){
		switch ($mes){
			case 2:
				if ($dia > 28)
					$dia = 28; // ano bi... sexto? o que é isso?
			break;

			case 4:
			case 6:
			case 9:
			case 11:
				if ($dia > 30)
					$dia = 30;
		}

		return $ano."-".$mes."-".$dia;
	}

	// Monta as datas
	$datainicio	= validaData($_POST['ano1'], $_POST['mes1'], $_POST['dia1']);
	$datafim	= validaData($_POST['ano2'], $_POST['mes2'], $_POST['dia2']);
	if (isset($_POST['liberar'])) {
		$liberar=	$_POST['liberar'] == 1 ? 1 : 0; // if liberar then 1 else 0
	} else {$liberar = 0;}
	$userid=	$_SESSION['SS_usuario_id'];

	$titulo_quest = conversor_pergunta($_POST['titulo'], "Título em branco.");
	$descricao_quest = conversor_pergunta($_POST['descrição'], "Descrição em branco.");
	$alquest = $_POST['alquest'];
	$questId = $_POST['id'];


	// EDITA o questionário
	$consulta = new conexao();
	$consulta->solicitar("UPDATE $tabela_PerguntaQuestionarios SET
	titulo = '$titulo_quest',
	descricao = '$descricao_quest',
	datainicio = '$datainicio',
	datafim = '$datafim',
	liberarGabarito = $liberar,
	alunoInsere = $alquest
	WHERE
	id = $questId");

	////print_r($consulta);

	$i = 1; // Para o loop

	while (isset($_POST['tipo_'.$i])) {
		switch ($_POST['tipo_'.$i]){
			case 1:
				$numops = str_replace("<", "&lt;", $_POST['numop_'.$i]);
				$questao	= conversor_pergunta($_POST['pergmul_'.$i], "Pergunta em branco."); // Texto da questão
				$correta	= $_POST['radio_'.$i]; // Posicao da correta
				$respostas	= array();
				$pergId		= $_POST['idperg_'.$i];
				$id_imagem	= $_POST['idimg_'.$i] == 0 ? 0 : $_POST['idimg_'.$i];
				$id_video	= strlen($_POST['idvid_'.$i]) > 3 ? $_POST['idvid_'.$i] : 0;
			
				for ($j=1; $j <= $numops; $j++) { // 1 e <= porque começa no 1, não no 0. Belê?
					$respostas[] = conversor_pergunta($_POST['opmul'.$j.'_'.$i], "Opção em branco.");
				}
			
				$resposta_parseada = implode("¦", $respostas); // Junta tudo numa só delimitada por ¦
				//print_r($respostas);
			
				
				$query = "UPDATE $tabela_PerguntaPerguntas SET
					id_questionario = $questId,
					tipo = 1,
					questao = '$questao',
					correta = '$correta',
					respostas = '$resposta_parseada'";
				
				if ($id_imagem != 0){ // tem imagem? concatena a alteração!
					$query .= ", id_imagem = $id_imagem";
				}
				if ($id_video != 0){ // mesmo pra video
					$query .= ", id_video = '$id_video'";
				}
				
				$query .= " WHERE id = $pergId"; // finaliza a query
				
				$consulta->solicitar($query);
				break;
			case 2:
				$questao	= conversor_pergunta($_POST['pergsubj_'.$i], "Pergunta em Branco"); // Texto da questão
				$resposta	= conversor_pergunta($_POST['respsubj_'.$i], "Resposta em Branco"); // acho que a resposta vai aqui
				$pergId		= $_POST['idperg_'.$i];
				$id_imagem	= $_POST['idimg_'.$i] == 0 ? 0 : $_POST['idimg_'.$i];
				$id_video	= strlen($_POST['idvid_'.$i]) > 3 ? $_POST['idvid_'.$i] : 0;
				
				$query = "UPDATE $tabela_PerguntaPerguntas SET
					questao = '$questao',
					respostas = '$resposta'";
				
				if ($id_imagem != 0){ // tem imagem? concatena a alteração!
					$query .= ", id_imagem = $id_imagem";
				}
				if ($id_video != 0){ // mesmo pra video
					$query .= ", id_video = '$id_video'";
				}
				
				$query .= " WHERE id = $pergId"; // finaliza a query
				
				$consulta->solicitar($query);
				
				//print_r($consulta);
				echo "$query";
				break;
			case 3:
				$questao	= conversor_pergunta($_POST['pergvf_'.$i], "Pergunta em Branco");
				$respostas	= array();
				$textos = array();
				$pergId		= $_POST['idperg_'.$i];
				$id_imagem	= $_POST['idimg_'.$i] == 0 ? 0 : $_POST['idimg_'.$i];
				$id_video	= strlen($_POST['idvid_'.$i]) > 3 ? $_POST['idvid_'.$i] : 0;
				
				$numops = $_POST['numop_'.$i];
				
				for ($j=1; $j <= $numops; $j++) { // Para cada resposta
					$respostas[]	= conversor_pergunta($_POST['radio'.$j.'_'.$i], "Deixado em branco"); // Converte os negóciozinhos aí pra entidade HTML deles pra não fufu depois
					$textos[]		= conversor_pergunta($_POST['opvf'.$j.'_'.$i], "Opção em branco.");
				}
				$resposta_parseada	= implode("¦", $respostas); // Junta tudo numa só delimitada por ¦
				$textos_parseados	= implode("¦", $textos); // Junta tudo numa só delimitada por ¦
				
				$query = "UPDATE $tabela_PerguntaPerguntas SET
					questao = '$questao',
					correta = '$resposta_parseada',
					respostas = '$textos_parseados'";
				
				if ($id_imagem != 0){ // tem imagem? concatena a alteração!
					$query .= ", id_imagem = $id_imagem";
				}
				if ($id_video != 0){ // mesmo pra video
					$query .= ", id_video = '$id_video'";
				}
				
				$query .= " WHERE id = $pergId"; // finaliza a query
				
				$consulta->solicitar($query);
				break;
		}
		$i++;
	}
?>
</pre>
<script>
	window.location = "planeta_pergunta.php";
</script><?php
} else {
	die("Algo de errado aconteceu. Provavelmente voce tentou acessar essa pagina sem estar editando um questionario. Por favor, <a href=\"planeta_pergunta.php\">volte</a> e tente novamente.");
}
