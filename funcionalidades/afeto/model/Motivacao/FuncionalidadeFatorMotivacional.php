<?php

require_once(dirname(__FILE__)."/../Util/Data.php");

class FuncionalidadeFatorMotivacional{
//dados
	//Id, no banco de dados, da turma do usu�rio na qual ser� feita a avalia��o.
	/*int*/ 		protected $idTurma;
	//Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	/*Usuario*/		protected $usuario;
	//Data � partir da qual faz-se a busca.
	/*Data*/		protected $dataInicio;
	//Data at� a qual faz-se a busca.
	/*Data*/		protected $dataFim;
	//Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	/*String*/		protected $divisaoTempo;
	
	/*
	* Indica uma vari�vel que n�o se aplica a determinada funcionalidade.
	*/
	const VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE = -99999;
	
	/*
	* Vari�veis.
	*/
	protected $NA;
	protected $FP;
	protected $MP;
	protected $PA;
	protected $TO;
	protected $NV;
	protected $TP;
	
	/*
	* Valores que podem ser assumidos pelas vari�veis nesta funcionalidade.
	*/
	public static $valoresFatores = array(	"confianca"		=>array("NA"=>array(0,0,0),
																	"FP"=>array(0,0,0,0,0),
																	"MP"=>array(0,0,0,0,0),
																	"PA"=>array(0,0,0,0),
																	"TO"=>array(0,0),
																	"NV"=>array(0,0),
																	"TP"=>array(0,0)),
											"esforco"		=>array("NA"=>array(0,0,0),
																	"FP"=>array(0,0,0,0,0),
																	"MP"=>array(0,0,0,0,0),
																	"PA"=>array(0,0,0,0),
																	"TO"=>array(0,0),
																	"NV"=>array(0,0),
																	"TP"=>array(0,0)),
											"independencia"	=>array("NA"=>array(0,0,0),
																	"FP"=>array(0,0,0,0,0),
																	"MP"=>array(0,0,0,0,0),
																	"PA"=>array(0,0,0,0),
																	"TO"=>array(0,0),
																	"NV"=>array(0,0),
																	"TP"=>array(0,0)));
	
//m�todos
	/*
	* Construtor.
	* @param int idTurma			Id, no banco de dados, da turma do usu�rio na qual ser� feita a avalia��o.
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	*/
	public function __construct($idTurma, $usuario, $dataInicio, $dataFim, $divisaoTempo){
		$this->idTurma		= $idTurma;
		$this->usuario		= $usuario;
		$this->dataInicio	= $dataInicio;
		$this->dataFim		= $dataFim;
		$this->divisaoTempo	= $divisaoTempo;
		$this->NA = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$this->FP = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$this->MP = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$this->PA = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$this->TO = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$this->NV = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$this->TP = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
	}
	
	/*	 NA - {inferior � m�dia, igual ou superior � m�dia}
	*	 FP - {>=75%, >=50% e <75%, >=25% e <50%, >0% e <25%, 0%}
	* 	 MP - Assume os valores {responde ao formador e responde ao colega, n�o responde ao colega e n�o responde ao formador, 
	*	 						responde ao formador e n�o responde ao colega, n�o responde ao formador e responde ao colega, n�o participa do f�rum}
	* 	 PA - Assume os valores {a todos, ao formador, aos colegas, n�o pede}
	* 	 TO - Assume os valores {cria sua pr�pria mensagem, cria um novo t�pico}
	* 	 NV - Assume os valores {inferior ao n�mero de acessos, igual ou superior ao n�mero de acessos}
	* 	 TP - Assume os valores {igual ou inferior � m�dia da turma, superior � m�dia da turma}*/
	public function getNA(){ return $this->NA; }
	public function getFP(){ return $this->FP; }
	public function getMP(){ return $this->MP; }
	public function getPA(){ return $this->PA; }
	public function getTO(){ return $this->TO; }
	public function getNV(){ return $this->NV; }
	public function getTP(){ return $this->TP; }
	
	/**
	* @return M�ximo valor da soma dos valores assumidos pelas vari�veis aplicadas em um fator motivacional.
	*/
	public static function getMaximoConfianca(){
		$soma = 0;
		if(array_key_exists("NA",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["NA"]); }
		if(array_key_exists("FP",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["FP"]); }
		if(array_key_exists("MP",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["MP"]); }
		if(array_key_exists("PA",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["PA"]); }
		if(array_key_exists("TO",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["TO"]); }
		if(array_key_exists("NV",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["NV"]); }
		if(array_key_exists("TP",$this->valoresFatores["confianca"])){ $soma += max($this->valoresFatores["confianca"]["TP"]); }
		return $soma;
	}
	public static function getMaximoEsforco(){
		$soma = 0;
		if(array_key_exists("NA",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["NA"]); }
		if(array_key_exists("FP",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["FP"]); }
		if(array_key_exists("MP",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["MP"]); }
		if(array_key_exists("PA",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["PA"]); }
		if(array_key_exists("TO",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["TO"]); }
		if(array_key_exists("NV",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["NV"]); }
		if(array_key_exists("TP",$this->valoresFatores["esforco"])){ $soma += max($this->valoresFatores["esforco"]["TP"]); }
		return $soma;
	}
	public static function getMaximoIndependencia(){
		$soma = 0;
		if(array_key_exists("NA",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["NA"]); }
		if(array_key_exists("FP",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["FP"]); }
		if(array_key_exists("MP",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["MP"]); }
		if(array_key_exists("PA",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["PA"]); }
		if(array_key_exists("TO",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["TO"]); }
		if(array_key_exists("NV",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["NV"]); }
		if(array_key_exists("TP",$this->valoresFatores["independencia"])){ $soma += max($this->valoresFatores["independencia"]["TP"]); }
		return $soma;
	}

	/**
	* @return M�nimo valor da soma dos valores assumidos pelas vari�veis aplicadas em um fator motivacional.
	*/
	public static function getMinimoConfianca(){
		$soma = 0;
		if(array_key_exists("NA",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["NA"]); }
		if(array_key_exists("FP",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["FP"]); }
		if(array_key_exists("MP",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["MP"]); }
		if(array_key_exists("PA",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["PA"]); }
		if(array_key_exists("TO",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["TO"]); }
		if(array_key_exists("NV",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["NV"]); }
		if(array_key_exists("TP",$this->valoresFatores["confianca"])){ $soma += min($this->valoresFatores["confianca"]["TP"]); }
		return $soma;
	}
	public static function getMinimoEsforco(){
		$soma = 0;
		if(array_key_exists("NA",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["NA"]); }
		if(array_key_exists("FP",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["FP"]); }
		if(array_key_exists("MP",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["MP"]); }
		if(array_key_exists("PA",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["PA"]); }
		if(array_key_exists("TO",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["TO"]); }
		if(array_key_exists("NV",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["NV"]); }
		if(array_key_exists("TP",$this->valoresFatores["esforco"])){ $soma += min($this->valoresFatores["esforco"]["TP"]); }
		return $soma;
	}
	public static function getMinimoIndependencia(){
		$soma = 0;
		if(array_key_exists("NA",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["NA"]); }
		if(array_key_exists("FP",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["FP"]); }
		if(array_key_exists("MP",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["MP"]); }
		if(array_key_exists("PA",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["PA"]); }
		if(array_key_exists("TO",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["TO"]); }
		if(array_key_exists("NV",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["NV"]); }
		if(array_key_exists("TP",$this->valoresFatores["independencia"])){ $soma += min($this->valoresFatores["independencia"]["TP"]); }
		return $soma;
	}
	
	
	/**
	* @return Array		 			Objetos que cont�m os fatores motivacionais em cada per�odo de tempo (ex.: semana, m�s).
	*								Cada objeto representa um per�odo. Ex.: array(semana1, semana2, semana3).
	*								O array � organizado por per�odos de tempo, ex:
	*								array(
	*									semana1 => array(
	*														"confianca"=>array(
	*																			"NA"=>1, "FP"=>2,...
	*																			),
	*														"esforco"=>array(
	*																			"NA"=>2, "FP"=>2,...
	*																			),
	*														"independencia"=>array(
	*																			"NA"=>1, "FP"=>-5,...
	*																			)
	*													),
	*									semana2 => ...
	*								)
	*								Somente estar�o presente no array vari�veis de intera��o (NA, FP,...) que se relacionem com esta funcionalidade.
	*/
	public function getFatoresNoPeriodo(){
		$fatoresPeriodo = array();
		
		$NAsPorPeriodo = $this->produzNA();
		$FPsPorPeriodo = $this->produzFP();
		$MPsPorPeriodo = $this->produzMP();
		$PAsPorPeriodo = $this->produzPA();
		$TOsPorPeriodo = $this->produzTO();
		$NVsPorPeriodo = $this->produzNV();
		$TPsPorPeriodo = $this->produzTP();
		
		$haNA = $NAsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$haFP = $FPsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$haMP = $MPsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$haPA = $PAsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$haTO = $TOsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$haNV = $NVsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		$haTP = $TPsPorPeriodo != FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
		
		$numeroIntervalosTempo = Data::getNumeroIntervalosTempoEntre($this->dataInicio, $this->dataFim, $this->divisaoTempo);

		for($i=0; $i<$numeroIntervalosTempo; $i++){
			$fatoresPeriodo[$i]["confianca"] = array();
			$fatoresPeriodo[$i]["esforco"] = array();
			$fatoresPeriodo[$i]["independencia"] = array();
			
			$fatoresPeriodo[$i]["confianca"]["NA"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["confianca"]["FP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["confianca"]["MP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["confianca"]["PA"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["confianca"]["TO"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["confianca"]["NV"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["confianca"]["TP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			
			$fatoresPeriodo[$i]["esforco"]["NA"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["esforco"]["FP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["esforco"]["MP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["esforco"]["PA"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["esforco"]["TO"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["esforco"]["NV"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["esforco"]["TP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			
			$fatoresPeriodo[$i]["independencia"]["NA"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["independencia"]["FP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["independencia"]["MP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["independencia"]["PA"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["independencia"]["TO"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["independencia"]["NV"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			$fatoresPeriodo[$i]["independencia"]["TP"] = FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE;
			
			if($haNA) $fatoresPeriodo[$i]["confianca"]["NA"] = self::getChaveSeExistir($i, "confianca", $NAsPorPeriodo);
			if($haFP) $fatoresPeriodo[$i]["confianca"]["FP"] = self::getChaveSeExistir($i, "confianca", $FPsPorPeriodo);
			if($haMP) $fatoresPeriodo[$i]["confianca"]["MP"] = self::getChaveSeExistir($i, "confianca", $MPsPorPeriodo);
			if($haPA) $fatoresPeriodo[$i]["confianca"]["PA"] = self::getChaveSeExistir($i, "confianca", $PAsPorPeriodo);
			if($haTO) $fatoresPeriodo[$i]["confianca"]["TO"] = self::getChaveSeExistir($i, "confianca", $TOsPorPeriodo);
			if($haNV) $fatoresPeriodo[$i]["confianca"]["NV"] = self::getChaveSeExistir($i, "confianca", $NVsPorPeriodo);
			if($haTP) $fatoresPeriodo[$i]["confianca"]["TP"] = self::getChaveSeExistir($i, "confianca", $TPsPorPeriodo);
			
			if($haNA) $fatoresPeriodo[$i]["esforco"]["NA"] = self::getChaveSeExistir($i, "esforco", $NAsPorPeriodo);
			if($haFP) $fatoresPeriodo[$i]["esforco"]["FP"] = self::getChaveSeExistir($i, "esforco", $FPsPorPeriodo);
			if($haMP) $fatoresPeriodo[$i]["esforco"]["MP"] = self::getChaveSeExistir($i, "esforco", $MPsPorPeriodo);
			if($haPA) $fatoresPeriodo[$i]["esforco"]["PA"] = self::getChaveSeExistir($i, "esforco", $PAsPorPeriodo);
			if($haTO) $fatoresPeriodo[$i]["esforco"]["TO"] = self::getChaveSeExistir($i, "esforco", $TOsPorPeriodo);
			if($haNV) $fatoresPeriodo[$i]["esforco"]["NV"] = self::getChaveSeExistir($i, "esforco", $NVsPorPeriodo);
			if($haTP) $fatoresPeriodo[$i]["esforco"]["TP"] = self::getChaveSeExistir($i, "esforco", $TPsPorPeriodo);
			
			if($haNA) $fatoresPeriodo[$i]["independencia"]["NA"] = self::getChaveSeExistir($i, "independencia", $NAsPorPeriodo);
			if($haFP) $fatoresPeriodo[$i]["independencia"]["FP"] = self::getChaveSeExistir($i, "independencia", $FPsPorPeriodo);
			if($haMP) $fatoresPeriodo[$i]["independencia"]["MP"] = self::getChaveSeExistir($i, "independencia", $MPsPorPeriodo);
			if($haPA) $fatoresPeriodo[$i]["independencia"]["PA"] = self::getChaveSeExistir($i, "independencia", $PAsPorPeriodo);
			if($haTO) $fatoresPeriodo[$i]["independencia"]["TO"] = self::getChaveSeExistir($i, "independencia", $TOsPorPeriodo);
			if($haNV) $fatoresPeriodo[$i]["independencia"]["NV"] = self::getChaveSeExistir($i, "independencia", $NVsPorPeriodo);
			if($haTP) $fatoresPeriodo[$i]["independencia"]["TP"] = self::getChaveSeExistir($i, "independencia", $TPsPorPeriodo);
		}
		return $fatoresPeriodo;
	}
	
	/**
	* Se existirem, no array, as duas chaves dadas, na ordem que s�o dadas, retorna o elemento na posi��o.
	* Caso contr�rio, retorna array().
	* @param String		$primeiraChave	Chave que ser� testada na forma arrayDado[primeiraChave].
	* @param String		$segundaChave	Chave que ser� testada na forma arrayDado[segundaChave].
	* @param array		$arrayDado		Array no qual a exist�ncia de um elemento do tipo arrayDado[primeiraChave][segundaChave] ser� testada.
	* @return 	se a chave n�o existir, array()
	*			sen�o, a pr�pria chave
	*/
	private static function getChaveSeExistir($primeiraChave, $segundaChave, $arrayDado){
		return (array_key_exists($primeiraChave, $arrayDado)? 
				(array_key_exists($segundaChave, $arrayDado[$primeiraChave])?
					$arrayDado[$primeiraChave][$segundaChave]
					: array() ) 
				: array());
	}
	
	/***********************************************************
	*	Dados de intera��o do usu�rio dentro de v�rias unidades de tempo
	*************************************************************/
	/**
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conex�o que cont�m os dados das tabelas n�o processados.
	*/
	protected function retornaConexaoAcessosFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ return new conexao(); }
	
	/**
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conex�o que cont�m os dados das tabelas n�o processados.
	*/
	protected function retornaConexaoFrequenciaFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ return new conexao(); }
	
	/**
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conex�o que cont�m os dados das tabelas n�o processados.
	*/
	protected function retornaConexaoModoParticipacaoFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ return new conexao(); }
	
	/**
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conex�o que cont�m os dados das tabelas n�o processados.
	*/
	protected function retornaConexaoGeracaoFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ return new conexao(); }
	
	/***********************************************************
	*	Valores dos fatores dentro de uma unidade de tempo
	*************************************************************/
	/**
	* @param int	$numeroAcessosUsuario 	 N�mero de acessos do usu�rio a esta funcionalidade de uma turma em um per�odo de tempo.
	* @param int	$mediaAcessosTurma		 N�mero m�dio de acessos de usu�rios a esta funcionalidade de uma turma em um per�odo de tempo.
	* @param String	$tipoVariavel			 Indica se � 'confianca', 'esforco' ou 'independencia'. Default � 'confianca'.
	* @return	int	Valor representativo da rela��o entre $numeroAcessosUsuario e $mediaAcessosTurma. O valor do fator NA.
	* OBS.: Um par�metro com valor NULL � interpretado como inexistente.
	*/
	protected function retornaValorNA($numeroAcessosUsuario=null, $mediaAcessosTurma=null, $tipoVariavel='confianca'){ 
		$classeAtual = get_called_class();
		$valor = 0;
		$periodoContemRegistrosDaTurma = ($numeroAcessosUsuario != null && $mediaAcessosTurma!= null);
		if($periodoContemRegistrosDaTurma){
			if(floatval($numeroAcessosUsuario) < floatval($mediaAcessosTurma)){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["NA"][0]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["NA"][0]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["NA"][0]; break; }
			} else if(floatval($numeroAcessosUsuario) == floatval($mediaAcessosTurma)){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["NA"][1]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["NA"][1]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["NA"][1]; break; }
			} else {
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["NA"][2]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["NA"][2]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["NA"][2]; break; }
			}
		} else {
			switch($tipoVariavel){
				case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["NA"][1]; break;
				case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["NA"][1]; break;
				case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["NA"][1]; break; }
		}
		return $valor;
	}
	
	/**
	* @param int	$frequenciaUsuario	 Freq��ncia de acessos de um usu�rio a uma funcionalidade de uma turma em um per�odo de tempo,
	*									 comparada com as freq��ncias de outros usu�rios da mesma turma.
	* @param String	$tipoVariavel			 Indica se � 'confianca', 'esforco' ou 'independencia'. Default � 'confianca'.
	* @return int	Valor representativo da rela��o entre $frequenciaUsuario e as frq��ncias dos outros usu�rio da turma. O valor do fator FP.
	* OBS.: Um par�metro com valor NULL � interpretado como inexistente.
	*/
	protected function retornaValorFP($frequenciaUsuario=null, $tipoVariavel='confianca'){ 
		$classeAtual = get_called_class();
		$valor = 0;
		$periodoContemRegistrosDaTurma = ($frequenciaUsuario != null);
		if($periodoContemRegistrosDaTurma){
			if(0 == floatval($frequenciaUsuario)){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["FP"][0]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["FP"][0]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["FP"][0]; break; }
			} else if(floatval($frequenciaUsuario) < 0.25){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["FP"][1]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["FP"][1]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["FP"][1]; break; }
			} else if(floatval($frequenciaUsuario) < 0.5){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["FP"][2]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["FP"][2]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["FP"][2]; break; }
			} else if(floatval($frequenciaUsuario) < 0.75){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["FP"][3]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["FP"][3]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["FP"][3]; break; }
			} else {
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["FP"][4]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["FP"][4]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["FP"][4]; break; }
			}
		} else {
			switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["FP"][2]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["FP"][2]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["FP"][2]; break; }
		}
		return $valor;
	}
	
	/**
	* @param Boolean	$respondeColega		 Indica se o usu�rio responde a algum colega.
	* @param Boolean	$respondeFormador	 Indica se o usu�rio responde a algum formador.
	* @param String		$tipoVariavel		 Indica se � 'confianca', 'esforco' ou 'independencia'. Default � 'confianca'.
	* @param String		$participa			 Disting�e o caso em que o usu�rio n�o responde, mas participa. Utilizada somente quando 
	*										 $respondeColega e $respondeFormador s�o falsos.
	* @return int	Valor representativo da intera��o do usu�rio com a ferramenta. O valor do fator MP.
	* OBS.: Um par�metro com valor NULL � interpretado como inexistente.
	*/
	protected function retornaValorMP($respondeColega=null, $respondeFormador=null, $tipoVariavel='confianca', $participa=true){ 
		$classeAtual = get_called_class();
		$valor = 0;
		$periodoContemRegistrosDaTurma = ($respondeColega != null && $respondeFormador != null);
		if($periodoContemRegistrosDaTurma){
			if($respondeColega && $respondeFormador){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["MP"][0]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["MP"][0]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["MP"][0]; break; }
			} else if($respondeColega && !$respondeFormador){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["MP"][1]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["MP"][1]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["MP"][1]; break; }
			} else if(!$respondeColega && $respondeFormador){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["MP"][2]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["MP"][2]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["MP"][2]; break; }
			} else if(!$respondeColega && !$respondeFormador && $participa){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["MP"][3]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["MP"][3]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["MP"][3]; break; }
			} else {
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["MP"][4]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["MP"][4]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["MP"][4]; break; }
			}
		} else {
			switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["MP"][4]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["MP"][4]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["MP"][4]; break; }
		}
		return $valor;
	}
	
	/**
	* @param Boolean	$ehGerador	 Indica se o usu�rio gera algo na funcionalidade.
	* @param String		$tipoVariavel		 Indica se � 'confianca', 'esforco' ou 'independencia'. Default � 'confianca'.
	* @return int	Valor representativo da intera��o do usu�rio com a ferramenta. O valor do fator TO.
	* OBS.: Um par�metro com valor NULL � interpretado como inexistente.
	*/
	protected function retornaValorTO($ehGerador=null, $tipoVariavel='confianca', $participa=true){ 
		$classeAtual = get_called_class();
		$valor = 0;
		$periodoContemRegistrosDaTurma = ($ehGerador != null);
		if($periodoContemRegistrosDaTurma){
			if($ehGerador){
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["TO"][1]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["TO"][1]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["TO"][1]; break; }
			} else {
				switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["TO"][0]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["TO"][0]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["TO"][0]; break; }
			}
		} else {
			switch($tipoVariavel){
					case "confianca": $valor = $classeAtual::$valoresFatores["confianca"]["TO"][0]; break;
					case "esforco": $valor = $classeAtual::$valoresFatores["esforco"]["TO"][0]; break;
					case "independencia": $valor = $classeAtual::$valoresFatores["independencia"]["TO"][0]; break; }
		}
		return $valor;
	}
	
	/***********************************************************
	*	Fatores dentro de v�rias unidades de tempo
	*************************************************************/
	/**
	* @param int idTurma			Id, no banco de dados, da turma do usu�rio na qual ser� feita a avalia��o.
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Array					Algo do tipo array(
	*													0 => "confianca" => 1
	*													0 => "independencia" => 0
	*													0 => "esforco" => -1
	*													1 => "confianca" => 1
	*													1 => "independencia" => 0
	*													1 => "esforco" => -1
	*													[...]
	*												)
	*								Caso perten�a a uma funcionalidade � qual a vari�vel n�o se aplica, retornar� FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE.
	*/
	protected function produzNA(){ 
		$variaveisNoPeriodo = array();
		global $nivelAluno;
		
		$conexaoComResultado = $this->retornaConexaoAcessosFuncionalidade($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		
		$ordemInicial = $conexaoComResultado->resultado['ordem_inicial'];
		$numeroIntervalosTempo = Data::getNumeroIntervalosTempoEntre($this->dataInicio, $this->dataFim, $this->divisaoTempo);
		for($i=0; $i<$numeroIntervalosTempo; $i++){
			$ordemAtual = (isset($conexaoComResultado->resultado['ordem'])? $conexaoComResultado->resultado['ordem'] : "" );
			$periodoContemRegistrosDaTurma = ($ordemInicial+$i == $ordemAtual);
			$usuarioPeriodo = null;
			$mediaTurmaPeriodo = null;
			if($periodoContemRegistrosDaTurma){
				$usuarioPeriodo = $conexaoComResultado->resultado['acessos'];
				$mediaTurmaPeriodo = $conexaoComResultado->resultado['mediaNoPeriodo'];
			}
			$variaveisNoPeriodo[$i]["confianca"]		 = $this->retornaValorNA($usuarioPeriodo, $mediaTurmaPeriodo, "confianca");
			$variaveisNoPeriodo[$i]["esforco"]			 = $this->retornaValorNA($usuarioPeriodo, $mediaTurmaPeriodo, "esforco");
			$variaveisNoPeriodo[$i]["independencia"]	 = $this->retornaValorNA($usuarioPeriodo, $mediaTurmaPeriodo, "independencia");
			
			$conexaoComResultado->proximo();
		}
		
		return $variaveisNoPeriodo;
	}
	protected function produzFP(){ 
		$variaveisNoPeriodo = array();
		global $nivelAluno;
		
		$conexaoComResultado = $this->retornaConexaoFrequenciaFuncionalidade($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		
		$ordemInicial = $conexaoComResultado->resultado['ordem_inicial'];
		$numeroIntervalosTempo = Data::getNumeroIntervalosTempoEntre($this->dataInicio, $this->dataFim, $this->divisaoTempo);
		for($i=0; $i<$numeroIntervalosTempo; $i++){
			$ordemAtual = (isset($conexaoComResultado->resultado['ordem'])? $conexaoComResultado->resultado['ordem'] : "" );
			$periodoContemRegistrosDaTurma = ($ordemInicial+$i == $ordemAtual);
			$frequenciaNoPeriodo = null;
			if($periodoContemRegistrosDaTurma){
				$frequenciaNoPeriodo = $conexaoComResultado->resultado['frequencia'];
			}
			$variaveisNoPeriodo[$i]["confianca"]		 = $this->retornaValorFP($frequenciaNoPeriodo, "confianca");
			$variaveisNoPeriodo[$i]["esforco"]			 = $this->retornaValorFP($frequenciaNoPeriodo, "esforco");
			$variaveisNoPeriodo[$i]["independencia"]	 = $this->retornaValorFP($frequenciaNoPeriodo, "independencia");
			
			$conexaoComResultado->proximo();
		}
		
		return $variaveisNoPeriodo;
	}
	protected function produzMP(){ 
		$variaveisNoPeriodo = array();
		global $nivelAluno;
		
		$conexaoComResultado = $this->retornaConexaoModoParticipacaoFuncionalidade($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		
		$ordemInicial = $conexaoComResultado->resultado['ordem_inicial'];
		$numeroIntervalosTempo = Data::getNumeroIntervalosTempoEntre($this->dataInicio, $this->dataFim, $this->divisaoTempo);
		for($i=0; $i<$numeroIntervalosTempo; $i++){
			$ordemAtual = (isset($conexaoComResultado->resultado['ordem'])? $conexaoComResultado->resultado['ordem'] : "" );
			$periodoContemRegistrosDaTurma = ($ordemInicial+$i == $ordemAtual);
			$respondeColegaPeriodo = null;
			$respondeFormadorPeriodo = null;
			if($periodoContemRegistrosDaTurma){
				$respondeColegaPeriodo = $conexaoComResultado->resultado['respondeColega'];
				$frequenciaNoPeriodo = $conexaoComResultado->resultado['respondeFormador'];
			}
			$variaveisNoPeriodo[$i]["confianca"]		 = $this->retornaValorMP($respondeColegaPeriodo, $respondeFormadorPeriodo, "confianca");
			$variaveisNoPeriodo[$i]["esforco"]			 = $this->retornaValorMP($respondeColegaPeriodo, $respondeFormadorPeriodo, "esforco");
			$variaveisNoPeriodo[$i]["independencia"]	 = $this->retornaValorMP($respondeColegaPeriodo, $respondeFormadorPeriodo, "independencia");
			
			$conexaoComResultado->proximo();
		}
		
		return $variaveisNoPeriodo;
	}
	protected function produzPA(){ return array(); }
	protected function produzTO(){ 
		$variaveisNoPeriodo = array();
		global $nivelAluno;
		
		$conexaoComResultado = $this->retornaConexaoModoParticipacaoFuncionalidade($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		
		$ordemInicial = $conexaoComResultado->resultado['ordem_inicial'];
		$numeroIntervalosTempo = Data::getNumeroIntervalosTempoEntre($this->dataInicio, $this->dataFim, $this->divisaoTempo);
		for($i=0; $i<$numeroIntervalosTempo; $i++){
			$ordemAtual = (isset($conexaoComResultado->resultado['ordem'])? $conexaoComResultado->resultado['ordem'] : "" );
			$periodoContemRegistrosDaTurma = ($ordemInicial+$i == $ordemAtual);
			$respondeColegaPeriodo = null;
			$respondeFormadorPeriodo = null;
			if($periodoContemRegistrosDaTurma){
				$respondeColegaPeriodo = $conexaoComResultado->resultado['respondeColega'];
				$frequenciaNoPeriodo = $conexaoComResultado->resultado['respondeFormador'];
			}
			$variaveisNoPeriodo[$i]["confianca"]		 = $this->retornaValorMP($respondeColegaPeriodo, $respondeFormadorPeriodo, "confianca");
			$variaveisNoPeriodo[$i]["esforco"]			 = $this->retornaValorMP($respondeColegaPeriodo, $respondeFormadorPeriodo, "esforco");
			$variaveisNoPeriodo[$i]["independencia"]	 = $this->retornaValorMP($respondeColegaPeriodo, $respondeFormadorPeriodo, "independencia");
			
			$conexaoComResultado->proximo();
		}
		
		return $variaveisNoPeriodo;
	}
	protected function produzNV(){ return array(); }
	protected function produzTP(){ return array(); }
	
	
	
}




?>