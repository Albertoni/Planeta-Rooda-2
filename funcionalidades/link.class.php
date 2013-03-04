<?php
require_once("cfg.php");
require_once("bd.php");

class Link {
  private $end_link;  
  private $id;
  
  function Link($endereco_link = ""){     
    global $tabela_links;  	
	$this->end_link = $endereco_link;  	
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
  
  //Verifica se o link jah estah no BD 
  //Se estah retorna true, se nao esta retorna false  
  public function isLinkBD(){
    global $tabela_links;	
	if ($this->end_link == ""){
        return false;
	}	  	
	$consulta = new conexao();
	$consulta->connect();
	$consulta->solicitar("SELECT endereco FROM $tabela_links");	
	for ($I = 0 ; $I < $consulta->registros ; $I++){
	    if ($consulta->resultado["endereco"] == $this->getLink() ){
		    return true;
	    }
		$consulta->proximo();
	}
	return false;	
  }
  
  //Insere no BD o link guardado no $this->end_link
  public function uploadBD(){    
      global $tabela_links;
	  $link = $this->getLink();	  
      if ($link == ""){
         return "ERRO - Tentativa de dar upload ao BD de um link vazio";
	  }	  	  
	  $consulta = new conexao();
	  $consulta->connect();	  
	  //$this->setId($arquivo_id);
	  $consulta->solicitar("INSERT INTO $tabela_links (endereco) VALUES ('$link');");  	  	  
	  
	  
	  if ($consulta->erro === ""){
	    return 0;
	  }
	  else{
	    return $consulta->erro;
	  }	
  }
  
  //acessa o bd e retorna o link do id passado como parametro
  //se encontrar retorna true, se nao encontrar retorna false
  public function getLinkBD($id){
    global $tabela_links;
	$consulta = new conexao();
	$consulta->connect();
	$consulta->solicitar("SELECT endereco FROM $tabela_links WHERE Id = '$id'");
	if ($consulta->itens){
	  $this->setLink($consulta->resultado["endereco"]);
	  $this->setId($id);
	  
	  return true;
	}
	else {
	  
	  $this->clear();
	  return false;
	}  
  }    
  
  //limpa as informacoes do objeto
  public function clear(){
    $this->end_link = "";
	$this->id = 0;  
  }
  
}

?>