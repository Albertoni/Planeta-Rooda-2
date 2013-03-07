import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

class c_aviso_com_ok extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "btAdmAlerta";
	
	/*
	* Nome da propridade de _root que conterá o nome do aviso que tem o foco do teclado.
	*/
	private static var avisoComFocoDoTeclado:String = "aviso"+LINK_BIBLIOTECA;

	//---- Botões
	private var botaoOk:c_btOk;
	private var POSX_BOTAO_OK:Number = -65.95;
	private var POSY_BOTAO_OK:Number = 16.8;
	private static var DISTANCIA_BOTAO_BORDA:Number = 10;

	//---- Texto
	private static var DISTANCIA_TEXTO_DESENHO:Number = 30;
	private static var DISTANCIA_TEXTO_BORDA:Number = 20;		

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

	//---- Estrutura
	public var desenho:c_desenhoAviso;

//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		
		/*Inicializações*/
		attachMovie(c_desenhoAviso.LINK_BIBLIOTECA, "desenho_dummy", getNextHighestDepth(), {_x:0, _y:0});
		desenho = this['desenho_dummy'];
		desenho.swapDepths(this['texto']);
		
		atualizarMensagem("");
		
		attachMovie("btOk", "botaoOk", getNextHighestDepth());
		botaoOk.inicializar();
		botaoOk._x = POSX_BOTAO_OK;
		botaoOk._y = POSY_BOTAO_OK;
		botaoOk.addEventListener("btOk", Delegate.create(this, esconder));	
		
		this['texto'].autoSize = true;
		Key.addListener(this);
		onKeyDown = function(){
			switch (Key.getCode()){
				case Key.ENTER: 
						if(_root[avisoComFocoDoTeclado] == this){ 
							esconder(); 
						}
					break;
			}
		}
		
		desenho.onPress = function(){
			var terreno:c_terreno = _root.planeta.getTerrenoEmQuePersonagemEstah();
			terreno.definirPermissaoMovimentoMp(false);
			_root[avisoComFocoDoTeclado] = _parent;
			_parent._alpha = 50;
			_parent.startDrag();
			_parent.swapDepths(_parent._parent.getNextHighestDepth());
			_root.ponteiro.swapDepths(_root.getNextHighestDepth());
		}
		desenho.onMouseUp = function(){
			var estahSoltandoOAviso:Boolean = _parent._alpha<100;
			if(estahSoltandoOAviso){
				var terreno:c_terreno = _root.planeta.getTerrenoEmQuePersonagemEstah();
				terreno.definirPermissaoMovimentoMp(true);
			}
			_parent._alpha = 100;
			_parent.stopDrag();
		}
	}
	
	/*
	* Cria um aviso com a mensagem passada de parâmetro, o qual soma ao ter seu botão ok pressionado.
	*/
	public static function mostrar(mensagem_param:String, posicao_param:Point):Void{
		if(_root.mostrarAviso == undefined){
			_root.mostrarAviso = function(mensagem_param:String, posicao_param:Point){
				var nomeAviso:String = "aviso";
				var aviso:c_aviso_com_ok;
				var i:Number = 0;
				do{
					i++;
					nomeAviso = "aviso" + i;
				}while(this[nomeAviso] != undefined);
				attachMovie(LINK_BIBLIOTECA, nomeAviso, _root.getNextHighestDepth());
				aviso = this[nomeAviso];
				aviso.inicializar();
				aviso.chamar(mensagem_param);
				if(posicao_param == undefined){
					aviso._x = Stage.width/2 - aviso._width/2;
					aviso._y = Stage.height/2 - aviso._height/2;
				} else {
					aviso._x = posicao_param.x - aviso._width/2;
					aviso._y = posicao_param.y - aviso._height/2;
				}
			}
		}
		_root.mostrarAviso(mensagem_param, posicao_param);
	}
	
	/*
	* Chama o aviso, mostrando-o na tela com a mensagem passada de parâmetro.
	*/
	public function chamar(mensagem_param:String):Void{
		atualizarMensagem(mensagem_param);
		_root.ponteiro.swapDepths(_root.getNextHighestDepth());
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
		var tamanhoTexto:Object = this['texto'].getTextFormat().getTextExtent(mensagem_param);
		this['texto'].text = mensagem_param;
		desenho.redimensionar(this['texto']._width + DISTANCIA_TEXTO_DESENHO, 
							  this['texto']._height + DISTANCIA_TEXTO_BORDA 
							  + botaoOk._height + DISTANCIA_BOTAO_BORDA);
		reposicionarBotao();
		reposicionarTexto();
	}
	
	/*
	* Coloca o botão em sua posição certa, considerando redimensionamento deste aviso.
	*/
	private function reposicionarBotao():Void{
		botaoOk._x = desenho._x + desenho._width/2 - botaoOk._width/2;
		botaoOk._y = desenho._y + desenho._height
					 - botaoOk._height - DISTANCIA_BOTAO_BORDA;
	}
	
	/*
	* Centraliza o texto.
	*/
	private function reposicionarTexto():Void{
		this['texto']._x = desenho._x + desenho._width/2 - this['texto']._width/2;
		this['texto']._y = desenho._y + DISTANCIA_TEXTO_BORDA;
	}
}