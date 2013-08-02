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
	function getNivelCheckbox($_temProfessor, $_temMonitor, $_temAluno, $_temTodos){
		global $nivelProfessor;
		global $nivelMonitor;
		global $nivelAluno;
		$nivel=0;
		if(((bool) $_temTodos) == true and $_temTodos != 'false'){
			$nivel+=$nivelProfessor+$nivelMonitor+$nivelAluno;
		} else {
			if(((bool) $_temProfessor) == true and $_temProfessor != 'false'){
				$nivel+=$nivelProfessor;
			}
			if(((bool) $_temMonitor) == true and $_temMonitor != 'false'){
				$nivel+=$nivelMonitor;
			}
			if(((bool) $_temAluno) == true and $_temAluno != 'false'){
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

	$comunicador_terreno          = getNivelCheckbox(false, 
													 $conteudoAssociativo['comunicador_Chat de Terreno_monitor'], 
													 $conteudoAssociativo['comunicador_Chat de Terreno_aluno'], 
													 false);
	$comunicador_turma            = getNivelCheckbox(false, 
													 $conteudoAssociativo['comunicador_Chat de Turma_monitor'], 
													 $conteudoAssociativo['comunicador_Chat de Turma_aluno'], 
													 false);
	$comunicador_privado          = getNivelCheckbox(false, 
													 $conteudoAssociativo['comunicador_Chat Privado_monitor'], 
													 $conteudoAssociativo['comunicador_Chat Privado_aluno'], 
													 false);
	$comunicador_amigo            = getNivelCheckbox(false, 
													 $conteudoAssociativo['comunicador_Chat Amigo_monitor'], 
													 $conteudoAssociativo['comunicador_Chat Amigo_aluno'], 
													 false);
	$biblioteca_enviarMateriais   = getNivelCheckbox($conteudoAssociativo['biblioteca_Enviar Materias_professor'], 
													 $conteudoAssociativo['biblioteca_Enviar Materias_monitor'], 
													 $conteudoAssociativo['biblioteca_Enviar Materias_aluno'], 
													 $conteudoAssociativo['biblioteca_Enviar Materias_todos']);
	$biblioteca_editarMateriais   = getNivelCheckbox($conteudoAssociativo['biblioteca_Editar Materias_professor'], 
													 $conteudoAssociativo['biblioteca_Editar Materias_monitor'], 
													 $conteudoAssociativo['biblioteca_Editar Materias_aluno'], 
													 $conteudoAssociativo['biblioteca_Editar Materias_todos']);
	$biblioteca_excluirArquivos   = getNivelCheckbox($conteudoAssociativo['biblioteca_Excluir Arquivos_professor'], 
													 $conteudoAssociativo['biblioteca_Excluir Arquivos_monitor'], 
													 $conteudoAssociativo['biblioteca_Excluir Arquivos_aluno'], 
													 $conteudoAssociativo['biblioteca_Excluir Arquivos_todos']);
	$biblioteca_aprovarMateriais  = getNivelCheckbox($conteudoAssociativo['biblioteca_Aprovar Arquivos_professor'], 
													 $conteudoAssociativo['biblioteca_Aprovar Arquivos_monitor'], 
													 $conteudoAssociativo['biblioteca_Aprovar Arquivos_aluno'], 
													 $conteudoAssociativo['biblioteca_Aprovar Arquivos_todos']);
	$blog_inserirPost             = getNivelCheckbox($conteudoAssociativo['blog_Inserir Post_professor'], 
													 $conteudoAssociativo['blog_Inserir Post_monitor'], 
													 $conteudoAssociativo['blog_Inserir Post_aluno'], 
													 $conteudoAssociativo['blog_Inserir Post_todos']);
	$blog_editarPost              = getNivelCheckbox($conteudoAssociativo['blog_Editar Post_professor'], 
													 $conteudoAssociativo['blog_Editar Post_monitor'], 
													 $conteudoAssociativo['blog_Editar Post_aluno'], 
													 $conteudoAssociativo['blog_Editar Post_todos']);
	$blog_inserirComentarios      = getNivelCheckbox($conteudoAssociativo['blog_Inserir Comentários_professor'], 
													 $conteudoAssociativo['blog_Inserir Comentários_monitor'], 
													 $conteudoAssociativo['blog_Inserir Comentários_aluno'], 
													 $conteudoAssociativo['blog_Inserir Comentários_todos']);
	$blog_excluirPost             = getNivelCheckbox($conteudoAssociativo['blog_Excluir Post_professor'], 
													 $conteudoAssociativo['blog_Excluir Post_monitor'], 
													 $conteudoAssociativo['blog_Excluir Post_aluno'], 
													 $conteudoAssociativo['blog_Excluir Post_todos']);
	$blog_adicionarLinks          = getNivelCheckbox($conteudoAssociativo['blog_Adicionar Links_professor'], 
													 $conteudoAssociativo['blog_Adicionar Links_monitor'], 
													 $conteudoAssociativo['blog_Adicionar Links_aluno'], 
													 $conteudoAssociativo['blog_Adicionar Links_todos']);
	$blog_adicionarArquivos       = getNivelCheckbox($conteudoAssociativo['blog_Adicionar Arquivos_professor'], 
													 $conteudoAssociativo['blog_Adicionar Arquivos_monitor'], 
													 $conteudoAssociativo['blog_Adicionar Arquivos_aluno'], 
													 $conteudoAssociativo['blog_Adicionar Arquivos_todos']);
	$portfolio_visualizarPost     = getNivelCheckbox($conteudoAssociativo['portfolio_Visualizar Post_professor'], 
													 $conteudoAssociativo['portfolio_Visualizar Post_monitor'], 
													 $conteudoAssociativo['portfolio_Visualizar Post_aluno'], 
													 $conteudoAssociativo['portfolio_Visualizar Post_todos']);
	$portfolio_inserirPost        = getNivelCheckbox($conteudoAssociativo['portfolio_Inserir Post_professor'], 
													 $conteudoAssociativo['portfolio_Inserir Post_monitor'], 
													 $conteudoAssociativo['portfolio_Inserir Post_aluno'], 
													 $conteudoAssociativo['portfolio_Inserir Post_todos']);
	$portfolio_editarPost         = getNivelCheckbox($conteudoAssociativo['portfolio_Editar Post_professor'], 
													 $conteudoAssociativo['portfolio_Editar Post_monitor'], 
													 $conteudoAssociativo['portfolio_Editar Post_aluno'], 
													 $conteudoAssociativo['portfolio_Editar Post_todos']);
	$portfolio_inserirComentarios = getNivelCheckbox($conteudoAssociativo['portfolio_Inserir Comentários_professor'], 
													 $conteudoAssociativo['portfolio_Inserir Comentários_monitor'], 
													 $conteudoAssociativo['portfolio_Inserir Comentários_aluno'], 
													 $conteudoAssociativo['portfolio_Inserir Comentários_todos']);
	$portfolio_excluirPost        = getNivelCheckbox($conteudoAssociativo['portfolio_Excluir Post_professor'], 
													 $conteudoAssociativo['portfolio_Excluir Post_monitor'], 
													 $conteudoAssociativo['portfolio_Excluir Post_aluno'], 
													 $conteudoAssociativo['portfolio_Excluir Post_todos']);
	$portfolio_adicionarLinks     = getNivelCheckbox($conteudoAssociativo['portfolio_Adicionar Links_professor'], 
													 $conteudoAssociativo['portfolio_Adicionar Links_monitor'], 
													 $conteudoAssociativo['portfolio_Adicionar Links_aluno'], 
													 $conteudoAssociativo['portfolio_Adicionar Links_todos']);
	$portfolio_adicionarArquivos  = getNivelCheckbox($conteudoAssociativo['portfolio_Adicionar Arquivos_professor'], 
													 $conteudoAssociativo['portfolio_Adicionar Arquivos_monitor'], 
													 $conteudoAssociativo['portfolio_Adicionar Arquivos_aluno'], 
													 $conteudoAssociativo['portfolio_Adicionar Arquivos_todos']);
	$forum_criarTopico            = getNivelCheckbox($conteudoAssociativo['forum_Criar Tópico_professor'], 
													 $conteudoAssociativo['forum_Criar Tópico_monitor'], 
													 $conteudoAssociativo['forum_Criar Tópico_aluno'], 
													 $conteudoAssociativo['forum_Criar Tópico_todos']);
	$forum_editarTopico           = getNivelCheckbox($conteudoAssociativo['forum_Editar Tópico_professor'], 
													 $conteudoAssociativo['forum_Editar Tópico_monitor'], 
													 $conteudoAssociativo['forum_Editar Tópico_aluno'], 
													 $conteudoAssociativo['forum_Editar Tópico_todos']);
	$forum_excluirTopico          = getNivelCheckbox($conteudoAssociativo['forum_Excluir Tópico_professor'], 
													 $conteudoAssociativo['forum_Excluir Tópico_monitor'], 
													 $conteudoAssociativo['forum_Excluir Tópico_aluno'], 
													 $conteudoAssociativo['forum_Excluir Tópico_todos']);
	$forum_responderTopico        = getNivelCheckbox($conteudoAssociativo['forum_Responder Tópico_professor'], 
													 $conteudoAssociativo['forum_Responder Tópico_monitor'], 
													 $conteudoAssociativo['forum_Responder Tópico_aluno'], 
													 $conteudoAssociativo['forum_Responder Tópico_todos']);
	$forum_editarResposta         = getNivelCheckbox($conteudoAssociativo['forum_Editar Resposta_professor'], 
													 $conteudoAssociativo['forum_Editar Resposta_monitor'], 
													 $conteudoAssociativo['forum_Editar Resposta_aluno'], 
													 $conteudoAssociativo['forum_Editar Resposta_todos']);
	$forum_excluirResposta        = getNivelCheckbox($conteudoAssociativo['forum_Excluir Resposta_professor'], 
													 $conteudoAssociativo['forum_Excluir Resposta_monitor'], 
													 $conteudoAssociativo['forum_Excluir Resposta_aluno'], 
													 $conteudoAssociativo['forum_Excluir Resposta_todos']);
	$arte_criarDesenho            = getNivelCheckbox($conteudoAssociativo['arte_Criar Desenho_professor'], 
													 $conteudoAssociativo['arte_Criar Desenho_monitor'], 
													 $conteudoAssociativo['arte_Criar Desenho_aluno'], 
													 $conteudoAssociativo['arte_Criar Desenho_todos']);
	$arte_comentarDesenho         = getNivelCheckbox($conteudoAssociativo['arte_Comentar Desenho_professor'], 
													 $conteudoAssociativo['arte_Comentar Desenho_monitor'], 
													 $conteudoAssociativo['arte_Comentar Desenho_aluno'], 
													 $conteudoAssociativo['arte_Comentar Desenho_todos']);
	$pergunta_criarQuestionario   = getNivelCheckbox($conteudoAssociativo['pergunta_Criar Questionário_professor'], 
													 $conteudoAssociativo['pergunta_Criar Questionário_monitor'], 
													 $conteudoAssociativo['pergunta_Criar Questionário_aluno'], 
													 $conteudoAssociativo['pergunta_Criar Questionário_todos']);
	$pergunta_criarPergunta       = getNivelCheckbox($conteudoAssociativo['pergunta_Criar Pergunta_professor'], 
													 $conteudoAssociativo['pergunta_Criar Pergunta_monitor'], 
													 $conteudoAssociativo['pergunta_Criar Pergunta_aluno'], 
													 $conteudoAssociativo['pergunta_Criar Pergunta_todos']);
	$pergunta_editarQuestionario  = getNivelCheckbox($conteudoAssociativo['pergunta_Editar Questionário_professor'], 
													 $conteudoAssociativo['pergunta_Editar Questionário_monitor'], 
													 $conteudoAssociativo['pergunta_Editar Questionário_aluno'], 
													 $conteudoAssociativo['pergunta_Editar Questionário_todos']);
	$pergunta_editarPergunta      = getNivelCheckbox($conteudoAssociativo['pergunta_Editar Pergunta_professor'], 
													 $conteudoAssociativo['pergunta_Editar Pergunta_monitor'], 
													 $conteudoAssociativo['pergunta_Editar Pergunta_aluno'], 
													 $conteudoAssociativo['pergunta_Editar Pergunta_todos']);
	$pergunta_deletarQuestionario = getNivelCheckbox($conteudoAssociativo['pergunta_Deletar Questionário_professor'], 
													 $conteudoAssociativo['pergunta_Deletar Questionário_monitor'], 
													 $conteudoAssociativo['pergunta_Deletar Questionário_aluno'], 
													 $conteudoAssociativo['pergunta_Deletar Questionário_todos']);
	$pergunta_deletarPergunta     = getNivelCheckbox($conteudoAssociativo['pergunta_Deletar Pergunta_professor'], 
													 $conteudoAssociativo['pergunta_Deletar Pergunta_monitor'], 
													 $conteudoAssociativo['pergunta_Deletar Pergunta_aluno'], 
													 $conteudoAssociativo['pergunta_Deletar Pergunta_todos']);
	$player_inserirVideos         = getNivelCheckbox($conteudoAssociativo['player_Inserir Vídeos_professor'], 
													 $conteudoAssociativo['player_Inserir Vídeos_monitor'], 
													 $conteudoAssociativo['player_Inserir Vídeos_aluno'], 
													 $conteudoAssociativo['player_Inserir Vídeos_todos']);
	$player_inserirComentario     = getNivelCheckbox($conteudoAssociativo['player_Comentar Vídeos_professor'], 
													 $conteudoAssociativo['player_Comentar Vídeos_monitor'], 
													 $conteudoAssociativo['player_Comentar Vídeos_aluno'], 
													 $conteudoAssociativo['player_Comentar Vídeos_todos']);
	$player_deletarVideos         = getNivelCheckbox($conteudoAssociativo['player_Deletar Vídeos_professor'], 
													 $conteudoAssociativo['player_Deletar Vídeos_monitor'], 
													 $conteudoAssociativo['player_Deletar Vídeos_aluno'], 
													 $conteudoAssociativo['player_Deletar Vídeos_todos']);
	$player_deletarComentario     = getNivelCheckBox($conteudoAssociativo['player_Deletar Comentário_professor'], 
													 $conteudoAssociativo['player_Deletar Comentário_monitor'], 
													 $conteudoAssociativo['player_Deletar Comentário_aluno'], 
													 $conteudoAssociativo['player_Deletar Comentário_todos']);
	$aulas_criarAulas             = getNivelCheckbox($conteudoAssociativo['aulas_Criar Aulas_professor'], 
													 $conteudoAssociativo['aulas_Criar Aulas_monitor'], 
													 $conteudoAssociativo['aulas_Criar Aulas_aluno'], 
													 $conteudoAssociativo['aulas_Criar Aulas_todos']);
	$aulas_editarAulas            = getNivelCheckbox($conteudoAssociativo['aulas_Editar Aulas_professor'], 
													 $conteudoAssociativo['aulas_Editar Aulas_monitor'], 
													 $conteudoAssociativo['aulas_Editar Aulas_aluno'], 
													 $conteudoAssociativo['aulas_Editar Aulas_todos']);
	$aulas_importarAulas          = getNivelCheckbox($conteudoAssociativo['aulas_Importar Aulas_professor'], 
													 $conteudoAssociativo['aulas_Importar Aulas_monitor'], 
													 $conteudoAssociativo['aulas_Importar Aulas_aluno'], 
													 $conteudoAssociativo['aulas_Importar Aulas_todos']);

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
			  portfolio_visualizarPost, portfolio_inserirPost, portfolio_editarPost, portfolio_inserirComentarios, portfolio_excluirPost, portfolio_adicionarLinks, portfolio_adicionarArquivos,
			  forum_criarTopico, forum_editarTopico, forum_excluirTopico, forum_responderTopico, forum_editarResposta, forum_excluirResposta,
			  arte_criarDesenho, arte_comentarDesenho,
			  pergunta_criarQuestionario, pergunta_criarPergunta, pergunta_editarQuestionario, pergunta_editarPergunta, pergunta_deletarQuestionario, pergunta_deletarPergunta,
			  player_inserirVideos, player_deletarVideos, player_inserirComentario, player_deletarComentario,
			  aulas_criarAulas, aulas_editarAulas, aulas_importarAulas)
																			VALUES ($codTurma, '$conteudoCheckboxesGerenciamento',
			  $comunicador_terreno, $comunicador_turma, $comunicador_privado, $comunicador_amigo,
			  $biblioteca_enviarMateriais, $biblioteca_editarMateriais, $biblioteca_excluirArquivos, $biblioteca_aprovarMateriais,
			  $blog_inserirPost, $blog_editarPost, $blog_inserirComentarios, $blog_excluirPost, $blog_adicionarLinks, $blog_adicionarArquivos,
			  $portfolio_visualizarPost, $portfolio_inserirPost, $portfolio_editarPost, $portfolio_inserirComentarios, $portfolio_excluirPost, $portfolio_adicionarLinks, $portfolio_adicionarArquivos,
			  $forum_criarTopico, $forum_editarTopico, $forum_excluirTopico, $forum_responderTopico, $forum_editarResposta, $forum_excluirResposta,
			  $arte_criarDesenho, $arte_comentarDesenho,
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
