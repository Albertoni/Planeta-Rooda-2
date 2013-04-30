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
	var $id = 0;
	var $desenho = "";
	var $criador;
	var $titulo = "";
	var $palavras = "";
	var $data = "";
	var $turma = 0;
	var $status = 0;
	var $valido = false;
	var $comentarios = array();

	function Desenho($id=0, $user_id="", $turma=0, $desenho="", $titulo="", $tags=""){ // construtor da classe
		global $tabela_ArteComentarios;
		global $tabela_ArteDesenhos;

		unset($this->comentarios);
		$this->comentarios = array();
//echo "id: $id\n";
		if ($id != 0){ // Se tem id, é pra abrir.
			// Esse é o código de carregar os comentários.
			// Dá uma olhada na conexao $dados ali, creio que ela seja útil.
			$dados = new conexao();
			$queryComentarios = new conexao();
			$dados->solicitar("SELECT * FROM $tabela_ArteDesenhos WHERE CodDesenho = $id LIMIT 1" ); // Pega os dados do desenho
//			echo "SELECT * FROM $tabela_ArteDesenhos WHERE CodDesenho = $id LIMIT 1\n";
			if ($dados->registros > 0){
				$queryComentarios->solicitar("SELECT * FROM $tabela_ArteComentarios WHERE CodDesenho = $id");// E os comentarios dele
//				echo "SELECT * FROM $tabela_ArteComentarios WHERE CodDesenho = $id\n";
				$this->id = $id;
				$this->criador = new Aluno($dados->resultado['CodUsuario']);
				$this->desenho = $dados->resultado['Arquivo'];
				$this->titulo = $dados->resultado['Titulo'];
				$this->palavras = $dados->resultado['Palavras'];
				$this->data = $dados->resultado['Data'];
				$this->status = $dados->resultado['Status'];
				$this->turma = $dados->resultado['CodTurma'];
				$this->valido = true;

				for ($i = 0; $i < $queryComentarios->registros; $i++){
					$this->comentarios[] = new Comment($queryComentarios->resultado['CodComentario'], $queryComentarios->resultado['CodDesenho'], $queryComentarios->resultado['CodUsuario'], $queryComentarios->resultado['Comentario'], $queryComentarios->resultado['Data']);
					$queryComentarios->proximo();
				}
			}
			/*
			unset($this->comentarios); // limpa a lista de comentários
			foreach($queryComentarios->itens as $c){ // Gera um objeto comentario pra cada comentario
				$this->comentarios[] = new Comment($c['CodComentario'], $c['CodDesenho'], $c['CodUsuario'], $c['Comentario'], $c['Data']);
			}
			*/
		} else {
			$this->criador = new Aluno($user_id);
			$this->desenho = $desenho;
			$this->titulo = $titulo;
			$this->palavras = $tags;
			$this->turma = $turma;
		}
	}

	function salvar(){
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

			$this->id = mysqli_insert_id( $dados->socketMysqli);
		}
		$this->valido = true;
	}

	function excluir(){
		global $tabela_ArteDesenhos;
		$id = $this->id;
		$dados = new conexao();
		if ($this->id != 0){
			$dados->solicitar("DELETE FROM $tabela_ArteDesenhos WHERE CodDesenho = $id LIMIT 1" );
		}
	}

	function getId(){ // pega o id do desenho
		return $this->id;
	}

	function novoComentario(){ // recebe um novo comentário e já salva no banco de dados
	}

	function getComentarios(){ // pega array de comentarios
		return $this->comentarios;
	}

	function getAutor(){ // pega o nome do autor
		$temp = new Usuario();
		if ($temp->openUsuario($this->criador) === ""){ // falhou, bródis, se vira
			return false;
		}else{
			return $temp;
		}
	}

	function getIdAutor(){ // pega o id do autor
		return $this->criador;
	}

	public function getTitulo(){
		return $this->titulo;
	}

	public function getPalavras(){
		return $this->palavras;
	}

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

class Arte{
	var $contador = 0;
	var $desenhos = array();
	var $idUser = 0;
	var $idArte = 0;

	function Arte($idUser, $idArte){
		$this->idUser = $idUser;
		$this->idArte = $idArte;
	}

	function meusDesenhos(){
		global $tabela_ArteDesenhos;
		unset($this->desenhos);

		$user_id = $this->idUser;
		$arte_id = $this->idArte;

		$dados = new conexao();
		$dados->solicitar("SELECT CodDesenho FROM $tabela_ArteDesenhos WHERE CodUsuario = '$user_id' AND CodTurma = '$arte_id'" ); // Busca desenhos próprios

		for ($c=0; $c<$dados->registros; $c++){
			$id = $dados->resultado['CodDesenho'];
			$this->desenhos[] = new Desenho($id);
			$dados->proximo();
		}

		if ($dados->registros > 0)
			$this->contador = count($this->desenhos);
	}

	function desenhosDosColegas(){
		global $tabela_ArteDesenhos;
		unset($this->desenhos);

		$user_id = $this->idUser;
		$arte_id = $this->idArte;

		$dados = new conexao();
		$dados->solicitar("SELECT CodDesenho FROM $tabela_ArteDesenhos WHERE CodUsuario <> '$user_id' AND CodTurma = '$arte_id'" ); // Busca desenhos próprios

		for ($c=0; $c<$dados->registros; $c++){
			$id = $dados->resultado['CodDesenho'];
			$this->desenhos[] = new Desenho($id);
			$dados->proximo();
		}
		//$this->desenhos = $dados->resultado;
		if ($dados->registros > 0)
			$this->contador = count($this->desenhos);
	}

	function meuDesenho($id){
		$desenho = new Desenho($id);
		if ($desenho->valido){
			return ($desenho->criador->id == $this->idUser);
		}
	}

}
?>
