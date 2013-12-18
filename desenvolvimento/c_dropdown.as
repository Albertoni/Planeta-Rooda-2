import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

/*
* Um menu do tipo dropdown, que permite adição e remoção de itens.
* Possui uma barra, a qual abre um select quando pressionada.
*/
class c_dropdown extends MovieClip{
//dados
	/*
	* Link deste objeto na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "dropdown";

	/*
	* Eventos.
	*/
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	/*
	* Barra que dá acesso ao select.
	*/
	private static var NOME_BARRA_ACESSO:String = "barra_acesso";
	public var barra_acesso:c_btSelectAdm;
	
	/*
	* O menu que aparece quando este dropdown abre.
	*/
	private static var NOME_MENU_SELECT:String = "menu_select";
	public var menu_select:c_select;

	/*
	* Controle do mouse fora da estrutura, para que seja fácil de fecha-la.
	* Esta estrutura fecha-se sempre que um click é dado fora dela.
	*/
	private var listenerParaEventosDeMouse:MovieClip;
	public var mouseEstahFora:Boolean = false;
	
//métodos
	public function inicializar(_numeroDeBotoesVisiveis:Number, _textosDeBotoes:Array, _label:String):Void{
		/*this.onRollOver = function(){
			mouseEstahFora = false;
			//c_aviso_com_ok.mostrar("Dentro");
		}
		this.onRollOut = function(){
			mouseEstahFora = true;
			//c_aviso_com_ok.mostrar("fora");
		}
		this.onMouseDown = function(){
			if(mouseEstahFora){
				menu_select._visible = false;
			} else {
				mostrarSelect();
			}
		}*/
		
		
		var formatoLabel:TextFormat;
		formatoLabel = this['label'].getNewTextFormat();
		formatoLabel.bold = true;
		this['label'].setNewTextFormat(formatoLabel);
		this['label'].replaceText(0, _label.length-1, _label);
		mouseEstahFora = false;
		
		attachMovie(c_btSelectAdm.LINK_BIBLIOTECA, NOME_BARRA_ACESSO, this.getNextHighestDepth());
		barra_acesso = this[NOME_BARRA_ACESSO];
		barra_acesso.inicializar();
		barra_acesso.addEventListener("btPressionado", Delegate.create(this, mostrarSelect));
		
		attachMovie(c_select.LINK_BIBLIOTECA, NOME_MENU_SELECT, getNextHighestDepth());
		menu_select = this[NOME_MENU_SELECT];
		menu_select.inicializar(_numeroDeBotoesVisiveis, _textosDeBotoes, new String());
		menu_select.addEventListener("botaoPressionado", Delegate.create(this, botaoPressionado));
		menu_select.setTipoInvisivel(true);
		menu_select.fundo._visible = true;
		menu_select.redimensionar(384.35, menu_select._height);
		menu_select._visible = false;
		
		createEmptyMovieClip("listenerParaEventosDeMouse_dummy", getNextHighestDepth());
		listenerParaEventosDeMouse = this['listenerParaEventosDeMouse_dummy'];
		listenerParaEventosDeMouse.onMouseDown = function(){
			var mouseEstahFora:Boolean = false;
			var acertouSelect:Boolean = _parent.menu_select._x <= _parent._xmouse and _parent._xmouse <= _parent.menu_select._x + _parent.menu_select._width
										and _parent.menu_select._y <= _parent._ymouse and _parent._ymouse <= _parent.menu_select._y + _parent.menu_select._height;
			var acertouCaixaTexto:Boolean = _parent.barra_acesso._x <= _parent._xmouse and _parent._xmouse <= _parent.barra_acesso._x + _parent.barra_acesso._width
										and _parent.barra_acesso._y <= _parent._ymouse and _parent._ymouse <= _parent.barra_acesso._y + _parent.barra_acesso._height;
			var estahForaComSelect = _parent.menu_select._visible  and !acertouSelect and !acertouCaixaTexto;
			var estahForaSemSelect = !_parent.menu_select._visible and !acertouCaixaTexto;
			mouseEstahFora = estahForaComSelect or estahForaSemSelect;
			if(mouseEstahFora){
				_parent.menu_select._visible = false;
			} else {
				_parent.mostrarSelect();
			}
		}
	}
	
	/*
	* Mostra o menu_select logo abaixo da barra_acesso.
	*/
	public function mostrarSelect():Void{
		menu_select._x = barra_acesso._x;
		menu_select._y = barra_acesso._y + barra_acesso._height;
		menu_select._visible = true;
	}
	
	/*
	* Indica se há um botão pressionado.
	*/
	public function haBotaoPressionado():Boolean{
		return menu_select.haBotaoPressionado();
	}
	
	/*
	* Retorna todas as opções contidas neste menu.
	* @return Array de strings em cada elemento corresponde a uma opção.
	*/
	public function getListaOpcoes():Array{
		return menu_select.getListaOpcoes();
	}
	
	/*
	* @return A opção que está selecionada. Se não houver, undefined.
	*/
	public function getOpcaoSelecionada():String{
		return menu_select.getOpcaoSelecionada();
	}
	
	/*
	* Sendo a lista de opções ordenada, retorna o índice nesta lista da opção que estiver selecionada.
	* Se não houver, retorna undefined.
	* @return O índice da opção selecionada ou undefined.
	*/
	public function getIndiceOpcaoSelecionada():Number{
		return menu_select.getIndiceOpcaoSelecionada();
	}
	
	/*
	* @param opcao_param Opção procurada na lista.
	* @return Booleano indicando que se a opção procurada está na lista.
	*/
	public function existeOpcao(opcao_param:String):Boolean{
		return menu_select.existeOpcao(opcao_param);
	}
	
	/*
	* Insere uma opção no final do menu.
	* @param opcao_param A opção que deseja-se inserir na lista.
	*/
	public function inserirOpcao(opcao_param:String):Void{
		menu_select.inserirOpcao(opcao_param);
	}
	
	/*
	* Substitui a primeira ocorrência de uma opção por outra.
	* @param opcaoAtual_param Opção que será removida.
	* @param novaOpcao_param Opção que substituirá a outra.
	*/
	public function substituirOpcao(opcaoAtual_param:String, novaOpcao_param:String):Void{
		menu_select.substituirOpcao(opcaoAtual_param, novaOpcao_param);
	}
	
	/*
	* Remove a primeira ocorrência da opção da lista de opções.
	* @param opcao_param Opção a ser removida.
	*/
	public function retirarOpcao(opcao_param:String):Void{
		menu_select.retirarOpcao(opcao_param);
	}
	
	/*
	* Função executada toda vez em que um botão é pressionado.
	* @param evento_botao_param O evento recebido.
	*/
	private function botaoPressionado(evento_botao_param:Object):Void{
		menu_select._visible = false;
		barra_acesso.atualizar_mensagem(menu_select.getOpcaoSelecionada());
		dispatchEvent({target:this, type:"botaoPressionado", posicaoTexto : evento_botao_param.posicaoTexto});
	}
	
	/*
	* Muda o texto que aparece na caixa de texto que abre/fecha o dropdown.
	* @param texto_param Texto que deve aparecer na barra.
	*/
	public function mudarTextoBarraAbertura(texto_param:String):Void{
		barra_acesso.atualizar_mensagem(texto_param);
	}
	
	
	
	
	
	
	
	
}