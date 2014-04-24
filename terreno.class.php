<?php
class Terreno{
	private $id;
	private $nome;
	private $tipo;
	private $idChat;
	private $ehPatio;

	function __construct(	$nome = "",
							$tipo = 0,
							$idChat = 0,
							$ehPatio = false
						){
		$this->nome		= $nome;
		$this->tipo		= $tipo;
		$this->idChat	= $idChat;
		$this->ehPatio	= $ehPatio;
	}

	function getId()	{return $this->id;}
	function getNome()	{return $this->nome;}
	function getTipo()	{return $this->tipo;}
	function getIdChat(){return $this->idChat;}
	function getPatio()	{return $this->ehPatio;}

	function abrir($id){
		$q = new conexao();
		$idSanitizado = $q->sanitizaString($id);

		$q->solicitar("SELECT * FROM terrenos WHERE id = '$id'");

		if($q->registros > 0){
			$this->__construct( // melhor que copicolar código?
				$q->resultado['nome'],
				$q->resultado['tipo'],
				$q->resultado['idChat'],
				$q->resultado['patio'],
				);
		}
	}

	function salvar(){
		
	}
}