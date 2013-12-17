<?php

//Util
require_once(dirname(__FILE__)."/../Util/Data.php");
require_once(dirname(__FILE__)."/../../../../cfg.php");
require_once(dirname(__FILE__)."/../../../../bd.php");
require_once(dirname(__FILE__)."/../../../../funcoes_aux.php");
require_once(dirname(__FILE__)."/../../../../usuarios.class.php");

//Model
require_once(dirname(__FILE__)."/FatoresMotivacionaisBiblioteca.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisBlog.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisForum.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisPortfolio.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisAparencia.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisArte.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisPerguntas.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisAulas.php");
require_once(dirname(__FILE__)."/FatoresMotivacionaisPlayer.php");

/*
* Dados de comportamento de alunos. Inferidos � partir da intera��o dos alunos com o AVA.
*/
class FatoresMotivacionais{
//dados
	/*
	* Fator motivacional = {confian�a, esfor�o, independ�ncia}
	* Vari�vel de intera��o = {NA, FP, MP, PA, TO, NV, TP}
	* Funcionalidade = {biblioteca, blog, forum, portfolio, aparencia, arte, pergunta, aulas, player}
	* Para cada tripla (fator motivacional, vari�vel de intera��o, funcionalidade) t�m-se um conjunto de valores que pode ser assumido, 
	* lembrando que a rela��o n�o existe para toda tripla de elementos destes conjuntos.
	* O valor assumido pelo par � dependente de um usu�rio em um per�odo de tempo.
	* As descri��es � seguir foram retiradas de artigo dispon�vel em [http://www.lume.ufrgs.br/bitstream/handle/10183/39578/000826422.pdf?sequence=1] p�g 133.
	* As vari�veis de intera��o s�o:
	* NA - N�mero de Acessos               : definido pelo ato de abrir ou entrar na funcionalidade, tendo a turma como par�metro.
	*	Assume os valores {inferior � m�dia, igual ou superior � m�dia}
	* FP - Freq��ncia de Participa��o      : obtida pelo n�mero de vezes em que o aluno participa na funcionalidade em rela��o � turma.
	*	Assume os valores {>=75%, >=50% e <75%, >=25% e <50%, >0% e <25%, 0%}
	* MP - Modo de Participa��o            : verificada � partir da forma como o aluno participa na funcionalidade, isto �, o modo como ocorre a intera��o.
	*	Assume os valores {responde ao formador e responde ao colega, n�o responde ao colega e n�o responde ao formador, 
	*						responde ao formador e n�o responde ao colega, n�o responde ao formador e responde ao colega, n�o participa do f�rum}
	* PA - Pedidos ou presta��o de Ajuda   : indicam se, e com que intensidade, o aluno solicita dicas e ajuda (entrando em contato com formadores/colegas ou sistema) ou as oferece aos colegas.
	*	Assume os valores {a todos, ao formador, aos colegas, n�o pede}
	* TO - Gera��o de mensagens ou T�picos : informa a cria��o de novas mensagens em um t�pico ou novos t�picos para a funcionalidade F�rum.
	*	Assume os valores {cria sua pr�pria mensagem, cria um novo t�pico}
	* NV - N�mero de Vistas ao t�pico      : definida pela freq��ncia, em rela��o � turma, com que um usu�rio visita um t�pico do F�rum.
	*	Assume os valores {inferior ao n�mero de acessos, igual ou superior ao n�mero de acessos}
	* TP - Tempo de Perman�ncia na sess�o  : representa a m�dia de tempo despendido em uma sess�o (conex�o no AVA).
	*	Assume os valores {igual ou inferior � m�dia da turma, superior � m�dia da turma}
	*/
	
	/*
	* Fatores motivacionais calculados � partir das vari�veis de intera��o com o AVA. 
	* S�o n�meros reais no intervalo [-1, 1].
	*/
	private $confianca;
	private $esforco;
	private $independencia;
	
	/*
	* Fatores s�o separados em grandes arrays para cada funcionalidade.
	* @see Para mais informa��es, cheque o resultado de getFatoresNoPeriodo() em /afeto/model/Motivacao/FuncionalidadeFatorMotivacional.
	*/
	private $fatoresBiblioteca;
	private $fatoresBlog;
	private $fatoresForum;
	private $fatoresPortfolio;
	private $fatoresAparencia;
	private $fatoresArte;
	private $fatoresPerguntas;
	private $fatoresAulas;
	private $fatoresPlayer;
	
//m�todos
	/*
	* Procura os dados de comportamento do usu�rio dado no banco de dados no per�odo dado.
	* @param Data 		$data_inicio 	Data de in�cio da pesquisa.
	* @param Data 		$data_fim 		Data de fim da pesquisa.
	* @param Usuario	$usuario 		O usu�rio que ser� testado.
	* @param Turma		$turma			A turma em que o usu�rio ser� testado.
	*/
	public function __construct($data_inicio, $data_fim, $usuario, $turma){
		$this->levantarDadosDeInteracao($data_inicio, $data_fim, $usuario, $turma);
	}
	
	public function getConfianca(){ return $this->confianca; }
	public function getEsforco(){ return $this->esforco; }
	public function getIndependencia(){ return $this->independencia; }
	
	public function getFatoresBiblioteca()	{ return $this->fatoresBiblioteca; }
	public function getFatoresBlog()		{ return $this->fatoresBlog; }
	public function getFatoresForum()		{ return $this->fatoresForum; }
	public function getFatoresPortfolio()	{ return $this->fatoresPortfolio; }
	public function getFatoresAparencia()	{ return $this->fatoresAparencia; }
	public function getFatoresArte()		{ return $this->fatoresArte; }
	public function getFatoresPerguntas()	{ return $this->fatoresPerguntas; }
	public function getFatoresAulas()		{ return $this->fatoresAulas; }
	public function getFatoresPlayer()		{ return $this->fatoresPlayer; }
	
	/*
	* Preenche as vari�veis usadas no c�lculo dos fatores motivacionais com base em dados de intera��o do usu�rio com o AVA.
	* @param Data 		$data_inicio 	Data de in�cio da pesquisa.
	* @param Data 		$data_fim 		Data de fim da pesquisa.
	* @param Usuario	$usuario 		O usu�rio que ser� testado.
	* @param Turma		$turma			A turma em que o usu�rio ser� testado.
	*/
	private function levantarDadosDeInteracao($data_inicio, $data_fim, $usuario, $turma){
		$FatoresMotivacionaisBiblioteca = new FatoresMotivacionaisBiblioteca($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisBlog = new FatoresMotivacionaisBlog($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisForum = new FatoresMotivacionaisForum($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisPortfolio = new FatoresMotivacionaisPortfolio($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisAparencia = new FatoresMotivacionaisAparencia($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisArte = new FatoresMotivacionaisArte($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisPerguntas = new FatoresMotivacionaisPerguntas($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisAulas = new FatoresMotivacionaisAulas($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		$FatoresMotivacionaisPlayer = new FatoresMotivacionaisPlayer($turma->getId(), $usuario, $data_inicio, $data_fim, Data::SEMANA);
		
		$this->fatoresBiblioteca	 = $FatoresMotivacionaisBiblioteca->getFatoresNoPeriodo();
		$this->fatoresBlog			 = $FatoresMotivacionaisBlog->getFatoresNoPeriodo();
		$this->fatoresForum			 = $FatoresMotivacionaisForum->getFatoresNoPeriodo();
		$this->fatoresPortfolio		 = $FatoresMotivacionaisPortfolio->getFatoresNoPeriodo();
		$this->fatoresAparencia		 = $FatoresMotivacionaisAparencia->getFatoresNoPeriodo();
		$this->fatoresArte			 = $FatoresMotivacionaisArte->getFatoresNoPeriodo();
		$this->fatoresPerguntas		 = $FatoresMotivacionaisPerguntas->getFatoresNoPeriodo();
		$this->fatoresAulas			 = $FatoresMotivacionaisAulas->getFatoresNoPeriodo();
		$this->fatoresPlayer		 = $FatoresMotivacionaisPlayer->getFatoresNoPeriodo();
		
	}
	
	/*
	* @return Este objeto em forma de string (para leitura humana).
	*/
	public function toString(){
		$emString = "";
		
		//$emString .= "Biblioteca: ";
		//$emString .= $this->fatoresBiblioteca;
		//print_r($this->fatoresBiblioteca);
		//$emString .= implode(",",array_keys($this->fatoresBiblioteca)).''.implode(",",$this->fatoresBiblioteca);
		
		return $emString;
	}
	
}


?>