<?php
	
require_once("subjetividade.class.php");
require_once("fatoresMotivacionais.class.php");

class estadoAnimo{
//dados
	/*
	* Valores de um estado de ânimo.
	*/
	const ANIMADO = '1';
		// Famílias afetivas do estado ANIMADO.
		const ANIMADO_SURPRESA   = '11';
		const ANIMADO_INTERESSE  = '12';
		const ANIMADO_ESPERANCA  = '13';
		const ANIMADO_SERENIDADE = '14';
	const SATISFEITO = '2';
		// Famílias afetivas do estado SATISFEITO.
		const SATISFEITO_SATISFACAO = '21';
		const SATISFEITO_ALEGRIA    = '22';
		const SATISFEITO_ENTUSIASMO = '23';
		const SATISFEITO_ORGULHO    = '24';
	const INSATISFEITO = '3';
		// Famílias afetivas do estado INSATISFEITO.
		const INSATISFEITO_IRRITACAO = '31';
		const INSATISFEITO_DESPREZO  = '32';
		const INSATISFEITO_AVERSAO   = '33';
		const INSATISFEITO_INVEJA    = '34';
	const DESANIMADO = '4';
		// Famílias afetivas do estado DESANIMADO.
		const DESANIMADO_CULPA    = '41';
		const DESANIMADO_VERGONHA = '42';
		const DESANIMADO_MEDO     = '43';
		const DESANIMADO_TRISTEZA = '44';
	
	/*
	* Estado de ânimo (um valor definido nesta classe acima).
	*/
	private $animo;
	
	/*
	* Intensidade do estado de ânimo (um número de 1 a 4).
	*/
	private $intensidadeAnimo;
		
//métodos
	/*
	* @param data_inicio_param Uma data no formato dd-mm-aaaa. String.
	* @param data_fim_param Uma data no formato dd-mm-aaaa. String.
	* @param subjetividade_param Objeto da classe subjetividade.
	* @param fatoresMotivacionais_param Objeto da classe fatoresMotivacionais.
	*/
	public function estadoAnimo($data_inicio_param, $data_fim_param, $subjetividade_param, $fatoresMotivacionais_param){
		$this->setAnimoComIntensidade(ANIMADO_SURPRESA, 1);
	}
	
	public function getAnimo(){ return $this->animo; }
	public function getIntensidadeAnimo(){ return $this->intensidadeAnimo; }
	
	/*
	* @param animo_param Estado de ânimo (um valor definido nesta classe acima).
	* @param intensidadeAnimo_param Intensidade do estado de ânimo (um valor de 1 a 4).
	*/
	private function setAnimoComIntensidade($animo_param, $intensidadeAnimo_param){
		$this->animo = $animo_param;
		$this->intensidadeAnimo = $intensidadeAnimo_param;
	}
	
}




?>