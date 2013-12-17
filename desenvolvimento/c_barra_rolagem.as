/**-------------------------------------------------------------------------------------------------------------------------
 * classe que implementa uma barra de rolagem
 * uma barra de rolagem eh um movieclip que possui como componentes uma barra(barra_scroll:MovieClip),
 * um caminho pelo qual a barra anda (track:MovieClip), um botao para subir (botao_scroll_up:MovieClip) e 
 * um botao para descer(botao_scroll_down:MovieClip).
 * Ela eh um subclasse de MovieClip, logo temque ser criada usando os mesmos metodos que um MovieClip.
 * Apos ter sido criada ela deve ser inicializada com a funcao init_barra_rolagem. Apos sua inicializacao,
 * sempre que a barra de scroll se mover o suficiente ou algum dos botoes for pressionado a classe enviarah um evento
 * atraves do eventDispatcher "scrollMoveu", que conterah o subArray que deverah ser mostrado pela classe responsavel
 * pela barra de rolagem.
 * 
 * forma de uso:
 * import mx.utils.Delegate;
 * 
 * criar as variaveis:
 *  public var addEventListener:Function;
 *  public var removeEventListener:Function;
 *	public var dispatchEvent:Function;   
 * 
 * se a barra de rolagem nao existe ainda{
 *     <MovieClip>.attachMovie(<nomeSimboloBiblioteca:String>, <nomeVariavelBarraRolagem>, Depth:Number);	
 * }
 * <nomeVariavelBarraRolagem>.addEventListener("scrollMoveu", Delegate.create(this, this.<funcaoParaTratarOEvento>));	
 * <nomeVariavelBarraRolagem>.init_barra_rolagem(<tamanhoLista:Number>, <tamanhoSubLista:Number>);
 * 
 * function <funcaoParaTratarOEvento>(evento:Object){
 *     //atributos passados sao:
 *     //    evento.inicioSubLista:Number -> posicao da lista que deve ser utilizado como posicao inicial da subLista
 *     //    evento.fimSubLista:Number    -> posicao da lista que deve ser utilizada como posicao final da sublista
 * }   
 * 
 * @author Roger Nobre de Gouveia
 * ---------------------------------------------------------------------------------------------------------------------------
 */
import mx.events.EventDispatcher;

dynamic class c_barra_rolagem extends MovieClip {
	private var barra_scroll:MovieClip;
	private var botao_scroll_up:MovieClip;
	private var botao_scroll_down:MovieClip;
	private var trackUp:MovieClip;
	private var track:MovieClip;
	private var trackDown:MovieClip;	
	private var fundo:MovieClip;
	private var tamanhoScroll:Number;
	private var tamanhoLista:Number;
	private var tamanhoSubLista:Number;
	private var posicaoScroll:Number;
	private var ultimaPosX:Number;
	private var ultimaPosY:Number;
	
	public var addEventListener:Function;
    public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	function c_barra_rolagem() {	
		EventDispatcher.initialize(this);						
		//this.addEventListener("scroll", Delegate.create(this, reage));			
		//this.lista = new Array();	
		this.posicaoScroll = 0;
		this.ultimaPosX = this._x;
		this.ultimaPosY = this._y;		
		this.barra_scroll["barra_rolagem"] = this;
		this.botao_scroll_down["barra_rolagem"] = this;
		this.botao_scroll_up["barra_rolagem"] = this;
		
		this.setLimitesBarraScroll();
		
		this.barra_scroll.onPress = function(){
	        startDrag(this, false, this._x , this.limiteSuperiorScroll , this._x , this.limiteInferiorScroll);
	        this.scrollPress = true;
        }
		this.barra_scroll.onMouseUp = function() {
			if (this.scrollPress == true){
	            this.stopDrag();	        
	            this.scrollPress = false;
			}
        }
	}
	
	private function setLimitesBarraScroll(Void):Void {				
		this.barra_scroll.limiteSuperiorScroll = this.trackUp._y;		
		//this.barra_scroll.limiteInferiorScroll = this.track._y + this.track._height + this.trackDown - this.barra_scroll._height;		
		this.barra_scroll.limiteInferiorScroll = this.trackDown._y + this.trackDown._height - this.barra_scroll._height;
		this.tamanhoScroll = this.barra_scroll.limiteInferiorScroll - this.barra_scroll.limiteSuperiorScroll;		
	}	
    /*------------------------------------------------------------------------------------------------------------
	 * funcao que dispara um evento com a sublista que deve ser mostrada pela classe responsavel pela barra de rolagem
	 * ------------------------------------------------------------------------------------------------------------
	*/ 	
	private function scrollMoveu(posPrimeiroElemento:Number, posUltimoElemento:Number):Void {		
		if (posUltimoElemento != undefined){
		    dispatchEvent( { target:this, type:"scrollMoveu", inicioSubLista: posPrimeiroElemento, fimSubLista: posUltimoElemento } );
		}
		else {
			dispatchEvent({target:this, type:"scrollMoveu", inicioSubLista: posPrimeiroElemento});
		}
	}	
	
	/*-------------------------------------------------------------------------------------------------------------
	 * inicializa a barra de rolagem sao passados como parametro a lista sobre o que o scroll vai andar e o tamanho da sublista que o scroll deve retornar. A funcao implementa todos os eventos necessarios para seus sub-componentes. Quando o scroll se move o suficiente ele envia um evento com o subarray que deve ser mostrado por sua classe responsavel
	 * --------------------------------------------------------------------------------------------------------------
	 */
	public function init_barra_rolagem(tamanhoLista:Number, tamanhoSubLista:Number) {		
		this.setLimitesBarraScroll();		
		this.barra_scroll.onPress = function(){
	        startDrag(this, false, this._x , this.limiteSuperiorScroll , this._x , this.limiteInferiorScroll);
	        this.scrollPress = true;
        }		
		this.tamanhoLista = tamanhoLista;		
		this.tamanhoSubLista = tamanhoSubLista;
		//mp.fala.text = "tamanhoLista=" + tamanhoLista + ",tamanhoSubLista=" + tamanhoSubLista;
		this.barra_scroll.onMouseMove = function() {
			if (this.scrollPress == true) {					
				if ((this.barra_rolagem.ultimaPosX != this.barra_rolagem._x) or 
				    (this.barra_rolagem.ultimaPosY != this.barra_rolagem._y)) {
						this.barra_rolagem.setLimitesBarraScroll();
			    }
				
				var unidade:Number;
				if (this.barra_rolagem.tamanhoSubLista != undefined){				    
				    unidade = (this.barra_rolagem.tamanhoScroll) /
				              (this.barra_rolagem.tamanhoLista - this.barra_rolagem.tamanhoSubLista + 1);
					//unidade--;
				}
				//else {
				//	unidade = this.barra_rolagem.tamanhoScroll / this.barra_rolagem.tamanhoLista;
				//}				
				var temp:Number = (this._y - this.limiteSuperiorScroll) / (unidade);				
				if (temp < 0){
					temp = 0;
				}
				temp = Math.floor(temp);
				//temp--;
				if (this.barra_rolagem.posicaoScroll != temp) {
					this.barra_rolagem.posicaoScroll = temp;
					
					if (this.barra_rolagem.tamanhoSubLista != undefined){
					    this.barra_rolagem.scrollMoveu(this.barra_rolagem.posicaoScroll,
					                                   this.barra_rolagem.posicaoScroll + this.barra_rolagem.tamanhoSubLista);
					}
					//mp.fala.text = this.barra_rolagem.posicaoScroll + "," + this.barra_rolagem.tamanhoSubLista + "," + this.barra_rolagem.tamanhoLista + "," + temp;
					//else {
					//	this.barra_rolagem.scrollMoveu(this.barra_rolagem.posicaoScroll);
					//}
				}
			}
		}
		this.botao_scroll_down.unidade = (this.tamanhoScroll) /
										 (this.tamanhoLista - this.tamanhoSubLista);
		this.botao_scroll_down.onPress = function() {			
			if (this.barra_rolagem.tamanhoSubLista == undefined) { this.barra_rolagem.tamanhoSubLista = 0 }
			
			if (this.barra_rolagem.tamanhoSubLista < this.barra_rolagem.tamanhoLista){
				//if para setar corretamente os limites da barra de scroll caso a barra de rolagem tenha sido movida				
				if ((this.barra_rolagem.ultimaPosX != this.barra_rolagem._x) or 
					(this.barra_rolagem.ultimaPosY != this.barra_rolagem._y)) {
						this.barra_rolagem.setLimitesScroll();
						this.unidade = (this.barra_rolagem.tamanhoScroll) /
									   (this.barra_rolagem.tamanhoLista - this.barra_rolagem.tamanhoSubLista);
				}
			
				if ((this.unidade * (this.barra_rolagem.posicaoScroll + 1)) <= (this.barra_rolagem.tamanhoScroll)) {			
					this.barra_rolagem.posicaoScroll++;				
					this.barra_rolagem.barra_scroll._y = (this.barra_rolagem.posicaoScroll * this.unidade) + 
														  this.barra_rolagem.barra_scroll.limiteSuperiorScroll;																		                           							
					this.barra_rolagem.scrollMoveu(this.barra_rolagem.posicaoScroll,
												   this.barra_rolagem.posicaoScroll + this.barra_rolagem.tamanhoSubLista);
				}	
			}			
        }
		
		this.botao_scroll_up.unidade = (this.tamanhoScroll) /
									   (this.tamanhoLista - this.tamanhoSubLista);		
        this.botao_scroll_up.onPress = function() {	
			if (this.barra_rolagem.tamanhoSubLista == undefined){ this.barra_rolagem.tamanhoSubLista = 0 }
			if (this.barra_rolagem.tamanhoSubLista < this.barra_rolagem.tamanhoLista){
			    //if para setar corretamente os limites da barra de scroll caso a barra de rolagem tenha sido movida
			    if ((this.barra_rolagem.ultimaPosX != this.barra_rolagem._x) or 
			        (this.barra_rolagem.ultimaPosY != this.barra_rolagem._y)) {
					    this.barra_rolagem.setLimitesScroll();
					    this.unidade = (this.barra_rolagem.tamanhoScroll) /
						               (this.barra_rolagem.tamanhoLista - this.barra_rolagem.tamanhoSubLista);		
			    }			
			    if (this.barra_rolagem.posicaoScroll -1 >= 0) {
				    this.barra_rolagem.posicaoScroll--;				
				    this.barra_rolagem.barra_scroll._y = (this.barra_rolagem.posicaoScroll * this.unidade) + 
	    		                                          this.barra_rolagem.barra_scroll.limiteSuperiorScroll;
				    this.barra_rolagem.scrollMoveu(this.barra_rolagem.posicaoScroll,
				                                   this.barra_rolagem.posicaoScroll + this.barra_rolagem.tamanhoSubLista);
			    }			
			}			
        }		
	}
	
	/*------------------------------------------------------------------------------------------------------------------
	 * modifica o tamanho da barra de rolagem o numero utilizado como parametro define o novo tamanho do this.track:MovieClip, que eh o que define os limites do this.barra_scroll:MovieClip
	 * ---------------------------------------------------------------------------------------------------------------
	 */ 
	public function set_tamanho_barra_rolagem(novoTamanho:Number):Boolean {		
		//this.fundo estah na posicao (0,0) da instancia da classe e tem a mesma altura e largura
		var tamanhoParteEstatica:Number = this.fundo._height - this.track._height;
		//2 aki foi utilizado como valor arbitrario. Talvez possa ser melhor pensado no futuro
		if (novoTamanho < (2 * tamanhoParteEstatica)) {
			mp.fala.text = "iiiih";
			return false;
		}
		else {			
			var temp:Number = this.botao_scroll_down._y - (this.track._y + this.track._height); //espaco estatico entre o botao_scroll_down e a parte inferior do track
			this.track._height = novoTamanho - tamanhoParteEstatica;
			this.trackDown._y = this.track._y + this.track._height;
			this.botao_scroll_down._y = this.track._y + this.track._height + temp;
			
			this.fundo._height = this.track._height + tamanhoParteEstatica;
			
			this.init_barra_rolagem(this.tamanhoLista, this.tamanhoSubLista);			
			return true;
		}
	}
	
}