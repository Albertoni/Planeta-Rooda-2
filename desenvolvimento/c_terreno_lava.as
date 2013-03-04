import flash.geom.Point;

/*
* Classe que implementa o terreno lava.
* Este terreno possui área útil retangular.
*/
class c_terreno_lava extends c_terreno_colisao_complexa {
//dados
	public var POS_X_INICIO_AREA_UTIL:Number = 255.6;
	public var POS_Y_INICIO_AREA_UTIL:Number = 19.7;
	private var COMPRIMENTO_ESTIMADO:Number = 2000;
	private var LARGURA_ESTIMADA:Number = 1300;
	public var COMPRIMENTO_AREA_UTIL:Number = COMPRIMENTO_ESTIMADO;
	public var LARGURA_AREA_UTIL:Number = LARGURA_ESTIMADA;
	
//métodos
	public function inicializar(dados_personagem_param:c_personagem_bd, imagem_bd_param:c_terreno_bd){
		super.inicializar(dados_personagem_param, imagem_bd_param);

		this['teleporte']._x = 500;
		this['teleporte']._y = 500;
		carregarDados(imagem_bd_param);

		/*
			O motivo do trecho a seguir é que o flash não é capaz de reconhecer o objeto "acessoQueVaiSerDeletado",
		filho deste objeto, como sendo da classe que é. O flah o vê como um MovieClip. 
			Isso acontece só quando este objeto é inserido com attachMovie, o que é necessário.
		*/
		var cenario:MovieClip = this['cenario'];
		if(cenario.acesso_noroeste != undefined){
			var posxAcesso:Number = cenario.acesso_noroeste._x+cenario._x;
			var posyAcesso:Number = cenario.acesso_noroeste._y+cenario._y;
			cenario.acesso_noroeste.removeMovieClip();
			attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_PONTE_TERRENO, "acesso_noroeste_novo", getNextHighestDepth());
			this['acesso_noroeste_novo']._x = posxAcesso;
			this['acesso_noroeste_novo']._y = posyAcesso;
			this['acesso_noroeste_novo']._alpha = 0;
		}
		
		if(cenario.acesso_sudoeste != undefined){
			var posxAcesso:Number = cenario.acesso_sudoeste._x+cenario._x;
			var posyAcesso:Number = cenario.acesso_sudoeste._y+cenario._y;
			cenario.acesso_sudoeste.removeMovieClip();
			attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_PONTE_TERRENO, "acesso_sudoeste_novo", getNextHighestDepth());
			this['acesso_sudoeste_novo']._x = posxAcesso;
			this['acesso_sudoeste_novo']._y = posyAcesso;
			this['acesso_sudoeste_novo']._alpha = 0;
		}
		
		if(cenario.acesso_nordeste != undefined){
			var posxAcesso:Number = cenario.acesso_nordeste._x+cenario._x;
			var posyAcesso:Number = cenario.acesso_nordeste._y+cenario._y;
			cenario.acesso_nordeste.removeMovieClip();
			attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_PONTE_TERRENO, "acesso_nordeste_novo", getNextHighestDepth());
			this['acesso_nordeste_novo']._x = posxAcesso;
			this['acesso_nordeste_novo']._y = posyAcesso;
			this['acesso_nordeste_novo']._alpha = 0;
		}
		
		if(cenario.acesso_sudeste != undefined){
			var posxAcesso:Number = cenario.acesso_sudeste._x+cenario._x;
			var posyAcesso:Number = cenario.acesso_sudeste._y+cenario._y;
			cenario.acesso_sudeste.removeMovieClip();
			attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_PONTE_TERRENO, "acesso_sudeste_novo", getNextHighestDepth());
			this['acesso_sudeste_novo']._x = posxAcesso;
			this['acesso_sudeste_novo']._y = posyAcesso;
			this['acesso_sudeste_novo']._alpha = 0;
		}
		
		/*adicionarObjetoAcesso(this['acesso_noroeste_novo'], c_objeto_acesso.getLinkAcessoTerreno(_root.terreno_status.oeste));
		adicionarObjetoAcesso(this['acesso_sudoeste_novo'], c_objeto_acesso.getLinkAcessoTerreno(_root.terreno_status.oeste));
		adicionarObjetoAcesso(this['acesso_nordeste_novo'], c_objeto_acesso.getLinkAcessoTerreno(_root.terreno_status.oeste));
		adicionarObjetoAcesso(this['acesso_sudeste_novo'], c_objeto_acesso.getLinkAcessoTerreno(_root.terreno_status.oeste));*/
		adicionarObjetoAcesso(this['acesso_noroeste_novo'], undefined);
		adicionarObjetoAcesso(this['acesso_sudoeste_novo'], undefined);
		adicionarObjetoAcesso(this['acesso_nordeste_novo'], undefined);
		adicionarObjetoAcesso(this['acesso_sudeste_novo'], undefined);
		
		
	}
	
	/*
	* Pontos de entrada são pontos em que o personagem pode ficar e que são o mais próximo possível de pontes.
	* Devem ser sobrecarregadas em cada classe que use este template.
	*/
	public function getPontoEntradaNoroeste():Point{
		return new Point(222,399);
	}
	public function getPontoEntradaNordeste():Point{
		return new Point(1650.9,388);
	}
	public function getPontoEntradaSudeste():Point{
		return new Point(1675.9,875);
	}
	public function getPontoEntradaSudoeste():Point{
		return new Point(248,821);
	}
	
}
