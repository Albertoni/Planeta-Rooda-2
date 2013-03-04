<?php

class conexao {
  var $host;     	// qual o servidor
  var $base;      	// qual a base
  var $usuario;     // qual o username
  var $senha;     	// qual a senha
  var $socket;   	// socket da conexao com o banco
  var $erro;    	// mensagem de erro da query
  var $intquery; 	// int representando o resultado da query
  var $resultado;   // fetch_array de $intquery
  var $registros;   // qtde de linhas encontradas
  var $index;    	// indice do vetor $result
  var $status;   	// retorno true ou false da query
  var $registro_atual; // registro atual

  //****************************** CONSTRUTOR
  function conexao($host,$base,$usuario,$senha) {
    $this->host = $host;
    $this->base = $base;
    $this->usuario = $usuario;
    $this->senha = $senha;
    $this->connect();
  }
  
  //****************************** CONECTA NA BANCO
  function connect() {  
    $this->socket = mysql_connect($this->host,$this->usuario,$this->senha);
    if (!$this->socket) {
      $this->erro = mysql_error();
      $this->status = false;
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
  }
  
  //****************************** QUERY
  function solicitar ($query_str) {
    $this->primeiro();
    $this->intquery = mysql_query($query_str,$this->socket);
    if (!$this->intquery) {
      $this->erro = mysql_error();
      $this->status = false;
      return false;
    }
    else {
      if ((substr($query_str,0,6)=="select") || (substr($query_str,0,6)=="SELECT")) {
        $this->resultado = mysql_fetch_array($this->intquery);
        $this->registros = mysql_num_rows($this->intquery);
      }
      $this->erro = "";
      $this->status = true;
      return true;
    }
  }

  //****************************** MOVIMENTACAO
  function ir_para ($id) {
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
  }
  function primeiro () {
    if ($this->index!=0) {
      $this->ir_para(0);
      $this->index=0;
    }
  }
  function anterior () {
    if ($this->index-1>=0) {
      $this->ir_para($this->index-1);
    } else {
	 $this->resultado ="";
	}
  }
  function proximo () {
    if ($this->index+1<$this->registros) {
      $this->ir_para($this->index+1);
    } else {
	 $this->resultado ="";
	}
  }
  function ultimo () {
    if ($this->index!=$this->registros) {
      $this->ir_para($this->registros-1);
      $this->index=$this->registros-1;
    }
  }

  //****************************** ID DO ULTIMO REGISTRO INSERIDO
  function ultimo_id () {
    return mysql_insert_id($this->socket);
  }
	
  function fechar () {
	mysql_free_result($this->resultado);
	mysql_close($this->socket);
  }
}
?>