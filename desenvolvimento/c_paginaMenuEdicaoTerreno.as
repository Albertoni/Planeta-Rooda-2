import flash.geom.Point;
import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_paginaMenuEdicaoTerreno extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "paginaMenuEdicaoTerreno";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
	
	/*
	* Organiza os botões, quando inseridos com adicionarBotao.
	* Determina em que posição o botão deve ser inserido na próxima chamada de adicionarBotao.
	* Os índices são atribuídos às posição iniciando em 0 (POSICAO_BOTAO_NOROESTE) e terminando em 8 (POSICAO_BOTAO_SUDESTE).
	*/
	private var indiceUltimoBotaoPreenchido;
	
	/*
	* Posições em que botões (ícones de objetos) podem ser inseridos nesta página.
	*/
	public static var POSICAO_BOTAO_NOROESTE:Point;
	public static var POSICAO_BOTAO_NORTE:Point;
	public static var POSICAO_BOTAO_NORDESTE:Point;
	public static var POSICAO_BOTAO_OESTE:Point;
	public static var POSICAO_BOTAO_CENTRO:Point;
	public static var POSICAO_BOTAO_LESTE:Point;
	public static var POSICAO_BOTAO_SUDOESTE:Point;
	public static var POSICAO_BOTAO_SUL:Point;
	public static var POSICAO_BOTAO_SUDESTE:Point;

//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		
		/*POSICAO_BOTAO_NOROESTE = new Point(1.25  -10,0);
		POSICAO_BOTAO_NORTE    = new Point(78.35 -10,0);
		POSICAO_BOTAO_NORDESTE = new Point(157.3 -10,0);
		POSICAO_BOTAO_OESTE    = new Point(1.25  -10,90.4);
		POSICAO_BOTAO_CENTRO   = new Point(78.35 -10,90.4);
		POSICAO_BOTAO_LESTE    = new Point(157.3 -10,90.4);
		POSICAO_BOTAO_SUDOESTE = new Point(1.25  -10,182.4);
		POSICAO_BOTAO_SUL      = new Point(78.35 -10,182.4);
		POSICAO_BOTAO_SUDESTE  = new Point(157.3 -10,182.4);*/
		
		POSICAO_BOTAO_NOROESTE = new Point(157.3 -20,0);
		POSICAO_BOTAO_NORTE    = new Point(157.3 -20,90.4);
		POSICAO_BOTAO_NORDESTE = new Point(157.3 -20,182.4);
		POSICAO_BOTAO_OESTE    = new Point(78.35 -20,0);
		POSICAO_BOTAO_CENTRO   = new Point(78.35 -20,90.4);
		POSICAO_BOTAO_LESTE    = new Point(78.35 -20,182.4);
		POSICAO_BOTAO_SUDOESTE = new Point(1.25  -20,0);
		POSICAO_BOTAO_SUL      = new Point(1.25  -20,90.4);
		POSICAO_BOTAO_SUDESTE  = new Point(1.25  -20,182.4);
		
		indiceUltimoBotaoPreenchido = 0;
	}
	
	/*
	* Adiciona um botão a esta página. O botão não é adicionado se já existir botão na posição!
	* @param classe_botao_param A classe, conforme definido em c_iconeEdicaoTerreno (árvore ou casa).
	* @param terreno_botao_param O tipo de terreno do ícone (tipos definidos em c_terreno_bd).
	* @param tipo_aparencia_botao_param Especifica qual ícone escolher dentro de um subconjunto delimitado pelo classe e tipo de terreno.
	* @param posicao_param Uma das posições que esta classe oferece.
	* @see c_iconeEdicaoTerreno.inicializar(classe_param:String,  terreno_param:String, tipo_aparencia_param:Number);
	*/
	public function adicionarBotaoPosicao(classe_botao_param:String,  terreno_botao_param:String, tipo_aparencia_botao_param:Number, posicao_param:Point):Void{
		var nomeBotao:String = c_paginaMenuEdicaoTerreno.getNomeBotao(posicao_param);
		if(this[nomeBotao] == undefined){
			attachMovie(c_iconeEdicaoTerreno.LINK_BIBLIOTECA, nomeBotao, 
						getNextHighestDepth(), {_x:posicao_param.x, _y:posicao_param.y});
			this[nomeBotao].inicializar(classe_botao_param, terreno_botao_param, tipo_aparencia_botao_param);
			this[nomeBotao].addEventListener("btPressionado", Delegate.create(this, despacharEvento));
		}
	}
	
	/*
	* Análoga à adicionarBotarPosicao. Os botões são adicionados em ordem, começando pelo que fica a noroeste.
	* Utiliza-se um índice interno, que referencia o último botão adicionado com esta função.
	* @param classe_botao_param A classe, conforme definido em c_iconeEdicaoTerreno (árvore ou casa).
	* @param terreno_botao_param O tipo de terreno do ícone (tipos definidos em c_terreno_bd).
	* @param tipo_aparencia_botao_param Especifica qual ícone escolher dentro de um subconjunto delimitado pelo classe e tipo de terreno.
	* @see c_iconeEdicaoTerreno.inicializar(classe_param:String,  terreno_param:String, tipo_aparencia_param:Number);
	* ATENÇÃO: Não misturar chamadas de adicionarBotao com adicionarBotaoPosicao, os botões serão sobrescritos!
	*/
	public function adicionarBotao(classe_botao_param:String,  terreno_botao_param:String, tipo_aparencia_botao_param:Number):Void{
		var posicaoBotao:Point = undefined;
		switch(indiceUltimoBotaoPreenchido){
			case 0: posicaoBotao = POSICAO_BOTAO_NOROESTE;
				break;
			case 1: posicaoBotao = POSICAO_BOTAO_NORTE;
				break;
			case 2: posicaoBotao = POSICAO_BOTAO_NORDESTE;
				break;
			case 3: posicaoBotao = POSICAO_BOTAO_OESTE;
				break;
			case 4: posicaoBotao = POSICAO_BOTAO_CENTRO;
				break;
			case 5: posicaoBotao = POSICAO_BOTAO_LESTE;
				break;
			case 6: posicaoBotao = POSICAO_BOTAO_SUDOESTE;
				break;
			case 7: posicaoBotao = POSICAO_BOTAO_SUL;
				break;
			case 8: posicaoBotao = POSICAO_BOTAO_SUDESTE;
				break;
		}
		
		if(posicaoBotao != undefined){
			adicionarBotaoPosicao(classe_botao_param, terreno_botao_param, tipo_aparencia_botao_param, posicaoBotao);
			indiceUltimoBotaoPreenchido++;
		}
	}
	
	/*
	* Despacha um evento de botão para o pai.
	*/
	private function despacharEvento(evento_param:Object):Void{
		dispatchEvent({target:this, type:"btPressionado", classe: evento_param.classe, terreno: evento_param.terreno, tipo: evento_param.tipo});
	}
	
	/*
	* Retorna o nome que deve ter o botão que estiver na posição dada, independente dele existe ou não.
	* @param posicao_param A posição na qual o botão cujo nome é retornado estaria.
	* @return O nome do botão que deve estar na posição dada.
	*/
	public static function getNomeBotao(posicao_param:Point){
		var nomeBotao:String = "icone"+posicao_param.x+","+posicao_param.y;
		return nomeBotao;
	}
	
	
	
	
	
	
}