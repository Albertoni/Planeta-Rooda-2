import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

/*
* Classe dos ícones do menu de edição do terreno.
* Os ícones estão todos organizados dentro de um movieclip e são acessados via frame.
*/
class c_iconeEdicaoTerreno extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "iconeEdicaoTerreno";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;

	/*
	* Fundo, que pode clarear e escurecer.
	*/
	private var fundo:MovieClip;

	/*
	* Classes de ícones.
	*/
	public static var CLASSE_ARVORES:String = "1";
	public static var CLASSE_CASAS:String = "2";
	public static var CLASSE_PREDIOS:String = "3";
	
	/*
	* Frame em que começam subconjuntos de ícones, com base em suas classes e terrenos.
	*/
	private static var FRAME_INDEFINIDO:Number = 1;
	private static var FRAME_ARVORE_GRAMA:Number = 1;
	private static var FRAME_ARVORE_LAVA:Number = 7;
	private static var FRAME_ARVORE_GELO:Number = 11;
	private static var FRAME_ARVORE_URBANO:Number = 16;
	private static var FRAME_CASA_GRAMA:Number = 21;
	private static var FRAME_CASA_GELO:Number = 21;
	private static var FRAME_PREDIO:Number = 30;
	
	/*
	* Quantidade de elementos em cada subconjunto de ícones.
	*/
	public static var QUANTIDADE_ICONES_ARVORE_GRAMA:Number = 6;
	public static var QUANTIDADE_ICONES_ARVORE_LAVA:Number = 4;
	public static var QUANTIDADE_ICONES_ARVORE_GELO:Number = 5;
	public static var QUANTIDADE_ICONES_ARVORE_URBANO:Number = 5;
	public static var QUANTIDADE_ICONES_CASA_GRAMA:Number = 9;
	public static var QUANTIDADE_ICONES_CASA_GELO:Number = 9;
	
	/*
	* Dados deste ícone.
	*/
	private var classe:String;
	private var terreno:String;
	private var tipo:Number;
	
//métodos
	/*
	* Define a aparência deste ícone. À partir de uma classe e um tipo de terreno, encontra-se um subconjunto dos ícones.
	* Dentro deste subconjunto, tipo_aparencia_param identifica qual ícone escolher. Notar que todo valor de tipo_aparencia_param
	* gerará um ícone deste subconjunto e de forma circular.
	* 		EXEMPLO: Classe Árvore, Terreno Gelo
	* 		Há 4 árvores para o terreno gelo. 
	*		Valores tipo_aparencia_param {..., -3, 1, 5, ...} dão a mesma árvore.
	* @param classe_param A classe, conforme definido nesta classe (árvore ou casa).
	* @param terreno_param O tipo de terreno do ícone (tipos definidos em c_terreno_bd).
	* @param tipo_aparencia_param Especifica qual ícone escolher dentro de um subconjunto delimitado pelo classe e tipo de terreno.
	*/
	public function inicializar(classe_param:String,  terreno_param:String, tipo_aparencia_param:Number){
		mx.events.EventDispatcher.initialize(this);
		
		classe = classe_param;
		terreno = terreno_param;
		tipo = tipo_aparencia_param+1;
		
		/*Listeners*/
		onPress = funcaoOnPress;
		onRollOver = clarear;
		onRollOut = escurecer;
		
		/*Redimensionamento, deve vir antes de c_localizacao.criarPara(...)*/
		_width *= 0.90;
		_height *= 0.90;
		_rotation += 90;
		_x += _width;
		
		
		
		var frame_icone:Number;
		var deslocamento_aparencia:Number = tipo_aparencia_param;
		if(classe_param == CLASSE_ARVORES){
			switch(terreno_param){
				case c_terreno_bd.TIPO_VERDE:
				case c_terreno_bd.TIPO_GRAMA: deslocamento_aparencia %= QUANTIDADE_ICONES_ARVORE_GRAMA;
											  frame_icone = FRAME_ARVORE_GRAMA + deslocamento_aparencia;
					break;
				case c_terreno_bd.TIPO_LAVA: deslocamento_aparencia %= QUANTIDADE_ICONES_ARVORE_LAVA;
											 frame_icone = FRAME_ARVORE_LAVA + deslocamento_aparencia;
					break;
				case c_terreno_bd.TIPO_GELO: deslocamento_aparencia %= QUANTIDADE_ICONES_ARVORE_GELO;
											 frame_icone = FRAME_ARVORE_GELO + deslocamento_aparencia;
					break;
				case c_terreno_bd.TIPO_URBANO: deslocamento_aparencia %= QUANTIDADE_ICONES_ARVORE_URBANO;
											   frame_icone = FRAME_ARVORE_URBANO + deslocamento_aparencia;
					break;
				default: 
			}
		} else if(classe_param == CLASSE_CASAS){
			switch(terreno_param){
				case c_terreno_bd.TIPO_VERDE:
				case c_terreno_bd.TIPO_GRAMA: 
				case c_terreno_bd.TIPO_LAVA:
				case c_terreno_bd.TIPO_URBANO: deslocamento_aparencia %= QUANTIDADE_ICONES_CASA_GRAMA;
											   frame_icone = FRAME_CASA_GRAMA + deslocamento_aparencia;
					break;
				case c_terreno_bd.TIPO_GELO: deslocamento_aparencia %= QUANTIDADE_ICONES_CASA_GELO;
											 frame_icone = FRAME_CASA_GELO + deslocamento_aparencia;
					break;
				default: 
			}
			
			c_localizacao.criarPara(this, c_casa.tipoParaString(deslocamento_aparencia+1), new Point(15,30)); //Deve vir depois do redimensionamento.
		} else if(classe_param == CLASSE_PREDIOS){
			frame_icone = FRAME_PREDIO;
		} else {
			frame_icone = FRAME_INDEFINIDO;
		}
		gotoAndStop(frame_icone);
	}
	
	//---- Mouse
	private function funcaoOnPress(){
		dispatchEvent({target:this, type:"btPressionado", classe: classe, terreno: terreno, tipo: tipo});
	}
	private function escurecer(){
		fundo.gotoAndStop(1);
	}
	private function clarear(){
		fundo.gotoAndStop(2);
	}
	
}