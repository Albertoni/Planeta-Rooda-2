<?php
	require_once("../../cfg.php");
	require_once("../../bd.php");
	require_once("../../funcoes_aux.php");
	require_once("../../usuarios.class.php");
	require_once("comentarios.class.php");
	
	session_start();
	
	$user_id = isset($_SESSION['SS_usuario_id']) ? $_SESSION['SS_usuario_id'] : die("Voc&ecirc; precisa estar logado para fazer isso");
	
	global $tabela_biblioComentarios;
	global $tabela_usuarios;
	global $nivelAdmin;
	
	if(isset($_POST['delete']) and isset($_POST['cid'])){
		if (is_numeric($_POST['cid'])){
			$consulta = new conexao();
			$nivel = new conexao();
			$consulta->solicitar("SELECT codUsuario FROM $tabela_biblioComentarios WHERE codComentario = ".$_POST['cid']);
			$nivel->solicitar("SELECT usuario_nivel FROM $tabela_usuarios WHERE usuario_id = ".$consulta->resultado['codUsuario']);
			if ($consulta->resultado['codUsuario'] == $user_id or (checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivel->resultado['usuario_nivel']) === "xyzzy") or checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelAdmin)){ // Só apaga se for o usuario, tem conta de nivel maior ou admin. Admin pode tudo.
				$consulta->solicitar("DELETE FROM $tabela_biblioComentarios WHERE codComentario = ".$_POST['cid']);
				unset($consulta);
			}else{
				die("Você passou uma ID não-numérica de comentário. Traduzindo: Por favor tente de novo, isso deve se consertar sozinho.");
			}
		}
	}
	
	$post_id = (isset($_POST['post_id']) and is_numeric($_POST['post_id'])) ? $_POST['post_id'] : die("N&atilde;o foi fornecida a id do desenho!");


function alternador($classe1, $classe2) { // Para a tela nova dos comentários.
	static $show = true;
	$show = !$show;
	if ($show) {
		return $classe1;
	} else {
		return $classe2;
	}
}

if(isset($_POST['comment_text']) and $_POST['comment_text']!=""){
	$comment = new Comment(0, $post_id, $user_id, str_replace("<","&lt;",$_POST['comment_text']), date('Y-m-d H:i:s')); // str_replace previne injetar umas tags html.
	$comment->save();
}

$desenho = new listaComment($post_id);

//print_r($desenho);

$html  = "<h1>".fullUpper($desenho->getTitle())."</h1>\n";
$html .= "<img src=\"../../images/botoes/bt_fechar.png\" class=\"fechar_coments\" onmousedown=\"abreFechaLB()\" />\n";
$html .= "<div class=\"recebe_coments\">\n";
$html .= "Número de comentários: ".$desenho->tamLista."\n";
$html .= "<ul class=\"sem_estilo\" id=\"ie_coments\">\n";  
$html .= "<ul>\n";
if($desenho->tamLista == 0){
	$html .= "Nenhum comentário inserido até o momento.\n";
}else{
	foreach($desenho->lista as $c) {
		$comentario = ""; // LIMPA O LIXO QUE TU FEZ, BOLSISTA
	
		$comentario .= "<li class=\"tabela_blog\">\n";
		
		$comentario .= $c->getAuthor()->getName()."-".$c->getText()."\n";
		if ($c->getUserId() == $_SESSION['SS_usuario_id'])
			$comentario .= "<br /><a onClick=\"loadComentarios('light_box','comentarios.php','post_id=".$desenho->getId()."&cid=".$c->getId()."&delete=1')\">Deletar este comentário</a>";
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
$html .= "<input type=\"image\" src=\"../../images/botoes/bt_confir_pq.png\" onclick=\"loadComentarios('light_box','comentarios.php','post_id=".$desenho->getId()."&comment_text='+document.getElementById('TAComment').value)\" />\n";
$html .= "</div>\n";
$html .= "</li>\n";
$html .= "</ul>\n";
$html .= "</div>\n";

echo $html;
?>
