<?php
define("MATERIAL_LINK", 'l');
define("MATERIAL_ARQUIVO", 'a');
require_once("../../cfg.php");
require_once("../../bd.php");
require_once("../../usuarios.class.php");
require_once("../../turma.class.php");
require_once("../../arquivo.class.php");
require_once("../../link.class.new.php");
class Material
{
	private $id  = false;
	private $codTurma     = false;
	private $codRecurso   = false;   // codigo do link ou arquivo
	private $titulo       = "";
	private $autor        = "";   // Não confundir com as duas abaixo, que são quem deu upload. Esse é o autor do material.
	private $codUsuario   = false;   // Cod do usuário
	private $usuario      = NULL; // Obj do usuário
	private $tipo         = ""; // MATERIAL_LINK ou MATERIAL_ARQUIVO
	private $arquivo      = NULL; // guarda o objeto arquivo (se for arquivo)
	private $link         = NULL; // quarda o objeto link (se for link)
	private $data         = 0; // Unix timestamp
	private $erros        = NULL;
	private $tags         = array();
	private $aprovado     = false;
	private $novo         = false; // se for true, ainda nao está no banco de dados.
	private $turma_aberta = false;
	private $consulta_turma = null;
	function __construct($id = false)
	{
		global $tabela_Materiais;
		$this->erros = array();
		if ($id !== false && is_integer($id))
		{
			$bd = new conexao();
			$bd->solicitar(
"SELECT
	codMaterial      AS id,
	codTurma         AS codTurma,
	titulo           AS titulo,
	autor            AS autor,
	tags             AS tags,
	codUsuario       AS codUsuario,
	tipoMaterial     AS tipo,
	data             AS data,
	refMaterial      AS codRecurso,
	materialAprovado AS aprovado
FROM BibliotecaMateriais
WHERE codMaterial = $id"
			);
			if ($bd->registros === 1)
			{
				$this->popular($bd->resultado);
			}
			elseif ($bd->erro !== '')
			{
				$this->erros[] = $bd->erro;
			}
		}
		elseif($id === false)
		{
			$this->novo = true;
			$this->data = time();
		}
		else
		{
			$this->erros[] = 'Material não pode ser recuperado (parametros inválidos).';
		}
	}
	private function popular($assoc) {
		$this->setId((int) $assoc['id']);
		$this->setUsuario((int) $assoc['codUsuario']);
		$this->setTurma((int) $assoc['codTurma']);
		$this->titulo = $assoc['titulo'];
		$this->autor = $assoc['autor'];
		$this->setTags($assoc['tags']);
		$this->setTipo($assoc['tipo']);
		$this->setData((int) $assoc['data']);
		$this->setCodRecurso((int) $assoc['codRecurso']);
		$this->setAprovado((bool) $assoc['aprovado']);
		$this->carregaRecurso();
	}
	private function carregaRecurso() {
		if (!$this->temErros()) switch ($this->tipo) {
			case MATERIAL_ARQUIVO:
				$this->arquivo = new Arquivo($this->codRecurso);
				if ($this->arquivo->temErros())
				{
					$this->erros[] = "[material] Não foi possivel recuperar o material.";
				}
				break;

			case MATERIAL_LINK:
				$this->link = new Link($this->codRecurso);
				break;
			
			default:
				$this->erros[] = "[material] Tipo de material nao definido";
				break;
		}
	}
	public function salvar() {
		$usuario = usuario_sessao();
		global $email_administrador;
		global $tabela_Materiais;
		global $linkServidor;

		if ($this->titulo === '') {
			$this->erros[] = '[material] Não pode salvar material sem título.';
		}
		if ($this->autor === '') {
			$this->erros[] = '[material] Não pode salvar material sem autor.';
		}
		if ($this->codUsuario === false) {
			$this->erros[] = '[material] Não pode salvar material sem usuario.';
		}
		switch ($this->tipo) {
			case MATERIAL_ARQUIVO:
				$this->arquivo->salvar();
				$this->codRecurso = $this->arquivo->getId();
				$refMaterial = $this->codRecurso;
				if ($this->arquivo->temErros()) {
					$this->erros = array_merge($this->erros, $this->arquivo->getErros());
				}
				break;
			
			case MATERIAL_LINK:
				$this->link->salvar();
				$this->codRecurso = $this->link->getId();
				$refMaterial = $this->codRecurso;
				if ($this->link->temErros()) {
					$this->erros = array_merge($this->erros, $this->link->getErros());
				}
				break;

			default:
				$this->codRecurso = false;
		}
		if (!$refMaterial) $this->erros[] = '[material] Material nao pode ser definido.';
		if (count($this->erros) > 0) return false;
		if ($this->novo) {
			$bd = new conexao();
			$codTurma     = (int) $this->codTurma;
			$titulo       = $bd->sanitizaString($this->titulo);
			$autor        = $bd->sanitizaString($this->autor);
			$tags         = $bd->sanitizaString(implode(',', $this->tags));
			$codUsuario   = (int) $this->codUsuario;
			$tipoMaterial = $bd->sanitizaString($this->tipo);
			$refMaterial  = $this->codRecurso;
			//Sempre ao salvar indicará o arquivo como não aprovado, para que o professor sempre tenha que aprovar.
			$aprovado     = 0;
			$data = $bd->sanitizaString($this->data);
			$bd->solicitar(
				"INSERT INTO $tabela_Materiais
				(codTurma,titulo,autor,tags,codUsuario,tipoMaterial,data,refMaterial,materialAprovado)
				VALUES ($codTurma,'$titulo','$autor','$tags','$codUsuario','$tipoMaterial','$data','$refMaterial','$aprovado')"
			);
			if ($bd->erro !== '') {
				$this->erros[] = $bd->erro;
			}
			$this->id = (int) $bd->ultimoId();
			$this->novo = false;
		}
		elseif ($this->id) {
			$bd = new conexao();
			$codTurma     = (int) $this->codTurma;
			$titulo       = $bd->sanitizaString($this->titulo);
			$autor        = $bd->sanitizaString($this->autor);
			$tags         = $bd->sanitizaString(implode(',', $this->tags));
			//Sempre ao salvar indicará o arquivo como não aprovado, para que o professor sempre tenha que aprovar.
			$aprovado     = 0;
			$bd->solicitar(
				"UPDATE $tabela_Materiais
				  SET titulo = '$titulo',
						autor = '$autor',
						tags = '$tags',
						materialAprovado = '$aprovado'
				  WHERE codMaterial = {$this->id}"
			);
		}

		//Envia email para os professores, avisando que há material necessitando aprovação.
		//Consulta no BD buscando por todos os professores da turma em que o material foi salvo.
		$assunto = "Um item de uma biblioteca precisa de sua aprovação";
		$bd->solicitar("SELECT nomeTurma FROM Turmas
								WHERE codTurma='$this->codTurma'");
		$nomeDaTurma = $bd->resultado['nomeTurma'];

		$bd->solicitar("SELECT associacao FROM TurmasUsuario
												WHERE codTurma='$this->codTurma'
												AND codUsuario='$this->codUsuario'");
		switch($bd->resultado['associacao']){
			case 4: $nivelDeQuemPostou = "professor";
					break;
			case 8: $nivelDeQuemPostou = "monitor";
					break;
			case 16: $nivelDeQuemPostou = "aluno";
					break;
			default: $nivelDeQuemPostou = "usuário";
		}

		$bd->solicitar("SELECT biblioteca_aprovarMateriais FROM GerenciamentoTurma WHERE codTurma='$this->codTurma'");
		if($bd->resultado['biblioteca_aprovarMateriais'] == NIVELPROFESSOR){
			$bd->solicitar("SELECT usuario_email, usuario_id 
								FROM TurmasUsuario JOIN usuarios ON codUsuario=usuario_id
									WHERE codTurma='$this->codTurma' AND associacao=".NIVELPROFESSOR);
		}
		else if($bd->resultado['biblioteca_aprovarMateriais'] == NIVELPROFESSOR + NIVELMONITOR){
			$bd->solicitar("SELECT usuario_email, usuario_id 
								FROM TurmasUsuario JOIN usuarios ON codUsuario=usuario_id
									WHERE codTurma='$this->codTurma' 
									AND (associacao='".NIVELPROFESSOR."' OR associacao='".NIVELMONITOR."')");
		}

		$item = ($this->tipo == MATERIAL_ARQUIVO) ? 'arquivo' : 'link';

		// Para onde o usuário será redirecionado
		$enderecoBiblioteca = base64_encode("/funcionalidades/biblioteca/biblioteca.php?turma=".$this->codTurma);

		//O email do remetente está descrito no cfg.php
		$remetente = $email_administrador;
		$anexos=Array();

		$chaves = new conexao();
		for($i=0;$i<$bd->registros;$i++)
		{
		// Para não permitir alguem mandar uma URL maliciosa para redirecionar o cara para roubar a senha dele ou coisa pior.
			$key= uniqid(rand(), true);
			$idDestinatario = $bd->resultado['usuario_id'];
			$chaves->solicitar("INSERT INTO ChavesRedirecionamento (uniqueId, userId)
								VALUES ('$key','$idDestinatario')");

			$nomeUsuario = $usuario->getName();

//05/06/2014 Veja no link documentação sobre heredocs em php e descubra porque o trecho abaixo não está identado.
//http://www.php.net/manual/pt_BR/language.types.string.php
            $mensagem = <<<EOT
O $nivelDeQuemPostou $nomeUsuario alterou a biblioteca da turma $nomeDaTurma. Veja mais detalhes abaixo:

    Nome do envio: $titulo
    Tipo: $item

<a href="$linkServidor?redir=$enderecoBiblioteca&key=$key">Clique aqui para entrar em sua conta e aprovar o conteúdo.</a>


EOT;
			multi_attach_email ( $bd->resultado['usuario_email'], $assunto, $mensagem, $remetente, $anexos);
			$bd->proximo();
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
	public function getConteudoMaterial() {
		if (!$this->novo) switch ($this->tipo) {
			case MATERIAL_ARQUIVO:
				return $this->arquivo->getConteudo();
				break;
			
			case MATERIAL_LINK:
				return $this->link->getEndereco();
				break;
		}
		return '';
	}
	public function getErros() { return $this->erros; }
	public function temErros() { return (bool) $this->erros; }
	private function setId($id) { $this->id = (int) $id; }
	private function setCodRecurso($cod) { $this->codRecurso = (int) $cod; }
	public function setTitulo($titulo) {
		$this->titulo = trim($titulo);
		if ($this->arquivo) $this->arquivo->setTitulo($this->titulo);
		return true;
	}
	public function setMaterial($material) {
		if ($this->usuario === NULL || !$this->usuario->getId()) {
			$this->erros[] = '[material] Usuário não definido.';
		}
		if (!$this->codTurma) {
			$this->erros[] = '[material] Turma não definida.';
		}
		// array $_FILE['arquivo']
		if (is_array($material)) {
			$this->tipo = MATERIAL_ARQUIVO;
			$this->arquivo = new Arquivo();
			$this->arquivo->setArquivo($material);
			$this->arquivo->setIdUsuario($this->codUsuario);
			if ($this->titulo !== '') $this->arquivo->setTitulo($this->titulo);
			$this->codRecurso = $this->arquivo->getId();
			if ($this->arquivo->temErros()) {
				$this->erros[] = "[material] Nao foi possivel enviar o arquivo.";
				$this->erros = array_merge($this->erros, $this->arquivo->getErros());
			}
		}
		// objeto do recurso
		elseif (is_object($material)) {
			$this->tipo = MATERIAL_ARQUIVO;
			switch (get_class($material)) {
				case 'Arquivo':
					$this->tipo = MATERIAL_ARQUIVO;
					$this->codRecurso = $this->arquivo->getId();
					break;

				case 'Link':
					$this->tipo = MATERIAL_LINK;
					$this->codRecurso = $this->link->getId();
					break;
				
				default:
					$this->erros[] = '[material] O material não é válido.';
					return;
			}
			if (!$material->getId()) {
				$this->erros[] = '[material] O material não existe.';
			}
		}
		// link
		elseif (is_string($material)) {
			$this->link = new Link();
			$this->link->setEndereco($material);
			$this->link->setTitulo($this->titulo);
			$this->link->setAutor($this->autor);
			$this->link->setUsuario($this->codUsuario);
			if ($this->link->temErros()) {
				$this->erros = array_merge($this->erros, $this->link->getErros());
			}
			else {
				$this->codRecurso = $this->link->getId();
				$this->tipo = MATERIAL_LINK;
			}
		}
		else {
			$this->erros[] = '[material] Material estranho.';
		}
	}
	public function setTurma($turma) {
		if (get_class() === "turma") {
			$turma = $turma->getId();
		}
		if (is_integer($turma)) {
			$this->codTurma = $turma;
			return true;
		}
		return false;
	}
	public function setAutor($autor) {
		$this->autor = trim($autor);
		return true;
	}
	public function setTags($tags) {
		if (is_string($tags)) {
			$tags = explode(",", $tags);
		}
		// Nada de 'else' aqui, pois a entrada pode ser:
		//   1. string com tags speradas por vírgula ou
		//   2. array de tags.
		// se for uma string (1), ela será convertida em array e depois
		// é tratada como uma array a seguir.
		if (is_array($tags)) {
			$this->tags = array();
			foreach ($tags as $value) {
				$this->tags[] = trim($value);
			}
			return true;
		}
		return false;
	}
	public function setUsuario($usuario) {
		if (is_integer($usuario)) {
			$this->usuario = new Usuario();
			$this->usuario->openUsuario($usuario);
			$this->codUsuario = $usuario;
		}
		elseif (get_class($usuario) === "Usuario") {
			$this->usuario = $usuario;
			$this->codUsuario = $usuario->getId();
		}
		if ($this->usuario === NULL || $this->usuario->getId() === 0) {
			$this->usuario = NULL;
			throw new Exception("Usuário inválido.", 1);
		}
	}
	public function setTipo($tipo) {
		if ($tipo === MATERIAL_ARQUIVO || $tipo === MATERIAL_LINK) {
			$this->tipo = $tipo;
		}
		else {
			throw new Exception("Tipo de recurso inválido.", 1);
		}
	}
	private function setData($data) {
		if (is_string($data)) {
			$data = strtotime($data);
		}
		if (is_int($data)) {
			$this->data = $data;
		}
	}
	public function setAprovado($aprovado) {
		$this->aprovado = (bool) $aprovado;
	}
	public function temErro() {
		return (bool) $this->erros; // retorna falso se a array for vazia.
	}
	public function getAssoc() {
		$assoc['id']       = $this->getId();
		$assoc['titulo']   = $this->getTitulo();
		$assoc['autor']    = $this->getAutor();
		$assoc['tags']     = $this->getTags();
		$assoc['usuario']  = is_object($this->usuario) ? $this->usuario->getSimpleAssoc() : null;
		$assoc['data'] = $this->data;
		$assoc['aprovado'] = (bool) $this->aprovado;
		switch ($this->getTipo()) {
			case MATERIAL_ARQUIVO:
				$assoc['arquivo'] = $this->arquivo->getAssoc();
				$assoc['tipo']    = 'arquivo';
				break;
			case MATERIAL_LINK:
				$assoc['link'] = $this->link->getAssoc();
				$assoc['tipo'] = 'link';
				break;
		}
		return $assoc;
	}
	// Retorna array com todos os materiais da turma especificada. retorna false em caso de falha.
	public function abrirTurma($parametros) {
		global $tabela_Materiais;
		$mais_novo = 0;
		$mais_velho = 0;
		$turma = 0;
		$usuario = 0;
		$nao_aprovados = false;
		$condicaoSQL = "";
		if (is_array($parametros)) {
			$mais_novo = isset($parametros['mais_novo']) ? (int) $parametros['mais_novo'] : 0;
			$mais_velho = isset($parametros['mais_velho']) ? (int) $parametros['mais_velho'] : 0;
			$turma = isset($parametros['turma']) ? $parametros['turma'] : 0;
			$nao_aprovados = isset($parametros['nao_aprovados']) ? (bool) $parametros['nao_aprovados'] : false;
			$usuario = isset($parametros['usuario']) ? (int) $parametros['usuario'] : 0;
		} else {
			$turma = $parametros;
		}
		// permite passar o objeto turma como parâmetro.
		if (is_object($turma) && get_class($turma) === 'turma') {
			$turma = $turma->getId();
		}
		$turma = is_numeric($turma) ? (int) $turma : 0;
		if ($turma <= 0) throw new Exception("Turma não definida", 1);
		$this->novo = false;
		$turma = $parametros['turma'];
		if ($mais_novo > 0) {
			$condicaoSQL .= "AND codMaterial > {$mais_novo} ";
		}
		elseif ($mais_velho > 0) {
			$condicaoSQL .= "AND codMaterial < {$mais_velho} ";
		}
		if (!$nao_aprovados) {
			// mostrar somente os materiais aprovados
			$condicaoSQL .= "AND (materialAprovado = 1";
			// a não ser que tenha um usuario definido, entao mostrar os nao aprovados dele também.
			$condicaoSQL .= ($usuario > 0) ? " OR codUsuario = {$usuario})" : ')';
		}
		$this->consulta_turma = new conexao();

$sql = "SELECT
	codMaterial      AS id,
	codTurma         AS codTurma,
	titulo           AS titulo,
	autor            AS autor,
	tags             AS tags,
	codUsuario       AS codUsuario,
	tipoMaterial     AS tipo,
	data             AS data,
	refMaterial      AS codRecurso,
	materialAprovado AS aprovado
FROM BibliotecaMateriais
WHERE codTurma = $turma $condicaoSQL
ORDER BY codMaterial DESC";
		$this->consulta_turma->solicitar($sql);
		// se ocorreu erro, jogar exceção
		if ($this->consulta_turma->erro) {
			throw new Exception($this->consulta_turma->erro." :: $sql", 1);
		}
		$this->turma_aberta = true;
		if ($this->consulta_turma->registros > 0) {
			$this->popular($this->consulta_turma->resultado);
			return true;
		}
		return false;
	}
	public function proximo() {
		if (!$this->turma_aberta) throw new Exception("Material::proximo() só pode ser usado depois de abrir uma turma com Material::abrirTurma()", 1);
		
		$this->consulta_turma->proximo();
		if ((bool) $this->consulta_turma->resultado) {
			$this->popular($this->consulta_turma->resultado);
			return true;
		}
		return false;
	}
	public function registros() {
		if (!$this->turma_aberta) throw new Exception("Material::registros() só pode ser usado depois de abrir uma turma com Material::abrirTurma()", 1);
		return $this->consulta_turma->registros;
	}
	public function aprovar() {
		global $tabela_Materiais;
		if (!$this->id || $this->novo) {
			return;
		}
		$bd = new conexao();
		$bd->solicitar(
			"UPDATE $tabela_Materiais SET materialAprovado = '1' WHERE codMaterial = {$this->id}"
		);
		if ($bd->erro !== '') {
			$this->erros[] = "BD: {$bd->erro}";
		}
	}
	public function excluir() {
		global $tabela_Materiais;
		if (!$this->id || $this->novo) {
			return true;
		}
		$bd = new conexao();
		$bd->solicitar(
			"DELETE FROM $tabela_Materiais WHERE codMaterial = {$this->id}"
		);
		switch ($this->tipo) {
		 	case MATERIAL_ARQUIVO:
		 		// exclui se nao estiver sendo usado ()
		 		$this->arquivo->excluir();
		 		break;
		 	
		 	case MATERIAL_LINK:
		 		# code...
		 		break;
		 	
		 	default:
		 		# code...
		 		break;
		}
	}
}
?>
