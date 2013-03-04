import flash.geom.Point;
/*
* Um objeto com dados de colisão.
*
*/
class c_objeto_colisao extends MovieClip{
//dados
	/*
	* Dados de colisão.
	* Um array contendo informações sobre a borda do objeto.
	*/
	private var nucleoColisao:Array = new Array();
	private var referenciasColisao:Array = new Array();
	
	/*
	* Obstáculo de colisão do objeto.
	* Pelo alto custo de memória, só deve ser usado quando realmente necessário.
	*/
	private var obstaculo:c_obstaculo = undefined;

//métodos
	/*
	* Atualiza os dados de colisão, utilizando métodos de c_colisao_terreno.
	*/
	public function atualizarColisao(){
		//Cria matriz com o nucleo da sombra do objeto.
		nucleoColisao = c_sistema_colisao.objNucleo(_parent._parent._x + _parent._x + _x + getSombra()._x, 
						    				  		_parent._parent._y + _parent._y + _y + getSombra()._y, 
						    				  		getSombra());
		
		//Cria as matrizes com as referências do objeto da sombra.
		referenciasColisao = c_sistema_colisao.refBorda(nucleoColisao);
	}
	
	/*
	* @return Os dados de colisão deste objeto.
	*/
	public function getNucleoColisao():Array{
		return nucleoColisao;
	}
	
	/*
	* @return O ponto considerado centro do objeto (para definir sua profundidade).
	*/
	public function getPontoCentral():Point{
		return new Point(_x + getSombra()._x + getSombra._width/2, _y + getSombra()._y + getSombra()._y/2);
	}
	
	/*
	* @return Os dados de colisão deste objeto.
	*/
	public function getReferenciasColisao():Array{
		return referenciasColisao;
	}

	/*
	* @return A forma da colisão deste objeto como MovieClip.
	*/
	public function getSombra():MovieClip{
		return this['sombra'];
	}
	
	/*
	* @return Booleano indicando se há colisão entre a sombra deste objeto e a posição dada.
	*/
	public function haColisao(x_param:Number, y_param:Number):Boolean{
		if(obstaculo == undefined){
			obstaculo = new c_obstaculo(getSombra(), getSombra()._x, getSombra()._y);
		}
		return obstaculo.haColisao(x_param - _x, y_param - _y);
	}
	
	

}