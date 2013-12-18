import flash.geom.Point;

class c_desenhoAviso extends MovieClip{
//dados	
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "desenhoAviso";
	
	/*
	* Dimensões mínimas.
	*/
	public static var COMPRIMENTO_MINIMO:Number = 271.2;
	public static var LARGURA_MINIMA:Number = 90.9;
	
//métodos	
	/*
	* Redimensiona o desenho para o tamanho dado.
	*/
	public function redimensionar(novoComprimento_param:Number, novaLargura_param:Number){
		if(COMPRIMENTO_MINIMO < novoComprimento_param){
			redimensionarComprimento(novoComprimento_param);
		}
		if(LARGURA_MINIMA < novaLargura_param){
			redimensionarLargura(novaLargura_param);
		}
	}
	private function redimensionarComprimento(valor_param:Number){
		var SOBRA:Number = 1.1;
		this['fundo']._width = valor_param;
		this['desenhoDireito']._x = this['fundo']._x + this['fundo']._width - this['desenhoDireito']._width + SOBRA;
	}
	private function redimensionarLargura(valor_param:Number){
		this['fundo']._height = valor_param;
		this['desenhoEsquerdo']._height = valor_param + 6.6;
		this['desenhoDireito']._height = valor_param + 6.6;
	}
	
	
}