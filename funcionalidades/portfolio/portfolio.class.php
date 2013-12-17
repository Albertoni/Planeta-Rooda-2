<?php
require_once("../../cfg.php");
require_once("../../bd.php");

class post{
	private $id;
	private $projeto_id;
	private $user_id;
	private $titulo;
	private $texto;
	private $tags;
	private $dataCriacao;
	private $dataUltMod;

	function salvar(){
		$q = new conexao();

		if($this->existe){
			$query = "UPDATE $tabela_portfolioPosts SET 
				projeto_id = '$this->projeto_id',
				user_id = '$this->user_id',
				titulo = '$this->titulo',
				texto = '$this->texto',
				tags = '$this->tags',
				dataCriacao = '$this->dataCriacao',
				dataUltMod = '$this->dataUltMod'
			WHERE id = '$this->id'";
		}else{
			$query = "INSERT INTO $tabela_portfolioPosts VALUES(
				'$this->projeto_id',
				'$this->user_id',
				'$this->titulo',
				'$this->texto',
				'$this->tags',
				'$this->dataCriacao',
				'$this->dataUltMod')";
		}
		
		$q->solicitar($query);
		if($q->erro == ""){
			die("N&atilde;o foi possivel SALVAR o post de id '$this->id'.");
		}
	}
}


class projeto{
	private $id = 0;
	private $title = "";
	private $dataCriacao;
	private $dataEncerramento;
	private $ownersIds = array();

	private $posts = array();
	private $tags = array();

	private $existe = 0;
	private $turma = 0;

	function __construct(	$id = 0,
							$title = "",
							$palavras = "",
							$dataCriacao = 0;
							$dataEncerramento = 0;
							$ownersIds = array()
						){
		if($id === 0){
			$this->id = 0;
			$this->title = $title;
			$this->palavras = $palavras;
			$this->dataCriacao = $dataCriacao;
			$this->dataEncerramento = $dataEncerramento;
			$this->ownersIds = $ownersIds;
		}else{
			$this->carrega($id);
		}
	}

	function carrega($idProjeto){
		$q = new conexao();
		$idProjeto = $q->sanitizaString($idProjeto);
		$q->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE id = $idProjeto");

		if($q->registros > 0){
			$this->id = $idProjeto;
			$this->title = $q->resultado['titulo'];
			$this->palavras = $q->resultado['tags'];
			$this->dataCriacao = $q->resultado['dataCriacao'];
			$this->dataEncerramento = $q->resultado['dataEncerramento'];
			$this->ownersIds = explode(",", $q->resultado['owner_ids']);
			$this->existe = 1;

			$this->carregaPosts();
		}else{
			die("Esse projeto não existe.");
		}
	}

	// Confere se o usuário é dono
	function ehDono($userId){
		if(in_array($userId, $this->ownersIds)){
			return true;
		}else{
			return false;
		}
	}

	function carregaPosts(){
		$q = new conexao();
		$q->solicitar("SELECT * FROM $tabela_portfolioPosts WHERE projeto_id = ".$this->projeto_id);

		for($i=0; $i < $q->registros; $i++){
			$newPost = new post($q->resultado['']);

			array_push($this->posts, $newPost);
			$q->proximo();
		}
	}

	function salvar(){
		$q = new conexao();

		if($this->existe){
			$query = "UPDATE $tabela_portfolioProjetos SET 
				titulo = $this->title,
				tags = ".implode(',', $this->palavras).",
				owner_id = ".implode(',', $this->ownersIds).",
				dataCriacao = $this->dataCriacao,
				dataEncerramento = $this->dataEncerramento,
				turma = $this->turma
			WHERE
				id = $this->id";
		}else{
			$query = "INSERT INTO $tabela_portfolioPosts VALUES(
				'$this->id',
				'$this->titulo',
				'".implode(',', $this->palavras)."',
				'1',
				'$this->dataCriacao',
				'$this->dataEncerramento',
				'".implode(',', $this->ownersIds)."',
				'$this->turma')";
		}
		
		if($q->erro == ""){
			$numeroPosts = count($this->posts);

			for($i=0; $i < $numeroPosts; $i++){
				$this->posts[$i]->salvar();
			}
		}else{
			die("Erro ao salvar o projeto, por favor tente novamente em um momento.");
		}
		
	}
}