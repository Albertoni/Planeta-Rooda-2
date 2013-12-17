<?php

require_once(dirname(__FILE__)."/../../../../bd.php");
require_once(dirname(__FILE__)."/../../../../cfg.php");
require_once(dirname(__FILE__)."/../IPersistente.php");
include_once('fatoresPersonalidade.class.php');

class fatoresPersonalidadePersistente extends fatoresPersonalidade
	implements IPersistente{
//dados
	//Id deste objeto no banco de dados.
	private $id;
	
	//Id do usuário que realizou este teste de personalidade.
	private $idUsuario;
	
	//Conexão com o BD.
	private $conexao;
	
	//Banco de dados
	const NOME_TABELA						 = "fatoresPersonalidade";
	const NOME_COLUNA_ID					 = "id";
	const NOME_COLUNA_USUARIO				 = "usuario";
	const NOME_COLUNA_ASSISTENCIA			 = "assistencia";
	const NOME_COLUNA_INTRACEPCAO			 = "intracepcao";
	const NOME_COLUNA_AFAGO					 = "afago";
	const NOME_COLUNA_DEFERENCIA			 = "deferencia";
	const NOME_COLUNA_AFILIACAO				 = "afiliacao";
	const NOME_COLUNA_DOMINANCIA			 = "dominancia";
	const NOME_COLUNA_DENEGACAO				 = "denegacao";
	const NOME_COLUNA_DESEMPENHO			 = "desempenho";
	const NOME_COLUNA_EXIBICAO				 = "exibicao";
	const NOME_COLUNA_AGRESSAO				 = "agressao";
	const NOME_COLUNA_ORDEM					 = "ordem";
	const NOME_COLUNA_PERSISTENCIA			 = "persistencia";
	const NOME_COLUNA_MUDANCA				 = "mudanca";
	const NOME_COLUNA_AUTONOMIA				 = "autonomia";
	const NOME_COLUNA_HETEROSSEXUALIDADE	 = "heterossexualidade";
	
//métodos
	/*
	* Construtor.
	*
	* @param int $idUsuario Id do usuário cuja personalidade é expressa pelas variáveis.
	*/
	public function __construct($idUsuario			 = null,
								$assistencia		 = self::EQUILIBRIO,
								$intracepcao		 = self::EQUILIBRIO,
								$afago				 = self::EQUILIBRIO,
								$deferencia			 = self::EQUILIBRIO,
								$afiliacao			 = self::EQUILIBRIO,
								$dominancia			 = self::EQUILIBRIO,
								$denegacao			 = self::EQUILIBRIO,
								$desempenho			 = self::EQUILIBRIO,
								$exibicao			 = self::EQUILIBRIO,
								$agressao			 = self::EQUILIBRIO,
								$ordem				 = self::EQUILIBRIO,
								$persistencia		 = self::EQUILIBRIO,
								$mudanca			 = self::EQUILIBRIO,
								$autonomia			 = self::EQUILIBRIO,
								$heterossexualidade	 = self::EQUILIBRIO){
		$this->conexao				= new conexao();
		$this->id 					= IPersistente::ID_OBJETO_NAO_SALVO;
		$this->idUsuario			= $idUsuario;
		parent::__construct($assistencia, $intracepcao, $afago, $deferencia, $afiliacao, $dominancia, $denegacao,
							$desempenho, $exibicao, $agressao, $ordem, $persistencia, $mudanca, $autonomia, $heterossexualidade);
	}
	
	public function __get($variavel){
		return $this->$variavel;
	}
	
	/**
	* @return Id deste objeto no banco de dados.
	*/
	public function getId(){
		return $this->id;
	}
	
	/**
	* Salva este objeto no banco de dados.
	* Decide se deve usar inserir ou atualizar.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function salvar(){
		$conseguiu = false;
		if($this->id == IPersistente::ID_OBJETO_NAO_SALVO){
			$conseguiu = $this->inserir();
		} else {
			$conseguiu = $this->atualizar();
		}
		return $conseguiu;
	}

	/**
	* Insere este objeto no banco de dados.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function inserir(){
		$conseguiu = false;
		
		$this->conexao->solicitar("INSERT INTO ".self::NOME_TABELA." ("	.self::NOME_COLUNA_ASSISTENCIA			.","
																		.self::NOME_COLUNA_INTRACEPCAO			.","
																		.self::NOME_COLUNA_AFAGO				.","
																		.self::NOME_COLUNA_DEFERENCIA			.","
																		.self::NOME_COLUNA_AFILIACAO			.","
																		.self::NOME_COLUNA_DOMINANCIA			.","
																		.self::NOME_COLUNA_DENEGACAO			.","
																		.self::NOME_COLUNA_DESEMPENHO			.","
																		.self::NOME_COLUNA_EXIBICAO				.","
																		.self::NOME_COLUNA_AGRESSAO				.","
																		.self::NOME_COLUNA_ORDEM				.","
																		.self::NOME_COLUNA_PERSISTENCIA			.","
																		.self::NOME_COLUNA_MUDANCA				.","
																		.self::NOME_COLUNA_AUTONOMIA			.","
																		.self::NOME_COLUNA_HETEROSSEXUALIDADE	.","
																		.self::NOME_COLUNA_USUARIO				.")
									VALUES							 ("	.$this->assistencia			.","
																		.$this->intracepcao			.","
																		.$this->afago				.","
																		.$this->deferencia			.","
																		.$this->afiliacao			.","
																		.$this->dominancia			.","
																		.$this->denegacao			.","
																		.$this->desempenho			.","
																		.$this->exibicao			.","
																		.$this->agressao			.","
																		.$this->ordem				.","
																		.$this->persistencia		.","
																		.$this->mudanca				.","
																		.$this->autonomia			.","
																		.$this->heterossexualidade	.","
																		.$this->idUsuario			.")");
		
		if($this->conexao->erro != ''){
			$conseguiu = false;
			echo $this->conexao->erro;
		} else {
			$this->id = $this->conexao->ultimo_id();
			$conseguiu = true;
		}
		return $conseguiu;
	}
	
	/**
	* Atualiza este objeto no banco de dados.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function atualizar(){
		$conseguiu = false;
		
		$this->conexao->solicitar("UPDATE ".self::NOME_TABELA."
									SET			".self::NOME_COLUNA_ASSISTENCIA			."=".$this->assistencia			."
												".self::NOME_COLUNA_INTRACEPCAO			."=".$this->intracepcao			."
												".self::NOME_COLUNA_AFAGO				."=".$this->afago				."
												".self::NOME_COLUNA_DEFERENCIA			."=".$this->deferencia			."
												".self::NOME_COLUNA_AFILIACAO			."=".$this->afiliacao			."
												".self::NOME_COLUNA_DOMINANCIA			."=".$this->dominancia			."
												".self::NOME_COLUNA_DENEGACAO			."=".$this->denegacao			."
												".self::NOME_COLUNA_DESEMPENHO			."=".$this->desempenho			."
												".self::NOME_COLUNA_EXIBICAO			."=".$this->exibicao			."
												".self::NOME_COLUNA_AGRESSAO			."=".$this->agressao			."
												".self::NOME_COLUNA_ORDEM				."=".$this->ordem				."
												".self::NOME_COLUNA_PERSISTENCIA		."=".$this->persistencia		."
												".self::NOME_COLUNA_MUDANCA				."=".$this->mudanca				."
												".self::NOME_COLUNA_AUTONOMIA			."=".$this->autonomia			."
												".self::NOME_COLUNA_HETEROSSEXUALIDADE	."=".$this->heterossexualidade	."
												".self::NOME_COLUNA_USUARIO				."=".$this->idUsuario			."
									WHERE		".self::NOME_COLUNA_ID."=".$this->id."");
		
		if($this->conexao->erro != ''){
			$conseguiu = false;
			echo $this->conexao->erro;
		} else {
			$conseguiu = true;
		}
		return $conseguiu;
	}

	/**
	* Deleta este objeto do banco de dados.
	*
	* @return Booleano 	se conseguir salvar, true
	*					senão, false
	*/
	public function deletar(){
		$conseguiu = false;
		
		$this->conexao->solicitar("DELETE FROM ".self::NOME_TABELA." WHERE ".self::NOME_COLUNA_ID."=".$this->id."");
		
		if($this->conexao->erro != ''){
			$conseguiu = false;
			echo $this->conexao->erro;
		} else {
			$conseguiu = true;
		}
		return $conseguiu;
	}
	
	/**
	* @return Array<Tipo> Array com todos os resultados encontrados no BD.
	*/
	public function busca(){
		$idUsuario = 0;
		$resultados = array();
		
		$this->conexao->solicitar("SELECT * 
									FROM ".self::NOME_TABELA." 
									WHERE ".self::NOME_COLUNA_USUARIO."=".$this->idUsuario."");
		
		if($this->conexao->erro != ''){
			$resultados = array();
			echo $this->conexao->erro;
		} else {
			$this->popular($this->conexao->resultado);
		}
	}
	
	/**
	* Popula este objeto com o conteúdo de um array.
	*
	* @param Array<String,String> array O array que mapeia nomes de propriedades desta classe em dados que devem ter.
	*/
	public function popular(array $array){
		$this->assistencia			 = $array[self::NOME_COLUNA_ASSISTENCIA];
		$this->intracepcao			 = $array[self::NOME_COLUNA_INTRACEPCAO];
		$this->afago				 = $array[self::NOME_COLUNA_AFAGO];
		$this->deferencia			 = $array[self::NOME_COLUNA_DEFERENCIA];
		$this->afiliacao			 = $array[self::NOME_COLUNA_AFILIACAO];
		$this->dominancia			 = $array[self::NOME_COLUNA_DOMINANCIA];
		$this->denegacao			 = $array[self::NOME_COLUNA_DENEGACAO];
		$this->desempenho			 = $array[self::NOME_COLUNA_DESEMPENHO];
		$this->exibicao				 = $array[self::NOME_COLUNA_EXIBICAO];
		$this->agressao				 = $array[self::NOME_COLUNA_AGRESSAO];
		$this->ordem				 = $array[self::NOME_COLUNA_ORDEM];
		$this->persistencia			 = $array[self::NOME_COLUNA_PERSISTENCIA];
		$this->mudanca				 = $array[self::NOME_COLUNA_MUDANCA];
		$this->autonomia			 = $array[self::NOME_COLUNA_AUTONOMIA];
		$this->heterossexualidade	 = $array[self::NOME_COLUNA_HETEROSSEXUALIDADE];
		$this->idUsuario			 = $array[self::NOME_COLUNA_USUARIO];
		$this->id					 = $array[self::NOME_COLUNA_ID];
	}
	
	
	
	
}

?>