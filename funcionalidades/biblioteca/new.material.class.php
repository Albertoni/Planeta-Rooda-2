<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../usuarios.class.php");
define("MATERIAL_LINK", 1);
define("MATERIAL_ARQUIVO", 2);
class Material
{
	private $codMaterial  = -1;
	private $codTurma        = -1;
	private $codRecurso   = -1;    // codigo do link ou arquivo
	private $titulo       = "";
	private $autor        = "";   // Não confundir com as duas abaixo, que são quem deu upload. Esse é o autor do material.
	private $codUsuario   = -1;    // Cod do usuário
	private $usuario      = NULL; // Obj do usuário
	private $tipo         = 0;
	private $arquivo      = NULL; // guarda o objeto arquivo (se for arquivo)
	private $link         = NULL; // quarda o objeto link (se for link)
	private $data         = "";
	private $hora         = "";
	private $erros        = NULL;
	private $tags         = "";
	private $aprovado     = false;
	private $novo         = false; // se for true, ainda nao está no banco de dados.
	
	function __construct($id = false)
	{
		global $tabela_Materiais;
		$this->erros = array();
		if ($id !== false && is_integer($id))
		{
			$bd = new conexao();
			$bd->solicitar(
				"SELECT
					codTurma AS turma,
					titulo AS titulo,
					autor AS autor,
					palavras AS tags,
					codUsuario AS codUsuario,
					tipoMaterial AS tipo,
					material AS material, -- deprecado
					data AS data,
					hora AS hora,
					refMaterial AS codRecurso,
					materialAprovado AS aprovado
				FROM $tabela_Materiais WHERE codMaterial = $id"
			);
			if ($bd->registros === 1)
			{
				$this->codTurma   = setTurma($bd->resultado['codTurma']);
				$this->titulo     = setTitulo($bd->resultado['titulo']);
				$this->autor      = setAutor($bd->resultado['autor']);
				$this->tags       = setTags($bd->resultado['tags']);
				$this->codUsuario = setUsuario($bd->resultado['codUsuario']);
				$this->tipo       = setTipo($bd->resultado['tipo']);
				$this->data       = setData($bd->resultado['data']);
				$this->hora       = setHora($bd->resultado['hora']);
				$this->codRecurso = setCodRecurso($bd->resultado['codRecurso']);
				$this->aprovado   = setAprovado($bd->resultado['aprovado']);
			}
			else
			{
				if ($bd->erro !== '')
				{
					$this->erros[] = $bd->erro;
				}
			}
		}
		else if($id === false)
		{
			$this->novo = true;
		}
	}
	private function carregaRecurso($ref)
	{
		switch ($this->tipo) {
			case MATERIAL_ARQUIVO:
				# code...
				break;
			case MATERIAL_LINK:
				# code...
				break;
			
			default:
				$this->erros[] = "Tipo de material nao definido";
				break;
		}
	}
	public function setCodTurma($codTurma)
	{
		$this->codTurma = trim($codTurma);
	}
	public function setTitulo($titulo)
	{
		$this->titulo = trim($titulo);
	}
	public function setAutor($autor)
	{
		$this->autor = trim($autor);
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
	public function setUsuario($usuario)
	{
		if (is_integer($usuario))
		{
			$this->codUsuario = $usuario;
			$this->usuario = new Usuario();
			$this->usuario->openUsuario($usuario);
		}
		else if (get_class($usuario) === "Usuario")
		{
			$this->usuario = $usuario;
			$this->codUsuario = $usuario->getId();
		}
	}
	public function setTipo($tipo)
	{
		if ($tipo === MATERIAL_ARQUIVO || $tipo === MATERIAL_LINK)
		{
			$this->tipo = $tipo;
		}
		$this->erros[] = 'Tipo inválido de recurso.';
	}
	public function setData($data)
	{
		if (is_string($data))
		{
			$this->data = trim($data);
		}
	}
	public function setHora($hora)
	{
		$this->hora = trim($hora);
	}
	public function setAprovado($aprovado)
	{
		$this->aprovado = $aprovado ? true : false;
	}
}