<?php
session_start();
require("blog.class.php");
require("../../usuarios.class.php");
require("../../funcoes_aux.php");
require("../../cfg.php");
require("../../bd.php");
$usuario_id = $_SESSION['SS_usuario_id'];
global $tabela_posts;
global $tabela_blogs;
global $tabela_imagem_blog;
global $tabela_comentarios;
global $tabela_tags;

if (isset($_GET['id'])){
	$b = new Blog($_GET['id']);
	$id = $_GET['id'];
	if (checa_nivel($_SESSION['SS_usuario_nivel_sistema'], $nivelProfessor) or $b->isOwner($_SESSION['SS_usuario_id'])){
		$a=new conexao();
		$c=new conexao();
		
		$c->solicitar("SELECT Id FROM $tabela_posts WHERE BlogId = $id");
		if ($c->erro!=""){die($c->erro);}
		$postCount = count($c->itens);
		for ($i=0;$i<$postCount; $i++){
			$idcom = $c->resultado['Id'];
			$a->solicitar("DELETE FROM $tabela_comentarios WHERE PostId = $idcom");
			if ($a->erro!=""){die($a->erro);}
			$c->proximo();
		}
		$c=$postCount=NULL; // free($ram);
		
		$a->solicitar("DELETE FROM $tabela_posts WHERE BlogId = $id");
		if ($a->erro!=""){die($a->erro);}
		$a->solicitar("DELETE FROM $tabela_blogs WHERE Id = $id");
		if ($a->erro!=""){die($a->erro);}
		$a->solicitar("DELETE FROM $tabela_imagem_blog WHERE id = $id");
		if ($a->erro!=""){die($a->erro);}
		$a->solicitar("DELETE FROM $tabela_tags WHERE BlogId = $id");
		if ($a->erro!=""){die($a->erro);}
		
		echo "el blog esta muerto";
	} else {
		echo "Você não tem as permissões necessárias para apagar este blog.";
	}
} else {
	echo "Favor acessar essa página com uma id setada muito obrigado até logo desculpequalquercoisaesperoquegostedoplanetarooda";
}

?>
