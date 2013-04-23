<?php
class Comment {
	var $id = 0;
	var $desenhoId = 0;
	var $userId = 0;
	var $text = 0;
	var $date = '';
	var $author = "?";
	var $e = false; // ERROS VÃO AQUI

	function open($id_comment) {
		global $tabela_ArteComentarios;
		$q = new conexao();
		$q->solicitar("SELECT * FROM $tabela_ArteComentarios WHERE CodDesenho = $id_comment");
		if(isset($q->itens[0])) {
			$a = $q->itens[0];
			$this->id = $a['Id'];
			$this->desenhoId = $a['PostId'];
			$this->userId = $a['UserId'];
			$this->text = $a['Text'];
			$this->date = $a['Date'];
			$this->author = new Usuario();
			$this->author->openUsuario($this->userId);
		} else {
			$this->e = "Id informado não existe na tabela de comentarios";
		}
	}

	function save() {
		global $tabela_ArteComentarios;
		$q = new conexao();
		if($this->id == 0) {
			$q->inserir($this->toDBArray(),$tabela_ArteComentarios);
			$this->id = $q->ultimo_id();
		} else
			$q->atualizar($this->id,$this->toDBArray(),$tabela_ArteComentarios);
	}

	function toDBArray() {
		unset($dados);
		$dados['CodComentario'] = $this->id;
		$dados['CodDesenho'] = $this->desenhoId;
		$dados['CodUsuario'] = $this->userId;
		$dados['Comentario'] = $this->text;
		$dados['Data'] = $this->date;
		return($dados);
	}
	
	function Comment($id=0, $desenho_id="", $user_id="", $text="", $date=""){
		if($text != ""){
			$this->id = $id;
			$this->desenhoId = $desenho_id;
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
?>
