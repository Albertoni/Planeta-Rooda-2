<?php
/*
	Classe para guardar dados de turma.
	
	Identifica erros de formata��o, retornando-os e usa comunica��es com o banco de dados para salvar/editar/deletar turmas.
*/
session_start();

require_once("../../cfg.php");
require_once("../../bd.php");

class c_turma{
//dados	
	//---- Mensagens de Erro
	private $MSG_ERRO_DESCONHECIDO = "Erro desconhecido"; //Caso n�o seja poss�vel determinar o tipo de erro, cl�usula default.
	private $MSG_ERRO_INEXISTENTE = "Erro inexistente";	//Erro = null, mas foi feita chamada a erro(). � erro de l�gica.
	private $MSG_ERRO_IDENTIFICACAO_NULL = "Erro: Identifica��o n�o especificada"; //Erro de l�gica desta classe. A identifica��o nunca deve ser null.
	private $MSG_ERRO_NOME_NULL = "Erro: Nome n�o especificado";
	private $MSG_ERRO_PROFESSOR_NULL = "Erro: Professor n�o especificado";
	private $MSG_ERRO_SERIE_NULL = "Erro: S�rie n�o especificada";
	
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
	
//m�todos
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
		//Valida��o dos dados, protegendo o BD de dados que n�o pode receber.
		if($this->validarSemId()){
			//Defini��o da sql, excluindo campos que podem ser null.
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
		//Valida��o dos dados, protegendo o BD de dados que n�o pode receber.
		if($this->validarIdentificacao()){
			
			return true;
		}
		else{
			return false;
		}
	}
	public function deletarBD(){
		//Valida��o dos dados, protegendo o BD de dados que n�o pode receber.
		if($this->validarIdentificacao()){
			
			return true;
		}
		else{
			return false;
		}
	}
	
	//---- Formato
	//A valida��o � feita contra dados que o BD n�o pode receber.
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
			case null: return true;	//Descri��o � nullable na tabela de turmas.
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