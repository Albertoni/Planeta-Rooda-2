/*
* Classe que implementa terrenos retangulares, com 4 pontes.
* Este terreno possui área útil retangular.
*/
class c_terreno_retangular extends c_terreno {
//dados
	/*
	* Constantes cujo valor deve ser dado em cada terreno que especialize esta classe.
	* Indicam a área útil deste terreno, isto é, delimitam a região em que o usuário pode se movimentar e inserir casas/árvores/etc.
	* Este terreno possui área útil retangular.
	*/
	public var POS_X_INICIO_AREA_UTIL:Number = 10;
	public var POS_Y_INICIO_AREA_UTIL:Number = 10;
	public var COMPRIMENTO_AREA_UTIL:Number = 1487.05;
	public var LARGURA_AREA_UTIL:Number = 1146.95;
	/*
	* O terreno que especializa esta classe precisa possuir 4 movieclips com os nomes que seguem.
	*/
	private var PONTE_NOROESTE:String = "ponte_noroeste";
	private var PONTE_SUDOESTE:String = "ponte_sudoeste";
	private var PONTE_NORDESTE:String = "ponte_nordeste";
	private var PONTE_SUDESTE:String = "ponte_sudeste";
	
//métodos	
	public function inicializar(x_personagem_param:Number, y_personagem_param:Number) {
		super.inicializar(x_personagem_param, y_personagem_param);
	}
	
	//---- Colisão
	/*
	* Indica se o ponto (x,y) do parâmetro pertence à área útil do terreno.
	* Recebe coordenadas de terreno.
	*/
	public function estaNaAreaUtil(x_param:Number, y_param:Number):Boolean{
		if(pertenceAoRetangulo(x_param, y_param, POS_X_INICIO_AREA_UTIL, POS_Y_INICIO_AREA_UTIL, COMPRIMENTO_AREA_UTIL, LARGURA_AREA_UTIL)){
			return true;
		} else {
			return false;
		}
	}
	/*
	* Indica se o ponto está fora do terreno e em posição de transição, à oeste.
	* Exemplo: Em um terreno cuja transição é dada por pontes, esta função retorna true se o ponto pertencer à ponte.
	* Recebe coordenadas de terreno.
	*/
	public function ultrapassaLimiteOeste(x_param:Number, y_param:Number):Boolean{
		if(x_param < POS_X_INICIO_AREA_UTIL and 
		   (pertenceAoRetangulo(x_param, y_param, this[PONTE_NOROESTE]._x, this[PONTE_NOROESTE]._y, this[PONTE_NOROESTE]._width, this[PONTE_NOROESTE]._height)
			or pertenceAoRetangulo(x_param, y_param, this[PONTE_SUDOESTE]._x, this[PONTE_SUDOESTE]._y, this[PONTE_SUDOESTE]._width, this[PONTE_SUDOESTE]._height))){
			return true;
		} else {
			return false;
		}
	}
	/*
	* Indica se o ponto está fora do terreno e em posição de transição, à oeste.
	* Exemplo: Em um terreno cuja transição é dada por pontes, esta função retorna true se o ponto pertencer à ponte.
	* Recebe coordenadas de terreno.
	*/
	public function ultrapassaLimiteLeste(x_param:Number, y_param:Number):Boolean{
		if(POS_X_INICIO_AREA_UTIL + COMPRIMENTO_AREA_UTIL < x_param and 
		   (pertenceAoRetangulo(x_param, y_param, this[PONTE_NORDESTE]._x, this[PONTE_NORDESTE]._y, this[PONTE_NORDESTE]._width, this[PONTE_NORDESTE]._height)
			or pertenceAoRetangulo(x_param, y_param, this[PONTE_SUDESTE]._x, this[PONTE_SUDESTE]._y, this[PONTE_SUDESTE]._width, this[PONTE_SUDESTE]._height))){
			return true;
		} else {
			return false;
		}
	}
	/*
	* Para o dado y, retorna o x que é o maior possível relacionado com o y dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteLeste(y_param:Number):Number{
		return POS_X_INICIO_AREA_UTIL + COMPRIMENTO_AREA_UTIL - 1;
	}
	/*
	* Para o dado y, retorna o x que é o menor possível relacionado com o y dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteOeste(y_param:Number):Number{
		return POS_X_INICIO_AREA_UTIL + 1;
	}
	/*
	* Para o dado x, retorna o y que é o menor possível relacionado com o x dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteNorte(y_param:Number):Number{
		return POS_Y_INICIO_AREA_UTIL + 1;
	}
	/*
	* Para o dado x, retorna o y que é o maior possível relacionado com o x dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteSul(y_param:Number):Number{
		return POS_Y_INICIO_AREA_UTIL + LARGURA_AREA_UTIL - 1;
	}
	
	
	
	
	
	
	
	
	
}
