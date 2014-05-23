<?php
if (class_exists('Planeta') != true){ // conserta bugs raros mas incomodativos
class Planeta{
	private $id;
	private $aparencia;
	private $ehVisitante;
	private $idTerrenoPrincipal;
	private $idTerrenoPatio;
	private $salvo;
	private $turma;
	private $nome;

	const APARENCIA_VERDE='6';
	const APARENCIA_GRAMA='1';
	const APARENCIA_LAVA='2';
	const APARENCIA_GELO='3';
	const APARENCIA_URBANO='4';
	const APARENCIA_QUARTO='5';

	function __construct(	$novaAparencia = 0,
							$novoEhVisitante = 0,
							$novoIdTerrenoPrincipal = 0,
							$novoIdTerrenoPatio = 0,
							$novoCodTurma = 0,
							$novoNomeTurma = 0
						){
		$this->aparencia= $novaAparencia;
		$this->ehVisitante	= $novoEhVisitante;
		$this->idTerrenoPrincipal	= $novoIdTerrenoPrincipal;
		$this->idTerrenoPatio	= $novoIdTerrenoPatio;
		$this->idTurma = $novoCodTurma;
		$this->nome = $novoNomeTurma;
		$this->salvo = false;
	}
//Públicos porque são usados pelo script salvaEdicaoTurma.
 function setNome($novoNome){ $this->nome = $novoNome;}
 function setAparencia($novaAparencia){	$this->aparencia = $novaAparencia;}
	
	function getId()	{return $this->id;}
	function getAparencia()	{return $this->aparencia;}
	function getEhVisitante(){return $this->ehVisitante;}
	function getIdTerrenoPrincipal()	{return $this->idTerrenoPrincipal;}
	function getIdTerrenoPatio()	{return $this->idTerrenoPatio;}
	public function __get($propriedade_param){
		return $this->$propriedade_param;
	}

	function abrir($id){
		$q = new conexao();
		$idSanitizado = (int) $id;

		$q->solicitar("SELECT Planetas.*, Turmas.* FROM Planetas JOIN Turmas ON Turmas.idPlaneta = Planetas.Id WHERE Planetas.Id = '$idSanitizado'");

		if($q->registros > 0){
			$this->__construct(
				$q->resultado['Aparencia'],
				$q->resultado['EhVisitante'],
				$q->resultado['IdTerrenoPrincipal'],
				$q->resultado['IdTerrenoPatio'],
				$q->resultado['codTurma'],
				$q->resultado['nomeTurma']
				);
			$this->id = $q->resultado['Id'];
			$this->salvo = true;
		}else{
			$this->__construct("Planeta inexistente");
		}
	}

	function salvar(){
		$q = new conexao();
		
		$aparenciaSanitizada = (int) $this->aparencia;
		$ehVisitanteSanitizado = ($this->ehVisitante ? 1 : 0);
		$idTerrenoPrincipalSanitizado = (int) $this->idTerrenoPrincipal;
		$idTerrenoPatioSanitizado = (int) $this->idTerrenoPatio;
		$idTurmaSanitizado = (int) $this->idTurma;
		
		if($this->salvo === false){
			$q->solicitar("
				INSERT INTO Planetas
					(Aparencia, EhVisitante, IdTerrenoPrincipal, IdTerrenoPatio, Turma) 
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
			$q->solicitar("
				UPDATE Planetas SET 
					Aparencia   = '$aparenciaSanitizada',
					EhVisitante = '$ehVisitanteSanitizado',
					IdTerrenoPrincipal  = '$idTerrenoPrincipalSanitizado',
					IdTerrenoPatio = '$idTerrenoPatioSanitizado'
				WHERE Id = '$this->id'");
		}
	}

	function toJson($sendHeaders = false){
		if($sendHeaders){
			header("Content-Type: application/json");
		}
		
		$json = array();
		$json['id']     = $this->id;
		$json['aparencia']   = $this->aparencia;
		$json['ehVisitante'] = $this->ehVisitante;
		$json['idTerrenoPrincipal']  = $this->idTerrenoPrincipal;
		$json['idTerrenoPatio']  = $this->idTerrenoPatio;

		return json_encode($json);
	}
}
}