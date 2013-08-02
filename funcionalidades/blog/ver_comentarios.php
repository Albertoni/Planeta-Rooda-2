<?php
	session_start();
	
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	require_once("blog.class.php");
	$user_id = $_SESSION['SS_usuario_id'];
	$post_id = (isset($_POST['post_id']) and is_numeric($_POST['post_id'])) ? $_POST['post_id'] : die("não foi fornecido id de post");
	
	global $tabela_comentarios;
	global $nivelAdmin;
	
	if(isset($_POST['delete']) and isset($_POST['cid'])){
		if (is_numeric($_POST['cid'])) {
			$consulta = new conexao();
			$consulta->solicitar("SELECT UserId FROM $tabela_comentarios WHERE Id = ".$_POST['cid']);
			if ($consulta->resultado['UserId'] == $user_id or checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelAdmin)){ // Só apaga se for o usuario. Ou admin. Admin pode tudo.
				$consulta->solicitar("DELETE FROM $tabela_comentarios WHERE Id = ".$_POST['cid']);
				unset($consulta);
			} else {
				die("THIS ARE SERIOUS SYSTEM. SERIOUS CONSEQUENCES WILL NEVER BE THE SAME FOR SQL INJECTION");
			}
		}
	}



function alternador($classe1, $classe2) { // Para a tela nova dos comentários.
	static $show = true;
	$show = !$show;
	if ($show) {
		return $classe1;
	} else {
		return $classe2;
	}
}

	
	
	if(isset($_POST['comment_text']) and $_POST['comment_text']!="") {
		$comment = new Comment(0, $post_id, $user_id, str_replace("<","&lt;",$_POST['comment_text']), date('Y-m-d H:i:s')); // str_replace previne injetar umas tags html.
		$comment->save();
	}
	
	$post = new Post();
	$post->open($post_id);
	$html = "";
	$html = "<h1>".$post->getTitle()."</h1>\n";
	$html .= "<img src=\"images/botoes/bt_fechar.png\" class=\"fechar_coments\" onmousedown=\"abreFechaLB()\" />\n";
	$html .= "<div class=\"recebe_coments\">\n";
	$html .= "Número de comentários: " . sizeof($post->comments). "\n";
	$html .= "<ul class=\"sem_estilo\" id=\"ie_coments\">\n";  
	$html .= "<ul>\n";
	if(sizeof($post->comments)==0) {
		$html .= "Nenhum comentário inserido até o momento.\n";
	} else {
		$comentario = "";
		foreach($post->comments as $c) {
		
			//<div class="cor_comentarios">
			
			$comentario .= "<li class=\"tabela_blog\">\n";
			
			$comentario .= utf8_decode($c->getAuthor()->getName()) . "-" . utf8_decode($c->getText()) ."\n";
			if ($c->getUserId() == $_SESSION['SS_usuario_id'])
				$comentario .= "<br /><a class=\"mata_comentario\" onClick=\"carregaHTML('light_box','ver_comentarios','post_id=".$post->getId()."&cid=".$c->getId()."&delete=1')\">Deletar este comentário</a>";
			$comentario .= "</li>";
			
			// </div>
			
			$html .= alternador("<div class=\"cor_comentarios\">".$comentario."</div>", $comentario);
			
			$comentario = ""; // LIMPA O LIXO QUE TU FEZ, BOLSISTA
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
	$html .= "<input type=\"image\" src=\"images/botoes/bt_confir_pq.png\" onclick=\"carregaHTML('light_box','ver_comentarios','post_id=".$post->getId()."&comment_text='+document.getElementById('TAComment').value)\" />\n";
	$html .= "</div>\n";
	$html .= "</li>\n";
	$html .= "</ul>\n";
	$html .= "</div>\n";

	echo utf8_encode($html);
