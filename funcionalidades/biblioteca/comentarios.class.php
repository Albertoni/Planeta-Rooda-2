<?php
require_once("../../cfg.php");
require_once("../../bd.php");
class Comentario {
	// TABELA ONDE FICAM GUARDADOS OS COMENTÁRIOS -------------
	public static $tabelaBD = "";
	// --------------------------------------------------------
	protected $id = false;
	protected $idRef = false;
	protected $idUsuario = false;
	protected $mensagem = '';
	protected $data;
	private $salvo = false; // flag: se alguma mudança foi feita e não foi salva.
	private $consultaRef = false;
	public function __construct($id = false) {
		if (!(Comentario::$tabelaBD)) {
			throw new Exception('"Comentario::$tabelaBD" não está definida');
		}
		if ($id) {
			$this->abrir($id);
		}
	}
	public function abrir($id) {
		if (!is_integer($id))
			throw new Exception("Error Processing Request");
		$bd = new conexao();
		$bd->solicitar(
			"SELECT * FROM " . Comentario::$tabelaBD . " WHERE id = $id"
		);
		if ($bd->erro)
			throw new Exception("Erro na consulta: " . $bd->erro, 1);
		if ($bd->registros !== 1)
			return;
		$this->carregaAssoc($bd->resultado);
	}
	public function abrirComentarios($idRef) {
		$bd = new conexao();
		$bd->solicitar(
			"SELECT * FROM " . Comentario::$tabelaBD . " WHERE idRef = $idRef"
		);
		if ($bd->erro)
			throw new Exception("Erro na consulta: " . $bd->erro, 1);
		$this->consultaRef = $bd;
		$this->carregaAssoc($bd->resultado);
	}
	public function proximo() {
		if ($this->consultaRef === false)
			throw new Exception("Nenhuma referencia aberta");
		$this->consultaRef->proximo();
		$this->carregaAssoc($this->consultaRef->resultado);
	}
	public function setUsuario($idUsuario) {
		if ($this->id !== false)
			throw new Exception("Não pode mudar o usuário de uma mensagem existente.");
		if (is_object($idUsuario))
			if (get_class($idUsuario) === 'Usuario')
				$idUsuario = $idUsuario->getId();
		if (!is_integer($idUsuario)) 
			throw new Exception("Usuário inválido");
		$this->idUsuario = $idUsuario;
	}
	public function setIdRef($idRef) {
		if ($this->id !== false)
			throw new Exception("Não pode mudar idRef de mensagem existente.");
		if (!is_integer($idRef))
			throw new Exception("Error Processing Request", 1);
		$this->idRef = $idRef;
	}
	public function setMensagem($mensagem) {
		$this->mensagem = (string) $mensagem;
		$this->salvo = false;
	}
	public function salvar() {
		if ($this->salvo)
			return;
		if ($this->id === false) {
			$this->data = time();
			$bd = new conexao();
			$mensagemSql = $bd->sanitizaString($this->mensagem);
			$bd->solicitar(
				"INSERT INTO " . Comentario::$tabelaBD . " (idRef, idUsuario, mensagem, data)
				VALUES ({$this->idRef}, {$this->idUsuario}, '{$mensagemSql}', {$this->data})"
			);
			if ($bd->erro)
				throw new Exception("Erro na consulta: " . $bd->erro, 1);
		} else {
			$bd = new conexao();
			$mensagemSql = $bd->sanitizaString($this->mensagem);
			$bd->solicitar(
				"UPDATE " . Comentario::$tabelaBD . "
				SET mensagem = '{$mensagemSql}' WHERE id = {$this->id}"
			);
		}
	}
	private function carregaAssoc($assoc) {
		$this->id        = $assoc['id']        ? (int) $assoc['id']        : false;
		$this->idRef     = $assoc['idRef']     ? (int) $assoc['idRef']     : false;
		$this->idUsuario = $assoc['idUsuario'] ? (int) $assoc['idUsuario'] : false;
		$this->mensagem  = $assoc['mensagem'];
		$this->data      = (int) $assoc['data'];
		$this->salvo     = true;
	}
}
Comentario::$tabelaBD = "BibliotecaComentarios";
print_r(Comentario::$tabelaBD);