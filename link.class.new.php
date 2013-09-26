<?php
require_once("cfg.php");
require_once("bd.php");
require_once("usuarios.class.php");
class Link
{
	private $novo = false; // se verdadeiro, é um link novo ainda nao salvo.

	private $id = false; // falso significa que o link nao foi carregado/salvo (nao existe)
	private $titulo = "";
	private $autor = "";
	private $tags = [];
	private $endereco = ""; // URL do link
	private $codUsuario = false;
	private $erros = [];
	
	function __construct($id = false)
	{
		global $tabela_links;
		if ($id === false)
		{
			// novo link
			$this->novo = true;
		}
		else if (is_int($id))
		{
			$bd = new conexao();
			$bd->solicitar(
				"SELECT titulo, autor, tags, endereco, codUsuario
				FROM $tabela_links WHERE Id = $id"
			);
			if ($bd->erro !== "")
			{
				$this->erros[] = $bd->erro;
			}
			else if ($bd->registros !== 1)
			{
				$this->erros[] = "[link] Link não encontrado.";
			}
			else
			{
				$this->id = $id;
				$this->titulo = $bd->resultado['titulo'];
				$this->autor = $bd->resultado['autor'];
				$this->setTags($bd->resultado['tags']);
				$this->endereco = $bd->resultado['endereco'];
				$this->codUsuario = $bd->resultado['codUsuario'];
			}
		}
	}
	public function getId()
	{
		return $this->id;
	}
	public function getTitulo() {
		return $this->titulo;
	}
	public function getEndereco()
	{
		return $this->endereco;
	}
	public function getAutor()
	{
		return $this->autor;
	}
	public function getAssoc()
	{
		$assoc = array();
		$assoc['id'] = $this->getId();
		$assoc['titulo'] = $this->getTitulo();
		$assoc['autor'] = $this->getAutor();
		$assoc['endereco'] = $this->getEndereco();
		return $assoc;
	}
	public function getErros()
	{
		return $this->erros;
	}
	public function temErros()
	{
		return (bool) $this->erros;
	}
	public function salvar()
	{
		global $tabela_links;
		if ($this->novo && $this->id === false)
		{
			if ($this->endereco === '')
				$this->erros[] = '[link] Endereço inválido';
			if ($this->titulo === '') 
				$this->erros[] = '[link] O link precisa de um titulo';
			if ($this->codUsuario === false)
				$this->erros[] = '[link] Usuario não definido.';
			$bd = new conexao();
			$titulo = $bd->sanitizaString($this->titulo);
			$autor = $bd->sanitizaString($this->autor);
			$tags = $bd->sanitizaString(implode(',', $this->tags));
			$endereco = $bd->sanitizaString($this->endereco);
			$codUsuario = (int) $this->codUsuario;
			$bd->solicitar(
				"INSERT INTO $tabela_links (titulo, autor, tags, endereco, codUsuario)
				VALUES ('$titulo', '$autor', '$tags', '$endereco', $codUsuario)"
			);
			if ($bd->erro !== '') {
				$this->erros[] = '[link] BD: ' . $bd->erro;
				return;
			} else {
				$this->id = $bd->ultimo_id();
				$this->novo = false;
			}
		} elseif ($this->id !== false) {
			$bd = new conexao();
			$id = $this->id;
			$titulo = $bd->sanitizaString($this->titulo);
			$autor = $bd->sanitizaString($this->autor);
			$tags = $bd->sanitizaString(implode(',', $this->tags));
			$endereco = $bd->sanitizaString($this->endereco);
			$bd->solicitar(
				"UPDATE $tabela_links SET 
				titulo = '$titulo',
				autor = '$autor',
				tags = '$tags',
				endereco = '$endereco'
				WHERE Id = $id"
			);
			if ($bd->erro !== '') {
				$this->erros[] = '[link] BD: ' . $bd->erro;
			}
		}
	}
	public function excluir()
	{
		global $tabela_links;
		if (!$this->novo && $this->id !== false)
		{
			$bd = new conexao();
			$bd->solicitar(
				"DELETE FROM $tabela_links WHERE Id = {$this->id}"
			);
			if ($bd->erro !== '') {
				$this->erros[] = $bd->erro;
				return false;
			}
			return true;
		}
	}
	public function setTitulo($titulo)
	{
		if (is_string($titulo))
		{
			$this->titulo = trim($titulo);
		}
	}
	public function setAutor($autor)
	{
		if (is_string($autor))
		{
			$this->autor = trim($autor);
		}
	}
	public function setTags($tags)
	{
		if (is_string($tags))
		{
			$tags = explode(',', $tags);
		}
		// Nada de 'else' aqui, pois a entrada pode ser:
		//   1. string com tags speradas por vírgula ou
		//   2. array de tags.
		// se for uma string (1), ela será convertida em array e depois
		// é tratada como uma array a seguir.
		if (is_array($tags))
		{
			$this->tags = [];
			foreach ($tags as $value)
			{
				$this->tags[] = trim($value);
			}
			return true;
		}
		return false;
	}
	public function setEndereco($url)
	{
		$url = trim($url);
		if ($url !== '')
		{
			$url = urlencode($url);
			if (('http://' !== substr($url,0,7)) and ('https://' !== substr($url,0,8)))
			{
				$url = "http://$url";
			}
		}
		$this->endereco = $url;
	}
	public function setUsuario($usuario) {
		if (is_object($usuario) && get_class($usuario) === 'Usuario') {
			$usuario = $usuario->getId();
		}
		if (is_numeric($usuario)) {
			$this->codUsuario = (int) $usuario;
		}
	}
}