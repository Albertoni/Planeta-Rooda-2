import flash.geom.Point;
/*
* Um mapa que representa um terreno.
* Contém indicadores de personagens e casas.
* O mapa é constituído de um "mapaEscala", que é a área onde ficam de fato os indicadores.
* Por praticidade, mantenho o nome dos indicadores igual ao nome dos objetos aos quais correspondem.
* Desta forma, é simples movimentá-los, basta usar como ID seu próprio nome.
*/
class c_mapa extends MovieClip{
//dados
	/*
	* Link do símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "mapaWide";
	
	/*
	* Dados do terreno.
	*/
	private var posicao_x_inicio_terreno:Number;
	private var posicao_y_inicio_terreno:Number;
	private var comprimento_terreno:Number;
	private var largura_terreno:Number;
	
	/*
	* Dados deste mapa. 
	* Estão sendo gravadados para impedir um bug em que o mapa ficava passeando pela tela.
	*/
	private var comprimentoIdeal:Number;
	private var larguraIdeal:Number;

//métodos
	/*
	* Inicializa o mapa com seus dados de escala (que variam de acordo com o terreno).
	*/
	public function inicializar(terreno_param:c_terreno):Void{
		posicao_x_inicio_terreno = terreno_param.POS_X_INICIO_AREA_UTIL;
		posicao_y_inicio_terreno = terreno_param.POS_Y_INICIO_AREA_UTIL;
		comprimento_terreno = terreno_param.COMPRIMENTO_AREA_UTIL;
		largura_terreno = terreno_param.LARGURA_AREA_UTIL;
								
		this['mapaTxt'].selectable = false;								//Mapa do terreno - Giovani - 05.05.10

		this['mapaInfo'].nomePlaneta.text = terreno_param.getImagemBancoDeDados().mensagemLocalizacao;
		atualizarNomeTerreno(terreno_param.getImagemBancoDeDados().mensagemLocalizacao);
		
		comprimentoIdeal = _width;
		larguraIdeal = _height;
	}
	
	/*
	* Atualiza o nome do terreno, que é exibido acima do mapa.
	* @param nome_terreno_param O nome do terreno exatamente como será exibido.
	*/
	public function atualizarNomeTerreno(nome_terreno_param:String):Void{
		if(nome_terreno_param != ""){
			this['mapaInfo'].nomeTerreno.text = nome_terreno_param;
		} else {
			this['mapaInfo'].nomeTerreno.text = "N.I.";
		}
	}
	
	/*
	* Adiciona um indicador de um dos tipos em c_indicador ao mapa na posição indicada.
	* Recebe coordenadas de terreno.
	* @param x_param Posição, no mapa, em coordenadas de terreno.
	* @param y_param Posição, no mapa, em coordenadas de terreno.
	* @param id_param Id, para modificação/deleção.
	* @param tipo_param O tipo de indicador.
	* @param nome_param O nome a ser exibido.
	*/
	public function adicionarIndicador(x_param:Number, y_param:Number, id_param:String, tipo_param:Number, nome_param:String):Void{
		var nomeIndicador:String = id_param; //Nome do indicador. Desta forma para rápido acesso e deleção.
		
		attachMovie( c_indicador.LINK_BIBLIOTECA, nomeIndicador, getNextHighestDepth() );	
		this[nomeIndicador].setTipo(tipo_param);												//define a cor do indicador - Jean 
		this[nomeIndicador].ajustarEscala(comprimento_terreno, largura_terreno, this['mapaEscala']._width, this['mapaEscala']._height);
		moverIndicador(x_param, y_param, id_param);
		
		if(nome_param != undefined){
			c_localizacao.criarPara(this[nomeIndicador], nome_param, new Point(this[nomeIndicador].desenho._x,this[nomeIndicador].desenho._y));
		}
	}
	
	/*
	* Move um indicador qualquer para a posição dada.
	* A ID pedida é o nome do objeto.
	*/
	public function moverIndicador(x_param:Number, y_param:Number, id_param:String):Void{
		var nomeIndicador:String = id_param; //Nome do indicador. Desta forma para rápido acesso e deleção.
			
		this[nomeIndicador]._x = calculoCoordenadaX(x_param); 
		this[nomeIndicador]._y = calculoCoordenadaY(y_param);
	}
	
	/*
	* Move este mapa.
	* @param posicao_destino_param A posição em que se deseja o mapa.
	*/
	public function mover(posicao_destino_param:Point){
		var variacaoComprimento:Number = _width - comprimentoIdeal;
		var variacaoLargura:Number = _height - larguraIdeal;
		_x = posicao_destino_param.x+variacaoComprimento;
		_y = posicao_destino_param.y+variacaoLargura;
	}
	
	/*
	* Deleta o indicador correpondente ao objeto de id passado como parâmetro.
	*/
	public function deletarIndicador(id_param:String){
		var nomeIndicador:String = id_param;
		c_localizacao.destruirDe(this[nomeIndicador]);
		this[nomeIndicador].removeMovieClip();
	}
	

	/*
	* Calculo padrão para inserção de indicador. Válido para qualquer tipo de indicador.
	* Retorna valor do eixo x.
	*/
	private function calculoCoordenadaX(x_param:Number, comprimento_indicador:Number):Number{
		var x_calculado:Number = (x_param)*(this['mapaEscala']._width/(comprimento_terreno));
		if(this['mapaEscala']._width < x_calculado){
			x_calculado = this['mapaEscala']._width;
		} else if(x_calculado < 0){
			x_calculado = 0;
		}
		return this['mapaEscala']._x + x_calculado;
	}

	/*
	* Calculo padrão para inserção de indicador. Válido para qualquer tipo de indicador.
	* Retorna valor do eixo y.
	*/
	private function calculoCoordenadaY(y_param:Number):Number{
		var y_calculado:Number = (y_param)*(this['mapaEscala']._height/(largura_terreno));
		if(this['mapaEscala']._height < y_calculado){
			y_calculado = this['mapaEscala']._height;
		} else if(y_calculado < 0){
			y_calculado = 0;
		}
		return this['mapaEscala']._y + y_calculado;
	}
	
	
}