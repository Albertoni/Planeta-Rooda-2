<?php
require_once("cfg.php");
require_once("bd.php");
class Link
{
	private $to_register = false; // se verdadeiro, é um link novo ainda nao salvo.

	private $id = false; // falso significa que o link nao foi carregado/salvo (nao existe)
	private $titulo = "";
	private $autor = "";
	private $tags = array();
	private $endereco = ""; // URL do link
	private $uploader_id = false;
	private $funcionalidade_id = 0; // deve ser removido no futuro
	private $funcionalidade_tipo = 0;  // deve ser removido no futuro]
	private $erros = array();
	
	function __construct($id = false)
	{
		global $tabela_links;
		if ($id === false)
		{
			// novo link
			$this->to_register = true;
		}
		else if (is_int($id))
		{
			$bd = new conexao();
			$bd->solicitar(
				"SELECT titulo, autor, tags, endereco, funcionalidade_tipo, funcionalidade_id, uploader_id 
				FROM $tabela_links WHERE Id = $id"
			);
			if ($bd->erro !== "")
			{
				$this->erros[] = $bd->erro;
			}
			else if ($bd->registros !== 1)
			{
				$this->erros[] = "Link não encontrado.";
			}
			else
			{
				$this->id = $id;
			}
		}
	}
	public function getId()
	{
		return $this->id;
	}
	public function getEndereco()
	{
		return $this->endereco;
	}
	public function excluir()
	{
		global $tabela_links;
		if (!$this->to_register && $this->id !== false)
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
		return $this;
	}
	public function setAutor($autor)
	{
		if (is_string($autor))
		{
			$this->autor = trim($autor);
		}
		return $this;
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
			$this->tags = array();
			foreach ($tags as $value)
			{
				$this->tags[] = trim($value);
			}
		}
		return $this;
	}
	public function setEndereco($url)
	{
		if (is_string($url))
		{
			$url = urlencode($url);
			if ('http://' !== substr($url,0,7) and 'https://' !== substr($url,0,8))
			{
				$url = "http://$url";
			}
		}
		return $this;
	}
}