<?php
class Terreno{
	private $id;
	private $tipo;
	private $idChat;
	private $ehPatio;
	private $salvo;

	function __construct(	$tipo = 0,
							$idChat = 0,
							$ehPatio = false
						){
		$this->tipo		= $tipo;
		$this->idChat	= $idChat;
		$this->ehPatio	= $ehPatio;
		$this->salvo	= false;
	}

	function getId()	{return $this->id;}
	function getTipo()	{return $this->tipo;}
	function getIdChat(){return $this->idChat;}
	function getPatio()	{return $this->ehPatio;}

	function abrir($id){
		$q = new conexao();
		$idSanitizado = $q->sanitizaString($id);

		$q->solicitar("SELECT * FROM terrenos WHERE id = '$id'");

		if($q->registros > 0){
			$this->__construct( // melhor que copicolar código?
				$q->resultado['tipo'],
				$q->resultado['idChat'],
				$q->resultado['patio']
				);
			$this->id = $q->resultado['id'];
			$this->salvo = true;
		}else{
			$this->__construct("Terreno inexistente");
		}
	}

	function salvar(){
		$q = new conexao();
		if($this->salvo === false){
			$tipoSanitizado = (int) $this->tipo;
			$chatSanitizado = (int) $this->idChat;
			$patioSanitizado = ($this->ehPatio ? 1 : 0);

			$q->solicitar("
				INSERT INTO terrenos 
					(tipo, chat_id, patio)
				VALUES(
					'$tipoSanitizado',
					'$chatSanitizado',
					'$patioSanitizado')");

			if($q->erro == ""){
				$this->id = $q->ultimoId();
				$this->salvo = true;
			}

		}else{
			$query = ("
				UPDATE terrenos SET 
					tipo   = '$this->tipoSanitizado',
					idChat = '$this->chatSanitizado',
					patio  = '$this->patioSanitizado'
				WHERE id = '$this->id'");
		}
	}

	function toJson($sendHeaders = false){
		if($sendHeaders){
			header("Content-Type: application/json");
		}
		
		$json = Array();
		$json['id']     = $this->id;
		$json['tipo']   = $this->tipo;
		$json['idChat'] = $this->idChat;
		$json['patio']  = $this->ehPatio;

		return json_encode($json);
	}
}