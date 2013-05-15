<?php
/*
* sistema de busca do forum
*/
	session_start();
	
	require("../../cfg.php");
	require("../../bd.php");
	require("../../funcoes_aux.php");
	require("verifica_user.php");
	require("sistema_forum.php");
	require("visualizacao_forum.php");
	
	$_SESSION['SS_forum_pesq_consulta'] = isset($_POST['consulta'])? stripslashes($_POST['consulta']) : $_SESSION['SS_forum_pesq_consulta'];
	$_SESSION['SS_forum_pesq_tipo'] = isset($_POST['tipo'])? stripslashes($_POST['tipo']) : $_SESSION['SS_forum_pesq_tipo'];
	$pagina = (isset($_POST['pagina']))? stripslashes($_POST['pagina']) : 1;
	$tipo = $_SESSION['SS_forum_pesq_tipo'];
	$consulta = $_SESSION['SS_forum_pesq_consulta'];
	
	if (($VERIFICA_USER_ERRO_ID == 0)&&($consulta = string2consulta($tipo, $consulta))) {
		$FORUM = new forum($FORUM_ID);
		$FORUM->pesquisa($pagina, $tipo, $consulta);
		
		$paginas = array();
		$paginas = $FORUM->paginas($pagina,10);
		if ($FORUM->contador > 0){
	
			mostraPaginas ($paginas, $pagina, true, "forum_procurar.php?fid=$FORUM_ID");
			echo '<div id="topicos" class="bloco">';
			echo '<h1>RESULTADOS DA PESQUISA</h1>';

			$forum_msg_cont = count($FORUM->mensagem);
			for ($c=0; $c<$forum_msg_cont; $c++){
				$mens = $FORUM->mensagem[$c];
				mostraPesquisa ($mens->msgId,$mens->msgPai,$mens->msgUserName,$mens->msgTitulo,$mens->msgTexto,$mens->msgData,($c % 2), $mens->msgEditavel);
			}
			echo '</div>';
			mostraPaginas ($paginas, $pagina, true, "forum_procurar.php?turma=$FORUM_ID");
			echo '<script>forum_pg = '.$pagina.'</script>';

		}else{
			echo '<div id="topicos" class="bloco">';
			echo '<h1>RESULTADOS DA PESQUISA</h1>';
			mostraAviso(7);
			echo '</div>';
		}
	}else{
		echo '<div id="topicos" class="bloco">';
		echo '<h1>RESULTADOS DA PESQUISA</h1>';
		if ($VERIFICA_USER_ERRO_ID == 0)
			mostraAviso(9);
		else
			mostraAviso($VERIFICA_USER_ERRO_ID);
		echo '</div>';
	}
	

?>
