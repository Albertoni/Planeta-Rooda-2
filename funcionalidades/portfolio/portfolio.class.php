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
	function getId(){return $this->id;}
	function getIdProjeto(){return $this->projeto_id;}
	function getIdUsuario(){return $this->user_id;}
	function getTitulo(){return $this->titulo;}
	function getTexto(){return $this->texto;}
	function getTags(){return $this->tags;}
	function getDataCriacao(){return $this->dataCriacao;}
	function getDataUltMod(){return $this->dataUltMod;}

	function setId($arg){$this->id = $arg;}
	function setIdProjeto($arg){$this->projeto_id = $arg;}
	function setIdUsuario($arg){$this->user_id = $arg;}
	function setTitulo($arg){$this->titulo = $arg;}
	function setTexto($arg){$this->texto = $arg;}
	function setTags($arg){$this->tags = $arg;}
	function setDataCriacao($arg){$this->dataCriacao = $arg;}
	function setDataUltMod($arg){$this->dataUltMod = $arg;}

	function geraHtmlPost(){
		$html = "
		<div class=\"cor".alterna()."\" id=\"postDiv".$this->id."\">
					<ul class=\"sem_estilo\">
						<li class=\"tabela_port\">
							<span class=\"titulo\">
								<div class=\"textitulo\">".$this->titulo."</div>
							</span>
							<span class=\"data\">
								".$this->dataCriacao."
								<button type=\"button\" class=\"bt_excluir\" onclick=\"ROODA.ui.confirm('Tem certeza que deseja apagar este post?',function () { deletePost(".$this->id."); });\">Excluir</button>
							</span>
						</li>
						<li class=\"tabela_port postagem\">
						<p>
							".$this->texto."
						</p>
						</li>
						<li class=\"tabela_port\">
							<a class=\"bt_abre_coment\" onclick=\"abreComentarios($this->id)\" id=\"abre_coment_$this->id\">Ver comentários</a>
						</li>
					</ul>
				</div>
		";

		return $html;
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
							$dataCriacao = 0,
							$dataEncerramento = 0,
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

	function getTurma(){return $this->turma;}
	function getDataCriacao(){return $this->dataCriacao;}
	function getDataEncerramento(){return $this->dataEncerramento;}
	function getPalavras(){return implode(', ', $this->palavras);}

	function carrega($idProjeto){
		global $tabela_portfolioProjetos;
		$q = new conexao();
		$idProjeto = $q->sanitizaString($idProjeto);
		$q->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE id = $idProjeto");

		if($q->registros > 0){
			$this->id = $idProjeto;
			$this->title = $q->resultado['titulo'];
			$this->palavras = explode(';', $q->resultado['tags']);
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
		global $tabela_portfolioPosts;
		$q = new conexao();
		$q->solicitar("SELECT * FROM $tabela_portfolioPosts WHERE projeto_id = ".$this->id);

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