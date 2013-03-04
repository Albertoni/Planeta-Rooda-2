<?php

require_once(dirname(__FILE__)."/../Util/Data.php");
require_once(dirname(__FILE__)."/../../../../bd.php");
require_once(dirname(__FILE__)."/../../../../cfg.php");

class FuncionalidadeTurma{
//dados
	//Id no banco de dados da turma à qual pertence a aparência.
	/*String*/ protected $idTurma;
	//Nome desta funcionalidade, como deve aparecer em 'funcionalidade' de acessos_planeta.
	/*String*/ protected static $NOME_FUNCIONALIDADE = "";
	
//métodos
	/**
	* @param String idTurma	Id no banco de dados da turma à qual pertence a aparência.
	*/
	public function __construct($idTurma){
		$this->idTurma = $idTurma;
	}
	
	
	/**
	* @param Usuario usuario 		Usuário ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data à partir da qual faz-se a busca.
	* @param Data dataFim			Data até a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conexão que contém os dados das tabelas não processados.
	*/
	public function buscaAcessosUsuario($usuario, $dataInicio, $dataFim, $divisaoTempo){
		global $nivelAluno;
		$classeAtual = get_called_class();

		$conexao = new conexao();
		$conexao->solicitar("SELECT T1.acessos AS acessos, T1.ordem AS ordem, T2.mediaNoPeriodo AS mediaNoPeriodo, 
									".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataInicio->paraString()."') AS ordem_inicial
							FROM	(SELECT COUNT(*) AS acessos, ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(data_hora) AS ordem
									FROM acessos_planeta
									WHERE id_usuario = ".$usuario->getId()." 
										AND '".$dataInicio->paraString()."' <= data_hora AND data_hora <= '".$dataFim->paraString()."'
										AND funcionalidade = '".$classeAtual::$NOME_FUNCIONALIDADE."'
									GROUP BY ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(data_hora)) AS T1
									JOIN
									(SELECT COUNT(*)/(SELECT COUNT(*) 
													FROM TurmasUsuario
													WHERE associacao = ".$nivelAluno."
														AND codTurma = ".$this->idTurma.")
										AS mediaNoPeriodo, ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(data_hora) AS ordem
									FROM acessos_planeta
									WHERE id_usuario IN(
											SELECT codUsuario
											FROM TurmasUsuario
											WHERE codTurma = ".$this->idTurma."
										)
										AND '".$dataInicio->paraString()."' <= data_hora AND data_hora <= '".$dataFim->paraString()."'
										AND funcionalidade = '".$classeAtual::$NOME_FUNCIONALIDADE."'
									GROUP BY ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(data_hora)) AS T2
									ON T1.ordem = T2.ordem
									RIGHT JOIN (SELECT numero 
												FROM Numeros 
												WHERE ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataInicio->paraString()."') <= numero 
													AND numero <= ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataFim->paraString()."')) AS T3 ON T1.ordem = T3.numero");

		return $conexao;
	}
	
	/**
	* @param Usuario usuario 		Usuário ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data à partir da qual faz-se a busca.
	* @param Data dataFim			Data até a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conexão que contém os dados das tabelas não processados.
	*/
	public function buscaFrequenciaParticipacaoUsuario($usuario, $dataInicio, $dataFim, $divisaoTempo){
		global $nivelAluno;
		$classeAtual = get_called_class();

		$conexao = new conexao();
		$conexao->solicitar("SELECT T1.frequenciasMenores/T2.todasFrequencias AS frequencia, T1.ordem AS ordem,
									".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataInicio->paraString()."') AS ordem_inicial
							FROM 	(SELECT T0.todas AS frequenciasMenores, ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(data_hora) AS ordem
									FROM (	SELECT COUNT(*) AS todas, data_hora
											FROM acessos_planeta
											WHERE funcionalidade = '".$classeAtual::$NOME_FUNCIONALIDADE."'
												AND '".$dataInicio->paraString()."' <= data_hora AND data_hora <= '".$dataFim->paraString()."'
												AND id_usuario IN(	SELECT codUsuario
																	FROM TurmasUsuario
																	WHERE codTurma = ".$this->idTurma."
																)
											GROUP BY id_usuario
											) AS T0
									WHERE todas < (	SELECT COUNT(*) 
													FROM acessos_planeta 
													WHERE funcionalidade = '".$classeAtual::$NOME_FUNCIONALIDADE."' AND '".$dataInicio->paraString()."' <= data_hora AND data_hora <= '".$dataFim->paraString()."'
														AND id_usuario = ".$usuario->getId()." 
													)
									) AS T1
									JOIN (	SELECT COUNT(*) AS todasFrequencias, ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(data_hora) AS ordem
											FROM acessos_planeta
											WHERE funcionalidade = '".$classeAtual::$NOME_FUNCIONALIDADE."'
												AND '".$dataInicio->paraString()."' <= data_hora AND data_hora <= '".$dataFim->paraString()."'
												AND id_usuario IN(	SELECT codUsuario
																	FROM TurmasUsuario
																	WHERE codTurma = ".$this->idTurma."
																)
											GROUP BY id_usuario
										) AS T2 ON T1.ordem = T2.ordem
										RIGHT JOIN (SELECT numero 
													FROM Numeros 
													WHERE ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataInicio->paraString()."') <= numero 
														AND numero <= ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataFim->paraString()."')) AS T3 ON T1.ordem = T3.numero");

		return $conexao;
	}
}




?>