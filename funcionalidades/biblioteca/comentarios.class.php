<?php
class Comment {
	var $id = 0;
	var $postId = 0;
	var $userId = 0;
	var $text = 0;
	var $date = '';
	var $author = "?";
	var $e = false; // ERROS VÃO AQUI

	function open($id_comment) {
		global $tabela_biblioComentarios;
		$q = new conexao();
		$q->solicitar("SELECT * FROM $tabela_biblioComentarios WHERE codComentario = $id_comment");
		if(isset($q->itens[0])) {
			$a = $q->itens[0];
			$this->id = $a['codComentario'];
			$this->postId = $a['codMaterial'];
			$this->userId = $a['codUsuario'];
			$this->text = $a['comentario'];
			$this->date = $a['data'];
			$this->author = new Usuario();
			$this->author->openUsuario($this->userId);
		} else {
			$this->e = "Id informado não existe na tabela de comentarios";
		}
	}

	function save() {
		global $tabela_biblioComentarios;
		$q = new conexao();
		if($this->id == 0) {
			$q->inserir($this->toDBArray(),$tabela_biblioComentarios);
			$this->id = $q->ultimo_id();
		} else
			$q->atualizar($this->id,$this->toDBArray(),$tabela_biblioComentarios);
	}

	function toDBArray() {
		unset($dados);
		$dados['codComentario']	= $this->id;
		$dados['codMaterial']	= $this->postId;
		$dados['codUsuario']	= $this->userId;
		$dados['comentario']	= $this->text;
		$dados['data']			= $this->date;
		return($dados);
	}
	
	function Comment($id=0, $desenho_id="", $user_id="", $text="", $date=""){
		if($text != ""){
			$this->id = $id;
			$this->postId = $desenho_id;
			$this->userId = $user_id;
			$this->text = $text;
			$this->date = $date;
			$this->author = new Usuario();
			if($this->userId != "")
				$this->author->openUsuario($this->userId);
		}
	}
	
	function getDate($format="d/m/Y H:i:s") {
		if($format=="")
			$r = $this->date;
		else
			$r = date($format,strtotime($this->date));
		return $r;
	}

	function setId($id)	{$this->id = $id;}

	function getId()	{return $this->id;}
	function getText()	{return $this->text;}
	function getAuthor(){return $this->author;}
	function getUserId(){return $this->userId;} // Necessário pra deletar comments
}

class listaComment{
	var $lista = array();
	var $tamLista = 0;
	var $titulo = "";
	var $id = 0;
	
	private function setId($id){$this->id = $id;}
	public function getId(){return $this->id;}
	
	function listaComment($idPost){
		global $tabela_biblioComentarios;
		$this->setId($idPost);
		$pegador = new conexao();
		$pegador->solicitar("SELECT codComentario FROM $tabela_biblioComentarios WHERE codMaterial = $idPost"); // Pega todos os comentários
		for ($i=0; $i < $pegador->registros; $i++){
			$this->lista[$i] = new Comment();
			$this->lista[$i]->open($pegador->resultado['codComentario']); // Bota eles em uma lista
			$pegador->proximo();
		}
		$this->tamLista = sizeof($this->lista); // tamanho da lista
	}
	
	function getTitle(){
		if ($this->titulo === ""){
			global $tabela_Materiais;
			$tit = new conexao(); // boobiez
			$tit->solicitar("SELECT titulo FROM $tabela_Materiais WHERE codMaterial = ".$this->id);
			$this->titulo = $tit->resultado['titulo'];
			return $this->titulo;
		}else{
			return $this->titulo;
		}
	}
}
?>
