<?php
/*
VINADÉ!
SINTA-SE A VONTADE PRA ADICIONAR O QUE QUISER AQUI,
MAS TENHA EM MENTE QUE SE QUEBRAR O SISTEMA DE COMENTÁRIOS,
TU QUE VAI TER QUE CONSERTAR!
*/

class Aluno {
	var $id = 0;
	var $nome = 0;

	function Aluno ($id){
		global $tabela_usuarios;

		$dados = new conexao();
		$dados->solicitar("SELECT usuario_nome FROM $tabela_usuarios WHERE usuario_id = $id LIMIT 1" ); // Pega os dados do desenho

		$this->id = $id;
		$this->nome = $dados->resultado['usuario_nome'];
	}
}

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
	private $comentarios = array();

	public function __construct($id=0, $user_id="", $turma=0, $desenho="", $titulo="", $tags=""){ // construtor da classe
		global $tabela_ArteDesenhos;

		if ($id != 0){ // Se tem id, é pra abrir.
			$dados = new conexao();
			$dados->solicitar("SELECT * FROM $tabela_ArteDesenhos WHERE CodDesenho = $id LIMIT 1" );

			if($dados->registros > 0){
				$this->id = $id;
				$this->criador = new Aluno($dados->resultado['CodUsuario']);
				$this->desenho = $dados->resultado['Arquivo'];
				$this->titulo = $dados->resultado['Titulo'];
				$this->palavras = $dados->resultado['Palavras'];
				$this->data = $dados->resultado['Data'];
				$this->status = $dados->resultado['Status'];
				$this->turma = $dados->resultado['CodTurma'];
				$this->valido = true;
			}
		}else{
			$this->criador = new Aluno($user_id);
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

			$this->id = $dados->ultimo_id( $dados->socketMysqli);
		}
		$this->valido = true;
	}

	public function excluir(){
		global $tabela_ArteDesenhos;
		$id = $this->id;
		$dados = new conexao();
		if ($this->id != 0){
			$dados->solicitar("DELETE FROM $tabela_ArteDesenhos WHERE CodDesenho = $id LIMIT 1" );
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

	public function getId(){return $this->id;}
	public function getIdAutor(){return $this->criador;}
	public function getTitulo(){return $this->titulo;}
	public function getPalavras(){return $this->palavras;}
	public function getData(){return $this->data;}
	public function getValido(){return $this->valido;}

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
}

/* Contem todas as artes de uma turma. */
class Arte{
	private $contador = 0; // é preenchido quando uma das funções de pegar desenhos ser chamada.
	private $desenhos = array();
	private $idUser = 0;
	private $idTurma = 0;

	public function __construct($idUser, $idTurma){
		$this->idUser = $idUser;
		$this->idTurma = $idTurma;
	}

	public function getContador(){return $this->contador;} // Retorna o numero de desenhos
	public function getDesenhos(){return $this->desenhos;}

	public function meusDesenhos(){
		$this->fetchDesenhos("SELECT CodDesenho FROM $tabela_ArteDesenhos WHERE CodUsuario = '$user_id' AND CodTurma = '$this->idTurma'" ); // Busca desenhos próprios
	}

	public function desenhosDosColegas(){
		$this->fetchDesenhos("SELECT CodDesenho FROM $tabela_ArteDesenhos WHERE CodUsuario <> '$user_id' AND CodTurma = '$this->idTurma'" );
	}

	// Chame com meusDesenhos ou desenhosDosColegas
	private function fetchDesenhos($query){
		global $tabela_ArteDesenhos;
		unset($this->desenhos);

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

	function meuDesenho($id){
		$desenho = new Desenho($id);
		if ($desenho->getValido()){
			return ($desenho->criador->id == $this->idUser);
		}
	}
}