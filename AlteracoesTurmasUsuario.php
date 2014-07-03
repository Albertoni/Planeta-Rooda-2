<?php
require_once("cfg.php");
require_once("bd.php");
require_once("funcoes_aux.php");
require_once("reguaNavegacao.class.php");
require_once("usuarios.class.php");
require_once("turma.class.php");

/**
* Este arquivo destina-se � implementa��o da classe AlteracoesTurmasUsuario.
*/
class AlteracoesTurmasUsuario{
//dados
	//Usuario de cujas turmas as altera��es poder�o ser acessadas.
	private /*Usuario*/ $usuario;
	//Turmas em que o usu�rio desempenha certo papel.
	private /*Array<Turma>*/ $turmasUsuarioAluno;
	private /*Array<Turma>*/ $turmasUsuarioMonitor;
	private /*Array<Turma>*/ $turmasUsuarioProfessor;
	
	
//m�todos
	/**
	* @param Usuario 	$usuario	O usu�rio de cujas turmas as altera��es ser�o pesquisadas.
	*/
	public function __construct(/*Usuario*/ $usuario){
		global $nivelAluno;
		global $nivelMonitor;
		global $nivelProfessor;
		
		$this->usuario = $usuario;
		
		$this->turmasUsuarioAluno			 = $this->usuario->buscaTurmasComNivel($nivelAluno);
		$this->turmasUsuarioMonitor			 = $this->usuario->buscaTurmasComNivel($nivelMonitor);
		$this->turmasUsuarioProfessor		 = $this->usuario->buscaTurmasComNivel($nivelProfessor);
	}
	
	/**
	* @param int	$nivelDeCorte	Determina de que turmas as mensagens ser�o buscadas. O n�vel de corte � aplicado como <=, retornando turmas em que 
	*								o usu�rio desempenhe m�ltiplos pap�is.
	* @return Array<String> 		Todas as mensagens que de altera��es que devem ser exibidas, separadas por turmas em que um usu�rio desempenha um papel.
	*								Exemplo: Array[0] = <mensagem das altera��es na turma X>, Array[1] = <mensagem das altera��es na turma Y>, ...
	*/
	public function gerarMensagensAlteracoesTurmasComPapel(/*int*/ $nivelDeCorte){
		global $nivelAluno;
		global $nivelMonitor;
		global $nivelProfessor;
		
		$mensagens = array();
		$arrayTurmas = array();
		switch($nivelDeCorte){
			case $nivelAluno:		$arrayTurmas = $this->turmasUsuarioAluno;
				break;
			case $nivelMonitor:		$arrayTurmas = $this->turmasUsuarioMonitor;
				break;
			case $nivelProfessor:	$arrayTurmas = $this->turmasUsuarioProfessor;
				break;
		}
		
		$dataUltimoLogin = $this->usuario->getDataUltimoLogin();
		if(0 < count($arrayTurmas)){
			for($i=0; $i<count($arrayTurmas); $i++){
				$mensagemAlteracoes = "";

                $aprovacoesNecessariasBib = $arrayTurmas[$i]->getNumeroAprovacoesBiblioteca();
				$alteracoesBiblioteca	 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::BIBLIOTECA, $dataUltimoLogin);
				$alteracoesBlog 		 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::BLOG, $dataUltimoLogin);
				$alteracoesForum 		 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::FORUM, $dataUltimoLogin);
				$alteracoesArte 		 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::ARTE, $dataUltimoLogin);
				$alteracoesPergunta 	 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::PERGUNTA, $dataUltimoLogin);
				$alteracoesPortfolio 	 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::PORTFOLIO, $dataUltimoLogin);
				$alteracoesPlayer 		 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::PLAYER, $dataUltimoLogin);
				$alteracoesAulas 		 = $arrayTurmas[$i]->getNumeroAlteracoes(turma::AULAS, $dataUltimoLogin);
				
				if(0 < $alteracoesBiblioteca || 0 < $alteracoesBlog || 0 < $alteracoesForum || 0 < $alteracoesArte
						|| 0 < $alteracoesPergunta || 0 < $alteracoesPortfolio || 0 < $alteracoesPlayer || 0 < $alteracoesAulas){
					$mensagemAlteracoes = "Altera&ccedil;&otilde;es na turma ".$arrayTurmas[$i]->getNome().":<br>";

					if(0 < $alteracoesBiblioteca)	{ $mensagemAlteracoes .= $alteracoesBiblioteca." na Biblioteca.<br>"; }
					if(0 < $alteracoesBlog)			{ $mensagemAlteracoes .= $alteracoesBlog." em Blogs de pessoas desta turma.<br>"; }
					if(0 < $alteracoesForum)		{ $mensagemAlteracoes .= $alteracoesForum." no F&oacute;rum.<br>"; }
					if(0 < $alteracoesArte)			{ $mensagemAlteracoes .= $alteracoesArte." no Arte.<br>"; }
					if(0 < $alteracoesPergunta)		{ $mensagemAlteracoes .= $alteracoesPergunta." no Pergunta.<br>"; }
					if(0 < $alteracoesPortfolio)	{ $mensagemAlteracoes .= $alteracoesPortfolio." no Portf&oacute;lio.<br>"; }
					if(0 < $alteracoesPlayer)		{ $mensagemAlteracoes .= $alteracoesPlayer." no Player.<br>"; }
					if(0 < $alteracoesAulas)		{ $mensagemAlteracoes .= $alteracoesAulas." no Aulas.<br>"; }
					
					$mensagemAlteracoes .= '<br>';
					
					$mensagens[] = $mensagemAlteracoes;
				}

                if(0 < $aprovacoesNecessariasBib){
                    $mensagemAlteracoes = " N&uacute;mero de aprova&ccedil;&otilde;es pendentes na biblioteca da turma ".$arrayTurmas[$i]->getNome().": ".$aprovacoesNecessariasBib."<br>";
                    $mensagemAlteracoes .= '<br>';
                    $mensagens[] = $mensagemAlteracoes;
                }
			}
		}
		
		return $mensagens;
	}
	
	
	
}



?>