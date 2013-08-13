<?php
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../usuarios.class.php");
require_once("../../turma.class.php");
require_once("../../arquivo.class.php");
require_once("../../link.class.new.php");
define("MATERIAL_LINK", 'l');
define("MATERIAL_ARQUIVO", 'a');
class Material
{
	private $id  = false;
	private $codTurma     = false;
	private $codRecurso   = false;   // codigo do link ou arquivo
	private $titulo       = "";
	private $autor        = "";   // Não confundir com as duas abaixo, que são quem deu upload. Esse é o autor do material.
	private $codUsuario   = false;   // Cod do usuário
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
	private $turma_aberta = false;
	private $consulta_turma = null;
	private $sql = <<<SQL
SELECT
	codMaterial      AS id,
	codTurma         AS codTurma,
	titulo           AS titulo,
	autor            AS autor,
	tags             AS tags,
	codUsuario       AS codUsuario,
	tipoMaterial     AS tipo,
	data             AS data,
	hora             AS hora,
	refMaterial      AS codRecurso,
	materialAprovado AS aprovado
	FROM BibliotecaMateriais
SQL;
	function __construct($id = false)
	{
		global $tabela_Materiais;
		$this->erros = array();
		if ($id !== false && is_integer($id))
		{
			$bd = new conexao();
			$bd->solicitar(
				$this->sql.<<<SQL
				WHERE codMaterial = $id
SQL
			);
			if ($bd->registros === 1)
			{
				$this->popular($bd->resultado);
			}
			elseif ($bd->erro !== '')
			{
				echo $bd->erro;
				$this->erros[] = $bd->erro;
			}
		}
		elseif($id === false)
		{
			$this->novo = true;
		}
		else
		{
			$this->erros[] = 'Material não pode ser recuperado (parametros inválidos).';
		}
	}
	private function popular($assoc) {
		$this->setId($assoc['id']);
		$this->setTurma((int) $assoc['codTurma']);
		$this->setTitulo($assoc['titulo']);
		$this->setAutor($assoc['autor']);
		$this->setTags($assoc['tags']);
		$this->setUsuario((int) $assoc['codUsuario']);
		$this->setTipo($assoc['tipo']);
		$this->setData($assoc['data']);
		$this->setHora($assoc['hora']);
		$this->setCodRecurso((int) $assoc['codRecurso']);
		$this->setAprovado((bool) $assoc['aprovado']);
		$this->carregaRecurso();
	}
	private function carregaRecurso()
	{
		if (!$this->temErros()) switch ($this->tipo) {
			case MATERIAL_ARQUIVO:
				$this->arquivo = new Arquivo($this->codRecurso);
				if ($this->arquivo->temErros())
				{
					$this->erros[] = "Não foi possivel recuperar o material.";
				}
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
		if ($this->titulo === '')
		{
			$this->erros[] = 'Não pode salvar material sem título.';
		}
		if ($this->autor === '')
		{
			$this->erros[] = 'Não pode salvar material sem autor.';
		}
		if ($this->codUsuario === false)
		{
			$this->erros[] = 'Não pode salvar material sem usuario.';
		}
		if ($this->codRecurso !== false || ($this->arquivo === NULL && $this->link === NULL))
		{
			$this->erros[] = 'Não pode salvar material sem conteúdo.';
		}
		if (count($this->erros) > 0)
			return false;
		if ($this->novo)
		{
			$bd = new conexao();
			$bd->solicitar(
				"INSERT INTO $tabela_Materiais
				(codTurma,titulo,autor,tags,codUsuario,tipoMaterial,data,hora,refMaterial,materialAprovado)"
			);
			$this->novo = false;
		}
		elseif ($this->id)
		{
			$bd = new conexao();
			$codTurma     = (int) $this->codTurma;
			$titulo       = $bd->sanitizaString($this->titulo);
			$autor        = $bd->sanitizaString($this->autor);
			$tags         = $bd->sanitizaString(implode(',', $this->tags));
			$codUsuario   = (int) $this->codUsuario;
			$tipoMaterial = $bd->sanitizaString($this->tipoMaterial);
			$aprovado     = $this->aprovado ? '1' : '0';
			$bd->solicitar(<<<SQL
				UPDATE $tabela_Materiais 
				SET codTurma = '$codTurma', 
					titulo = '$titulo', 
					autor = '$autor', 
					tags = '$tags', 
					codUsuario = '$codUsuario', 
					tipoMaterial = '$tipoMaterial', 
					data = '$data', 
					hora = '$hora', 
					refMaterial = '$refMaterial', 
					materialAprovado = $aprovado
SQL
			);
		}
	}
	public function existe() { return ($this->id !== false && !$this->novo); }
	public function getId() { return $this->id; }
	public function getTitulo() { return $this->titulo; }
	public function getAutor() { return $this->autor; }
	public function getUsuario() { return $this->usuario; }
	public function getIdTurma() { return $this->codTurma; }
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
	public function getErros() { return $this->erros; }
	public function temErros()
	{
		return (bool) $this->erros;
	}
	private function setId($id) { $this->id = (int) $id; }
	private function setCodRecurso($cod) { $this->codRecurso = (int) $cod; }
	public function setTitulo($titulo)
	{
		$this->titulo = trim($titulo);
		return true;
	}
	public function setMaterial($material)
	{
		if ($this->usuario === NULL || !$this->usuario->getId())
		{
			$this->erros[] = 'Usuário não definido.';
		}
		if (!$this->codTurma) {
			$this->erros[] = 'Turma não definida.';
		}
		// array $_FILE['arquivo']
		if (is_array($material))
		{
			$obj = new Arquivo();
			$arquivo->setArquivo($material);
		}
		// objeto do recurso
		elseif (is_object($material))
		{
			if (get_class($material) === 'Arquivo' || get_class($material) === 'Link')
			{
				if (!$material->getId())
				{
					$this->erros[] = 'O material não existe.';
				}
			}
		}
		// link
		elseif (is_string($material))
		{
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
			$this->usuario = new Usuario();
			$this->usuario->openUsuario($usuario);
			if ($this->usuario->getId() === 0)
			{
				$this->usuario = NULL;
				$this->erros[] = 'Usuario inválido';
				throw new Exception("Usuário inválido como ", 1);
				return false;
			}
			$this->codUsuario = $usuario;
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
	public function getAssoc()
	{
		$assoc = array(
			'id' => $this->getId(),
			'titulo' => $this->getTitulo(),
			'tipo' => $this->getTipo(),
			'tags' => $this->getTags(),
			'aprovado' => (bool) $this->aprovado
		);
	}
	// Retorna array com todos os materiais da turma especificada. retorna false em caso de falha.
	public function abreTurma($parametros)
	{
		global $tabela_Materiais;
		// permite passar o objeto turma como parâmetro.
		if (is_object($parametros['turma']) && get_class($parametros['turma']) === "turma")
		{
			// pega o id do objeto turma
			$parametros['turma'] = $parametros['turma']->getId();
		}
		// neste ponto, a turma precisa ser o id da turma.
		if (!is_integer($parametros['turma'])) {
			throw new Exception('turma inválida', 1);
			return false;
		}
		$this->consulta_turma = new conexao();
		$this->consulta_turma->solicitar(
			$this->sql."
			WHERE codTurma = {$parametros['turma']}
			ORDER BY codMaterial DESC"
		);
		// se ocorreu erro, retornar false.
		if ($this->consulta_turma->erro) 
		{
			throw new Exception($this->consulta_turma->erro, 1);
		}

		$this->popular($this->consulta_turma->resultado);
		return true;
	}
	public function proximo()
	{
		$this->consulta_turma->proximo();
		if ((bool) $this->consulta_turma->resultado)
		{
			$this->popular($this->consulta_turma->resultado);
			return true;
		}
		return false;
	}
}