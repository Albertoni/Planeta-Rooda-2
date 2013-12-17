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
	* Valores que podem ser assumidos pelas variбveis nesta funcionalidade.
	* Dentro dos arrays mais aninhados, mantйm-se uma relaзгo entre ordem e valor tal que array[0] terб sempre o menor valor possнvel.
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
//mйtodos
	/***********************************************************
	*	Dados de interaзгo do usuбrio dentro de vбrias unidades de tempo
	*************************************************************/
	/**
	* @param Usuario usuario 		Usuбrio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data а partir da qual faz-se a busca.
	* @param Data dataFim			Data atй a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conexгo que contйm os dados das tabelas nгo processados.
	*/
	protected function retornaConexaoAcessosFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ 
		$bibliotecaTurma = new ArteTurma($this->idTurma);
		$conexaoComResultado = $bibliotecaTurma->buscaAcessosUsuario($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		return $conexaoComResultado;
	}
	
	/**
	* @param Usuario usuario 		Usuбrio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data а partir da qual faz-se a busca.
	* @param Data dataFim			Data atй a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conexгo que contйm os dados das tabelas nгo processados.
	*/
	protected function retornaConexaoFrequenciaFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ 
		$bibliotecaTurma = new ArteTurma($this->idTurma);
		$conexaoComResultado = $bibliotecaTurma->buscaFrequenciaParticipacaoUsuario($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		return $conexaoComResultado;
	}
	
	/**
	* @param Usuario usuario 		Usuбrio ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data а partir da qual faz-se a busca.
	* @param Data dataFim			Data atй a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conexгo que contйm os dados das tabelas nгo processados.
	*/
	protected function retornaConexaoModoParticipacaoFuncionalidade($usuario, $dataInicio, $dataFim, $divisaoTempo){ 
		$bibliotecaTurma = new ArteTurma($this->idTurma);
		$conexaoComResultado = $bibliotecaTurma->buscaModoParticipacaoUsuario($this->usuario, $this->dataInicio, $this->dataFim, $this->divisaoTempo);
		return $conexaoComResultado;
	}

}
?>