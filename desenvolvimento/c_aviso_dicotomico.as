import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

class c_aviso_dicotomico extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "avisoDicotomico";

	//---- Mensagem
	private var mensagem:String = new String();
	
	//---- Desenho do aviso, a estrutura azul
	public var desenho:MovieClip;
	
	//---- Botões
	private var btEsquerda:c_btGrande;
	private var btDireita:c_btGrande;
	
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
		
		/*Key.addListener(this);
		onKeyDown = function(){
			switch (Key.getCode()){
				case Key.ENTER: esconder();
					break;
			}
		}*/
	}
	
	/*
	* Cria um aviso com a mensagem passada de parâmetro, o qual soma ao ter seu botão ok pressionado.
	* @param objeto_param O objeto que conterá o aviso.
	* @param mensagem_param Texto do aviso.
	* @param posicao_param A posição, em coordenadas de root, em que deve ser exibido.
	* @param opcaoEsquerda_param Texto exibido no botão esquerdo.
	* @param opcaoDireita_param Texto exibido no botão direita.
	* @param acaoEsquerda_param Ação executada ao clicar no botão esquerdo.
	* @param acaoDireita_param Ação executada ao clicar no botão direita.
	* @param escopo_param Escopo em que as ações serão executadas.
	*/
	public static function criarPara(objeto_param:MovieClip, mensagem_param:String, posicao_param:Point,
								   	 opcaoEsquerda_param:String, opcaoDireita_param:String,
								     acaoEsquerda_param:Function, acaoDireita_param:Function, 
								     escopo_param:Object):Void{
		var nomeAviso:String = getNomeAviso(objeto_param);
		var aviso:c_aviso_dicotomico;
		
		if(objeto_param[nomeAviso] != undefined){
			c_aviso_dicotomico.destruirDe(objeto_param);
		}
		
		objeto_param.attachMovie(c_aviso_dicotomico.LINK_BIBLIOTECA, nomeAviso, objeto_param.getNextHighestDepth());
		aviso = objeto_param[nomeAviso];
		aviso.inicializar();
		if(posicao_param == undefined){
			aviso._x = Stage.width/2 - aviso.desenho._width/2;
			aviso._y = Stage.height/2 - aviso.desenho._height/2;
		} else {
			aviso._x = posicao_param.x - aviso.desenho._width/2;
			aviso._y = posicao_param.y - aviso.desenho._height/2;
		} 																		 
		aviso.chamar(mensagem_param, opcaoEsquerda_param, opcaoDireita_param, acaoEsquerda_param, acaoDireita_param, escopo_param);
	}
	
	/*
	* Destrói, se houver, o aviso do objeto passado como parâmetro.
	*/
	public static function destruirDe(objeto_param:MovieClip):Void{
		var nomeAviso:String = getNomeAviso(objeto_param);
		objeto_param[nomeAviso].removeMovieClip();
	}
	
	
	/*
	* Chama o aviso, mostrando-o na tela com a mensagem passada de parâmetro.
	* @param mensagem_param Texto do aviso.
	* @param opcaoEsquerda_param Texto exibido no botão esquerdo.
	* @param opcaoDireita_param Texto exibido no botão direita.
	* @param acaoEsquerda_param Ação executada ao clicar no botão esquerdo.
	* @param acaoDireita_param Ação executada ao clicar no botão direita.
	* @param escopo_param Escopo em que as ações serão executadas.
	*/
	public function chamar(mensagem_param:String, opcaoEsquerda_param:String, opcaoDireita_param:String, acaoEsquerda_param:Function, acaoDireita_param:Function, escopo_param:Object):Void{
		atualizarMensagem(mensagem_param);
		
		createEmptyMovieClip("container_botoes", getNextHighestDepth());
		
		this['container_botoes'].attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEsquerda_dummy", this['container_botoes'].getNextHighestDepth(), {_x:-0.1, _y:65.85});
		btEsquerda = this['container_botoes']['btEsquerda_dummy'];
		btEsquerda.inicializar(opcaoEsquerda_param, 131.95);
		btEsquerda.addEventListener("btPressionado", Delegate.create(escopo_param, acaoEsquerda_param));
		
		this['container_botoes'].attachMovie(c_btGrande.LINK_BIBLIOTECA, "btDireita_dummy", this['container_botoes'].getNextHighestDepth(), {_x:145.9, _y:65.85});
		btDireita = this['container_botoes']['btDireita_dummy'];
		btDireita.inicializar(opcaoDireita_param, 131.95);
		btDireita.addEventListener("btPressionado", Delegate.create(escopo_param, acaoDireita_param));	
		
		this['container_botoes'].setMask(this['mascara_botoes']);
		this['desenho'].swapDepths(getNextHighestDepth());
		this['desenho'].fundo._alpha = 0;
		_root.ponteiro.swapDepths(_root.getNextHighestDepth());
		
		_visible = true;
	}
	
	/*
	* Dado um objeto, retorna o nome de seu aviso dicotômico, caso possua algum.
	*/
	private static function getNomeAviso(objeto_param:MovieClip):String{
		return objeto_param._name.concat(LINK_BIBLIOTECA);
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
		this['caixaMensagem'].text = mensagem_param;
		mensagem = mensagem_param;
	}

	
	
}