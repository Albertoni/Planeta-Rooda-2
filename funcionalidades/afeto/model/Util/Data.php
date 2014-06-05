<?php
	
class Data{
//dados
	/**
	* Constantes que representam intervalos de tempo.
	*/
	const SEMANA 		= "1";
	const MES 			= "2";
	const BIMESTRE 		= "4";
	const TRIMESTRE 	= "5";
	const SEMESTRE 		= "6";
	const ANO 			= "7";
	
	/**
	* Constantes que representam os meses existentes.
	*/
	const JANEIRO		= 1;
	const FEVEREIRO		= 2;
	const MARCO			= 3;
	const ABRIL			= 4;
	const MAIO			= 5;
	const JUNHO			= 6;
	const JULHO			= 7;
	const AGOSTO		= 8;
	const SETEMBRO		= 9;
	const OUTUBRO		= 10;
	const NOVEMBRO		= 11;
	const DEZEMBRO		= 12;
	
	/**
	* Elementos de tempo da data, todos inteiros.
	*/
	private $segundos;
	private $minutos;
	private $horas;
	private $dias;
	private $meses;
	private $anos;
	
//métodos
	/**
	* @param String		dataString	Uma data no formato 'aaaa-mm-dd hh:mm:ss'.
	*/
	public function __construct($dataString="0000-00-00 00:00:00"){
		$emArray = explode(" ",$dataString);
		$anoMesDia = $emArray[0];
		$anoMesDia = explode("-",$anoMesDia);
		$horaMinutoSegundo = $emArray[1];
		$horaMinutoSegundo = explode(":",$horaMinutoSegundo);
		$this->segundos	 = $horaMinutoSegundo[2];
		$this->minutos	 = $horaMinutoSegundo[1];
		$this->horas	 = $horaMinutoSegundo[0];
		$this->dias		 = $anoMesDia[2];
		$this->meses	 = $anoMesDia[1];
		$this->anos		 = $anoMesDia[0];
	}
	
	/**
	* Clona a data parâmetro nesta data, resultando em dois objetos diferentes com o mesmo conteúdo.
	* @param Data	$dataClonada	A data que será clonada.
	*/
	public function clonar($dataClonada){
		$this->__construct($dataClonada->paraString());
	}
	
	/*
	* @param Data dataInicio		Data à partir da qual faz-se a busca.
	* @param Data dataFim			Data até a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return int	O número de intervalos de tempo (divididos segundo o critério de divisão) entre dataInicio e dataFim.
	*/
	public static function getNumeroIntervalosTempoEntre($dataInicio, $dataFim, $divisaoTempo){
		$numeroIntervalos = 0;
		$dataAtual = $dataInicio;
		while(!$dataAtual->ehDepoisDe($dataFim)){
			$numeroIntervalos += 1;
			$dataAtual = $dataAtual->getDataDepois($divisaoTempo, 1);
		}
		return $numeroIntervalos;
	}
	
	/*
	* @param String divisaoTempo			Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @param int  quantidadeTempoPassado	Quantidade (na divisão de tempo fornecida) decorrida desde esta data.
	* @return Data							Uma data uma divisão de tempo (semana, mês, ano,...) depois desta data.
	*/
	private function getDataDepois($divisaoTempo, $quantidadeTempoPassado=1){
		$dataDepois = new Data($this->paraString());
		switch($divisaoTempo){
			case self::SEMANA:		$dataDepois->dias += 7*$quantidadeTempoPassado;
				break;
			case self::MES:			$dataDepois->meses += 1*$quantidadeTempoPassado;
				break;
			case self::BIMESTRE:	$dataDepois->meses += 2*$quantidadeTempoPassado;
				break;
			case self::TRIMESTRE:	$dataDepois->meses += 3*$quantidadeTempoPassado;
				break;
			case self::SEMESTRE:	$dataDepois->meses += 6*$quantidadeTempoPassado;
				break;
			case self::ANO:			$dataDepois->anos += 1*$quantidadeTempoPassado;
				break;
		}
		$dataDepois->corrigir();
		return $dataDepois;
	}
	
	/**
	* Corrige inconsistências nesta data, como mês 13, fevereiro com 30 dias, etc.
	*/
	private function corrigir(){
		if(12 < $this->meses){
			$this->anos += ($this->meses-$this->meses%12)/12;
			$this->meses = $this->meses%12;
		}
		$mesDe31 = 	$this->meses == self::JANEIRO 
				|| 	$this->meses == self::MARCO 
				|| 	$this->meses == self::MAIO 
				|| 	$this->meses == self::JULHO
				|| 	$this->meses == self::AGOSTO 
				|| 	$this->meses == self::OUTUBRO 
				|| 	$this->meses == self::DEZEMBRO;
		$mesDe30 = 	$this->meses == self::ABRIL 
				|| 	$this->meses == self::JUNHO 
				|| 	$this->meses == self::SETEMBRO 
				|| 	$this->meses == self::NOVEMBRO;
		if($mesDe31 and 31 < $this->dias)				{ $this->meses+=1; 		$this->dias = $this->dias - 31; 			$this->corrigir();
		} else if($mesDe30 and 30 < $this->dias)		{ $this->meses+=1; 		$this->dias = $this->dias - 30; 			$this->corrigir();
		} else if($this->meses == self::FEVEREIRO){
			$anoBissexto = ($this->anos%4==0);
			if($anoBissexto and 29 < $this->dias)		{ $this->meses+=1; 		$this->dias = $this->dias - 29; 			$this->corrigir();
			} else if(28 < $this->dias)					{ $this->meses+=1; 		$this->dias = $this->dias - 28; 			$this->corrigir(); }
		}
		if(24 < $this->horas)							{ $this->dias+=1; 		$this->horas = $this->horas - 24; 			$this->corrigir(); }
		if(60 < $this->minutos)							{ $this->horas+=1; 		$this->minutos = $this->minutos - 60; 		$this->corrigir(); }
		if(60 < $this->segundos)						{ $this->minutos+=1; 	$this->segundos = $this->segundos - 60; 	$this->corrigir(); }
	}
	
	/*
	* @param Data dataFim				Data até a qual faz-se a busca.
	* @return Booleano					Indica se esta data é depois de dataFim.
	*/
	private function ehDepoisDe($dataFim){
		if($this->anos 				< $dataFim->anos)		{	return false; }
		else if($dataFim->anos 		< $this->anos)			{	return true;  }
		if($this->meses 			< $dataFim->meses)		{	return false; }
		else if($dataFim->meses 	< $this->meses)			{	return true;  }
		if($this->dias 				< $dataFim->dias)		{	return false; }
		else if($dataFim->dias 		< $this->dias)			{	return true;  }
		if($this->horas 			< $dataFim->horas)		{	return false; }
		else if($dataFim->horas 	< $this->horas)			{	return true;  }
		if($this->minutos 			< $dataFim->minutos)	{	return false; }
		else if($dataFim->minutos 	< $this->anos)			{	return true;  }
		if($this->segundos 			< $dataFim->segundos)	{	return false; }
		else if($dataFim->segundos 	< $this->segundos)		{	return true;  }
		return true;
	}
	
	/*
	* @param String divisaoTempo		Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return String 					Uma função do banco de dados que retorne a semana, o mês, o ano, etc. de uma data.
	* OBS.: É, eu sei que este método não deveria estar aqui e sim na classe BD.
	*/
	public static function getFuncaoBancoDeDadosPeriodo($divisaoTempo){
		switch($divisaoTempo){
			case Data::SEMANA: return "WEEK";
				break;
			case Data::MES: return "MONTH";
				break;
			default: return "WEEK";
		}
	}
	
	/**
	* @return String	Esta data em forma de string.
	*/
	public function paraString(){
		return 	$this->anos		."-".
				$this->meses	."-".
				$this->dias		." ".
				$this->horas	.":".
				$this->minutos	.":".
				$this->segundos	;
	}
	
}





?>