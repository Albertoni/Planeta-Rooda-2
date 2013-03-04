<?php
/*
*	Sistema do blog
*
*/
$tabela_usuarios = "usuarios";

class Usuario { //estrutura para o item post do blog
	var $id = 0;
	var $user = "";
	var $pass = "";
	var $birthday = "";
	var $name = "";
	var $email = "";
	var $personagemId = 0;
	var $nivel = -1;
	private $erros = Array();
	
	function Usuario($id=0, $user="", $pass="", $birthday="", $name="", $email="", $personagem_id=0, $nivel=-1){
		$this->id = $id;
		$this->user = $user;
		$this->pass = $pass;
		$this->birthday = $birthday;
		$this->name = $name;
		$this->email = $email;
		$this->personagemId = $personagem_id;
		$this->nivel = $nivel;
	}
	/*
	function openUsuario($idUsuario) {
		global $tabela_usuarios;
		//echo("openUsuario -> ".$idUsuario."<br />");
		$q = new conexao();
		$q->solicitar("select * from $tabela_usuarios where usuario_id = '$idUsuario'");
		$numItens= count($q->itens);
		
		if($numItens == 0)	
			die("Usuario inexistente. id = ".$idUsuario);
		$this->setId($idUsuario);	
		$this->setUser($q->itens[0]['usuario_login']);
		$this->setPass($q->itens[0]['usuario_senha']);
		$this->setBirthday($q->itens[0]['usuario_data_aniversario']);
		$this->setName($q->itens[0]['usuario_nome']);				
		$this->setEmail($q->itens[0]['usuario_email']);				
	}*/
	
	//recebe como parametro um id (inteiro maior que 0) ou 
	//um nome de usuario e um password (nao sao verificados aqui se estao nos padroes do planeta)
	//em caso de erro adiciona uma posicao a mais no array de erros da classe explicando o ocorrido
	public function openUsuario($param , $param2="") {
		global $tabela_usuarios;
		$consulta = new conexao();
		if ((is_int($param)) and ($param2 == "")){
			$id = $param;
			$consulta->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_id = '$id'");
			$numItens= count($consulta->itens);
			if($numItens === 0)
				$this->erros[]= "Usuario inexistente (Id=$id)" ;
		}
		else if ((is_string($param)) and (is_string($param2)) and ($param2 != "")){
			$username = $param;
			$password = md5($param2);
			$consulta->solicitar("SELECT * FROM $tabela_usuarios WHERE usuario_login = '$username'");
			$numItens= count($consulta->itens);
			if($numItens === 0)
				$this->erros[]="Usuario inexistente (username=$username)" ;
			$passBD = $consulta->itens[0]['usuario_senha'];
			
			if ($password !== $passBD){
				$this->erros[]="ERRO - Senha de login errada";
			}
		}
		else $this->erros[]="ERRO - parametros de Usuario->openUsuario errados";
	
		if($this->temErro() === false){
			$this->setId($consulta->itens[0]['usuario_id']);
			$this->setUser($consulta->itens[0]['usuario_login']);
			$this->setPass($consulta->itens[0]['usuario_senha']);
			$this->setBirthday($consulta->itens[0]['usuario_data_aniversario']);
			$this->setName($consulta->itens[0]['usuario_nome']);
			$this->setEmail($consulta->itens[0]['usuario_email']);
			$this->setPersonagemId($consulta->itens[0]['usuario_personagem_id']);
			$this->setNivel($consulta->itens[0]['usuario_nivel']);
		}		
		
		
	}



	private function setId($id) {
		$this->id = $id;
	}

	private function setUser($user) {
		$this->user = $user;
	}

	private function setPass($pass) {
		$this->pass = $pass;
	}

	private function setBirthday($birthday) {
		$this->birthday = $birthday;
	}

	private function setName($name) {
		$this->name = $name;
	}
	
	private function setEmail($email) {
		$this->email = $email;
	}
	
	private function setPersonagemId($personagemId){
		$this->personagemId = $personagemId;
	}
	
	private function setNivel($nivel){
		$this->nivel = $nivel;
	}
	
	public function temErro(){
		if (count($this->erros) == 0){
			return false;
		}
		else return true;	
	}
	
	//funcao que retorna os erros que aconteceram
	public function getErrosArray(){
		return $this->erros;
	}
	
	public function getErrosString(){
		$erros = "";		
		for ($i = 0 ; $i < count($this->erros) ; $i++){		   
			if ($i < (count($this->erros) - 1) ){
				$erros .= $this->erros[$i]."<BR />";
			}
			else {
				$erros .= $this->erros[$i];
			}			
		}	
		return $erros;
	}
	
	
	public function getId(){
		return $this->id;
	}
	
	public function getUser(){
		return $this->user;
	}
	
	public function getPass(){
		return $this->pass;
	}
	
	public function getBirthday(){
		return $this->birthday;
	}
	
	public function getName() {
		return $this->name;	
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getPersonagemId(){
		return $this->personagemId;
	}
	
	public function getNivel(){
		return $this->nivel;
	}
}

?>
