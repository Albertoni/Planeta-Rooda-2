<?php
class Planeta{
	private $id;
	private $nome;
	private $aparencia;
	private $ehVisitante;
	private $idTerrenoPrincipal;
	private $idTerrenoPatio;
	private $salvo

	function __construct(	$novoNome = "",
							$novaAparencia = 0,
							$novoEhVisitante = 0,
							$novoIdTerrenoPrincipal = 0,
							$novoIdTerrenoPatio = 0
						){
		$this->nome		= $novoNome;
		$this->aparencia= $novaAparencia;
		$this->ehVisitante	= $novoEhVisitante;
		$this->idTerrenoPrincipal	= $novoIdTerrenoPrincipal;
		$this->idTerrenoPatio	= $novoIdTerrenoPatio;
		$this->salvo = false;
	}

	function getId()	{return $this->id;}
	function getNome()	{return $this->nome;}
	function getAparencia()	{return $this->aparencia;}
	function getEhVisitante(){return $this->ehVisitante;}
	function getIdTerrenoPrincipal()	{return $this->idTerrenoPrincipal;}
	function getIdTerrenoPatio()	{return $this->idTerrenoPatio;}

	function abrir($id){
		$q = new conexao();
		$idSanitizado = $q->sanitizaString($id);

		$q->solicitar("SELECT * FROM Planetas WHERE Id = '$id'");

		if($q->registros > 0){
			$this->__construct(
				$q->resultado['Nome'],
				$q->resultado['Aparencia'],
				$q->resultado['EhVisitante'],
				$q->resultado['IdTerrenoPrincpal'],
				$q->resultado['IdTerrenoPatio']
				);
			$this->id = $q->resultado['Id'];
			$this->salvo = true;
		}else{
			$this->__construct("Planeta inexistente");
		}
	}

	function salvar(){
		$q = new conexao();
		if($this->salvo === false){
			$nomeSanitizado = $q->sanitizaString($this->nome);
			$aparenciaSanitizada = (int) $this->aparencia;
			$ehVisitanteSanitizado = ($this->ehVisitante ? 1 : 0);
			$idTerrenoPrincipalSanitizado = (int) $this->idTerrenoPrincipal;
			$idTerrenoPatioSanitizado = (int) $this->idTerrenoPatio;
			$idTurmaSanitizado = (int) $this->idTurma;

			$q->solicitar("
				INSERT INTO Planetas
					(Nome, Aparencia, EhVisitante, IdTerrenoPrincipal, IdTerrenoPatio);
				VALUES(
					'$nomeSanitizado',
					'$aparenciaSanitizada',
					'$ehVisitanteSanitizado',
					'$idTerrenoPrincipalSanitizado',
					'$idTerrenoPatioSanitizado',
					'$idTurmaSanitizado')");

			if($q->erro == ""){
				$this->id = $q->ultimoId();
				$this->salvo = true;
			}
		}else{
			$query = ("
				UPDATE Planetas SET 
					Nome   = '$this->nomeSanitizado',
					Aparencia   = '$this->aparenciaSanitizada',
					EhVisitante = '$this->ehVisitanteSanitizado',
					IdTerrenoPrincipal  = '$this->idTerrenoPrincipalSanitizado',
					IdTerrenoPatio = '$this->idTerrenoPatioSanitizado'
				WHERE Id = '$this->id'");
		}
	}

	function toJson($sendHeaders = false){
		if($sendHeaders){
			header("Content-Type: application/json");
		}
		
		$json = [];
		$json['id']     = $this->id;
		$json['nome']   = $this->nome;
		$json['aparencia']   = $this->aparencia;
		$json['ehVisitante'] = $this->ehVisitante;
		$json['idTerrenoPrincipal']  = $this->idTerrenoPrincipal;
		$json['idTerrenoPatio']  = $this->idTerrenoPatio;

		return json_encode($json);
	}
}