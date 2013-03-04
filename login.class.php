<?php
require_once("cfg.php");
require_once("bd.php");
require_once("usuarios.class.php");

class Login {
	private $usuario;		 //objeto da classe usuario. Instanciado com o metodo instanciarUsuario
	private $TAMMAX = 20;	 //"constante" que guarda o numero maximo de caracteres que o login 
							  //e o password devem ter
	private $TAMMIN = 6;	  //"constante" que guarda o numero minimo de caracteres que o login
							  //e o password devem ter
	public $validUsernameChars = array("_"); //conjunto de caracteres possiveis de serem 
											 //usados em username alem dos alfanumericos
	private $erros = array();	  //array de strings que guarda os erros da execucao do login, caso aja algum
	private $destino = "desenvolvimento/index.php";  //destino caso login esteja correto
	
	function Login($username , $password){
	
		if ($this->validUsername($username) === false){
			$this->erros[] = "ERRO - username fora do padrao (Class Login Constructor)";
		}
		
		if ($this->validPassword($password) === false){
			$this->erros[] = "ERRO - password fora do padrao (Class Login Constructor)";
		}
		
		if ($this->temErro() === false){
			$this->instanciarUsuario($username, $password);
		}
		
		if ($this->temErro() === false){
			$this->instanciarSessao();
		}
	}
	
	//funcao que retorna true se aconteceu algum erro
	//retorna false caso contrario
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
	
	
	public function respostaJS(){
		if ($this->temErro()){
			//return $data = '{ "valor":"1", "texto":"Erro no servidor"}';
			$erro = '"1"';
			$texto = '"'.$this->getErrosString().'"';
		}
		else {
			$erro = '"0"';
			$texto = '"'.$this->getDestino().'"';
		
		}
		
		$resposta = '{ "valor":'.$erro.', "texto":'.$texto.'}';
		return $resposta;
	}
	
	//retorna o objeto $this->usuario, caso ele esteja setado
	//senao retorna false
	public function getUsuario(){
		if (isset($this->usuario) === true){
			return $this->usuario;
		}
		else return false;
	}
	
	public function setDestino($destino){
		$this->destino = $destino;
	}
	
	public function getDestino(){
		return $this->destino;
	}
	
	private function instanciarSessao(){
		$_SESSION['SS_usuario_id']				= $this->usuario->getId();
		$_SESSION['SS_usuario_nome']			= $this->usuario->getName();
		$_SESSION['SS_usuario_nivel_sistema']	= $this->usuario->getNivel();
		$_SESSION['SS_usuario_login']			= $this->usuario->getUser();
		$_SESSION['SS_usuario_email']			= $this->usuario->getEmail();
		$_SESSION['SS_personagem_id']			= $this->usuario->getPersonagemId();
	}
	
	//testa se o tamanho do string esta entre as "constantes" TAMMIN e TAMMAX
	private function testNumChar($string){
		if ((strlen($string) >= $this->TAMMIN) && (strlen($string) <= $this->TAMMAX)){
			return true;
		}
		else return false;
	}
	
	//testa se o caracter passado eh uma letra ou um numero 
	// (nao testa se o parametro eh realmente apenas um caracter)
	
	private function isAlfaNumChar($char){
		$charLower = mb_strtolower($char);
		if ((ord($charLower) >= 48) && (ord($charLower) <= 57)){  //se o $char eh um numero
			return true;
		}
		else if ((ord($charLower) >= 97) && (ord($charLower) <= 122)){ //se o $char eh uma letra
			return true;		
		}
		else return false;
	
	}
	
	//para cada caracter do parametro verifica se ele eh alfanumerico.
	//Se nao for verifica se ele pertence ao array de caracteres nao-alfanumericos validos.
	//Nesses dois casos retorna true, senao retorna false.
	
	private function testChars($string){
		for ($i = 0 ; $i < strlen($string) ; $i++){
		
			if ($this->isAlfaNumChar($string[$i]) === false){
				$tempBool=false;
				foreach($this->validUsernameChars as $validChar){
					if ($string[$i]===$validChar){
						$tempBool=true;
						break;
					}
				}
				if ($tempBool === false){
					return false;
				}
				
			}
			
		}
		return true;
	}
	
	//Apenas verifica se o username estah no padrao. Nao verifica a sua consistencia no BD
	private function validUsername($username){
		//colocar aqui testes para saber se o username estah no padrao (definir um padrao)
		
		//- um username deve conter no minimo 6 e no maximo 20 caracteres alfanumericos ou underline '_'
		//- username eh case sensitive
		//- nao sao permitidos acentos
		
		if ($this->testNumChar($username) === false){
			return false;
		}
		if ($this->testChars($username) === false){
			return false;
		}
		return true;
	}
	
	//Apenas verifica se o password estah no padrao. Nao verifica a sua consistencia no BD
	private function validPassword($password){
		//colocar aqui testes para saber se o password estah no padrao
		
		//- deve conter no minimo 6 e no maximo 20 caracteres alfanumericos ou underline '_'
		//- password eh case sensitive
		//- nao permitir acentos
		
		if ($this->testNumChar($password) === false){
			return false;
		}
		if ($this->testChars($password) === false){
			return false;
		}
		return true;
	}
	
	//Cria uma nova instancia de usuario com o username e a senha, se a senha estiver correta
	private function instanciarUsuario($username,$password){
		$this->usuario = new Usuario();
		$resultado = $this->usuario->openUsuario($username, $password);
		if ($resultado != ""){
			$this->erros[] = $resultado;
		}
	}
}
?>
