/*
* Caixa que envolve um movieclip, utilizada para testes de colisão rápidos.
*/
import flash.geom.Point;

class c_bounding_box extends Object{
//dados
	/*
	* Localização da caixa.
	*/
	private var pontoSuperiorEsquerdo:Point;
	private var pontoInferiorDireito:Point;

//métodos
	/*
	* @param envolvido_param O movieclip envoldido por esta bounding box.
	*/
	function c_bounding_box(envolvido_param:c_objeto_colisao){
		inicializar(envolvido_param);
	}
	
	/*
	* Reinicializa, como se fosse um objeto novo.
	* @param envolvido_param O movieclip envoldido por esta bounding box.
	*/
	public function inicializar(envolvido_param:c_objeto_colisao):Void{
		pontoSuperiorEsquerdo = new Point(envolvido_param._x + envolvido_param.getSombra()._x, 
										  envolvido_param._y + envolvido_param.getSombra()._y);
		pontoInferiorDireito = new Point(envolvido_param._x + envolvido_param.getSombra()._x + envolvido_param.getSombra()._width,
										 envolvido_param._y + envolvido_param.getSombra()._y + envolvido_param.getSombra()._height);
	}
	
	public function getX():Number{ return pontoSuperiorEsquerdo.x; }
	public function getY():Number{ return pontoSuperiorEsquerdo.y; }
	public function getComprimento():Number{ return pontoInferiorDireito.x - pontoSuperiorEsquerdo.x; }
	public function getLargura():Number{ return pontoInferiorDireito.y - pontoSuperiorEsquerdo.y; }
	
	public function toString(){
		return super.toString()+"\nponto superior esquerdo "+pontoSuperiorEsquerdo.toString()+"\n"
			+"ponto inferior direito "+pontoInferiorDireito.toString()+"\n";
	}
	
	/*
	* @param pontoTestado_param Ponto que será testado.
	* @return Booleano indicando se o ponto dado pertence a esta bounding box.
	*/
	public function contem(pontoTestado_param:Point):Boolean{
		return pontoSuperiorEsquerdo.x <= pontoTestado_param.x and pontoTestado_param.x <= pontoInferiorDireito.x
			and pontoSuperiorEsquerdo.y <= pontoTestado_param.y and pontoTestado_param.y <= pontoInferiorDireito.y;
	}
	
	/*
	* @param boxDentro_param Caixa suspeita de estar dentro desta.
	* @return Booleano indicando se a bounding box está complementamente ou parcialmente dentro desta.
	*/
	public function colideCom(boxDentro_param:c_bounding_box):Boolean{
		return contem(boxDentro_param.pontoSuperiorEsquerdo) or contem(boxDentro_param.pontoInferiorDireito);
	}
	
	/*
	* @param boxDentro_param Caixa suspeita de estar dentro desta.
	* @return Booleano indicando se a bounding box está completamente dentro desta.
	*/
	public function envolve(boxDentro_param:c_bounding_box):Boolean{
		return contem(boxDentro_param.pontoSuperiorEsquerdo) and contem(boxDentro_param.pontoInferiorDireito);
	}
	
}