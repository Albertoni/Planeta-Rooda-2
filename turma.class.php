<?php

class Turma{
	
	private $id;
	private $nomeTurma;
	private $profResponsavel;
	private $descricao;
	private $serie;
	private $escola;
	private $idChat;
	private $idPlaneta;
	private $NivelPermissao = array(); //Array que conterá o nível de permissao dos usuarios de uma turma, indexado por CodUsuario.
	
	function getId(){	return id;}
	function getNomeTurma(){	return nomeTurma;}
	function getProfResponsavel(){	return profResponsavel;}
	function getDescricao(){	return descricao;}
	function getSerie(){	return serie;}
	function getEscola(){	return escola;}
	function getIdChat(){	return idChat;}
	function getIdPlaneta(){	return idPlaneta;}
	
	function __construct(	$novoNomeTurma = "",
							$novoProfResponsavel = 0,
							$novaDescricao = "",
							$novaSerie = 0,
							$novaEscola = 0,
							$novoIdChat = 0,
							$novoIdPlaneta = 0 ){
		
		$this->nomeTurma = $novoNomeTurma;
		$this->profResponsavel = $novoProfResponsavel;
		$this->descricao = $novaDescricao;
		$this->serie = $novaSerie;
		$this->escola = $novaEscola;
		$this->idChat = $novoIdChat;
		$this->idPlaneta = $novoIdPlaneta;
		//O array nivel de permissao sera inicializado ao abrir uma turma.
	}
	
	function abrir($id){
		$q = new conexao();
		$idSanitizado = $q->sanitizaString($id);

		$q->solicitar("SELECT * FROM Turmas WHERE codTurma = '$id'");

		if($q->registros > 0){
			$this->__construct(
				$q->resultado['nomeTurma'],
				$q->resultado['profResponsavel'],
				$q->resultado['descricao'],
				$q->resultado['serie'],
				$q->resultado['Escola'],
				$q->resultado['chat_ID'],
				$q->resultado['idPlaneta'],
				);
			$this->id = $q->resultado['codTurma'];
			$this->salvo = true;
			//consultado no DB pelos usuarios da turma e..
			$q->solicitar("SELECT * FROM TurmasUsuario WHERE codTurma = '$id'");
			//.. atribui no array indexado pelo codUsuario o nivel de associacao de cada um.
			for($i=0; $i < $q->registros; $i++){
				$NivelPermissao[$q->resultado['codUsuario']] = $q->resultado['associacao'];
				$q->proximo();
			}
		}else{
			$this->__construct("Terreno inexistente");
		}
		
		
	}
	
	function salvar(){
		$q = new conexao();
		if($this->salvo === false){
			$nomeTurmaSanitizado = $q->sanitizaString($this->nomeTurma);
			$profResponsavelSanitizado = (int) $this->profResponsavel;
			$descricaoSanitizada = $q->sanitizaString($this->descricao);
			$serieSanitizada = (int) $this->serie;
			$escolaSanitizada = (int) $this->escola;
			$idChatSanitizado = (int) $this->idChat;
			$idPlanetaSanitizado = (int) $this->idPlaneta;

			$q->solicitar("
				INSERT INTO Turmas
					(nomeTurma, profResponsavel, descricao, serie, Escola, chat_ID, idPlaneta);
				VALUES(
					'$nomeTurmaSanitizado',
					'$profResponsavelSanitizado',
					'$descricaoSanitizada',
					'$serieSanitizada',
					'$escolaSanitizada',
					'$idChatSanitizado',
					'$idPlanetaSanitizado'
					)");

			if($q->erro == ""){
				$this->id = $q->ultimoId();
				$this->salvo = true;
			}
		}else{
			$query = ("
				UPDATE Planetas SET 
					nomeTurma   = '$this->nomeTurmaSanitizado',
					profResponsavel   = '$this->profResponsavelSanitizado',
					descricao = '$this->descricaoSanitizada',
					serie  = '$this->serieSanitizada',
					Escola = '$this->escolaSanitizada',
					chat_ID = '$this->idChatSanitizado',
					idPlaneta = '$this->idPlanetaSanitizado',
				WHERE CodTurma = '$this->id'");
		}
	}
	
	function toJson($sendHeaders = false){
		if($sendHeaders){
			header("Content-Type: application/json");
		}
		
		$json = [];
		$json['id']     = $this->id;
		$json['nomeTurma']   = $this->nomeTurma;
		$json['profResponsavel']   = $this->profResponsavel;
		$json['descricao'] = $this->descricao;
		$json['serie']  = $this->serie;
		$json['escola']  = $this->escola;
		$json['idChat']  = $this->idChat;
		$json['idPlaneta']  = $this->idPlaneta;

		return json_encode($json);
	}
}