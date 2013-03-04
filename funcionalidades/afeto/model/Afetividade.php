<?php

require_once("subjetividade.class.php");
require_once("fatoresMotivacionais.class.php");
require_once("fatoresPersonalidade.class.php");
require_once("estadoAnimo.class.php");

class afetividade{
//dados
	/*
	* Objeto da classe subjetividade.
	*/
	private $subjetividade;

	/*
	* Objeto da classe fatoresMotivacionais.
	*/
	private $fatoresMotivacionais;

	/*
	* Objeto da classe fatoresPersonalidade.
	*/
	private $fatoresPersonalidade;

	/*
	* Objeto da classe estadoAnimo.
	*/
	private $estadoAnimo;

//métodos
	/*
	* Procura os dados de subjetividade do usuário dado no banco de dados no período dado.
	* @param data_inicio_param Uma data no formato dd-mm-aaaa. String.
	* @param data_fim_param Uma data no formato dd-mm-aaaa. String.
	* @param usuario_param Um objeto da classe usuário
	*/
	public function afetividade($data_inicio_param, $data_fim_param, $usuario_param){
		$this->fatoresPersonalidade = null;
		
		$this->subjetividade = new subjetividade($data_inicio_param, $data_fim_param, $usuario_param);
		$this->fatoresMotivacionais = new fatoresMotivacionais($data_inicio_param, $data_fim_param, $usuario_param);
		$this->estadoAnimo = new estadoAnimo($data_inicio_param, $data_fim_param, $this->subjetividade, $this->fatoresMotivacionais);
	}
	
	public function getSubjetividade(){ return $this->subjetividade; }
	public function getFatoresMotivacionais(){ return $this->fatoresMotivacionais; }
	public function getFatoresPersonalidade(){ return $this->fatoresPersonalidade; }
	public function getEstadoAnimo(){ return $this->estadoAnimo; }
	
	
}






?>
	