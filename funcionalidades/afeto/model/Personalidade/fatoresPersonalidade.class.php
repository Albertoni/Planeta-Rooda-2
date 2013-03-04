<?php
	
class fatoresPersonalidade{
	/*
	* Os 15 fatores do IFP.
	* - Assist�ncia (Ass)
	*		Expressa, em sujeitos fortes neste fator, sentimentos de piedade, compaix�o e ternura, pelos quais se busca ser simp�tico e gratificar as necessidades de outro.
	* - Intracep��o (I)
	*		Tend�ncia, em sujeitos fortes neste item, de se deixarem conduzir por sentimentos e inclina��es difusas e por julgamentos subjetivos. Busca da felicidade pela fantasia e imagina��o.
	* - Afago (Af)
	*		
	* - Defer�ncia (Def)
	*		
	* - Afilia��o (Afi)
	*		
	* - Domin�ncia (Do)
	*		
	* - Denega��o (De)
	*		
	* - Desempenho (Des)
	*		
	* - Exibi��o (Ex)
	*		
	* - Agress�o (Ag)
	*		
	* - Ordem (O)
	*		
	* - Persist�ncia (Pers)
	*		
	* - Mudan�a (M)
	*		
	* - Autonomia (Aut)
	*		
	* - Heterossexualidade (Het)
	*		
	* Fonte [http://www.lume.ufrgs.br/bitstream/handle/10183/39578/000826422.pdf?sequence=1] p�g. 28.
	* S�o todas arrays com 5 elementos. Cada elementos corresponde � componente da vari�vel em determinada intensidade. 
	* Cada componente � um n�mero real. A soma de todas componentes � sempre 100.
	* Exemplo:
	* 	$assistencia[self::MUITO_FORTE]	 = 0;
	* 	$assistencia[self::FORTE]		 = 80;
	* 	$assistencia[self::EQUILIBRIO]	 = 10.4;
	* 	$assistencia[self::FRACA]		 = 0.6;
	* 	$assistencia[self::MUITO_FRACA]	 = 9;
	*/
	protected $assistencia;
	protected $intracepcao;
	protected $afago;
	protected $deferencia;
	protected $afiliacao;
	protected $dominancia;
	protected $denegacao;
	protected $desempenho;
	protected $exibicao;
	protected $agressao;
	protected $ordem;
	protected $persistencia;
	protected $mudanca;
	protected $autonomia;
	protected $heterossexualidade;
	
	/*
	* Vari�veis de personalidade (as 15 vari�veis acima) podem assumir 1 entre 5 valores.
	* Os valores s�o definidos � seguir.
	*/
	const MUITO_FORTE	 = 1;
	const FORTE			 = 2;
	const EQUILIBRIO	 = 3;
	const FRACA			 = 4;
	const MUITO_FRACA	 = 5;
	
	/*
	* Indicam as componentes da predomin�ncia dos fatores de personalidade.
	*/
	const PREDOMINANCIA_NEGATIVA	 = 6;
	const PREDOMINANCIA_POSITIVA	 = 7;
	const PREDOMINANCIA_AMBIGUA		 = 8;
	
	/*
	* Construtor.
	*/
	public function __construct($assistencia		 = self::EQUILIBRIO,
								$intracepcao		 = self::EQUILIBRIO,
								$afago				 = self::EQUILIBRIO,
								$deferencia			 = self::EQUILIBRIO,
								$afiliacao			 = self::EQUILIBRIO,
								$dominancia			 = self::EQUILIBRIO,
								$denegacao			 = self::EQUILIBRIO,
								$desempenho			 = self::EQUILIBRIO,
								$exibicao			 = self::EQUILIBRIO,
								$agressao			 = self::EQUILIBRIO,
								$ordem				 = self::EQUILIBRIO,
								$persistencia		 = self::EQUILIBRIO,
								$mudanca			 = self::EQUILIBRIO,
								$autonomia			 = self::EQUILIBRIO,
								$heterossexualidade	 = self::EQUILIBRIO){
		
		$this->assistencia		  = $assistencia;
		$this->intracepcao		  = $intracepcao;
		$this->afago			  = $afago;
		$this->deferencia		  = $deferencia;
		$this->afiliacao		  = $afiliacao;
		$this->dominancia		  = $dominancia;
		$this->denegacao		  = $denegacao;
		$this->desempenho		  = $desempenho;
		$this->exibicao			  = $exibicao;
		$this->agressao			  = $agressao;
		$this->ordem			  = $ordem;
		$this->persistencia		  = $persistencia;
		$this->mudanca			  = $mudanca;
		$this->autonomia		  = $autonomia;
		$this->heterossexualidade = $heterossexualidade;
	}
	
	
	
	/*
	* @param self::MUITO_FORTE, self::FORTE, self::EQUILIBRIO, self::FRACA ou self::MUITO_FRACA $intensidade Indica a intensidade na qual deseja-se obter o valor da vari�vel.
	* @return Um n�mero real indicando o valor da vari�vel de personalidade na intensidade. Retornar� null em caso de erro.
	*/
	public function getTendenciaPositiva($intensidade){
		$valor = null;
		if($intensidade == self::MUITO_FORTE or $intensidade == self::FORTE or $intensidade == self::EQUILIBRIO or $intensidade == self::FRACA or $intensidade == self::MUITO_FRACA){
			//$valor = c�lculo 
		} else {
			$valor = null;
		}
		return $valor;
	}
	
	/*
	* @param self::MUITO_FORTE, self::FORTE, self::EQUILIBRIO, self::FRACA ou self::MUITO_FRACA $intensidade Indica a intensidade na qual deseja-se obter o valor da vari�vel.
	* @return Um n�mero real indicando o valor da vari�vel de personalidade na intensidade. Retornar� null em caso de erro.
	*/
	public function getTendenciaNegativa($intensidade){
		$valor = null;
		if($intensidade == self::MUITO_FORTE or $intensidade == self::FORTE or $intensidade == self::EQUILIBRIO or $intensidade == self::FRACA or $intensidade == self::MUITO_FRACA){
			//$valor = c�lculo 
		} else {
			$valor = null;
		}
		return $valor;
	}
	
	/*
	* @param self::PREDOMINANCIA_POSITIVA, self::PREDOMINANCIA_NEGATIVA ou self::PREDOMINANCIA_AMBIGUA $intensidade Indica a intensidade na qual deseja-se obter o valor da vari�vel.
	* @return Um n�mero real indicando o valor da vari�vel de personalidade na intensidade. Retornar� null em caso de erro.
	*/
	public function getPredominancia($intensidade){
		$valor = null;
		if($intensidade == self::PREDOMINANCIA_POSITIVA or $intensidade == self::PREDOMINANCIA_NEGATIVA or $intensidade == self::PREDOMINANCIA_AMBIGUA){
			//$valor = c�lculo 
		} else {
			$valor = null;
		}
		return $valor;
	}
	
	
	
	
	
}

?>