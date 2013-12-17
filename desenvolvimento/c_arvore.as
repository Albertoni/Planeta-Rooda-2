/*
* Árvores são implementadas no símbolo "parede" da biblioteca.
* Dentro deste, cada frame possui um tipo diferente de árvore.
* Elas estão organizadas segundo o terreno ao qual pertencem.
* Assim, todas as árvores de um mesmo terreno sempre estão em seqüência.
* 
* Desta forma, as árvores são classificadas segundo dois critérios:
* 	+ O terreno a que pertencem;
*	+ Sua aparência;
*/
class c_arvore extends c_objeto_editavel{
//dados
	/*
	* Caso o tipo não seja determinado.
	*/
	private static var FRAME_TIPO_INDETERMINADO:Number = 1;

	/*
	* Dados do MovieClip.
	*/
	public static var LINK_BIBLIOTECA:String = "parede";

	/*
	* O frame em que começam as árvores do tipo de terreno.
	*/
	private static var FRAME_TIPO_VERDE:Number = 1;
	private static var FRAME_TIPO_GRAMA:Number = 1;
	private static var FRAME_TIPO_LAVA:Number = 7;
	private static var FRAME_TIPO_GELO:Number = 16;
	private static var FRAME_TIPO_URBANO:Number = 11;

	/*
	* O número de árvores na seqüência de árvores de um tipo dentro do símbolo "parede".
	*/
	public static var NUMERO_ARVORES_TIPO_VERDE:Number = 6;
	public static var NUMERO_ARVORES_TIPO_GRAMA:Number = 6;
	public static var NUMERO_ARVORES_TIPO_LAVA:Number = 4;
	public static var NUMERO_ARVORES_TIPO_GELO:Number = 5;
	public static var NUMERO_ARVORES_TIPO_URBANO:Number = 5;
	
	/*
	* ID desta árvore no banco de dados.
	*/
	private var id:Number;
	
//métodos
	/*
	* Ao inicializar este objeto, é necessário definir sua identificação, para que possa ter seus dados 
	* sincronizados com sua imagem no banco de dados.
	*/
	public function inicializar(identificacao_param:String){
		super.inicializar(identificacao_param);
	}
	
	/*
	* Cria o nome de uma árvore a partir de seu id.
	*/
	public static function criarNome(id_param:Number):String{
		return c_arvore.LINK_BIBLIOTECA+id_param;
	}
	
	/*
	* Retorna o tipo de terreno ao qual pertence esta árvore.
	*/
	public function getTipoTerreno():String{
		if(_currentframe <=  FRAME_TIPO_GRAMA+NUMERO_ARVORES_TIPO_GRAMA-1){
			return c_terreno_bd.TIPO_GRAMA;
		} else if(_currentframe <= FRAME_TIPO_LAVA+NUMERO_ARVORES_TIPO_LAVA-1){
			return c_terreno_bd.TIPO_LAVA;
		} else if(_currentframe <= FRAME_TIPO_URBANO+NUMERO_ARVORES_TIPO_URBANO-1){
			return c_terreno_bd.TIPO_URBANO;
		} else if(_currentframe <= FRAME_TIPO_GELO+NUMERO_ARVORES_TIPO_GELO-1){
			return c_terreno_bd.TIPO_GELO;
		} else{
			return c_terreno_bd.TIPO_GRAMA;
		}
	}
	
	/*
	* Retorna o frame do tipo da aparência desta árvore.
	*/
	public function getFrameTipoAparencia():Number{
		var frame_tipo_aparencia:Number;
		switch(getTipoTerreno()){
			case c_terreno_bd.TIPO_VERDE: frame_tipo_aparencia = _currentframe - FRAME_TIPO_VERDE + 1;
				break;
			case c_terreno_bd.TIPO_GRAMA: frame_tipo_aparencia = _currentframe - FRAME_TIPO_GRAMA + 1;
				break;
			case c_terreno_bd.TIPO_LAVA: frame_tipo_aparencia = _currentframe - FRAME_TIPO_LAVA + 1;
				break;
			case c_terreno_bd.TIPO_GELO: frame_tipo_aparencia = _currentframe - FRAME_TIPO_GELO + 1;
				break;
			case c_terreno_bd.TIPO_URBANO: frame_tipo_aparencia = _currentframe - FRAME_TIPO_URBANO + 1;
				break;
			default: frame_tipo_aparencia = FRAME_TIPO_INDETERMINADO;
				break;
		}
		return frame_tipo_aparencia;
	}

	/*
	* Converte esta árvore para o tipo de terreno destino.
	* O mapeamento é definido de forma conveninte à programação e pode (talvez deva) ser mudado futuramente.
	*/
	public function definirTipoTerreno(tipo_terreno_destino_param:String):Void{
		var frameAparencia:Number = getFrameTipoAparencia();
		switch(tipo_terreno_destino_param){
			case c_terreno_bd.TIPO_VERDE: gotoAndStop(FRAME_TIPO_VERDE);
				break;
			case c_terreno_bd.TIPO_GRAMA: gotoAndStop(FRAME_TIPO_GRAMA);
				break;
			case c_terreno_bd.TIPO_LAVA: gotoAndStop(FRAME_TIPO_LAVA);
				break;
			case c_terreno_bd.TIPO_GELO: gotoAndStop(FRAME_TIPO_GELO);
				break;
			case c_terreno_bd.TIPO_URBANO: gotoAndStop(FRAME_TIPO_URBANO);
				break;
			default: gotoAndStop(FRAME_TIPO_INDETERMINADO);
				break;
		}
		definirTipoAparencia(frameAparencia);
	}

	/*
	* Define o tipo da aparência da árvore, independente do terreno a que pertença e de forma definida (circular) para qualquer tipo_aparencia_param.
	*/
	public function definirTipoAparencia(tipo_aparencia_param:Number):Void{
		var frame_tipo_especificado:Number;
		if(tipo_aparencia_param < 0){
			tipo_aparencia_param = -tipo_aparencia_param;
		}
		switch(getTipoTerreno()){
			case c_terreno_bd.TIPO_VERDE: frame_tipo_especificado = FRAME_TIPO_VERDE+((tipo_aparencia_param+NUMERO_ARVORES_TIPO_VERDE-1)%NUMERO_ARVORES_TIPO_VERDE);
				break;
			case c_terreno_bd.TIPO_GRAMA: frame_tipo_especificado = FRAME_TIPO_GRAMA+((tipo_aparencia_param+NUMERO_ARVORES_TIPO_GRAMA-1)%NUMERO_ARVORES_TIPO_GRAMA);
				break;
			case c_terreno_bd.TIPO_LAVA: frame_tipo_especificado = FRAME_TIPO_LAVA+((tipo_aparencia_param+NUMERO_ARVORES_TIPO_LAVA-1)%NUMERO_ARVORES_TIPO_LAVA);
				break;
			case c_terreno_bd.TIPO_GELO: frame_tipo_especificado = FRAME_TIPO_GELO+((tipo_aparencia_param+NUMERO_ARVORES_TIPO_GELO-1)%NUMERO_ARVORES_TIPO_GELO);
				break;
			case c_terreno_bd.TIPO_URBANO: frame_tipo_especificado = FRAME_TIPO_URBANO+((tipo_aparencia_param+NUMERO_ARVORES_TIPO_URBANO-1)%NUMERO_ARVORES_TIPO_URBANO);
				break;
			default: frame_tipo_especificado = FRAME_TIPO_INDETERMINADO;
				break;
		}
		gotoAndStop(frame_tipo_especificado);
	}

	/*
	* Altera a aparência para a adjacente à direita do mesmo tipo de terreno.
	* No limite, volta para o início (primeira da esquerda para a direita).
	*/
	public function aparencia_seguinte():Void{
		definirTipoAparencia(getFrameTipoAparencia() + 1);
	}

	/*
	* Altera a aparência para a adjacente à esquerda do mesmo tipo de terreno.
	* No limite, vai para o fim (última da esquerda para a direita).
	*/
	public function aparencia_anterior():Void{
		definirTipoAparencia(getFrameTipoAparencia() - 1);
	}




}
