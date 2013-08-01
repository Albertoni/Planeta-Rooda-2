<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../usuarios.class.php");
require_once("../../turma.class.php");
define("MATERIAL_LINK", 'l');
define("MATERIAL_ARQUIVO", 'a');
class Material
{
	private $codMaterial  = -1;
	private $codTurma     = -1;
	private $codRecurso   = -1;   // codigo do link ou arquivo
	private $titulo       = "";
	private $autor        = "";   // Não confundir com as duas abaixo, que são quem deu upload. Esse é o autor do material.
	private $codUsuario   = -1;   // Cod do usuário
	private $usuario      = NULL; // Obj do usuário
	private $tipo         = "";
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
					codTurma         AS turma,
					titulo           AS titulo,
					autor            AS autor,
					palavras         AS tags,
					codUsuario       AS codUsuario,
					tipoMaterial     AS tipo,
					data             AS data,
					hora             AS hora,
					refMaterial      AS codRecurso,
					materialAprovado AS aprovado
				FROM $tabela_Materiais
				WHERE codMaterial = $id"
			);
			if ($bd->registros === 1)
			{
				$this->codMaterial = $id;
				$this->setTurma((int) $bd->resultado['codTurma']);
				$this->setTitulo($bd->resultado['titulo']);
				$this->setAutor($bd->resultado['autor']);
				$this->setTags($bd->resultado['tags']);
				$this->setUsuario((int) $bd->resultado['codUsuario']);
				$this->setTipo($bd->resultado['tipo']);
				$this->setData($bd->resultado['data']);
				$this->setHora($bd->resultado['hora']);
				$this->setCodRecurso((int) $bd->resultado['codRecurso']);
				$this->setAprovado((bool) $bd->resultado['aprovado']);
				$this->carregaRecurso();
			}
			elseif ($bd->erro !== '')
			{
				$this->erros[] = $bd->erro;
			}
		}
		elseif($id === false)
		{
			$this->novo = true;
		}
		else
		{
			$this->erros[] = 'Material não pode ser recuperado (parametros inválidos)';
		}
	}
	private function carregaRecurso()
	{
		switch ($this->tipo) {
			case MATERIAL_ARQUIVO:
				$this->arquivo = new Arquivo($this->codRecurso);
				if ($this->arquivo->getId() === 0)
				break;
			case MATERIAL_LINK:
				$this->link = new Link($this->codRecurso);
				break;
			
			default:
				$this->erros[] = "Tipo de material nao definido";
				break;
		}
	}
	public function salvar() {
		if ($this->novo)
		{}
		else
		{}
	}
	public function getId() { return $this->codMaterial; }
	public function getTitulo() { return $this->titulo; }
	public function getUsuario() { return $this->usuario; }
	public function getTags() { return $this->tags; }
	public function getTipo() { return $this->tipo; }
	public function getArquivo() { return $this->arquivo; }
	public function getLink() { return $this->link; }
	public function getData() { return $this->data; }
	public function getHora() { return $this->hora; }
	public function getConteudoMaterial() {
		if ($this->tipo === MATERIAL_ARQUIVO && !$this->novo)
		{
			return $this->arquivo->getConteudo();
		}
		if ($this->tipo === MATERIAL_LINK && !$this->novo)
		{
			return $this->link->getEndereco();
		}
	}
	public function setTurma($turma)
	{
		if (get_class() === "turma")
		{
			$turma = $turma->getId();
		}
		if (is_integer($turma))
		{
			$this->codTurma = $turma;
			return true;
		}
		return false;
	}
	public function setTitulo($titulo)
	{
		$this->titulo = trim($titulo);
		return true;
	}
	public function setAutor($autor)
	{
		$this->autor = trim($autor);
		return true;
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
			return true;
		}
		return false;
	}
	public function setUsuario($usuario)
	{
		if (is_integer($usuario))
		{
			$this->codUsuario = $usuario;
			$this->usuario = new Usuario();
			$this->usuario->openUsuario($usuario);
			return true;
		}
		elseif (get_class($usuario) === "Usuario")
		{
			$this->usuario = $usuario;
			$this->codUsuario = $usuario->getId();
			return true;
		}
		else
		{
			$this->erros[] = 'Usuario inválido';
			return false;
		}
	}
	public function setTipo($tipo)
	{
		if ($tipo === MATERIAL_ARQUIVO || $tipo === MATERIAL_LINK)
		{
			$this->tipo = $tipo;
		}
		else
		{
			$this->erros[] = 'Tipo de recurso inválido.';
		}
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
		if (is_string($hora))
		{
			$this->hora = trim($hora);
		}
	}
	public function setAprovado($aprovado)
	{
		$this->aprovado = (bool) $aprovado;
	}
	public function temErro()
	{
		return (bool) $this->erros; // retorna falso se a array for vazia.
	}
	// Retorna array com todos os materiais da turma especificada. retorna false em caso de falha.
	public static function getMateriaisTurma($turma)
	{
		global $tabela_Materiais;
		// permite passar o objeto turma como parâmetro.
		if (get_class($turma) === "turma")
		{
			// pega o id do objeto turma
			$turma = $turma->getId();
		}
		// neste ponto, a turma precisa ser o id da turma.
		if (!is_integer($turma)) return false;
		$bd = new conexao();
		$bd->solicitar(
			"SELECT codMaterial AS id
			FROM $tabela_Materiais
			WHERE codTurma = $turma
			ORDER BY codMaterial ASC"
		);
		// se ocorreu erro, retornar false.
		if ($bd->erro !== '') return false;
		// caso contrário, fazer array com resultados.
		$materiais = array();
		while ($bd->resultado) {
			$id = (int) $bd->resultado['id'];
			$materiais[] = new Material($id);
			$bd->proximo();
		}
		return $materiais;
	}
}