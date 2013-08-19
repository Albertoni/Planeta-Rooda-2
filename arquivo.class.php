<?php //>
require_once("cfg.php");
require_once("bd.php");
require_once("usuarios.class.php");
class Arquivo
{
	private $id = false; // só mudar se o arquivo for carregado/salvado com sucesso.
	private $idUploader;

	private $titulo = "";
	private $nome;      // nome do arquivo. Deve conter a extensao também
	private $tipo = ""; // mime-type
	private $tamanho;
	private $conteudo;
	private $tags = array(); // deve ser removido futuramente (só é usado na biblioteca, que tem um campo proprio na tabela de materiais)
	private $data;
	private $erros = array();
	private $upload = false;
	private $download = false;

	public function __construct($id = false)
	{
		global $tabela_arquivos;
		$id = (int) $id;
		if ($id === false)
		{
			$this->data = date('Y-m-d');
			$upload = true;
		}
		else
		{
			$bd = new conexao();
			$bd->solicitar(
				"SELECT
				titulo AS 'titulo',
				nome AS 'nome',
				tipo AS 'tipo',
				tamanho AS 'tamanho',
				arquivo AS 'conteudo',
				tags AS 'tags',
				dataUpload AS 'data',
				uploader_id AS 'idUploader'
				FROM $tabela_arquivos
				WHERE arquivo_id = '$id'"
			);
			if ($bd->erro)
			{
				// erro na consulta;
				$this->erros[] = "mysql: " . $bd->erro;
			}
			if ($bd->registros === 1)
			{
				// carrega arquivo encontrado
				$this->id = $id;
				$this->popular($bd->resultado);
				$download = true;
			}
			else
			{
				$this->erros[] = "Arquivo n&atilde;o encontrado";
			}
		}
	}
	private function popular($resultadoBd)
	{
		$this->conteudo = $resultadoBd['conteudo']; // conteudo do arquivo
		$this->titulo   = $resultadoBd['titulo'];     // titulo do arquivo
		$this->nome     = $resultadoBd['nome'];
		$this->tipo     = $resultadoBd['tipo'];
		$this->tamanho  = $resultadoBd['tamanho'];
		$this->data     = $resultadoBd['data'];
		$this->idUploader = $resultadoBd['idUploader'];
		$this->setTags($resultadoBd['tags']);
	}
	public function getId() { return $this->id; }
	public function getConteudo() { return $this->conteudo; }
	public function getTitulo() { return $this->titulo; }
	public function getNome() { return $this->nome; }
	public function getTipo() { return $this->tipo; }
	public function getTamanho() { return $this->tamanho; }
	public function getData() { return $this->data; }
	public function getIdUploader() { return $this->idUploader; }
	public function getTags()
	{
		$tags = array();
		foreach ($this->tags as $value)
		{
			$tags[] = $value;
		}
		return $tags;
	}
	public function getErros()
	{
		$erros = array();
		foreach ($this->erros as $value)
		{
			$erros[] = $value;
		}
		return $erros;
	}
	public function temErros()
	{
		return (0 !== count($this->erros));
	}
	public function getAssoc() {
		$assoc = array();
		$assoc['id'] = $this->getId();
		$assoc['titulo'] = $this->getTitulo();
		$assoc['nome'] = $this->getNome();
		$assoc['tipo'] = $this->getTipo();
		$assoc['tamanho'] = $this->getTamanho();
		$assoc['tags'] = $this->getTags();
		return $assoc;
	}
	// METODOS RELACIONSADOS A UPLOAD
	public function salvar()
	{
		global $tabela_arquivos;
		// NOVO ARQUIVO
		if ($this->upload && !$this->download)
		{
			// novo arquivo
			$bd = new conexao();
			// sanitizando dados para o banco de dados
			$campos[]  = 'titulo';
			$valores[] = $bd->sanitizaString($this->titulo);
			$campos[]  = 'nome';
			$valores[] = $bd->sanitizaString($this->nome);
			$campos[]  = 'tipo';
			$valores[] = $bd->sanitizaString($this->tipo);
			$campos[]  = 'tamanho';
			$valores[] = $bd->sanitizaString($this->tamanho);
			$campos[]  = 'arquivo';
			$valores[] = $bd->sanitizaString($this->conteudo);
			$campos[]  = 'tags';
			$valores[] = $bd->sanitizaString(implode(",", $this->tags)); // campo deve ser removido futuramente
			$campos[]  = 'dataUpload';
			$valores[] = $bd->sanitizaString($this->data);
			$campos[]  = 'uploader_id';
			$valores[] = (int) $this->idUploader;
			// executando consulta
			$bd->solicitar(
				"INSERT INTO $tabela_arquivos (" . implode(", ", $campos) . ")
				VALUES ('" . implode("', '", $valores) . "')"
			);
			if ($bd->erro !== "")
			{
				$this->erros[] = "BD: {$bd->erro}";
			}
			else
			{
				$this->id = $bd->ultimo_id();
				$this->upload = false;
				$this->download = true;
				return true;
			}
		}
		// MUDANDO ARQUIVO ANTIGO
		else if ($this->download && !$this->upload)
		{
			$bd = new conexao();
			// sanitizando dados para o banco de dados
			$campos[]  = 'titulo';
			$valores[] = $bd->sanitizaString($this->titulo);
			$campos[]  = 'nome';
			$valores[] = $bd->sanitizaString($this->nome);
			$campos[]  = 'tipo';
			$valores[] = $bd->sanitizaString($this->tipo);
			$campos[]  = 'tamanho';
			$valores[] = $bd->sanitizaString($this->tamanho);
			$campos[]  = 'arquivo';
			$valores[] = $bd->sanitizaString($this->conteudo);
			$campos[]  = 'tags';
			$valores[] = $bd->sanitizaString(implode(",", $this->tags)); // campo deve ser removido futuramente
			$campos[]  = 'dataUpload';
			$valores[] = $bd->sanitizaString($this->data);
			$campos[]  = 'uploader_id';
			$valores[] = (int) $this->idUploader;
			$sqlset = array();
			// construindo a sintaxe do sql
			foreach ($campos as $num => $campo)
			{
				$sqlset[] = "{$campo} = '{$valores[$num]}'";
			}
			// executando consulta
			$bd->solicitar(
				"UPDATE $tabela_arquivos
				SET " . implode(", ", $sqlset) . "
				WHERE arquivo_id = {$this->id}"
			);
			if ($bd->erro !== '')
			{
				$this->erros[] = $bd->erro;
				return false;
			}
			return true;
		}
		else
		{
			$this->erros[] = "Este arquivo não pode ser enviado";
			return false;
		}
	}
	// public function atualizar() {
	// 	if ($this->download && !$this->upload) {
	// 	}
	// }
	public function setIdUploader($id) {
		$id = (int) $id;
		if ($id === 0)
		{
			$this->erros[] = "Id inválido (nulo)";
		}
		else
		{
			$this->idUploader = $id;
		}
		return $this;
	}
	public function setArquivo($FILE) {
		if (!isset($FILE['tmp_name']) || !$FILE['tmp_name'])
		{
			$this->erros[] = "Parametro inv&aacute;lido (Arquivo::setArquivo($FILE))";
		}
		if(!filesize($FILE['tmp_name']))
		{
			$this->erros[] = "Arquivo vazio ou inválido.";
		}
		else
		{
			$this->tamanho = $FILE['size'];
			$arquivo = fopen($FILE['tmp_name'], 'r');
			$this->conteudo = fread($arquivo, filesize($FILE['tmp_name']));
			$this->setNome($FILE['name']);
			$this->setTipo($FILE['type']);
			if (!$this->getTitulo()) $this->setTitulo($FILE['name']);
		}
		return $this;
	}
	public function setConteudo($conteudo)
	{
		$this->conteudo = $conteudo;
	}
	public function setTitulo($titulo)
	{
		$this->titulo = trim($titulo);
		return $this;
	}
	public function setNome($nome)
	{
		$this->nome = trim($nome);
		return $this;
	}
	public function setTipo($tipo)
	{
		$this->tipo = trim($tipo);
		return $this;
	}
	public function setTags($tags)
	{
		if (is_string($tags))
		{
			$tags = explode(",", $tags);
		}
		// Nada de 'else' aqui, pois a entrada pode ser:
		//   1. string com tags speradas por vírgula ou
		//   2. array de tags.
		// se for uma string (1), ela será convertida em array e depois
		// é tratada como uma array a seguir.
		if (is_array($tags))
		{
			$this->tags = array();
			foreach ($tags as $value)
			{
				$this->tags[] = trim($value);
			}
		}
		return $this;
	}
	public function excluir()
	{
		global $tabela_arquivos;
		if (!$this->upload && $this->download)
		{
			$bd = new conexao();
			$bd->solicitar(
				"DELETE FROM $tabela_arquivos WHERE arquivo_id = {$this->id}"
			);
			if ($bd->erro !== '')
			{
				$this->erros[] = "DB:" . $bd->erro;
				return false;
			}
		}
		return true;
	}
	public static function getArquivosUsuario($usuario)
	{
		if (get_class($usuario) === "Usuario")
		{
			$usuario = $usuario->getId();
		}
		global $tabela_arquivos;
		$arquios = array();
		$bd = new conexao();
		$bd->solicitar(
			"SELECT arquivo_id AS id 
			FROM $tabela_arquivos
			WHERE uploader_id = $usuario"
		);
		return $arquivos;
	}
}
/* /
$arquivo = new Arquivo(222);
$erros = $arquivo->getErros();
if (count($erros) === 0) {
	header("Content-length: {$arquivo->getTamanho()}");
	header("Content-type: {$arquivo->getTipo()}");
	header("Content-Disposition: attachment; filename={$arquivo->getNome()}");
	exit($arquivo->getConteudo());
} else {
	echo '<html><head><meta charset="utf-8"></head><body><ul><li>';
	echo implode("</li><li>", $erros);
	echo '</li></body></html>';
}/* */