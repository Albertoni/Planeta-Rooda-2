<?php
/*
VINADÉ!
SINTA-SE A VONTADE PRA ADICIONAR O QUE QUISER AQUI,
MAS TENHA EM MENTE QUE SE QUEBRAR O SISTEMA DE COMENTÁRIOS,
TU QUE VAI TER QUE CONSERTAR!
*/

class Desenho {
	private $id = 0;
	private $desenho = "";
	private $criador;
	private $titulo = "";
	private $palavras = "";
	private $data = "";
	private $turma = 0;
	private $status = 0;
	private $valido = false;

	public function __construct($id=0, $user_id="", $turma=0, $desenho="", $titulo="", $tags=""){ // construtor da classe
		global $tabela_ArteDesenhos;

		if ($id != 0){ // Se tem id, é pra abrir.
			$dados = new conexao();
			$dados->solicitar("SELECT * FROM ArtesDesenhos WHERE CodDesenho = '$id'" );

			//print_r($dados);

			if($dados->registros > 0){
				$this->id = $id;
				$this->criador = new Usuario($dados->resultado['CodUsuario']);
				$this->desenho = $dados->resultado['Arquivo'];
				$this->titulo = $dados->resultado['Titulo'];
				$this->palavras = $dados->resultado['Palavras'];
				$this->data = $dados->resultado['Data'];
				$this->status = $dados->resultado['Status'];
				$this->turma = $dados->resultado['CodTurma'];
				$this->valido = true;
			}
		}else{
			$this->criador = new Usuario($user_id);
			$this->desenho = $desenho;
			$this->titulo = $titulo;
			$this->palavras = $tags;
			$this->turma = $turma;
		}
	}

	public function salvar(){
		global $tabela_ArteDesenhos;

		$id = $this->id;
		$user_id = $this->criador->id;
		$arquivo = $this->desenho;
		$titulo = $this->titulo;
		$tags = $this->palavras;
		$turma = $this->turma;

		$dados = new conexao();
		if ($this->id != 0){ // Se tem id, é para salvar num já existente.
			$dados->solicitar("UPDATE $tabela_ArteDesenhos SET Arquivo='$arquivo', Titulo='$titulo', Palavras='$tags', Data = NOW()  WHERE CodDesenho = '$id' LIMIT 1" ); // Atualiza os dados do desenho no banco de dados
		}else{ //se não tem id, salva num novo registro
			$dados->solicitar("INSERT $tabela_ArteDesenhos (CodUsuario, CodTurma, Arquivo, Titulo, Palavras, Data) VALUES ($user_id, $turma, '$arquivo', '$titulo', '$tags', NOW())" ); // Cria novo desenho no banco de dados

			$this->id = $dados->ultimo_id();
		}
		$this->valido = true;
	}

	public function excluir(){
		global $tabela_ArteDesenhos;
		$id = $this->id;
		$dados = new conexao();
		if ($this->id != 0){
			$dados->solicitar("DELETE FROM $tabela_ArteDesenhos WHERE CodDesenho = $id LIMIT 1");
		}

		if($dados->erro != ""){
			return "Ocorreu um erro ao excluir o desenho.";
		}else{
			return "Desenho excluido com sucesso.";
		}
	}

	public function getAutor(){ // pega o nome do autor
		$temp = new Usuario();
		if ($temp->openUsuario($this->criador) === ""){ // falhou, bródis, se vira
			return false;
		}else{
			return $temp;
		}
	}

	public function getId()			{return $this->id;}
	public function getIdAutor()	{return $this->criador;}
	public function getTitulo()		{return $this->titulo;}
	public function getPalavras()	{return $this->palavras;}
	public function getData()		{return $this->data;}
	public function getValido()		{return $this->valido;}
	public function getCriador()	{return $this->criador;}
	public function getDesenho()	{return $this->desenho;}


	public function setId($dado)		{$this->id = $dado;}
	public function setIdAutor($dado)	{$this->criador = $dado;}
	public function setTitulo($dado)	{$this->titulo = $dado;}
	public function setPalavras($dado)	{$this->palavras = $dado;}
	public function setData($dado)		{$this->data = $dado;}
	public function setValido($dado)	{$this->valido = $dado;}
	public function setCriador($dado)	{$this->criador = $dado;}
	public function setDesenho($dado)	{$this->desenho = $dado;}

//	para ignorar width ou height, basta colocar 0 no seu valor
//	exemplo:
//	desenho->visualizar(0,100,"border: 1px solid black");
//	apenas o height da imagem será considerado, forçando o width a manter a proporção da imagem
	function visualizar($width, $height, $style=""){
		$atributos = "";

		if ($width != 0){
			$atributos .= " width = '$width'";
		}
		if ($height != 0){
			$atributos .= " height = '$height'";
		}
		$atributos .= " style = '$style'";
		$src = $this->desenho;
		$html = "<img src='$src' $atributos />";
		return $html;
	}

	function pertenceAoId($userId){
		if ($this->getValido()){
			return ($this->getCriador()->getId() === $userId);
		}
	}
}

/* Contem todas as artes de uma turma. */
class Arte{
	private $contador = 0; // é preenchido quando uma das funções de pegar desenhos ser chamada.
	private $desenhos = 0;
	private $idUser = 0;
	private $idTurma = 0;

	public function __construct($idUser, $idTurma){
		$this->idUser = $idUser;
		$this->idTurma = $idTurma;
	}

	public function getContador(){return $this->contador;} // Retorna o numero de desenhos
	public function getDesenhos(){return $this->desenhos;}

	public function meusDesenhos(){
		$this->fetchDesenhos("SELECT CodDesenho FROM ArtesDesenhos WHERE CodUsuario = '$this->idUser' AND CodTurma = '$this->idTurma'" ); // Busca desenhos próprios
	}

	public function desenhosDosColegas(){
		$this->fetchDesenhos("SELECT CodDesenho FROM ArtesDesenhos WHERE CodUsuario <> '$this->idUser' AND CodTurma = '$this->idTurma'" );
	}

	// Chame com meusDesenhos ou desenhosDosColegas
	// Isso está dessa forma porque o vinadé não sabe o que boas práticas significam, pode ser melhor refatorado creio - João - 25/3/14
	private function fetchDesenhos($query){
		$this->desenhos = array();

		$dados = new conexao();
		$dados->solicitar($query);

		for ($i=0; $i<$dados->registros; $i++){
			$id = $dados->resultado['CodDesenho'];
			$this->desenhos[] = new Desenho($id);
			$dados->proximo();
		}
		
		if ($dados->registros > 0){
			$this->contador = count($this->desenhos);
		}
	}
}