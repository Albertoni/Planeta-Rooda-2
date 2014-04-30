<?php
require_once("terreno.class.php");
require_once("Planeta-Guilherme.class.php");
class Turma_Tentativa_Guilherme
{
	//Atributos da classe Turma.
	private $codTurma;
	private $nomeTurma;
	private $profResponsavel;
	private $descricao;
	private $serie;
	private $escola;
	private $chatId;
	private $NivelDePermissao = array(); //Array associativo, indexado pelo ID_USUARIO, contendo os níveis de permissão (associacao) - obtidos da tabela Turmas Usuario.
	private $idPlaneta;
	private $salvo;
	
	public function __construct($novoNomeTurma = "",
								$novoProfResponsavel = 0,
								$novaDescricao = "", 
								$novaSerie = 0, 
								$novaEscola = 0, 
								$novaChatId = 0,
								$novoPlaneta = 0,
								$novoSalvo = false
								)
	{
		$this->nomeTurma = $novoNomeTurma;
		$this->profResponsavel = $novoProfResponsavel;
		$this->descricao = $novaDescricao;
		$this->serie = $novaSerie;
		$this->escola = $novaEscola;
		$this->chatId = $novaChatId;
	    $this->idPlaneta = $novoIdPlaneta;
		$this->salvo = $novoSalvo;
		//array de NivelDePermissao será inicializado ao se abrir uma turma.
	}
	
	public function getCodTurma(){	return $this->codTurma;}
	
	public function getNomeTurma(){	return $this->nomeTurma;}
	
	public function getProfResponsavel(){	return $this->ProfResponsavel;}
	
	public function getDescricao(){	return $this->descricao;}
	
	public function getSerie(){	return $this->serie;}
	
	public function getEscola(){	return $this->escola;}
	
	public function getChatId(){	return $this->chatID;}
	
	function abrir($codTurma){
		$q = new conexao();
		$codTurmaSanitizado = $q->sanitizaString($codTurma);

		$q->solicitar("SELECT * FROM Turmas WHERE codTurma = '$codTurma'");

		if($q->registros > 0){
			$this->__construct( // melhor que copicolar código?
				$q->resultado['nomeTurma'],
				$q->resultado['profResponsavel'],
				$q->resultado['descricao'],
				$q->resultado['serie'],
				$q->resultado['Escola'],
				$q->resultado['chat_id'],
				$q->resultado['idPlaneta']
				);
			$this->codTurma = $q->resultado['codTurma'];
			$this->salvo = true;
			
			//Carrega no array NivelDePermissao, indexado pelo codUsuario, o nivel de associacao.
			$q->solicitar("SELECT * FROM TurmasUsuario WHERE codTurma = '$codTurma'");
			for($i=0; $i < $q->registros; $i++)
			{
				$this->NivelDePermissao[$q->resultado['codUsuario']] = $q->resultado['associacao'];
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
			$chatIdSanitizado = (int) $this->chatId;
			$idPlanetaSanitizado = (int) $this->idPlaneta;

			$q->solicitar("
				INSERT INTO Turmas 
					(nomeTurma, profResponsavel, descricao, serie, Escola, chat_id, idPlaneta) 
				VALUES(
					'$nomeTurmaSanitizado',
					'$profResponsavelSanitizado',
					'$descricaoSanitizada',
					'$serieSanitizada'),
					'$chatIdSanitizado',
					'$idPlanetaSanitizado'");

			if($q->erro == ""){
				$this->codTurma = $q->ultimoId();
				$this->salvo = true;
			}
		}else{
			$query = ("
				UPDATE Turmas SET 
					nomeTurma   = '$this->nomeTurmaSanitizado',
					profResponsavel   = '$this->profResponsavelSanitizado',
					descricao = '$this->descricaoSanitizada',
					serie  = '$this->serieSanitizada',
					Escola = '$this->escolaSanitizada',
					chat_id = '$this->chatIdSanitizado',
					idPlaneta = '$this->idPlanetaSanitizado'
				WHERE codTurma = '$this->codTurma'");
		}
	}

	function toJson($sendHeaders = false){
		if($sendHeaders){
			header("Content-Type: application/json");
		}
		
		$json = [];
		$json['idTurma']     = $this->codTurma;
		$json['nomeTurma']   = $this->nomeTurma;
		$json['profResponsavel']   = $this->profResponsavel;
		$json['descricao'] = $this->descricao;
		$json['serie']  = $this->serie;
		$json['escola']  = $this->Escola;
		$json['idChat']  = $this->chatId;
		$json['idPlaneta']  = $this->idPlaneta;

		return json_encode($json);
	}
	
	/*
		TODO:
	
	* Determina se houve alterações na funcionalidade desta turma.
	* @param funcionalidade_param Funcionalidade a ser verificada.
	* @param data_param Data à partir da qual uma alteração deve ter acontecido para ser retornada.
	* @return Número de alterações que ocorreram na dada funcionalidade desta turma desde a data passada.
	*/
	public function getNumeroAlteracoes($funcionalidade_param, $data_param){
		$alteracoes = 0;
		$sql = '';
		
		// DEBUG REMOVER ISSO ASSIM QUE O TEMPO DE ULTIMO LOGIN FOR IMPLEMENTADO
		$data_param = strtotime("-5 years"); //código de teste para mostrar para as gurias na reunião...
		$data_param = date("Y-m-d H:i:s", $data_param);
		
		switch($funcionalidade_param){
			case turma::BIBLIOTECA: $sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM BibliotecaMateriais
													WHERE codTurma = ".$this->id." AND '$data_param'<=data
											) AS BMalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM BibliotecaComentarios AS BC JOIN BibliotecaMateriais AS BM ON BC.codMaterial = BM.codMaterial
												WHERE BM.codTurma = ".$this->id." AND '$data_param'<=BC.data
											)";
				break;
			case turma::BLOG:		$sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM blogblogs AS B JOIN usuarios AS U ON OwnersIds = usuario_id
																		JOIN TurmasUsuario AS TU ON TU.codUsuario = U.usuario_id
																		JOIN blogposts AS BP ON BP.BlogId = B.Id
													WHERE B.Tipo=1 AND TU.codTurma=".$this->id." AND '$data_param'<=BP.Date
											) AS BPalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM blogblogs AS B JOIN usuarios AS U ON OwnersIds = usuario_id
																	JOIN TurmasUsuario AS TU ON TU.codUsuario = U.usuario_id
																	JOIN blogposts AS BP ON BP.BlogId = B.Id
																	JOIN blogcomentarios AS BC ON BC.PostId = BP.Id
												WHERE B.Tipo=1 AND TU.codTurma=".$this->id." AND '$data_param'<=BC.Date
											)";
				break;
			case turma::FORUM:		$sql = "";
				break;
			case turma::ARTE:		$sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM ArtesDesenhos
													WHERE codTurma=".$this->id." AND '$data_param'<=Data
											) AS ADalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM ArtesComentarios AS AC JOIN ArtesDesenhos AS AD ON AC.CodDesenho=AD.CodDesenho
												WHERE codTurma=".$this->id." AND '$data_param'<=AC.Data
											)";
				break;
			case turma::PERGUNTA:	$sql = "SELECT COUNT(*) AS alteracoes
											FROM PerguntaQuestionarios
											WHERE turma = ".$this->id." AND '$data_param'<=datainicio";
				break;
			case turma::PORTFOLIO:	$sql = "SELECT *
											FROM (
													SELECT COUNT(*) AS alteracoes
													FROM PortfolioProjetos 
													WHERE turma=".$this->id." AND '$data_param'<=dataCriacao
											) AS PPalteracoes
											UNION ALL(
												SELECT COUNT(*) AS alteracoes
												FROM PortfolioProjetos AS PP JOIN PortfolioPosts AS PPo ON PP.id=PPo.projeto_id
												WHERE PP.turma=".$this->id." AND ('$data_param'<=PPo.dataCriacao OR '$data_param'<=PPo.dataUltMod)
											)";
				break;
			case turma::PLAYER:		$sql = "SELECT COUNT(*)
											FROM PlayerComentarios AS PC JOIN PlayerVideos AS PV ON PC.id_video=PV.id
											WHERE PV.turma=".$this->id." AND '$data_param'<=PC.data";
				break;
			case turma::AULAS:		$sql = "";
				break;
		}
		if($sql != ''){
			$conexaoAlteracoes = new conexao();
			$conexaoAlteracoes->solicitar($sql);
			if(isset($conexaoAlteracoes->resultado['alteracoes'])){
				$alteracoes = 0;
				for($i=0; $i<$conexaoAlteracoes->registros; $i++){
					$alteracoes += $conexaoAlteracoes->resultado['alteracoes'];
					$conexaoAlteracoes->proximo();
				}
			}
			
		}
		return $alteracoes;
	}
}