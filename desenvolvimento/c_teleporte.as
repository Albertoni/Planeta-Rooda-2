import flash.geom.Point;


class c_teleporte extends c_objeto_colisao{
//dados
	/*
	* Link para este objeto na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "teleporte";

	/*
	* Bounding box deste teleporte.
	*/
	private var boundingbox:c_bounding_box = undefined;
	
	/*
	* Ponto central do teleporte (não do objeto gráfico, mas o centro do círculo que o teleporte tem).
	*/
	private var X_CENTRO:Number = 230;
	private var Y_CENTRO:Number = 105;

//métodos
	/*
	* @return Booleao indicando se o objeto de colisão fornecido colide com este teleporte.
	*/
	public function colideCom(objetoColisao_param:c_objeto_colisao):Boolean{
		var boundingBoxDoOutro:c_bounding_box = new c_bounding_box(objetoColisao_param);
		
		if(boundingbox == undefined){
			boundingbox = new c_bounding_box(this);
		} else {
			boundingbox.inicializar(this);
		}
		
		return boundingbox.colideCom(boundingBoxDoOutro);
	}
	
	/*
	* @param posicaoAbsoluta_param Posição absoluta do objeto de colisão com a qual a checagem de colisão será feita.
	* @return Booleao indicando se o objeto de colisão fornecido colide com este teleporte.
	*/
	public function colideComDeslocamento(objetoColisao_param:c_objeto_colisao, posicaoAbsoluta_param:Point):Boolean{
		var posicaoOriginal:Point = new Point(objetoColisao_param._x, objetoColisao_param._y);
		objetoColisao_param._x = posicaoAbsoluta_param.x;
		objetoColisao_param._y = posicaoAbsoluta_param.y;
		var colide:Boolean = colideCom(objetoColisao_param);
		objetoColisao_param._x = posicaoOriginal.x;
		objetoColisao_param._y = posicaoOriginal.y;
		return colide;
	}
	
	/*
	* @return Ponto central do teleporte (não do objeto gráfico, mas o centro do círculo que o teleporte tem).
	*/
	public function getPosicaoCentro():Point{
		return new Point(_x + /*X_CENTRO*/_width/2, _y + /*Y_CENTRO*/_height/2);
	}
	
}