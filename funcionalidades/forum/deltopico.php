<?php
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	require("sistema_forum.php");
	require("visualizacao_forum.php");
	
	$permissoes = checa_permissoes(TIPOFORUM, $FORUM_ID);
	if($permissoes === false){
		die("Funcionalidade desabilitada para a sua turma. Favor voltar.");
	}
	
	$user = new Usuario();
	$user->openUsuario($_SESSION['SS_usuario_id']);

	$erro = 1;
	if ($user->podeAcessar($permissoes['forum_deletarTopico'], $FORUM_ID)) {
		$erro = 0;
		$topico = stripslashes($_POST['topico']);
		$pagina = stripslashes($_POST['pagina']);
		$deltipo = isset($_POST['deltipo']) ? $_POST['deltipo'] : 10;
		if ($deltipo){
			$tipo = $_SESSION['SS_forum_pesq_tipo'];
			$consulta = $_SESSION['SS_forum_pesq_consulta'];
			$consulta = string2consulta($tipo, $consulta);
		}
		$FORUM = new forum($FORUM_ID);
		$FORUM->configBD($BD_host1,$BD_base1,$BD_user1,$BD_pass1,$tabela_forum,$tabela_usuarios);

		if ($FORUM->pegaDadosDaMensagem($topico)){
			if ($FORUM->mensagem[0]->msgEditavel){
				$pai = $FORUM->mensagem[0]->msgPai;
				$FORUM->excluiMensagem($topico);
				
				switch ($deltipo){
					case 0:
						$FORUM->topicos($pagina);
						$forum_msg_cont = count($FORUM->mensagem);
						if ($forum_msg_cont == 0){
							if ($pagina > 1){
								$pagina--;
								$FORUM->topicos($pagina);
							}else{
								echo '<div id="bloco_mensagens" class="bloco">';
								echo '<h1>TÓPICOS</h1>';
								mostraAviso(6);
								echo '</div>';
								exit;
							}
						}
						$linkPG = "forum.php?fid=$FORUM_ID";
						break;
					case 1:
						$FORUM->pegaMensagensArvore($pai,$pagina, true);
						$forum_msg_cont = count($FORUM->mensagem);
						if ($forum_msg_cont == 0){
							if ($pagina > 1){
								$pagina--;
								$FORUM->pegaMensagensArvore($pai,$pagina, true);
							}else{
								echo '<div id="bloco_mensagens" class="bloco">';
								echo '<h1>MENSAGENS</h1>';
								mostraAviso(6);
								echo '</div>';
								exit;
							}
						}
						$linkPG = "forum_arvore.php?fid=$FORUM_ID&topico=$pai";
						break;
					case 2:
						$FORUM->pesquisa($pagina, $tipo, $consulta);
						$forum_msg_cont = count($FORUM->mensagem);
						if ($forum_msg_cont == 0){
							if ($pagina > 1){
								$pagina--;
								$FORUM->pesquisa($pagina, $tipo, $consulta);
							}else{
								echo '<div id="topicos" class="bloco">';
								echo "<h1>$pagina, $tipo, $consulta $forum_msg_cont</h1>";
								mostraAviso(6);
								echo '</div>';
								exit;
							}
						}
						$linkPG = "forum_procurar.php?fid=$FORUM_ID";
						break;
					default:
						echo '<div id="topicos" class="bloco">';
						echo '<h1>TÓPICOS</h1>';
						mostraAviso(6);
						echo '</div>';
						exit;
				}

	
				$paginas = array();
				$paginas = $FORUM->paginas($pagina,10);
				if ($FORUM->contador > 0){
					mostraPaginas ($paginas, $pagina, false, $linkPG);
					echo ($deltipo == 0)? '<div id="topicos" class="bloco">':'<div id="bloco_mensagens" class="bloco">';
					echo '<h1>'.$FORUM->titulo.'</h1>';
					$forum_msg_cont = count($FORUM->mensagem);
					for ($c=0; $c<$forum_msg_cont; $c++){
						$mens = $FORUM->mensagem[$c];
						switch ($deltipo){
							case 0:
								mostraTopicos($mens->msgId,$mens->msgUserName,$mens->msgTitulo,$mens->msgTexto,$mens->msgData,$mens->msgQntFilhos,($c % 2), $mens->msgEditavel);
								break;
							case 1:
							case 2:
								mostraMensagens($mens->msgId,$mens->msgUserName,$mens->msgTexto,$mens->msgData,($c % 2), $mens->msgEditavel);
								break;
						}
					}
					echo '</div>';
					mostraPaginas ($paginas, $pagina, false, $linkPG);
					echo '<script>forum_pg = '.$pagina.'</script>';
				}else{
					echo '<div id="topicos" class="bloco">';
					echo '<h1>TÓPICOS</h1>';
					mostraAviso(6);
					echo '</div>';
				}
			}
		}
	}
?>
