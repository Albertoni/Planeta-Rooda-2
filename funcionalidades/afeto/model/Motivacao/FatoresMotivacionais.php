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
* Dados de comportamento de alunos. Inferidos à partir da interação dos alunos com o AVA.
*/
class FatoresMotivacionais{
//dados
	/*
	* Fator motivacional = {confiança, esforço, independência}
	* Variável de interação = {NA, FP, MP, PA, TO, NV, TP}
	* Funcionalidade = {biblioteca, blog, forum, portfolio, aparencia, arte, pergunta, aulas, player}
	* Para cada tripla (fator motivacional, variável de interação, funcionalidade) têm-se um conjunto de valores que pode ser assumido, 
	* lembrando que a relação não existe para toda tripla de elementos destes conjuntos.
	* O valor assumido pelo par é dependente de um usuário em um período de tempo.
	* As descrições à seguir foram retiradas de artigo disponível em [http://www.lume.ufrgs.br/bitstream/handle/10183/39578/000826422.pdf?sequence=1] pág 133.
	* As variáveis de interação são:
	* NA - Número de Acessos               : definido pelo ato de abrir ou entrar na funcionalidade, tendo a turma como parâmetro.
	*	Assume os valores {inferior à média, igual ou superior à média}
	* FP - Freqüência de Participação      : obtida pelo número de vezes em que o aluno participa na funcionalidade em relação à turma.
	*	Assume os valores {>=75%, >=50% e <75%, >=25% e <50%, >0% e <25%, 0%}
	* MP - Modo de Participação            : verificada à partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação.
	*	Assume os valores {responde ao formador e responde ao colega, não responde ao colega e não responde ao formador, 
	*						responde ao formador e não responde ao colega, não responde ao formador e responde ao colega, não participa do fórum}
	* PA - Pedidos ou prestação de Ajuda   : indicam se, e com que intensidade, o aluno solicita dicas e ajuda (entrando em contato com formadores/colegas ou sistema) ou as oferece aos colegas.
	*	Assume os valores {a todos, ao formador, aos colegas, não pede}
	* TO - Geração de mensagens ou Tópicos : informa a criação de novas mensagens em um tópico ou novos tópicos para a funcionalidade Fórum.
	*	Assume os valores {cria sua própria mensagem, cria um novo tópico}
	* NV - Número de Vistas ao tópico      : definida pela freqüência, em relação à turma, com que um usuário visita um tópico do Fórum.
	*	Assume os valores {inferior ao número de acessos, igual ou superior ao número de acessos}
	* TP - Tempo de Permanência na sessão  : representa a média de tempo despendido em uma sessão (conexão no AVA).
	*	Assume os valores {igual ou inferior à média da turma, superior à média da turma}
	*/
	
	/*
	* Fatores motivacionais calculados à partir das variáveis de interação com o AVA. 
	* São números reais no intervalo [-1, 1].
	*/
	private $confianca;
	private $esforco;
	private $independencia;
	
	/*
	* Fatores são separados em grandes arrays para cada funcionalidade.
	* @see Para mais informações, cheque o resultado de getFatoresNoPeriodo() em /afeto/model/Motivacao/FuncionalidadeFatorMotivacional.
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
	
//métodos
	/*
	* Procura os dados de comportamento do usuário dado no banco de dados no período dado.
	* @param Data 		$data_inicio 	Data de início da pesquisa.
	* @param Data 		$data_fim 		Data de fim da pesquisa.
	* @param Usuario	$usuario 		O usuário que será testado.
	* @param Turma		$turma			A turma em que o usuário será testado.
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
	* Preenche as variáveis usadas no cálculo dos fatores motivacionais com base em dados de interação do usuário com o AVA.
	* @param Data 		$data_inicio 	Data de início da pesquisa.
	* @param Data 		$data_fim 		Data de fim da pesquisa.
	* @param Usuario	$usuario 		O usuário que será testado.
	* @param Turma		$turma			A turma em que o usuário será testado.
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