<?php
/*
	Classe para guardar dados de turma.
	
	Identifica erros de formataчуo, retornando-os e usa comunicaчѕes com o banco de dados para salvar/editar/deletar turmas.
*/
session_start();

require("../../cfg.php");
require("../../bd.php");

class c_usuario{
//dados	
	//---- Mensagens de Erro
	private $MSG_ERRO_DESCONHECIDO = "Erro desconhecido"; //Caso nуo seja possэvel determinar o tipo de erro, clсusula default.
	private $MSG_ERRO_INEXISTENTE = "Erro inexistente";	//Erro = null, mas foi feita chamada a erro(). Щ erro de lѓgica.
	private $MSG_ERRO_NOME_INEXISTENTE = "Erro: este nome de professor nуo estс cadastrado";
	private $MSG_ERRO_NOME_DUPLICADO = "Erro: este nome estс cadastrado para mais de um professor";
	private $MSG_ERRO_NOME_INVALIDO = "Erro: verifique o nome do professor";
	
	//---- Erros
	private $erro = null;
	private $ERRO_NOME_INEXISTENTE = 1;
	private $ERRO_NOME_DUPLICADO = 2;
	private $ERRO_NOME_INVALIDO = 3;
	
	//---- Dados
	private $identificacao = null;		
	private $login = null;				
	private $senha = null;
	private $aniversario = null;
	private $nome = null;
	private $email = null;
	private $nivel = null;
	private $personagem = null;
	
//mщtodos
	function c_usuario(){
		
	}

	//---- Dados
	public function setIdentificacao($identificacao_param){
		$this->identificacao = $identificacao_param;
	}
	public function setLogin($login_param){
		$this->login = $login_param;
	}
	public function setSenha($senha_param){
		$this->senha = $senha_param;
	}
	public function setAniversario($aniversario_param){
		$this->aniversario = $aniversario_param;
	}
	public function setNome($nome_param){
		$this->nome = $nome_param;
	}
	public function setEmail($email_param){
		$this->email = $email_param;
	}
	public function setNivel($nivel_param){
		$this->nivel = $nivel_param;
	}
	public function setPersonagem($personagem_param){
		$this->personagem = $personagem_param;
	}
	
	//---- Banco de dados
	public function cadastrarBD(){
		//Validaчуo dos dados, protegendo o BD de dados que nуo pode receber.
		if($this->validarSemId()){
			//Definiчуo da sql, excluindo campos que podem ser null.
			if($this->personagem == null){
				$cadastroSQL = "INSERT INTO $tabela_usuarios (usuario_login, usuario_senha, usuario_data_aniversario, usuario_nome, usuario_email, usuario_nivel) 
								VALUES ('$login', '$senha', '$aniversario', '$nome', '$email', $nivel)";
			}
			else{
				$cadastroSQL = "INSERT INTO $tabela_usuarios (usuario_login, usuario_senha, usuario_data_aniversario, usuario_nome, usuario_email, usuario_nivel, usuario_id_personagem) 
								VALUES ('$login', '$senha', '$aniversario', '$nome', '$email', $nivel, $personagem)";
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
	public function pesquisarOutrosDadosTendoNome(){
		if($this->validarNome()){
			$pesquisa = new conexao();
			$pesquisa->solicitar("SELECT * FROM $tabela_usuarios WHERE $usuario_nome = '$this->nome'");
			
			if($pesquisa->registros == 1){
				$this->identificacao = $pesquisa->resultado['usuario_id'];
				$this->login = $pesquisa->resultado['usuario_login'];
				$this->senha = $pesquisa->resultado['usuario_senha'];
				$this->aniversario = $pesquisa->resultado['usuario_data_aniversario'];
				$this->email = $pesquisa->resultado['usuario_email'];
				$this->nivel = $pesquisa->resultado['usuario_nivel'];
				$this->personagem = $pesquisa->resultado['usuario_personagem_id'];
				return true;
			}
			else if($pesquisa->registros > 1){
				$this->erro = $this->ERRO_NOME_DUPLICADO;
				return false;
			}
			else{
				$this->erro = $this->ERRO_NOME_INEXISTENTE;
				return false;
			}
		}
		else{
			$this->erro = $this->ERRO_NOME_INVALIDO;
			return false;
		}
	}
	
	//---- Formato
	//A validaчуo щ feita contra dados que o BD nуo pode receber.
	public function validar(){	
		return ($this->validarIdentificacao() 
				and $this->validarLogin() 
				and $this->validarSenha() 
				and $this->validarAniversario() 
				and $this->validarNome()
				and $this->validarEmail()
				and $this->validarNivel()
				and $this->validarPersonagem());
	}
	public function validarSemId(){	
		return ($this->validarLogin() 
				and $this->validarSenha() 
				and $this->validarAniversario() 
				and $this->validarNome()
				and $this->validarEmail()
				and $this->validarNivel()
				and $this->validarPersonagem());
	}
	public function validarIdentificacao(){
		switch($this->identificacao){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarLogin(){
		switch($this->login){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarSenha(){
		switch($this->senha){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarAniversario(){
		switch($this->aniversario){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarNome(){
		switch($this->nome){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarEmail(){
		switch($this->email){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarNivel(){
		switch($this->nivel){
			case null: return false;
			break;
		
			
			default: return true;
			break;
		}
	}
	public function validarPersonagem(){
		switch($this->personagem){
			case null: return true; //nullable em tabela_usuarios
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
			case $this->ERRO_NOME_INEXISTENTE: return $this->MSG_ERRO_NOME_INEXISTENTE;
			break;
			case $this->ERRO_NOME_DUPLICADO: return $this->MSG_ERRO_NOME_DUPLICADO;
			break;
			case $this->ERRO_NOME_INVALIDO: return $this->MSG_ERRO_NOME_INVALIDO;
			break;
			
			default:return $this->MSG_ERRO_DESCONHECIDO;
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