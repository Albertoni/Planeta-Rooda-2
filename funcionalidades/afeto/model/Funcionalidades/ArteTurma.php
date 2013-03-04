<?php

require_once(dirname(__FILE__)."/FuncionalidadeTurma.php");
require_once(dirname(__FILE__)."/../Util/Data.php");
require_once(dirname(__FILE__)."/../../../../bd.php");
require_once(dirname(__FILE__)."/../../../../cfg.php");

class ArteTurma extends FuncionalidadeTurma{
//dados
	//Nome desta funcionalidade, como deve aparecer em 'funcionalidade' de acessos_planeta.
	/*String*/ protected static $NOME_FUNCIONALIDADE = "arte";
	
//métodos
	/**
	* @param Usuario usuario 		Usuário ao qual refere-se este grupo de fatores motivacionais.
	* @param Data dataInicio		Data à partir da qual faz-se a busca.
	* @param Data dataFim			Data até a qual faz-se a busca.
	* @param String divisaoTempo	Se deve-se retornar os dados em semanas, meses, semestres, anos... Espera uma constante definida em Data.php.
	* @return Conexão que contém os dados das tabelas não processados.
	*/
	public function buscaModoParticipacaoUsuario($usuario, $dataInicio, $dataFim, $divisaoTempo){
		global $nivelAluno;
		global $nivelMonitor;
		global $nivelProfessor;
		$classeAtual = get_called_class();
		
		$conexao = new conexao();
		$conexao->solicitar("SELECT 0<T2.comentariosDesenhoColega AS respondeColega, 0<T1.comentariosDesenhoFormador AS respondeFormador, T1.ordem AS ordem, 
								".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataInicio->paraString()."') AS ordem_inicial
							FROM (	SELECT COUNT(*) AS comentariosDesenhoFormador, ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(ArtesComentarios.Data) AS ordem
									FROM ArtesComentarios JOIN ArtesDesenhos ON ArtesComentarios.codDesenho = ArtesDesenhos.codDesenho
									WHERE ArtesDesenhos.codUsuario IN (
											SELECT codUsuario
											FROM TurmasUsuario
											WHERE codTurma = ".$this->idTurma." 
												AND (associacao = ".$nivelMonitor." OR associacao = ".$nivelProfessor.")
										)
									GROUP BY ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(ArtesComentarios.Data)
								) AS T1
								JOIN
								(	SELECT COUNT(*) AS comentariosDesenhoColega, ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(ArtesComentarios.Data) AS ordem
									FROM ArtesComentarios JOIN ArtesDesenhos ON ArtesComentarios.codDesenho = ArtesDesenhos.codDesenho
									WHERE ArtesDesenhos.codUsuario IN (
											SELECT codUsuario
											FROM TurmasUsuario
											WHERE codTurma = ".$this->idTurma." 
												AND (associacao = ".$nivelAluno.")
										)
									GROUP BY ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."(ArtesComentarios.Data)
								) AS T2
								ON T1.ordem = T2.ordem
								RIGHT JOIN (SELECT numero 
											FROM Numeros 
											WHERE ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataInicio->paraString()."') <= numero 
												AND numero <= ".Data::getFuncaoBancoDeDadosPeriodo($divisaoTempo)."('".$dataFim->paraString()."')) AS T3 ON T1.ordem = T3.numero");
		return $conexao;
	}
}




?>