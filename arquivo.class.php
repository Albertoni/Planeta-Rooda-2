<?php //>
require_once("cfg.php");
require_once("bd.php");
class Arquivo {
	private $id;
	private $tipoFuncionalidade;
	private $idFuncionalidade;
	private $idUploader;

	private $titulo = "";
	private $nome;      // nome do arquivo. Deve conter a extensao também 
	private $autor = "";
	private $tipo = ""; // mime-type
	private $tamanho;
	private $conteudo;
	private $tags = array();
	private $data;
	private $erros = array();
	private $upload = false;
	private $download = false;

	public function Arquivo($id = 0) {
		global $tabela_arquivos;
		$id = (int) $id;
		if ($id === 0) {
			$upload = true;
		} else {
			$this->id = $id;
			$bd = new conexao();
			$bd->solicitar(
				"SELECT
				titulo AS 'titulo',
				nome AS 'nome',
				autor AS 'autor',
				tipo AS 'tipo',
				tamanho AS 'tamanho',
				arquivo AS 'conteudo',
				tags AS 'tags',
				dataUpload AS 'data',
				funcionalidade_tipo AS 'tipoFuncionalidade',
				funcionalidade_id AS 'idFuncionalidade',
				uploader_id AS 'idUploader'
				FROM $tabela_arquivos
				WHERE arquivo_id = '$id'"
			);
			if ($bd->erro) {
				// erro na consulta;
				$this->erros[] = "mysql: " . $bd->erro;
			}
			if ($bd->registros === 1) {
				// carrega arquivo encontrado
				$this->popular($bd->resultado);
				$download = true;
			} else {
				$this->erros[] = "Arquivo n&atilde;o encontrado";
			}
		}
	}
	private function popular($resultadoBd) {
		$this->conteudo = $resultadoBd['conteudo']; // conteudo do arquivo
		$this->titulo = $resultadoBd['titulo'];     // titulo do arquivo
		$this->nome = $resultadoBd['nome'];
		$this->autor = $resultadoBd['autor'];
		$this->tipo = $resultadoBd['tipo'];
		$this->tamanho = $resultadoBd['tamanho'];
		$this->setTags($resultadoBd['tags']);
		$this->data = $resultadoBd['data'];
		$this->tipoFuncionalidade = $resultadoBd['tipoFuncionalidade'];
		$this->idFuncionalidade = $resultadoBd['idFuncionalidade'];
		$this->idUploader = $resultadoBd['idUploader'];
	}
	public function getId() {
		return $this->id;
	}
	public function getConteudo() {
		return $this->conteudo;
	}
	public function getTitulo() {
		return $this->titulo;
	}
	public function getNome() {
		return $this->nome;
	}
	public function getAutor() {
		return $this->autor;
	}
	public function getTipo() {
		return $this->tipo;
	}
	public function getTamanho() {
		return $this->tamanho;
	}
	public function getTags() {
		$tags = array();
		foreach ($this->tags as $value) {
			$tags[] = $value;
		}
		return $tags;
	}
	public function getErros() {
		$erros = array();
		foreach ($this->erros as $value) {
			$erros[] = $value;
		}
		return $erros;
	}
	public function temErros() {
		return (0 !== count($this->erros));
	}
	public function getData() {
		return $this->data;
	}
	public function getTipoFuncionalidade() {
		return $this->tipoFuncionalidade;
	}
	public function getIdFuncionalidade() {
		return $this->idFuncionalidade;
	}
	public function getIdUploader() {
		return $this->idUploader;
	}
	// METODOS RELACIONSADOS A UPLOAD
	public function salvar() {
		if ($this->upload && !$this->download) {
			// novo arquivo
			$bd = new conexao();
			
			$campos[]  = 'titulo';
			$valores[] = $bd->sanitizaString($this->titulo);
			$campos[]  = 'nome';
			$valores[] = $bd->sanitizaString($this->nome);
			$campos[]  = 'autor';
			$valores[] = $bd->sanitizaString($this->autor);
			$campos[]  = 'tipo';
			$valores[] = $bd->sanitizaString($this->tipo);
			$campos[]  = 'tamanho';
			$valores[] = $bd->sanitizaString($this->tamanho);
			$campos[]  = 'arquivo';
			$valores[] = $bd->sanitizaString($this->arquivo);
			$campos[]  = 'tags';
			$valores[] = $bd->sanitizaString(implode(",", $this->tags));
			$campos[]  = 'dataUpload';
			$valores[] = $bd->sanitizaString($this->data);
			$campos[]  = 'funcionalidade_tipo';
			$valores[] = $bd->sanitizaString($this->tipoFuncionalidade);
			$campos[]  = 'funcionalidade_id';
			$valores[] = $bd->sanitizaString($this->idFuncionalidade);
			$campos[]  = 'uploader_id';
			$valores[] = $bd->sanitizaString($this->idUploader);

			$bd->solicitar(
				"INSERT INTO $tabela_arquivos (".implode(", ", $campos).")
				VALUES ('".implode("', '", $valores)."')"
			);
		} else if ($this->download && !$this->upload) {
			// arquivo editado
		} else {
			$this->erros[] = "Este arquivo não pode ser enviado";
			return false;
		}
	}
	// public function atualizar() {
	// 	if ($this->download && !$this->upload) {
	// 	}
	// }
	public function setFuncionalidade($tipo, $id) {
		if ($upload === true) {
			$tipo = (int) $tipo;
			$id = (int) $id;
			switch ($tipo) {
				case TIPOBLOG:
				case TIPOPORTFOLIO:
				case TIPOBIBLIOTECA:
				case TIPOAULA:
				case TIPOFORUM:
					$this->tipoFuncionalidade = $tipo;
					$this->idFuncionalidade   = $id;
					break;

				default:
					$this->erros[] = "Esta funcionalidade n&atilde;o suporta arquivos.";
					break;
			}
		}
		return $this;
	}
	public function setIdUploader($id) {
		$id = (int) $id;
		if ($id === 0) {
			$this->erros[] = "Id inválido (nulo)";
		} else {
			$this->idUploader = $id;
		}
		return $this;
	}
	public function setArquivo($FILE) {
		if (!isset($FILE['tmp_name']) || !$FILE['tmp_name']) {
			$this->erros[] = "Parametro inv&aacute;lido (Arquivo::setArquivo($FILE))";
		} if (!filesize($FILE['tmp_name'])) {
			$this->erros[] = "Arquivo vazio.";
		} else {
			$this->tamanho = $FILE['size'];
			$arquivo = fopen($FILE['tmp_name'], 'r');
			$this->conteudo = fread($arquivo, filesize($FILE['tmp_name']));
			$this->setNome($FILE['name']);
			$this->setTipo($FILE['type']);
		}
		return $this;
	}
	public function setConteudo($conteudo) {
		$this->conteudo = $conteudo;
	}
	public function setTitulo($titulo) {
		$titulo = trim($titulo);
		$this->titulo = $titulo;
		return $this;
	}
	public function setNome($nome) {
		$nome = trim($nome);
		$this->nome = $nome;
		return $this;
	}
	public function setAutor($autor) {
		$autor = trim($autor);
		$this->autor = $autor;
		return $this;
	}
	public function setTipo($tipo) {
		$tipo = trim($tipo);
		$this->tipo = $tipo;
		return $this;
	}
	public function setTags($tags) {
		if (is_string($tags)) {
			$tags = explode(",", $tags);
		}
		if (is_array($tags)) {
			$this->tags = array();
			foreach ($tags as $value) {
				$this->tags[] = trim($value);
			}
		}
		return $this;
	}
}
/* /
$arquivo = new Arquivo(222);
$erros = $arquivo->getErros();
if (sizeof($erros) === 0) {
	header("Content-length: {$arquivo->getTamanho()}");
	header("Content-type: {$arquivo->getTipo()}");
	header("Content-Disposition: attachment; filename={$arquivo->getNome()}");
	exit($arquivo->getConteudo());
} else {
	echo '<html><head><meta charset="utf-8"></head><body><ul><li>';
	echo implode("</li><li>", $erros);
	echo '</li></body></html>';
}/* */