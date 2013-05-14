<?php
require("../../cfg.php");
require("../../bd.php");
require("../../funcoes_aux.php");
require("../../usuarios.class.php");

session_start();


echo "<pre>";
print_r($_POST);


$turma =	is_numeric($_POST['turma']) ? $_POST['turma'] : 0;

$perm = checa_permissoes(TIPOPERGUNTA, $turma);
if($perm == false){
	die("Desculpe, mas o Planeta Pergunta esta desabilitado para esta turma.");
}
if(!$_SESSION['user']->podeAcessar($perm['pergunta_criarQuestionario'], $turma)){
	die("Desculpe, voce nao pode criar questionários nessa turma.");
}


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
			if ($dia > 30){
				$dia = 30;
			}
			break;
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			if ($dia > 31){
				$dia = 31; // Não é pra isso acontecer se o usuario não mexer com o formulário, mas é bom garantir.
			}
			break;
		default:
			die("Foi passado um mes que vem depois de dezembro para essa pagina. Favor voltar e tentar novamente.");
	}
	
	return $ano."-".$mes."-".$dia;
}

// Monta as datas
$datainicio	= validaData($_POST['ano1'], $_POST['mes1'], $_POST['dia1']);
$datafim	= validaData($_POST['ano2'], $_POST['mes2'], $_POST['dia2']);

$liberar=	$_POST['libera'] == 1 ? 1 : 0; // if liberar then 1 else 0
$alquest=	$_POST['alquest'] == 1 ? 1 : 0;
$userid=	$_SESSION['SS_usuario_id'];

$titulo_quest = conversor_pergunta($_POST['titulo'], "Título em branco.");
$descricao_quest = conversor_pergunta($_POST['descricao'], "Descrição em branco.");


// Cria o questionário
$consulta = new conexao();
$titulo_quest = $consulta->sanitizaString($titulo_quest);
$descricao_quest = $consulta->sanitizaString($descricao_quest);
$consulta->solicitar("INSERT INTO $tabela_PerguntaQuestionarios
					(titulo,			descricao,			datainicio,		datafim,	liberarGabarito, criador,	alunoInsere, turma) VALUES
					('$titulo_quest', '$descricao_quest', '$datainicio',	'$datafim',	$liberar,		$userid,	$alquest,	$turma)");

$questId = $consulta->ultimoId(); // PUXA A ID DO QUESTIONARIO GERADO COM ESSA INSERÇÃO

$i = 1; // Para o loop

while (isset($_POST['tipo_'.$i])) {
	switch ($_POST['tipo_'.$i]){
		case 1:
			$numops		= str_replace("<", "&lt;", $_POST['numop_'.$i]);
			$questao	= conversor_pergunta($_POST['pergmul_'.$i], "Pergunta em branco."); // Texto da questão
			$correta	= $_POST['radio_'.$i]; // Posicao da correta
			$respostas	= array();
			
			for ($j=1; $j <= $numops; $j++) { // 1 e <= porque começa no 1, não no 0. Belê?
				$respostas[] = conversor_pergunta($_POST['opmul'.$j.'_'.$i], "Opção em branco.");
			}
			
			$resposta_parseada = implode("¦", $respostas); // Junta tudo numa só delimitada por ¦
			//print_r($respostas);
			
			// Pega a id da imagem. if idimg == 0 then 0 else id
			$id_imagem	= $_POST['idimg_'.$i] == 0 ? 0 : $_POST['idimg_'.$i];
			$id_video	= strlen($_POST['idvid_'.$i]) > 3 ? $_POST['idvid_'.$i] : 0;
			
			
			
			$consulta->solicitar("INSERT INTO $tabela_PerguntaPerguntas
								(id_questionario, tipo, questao,	correta, 	respostas,			id_imagem,		id_video) VALUES
								($questId,		1,		'$questao',	'$correta',	'$resposta_parseada', $id_imagem,	'$id_video')");
			//print_r($consulta);
			break;
		case 2:
			$questao	= conversor_pergunta($_POST['pergsubj_'.$i], "Pergunta em Branco"); // Texto da questão
			$resposta	= conversor_pergunta($_POST['respsubj_'.$i], "Resposta em Branco"); // acho que a resposta vai aqui
			$id_imagem	= $_POST['idimg_'.$i] == 0 ? 0 : $_POST['idimg_'.$i];
			$id_video	= strlen($_POST['idvid_'.$i]) > 3 ? $_POST['idvid_'.$i] : 0;
			
			
			
			$consulta->solicitar("INSERT INTO $tabela_PerguntaPerguntas
								  (id_questionario,	tipo, questao,		respostas,		id_imagem,	id_video) VALUES
								  ($questId,		2,	'$questao',		'$resposta',	$id_imagem,	'$id_video')");
			//print_r($consulta);
			break;
		case 3:
			$questao	= conversor_pergunta($_POST['pergvf_'.$i], "Pergunta em Branco");
			$respostas	= array();
			$textos = array();
			
			$numops = $_POST['numop_'.$i];
			
			// Pega a id da imagem. if idimg == 0 then 0 else id
			$id_imagem	= $_POST['idimg_'.$i] == 0 ? 0 : $_POST['idimg_'.$i];
			$id_video	= strlen($_POST['idvid_'.$i]) > 3 ? $_POST['idvid_'.$i] : 0;
			
			
			
			for ($j=1; $j <= $numops; $j++) { // Para cada resposta
				$respostas[]	= conversor_pergunta($_POST['radio'.$j.'_'.$i], "Deixado em branco"); // Converte os negóciozinhos aí pra entidade HTML deles pra não fufu depois
				$textos[]		= conversor_pergunta($_POST['opvf'.$j.'_'.$i], "Opção em branco.");
			}
			$resposta_parseada	= implode("¦", $respostas); // Junta tudo numa só delimitada por ¦
			$textos_parseados	= implode("¦", $textos); // Junta tudo numa só delimitada por ¦
			
			$consulta->solicitar("INSERT INTO $tabela_PerguntaPerguntas
								  (id_questionario, tipo, questao,		correta, 				respostas,				id_imagem,	id_video) VALUES
								  ($questId,		3,	'$questao',		'$resposta_parseada',	'$textos_parseados',	$id_imagem,	'$id_video')");
			//print_r($consulta);
			break;
	}
	$i++;
}
?>
</pre>
<script>
	//window.location = "planeta_pergunta.php?turma=<?=$turma?>";
</script>
