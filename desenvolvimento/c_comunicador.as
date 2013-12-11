/**
 * ...
 * @author ...
 */

import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
 
dynamic class c_comunicador extends MovieClip {	
    private var barra_rolagem:c_barra_rolagem;
	public var fala:TextField;
	private var botaoEnviar:MovieClip;
	private var redimensionador:MovieClip;
	private var header:MovieClip;    //menu aonde seleciona-se chat terreno, contato ou grupo
	private var fundo:MovieClip;	
	private var organizador_caixas_texto:c_organizador_caixas_texto;
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	
	function c_comunicador(Void) {
		EventDispatcher.initialize(this);
		
		this.fala.text = "";
		this.attachMovie("barraRolagem", "barra_rolagem", this.getNextHighestDepth());		
		this.barra_rolagem._y = this.redimensionador._y + this.redimensionador._height; //+ this.redimensionar._height;
		this.barra_rolagem._x = this._width - this.barra_rolagem._width - 3;
		this.barra_rolagem.addEventListener("scrollMoveu", Delegate.create(this, this.barra_scroll_moveu));	
		
		this.organizador_caixas_texto = new c_organizador_caixas_texto(targetPath(this), this);		

		this.organizador_caixas_texto.nova_caixa_texto("conversa", -1, "conversa");			
		

        Key.addListener(this.fala);                 //inicializacao do listener		
		this.fala["comunicador"] = this;
		this.fala.onKeyDown = function() {
			if ((Key.getCode() == Key.ENTER) and (Selection.getFocus() == targetPath(this))) {					
				if (this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa() == "conversa"){
				    if ((mp.fala.timeout != -1) and (mp.fala.timeout != undefined)) {
					    clearTimeout(mp.fala.timeout);
					    mp.fala.timeout = -1;
				    }
			        mp.fala.text = this.text;							
				    this.comunicador.setTimeoutApagarFala(4000);				
				}
				this.comunicador.organizador_caixas_texto.addTexto(this.text, 
				                                                   this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa(),
																   this.comunicador.organizador_caixas_texto.get_tipo_caixa_texto(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa()));				

				this.comunicador.barra_rolagem.init_barra_rolagem( this.comunicador.organizador_caixas_texto.get_numero_linhas_total(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa(),
				                                                                                                                     this.comunicador.organizador_caixas_texto.get_tipo_caixa_texto(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa())),
																   this.comunicador.organizador_caixas_texto.get_numero_linhas_visivel(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa(),
				                                                                                                                       this.comunicador.organizador_caixas_texto.get_tipo_caixa_texto(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa())));
				
			    this.text = "";				
			}
		}		
		this.botaoEnviar["comunicador"] = this;
		this.botaoEnviar.onRelease = function() {
			if (this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa() == "conversa"){
				if ((mp.fala.timeout != -1) and (mp.fala.timeout != undefined)) {
				    clearTimeout(mp.fala.timeout);
				    mp.fala.timeout = -1;
				}
			    mp.fala.text = this.comunicador.fala.text;							
				this.comunicador.setTimeoutApagarFala(4000);				
			}
			this.comunicador.organizador_caixas_texto.addTexto(this.comunicador.fala.text, 
			                                                   this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa(),
															   this.comunicador.organizador_caixas_texto.get_tipo_caixa_texto(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa()));				

			this.comunicador.barra_rolagem.init_barra_rolagem( this.comunicador.organizador_caixas_texto.get_numero_linhas_total(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa(),
			                                                                                                                     this.comunicador.organizador_caixas_texto.get_tipo_caixa_texto(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa())),
															   this.comunicador.organizador_caixas_texto.get_numero_linhas_visivel(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa(),
			                                                                                                                       this.comunicador.organizador_caixas_texto.get_tipo_caixa_texto(this.comunicador.organizador_caixas_texto.get_caixa_texto_ativa())));
				
			this.comunicador.fala.text = "";	
		}
		
		this.inicializar_header("comunicadorMenu");
		
		this.header.botaoPrincipal.nBotoesPiscando = 0;
		this.header.botaoPrincipal["comunicador"] = this;
		this.header.botaoPrincipal.onPress = function() {			
			if ((this.interval != -1) and (this._parent._currentframe == 1 )){
				clearInterval(this.interval);
				this.interval = -1;
				this.gotoAndStop(1);				
			}
			else if ((this._parent._currentframe != 1) and (this.interval == -1) and (this.nBotoesPiscando > 0)) {
				this.interval = setInterval(this._parent.comunicador_menu, "alternar_frame", 1000, this);
			}
			this.comunicador.header.play();				
		}
		
		
		//this._parent._parent._parent.organizador_caixas_texto.addEventListener("recebidoChat" + this.tipoContato, Delegate.create(this, this.piscar_botao_begin));	
		/*
		this.organizador_caixas_texto.addEventListener("recebidoChatconversa", Delegate.create(this, this.recebidoChat));
		this.organizador_caixas_texto.addEventListener("recebidoChatcontato", Delegate.create(this, this.recebidoChat));
		this.organizador_caixas_texto.addEventListener("recebidoChatgrupo", Delegate.create(this, this.recebidoChat));		
		*/
		//this.header.enabled = false;
		
		//adicionar funcao de redimensionamento do comunicador
		
		//adicionar evendo de drag do redimensionar
		
		
		
	}
	
	private function barra_scroll_moveu(eventoScroll:Object):Void {	
		
		this.organizador_caixas_texto.set_posicao_scroll(eventoScroll.inicioSubLista+1,
		                                                 this.organizador_caixas_texto.get_caixa_texto_ativa(),
														 this.organizador_caixas_texto.get_tipo_caixa_texto(this.organizador_caixas_texto.get_caixa_texto_ativa()));
														 
		//    evento.inicioSubLista:Number -> posicao da lista que deve ser utilizado como posicao inicial da subLista
        //    evento.fimSubLista:Number    -> posicao da lista que deve ser utilizada como posicao final da sublista
	}
	
	private function inicializar_header(nomeSimboloBiblioteca:String) {
		var init_header:Object;
		this.header.botaoPrincipal.interval = -1;
		init_header = new Object( { _x: this.header.botaoPrincipal._x,
		                            _y: (this.header.botaoPrincipal._y + this.header.botaoPrincipal._height) ,
									_visible: false									
								} );
		this.header.attachMovie(nomeSimboloBiblioteca, "comunicador_menu", this.header.getNextHighestDepth(), init_header);		
	}
	
	private function setTimeoutApagarFala(tempo) {		
		mp.fala.timeout = setTimeout(apagarMpFala, tempo);
	}
	private function apagarMpFala() {
		mp.fala.text = "";
		mp.fala.timeout = -1;
	}
	
}