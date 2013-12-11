<?php
/*
	Classe para guardar dados de turma.
	
	Identifica erros de formataчуo, retornando-os e usa comunicaчѕes com o banco de dados para salvar/editar/deletar turmas.
*/
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");

class c_turma{
//dados	
	//---- Mensagens de Erro
	private $MSG_ERRO_DESCONHECIDO = "Erro desconhecido"; //Caso nуo seja possэvel determinar o tipo de erro, clсusula default.
	private $MSG_ERRO_INEXISTENTE = "Erro inexistente";	//Erro = null, mas foi feita chamada a erro(). Щ erro de lѓgica.
	private $MSG_ERRO_IDENTIFICACAO_NULL = "Erro: Identificaчуo nуo especificada"; //Erro de lѓgica desta classe. A identificaчуo nunca deve ser null.
	private $MSG_ERRO_NOME_NULL = "Erro: Nome nуo especificado";
	private $MSG_ERRO_PROFESSOR_NULL = "Erro: Professor nуo especificado";
	private $MSG_ERRO_SERIE_NULL = "Erro: Sщrie nуo especificada";
	
	//---- Erros
	private $erro = null;
	private $ERRO_IDENTIFICACAO_NULL = 1;
	private $ERRO_NOME_NULL = 2;
	private $ERRO_PROFESSOR_NULL = 3;
	private $ERRO_SERIE_NULL = 4;
	
	
	//---- Dados	
	private $identificacao = null;		
	private $nome = null;				
	private $professor = null;	//Um objeto de c_usuario.
	private $descricao = null;
	private $serie = null;
	
//mщtodos
	function c_turma(){
		
	}

	//---- Dados
	public function setIdentificacao($identificacao_param){
		$this->identificacao = $identificacao_param;
	}
	public function setNome($nome_param){
		$this->nome = $nome_param;
	}
	public function setProfessor($professor_param){
		$this->professor = $professor_param;
		if(!$this->professor->pesquisarOutrosDadosTendoNome()){
			$this->professor = null;
		}
	}
	public function setDescricao($descricao_param){
		$this->descricao = $descricao_param;
	}
	public function setSerie($serie_param){
		$this->serie = $serie_param;
	}
	
	//---- Banco de dados
	public function cadastrarBD(){
		//Validaчуo dos dados, protegendo o BD de dados que nуo pode receber.
		if($this->validarSemId()){
			//Definiчуo da sql, excluindo campos que podem ser null.
			if($this->descricao == null){
				$cadastroSQL = "INSERT INTO $tabela_turmas (nomeTurma, profResponsavel, serie) 
								VALUES ('$this->nome', $this->professor.codigo, $this->serie)";
			}
			else{
				$cadastroSQL = "INSERT INTO $tabela_turmas (nomeTurma, profResponsavel, descricao, serie) 
								VALUES ('$this->nome', $this->professor.codigo, '$this->descricao', $this->serie)";
			}
			$cadastro = new conexao();
			$cadastro->solicitar($cadastroSQL);
			return true;
		}
		else{
			return false;
		}
	}
	public function editarBD(){
		//Validaчуo dos dados, protegendo o BD de dados que nуo pode receber.
		if($this->validarIdentificacao()){
			
			return true;
		}
		else{
			return false;
		}
	}
	public function deletarBD(){
		//Validaчуo dos dados, protegendo o BD de dados que nуo pode receber.
		if($this->validarIdentificacao()){
			
			return true;
		}
		else{
			return false;
		}
	}
	
	//---- Formato
	//A validaчуo щ feita contra dados que o BD nуo pode receber.
	public function validar(){	
		return ($this->validarIdentificacao() 
				and $this->validarNome() 
				and $this->validarProfessor() 
				and $this->validarDescricao() 
				and $this->validarSerie());
	}
	public function validarSemId(){	
		return ($this->validarNome() 
				and $this->validarProfessor() 
				and $this->validarDescricao() 
				and $this->validarSerie());
	}
	public function validarIdentificacao(){
		switch($this->identificacao){
			case null:  $this->erro = $this->ERRO_IDENTIFICACAO_NULL;
						return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarNome(){
		switch($this->nome){
			case null:  $this->erro = $this->ERRO_NOME_NULL;
						return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarProfessor(){
		switch($this->nome){
			case null:  $this->erro = $this->ERRO_NOME_NULL;
						return false;
			break;
		
			
			default: return $this->professor.validarNome();
			break;
		}
	}
	public function validarDescricao(){
		switch($this->descricao){
			case null: return true;	//Descriчуo щ nullable na tabela de turmas.
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarSerie(){
		switch($this->serie){
			case null:  $this->erro = $this->ERRO_SERIE_NULL;
						return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	
	//---- Erros
	public function erro(){
		switch($this->erro){
			case null: return $this->MSG_ERRO_INEXISTENTE;
			break;
			case $this->ERRO_IDENTIFICACAO_NULL: return $this->MSG_ERRO_IDENTIFICACAO_NULL;
			break;
			case $this->ERRO_NOME_NULL: return $this->MSG_ERRO_NOME_NULL;
			break;
			case $this->ERRO_PROFESSOR_NULL: return $this->MSG_PROFESSOR_NULL;
			break;
			case $this->ERRO_SERIE_NULL: return $this->MSG_ERRO_SERIE_NULL;
			break;
			
			
			
			
			default:if($this->professor->possuiErro()){
						return $this->professor->erro();
					}
					else{
						return $this->MSG_ERRO_DESCONHECIDO;
					}
			break;
		}
	}
	public function possuiErro(){
		if($this->erro != null){
			return true;
		}
		else{
			return false;
		}
	}



}
?>