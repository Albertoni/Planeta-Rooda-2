<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../funcoes_aux.php");
require_once("../../usuarios.class.php");
require_once ('portfolio.class.php');

$user = usuario_sessao();

$turma = is_numeric($_POST['turma']) ? $_POST['turma'] : die("Um identificador de turma inv&aacute;lido foi enviado para essa p&aacute;gina.");

$perm = checa_permissoes(TIPOPORTFOLIO, $turma);
if($perm == false){
	die("Desculpe, mas os Projetos est&atilde;o desabilitados para esta turma.");
}

$consulta = new conexao();
$projeto_id_sanitizado = $consulta->sanitizaString($_POST['projeto_id']);

$consulta->solicitar("SELECT turma FROM $tabela_portfolioProjetos WHERE id = $projeto_id_sanitizado");
if($turma != $consulta->resultado['turma']){
	die("A identifica&ccedil;&atilde;o de turma passada para essa pagina n&atilde;o corresponde com a identifica&ccedil;&atilde;o de turma que o projeto tem. Isso &eacute; um erro.");
}

/*function __construct($id, $dados = false){
		if($dados !== false){
			$this->id			= $dados['id'];
			$this->projeto_id	= $dados['projeto_id'];
			$this->user_id		= $dados['user_id'];
			$this->titulo		= $dados['titulo'];
			$this->texto		= $dados['texto'];
			$this->tags			= $dados['tags'];
			$this->dataCriacao	= $dados['dataCriacao'];
			$this->dataUltMod	= $dados['dataUltMod'];
		}else{
			$this->carrega($id);
		}
	}

Array
(
    [text] => 5hureingkjfdgfd
    [x] => 129
    [y] => 7
    [titulo_post] => dasdasdasdas
    [tags_post] => dsadas
    [projeto_id] => 44
    [post_id] => 0
    [update] => 0
    [turma] => 1081
)



Notice: Undefined index: dataCriacao in /var/www/prjoao/funcionalidades/portfolio/portfolio.class.php on line 23

Notice: Undefined index: dataUltMod in /var/www/prjoao/funcionalidades/portfolio/portfolio.class.php on line 24

Notice: Undefined property: post::$existe in /var/www/prjoao/funcionalidades/portfolio/portfolio.class.php on line 67

Notice: Undefined variable: tabela_portfolioPosts in /var/www/prjoao/funcionalidades/portfolio/portfolio.class.php on line 79
Array ( [text] => dsadsadas [x] => 43 [y] => 29 [titulo] => dasdas [tags] => dasdas [projeto_id] => 44 [post_id] => 0 [update] => 0 [turma] => 1081 [dataAtual] => 1391545396 ) 


	*/

if ($_POST['update'] == 1){
	$post = new post($_POST['post_id']);
	
	$post->setTitulo($_POST['titulo']);
	$post->setTexto($_POST['text']);
	$post->setTags($_POST['tags']);

	echo $post->salvar();
}else{
	$dados = array(
		'id' => $_POST['post_id'],
		'projeto_id' => $_POST['projeto_id'],
		'user_id' => $user->getId(),
		'titulo' => $_POST['titulo'],
		'texto' => $_POST['text'],
		'tags' => $_POST['tags']
		);

	$post = new post(0, $dados);
	echo $post->salvar();
}

print_r($post);


//magic_redirect("portfolio_projeto.php?projeto_id=$projeto_id&turma=$turma");
?>
