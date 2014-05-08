<?php
class Planeta{
	private $id;
	private $aparencia;
	private $ehVisitante;
	private $idTerrenoPrincipal;
	private $idTerrenoPatio;
	private $salvo

	function __construct(	$novaAparencia = 0,
							$novoEhVisitante = 0,
							$novoIdTerrenoPrincipal = 0,
							$novoIdTerrenoPatio = 0
						){
		$this->aparencia= $novaAparencia;
		$this->ehVisitante	= $novoEhVisitante;
		$this->idTerrenoPrincipal	= $novoIdTerrenoPrincipal;
		$this->idTerrenoPatio	= $novoIdTerrenoPatio;
		$this->salvo = false;
	}

	function getId()	{return $this->id;}
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
			$aparenciaSanitizada = (int) $this->aparencia;
			$ehVisitanteSanitizado = ($this->ehVisitante ? 1 : 0);
			$idTerrenoPrincipalSanitizado = (int) $this->idTerrenoPrincipal;
			$idTerrenoPatioSanitizado = (int) $this->idTerrenoPatio;
			$idTurmaSanitizado = (int) $this->idTurma;

			$q->solicitar("
				INSERT INTO Planetas
					(Aparencia, EhVisitante, IdTerrenoPrincipal, IdTerrenoPatio);
				VALUES(
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
		$json['aparencia']   = $this->aparencia;
		$json['ehVisitante'] = $this->ehVisitante;
		$json['idTerrenoPrincipal']  = $this->idTerrenoPrincipal;
		$json['idTerrenoPatio']  = $this->idTerrenoPatio;

		return json_encode($json);
	}
}