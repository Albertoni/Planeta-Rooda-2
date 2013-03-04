/*
* Um indicador do mapa.
* Pode assumir a forma de uma casa, personagem, etc.
*/
class c_indicador extends MovieClip{
//dados
	/*
	* Nome deste símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "indicadorMapa";

	/*
	* Tipos de indicadores.
	*/
	public static var TIPO_INDEFINIDO:Number = 0;
	public static var TIPO_MP:Number = 1;
	public static var TIPO_CASA:Number = 2;
	public static var TIPO_OP:Number = 3;
	public static var TIPO_PREDIO:Number = 4;
	
	/*
	* Frames dos tipos.
	*/
	private static var FRAME_TIPO_MP:Number = 1;
	private static var FRAME_TIPO_CASA:Number = 2;
	private static var FRAME_TIPO_OP:Number = 3;
	private static var FRAME_TIPO_PREDIO:Number = 4;
	
	/*
	* Dimensões do terreno para o qual os indicadores foram projetados.
	* Devem ser escalados conforme dimensão do terreno que representam.
	*/
	private static var COMPRIMENTO_TERRENO_INDICADOR:Number = 1600;
	private static var LARGURA_TERRENO_INDICADOR:Number = 1200;

//métodos
	public function inicializar():Void{
		
	}

	/*
	* Ajusta o tamanho deste indicador para ficar de acordo com um terreno de dimensões passadas.
	*/
	public function ajustarEscala(comprimento_terreno_param:Number, largura_terreno_param:Number, comprimento_mapa_param:Number, largura_mapa_param:Number):Void{
		switch(getTipo()){
			case TIPO_MP:
					this['desenho']._width = _root.mp.sombra._width * comprimento_mapa_param/comprimento_terreno_param;
					this['desenho']._height = _root.mp.sombra._height * largura_mapa_param/largura_terreno_param;
					if(this['desenho']._width < this['desenho']._height){
						this['desenho']._height = this['desenho']._width;
					} else {
						this['desenho']._width = this['desenho']._height;
					}
				break;
			case TIPO_PREDIO:
					this['desenho']._width = _root.predio_alunos.sombra._width * comprimento_mapa_param/comprimento_terreno_param;
					this['desenho']._height = _root.predio_alunos.sombra._height * largura_mapa_param/largura_terreno_param;
				break;
			case TIPO_CASA:
					this['desenho']._width = _root.objeto_link.sombra._width * comprimento_mapa_param/comprimento_terreno_param;
					this['desenho']._height = _root.objeto_link.sombra._height * largura_mapa_param/largura_terreno_param;
				break;
			case TIPO_OP:
					this['desenho']._width = _root.mp.sombra._width * comprimento_mapa_param/comprimento_terreno_param;
					this['desenho']._height = _root.mp.sombra._height * largura_mapa_param/largura_terreno_param;
					if(this['desenho']._width < this['desenho']._height){
						this['desenho']._height = this['desenho']._width;
					} else {
						this['desenho']._width = this['desenho']._height;
					}
				break;
			default:
					this['desenho']._width = _root.mp.sombra._width * comprimento_mapa_param/comprimento_terreno_param;
					this['desenho']._height = _root.mp.sombra._height * largura_mapa_param/largura_terreno_param;
				break;
		}
	}

	/*
	* Define o tipo do indicador.
	*/
	public function setTipo(tipo_param:Number):Void{
		switch(tipo_param){
			case TIPO_MP: this['desenho'].gotoAndStop(FRAME_TIPO_MP);
				break;
			case TIPO_CASA: this['desenho'].gotoAndStop(FRAME_TIPO_CASA);
				break;
			case TIPO_OP: this['desenho'].gotoAndStop(FRAME_TIPO_OP);
				break;
			case TIPO_PREDIO: this['desenho'].gotoAndStop(FRAME_TIPO_PREDIO);
				break;
			default: this['desenho'].gotoAndStop(FRAME_TIPO_OP);
				break;
		}
	}

	/*
	* Retorna o tipo deste indicador.
	*/
	public function getTipo():Number{
		switch(this['desenho']._currentframe){
			case FRAME_TIPO_MP: return TIPO_MP;
				break;
			case FRAME_TIPO_CASA: return TIPO_CASA;
				break;
			case FRAME_TIPO_OP: return TIPO_OP;
				break;
			case FRAME_TIPO_PREDIO: return TIPO_PREDIO;
				break;
			default: return TIPO_INDEFINIDO;
				break;
		}
	}




}