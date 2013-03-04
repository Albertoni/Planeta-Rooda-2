<?php

//Util
require_once(dirname(__FILE__)."/../../../../bd.php");
require_once(dirname(__FILE__)."/../../../../cfg.php");
require_once(dirname(__FILE__)."/../Util/Data.php");

//Model
require_once(dirname(__FILE__)."/FuncionalidadeFatorMotivacional.php");
require_once(dirname(__FILE__)."/../Funcionalidades/ArteTurma.php");

class fatoresMotivacionaisArte extends FuncionalidadeFatorMotivacional{
//dados
	
	/*
	* Valores que podem ser assumidos pelas vari�veis nesta funcionalidade.
	* Dentro dos arrays mais aninhados, mant�m-se uma rela��o entre ordem e valor tal que array[0] ter� sempre o menor valor poss�vel.
	*/
	public static $valoresFatores = array(	"confianca"		=>array("NA"=>array(-1,0,1),
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
	protected function retornaConexaoAcessosFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ 
		$bibliotecaTurma = new ArteTurma($this->idTurma);
		$conexaoComResultado = $bibliotecaTurma->buscaAcessosUsuario($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		return $conexaoComResultado;
	}
	
	/**
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conex�o que cont�m os dados das tabelas n�o processados.
	*/
	protected function retornaConexaoFrequenciaFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ 
		$bibliotecaTurma = new ArteTurma($this->idTurma);
		$conexaoComResultado = $bibliotecaTurma->buscaFrequenciaParticipacaoUsuario($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		return $conexaoComResultado;
	}
	
	/**
	* @param Usuario usuario 		Usu�rio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data � partir da qual faz-se a busca.
	* @param Data dataFim			Data at� a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conex�o que cont�m os dados das tabelas n�o processados.
	*/
	protected function retornaConexaoModoParticipacaoFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ 
		$bibliotecaTurma = new ArteTurma($this->idTurma);
		$conexaoComResultado = $bibliotecaTurma->buscaModoParticipacaoUsuario($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		return $conexaoComResultado;
	}

}
?>