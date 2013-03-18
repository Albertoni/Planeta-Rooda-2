<?php

if (class_exists('conexao') != true){ // conserta bugs raros mas incomodativos
class conexao {
  var $host;		// qual o servidor
  var $base;		// qual a base
  var $usuario;	// qual o username
  var $senha;		// qual a senha
  var $socket;		// socket da conexao com o banco
  var $erro;		// mensagem de erro da query
  var $intquery;	// int representando o resultado da query
  var $resultado;	// fetch_array de $intquery
  var $itens;
  var $registros;	// qtde de linhas encontradas
  var $index;		// indice do vetor $result
  var $status;		// retorno true ou false da query
  var $registro_atual; // registro atual

	var $socketMysqli;

	function conexao($host=0,$base=0,$usuario=0,$senha=0){
		global $BD_host1;
		global $BD_base1;
		global $BD_user1;
		global $BD_pass1;
		if(($host===0)||($base===0)||($usuario===0)||($senha===0)){
			$this->host=$BD_host1;
			$this->base=$BD_base1;
			$this->usuario=$BD_user1;
			$this->senha=$BD_pass1;
		}
		else{
			$this->host=$host;
			$this->base=$base;
			$this->usuario=$usuario;
			$this->senha=$senha;
		}
		if(!$this->connect()){
			die('Não foi possível conectar-se ao banco de dados');
		}
	}

	function connect(){
		$erroConexao=FALSE;
		$this->socketMysqli=new mysqli($this->host,$this->usuario,$this->senha,$this->base);
		if($this->socketMysqli->connect_errno){
			$this->erro=$this->socketMysqli->connect_error;
			$erroConexao=TRUE;
		}

		// INÍCIO
		// Remover quando o suporte a MySQL for abandonado em prol do MySQL Improved Extension
		$this->socket=mysql_connect($this->host,$this->usuario,$this->senha);
		if(!$this->socket){
			$this->erro=mysql_error($this->socket);
			$erroConexao=TRUE;
		}
		else{
			if(!mysql_select_db($this->base,$this->socket)){
				$this->erro=mysql_error($this->socket);
				$erroConexao=TRUE;
			}
		}
		// Remover quando o suporte a MySQL for abandonado em prol do MySQL Improved Extension
		// FIM

		if($erroConexao){
			$this->status=FALSE;
			echo $this->erro;
			return FALSE;
		}
		else{
			$this->erro='';
			$this->status=TRUE;
			return TRUE;
		}
	}

	function solicitar($query){
		$this->primeiro();
		$this->intquery=$this->socketMysqli->query($query);
		if(!$this->intquery){
			$this->erro=$this->socketMysqli->error;
			$this->status=FALSE;
			return FALSE;
		}
		else{
			if(strtolower(substr($query,0,6))==='select'){
				$this->registros=$this->intquery->num_rows;
				$this->resultado=$this->intquery->fetch_assoc();
				$this->itens=array();
				$itemAtual=$this->resultado;
				while($itemAtual){
					$this->itens[]=$itemAtual;
					$itemAtual=$this->intquery->fetch_assoc();
				}
			}
			$this->erro='';
			$this->status=TRUE;
			return TRUE;
		}
	}

	function solicitarSI($query){
		$this->primeiro();
		$this->intquery=$this->socketMysqli->query($query);
		if(!$this->intquery){
			$this->erro=$this->socketMysqli->error;
			$this->status=FALSE;
			return FALSE;
		}
		else{
			if(strtolower(substr($query,0,6))==='select'){
				$this->registros=$this->intquery->num_rows;
				$this->resultado=$this->intquery->fetch_assoc();
			}
			$this->erro='';
			$this->status=TRUE;
			return TRUE;
		}
	}

	function ir_para($id){
		if(!$this->intquery->data_seek($id)){
			$this->erro=$this->socketMysqli->error;
			$this->status=FALSE;
			return FALSE;
		}
		else{
			$this->resultado=$this->intquery->fetch_assoc();
			$this->erro='';
			$this->index=$id;
			$this->registro_atual=$id;
			return TRUE;
		}
	}

	function primeiro(){
		if($this->index!=0){
			$this->ir_para(0);
		}
	}

	function anterior(){
		if(($this->index-1)>=0){
			$this->ir_para($this->index-1);
		}
		else{
			$this->resultado='';
		}
	}

	function proximo(){
		if(($this->index+1)<$this->registros){
			$this->ir_para($this->index+1);
		}
		else{
			$this->resultado='';
		}
	}

	function ultimo(){
		if($this->index!=($this->registros-1)){
			$this->ir_para($this->registros-1);
		}
	}

	function ultimo_id(){
		return $this->socketMysqli->insert_id;
	}

	function fechar(){
		// Função vazia por retrocompatibilidade
		// O PHP se encarrega de desfazer as conexões e limpar os resultados armazenados
	}

	function inserir($dados,$tabela){
		$sql_campos='(';
		$sql_valores='(';
		foreach($dados as $nome_campo => $valor_campo){
			$sql_campos.=$nome_campo.',';
			$sql_valores.='"'.$valor_campo.'",';
		}
		$sql_campos{strlen($sql_campos)-1}=')';
		$sql_valores{strlen($sql_valores)-1}=')';
		$query=$this->socketMysqli->query('INSERT INTO '.$tabela.' '.$sql_campos.' VALUES '.$sql_valores);
		echo $this->socketMysqli->error;
		return $query;
	}

	function atualizar($id,$dados,$tabela){
		$query='';
		foreach($dados as $nome_campo => $valor_campo){
			$query.=$nome_campo.'="'.$valor_campo.'",';
		}
		$query{strlen($query)-1}=' ';
		$query=$this->socketMysqli->query('UPDATE '.$tabela.' SET '.$query.'WHERE Id='.$id);
		echo $this->socketMysqli->error;
		return $query;
	}

  //global $BD_host1;
  //****************************** CONSTRUTOR
  //function conexao($host="ragno.ufrgs.br",$base="nuted_planeta",$usuario="nuted_planeta",$senha="6132M3fW7559"){  
  /*function conexao($host=0,$base=0,$usuario=0,$senha=0){
	//echo($host." , ".$base." , ".$usuario." , ".$senha."   ");
	global $BD_host1;
	global $BD_base1;
	global $BD_user1;
	global $BD_pass1;
	if (($host==0) or ($base==0) or ($usuario==0) or ($senha==0)){
		$this->host = $BD_host1;
		$this->base = $BD_base1;
		$this->usuario = $BD_user1;
		$this->senha = $BD_pass1;
	}
	else{
		$this->host = $host;
		$this->base = $base;
		$this->usuario = $usuario;
		$this->senha = $senha;
	}

	$resultado = $this->connect();
	if (!$resultado)
		die("Não foi possivel conectar com o banco de dados");
  }*/



  //****************************** CONECTA NA BANCO
  /*function connect() {
	$this->socket = mysql_connect($this->host,$this->usuario,$this->senha);
	if (!$this->socket) {
	  $this->erro = mysql_error();
	  $this->status = false;
	  echo $this->erro;
	  return false;
	}
	else {
	  if (!mysql_select_db($this->base,$this->socket)) {
		$this->erro = mysql_error();
		$this->status = false;
		return false;
	  }
	  else {
		$this->erro = "";
		$this->status = true;
		return true;
	  }
	}
  }*/
  
  //****************************** QUERY
  /*function solicitar ($query_str) {
	$this->primeiro();
	$this->intquery = mysql_query($query_str,$this->socket);
	if (!$this->intquery) {
	  $this->erro = mysql_error();
	  $this->status = false;
	  return false;
	}
	else {
	  if ((substr($query_str,0,6)=="select") || (substr($query_str,0,6)=="SELECT")) {
	  $q = mysql_query($query_str,$this->socket);
		$this->resultado = mysql_fetch_array($this->intquery);
		$this->registros = mysql_num_rows($this->intquery);
		while($r = mysql_fetch_assoc($q)) // ... mas por que não um vetor com todos os resultados da busca? 
			$this->itens[] = $r;
	  }
	  $this->erro = "";
	  $this->status = true;
	  return true;
	}
  }*/

	/*
	@author Yuri Pelz Gossmann
	@date 2012-08-09 -> 2012-08-14
	INÍCIO
	*/
	/*function solicitar($query){
		$this->primeiro();
		$this->intquery=mysql_query($query,$this->socket);
		if(!$this->intquery){
			$this->erro=mysql_error();
			$this->status=FALSE;
			return FALSE;
		}
		else{
			if(strtolower(substr($query,0,6))==='select'){
				$this->registros=mysql_num_rows($this->intquery);
				$this->resultado=mysql_fetch_assoc($this->intquery);
				$this->itens=array();
				$this->itens[]=$this->resultado;
				if($this->resultado)
					while($itemAtual=mysql_fetch_assoc($this->intquery))
						$this->itens[]=$itemAtual;
			}
			$this->erro='';
			$this->status=TRUE;
			return TRUE;
		}
	}*/
	/*function solicitarSI($query){
		$this->primeiro();
		$this->intquery=mysql_query($query,$this->socket);
		if(!$this->intquery){
			$this->erro=mysql_error();
			$this->status=FALSE;
			return FALSE;
		}
		else{
			if(strtolower(substr($query,0,6))==='select'){
				$this->registros=mysql_num_rows($this->intquery);
				$this->resultado=mysql_fetch_assoc($this->intquery);
			}
			$this->erro='';
			$this->status=TRUE;
			return TRUE;
		}
	}*/
	/*
	FIM
	*/

  //****************************** MOVIMENTACAO
  /*function ir_para ($id) {
	if (!mysql_data_seek($this->intquery, $id)) {
	  $this->erro = mysql_error();
	  $this->status = false;
	  return false;
	}
	else {
	  $this->resultado = mysql_fetch_array($this->intquery);
	  $this->erro = "";
	  $this->index = $id;
	  $this->registro_atual = $id;
	  return true;
	}
  }*/

  /*function primeiro () {
	if ($this->index!=0) {
	  $this->ir_para(0);
	  $this->index=0;
	}
  }*/

  /*function anterior () {
	if ($this->index-1>=0) {
	  $this->ir_para($this->index-1);
	} else {
	 $this->resultado ="";
	}
  }*/
  /*function proximo () {
	if ($this->index+1<$this->registros) {
	  $this->ir_para($this->index+1);
	} else {
	 $this->resultado ="";
	}
  }*/
  /*function ultimo () {
	if ($this->index!=$this->registros) {
	  $this->ir_para($this->registros-1);
	  $this->index=$this->registros-1;
	}
  }*/

  //****************************** ID DO ULTIMO REGISTRO INSERIDO
  /*function ultimo_id () {
	return mysql_insert_id($this->socket);
  }*/

  /*function fechar () {
	//mysql_free_result($this->resultado);
	//mysql_close($this->socket);
  }*/

// POR FAVOR NÃO APAGAR OS MÉTODOS ABAIXO, FUNDAMENTAIS PARA ESCREVER E ATUALIZAR REGISTROS.
	/*function inserir($dados,$tabela) {
		$sql_campos = "(";
		$sql_valores = "(";
		foreach($dados as $nome_campo => $valor_campo) {
			$sql_campos .= $nome_campo . ",";
			$sql_valores .= "\"$valor_campo\",";
		}			
		$sql_campos{strlen($sql_campos)-1} = ")";
		$sql_valores{strlen($sql_valores)-1} = ")";
		$query = mysql_query("INSERT INTO $tabela $sql_campos VALUES $sql_valores");
		echo mysql_error();
		return $query;
	}*/



	/*function atualizar($id,$dados,$tabela) {
		$query = "";
		foreach($dados as $nome_campo => $valor_campo) {
			$query .= $nome_campo . " = " . "\"$valor_campo\",";
		}
		$query{strlen($query)-1} = " ";
		$query = "UPDATE $tabela SET " . $query . " WHERE Id = $id";
		$query = mysql_query($query);			
		echo mysql_error();
		return $query;
	}*/
//
}
}
?>
