import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

class c_aviso_espera extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "bt_aguardar_bd";

	//---- Mensagem
	private var mensagem:String = new String();
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		
		/*Inicializações*/
		mensagem = new String();
		atualizarMensagem("");
	}
	
	/*
	* Cria um aviso com a mensagem passada de parâmetro.
	*/
	public static function criarPara(objeto_param:MovieClip, mensagem_param:String, posicao_param:Point):Void{
		var nomeAviso:String = getNomeAviso(objeto_param);
		objeto_param.attachMovie(c_aviso_espera.LINK_BIBLIOTECA, nomeAviso, objeto_param.getNextHighestDepth()+500, {_x:Stage.width/2 - objeto_param._x, 
								 																				     _y:Stage.height/2 - objeto_param._y});
		objeto_param[nomeAviso].inicializar();
		objeto_param[nomeAviso]._x -= objeto_param[nomeAviso]._width/2;
		objeto_param[nomeAviso]._y -= objeto_param[nomeAviso]._height/2;
		objeto_param[nomeAviso].chamar(mensagem_param);
		
		if(posicao_param!=undefined){
			objeto_param[nomeAviso]._x = posicao_param.x;
			objeto_param[nomeAviso]._y = posicao_param.y;
		}
	}
	
	/*
	* Destrói, se houver, o aviso de espera do objeto passado como parâmetro.
	*/
	public static function destruirDe(objeto_param:MovieClip):Void{
		var nomeAviso:String = getNomeAviso(objeto_param);
		objeto_param[nomeAviso].removeMovieClip();
	}
	
	/*
	* Retorna o objeto aviso de espera do objeto, se existir.
	* Caso contrário, retorna undefined.
	*/
	public static function getAviso(objeto_param:MovieClip):c_aviso_espera{
		var nomeAviso:String = getNomeAviso(objeto_param);
		return objeto_param[nomeAviso];
	}
	
	/*
	* Dado um objeto, retorna o nome de seu aviso de espera, caso possua algum.
	*/
	private static function getNomeAviso(objeto_param:MovieClip):String{
		return objeto_param._name.concat(LINK_BIBLIOTECA);
	}
	
	/*
	* Chama o aviso, mostrando-o na tela com a mensagem passada de parâmetro.
	*/
	public function chamar(mensagem_param:String):Void{
		atualizarMensagem(mensagem_param);
		_visible = true;
	}
	
	/*
	* Destrói este aviso.
	*/
	public function esconder():Void{
		_visible = false;
		removeMovieClip(this);
	}
	
	/*
	* Atualiza a mensagem mostrada pelo aviso ao usuário.
	*/
	private function atualizarMensagem(mensagem_param:String):Void{
		this['aviso'].text = mensagem_param;
		mensagem = mensagem_param;
	}

	
	
}