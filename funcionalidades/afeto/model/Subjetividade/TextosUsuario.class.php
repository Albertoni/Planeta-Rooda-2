<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../usuarios.class.php");

class TextosUsuario {
//dados
	/**
	*	Arrays com textos de funcionalidades. Os arrays são associativos e seus elementos são arrays com outros dados.
	*	Explicações sobre o conteúdo são dadas de forma sucinta com exemplos.
	*	Todo '0' indica início de contagem. Assim, ao 0 seguem 1, 2, 3...
	*/
		/*
		* ['frases'][0]['data']
		* ['frases'][0]['texto']
		*/
	private $textosChat;
		/*
		* ['comentarios'][0]['data']
		* ['comentarios'][0]['texto']
		* ['posts'][0]['data']
		* ['posts'][0]['texto']
		*/
	private $textosBlog;
		/*
		* ['meus_topicos'][0]['titulo']
		* ['meus_topicos'][0]['descricao']
		* ['meus_topicos'][0]['data']
		* ['mensagens_topicos'][0]['data']
		* ['mensagens_topicos'][0]['texto']
		*/
	private $textosForum;
		/*
		* ['posts'][0]['titulo']
		* ['posts'][0]['texto']
		* ['posts'][0]['tags']
		* ['posts'][0]['data']
		* ['projetos'][0]['data']
		* ['projetos'][0]['titulo']
		* ['projetos'][0]['autor']
		* ['projetos'][0]['descricao']
		* ['projetos'][0]['objetivos']
		* ['projetos'][0]['conteudos']
		* ['projetos'][0]['metodologia']
		* ['projetos'][0]['publico']
		* ['projetos'][0]['tags']
		*/
	private $textosPortfolio;
		/*
		* ['comentarios'][0]['data']
		* ['comentarios'][0]['texto']
		*/
	private $textosBiblioteca;
		/*
		* ['questionarios'][0]['titulo']
		* ['questionarios'][0]['descricao']
		* ['questionarios'][0]['data']
		* ['perguntas'][0]['data']
		* ['perguntas'][0]['questao']
		* ['perguntas'][0]['resposta']
		* ['respostas'][0]['texto']
		* ['respostas'][0]['data']
		*/
	private $textosPergunta;
	private $textosAulas;
		/*
		* ['comentarios'][0]['data']
		* ['comentarios'][0]['texto']
		*/
	private $textosArte;
	
	/*
	* Id do usuário autor dos textos deste objeto.
	*/
	private $idUsuarioAutor;

	/*
	* Objeto usuário, com dados do autor dos textos.
	*/
	private $usuario;

//métodos
	/**
	*	Construtor.
	*	Realiza pesquisas para conter os textos do usuário.
	*	@param pIdUsuario A id (do BD) do usuário cujos textos irão estar no objeto desta classe.
	*/
	function __construct($pIdUsuario){
		$idUsuarioAutor = $pIdUsuario;
		$this->usuario = new Usuario($idUsuarioAutor);
		$this->usuario->openUsuario($idUsuarioAutor);
		$this->consultarTextosChat();
		$this->consultarTextosBlog();
		$this->consultarTextosPortfolio();
		$this->consultarTextosForum();
		$this->consultarTextosBiblioteca();
		$this->consultarTextosPergunta();
		$this->consultarTextosAulas();
		$this->consultarTextosArte();
	}
	
	/**
	* Getters para cópias dos dados de texto do usuário.
	*/
	public function getTextosChat(){
		return $this->textosChat;
	}
	public function getTextosBlog(){
		return $this->textosBlog;
	}
	public function getTextosPortfolio(){
		return $this->textosPortfolio;
	}
	public function getTextosForum(){
		return $this->textosForum;
	}
	public function getTextosBiblioteca(){
		return $this->textosBiblioteca;
	}
	public function getTextosPergunta(){
		return $this->textosPergunta;
	}
	public function getTextosAulas(){
		return $this->textosAulas;
	}
	public function getTextosArte(){
		return $this->textosArte;
	}
	
	/**
	*	Pesquisas que preenchem os arrays de textos deste objeto.
	*	Cada array é preenchido conforme sua descrição em sua declaração, deste mesmo arquivo.
	*/
		/*
		* ['frases'][0]['data']
		* ['frases'][0]['texto']
		*/
	private function consultarTextosChat(){
		$this->textosChat = array();
		$this->textosChat['frases'] = array();

		$idPersonagemUsuario = $this->usuario->getPersonagemId();

		$conexao_chat = new conexao();
		$conexao_chat->solicitar("SELECT * 
								FROM falas_personagens 
								WHERE id_personagem=$idPersonagemUsuario");
	
		for($i=0; $i<$conexao_chat->registros; $i++){
			$this->textosChat['frases'][$i]['texto'] = $conexao_chat->resultado['texto_fala'];
			$this->textosChat['frases'][$i]['data'] = $conexao_chat->resultado['data'];
			$conexao_chat->proximo();
		}
	}
		/*
		* ['comentarios'][0]['data']
		* ['comentarios'][0]['texto']
		* ['posts'][0]['data']
		* ['posts'][0]['texto']
		*/
	private function consultarTextosBlog(){
		$this->textosBlog = array();
		$this->textosBlog['comentarios'] = array();
		$this->textosBlog['posts'] = array();

		$idUsuario = $this->usuario->getId();

		$conexao_blog = new conexao();
		$conexao_blog->solicitar("SELECT *
								FROM blogcomentarios
								WHERE userId = $idUsuario");

		for($i=0; $i<$conexao_blog->registros; $i++){
			$this->textosBlog['comentarios'][$i]['texto'] = $conexao_blog->resultado['Text'];
			$this->textosBlog['comentarios'][$i]['data'] = $conexao_blog->resultado['Date'];
			$conexao_blog->proximo();
		}
		
		$conexao_blog->solicitar("SELECT *
								FROM blogposts
								WHERE userId = $idUsuario");

		for($i=0; $i<$conexao_blog->registros; $i++){
			$this->textosBlog['posts'][$i]['texto'] = $conexao_blog->resultado['Text'];
			$this->textosBlog['posts'][$i]['data'] = $conexao_blog->resultado['Date'];
			$conexao_blog->proximo();
		}
	}
		/*
		* ['meus_topicos'][0]['titulo']
		* ['meus_topicos'][0]['descricao']
		* ['meus_topicos'][0]['data']
		* ['mensagens_topicos'][0]['data']
		* ['mensagens_topicos'][0]['texto']
		*/
	private function consultarTextosForum(){
		$this->textosForum = array();
		$this->textosForum['meus_topicos'] = array();
		$this->textosForum['mensagens_topicos'] = array();

		$idUsuario = $this->usuario->getId();

		$conexao_forum = new conexao();
		$conexao_forum->solicitar("SELECT * 
									FROM ForumTopico
									WHERE codUsuario = $idUsuario");
		for($i=0; $i<$conexao_forum->registros; $i++){
			$this->textosForum['meus_topicos'][$i]['titulo'] = $conexao_forum->resultado['titulo'];
			$this->textosForum['meus_topicos'][$i]['descricao'] = $conexao_forum->resultado['descricao'];
			$this->textosForum['meus_topicos'][$i]['data'] = $conexao_forum->resultado['data'];
			$conexao_forum->proximo();
		}
		
		$conexao_forum->solicitar("SELECT * 
								FROM ForumMensagem
								WHERE codUsuario = $idUsuario");
		for($i=0; $i<$conexao_forum->registros; $i++){
			$this->textosForum['mensagens_topicos'][$i]['data'] = $conexao_forum->resultado['data'];
			$this->textosForum['mensagens_topicos'][$i]['texto'] = $conexao_forum->resultado['mensagem'];
			$conexao_forum->proximo();
		}
	}
		/*
		* ['posts'][0]['titulo']
		* ['posts'][0]['texto']
		* ['posts'][0]['tags']
		* ['posts'][0]['data']
		* ['projetos'][0]['data']
		* ['projetos'][0]['titulo']
		* ['projetos'][0]['autor']
		* ['projetos'][0]['descricao']
		* ['projetos'][0]['objetivos']
		* ['projetos'][0]['conteudos']
		* ['projetos'][0]['metodologia']
		* ['projetos'][0]['publico']
		* ['projetos'][0]['tags']
		*/
	private function consultarTextosPortfolio(){
		$this->textosPortfolio = array();
		$this->textosPortfolio['posts'] = array();
		$this->textosPortfolio['projetos'] = array();
		
		$idUsuario = $this->usuario->getId();
		
		$conexao_portfolio = new conexao();
		$conexao_portfolio->solicitar("SELECT *
										FROM PortfolioPosts
										WHERE user_id = $idUsuario");
		for($i=0; $i<$conexao_portfolio->registros; $i++){
			$this->textosPortfolio['posts'][$i]['titulo'] = $conexao_portfolio->resultado['titulo'];
			$this->textosPortfolio['posts'][$i]['texto'] = $conexao_portfolio->resultado['texto'];
			$this->textosPortfolio['posts'][$i]['tags'] = $conexao_portfolio->resultado['tags'];
			$this->textosPortfolio['posts'][$i]['data'] = $conexao_portfolio->resultado['dataCriacao'];
			$conexao_portfolio->proximo();
		}
		
		$conexao_portfolio = new conexao();
		$conexao_portfolio->solicitar("SELECT *
										FROM PortfolioProjetos
										WHERE user_id = $idUsuario");
		for($i=0; $i<$conexao_portfolio->registros; $i++){
			$this->textosPortfolio['projetos'][$i]['data'] = $conexao_portfolio->resultado['dataCriacao'];
			$this->textosPortfolio['projetos'][$i]['titulo'] = $conexao_portfolio->resultado['titulo'];
			$this->textosPortfolio['projetos'][$i]['autor'] = $conexao_portfolio->resultado['autor'];
			$this->textosPortfolio['projetos'][$i]['descricao'] = $conexao_portfolio->resultado['descricao'];
			$this->textosPortfolio['projetos'][$i]['objetivos'] = $conexao_portfolio->resultado['objetivos'];
			$this->textosPortfolio['projetos'][$i]['conteudos'] = $conexao_portfolio->resultado['conteudosAbordados'];
			$this->textosPortfolio['projetos'][$i]['metodologia'] = $conexao_portfolio->resultado['metodologia'];
			$this->textosPortfolio['projetos'][$i]['publico'] = $conexao_portfolio->resultado['publicoAlvo'];
			$this->textosPortfolio['projetos'][$i]['tags'] = $conexao_portfolio->resultado['tags'];
			$conexao_portfolio->proximo();
		}
	}
		/*
		* ['comentarios'][0]['data']
		* ['comentarios'][0]['texto']
		*/
	private function consultarTextosBiblioteca(){
		$this->textosBiblioteca = array();
		$this->textosBiblioteca['comentarios'] = array();
		
		$idUsuario = $this->usuario->getId();
		
		$consulta_biblioteca = new conexao();
		$consulta_biblioteca->solicitar("SELECT *
										FROM BibliotecaComentarios
										WHERE codUsuario = $idUsuario");
		for($i=0; $i<$consulta_biblioteca->registros; $i++){
			$this->textosBiblioteca['comentarios'][$i]['data'] = $consulta_biblioteca->resultado['data'];
			$this->textosBiblioteca['comentarios'][$i]['texto'] = $consulta_biblioteca->resultado['comentario'];
			$consulta_biblioteca->proximo();
		}
	}
		/*
		* ['questionarios'][0]['titulo']
		* ['questionarios'][0]['descricao']
		* ['questionarios'][0]['data']
		* ['perguntas'][0]['data']
		* ['perguntas'][0]['questao']
		* ['perguntas'][0]['resposta']
		* ['respostas'][0]['texto']
		* ['respostas'][0]['data']
		*/
	private function consultarTextosPergunta(){
		$this->textosPergunta = array();
		$this->textosPergunta['questionarios'] = array();
		$this->textosPergunta['perguntas'] = array();
		$this->textosPergunta['respostas'] = array();
		
		$idUsuario = $this->usuario->getId();
		
		$conexao_pergunta = new conexao();
		$conexao_pergunta->solicitar("SELECT *
									FROM PerguntaQuestionarios
									WHERE criador=$idUsuario");
		for($i=0; $i<$conexao_pergunta->registros; $i++){
			$this->textosPergunta['questionarios'][$i]['data'] = $conexao_pergunta->resultado['datainicio'];
			$this->textosPergunta['questionarios'][$i]['titulo'] = $conexao_pergunta->resultado['titulo'];
			$this->textosPergunta['questionarios'][$i]['descricao'] = $conexao_pergunta->resultado['descricao'];
			$conexao_pergunta->proximo();
		}
		
		$conexao_pergunta->solicitar("SELECT perguntas.*, questionarios.datainicio AS data
									FROM PerguntaPerguntas AS perguntas JOIN PerguntaQuestionarios AS questionarios ON perguntas.id_questionario=questionarios.id
									WHERE criador=$idUsuario");
		for($i=0; $i<$conexao_pergunta->registros; $i++){
			$this->textosPergunta['perguntas'][$i]['data'] = $conexao_pergunta->resultado['data'];
			$this->textosPergunta['perguntas'][$i]['questao'] = $conexao_pergunta->resultado['questao'];
			if($conexao_pergunta->resultado['tipo']==2){
				$this->textosPergunta['perguntas'][$i]['resposta'] = $conexao_pergunta->resultado['respostas'];
			} else {
				$this->textosPergunta['perguntas'][$i]['resposta'] = "";
			}
			$conexao_pergunta->proximo();
		}
		
		$conexao_pergunta->solicitar("SELECT respostas.*, questionarios.datainicio AS data
									FROM PerguntaRespostas AS respostas JOIN PerguntaQuestionarios AS questionarios ON respostas.questionario=questionarios.id
									WHERE usuario=$idUsuario");
		for($i=0; $i<$conexao_pergunta->registros; $i++){
			$this->textosPergunta['respostas'][$i]['data'] = $conexao_pergunta->resultado['data'];
			$this->textosPergunta['respostas'][$i]['texto'] = $conexao_pergunta->resultado['resposta'];
			$conexao_pergunta->proximo();
		}
	}
	private function consultarTextosAulas(){
		$this->textosAulas = array();
	}
		/*
		* ['comentarios'][0]['data']
		* ['comentarios'][0]['texto']
		*/
	private function consultarTextosArte(){
		$this->textosArte = array();
		$this->textosArte['comentarios'] = array();
		
		$idUsuario = $this->usuario->getId();
		
		$conexao_arte = new conexao();
		$conexao_arte->solicitar("SELECT *
								FROM ArtesComentarios
								WHERE CodUsuario = $idUsuario");
		for($i=0; $i<$conexao_arte->registros; $i++){
			$this->textosArte['comentarios'][$i]['data'] = $conexao_arte->resultado['Data'];
			$this->textosArte['comentarios'][$i]['texto'] = $conexao_arte->resultado['Comentario'];
			$conexao_arte->proximo();
		}
	}
}

?>