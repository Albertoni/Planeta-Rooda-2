<?php
	
class fatoresPersonalidade{
	/*
	* Os 15 fatores do IFP.
	* - Assistъncia (Ass)
	*		Expressa, em sujeitos fortes neste fator, sentimentos de piedade, compaixуo e ternura, pelos quais se busca ser simpсtico e gratificar as necessidades de outro.
	* - Intracepчуo (I)
	*		Tendъncia, em sujeitos fortes neste item, de se deixarem conduzir por sentimentos e inclinaчѕes difusas e por julgamentos subjetivos. Busca da felicidade pela fantasia e imaginaчуo.
	* - Afago (Af)
	*		
	* - Deferъncia (Def)
	*		
	* - Afiliaчуo (Afi)
	*		
	* - Dominтncia (Do)
	*		
	* - Denegaчуo (De)
	*		
	* - Desempenho (Des)
	*		
	* - Exibiчуo (Ex)
	*		
	* - Agressуo (Ag)
	*		
	* - Ordem (O)
	*		
	* - Persistъncia (Pers)
	*		
	* - Mudanчa (M)
	*		
	* - Autonomia (Aut)
	*		
	* - Heterossexualidade (Het)
	*		
	* Fonte [http://www.lume.ufrgs.br/bitstream/handle/10183/39578/000826422.pdf?sequence=1] pсg. 28.
	* Sуo todas arrays com 5 elementos. Cada elementos corresponde р componente da variсvel em determinada intensidade. 
	* Cada componente щ um nњmero real. A soma de todas componentes щ sempre 100.
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
	* Variсveis de personalidade (as 15 variсveis acima) podem assumir 1 entre 5 valores.
	* Os valores sуo definidos р seguir.
	*/
	const MUITO_FORTE	 = 1;
	const FORTE			 = 2;
	const EQUILIBRIO	 = 3;
	const FRACA			 = 4;
	const MUITO_FRACA	 = 5;
	
	/*
	* Indicam as componentes da predominтncia dos fatores de personalidade.
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
	* @param self::MUITO_FORTE, self::FORTE, self::EQUILIBRIO, self::FRACA ou self::MUITO_FRACA $intensidade Indica a intensidade na qual deseja-se obter o valor da variсvel.
	* @return Um nњmero real indicando o valor da variсvel de personalidade na intensidade. Retornarс null em caso de erro.
	*/
	public function getTendenciaPositiva($intensidade){
		$valor = null;
		if($intensidade == self::MUITO_FORTE or $intensidade == self::FORTE or $intensidade == self::EQUILIBRIO or $intensidade == self::FRACA or $intensidade == self::MUITO_FRACA){
			//$valor = cсlculo 
		} else {
			$valor = null;
		}
		return $valor;
	}
	
	/*
	* @param self::MUITO_FORTE, self::FORTE, self::EQUILIBRIO, self::FRACA ou self::MUITO_FRACA $intensidade Indica a intensidade na qual deseja-se obter o valor da variсvel.
	* @return Um nњmero real indicando o valor da variсvel de personalidade na intensidade. Retornarс null em caso de erro.
	*/
	public function getTendenciaNegativa($intensidade){
		$valor = null;
		if($intensidade == self::MUITO_FORTE or $intensidade == self::FORTE or $intensidade == self::EQUILIBRIO or $intensidade == self::FRACA or $intensidade == self::MUITO_FRACA){
			//$valor = cсlculo 
		} else {
			$valor = null;
		}
		return $valor;
	}
	
	/*
	* @param self::PREDOMINANCIA_POSITIVA, self::PREDOMINANCIA_NEGATIVA ou self::PREDOMINANCIA_AMBIGUA $intensidade Indica a intensidade na qual deseja-se obter o valor da variсvel.
	* @return Um nњmero real indicando o valor da variсvel de personalidade na intensidade. Retornarс null em caso de erro.
	*/
	public function getPredominancia($intensidade){
		$valor = null;
		if($intensidade == self::PREDOMINANCIA_POSITIVA or $intensidade == self::PREDOMINANCIA_NEGATIVA or $intensidade == self::PREDOMINANCIA_AMBIGUA){
			//$valor = cсlculo 
		} else {
			$valor = null;
		}
		return $valor;
	}
	
	
	
	
	
}

?>