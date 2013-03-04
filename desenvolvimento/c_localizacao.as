import flash.geom.Point;

/*
* Símbolo que indica nomes de objetos no mapa, detalhamento de textos truncados, etc.
* A cada objeto é permitido somente um objeto de localização.
* Objetos de localização são criados com criarPara e destruídos com destruirDe.
*/
class c_localizacao extends MovieClip{
//dados	
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "localizacao";
	
//métodos
	/*
	* Cria um objeto de localização que mantém-se até o momento em que o mouse é retirado do objeto passado como parâmetro.
	* @param objeto_param O objeto que possuirá o objeto de localização.
	* @param mensagem_param A mensagem a ser exibida.
	* @param posicao_param Posição, no sistema de coordenadas do objeto, onde deve ficar o objeto de localização.
	*/
	public static function criarPara(objeto_param:MovieClip, mensagem_param:String, posicao_param:Point):Void{
		var nomeObjetoLocalizacao:String = getNomeObjetoLocalizacao(objeto_param);
		if(posicao_param != undefined){
			objeto_param.attachMovie(LINK_BIBLIOTECA, nomeObjetoLocalizacao, _root.getNextHighestDepth(), {_x:posicao_param.x, _y:posicao_param.y});
		} else {
			objeto_param.attachMovie(LINK_BIBLIOTECA, nomeObjetoLocalizacao, _root.getNextHighestDepth(), {_x:0, _y:0});
		}
		objeto_param[nomeObjetoLocalizacao].atualizarMensagem(mensagem_param);
		objeto_param[nomeObjetoLocalizacao]._visible = false;

		objeto_param.onRollOver = function(){ 
			var nomeObjetoLocalizacao:String = getNomeObjetoLocalizacao(this);
			this[nomeObjetoLocalizacao].swapDepths(_parent.getNextHighestDepth());
			this[nomeObjetoLocalizacao]._visible = true;
		};

		objeto_param.onRollOut = function(){ 
			var nomeObjetoLocalizacao:String = getNomeObjetoLocalizacao(this);
			this[nomeObjetoLocalizacao]._visible = false;
		};
		
		objeto_param[nomeObjetoLocalizacao]._width *= 1.5;
		objeto_param[nomeObjetoLocalizacao]._height *= 1.5;
	}
	
	/*
	* Destrói, se houver, o objeto de localização do objeto passado como parâmetro.
	*/
	public static function destruirDe(objeto_param:MovieClip):Void{
		var nomeObjetoLocalizacao:String = getNomeObjetoLocalizacao(objeto_param);
		objeto_param[nomeObjetoLocalizacao].removeMovieClip();
	}
	
	/*
	* Retorna o objeto de localização do objeto, se existir.
	* Caso contrário, retorna undefined.
	*/
	public static function getObjetoLocalizacao(objeto_param:MovieClip):c_localizacao{
		var nomeObjetoLocalizacao:String = getNomeObjetoLocalizacao(objeto_param);
		return objeto_param[nomeObjetoLocalizacao];
	}
	
	/*
	* Dado um objeto, retorna o nome de seu objeto de localização, caso possua algum.
	*/
	private static function getNomeObjetoLocalizacao(objeto_param:MovieClip):String{
		return objeto_param._name.concat(LINK_BIBLIOTECA);
	}
	
	/*
	* Atualiza a mensagem deste objeto de localização.
	*/
	public function atualizarMensagem(mensagem_param:String):Void{
		var novoComprimentoLocalCaixa:Number;
		this['nome'].autoSize = "center";
		this['nome'].text = mensagem_param;
		novoComprimentoLocalCaixa = 5+(this['nome'].textWidth)+5;
		this['localCaixa']._x = -novoComprimentoLocalCaixa/2;
		this['localCaixa']._width = novoComprimentoLocalCaixa;
	}
	
	
}
