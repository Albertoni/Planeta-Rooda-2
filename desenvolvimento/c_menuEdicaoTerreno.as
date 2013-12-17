import flash.geom.Point;
import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_menuEdicaoTerreno extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "menuEdicaoTerreno";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;

	/*
	* Variável que indica se o menu deve ser fechado.
	*/
	private var fecharMenu:Boolean = false;
	
	/*
	* Frames que caracterizam estados deste menu.
	*/
	private static var FRAME_ESTADO_FECHADO:Number = 1;
	private static var FRAME_ESTADO_BOTOES:Number = 15;	//Frame que mostra os botoes.
	private static var FRAME_ESTADO_REINICIAR:Number = 29; //Quando o menu deve voltar para o estado fechado.

	/*
	* Tempos para transição de estados do menu.
	*/
	private var TEMPO_ESPERA_MOSTRAR_BOTOES:Number = 600;
	private var TEMPO_ESPERA_ESCONDER_BOTOES:Number = 600;

	/*
	* As páginas que possui este menu.
	* Todos os elementos deste array devem ser do tipo c_paginaMenuEdicaoTerreno.
	*/
	private var paginas:Array;
	
	/*
	* A quantidade de páginas que possui este menu.
	* Nas páginas são colocados os ícones.
	* O menu permite navegar entre as páginas com o uso de dois botões.
	*/
	private var quantidade_paginas:Number;

	/*
	* Índice no array de páginas da página que está sendo mostrada atualmente.
	*/
	private var indicePaginaAtual:Number;

	/*
	* Posição em que as páginas devem ser colocadas.
	*/
	private static var POSICAO_PAGINAS:Point;

	/*
	* Botões para navegação nas páginas.
	*/
	private var btProximo:MovieClip;
	private var btAnterior:MovieClip;
	
	/*
	* Botões para abrir/fechar o menu.
	*/
	private var btMenu:c_btMenu;

//métodos
	/*
	* @param terreno_param O tipo de terreno dos ícones.
	*/
	public function inicializar(terreno_param:String){
		mx.events.EventDispatcher.initialize(this);
		
		POSICAO_PAGINAS = new Point(-178.35, -83.5);
		paginas = new Array();
		quantidade_paginas = 0;
		indicePaginaAtual = 0;
		
		btProximo = this['btProximo'];
		btProximo.inicializar();
		btProximo.addEventListener("btProximoPress", Delegate.create(this, mostrarPaginaSeguinte));
		btProximo._visible = false;
		
		btAnterior = this['btAnterior'];
		btAnterior.inicializar();
		btAnterior.addEventListener("btAnteriorPress", Delegate.create(this, mostrarPaginaAnterior));	
		btAnterior._visible = false;
		
		btMenu = this['btMenu'];
		btMenu.inicializar();
		btMenu.addEventListener("btMenuPress", Delegate.create(this, toggleAbrirFechar));	
		btMenu.addEventListener("btMenuOver", Delegate.create(this, escurecerBtMenu));	
		btMenu.addEventListener("btMenuOut", Delegate.create(this, clarearBtMenu));	

		criarPaginasCasas(0, terreno_param);
		criarPaginasPredios(1, terreno_param);
		criarPaginasArvores(2, terreno_param);
		
		onEnterFrame = atualizacoesEnterFrame;
		esconder_botoes();
	}
	
	/*
	* Despacha um evento de botão para o pai.
	*/
	private function despacharEvento(evento_param:Object):Void{
		ficarAcimaDosOutrosMenus();
		dispatchEvent({target:this, type:"btPressionado", classe: evento_param.classe, terreno: evento_param.terreno, tipo: evento_param.tipo});
	}
	
	/*
	* Cria as páginas com ícones de prédios do terreno grama.
	* @param indice_pagina_inicial_param O índice da primeira página a ser criada.
	* @param terreno_param O tipo de terreno das casas.
	*/
	private function criarPaginasPredios(indice_pagina_inicial_param:Number, terreno_param:String):Void{
		var quantidadeCasasDiferentes:Number;
		if(terreno_param != c_terreno_bd.TIPO_QUARTO){
			adicionarPagina();
			var oTerrenoNaoImporta:Number=0;
			paginas[indice_pagina_inicial_param].adicionarBotao(c_iconeEdicaoTerreno.CLASSE_PREDIOS, oTerrenoNaoImporta, 0);
		}
	}
	
	/*
	* Cria as páginas com ícones de casas do terreno grama.
	* @param indice_pagina_inicial_param O índice da primeira página a ser criada.
	* @param terreno_param O tipo de terreno das casas.
	*/
	private function criarPaginasCasas(indice_pagina_inicial_param:Number, terreno_param:String):Void{
		var quantidadeCasasDiferentes:Number;
		
		switch(terreno_param){
			case c_terreno_bd.TIPO_VERDE:
			case c_terreno_bd.TIPO_GRAMA:
			case c_terreno_bd.TIPO_LAVA: 
			case c_terreno_bd.TIPO_URBANO:
			case c_terreno_bd.TIPO_GELO: quantidadeCasasDiferentes = c_iconeEdicaoTerreno.QUANTIDADE_ICONES_CASA_GRAMA;
				break;
			default: quantidadeCasasDiferentes = 0;
		}
		
		if(terreno_param != c_terreno_bd.TIPO_QUARTO || 0 < quantidadeCasasDiferentes){
			adicionarPagina();
			for(var botoesAdicionados:Number=0; botoesAdicionados<quantidadeCasasDiferentes; botoesAdicionados++){
				paginas[indice_pagina_inicial_param].adicionarBotao(c_iconeEdicaoTerreno.CLASSE_CASAS, terreno_param, botoesAdicionados);
			}
		}
	}
	
	/*
	* Cria as páginas com ícones de árvores.
	* @param indice_pagina_inicial_param O índice da primeira página a ser criada.
	* @param terreno_param O tipo de terreno das árvores.
	*/
	private function criarPaginasArvores(indice_pagina_inicial_param:Number, terreno_param:String):Void{
		var quantidadeArvoresDiferentes:Number;
		switch(terreno_param){
			case c_terreno_bd.TIPO_VERDE:
			case c_terreno_bd.TIPO_GRAMA: quantidadeArvoresDiferentes = c_iconeEdicaoTerreno.QUANTIDADE_ICONES_ARVORE_GRAMA;
				break;
			case c_terreno_bd.TIPO_LAVA: quantidadeArvoresDiferentes = c_iconeEdicaoTerreno.QUANTIDADE_ICONES_ARVORE_LAVA;
				break;
			case c_terreno_bd.TIPO_URBANO: quantidadeArvoresDiferentes = c_iconeEdicaoTerreno.QUANTIDADE_ICONES_ARVORE_URBANO;
				break;
			case c_terreno_bd.TIPO_GELO: quantidadeArvoresDiferentes = c_iconeEdicaoTerreno.QUANTIDADE_ICONES_ARVORE_GELO;
				break;
			default: quantidadeArvoresDiferentes = 0;
		}
		
		if(0 < quantidadeArvoresDiferentes){
			adicionarPagina();
			for(var botoesAdicionados:Number=0; botoesAdicionados<quantidadeArvoresDiferentes; botoesAdicionados++){
				paginas[indice_pagina_inicial_param].adicionarBotao(c_iconeEdicaoTerreno.CLASSE_ARVORES, terreno_param, botoesAdicionados);
			}
		}
	}
	
	
	
	/*
	* Adiciona uma página a este menu, sem botões.
	* A página pode ser acessada por sua posição no array de páginas.
	*/
	private function adicionarPagina():Void{
		var nomePagina:String;
		nomePagina = c_paginaMenuEdicaoTerreno.LINK_BIBLIOTECA+quantidade_paginas;
		attachMovie(c_paginaMenuEdicaoTerreno.LINK_BIBLIOTECA, nomePagina, 
					getNextHighestDepth(), {_x:POSICAO_PAGINAS.x, _y:POSICAO_PAGINAS.y});
		this[nomePagina].inicializar();
		this[nomePagina].addEventListener("btPressionado", Delegate.create(this, despacharEvento));
		this[nomePagina]._visible = false;
		paginas.push(this[nomePagina]);
		quantidade_paginas++;
	}
	
	/*
	* Mostra a página seguinte, se existir. Esconde a página atual, caso exista seguinte.
	*/
	private function mostrarPaginaSeguinte():Void{
		ficarAcimaDosOutrosMenus();
		mostrarPagina(indicePaginaAtual+1);
	}
	
	/*
	* Mostra a página anterior, se existir. Esconde a página atual, caso exista anterior.
	*/
	private function mostrarPaginaAnterior():Void{
		ficarAcimaDosOutrosMenus();
		mostrarPagina(indicePaginaAtual-1);
	}
	
	/*
	* Faz com que este menu fique acima de outros.
	*/
	private function ficarAcimaDosOutrosMenus(){
		if(this.getDepth() < _root.menuMC.getDepth()){
			this.swapDepths(_root.menuMC);
		}
	}
	
	/*
	* Mostra a página cujo índice é passado como parâmetro.
	* Caso não haja páginas neste menu ou o índice excede o máximo do array, não faz nada.
	* Caso consiga mostrar a página, também esconde a página que está sendo mostrada no momento, se houver.
	* @param indice_pagina_param Índice no array de páginas da página que deve ser mostrada.
	*/
	private function mostrarPagina(indice_pagina_param:Number):Void{
		if(indice_pagina_param < quantidade_paginas
		   and 0 <= indice_pagina_param){
			paginas[indicePaginaAtual]._visible = false;
			paginas[indice_pagina_param]._visible = true;
			indicePaginaAtual = indice_pagina_param;
			if(indice_pagina_param == quantidade_paginas-1){
				btProximo._alpha = 15;
			} else {
				btProximo._alpha = 100;
			}
			if(indice_pagina_param == 0){
				btAnterior._alpha = 15;
			} else {
				btAnterior._alpha = 100;
			}
		}
	}
	
	/*********************************************************************
	* Funções de animação do menu.
	* Análogas as de c_menu.
	*********************************************************************/
	private function menuEstadoFechado():Boolean{
		if(_currentframe == FRAME_ESTADO_FECHADO){
			return true;
		}
		else{
			return false;
		}
	}
	private function menuEstadoBotoes():Boolean{
		if (_currentframe == FRAME_ESTADO_BOTOES){
			return true;
		}
		else{
			return false;
		}
	}
	private function menuEstadoReiniciar():Boolean{
		if(_currentframe == FRAME_ESTADO_REINICIAR){
			return true;
		}
		else{
			return false;
		}
	}
	
	private function abrirMenuEstadoBotoesComAnimacao():Void{
		gotoAndPlay(2);
	}
	private function fecharMenuBotoesComAnimacao():Void{
		gotoAndPlay(16);
		mostrar_botoes();
	}
	private function abrirMenuEstadoBotoesInstantaneamente():Void{
		gotoAndStop(FRAME_ESTADO_BOTOES);
		mostrar_botoes();
	}
	private function fecharMenuInstantaneamente():Void{
		gotoAndStop(FRAME_ESTADO_FECHADO);
		esconder_botoes();
	}
	
	private function mostrar_botoes(){
		btProximo._visible = true;
		btAnterior._visible = true;
		mostrarPagina(indicePaginaAtual);
	}
	private function esconder_botoes(){
		btProximo._visible = false;
		btAnterior._visible = false;
		paginas[indicePaginaAtual]._visible = false;
	}
	private function iniciar_espera_mostrar_botoes():Void{
		//Mágica encontrada no google. URL: "http://www.actionscript.org/forums/showthread.php3?t=171425".
		_global['setTimeout'](this, 'mostrar_botoes', TEMPO_ESPERA_MOSTRAR_BOTOES); 
	}
	private function iniciar_espera_esconder_botoes():Void{
		//Mágica encontrada no google. URL: "http://www.actionscript.org/forums/showthread.php3?t=171425".
		_global['setTimeout'](this, 'esconder_botoes', TEMPO_ESPERA_ESCONDER_BOTOES);
	}
	
	private function toggleAbrirFechar():Void{
		switch(true){
			case menuEstadoFechado():	abrirMenuEstadoBotoesComAnimacao();
										iniciar_espera_mostrar_botoes();
										ficarAcimaDosOutrosMenus();
			break;
			
			case menuEstadoBotoes(): fecharMenuBotoesComAnimacao();
									 esconder_botoes();
			break;
					
			case menuEstadoReiniciar(): abrirMenuEstadoBotoesComAnimacao();
			break;
		}
		clarearBtMenu();
	}
	public function atualizacoesEnterFrame():Void{
		switch(true){
			case menuEstadoFechado(): fecharMenu = false;
			break;
			
			case menuEstadoBotoes(): //nada...
			break;

			case menuEstadoReiniciar(): fecharMenuInstantaneamente();
			break;
		}
	}
	private function botaoFecharPressionado():Void{
		switch(true){
			case menuEstadoFechado():	//impossível
			break;
					
			case menuEstadoBotoes(): fecharMenuBotoesComAnimacao();
									 esconder_botoes();
			break;
					
			case menuEstadoReiniciar(): fecharMenuInstantaneamente();
			break;
		}
	}
	
	private function clarearBtMenu(){
		//para o botao do menu mudar de cor. 
		//O botao e composto de varias partes pois nao e retangular e precisa mudar de tamanho sem deformar suas extremidades.
		this['ptCimaMenu'].gotoAndStop(1);
		this['ptBaixoMenu'].gotoAndStop(1);
		this['txtMenu'].gotoAndStop(1);
		btMenu.clarear();
	}
	private function escurecerBtMenu(){
		this['ptCimaMenu'].gotoAndStop(2);
		this['ptBaixoMenu'].gotoAndStop(2);		
		this['txtMenu'].gotoAndStop(2);
		btMenu.escurecer();
	}
	
	
}