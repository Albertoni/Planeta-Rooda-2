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

	private $existe;

	function __construct($id, $dados = false){
		if($dados !== false){
			$this->id			= $dados['id'];
			$this->projeto_id	= $dados['projeto_id'];
			$this->user_id		= $dados['user_id'];
			$this->titulo		= $dados['titulo'];
			$this->texto		= $dados['texto'];
			$this->tags			= $dados['tags'];

			$this->existe = false;
		}else{
			$this->carrega($id);
		}
	}

	function carrega($id){
		global $tabela_portfolioPosts;
		$q = new conexao();
		$q->solicitar("SELECT * FROM $tabela_portfolioPosts WHERE id = $id");

		if ($q->erro != ""){
			$this->id			= $q->erro;
			$this->projeto_id	= $q->erro;
			$this->user_id		= $q->erro;
			$this->titulo		= $q->erro;
			$this->texto		= $q->erro;
			$this->tags			= $q->erro;
			$this->dataCriacao	= $q->erro;
			$this->dataUltMod	= $q->erro;

			$this->existe = false;
		}else{
			$dados = $q->resultado;
			$this->id			= $dados['id'];
			$this->projeto_id	= $dados['projeto_id'];
			$this->user_id		= $dados['user_id'];
			$this->titulo		= $dados['titulo'];
			$this->texto		= $dados['texto'];
			$this->tags			= $dados['tags'];
			
			$this->existe = true;
		}
	}

	function salvar(){
		global $tabela_portfolioPosts;
		$q = new conexao();

		$this->projeto_id = $q->sanitizaString($this->projeto_id);
		$this->user_id = $q->sanitizaString($this->user_id);
		$this->titulo = $q->sanitizaString($this->titulo);
		$this->texto = $q->sanitizaString($this->texto);
		$this->tags = $q->sanitizaString($this->tags);

		if($this->existe){
			$query = "UPDATE $tabela_portfolioPosts SET 
				projeto_id = '$this->projeto_id',
				user_id = '$this->user_id',
				titulo = '$this->titulo',
				texto = '$this->texto',
				tags = '$this->tags',
				dataCriacao = '$this->dataCriacao',
				dataUltMod = NOW()
			WHERE id = '$this->id'";
		}else{
			$query = "INSERT INTO $tabela_portfolioPosts 
				(projeto_id, user_id, titulo, texto, tags, dataCriacao, dataUltMod)
			VALUES(
				'$this->projeto_id',
				'$this->user_id',
				'$this->titulo',
				'$this->texto',
				'$this->tags',
				NOW(),
				NOW())";
		}
		
		$q->solicitar($query);
		if($q->erro == ""){
			return $q->erro;
		}else{
			$this->existe = true;
			return $query;
		}
	}
	function getId(){return $this->id;}
	function getIdProjeto(){return $this->projeto_id;}
	function getIdUsuario(){return $this->user_id;}
	function getTitulo(){return $this->titulo;}
	function getTexto(){return $this->texto;}
	function getTags(){return $this->tags;}
	function getDataCriacaoBruta(){return $this->dataCriacao;}
	function getDataCriacaoFormatada(){return date('d/m/Y H:m:s', strtotime($this->dataCriacao));}
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
								".$this->getDataCriacaoFormatada()."
								<button type=\"button\" class=\"bt_excluir\" onclick=\"ROODA.ui.confirm('Tem certeza que deseja apagar este post?',function () { deletePost(".$this->id."); });\">Excluir</button>
							</span>
						</li>
						<li class=\"tabela_port postagem\">
						<p>
							".$this->texto."
						</p>
						</li>
						<li class=\"tabela_port\">
							<input type=\"hidden\" name=\"comentarios\" value=\"{$this->id}\" />
						</li>
					</ul>
				</div>
		";

		return $html;
	}
}


class projeto{
	private $id = 0;
	private $titulo = "";
	private $dataCriacao;
	private $dataEncerramento;
	private $ownersIds = array();
	private $emAndamento;

	private $posts = array();
	private $tags = array();

	private $existe = 0;
	private $turma = 0;

	function __construct(	$id = 0,
							$titulo = "",
							$palavras = "",
							$dataCriacao = 0,
							$dataEncerramento = 0,
							$ownersIds = array(),
							$turma = 0
						){
		if($id === 0){
			$this->id = 0;
			$this->titulo = $titulo;
			$this->palavras = explode(';', $palavras);
			$this->dataCriacao = $dataCriacao;
			$this->dataEncerramento = $dataEncerramento;
			$this->ownersIds = is_array($ownersIds) ? $ownersIds : explode(';', $ownersIds);
			$this->turma = $turma;
		}else{
			$this->carrega($id);
		}
	}

	function getTurma(){return $this->turma;}
	function getTitulo(){return $this->titulo;}
	function getDataCriacaoBruta(){return $this->dataCriacao;}
	function getDataEncerramentoBruta(){return $this->dataEncerramento;}
	function getDataCriacaoFormatada(){return date('d/m/Y H:m:s', strtotime($this->dataCriacao));}
	function getDataEncerramentoFormatada(){return date('d/m/Y H:m:s', strtotime($this->dataEncerramento));}
	function getPalavras(){return $this->palavras;}
	function getPalavrasString(){return implode(', ', $this->palavras);}
	function getPosts(){return $this->posts;}

	function carrega($idProjeto){
		global $tabela_portfolioProjetos;
		$q = new conexao();
		$idProjeto = $q->sanitizaString($idProjeto);
		$q->solicitar("SELECT * FROM $tabela_portfolioProjetos WHERE id = $idProjeto");

		if($q->registros > 0){
			$this->id = $idProjeto;
			$this->titulo = $q->resultado['titulo'];
			$this->palavras = explode(';', $q->resultado['tags']);
			$this->dataCriacao = $q->resultado['dataCriacao'];
			$this->dataEncerramento = $q->resultado['dataEncerramento'];
			$this->ownersIds = explode(";", $q->resultado['owner_ids']);
			$this->turma = $q->resultado['turma'];
			$this->existe = 1;
			$this->emAndamento = $q->resultado['emAndamento'];

			$this->carregaPosts();
		}else{
			die("Esse projeto não existe. $idProjeto");
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
			$newPost = new post(0, $q->resultado);

			array_push($this->posts, $newPost);
			$q->proximo();
		}
	}

	function salvar(){
		global $tabela_portfolioPosts; global $tabela_portfolioProjetos;

		$q = new conexao();

		$this->id = $q->sanitizaString($this->id);
		$this->titulo = $q->sanitizaString($this->titulo);
		$palavrasImplodido = $q->sanitizaString(implode(';', $this->palavras));
		$this->dataCriacao = $q->sanitizaString($this->dataCriacao);
		$this->dataEncerramento = $q->sanitizaString($this->dataEncerramento);
		$ownersIdsImplodido = $q->sanitizaString(implode(';', $this->ownersIds));
		$this->turma = $q->sanitizaString($this->turma);

		if($this->existe){
			$query = "UPDATE $tabela_portfolioProjetos SET 
				titulo = $this->titulo,
				tags = $palavrasImplodido,
				owner_id = $ownersIdsImplodido,
				dataCriacao = $this->dataCriacao,
				dataEncerramento = $this->dataEncerramento,
				turma = $this->turma
			WHERE
				id = $this->id";
		}else{
			$query = "INSERT INTO $tabela_portfolioProjetos 
				(titulo, tags, emAndamento, dataCriacao, dataEncerramento, owner_ids, turma)
			VALUES(
				'$this->titulo',
				'$palavrasImplodido',
				'1',
				'$this->dataCriacao',
				'$this->dataEncerramento',
				'$ownersIdsImplodido',
				'$this->turma')";
		}

		$q->solicitar($query);
		if($q->erro == ""){
			print_r($this);

			$numeroPosts = count($this->posts);

			for($i=0; $i < $numeroPosts; $i++){
				$this->posts[$i]->salvar();
			}
		}else{
			return $q->erro;
			//die("Erro ao salvar o projeto, por favor tente novamente em um momento.");
		}
	}

	function geraHTMLProjeto($user, $turma, $perm, $CSScor, $CSSencerrado){

		$projeto_id = $this->id;

if($user->podeAcessar($perm['portfolio_editarPost'], $turma)){
	$editarProjeto = "								<a class=\"$CSSencerrado\" href=\"portfolio_novo_projeto.php?projeto_id=$projeto_id
	&amp;turma=$turma\">[Editar projeto]</a>\n";
}else{
	$editarProjeto = "";
}

global $nivelProfessor;
if(($this->emAndamento == true) && ($user->getNivel($turma)>=$nivelProfessor)){
	$encerrarProjeto = "								<a class=\"$CSSencerrado\" onclick=\"fechaProjeto($projeto_id, $projeto_id);\">[Encerrar projeto]</a>\n";
}else{
	$encerrarProjeto = "";
}

echo "					<div class=\"$CSScor\" id=\"proj_id$projeto_id\">
						<ul class=\"sem_estilo\">
							<li class=\"texto_port\">
								<span class=\"valor\">
									<a class=\"port_titulo\" href=\"portfolio_projeto.php?projeto_id=$projeto_id&amp;turma=$turma\">
										$this->titulo
									</a>
								</span>
							</li>
							<li>
								<span class=\"dados\">Data de criação:</span>
								<span class=\"valor\">".$this->getDataCriacaoFormatada()."</span>
							</li>
							<li>
								<span class=\"dados\">Data de Encerramento:</span>
								<span class=\"valor\">".$this->getDataEncerramentoFormatada()."</span>
							</li>
							<li>
								<span class=\"dados\">Palavras:</span>
								<span class=\"valor\">".$this->getPalavrasString()."</span>
							</li>
$editarProjeto
$encerrarProjeto
							</ul>
						</div>";
	}
}