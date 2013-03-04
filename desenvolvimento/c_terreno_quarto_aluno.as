import mx.utils.Delegate;
import flash.geom.Point;

/*
* Classe que implementa o terreno do quarto do aluno, com uma porta de entrada.
*/
class c_terreno_quarto_aluno extends c_terreno_colisao_complexa {
//dados
	/*
	* Constantes cujo valor deve ser dado em cada terreno que especialize esta classe.
	* Comprimento e largura são estimados e não devem ser utilizados para testes de colisão.
	*/
	public var POS_X_INICIO_AREA_UTIL:Number = 96;
	public var POS_Y_INICIO_AREA_UTIL:Number = 143;
	private var COMPRIMENTO_ESTIMADO:Number = 800;
	private var LARGURA_ESTIMADA:Number = 400;
	public var COMPRIMENTO_AREA_UTIL:Number = COMPRIMENTO_ESTIMADO;
	public var LARGURA_AREA_UTIL:Number = LARGURA_ESTIMADA;

	/*
	* Computador que permite acesso a links de funcionalidades próprias.
	* O computador em si funciona como um botão simples.
	*/
	private static var NOME_COMPUTADOR:String = "pc";
	private var computador:c_bt_simples;
	private static var POSICAO_COMPUTADOR:Point = new Point(640.6, 66.5);
	
	/*
	* A interface que permite escolha de links.
	*/
	private static var NOME_INTERFACE_TELA_COMPUTADOR:String = "interface_tela_pcs";
	private var interface_tela_computador:c_interface_tela_computador;

	/*
	* Frames em que se encontram as diferentes aparências, que dependem dos terrenos dos quais de acessou o quarto.
	*/
	private static var FRAME_VISTA_TIPO_VERDE:Number = 1;
	private static var FRAME_VISTA_TIPO_GELO:Number = 2;
	private static var FRAME_VISTA_TIPO_LAVA:Number = 3;
	private static var FRAME_VISTA_TIPO_URBANO:Number = 4;

//métodos
	public function inicializar(dados_personagem_param:c_personagem_bd, imagem_bd_param:c_terreno_bd){
		fatorEscala = 1.73;
		
		super.inicializar(dados_personagem_param, imagem_bd_param);
		
		this['teleporte']._x = -5000;
		this['teleporte']._y = -5000;
		this['teleporte']._alpha = 0;
		carregarDados(imagem_bd_param);
		
		this['cadeira'].swapDepths(getDepthObjeto(this['cadeira']._y + this['cadeira'].limiteProfundidade._y));
		
		switch(_root.terreno_principal_status.getPlaneta().getAparencia()){
			case c_terreno_bd.TIPO_GRAMA:
			case c_terreno_bd.TIPO_VERDE: this['vista'].gotoAndStop(FRAME_VISTA_TIPO_VERDE);
				break;
			case c_terreno_bd.TIPO_GELO: this['vista'].gotoAndStop(FRAME_VISTA_TIPO_GELO);
				break;
			case c_terreno_bd.TIPO_LAVA: this['vista'].gotoAndStop(FRAME_VISTA_TIPO_LAVA);
				break;
			case c_terreno_bd.TIPO_URBANO: this['vista'].gotoAndStop(FRAME_VISTA_TIPO_URBANO);
				break;
			default: this['vista'].gotoAndStop(FRAME_VISTA_TIPO_VERDE);
		}
		
		var posxAcesso:Number = this['porta']._x;
		var posyAcesso:Number = this['porta']._y;
		this['porta'].removeMovieClip();
		attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_PORTA_TERRENO, "porta_nova", getNextHighestDepth());
		this['porta_nova']._x = posxAcesso;
		this['porta_nova']._y = posyAcesso;
		this['porta_nova']._alpha = 0;
		adicionarObjetoAcesso(this['porta_nova'], c_objeto_acesso.LINK_MUDANCA_TERRENO.concat(_root.usuario_status.ultimo_terreno_id));
		
		attachMovie("computador_quarto", NOME_COMPUTADOR, getNextHighestDepth());
		computador = this[NOME_COMPUTADOR];
		computador.inicializar();
		computador.swapDepths(this.getDepth()+1);
		computador._x = POSICAO_COMPUTADOR.x;
		computador._y = POSICAO_COMPUTADOR.y;
		computador.addEventListener("btPressionado", Delegate.create(this, mostrarTelaComputador));
		
		this['cama'].onMouseDown = function(){
			var tocouCama:Boolean = (0 <= _xmouse and _xmouse <= _width)
				and (0 <= _ymouse and _ymouse <= _height);
				
			if(tocouCama){
				_parent.perguntarSeUsuarioQuerSair();
			}
		}
		
		/*attachMovie(c_interface_tela_computador.LINK_BIBLIOTECA, NOME_INTERFACE_TELA_COMPUTADOR, getNextHighestDepth());
		interface_tela_computador = this[NOME_INTERFACE_TELA_COMPUTADOR];
		interface_tela_computador.inicializar();
		interface_tela_computador._x = 0;
		interface_tela_computador._y = 0;
		interface_tela_computador.desaparecer();
		interface_tela_computador.addEventListener("chamarLink", Delegate.create(this, linkChamado));
		interface_tela_computador.addEventListener("fechou", Delegate.create(this, interfaceTelaFechou));*/
		
	}


	/*
	* Inicia a animação do computador, culminando na exibição de sua tela, a interface que permite escolha de links.
	*/
	private function mostrarTelaComputador(){
		definirPermissaoMovimentoMp(false);
		chamarLink(c_objeto_acesso.LINK_TELA_PC);
		/*interface_tela_computador.swapDepths(getNextHighestDepth());
		interface_tela_computador.aparecer();*/
	}

	/*
	* Ação a ser executada toda vez que algum link for chamado.
	* @param eventoLink Um objeto que possui uma propriedade chamada "link" com o link a ser acessado.
	*/
	private function linkChamado(eventoLink:Object){
		chamarLink(eventoLink.link);
	}

	/*
	* Ação a ser executada sempre que a tela do computador fechar.
	*/
	private function interfaceTelaFechou(evento:Object){
		interface_tela_computador.desaparecer();
		definirPermissaoMovimentoMp(true);
	}


	
	/*
	* Cria uma aviso que pergunta se o usuário deseja sair.
	* Caso o usuário deseje, sairá do planeta. Caso contrário, destruirá o aviso.
	*/
	public function perguntarSeUsuarioQuerSair():Void{
		definirPermissaoMovimentoMp(false);
		var donoAviso:MovieClip = this;
		var mensagemAviso:String = "Deseja sair?";
		var posicaoAviso:Point = new Point(Stage.width/2 - _x, Stage.height/2 - _y - 125);
		var textoBotaoEsquerdoAviso:String = "Sair";
		var textoBotaoDireitoAviso:String = "Cancelar";
		var funcaoBotaoEsquerdoAviso:Function = _root.sair;
		var funcaoBotaoDireitoAviso:Function = function(){ c_aviso_dicotomico.destruirDe(this);
														   definirPermissaoMovimentoMp(true);
														   moveMp(c_personagem.DIRECAO_LESTE); };
		var escopoFuncoesAviso:Object = this;
			   
		c_aviso_dicotomico.criarPara(donoAviso, mensagemAviso, posicaoAviso, 
									 textoBotaoEsquerdoAviso, textoBotaoDireitoAviso, 
								     funcaoBotaoEsquerdoAviso, funcaoBotaoDireitoAviso, 
									 escopoFuncoesAviso);
	}

}
