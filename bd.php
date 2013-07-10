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

	function __construct($host=0,$base=0,$usuario=0,$senha=0){
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
		if($this->index!=((int) ($this->registros)-1)){
			$this->ir_para((int) ($this->registros) - 1);
		}
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
	
	function sanitizaString($string){
		return $this->socketMysqli->real_escape_string($string);
	}
	
	function ultimoId(){
		return $this->socketMysqli->insert_id;
	}
	function ultimo_id(){
		return $this->socketMysqli->insert_id;
	}
}
}
?>
