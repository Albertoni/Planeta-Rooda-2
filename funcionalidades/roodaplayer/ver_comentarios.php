<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	require_once("player_aux.php");


	$user_id = $_SESSION['SS_usuario_id'];
	$post_id = (isset($_POST['vid_id']) and is_numeric($_POST['vid_id'])) ? $_POST['vid_id'] : die("não foi fornecido id de post");
	
	$turma = (isset($_POST['vid_id']) and is_numeric($_POST['codTurma'])) ? $_POST['codTurma'] : die("não foi fornecido id de turma");
	
	$usuario = new Usuario();
	$usuario->openUsuario($user_id);

	global $tabela_playerComentarios;
	global $nivelAdmin;

$permissoes = checa_permissoes(TIPOPLAYER, $turma);
if ($permissoes === false){die("Funcionalidade desabilitada para a sua turma.");}


	if(isset($_POST['delete']) and isset($_POST['cid'])){
		if (is_numeric($_POST['cid'])){
			$consulta = new conexao();
			$consulta->solicitar("SELECT UserId FROM $tabela_comentarios WHERE Id = ".$_POST['cid']);
			
			if ($usuario->podeAcessar($permissoes['player_deletarComentario'], $turma)){
				$consulta->solicitar("DELETE FROM $tabela_playerComentarios WHERE Id = ".$_POST['cid']);
				unset($consulta);
			}else{
				die("Voce nao tem permissoes para apagar esse comentario.");
			}
		}else{
			die("Ou voce tentou hackear o sistema, ou algum dado foi corrompido na transmissão. Favor voltar e tentar novamente.");
		}
	}



function alternador($classe1, $classe2) { // Para a tela nova dos comentários.
	static $show = true;
	$show = !$show;
	if ($show) {
		return $classe1;
	}else{
		return $classe2;
	}
}

	
	
	if(isset($_POST['comment_text']) and ($_POST['comment_text']!="") and $usuario->podeAcessar($permissoes['player_inserirComentario'], $turma)){
		$comment = new Comment(0, $post_id, $user_id, str_replace("<","&lt;",$_POST['comment_text']), date('Y-m-d H:i:s')); // str_replace previne injetar umas tags html.
		$comment->save();
	}
	
	$post = new video(true, $post_id);
	
	$html = "<h1>".$post->getTitulo()."</h1>\n";
	$html.= "<img src=\"../../images/botoes/bt_fechar.png\" class=\"fechar_coments\" onmousedown=\"document.getElementById('embede').style.visibility='visible';abreFechaLB()\" />\n";
	$html.= "<div class=\"recebe_coments\">\n";
	$html.= "Número de comentários: " . sizeof($post->comments). "\n";
	$html.= "<ul class=\"sem_estilo\" id=\"ie_coments\">\n";
	$html.= "<ul>\n";
	if(sizeof($post->comments)==0) {
		$html .= "Nenhum comentário inserido até o momento.\n";
	} else {
		foreach($post->comments as $singleComment) {
		
			//<div class="cor_comentarios">
			
			$comentario = "<li class=\"tabela_blog\">\n";
			
			$comentario .= $singleComment->getAuthor()->getName() . " - " . $singleComment->getText() ."<br/>\n";
			if ($usuario->podeAcessar($permissoes['player_deletarComentario'], $turma)){
				$comentario .= "<br /><a class=\"mata_comentario\" onClick=\"carregaHTML('light_box','ver_comentarios','vid_id=".$post->getId()."&codTurma=$turma&cid=".$singleComment->getId()."&delete=1')\">Deletar este comentário</a>";
			}
			$comentario .= "</li>";
			
			// </div>
			
			$html .= alternador("<div class=\"cor_comentarios\">".$comentario."</div>", $comentario);
		}
	}

	$html .= "</ul>\n";
	$html .= "<li id=\"novo_coment\">\n";
	$html .= "POSTAR NOVO COMENTÁRIO\n";
	$html .= "</li>\n";
	$html .= "<li>\n";
	$html .= "<textarea class=\"msg_dimensao\" rows=\"10\" id=\"TAComment\"></textarea>\n";
	$html .= "</li>\n";
	$html .= "<li>\n";
	$html .= "<div class=\"enviar\" align=\"right\">";
	$html .= "<input type=\"image\" src=\"../../images/botoes/bt_confir_pq.png\" onclick=\"carregaHTML('light_box','ver_comentarios','vid_id=".$post->getId()."&codTurma=$turma&comment_text='+document.getElementById('TAComment').value)\" />\n";
	$html .= "</div>\n";
	$html .= "</li>\n";
	$html .= "</ul>\n";
	$html .= "</div>\n";

	echo $html;
