<?php





class Emocao{
//dados
	//Quadrantes
	const QUADRANTE_INSATISFEITO	 = 1;
	const QUADRANTE_SATISFEITO		 = 2;
	const QUADRANTE_ANIMADO			 = 3;
	const QUADRANTE_DESANIMADO		 = 4;
	
	//Subquadrantes
	const SUBQUADRANTE_INSATISFEITO_IRRITACAO	 = /*QUADRANTE_INSATISFEITO*10+1	*/11;
	const SUBQUADRANTE_INSATISFEITO_DESPREZO	 = /*QUADRANTE_INSATISFEITO*10+2	*/12;
	const SUBQUADRANTE_INSATISFEITO_AVERSAO		 = /*QUADRANTE_INSATISFEITO*10+3	*/13;
	const SUBQUADRANTE_INSATISFEITO_INVEJA		 = /*QUADRANTE_INSATISFEITO*10+4	*/14;
	const SUBQUADRANTE_SATISFEITO_SATISFACAO	 = /*QUADRANTE_SATISFEITO*10+1		*/21;
	const SUBQUADRANTE_SATISFEITO_ALEGRIA		 = /*QUADRANTE_SATISFEITO*10+2		*/22;
	const SUBQUADRANTE_SATISFEITO_ENTUSIASMO	 = /*QUADRANTE_SATISFEITO*10+3		*/23;
	const SUBQUADRANTE_SATISFEITO_ORGULHO		 = /*QUADRANTE_SATISFEITO*10+4		*/24;
	const SUBQUADRANTE_DESANIMADO_CULPA			 = /*QUADRANTE_DESANIMADO*10+1		*/31;
	const SUBQUADRANTE_DESANIMADO_VERGONHA		 = /*QUADRANTE_DESANIMADO*10+2		*/32;
	const SUBQUADRANTE_DESANIMADO_MEDO			 = /*QUADRANTE_DESANIMADO*10+3		*/33;
	const SUBQUADRANTE_DESANIMADO_TRISTEZA		 = /*QUADRANTE_DESANIMADO*10+4		*/34;
	const SUBQUADRANTE_ANIMADO_SERENIDADE		 = /*QUADRANTE_ANIMADO*10+1			*/41;
	const SUBQUADRANTE_ANIMADO_ESPERANCA		 = /*QUADRANTE_ANIMADO*10+2			*/42;
	const SUBQUADRANTE_ANIMADO_INTERESSE		 = /*QUADRANTE_ANIMADO*10+3			*/43;
	const SUBQUADRANTE_ANIMADO_SURPRESA			 = /*QUADRANTE_ANIMADO*10+4			*/44;
	
	//Intensidades
	const INTENSIDADE_MAXIMA	 = 4;
	const INTENSIDADE_ALTA		 = 3;
	const INTENSIDADE_BAIXA		 = 2;
	const INTENSIDADE_MINIMA	 = 1;

	/**
	* int	O subquadrante que descreve a emoзгo.
	*/
	private $subquadrante;
	
	/**
	* int	A intensidade da emoзгo.
	*/
	private $intensidade;
	
//mйtodos
	/**
	* @param int	$subquadrante	O subquadrante que descreve a emoзгo.
	* @param int	$intensidade	A intensidade da emoзгo.
	*/
	public function __construct($subquadrante, $intensidade){
		$this->subquadrante = $subquadrante;
		$this->intensidade = $intensidade;
	}

	/**
	* @return int	Quadrante em que esta emoзгo estб, umas das constantes definidas no inнcio desta classe.
	*/
	public function getQuadrante(){ return $this->subquadrante%10; }
	
	/**
	* @return int	Subquadrante em que esta emoзгo estб, umas das constantes definidas no inнcio desta classe.
	*/
	public function getSubquadrante(){ return $this->subquadrante; }
	
	/**
	* @return int	Intensidade desta emoзгo, umas das constantes definidas no inнcio desta classe.
	*/
	public function getIntensidade(){ return $this->intensidade; }

}
















?>