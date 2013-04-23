<?php
class comment { //estrutura para o item post do blog
	var $id = 0;
	var $postId = 0;
	var $userId = 0;
	var $text = 0;
	var $date = '';
	var $author = "?";
	var $e = false;

	function open($id_comment){
		global $tabela_portfolioComentarios;
		$q = new conexao();
		$q->solicitar("select * from $tabela_portfolioComentarios where codComentario = $id_comment");
		if(isset($q->itens[0])) {
			$a = $q->itens[0];
			$this->id = $a['id'];
			$this->postId = $a['id_video'];
			$this->userId = $a['userId'];
			$this->text = $a['comentario'];
			$this->date = $a['data'];
			$this->author = new Usuario();
			$this->author->openUsuario($this->userId);
		} else {
			$this->e = "Id informado não existe na tabela de comentarios";
		}
	}

	function save(){
		global $tabela_portfolioComentarios;
		$q = new conexao();
		if($this->id == 0) {
			$q->inserir($this->toDBArray(),$tabela_portfolioComentarios);
			$this->id = $q->ultimo_id();
		}else
			echo "ERRO 0xB4DC0FEE";
			// Tá dando esse erro porque a função abaixo nunca foi implementada já que teoricamente nunca seria usada
			//$q->atualizar($this->id,$this->toDBArray(),$tabela_portfolioComentarios);
	}

	function toDBArray(){
		unset($dados);
		$dados['codComentario'] = $this->id;
		$dados['codPost'] = $this->postId;
		$dados['codUsuario'] = $this->userId;
		$dados['texto'] = $this->text;
		$dados['data'] = $this->date;
		return($dados);
	}
	
	function Comment($id=0, $post_id="", $user_id="", $text="", $date=""){
		if($text != ""){
			$this->id = $id;
			$this->postId = $post_id;
			$this->userId = $user_id;
			$this->text = $text;
			$this->date = $date;
			$this->author = new Usuario();
			if($this->userId!="")
				$this->author->openUsuario($this->userId);
		}
	}

	function getId() {
		return $this->id;
	}

	function setId($id) {
		$this->id = $id;
	}

	function getText() {
		return $this->text;
	}

	function getDate($format="d/m/Y H:i:s") {
		if($format=="")
			$r = $this->date;
		else
			$r = date($format,strtotime($this->date));
		return $r;
	}

	function getAuthor() {
		return $this->author;
	}
	
	function getUserId() { // Necessário pra deletar comments
		return $this->userId;
	}
}

class lista_comments{
	private $titulo;
	private $id;
	public  $comments;
	
	function getId()		{return $this->id;}
	function getTitulo()	{return $this->titulo;}
	
	function setId($id)		{$this->id			= $id;}
	function setTitulo($tit){$this->titulo		= $tit;}
	
	function setComments(){
		global $tabela_portfolioComentarios;
		$q = new conexao();
		$id = $this->getId();
		
		$q->solicitar("SELECT * FROM $tabela_portfolioComentarios WHERE codPost = $id ORDER BY data DESC");
		if($q->registros > 0){
			foreach($q->itens as $p){
				$this->comments[] = new comment($p['codComentario'], $p['codPost'], $p['codUsuario'], $p['texto'], $p['data']);
			}
		}
	}
	
	function lista_comments($id){
		global $tabela_portfolioPosts; global $tabela_usuarios;
		$q = new conexao();
		$q->solicitar("SELECT * FROM $tabela_portfolioPosts WHERE id = $id");
		if($q->registros == 0){
			echo "Não existe post com esse ID. Por acaso ele não foi deletado?";
		}else{
			if($q->erro){
				echo $q->erro;
			}else{
				$this->setId($q->resultado['id']);
				$this->setTitulo($q->resultado['titulo']);
				$this->setComments();
			}
		}
	}
}
