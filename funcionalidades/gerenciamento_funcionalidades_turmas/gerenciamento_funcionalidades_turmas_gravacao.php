<?php
	session_start();
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>
<?php

	/*
	* Com quatro booleanos indicando a permissão para determinado nível de uma funcionalidade,
	* retorna int que indique os níveis escolhidos.
	*/
	function getNivelCheckbox($conteudoAssociativo, $_base){
		global $nivelProfessor;
		global $nivelMonitor;
		global $nivelAluno;
		$nivel=0;

		$temProfessor	= isset($conteudoAssociativo[$_base."_professor"]) ? $conteudoAssociativo[$_base."_professor"] : false;
		$temMonitor		= isset($conteudoAssociativo[$_base."_monitor"]) ? $conteudoAssociativo[$_base."_monitor"] : false;
		$temAluno		= isset($conteudoAssociativo[$_base."_aluno"]) ? $conteudoAssociativo[$_base."_aluno"] : false;
		$temTodos		= isset($conteudoAssociativo[$_base."_todos"]) ? $conteudoAssociativo[$_base."_todos"] : false;

		if(((bool) $temTodos) == true and $temTodos != 'false'){
			$nivel+=$nivelProfessor+$nivelMonitor+$nivelAluno;
		} else {
			if(((bool) $temProfessor) == true and $temProfessor != 'false'){
				$nivel+=$nivelProfessor;
			}
			if(((bool) $temMonitor) == true and $temMonitor != 'false'){
				$nivel+=$nivelMonitor;
			}
			if(((bool) $temAluno) == true and $temAluno != 'false'){
				$nivel+=$nivelAluno;
			}
		}
		return $nivel;
	}


	$codTurma = $_POST['codTurma'];

	$conteudoCheckboxes = $_POST['conteudoCheckboxes'];

	$conteudoCheckboxesGerenciamento = '';
	$conteudoCheckboxesEmArray = explode(',',$conteudoCheckboxes);
	$conteudoAssociativo = array();
	for($i=0; $i<count($conteudoCheckboxesEmArray); $i+=2){
		$conteudoAssociativo[$conteudoCheckboxesEmArray[$i]] = $conteudoCheckboxesEmArray[$i+1];
		if($conteudoCheckboxesEmArray[$i] != 'comunicador'
			and $conteudoCheckboxesEmArray[$i] != 'biblioteca'
			and $conteudoCheckboxesEmArray[$i] != 'blog'
			and $conteudoCheckboxesEmArray[$i] != 'portfolio'
			and $conteudoCheckboxesEmArray[$i] != 'forum'
			and $conteudoCheckboxesEmArray[$i] != 'arte'
			and $conteudoCheckboxesEmArray[$i] != 'pergunta'
			and $conteudoCheckboxesEmArray[$i] != 'aulas'
			and $conteudoCheckboxesEmArray[$i] != 'player'){
			if($conteudoCheckboxesGerenciamento!=''){ $conteudoCheckboxesGerenciamento .= ',';}
			$conteudoCheckboxesGerenciamento .= $conteudoCheckboxesEmArray[$i].','.$conteudoCheckboxesEmArray[$i+1];
		}
	}

	//echo '<br><br><br>'.$conteudoCheckboxesGerenciamento.'<br><br><br>';

	$estahHabilitado_comunicador = ($conteudoAssociativo['comunicador']     ==='true'? 'h' : 'd');
	$estahHabilitado_biblioteca  = ($conteudoAssociativo['biblioteca']      ==='true'? 'h' : 'd');
	$estahHabilitado_blog        = ($conteudoAssociativo['blog']            ==='true'? 'h' : 'd');
	$estahHabilitado_portfolio   = ($conteudoAssociativo['portfolio']       ==='true'? 'h' : 'd');
	$estahHabilitado_forum       = ($conteudoAssociativo['forum']           ==='true'? 'h' : 'd');
	$estahHabilitado_arte        = ($conteudoAssociativo['arte']            ==='true'? 'h' : 'd');
	$estahHabilitado_pergunta    = ($conteudoAssociativo['pergunta']        ==='true'? 'h' : 'd');
	$estahHabilitado_player      = ($conteudoAssociativo['player']          ==='true'? 'h' : 'd');
	$estahHabilitado_aulas       = ($conteudoAssociativo['aulas']           ==='true'? 'h' : 'd');

///////////////////////////////////////////////////////////

	$comunicador_terreno = getNivelCheckbox($conteudoAssociativo,'comunicador_Chat de Terreno');
	$comunicador_turma   = getNivelCheckbox($conteudoAssociativo,'comunicador_Chat de Turma');
	$comunicador_privado = getNivelCheckbox($conteudoAssociativo,'comunicador_Chat Privado');
	$comunicador_amigo   = getNivelCheckbox($conteudoAssociativo,'comunicador_Chat Amigo');

	$biblioteca_enviarMateriais = getNivelCheckbox($conteudoAssociativo,'biblioteca_Enviar Materiais');
	$biblioteca_editarMateriais = getNivelCheckbox($conteudoAssociativo,'biblioteca_Editar Materiais');
	$biblioteca_excluirArquivos = getNivelCheckbox($conteudoAssociativo,'biblioteca_Excluir Arquivos');
	$biblioteca_aprovarMateriais= getNivelCheckbox($conteudoAssociativo,'biblioteca_Aprovar Arquivos');

	$blog_inserirPost = getNivelCheckbox($conteudoAssociativo,'blog_Inserir Post');
	$blog_editarPost = getNivelCheckbox($conteudoAssociativo,'blog_Editar Post');
	$blog_inserirComentarios = getNivelCheckbox($conteudoAssociativo,'blog_Inserir Comentários');
	$blog_excluirPost = getNivelCheckbox($conteudoAssociativo,'blog_Excluir Post');
	$blog_adicionarLinks = getNivelCheckbox($conteudoAssociativo,'blog_Adicionar Links');
	$blog_adicionarArquivos = getNivelCheckbox($conteudoAssociativo,'blog_Adicionar Arquivos');

	$portfolio_visualizarPost = getNivelCheckbox($conteudoAssociativo,'portfolio_Visualizar Post');
	$portfolio_inserirPost = getNivelCheckbox($conteudoAssociativo,'portfolio_Inserir Post');
	$portfolio_editarPost = getNivelCheckbox($conteudoAssociativo,'portfolio_Editar Post');
	$portfolio_inserirComentarios = getNivelCheckbox($conteudoAssociativo,'portfolio_Inserir Comentários');
	$portfolio_excluirPost = getNivelCheckbox($conteudoAssociativo,'portfolio_Excluir Post');
	$portfolio_adicionarLinks = getNivelCheckbox($conteudoAssociativo,'portfolio_Adicionar Links');
	$portfolio_enviarArquivos = getNivelCheckbox($conteudoAssociativo,'portfolio_Enviar Arquivos');
	$portfolio_excluirArquivos = getNivelCheckbox($conteudoAssociativo,'portfolio_Excluir Arquivos');

	$forum_criarTopico = getNivelCheckbox($conteudoAssociativo,'forum_Criar Tópico');
	$forum_editarTopico = getNivelCheckbox($conteudoAssociativo,'forum_Editar Tópico');
	$forum_excluirTopico = getNivelCheckbox($conteudoAssociativo,'forum_Excluir Tópico');
	$forum_responderTopico = getNivelCheckbox($conteudoAssociativo,'forum_Responder Tópico');
	$forum_editarResposta = getNivelCheckbox($conteudoAssociativo,'forum_Editar Resposta');
	$forum_excluirResposta = getNivelCheckbox($conteudoAssociativo,'forum_Excluir Resposta');

	$arte_criarDesenho = getNivelCheckbox($conteudoAssociativo,'arte_Criar Desenho');
	$arte_excluirDesenho = getNivelCheckbox($conteudoAssociativo,'arte_Excluir Desenho');
	$arte_verComentarios = getNivelCheckbox($conteudoAssociativo,'arte_Ver Comentários');
	$arte_inserirComentarios = getNivelCheckbox($conteudoAssociativo,'arte_Inserir Comentários');
	$arte_excluirComentarios = getNivelCheckbox($conteudoAssociativo,'arte_Excluir Comentários');

	$pergunta_criarQuestionario = getNivelCheckbox($conteudoAssociativo,'pergunta_Criar Questionário');
	$pergunta_criarPergunta = getNivelCheckbox($conteudoAssociativo,'pergunta_Criar Pergunta');
	$pergunta_editarQuestionario = getNivelCheckbox($conteudoAssociativo,'pergunta_Editar Questionário');
	$pergunta_editarPergunta = getNivelCheckbox($conteudoAssociativo,'pergunta_Editar Pergunta');
	$pergunta_deletarQuestionario = getNivelCheckbox($conteudoAssociativo,'pergunta_Deletar Questionário');
	$pergunta_deletarPergunta = getNivelCheckbox($conteudoAssociativo,'pergunta_Deletar Pergunta');

	$player_inserirVideos = getNivelCheckbox($conteudoAssociativo,'player_Inserir Vídeos');
	$player_inserirComentario = getNivelCheckbox($conteudoAssociativo,'player_Comentar Vídeos');
	$player_deletarVideos = getNivelCheckbox($conteudoAssociativo,'player_Deletar Vídeos');
	$player_deletarComentario = getNivelCheckBox($conteudoAssociativo,'player_Deletar Comentário');

	$aulas_criarAulas = getNivelCheckbox($conteudoAssociativo,'aulas_Criar Aulas');
	$aulas_editarAulas = getNivelCheckbox($conteudoAssociativo,'aulas_Editar Aulas');
	$aulas_importarAulas = getNivelCheckbox($conteudoAssociativo,'aulas_Importar Aulas');

///////////////////////////////////////////////////////////

	$mensagemDeErro = 'Desculpe, houve um erro ao gravar os dados. O erro é:<br>';
	$deuErro = false;

	$conexaoSalvarDadosGerenciamentoTurma = new conexao();
	$conexaoSalvarDadosGerenciamentoTurma->solicitar("DELETE FROM GerenciamentoTurma WHERE codTurma = $codTurma");
	if($conexaoSalvarDadosGerenciamentoTurma->erro != ''){
		echo $mensagemDeErro.$conexaoSalvarDadosGerenciamentoTurma->erro;
		$deuErro = true;
	}
	
	$conteudoCheckboxesGerenciamento = $conexaoSalvarDadosGerenciamentoTurma->sanitizaString($conteudoCheckboxesGerenciamento);

	$conexaoSalvarDadosGerenciamentoTurma->solicitar("INSERT INTO GerenciamentoTurma (codTurma, 
																					  dadosGerenciamento,
			  comunicador_terreno, comunicador_turma, comunicador_privado, comunicador_amigo,

			  biblioteca_enviarMateriais, biblioteca_editarMateriais, biblioteca_excluirArquivos, biblioteca_aprovarMateriais,

			  blog_inserirPost, blog_editarPost, blog_inserirComentarios, blog_excluirPost, blog_adicionarLinks, blog_adicionarArquivos,

			  portfolio_visualizarPost, portfolio_inserirPost, portfolio_editarPost, portfolio_inserirComentarios, portfolio_excluirPost, portfolio_adicionarLinks, portfolio_enviarArquivos, portfolio_excluirArquivos,

			  forum_criarTopico, forum_editarTopico, forum_excluirTopico, forum_responderTopico, forum_editarResposta, forum_excluirResposta,

			  arte_criarDesenho, arte_excluirDesenho, arte_inserirComentarios, arte_verComentarios, arte_excluirComentarios,

			  pergunta_criarQuestionario, pergunta_criarPergunta, pergunta_editarQuestionario, pergunta_editarPergunta, pergunta_deletarQuestionario, pergunta_deletarPergunta,

			  player_inserirVideos, player_deletarVideos, player_inserirComentario, player_deletarComentario,

			  aulas_criarAulas, aulas_editarAulas, aulas_importarAulas)
																			VALUES ($codTurma, '$conteudoCheckboxesGerenciamento',
			  $comunicador_terreno, $comunicador_turma, $comunicador_privado, $comunicador_amigo,

			  $biblioteca_enviarMateriais, $biblioteca_editarMateriais, $biblioteca_excluirArquivos, $biblioteca_aprovarMateriais,

			  $blog_inserirPost, $blog_editarPost, $blog_inserirComentarios, $blog_excluirPost, $blog_adicionarLinks, $blog_adicionarArquivos,

			  $portfolio_visualizarPost, $portfolio_inserirPost, $portfolio_editarPost, $portfolio_inserirComentarios, $portfolio_excluirPost, $portfolio_adicionarLinks, $portfolio_enviarArquivos, $portfolio_excluirArquivos,

			  $forum_criarTopico, $forum_editarTopico, $forum_excluirTopico, $forum_responderTopico, $forum_editarResposta, $forum_excluirResposta,

			  $arte_criarDesenho, $arte_excluirDesenho, $arte_inserirComentarios, $arte_verComentarios, $arte_excluirComentarios,

			  $pergunta_criarQuestionario, $pergunta_criarPergunta, $pergunta_editarQuestionario, $pergunta_editarPergunta, $pergunta_deletarQuestionario, $pergunta_deletarPergunta,

			  $player_inserirVideos, $player_deletarVideos, $player_inserirComentario, $player_deletarComentario,

			  $aulas_criarAulas, $aulas_editarAulas, $aulas_importarAulas)");
			
	if($conexaoSalvarDadosGerenciamentoTurma->erro != ''){
		echo $mensagemDeErro.$conexaoSalvarDadosGerenciamentoTurma->erro;
		$deuErro = true;
	}

	$conexaoSalvarDadosGerenciamentoTurma->solicitar("DELETE FROM FuncionalidadesTurma WHERE codTurma = $codTurma");
	$conexaoSalvarDadosGerenciamentoTurma->solicitar("INSERT INTO FuncionalidadesTurma (codTurma, 
				 batePapo, biblioteca, blog, portfolio, forum, planetaArte, planetaPergunta, planetaPlayer, aulas)
													  VALUES($codTurma,
				 '$estahHabilitado_comunicador', '$estahHabilitado_biblioteca', '$estahHabilitado_blog', '$estahHabilitado_portfolio', 
				 '$estahHabilitado_forum', '$estahHabilitado_arte', '$estahHabilitado_pergunta', '$estahHabilitado_player', '$estahHabilitado_aulas')");
	if($conexaoSalvarDadosGerenciamentoTurma->erro != ''){
		echo $mensagemDeErro.$conexaoSalvarDadosGerenciamentoTurma->erro;
		$deuErro = true;
	}
	
	if(!$deuErro){
		echo 'Dados salvos com sucesso!';
	}
?>
</body>
</html>
