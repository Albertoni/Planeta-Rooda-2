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

	function salvar($conexao = NULL){
		$q = new conexao();

		if($this->existe){
			$query = "UPDATE $tabela_portfolioPosts SET 
				projeto_id = '$projeto_id',
				user_id = '$user_id',
				titulo = '$titulo',
				texto = '$texto',
				tags = '$tags',
				dataCriacao = '$dataCriacao',
				dataUltMod = '$dataUltMod'
			WHERE id = '$id'";
		}else{
			$query = "INSERT INTO $tabela_portfolioPosts VALUES(
				'$projeto_id',
				'$user_id',
				'$titulo',
				'$texto',
				'$tags',
				'$dataCriacao',
				'$dataUltMod')";
		}
		
		if($q->erro == ""){
			# code...
		}
	}
}


class projeto{
	private $id = 0;
	private $title = "";
	private $dataInicio;
	private $dataFim;
	private $ownersIds = array();

	private $posts = array();
	private $tags = array();

	private $existe = 0;
	private $turma = 0;

	function __construct(	$id = 0,
							$title = "",
							$palavras = "",
							$dataInicio = 0;
							$dataFim = 0;
							$ownersIds = array()
						){
		if($id === 0){
			$this->id = 0;
			$this->title = $title;
			$this->palavras = $palavras;
			$this->dataInicio = $dataInicio;
			$this->dataFim = $dataFim;
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
			$this->dataInicio = $q->resultado['dataCriacao'];
			$this->dataFim = $q->resultado['dataEncerramento'];
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
			$query = "UPDATE $tabela_portfolioPosts SET 
			projeto_id = ,
			user_id = ,
			titulo = ,
			texto = ,
			tags = ,
			"
		}else{
			# code...
		}
		
	}
}