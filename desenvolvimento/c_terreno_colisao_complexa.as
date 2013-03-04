import flash.geom.Point;

/*
* Classe que implementa terrenos de colisão complexa.
* Este terreno possui área útil complexa, que pode ter qualquer forma.
* Nenhuma parte do terreno alcançável deve ficar antes de (0,0).
*/
class c_terreno_colisao_complexa extends c_terreno_editavel {
//dados
	/*
	* Constantes cujo valor deve ser dado em cada terreno que especialize esta classe.
	* Comprimento e largura são estimados e não devem ser utilizados para testes de colisão.
	*/
	public var POS_X_INICIO_AREA_UTIL:Number = 10;
	public var POS_Y_INICIO_AREA_UTIL:Number = 10;
	private var COMPRIMENTO_ESTIMADO:Number = 1500;
	private var LARGURA_ESTIMADA:Number = 1500;
	public var COMPRIMENTO_AREA_UTIL:Number = COMPRIMENTO_ESTIMADO;
	public var LARGURA_AREA_UTIL:Number = LARGURA_ESTIMADA;
	
	/*
	* Objeto obstáculo usado para conferir colisão (pois a função hitTest não foi suficiente).
	*/
	public var areaUtil:c_obstaculo;
	
//métodos	
	public function inicializar(dados_personagem_param:c_personagem_bd, imagem_bd_param:c_terreno_bd) {
		var cenario:MovieClip = this['cenario'];
		areaUtil = new c_obstaculo(cenario.areaUtil, 
								   cenario.areaUtil._x+cenario._x, 
								   cenario.areaUtil._y+cenario._y);
		super.inicializar(dados_personagem_param, imagem_bd_param);
	}
	
	//---- Colisão
	/*
	* Indica se o ponto (x,y) do parâmetro pertence à área útil do terreno.
	* Recebe coordenadas de terreno.
	*/
	public function estaNaAreaUtil(x_param:Number, y_param:Number):Boolean{
		if(areaUtil.haColisao(x_param, y_param)){
			return true;
		} else {
			return false;
		}
	}
	
	/*
	* Indica se o objeto de colisão do parâmetro pertence à área útil do terreno.
	* A posição do objeto é dada como parâmetro também.
	* @param objeto_param Um modelo, indicando o tipo do objeto.
	* @param posicao_param A posição do objeto. Caso não esteja definida, é considerada a posição do objeto.
	*/
	public function estaNaAreaUtilObjeto(objeto_param:c_objeto_colisao, posicao_param:Point):Boolean{
		var posicao:Point;
		if(posicao_param == undefined){
			posicao = new Point(objeto_param._x, objeto_param._y);
		} else {
			posicao = posicao_param;
		}
		
		if(estaNaAreaUtil(posicao.x + objeto_param.getSombra()._x, 
						  posicao.y + objeto_param.getSombra()._y)
		   and estaNaAreaUtil(posicao.x + objeto_param.getSombra()._x + objeto_param.getSombra()._width, 
							  posicao.y + objeto_param.getSombra()._y + objeto_param.getSombra()._height)){
			return true;
		} else {
			return false;
		}
	}

	
}
