<?php
require_once("cfg.php");
require_once("bd.php");

class Link {
	private $id;
	private $end_link;
	private $funcionalidade_tipo;
	private $funcionalidade_id;
	private $erros = array();
	private $listaLinks = array();
	private $modo;
	private $titulo;
	private $autor;
	private $tags;
	
	/*existem 3 opcoes de uso da classe link
	//pode-se fornecer como parametros para o construtor 
	//uma tupla (endereco<string>, funcionalidade_tipo<int>, funcionalidade_id<int>, titulo<string>, autor<string>, tags<string>), fara que o objeto
	//de um upload para o bd
	//pode-se fornecer como parametros um id<int>, que fara com que ele de um download do bd
	//pode-se fornecer como parametros dois inteiros (funcionalidade_tipo<int>, funcionalidade_id<int>),
	//o que fara com que ele faca um download dos links em questao.*/
function Link($param1 , $param2=-1, $param3=-1, $titulo="", $autor="", $tags=""){
	global $tabela_links;
	
	if ((is_string($param1) === true) and ($param1 != "") and ($param2 > 0) and ($param3 > 0)){
		//upload pro bd
		$this->end_link = $param1;
		if ($this->isPrefixoHttp() === false){
			$this->end_link = "http://".$this->end_link;
		}
		$this->funcionalidade_tipo = $param2;
		$this->funcionalidade_id = $param3;
		$this->modo = 1;
		$this->titulo = $titulo;
		$this->autor = $autor;
		$this->tags = $tags;
		$this->upload();
	}
	else if ((is_int($param1)===true) and ($param1>0) and ($param2===-1) and ($param3===-1)){
		//download do bd pelo id
		//echo("putz2");
		$this->id = $param1;
		$this->modo = 2;
		$this->download();
		
	}
	else if ((is_int($param1)===true) and ($param1>0) and ($param2 > 0) and ($param3===-1)){
		$this->funcionalidade_tipo = $param1;
		$this->funcionalidade_id = $param2;
		$this->modo = 3;
		//echo("putz");
		$this->download();
	}
	else {
		$this->erros[]="ERRO - parametros errados em Link Constructor:".$param1.";".$param2.";".$param3;
	}
}

private function upload(){
	global $tabela_links;
	
	if ($uid == 0){
		$this->erros[]= "ERRO - VOCÊ NÃO ESTÁ LOGADO!!!"; // mas é um babaca
	}
	else if ($this->isLinkBD() === false){
		$consulta = new conexao();
		
		$funcionalidade_tipo	= (int) $this->funcionalidade_tipo;
		$funcionalidade_id		= (int) $this->funcionalidade_id;
		
		$end_link	= $consulta->sanitizaString($this->getLink());
		$titulo		= $consulta->sanitizaString($this->titulo);
		$autor		= $consulta->sanitizaString($this->autor);
		$tags		= $consulta->sanitizaString($this->tags);
		$uid		= $_SESSION['SS_usuario_id'];
		
		$consulta->solicitar("INSERT INTO $tabela_links
							(endereco, funcionalidade_tipo, funcionalidade_id, titulo, autor, tags, uploader_id)
					VALUES ('$end_link', '$funcionalidade_tipo','$funcionalidade_id', '$titulo', '$autor', '$tags', $uid);");
		$this->id = $consulta->ultimo_id();
		if ($consulta->erro !== ""){
			$this->erros[] = "ERRO - \"".$consulta->erro."\"";
		}
	}
	else {
		$this->erros[] = "ERRO - Arquivo ja existe no banco de dados";
	}
}

private function download(){
	global $tabela_links;
	if ($this->modo===2){
		$id = $this->id;
		$colunas = "endereco, funcionalidade_tipo, funcionalidade_id";
		$condicao = "Id = '$id'";
	
	}
	else if ($this->modo===3){
		$funcionalidade_tipo = (int) $this->funcionalidade_tipo;
		$funcionalidade_id	 = (int) $this->funcionalidade_id;
		$colunas = "endereco";
		$condicao = "funcionalidade_tipo='$funcionalidade_tipo' AND funcionalidade_id='$funcionalidade_id'";
	}	
	$consulta = new conexao();
	$consulta->connect();
	$consulta->solicitar("SELECT $colunas 
							FROM $tabela_links 
							WHERE $condicao");
	if ($this->modo===2){
		$this->end_link = $consulta->resultado['endereco'];
		$this->funcionalidade_tipo = $consulta->resultado['funcionalidade_tipo'];
		$this->funcionalidade_id = $consulta->resultado['funcionalidade_id'];
	}
	else if ($this->modo===3){
		for($i=0 ; $i < count($consulta->itens) ; $i++) {
			$this->listaLinks[] = $consulta->resultado['endereco'];
			$consulta->proximo();
		}
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
	
	
	//exclui o link do bd
	public function excluir(){
		global $tabela_links;
		$funcionalidade_tipo = (int) $this->funcionalidade_tipo;
		$funcionalidade_id = (int) $this->funcionalidade_id;
		echo("$endereco	 $funcionalidade_tipo	$funcionalidade_id");
		$consulta = new conexao();
		
		$endereco = $consulta->sanitizaString($this->end_link);
		
		$consulta->solicitar("DELETE FROM $tabela_links 
								WHERE endereco = '$endereco'
								AND funcionalidade_tipo	= '$funcionalidade_tipo'
								AND funcionalidade_id	= '$funcionalidade_id'");
		
	
	}
	
	private function isPrefixoHttp(){
	$endereco = $this->end_link;
	if (strpos($endereco, '://') != 0){return true;}
	else return false; // trust me on this.
	
	}
	
	public function getListaLinks(){
	return $this->listaLinks;
	
	}
	public function getLink(){
		return $this->end_link;
	}
	public function getId(){
		return $this->id;
	}
	
	public function setLink($link){
	$this->end_link = $link;
	}
	private function setId($id){
	$this->id = $id;
	}
	public function setTitulo($tit){
		$this->titulo = $tit; // sem piadinhas
	}
	
	//Verifica se o link jah estah no BD 
	//Se estah retorna true, se nao esta retorna false
	public function isLinkBD(){
	global $tabela_links;
	$funcionalidade_tipo = (int) $this->funcionalidade_tipo;
	$funcionalidade_id = (int) $this->funcionalidade_id;
	$consulta = new conexao();
	$consulta->connect();
	$consulta->solicitar("SELECT * 
							FROM $tabela_links 
							WHERE funcionalidade_tipo=$funcionalidade_tipo
							AND funcionalidade_id = $funcionalidade_id");
	for ($I = 0 ; $I < $consulta->registros ; $I++){
		if ($consulta->resultado["endereco"] == $this->getLink() ){
			
			return true;
		}
		$consulta->proximo();
	}
	
	return false;
	}
}

?>
