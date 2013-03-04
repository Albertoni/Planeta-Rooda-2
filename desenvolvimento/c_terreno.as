import mx.utils.Delegate;
import flash.geom.Point;
import flash.external.*;
import mx.data.types.Obj;
import mx.events.EventDispatcher;

class c_terreno extends MovieClip{ 
//dados
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
	
	/*
	* Definem a área útil do terreno.
	*/
	public var POS_X_INICIO_AREA_UTIL:Number = 10;
	public var POS_Y_INICIO_AREA_UTIL:Number = 10;
	public var COMPRIMENTO_AREA_UTIL:Number;
	public var LARGURA_AREA_UTIL:Number;

	/*
	* Valores que representam as pontes do terreno.
	*/
	public static var PONTE_NOROESTE:Number=0;
	public static var PONTE_NORDESTE:Number=1;
	public static var PONTE_SUDOESTE:Number=2;
	public static var PONTE_SUDESTE:Number=3;

	/*
	* Sistema de colisão do terreno.
	*/
	public var sistemaColisao:c_colisao_terreno;

	/*
	* Um gerador de ids.
	*/
	private var id_corrente:Number = 0;
	
	/*
	* Personagens que estão online.
	*/
	private var idsPersonagensOnline:Array = new Array();
	
	/*
	* Determinam a distância mínima que o personagem pode ficar das bordas da tela,
	* ainda movendo-se independente desta. Ao ultrapassar esta distância, a tela seguirá o movimento do personagem.
	*/
	private static var LIMITE_MOVIMENTACAO_COMPRIMENTO:Number = 275;
	private static var LIMITE_MOVIMENTACAO_LARGURA:Number = 170;
	
	/*
	* Contém ids de casas que estão com a porta aberta.
	*/
	private var casasComPortaAberta:Array = new Array();
	
	/*
	* "Registra a altura base dos objetos no eixo Z - Guto - 19.12.08"
	*/
	private static var depthBaseObj:Number = 10000;

	/*
	* Imagem deste terreno no banco de dados, com todos seus dados.
	*/
	private var imagemBancoDeDados:c_terreno_bd;

	/*
	* Determina se o personagem do usuário logado pode movimentar-se.
	*/
	private var permissaoMovimento:Boolean = true;
	
	/*
	* Determina o fator de escala deste terreno, pelo qual devem ser multiplicados objetos divididos com outros terrenos.
	*/
	private var fatorEscala:Number = 1;
	
	/*
	* Lugar onde os personagens ficam ao entrar no terreno.
	*/
	private var teleporte:c_teleporte;

	/**
	* Indica se o personagem acaba de entrar neste terreno via ponte.
	* Quando está neste estado, não é possível utilizar a ponte para sair do terreno.
	*/
	private var ponteHabilitada:Boolean;

	/*
	* Movimento do personagem.
	*/
	private var mouseClick:Boolean = false;         //Indica se o mouse foi clicado - Roger - 20.07.09
	private var mousePress:Boolean = false;         //Indica se o botao esquerdo do mouse esta sendo pressionado - Roger - 26.08.09
	private var tempo_mouseClick:Number = 0;
	private var pontoDestinoClickMp:Point;

//métodos
	public function inicializar(dados_personagem_param:c_personagem_bd, imagem_bd_param:c_terreno_bd){
		mx.events.EventDispatcher.initialize(this);
		
		imagemBancoDeDados = imagem_bd_param;
		idsPersonagensOnline = new Array();
			
		pontoDestinoClickMp = new Point();
		
		habilitarPontes(true);
		
		//_x = 0;
		//_y = 0;
		useHandCursor = false;
		
		attachMovie(c_mapa.LINK_BIBLIOTECA, "mapa", getNextHighestDepth());
		this['mapa'].inicializar(this);
		this['mapa']._x = Stage.width - this['mapa']._width;
		this['mapa']._y = Stage.height - this['mapa']._height;
		
		attachMovie(c_personagem.LINK_BIBLIOTECA, "mp", getNextHighestDepth());
		this['mp'].inicializar(dados_personagem_param, true);
		this['mp'].escalar(fatorEscala);
		this['mapa'].adicionarIndicador(this['mp']._x, this['mp']._y, "mp", c_indicador.TIPO_MP, dados_personagem_param.getNome());
		
		attachMovie(c_teleporte.LINK_BIBLIOTECA, "teleporte", getNextHighestDepth());
		this.teleporte = this['teleporte'];
		
		sistemaColisao = new c_colisao_terreno(this);
		
		/*
		A colisão do mp deve ser a primeira a ser registrada.
		*/
		sistemaColisao.forcarRegistroColisaoObjeto(this['mp']);
		ajustarProfundidadeMp();
		
		onEnterFrame = atualizar;
		
		onMouseUp = function(){
			if(!_root.seleciounouBotaoForaTerreno()){
				mousePress = false; //Quando se clica no terreno, botaoMousePress vira true. Enquanto ela for true o mp segue o mouse. Só se torna false qndo o botao do mouse é solto - Roger - 21.08.09										
			}
		}
		
		centralizarTelaEm(new Point(this['mp']._x, this['mp']._y));
	}
	
	/**
	* @param Boolean		habilitar_param		Indica se deve ser possível utilizar as pontes do terreno.
	*/
	public function habilitarPontes(habilitar_param:Boolean):Void{
		this.ponteHabilitada = habilitar_param;
	}
	
	/**
	* Ativa/desativa funções deste terreno, o que permite ativação de outros.
	* @param Boolean		estahAtivo_param		Indica se deve ativar.
	*/
	public function estahAtivo(estahAtivo_param:Boolean){
		onEnterFrame = undefined;
		if(estahAtivo_param){
			definirPermissaoMovimentoMp(true);
			this['mp']._visible = true;
			this['mapa']._visible = true;
			colocarMapaNoLugar();
			onEnterFrame = atualizar;
			_root.comunicador.inicializarChatTerrreno(imagemBancoDeDados.getIdChat());
		} else {
			definirPermissaoMovimentoMp(false);
			this['mapa']._visible = false;
			this['mp']._visible = false;
		}
	}
	
	/*
	* "Limpa" completamente o terreno, deletando todas as casas, árvores, prédios e personagens.
	* O único personagem que permanecerá será o do usuário, o mp.
	*/
	private function limparTerreno():Void{
		var idDeletada:Number=0;
		var nomeArvore:String;
		var nomeCasa:String;
		var nomePredio:String;
		var idPersonagem:String;
		
		for(idDeletada=0; idDeletada<id_corrente; idDeletada++){
			nomeArvore = c_arvore.criarNome(idDeletada);
			nomeCasa = c_casa.criarNome(idDeletada);
			nomePredio = c_predio_alunos.criarNome(idDeletada);
			
			if(this[nomeArvore] != undefined){
				removerObjeto(nomeArvore);
			} else if(this[nomeCasa] != undefined){
				removerObjeto(nomeCasa);
			} else if(this[nomePredio] != undefined){
				removerObjeto(nomePredio);
			}
		}
		
		for(var personagemAtual:Number=0; personagemAtual<idsPersonagensOnline.length; idsPersonagensOnline++){
			removerPersonagem(idsPersonagensOnline[personagemAtual]);
		}
	}
	
	
	
	/*
	* Realiza mudança para terreno vizinho, com base na posição do personagem.
	*/
	private function trocarTerreno():Void{
		/*var ponteFicaAoLeste:Boolean = false;
		var ponteMaisProxima:Number = getPonteMaisProxima(new Point(this['mp']._x, this['mp']._y));
		ponteFicaAoLeste = (ponteMaisProxima==c_terreno.PONTE_NORDESTE or ponteMaisProxima==c_terreno.PONTE_SUDESTE);
		if(ponteFicaAoLeste){
			imagemBancoDeDados = imagemBancoDeDados.getTerrenoLeste();
		} else {
			imagemBancoDeDados = imagemBancoDeDados.getTerrenoOeste();
		}
		
		limparTerreno();
		carregarDados(imagemBancoDeDados);
		var distanciaCameraCentro:Point = new Point(_x, _y);
		centralizarTelaEm(new Point(this['mp']._x, this['mp']._y));
		distanciaCameraCentro.x -= _x;
		distanciaCameraCentro.y -= _y;
			
		switch(getPonteMaisProxima(new Point(this['mp']._x, this['mp']._y))){
			case c_terreno.PONTE_NORDESTE: this['mp']._x = getPontoEntradaNoroeste().x; //c_aviso_com_ok.mostrar("Mudou e vai para NOROESTE no "+imagemBancoDeDados.paraString());
										   this['mp']._y = getPontoEntradaNoroeste().y;
				break;
			case c_terreno.PONTE_NOROESTE: this['mp']._x = getPontoEntradaNordeste().x; //c_aviso_com_ok.mostrar("Mudou e vai para NORDESTE no "+imagemBancoDeDados.paraString());
										   this['mp']._y = getPontoEntradaNordeste().y;
				break;
			case c_terreno.PONTE_SUDESTE: this['mp']._x = getPontoEntradaSudoeste().x; //c_aviso_com_ok.mostrar("Mudou e vai para SUDOESTE no "+imagemBancoDeDados.paraString());
										  this['mp']._y = getPontoEntradaSudoeste().y;
				break;
			case c_terreno.PONTE_SUDOESTE: this['mp']._x = getPontoEntradaSudeste().x; //c_aviso_com_ok.mostrar("Mudou e vai para SUDESTE no "+imagemBancoDeDados.paraString());
										   this['mp']._y = getPontoEntradaSudeste().y;
				break;
		}
			
		centralizarTelaEm(new Point(this['mp']._x-distanciaCameraCentro.x, 
									this['mp']._y-distanciaCameraCentro.y));
		this['mapa'].atualizarNomeTerreno(imagemBancoDeDados.getNome());
		_root.comunicador.inicializarChatTerrreno(imagemBancoDeDados.getIdChat());*/
		dispatchEvent({target:this, type:"trocarTerreno"});
	}
	
	/*
	* Muda o terreno para o quarto do personagem.
	*/
	public static function irParaQuarto(){
		var imagemPersonagem:c_personagem_bd = _root.planeta.getTerrenoEmQuePersonagemEstah().mp.getImagemBancoDeDados();
		_root.planeta.getTerrenoEmQuePersonagemEstah().definirPermissaoMovimentoMp(false);
		_root.terrenoQuarto.definirPermissaoMovimentoMp(false);
		_root.planeta.getTerrenoEmQuePersonagemEstah().mp.salvarPosicao();
		_root.planeta.getTerrenoEmQuePersonagemEstah()._alpha = 0;
		_root.planeta.getTerrenoEmQuePersonagemEstah()._y -= 10000;
		_root.menuEdicaoMC._alpha = 0;
		_root.comunicador.setPermissaoChatTerreno(false);
		if(_root.terrenoQuarto == undefined){
			_root.attachMovie(c_terreno_bd.getLinkBibliotecaTipo(c_terreno_bd.TIPO_QUARTO), "terrenoQuarto", _root.getNextHighestDepth());
			_root.inicializaDepthElementosLayout();
			_root.terrenoQuarto.inicializar(imagemPersonagem, _root.usuario_status.getImagemTerrenoQuarto());
		} else {
			_root.terrenoQuarto._alpha = 100;
			_root.terrenoQuarto._y += 10000;
			_root.terrenoQuarto.mp.retomarPosicaoSalva();
			_root.terrenoQuarto.mp._y += 50;
			_root.terrenoQuarto.centralizarTelaEm(new Point(_root.terrenoQuarto.mp._x, _root.terrenoQuarto.mp._y));
		}
		c_barra_predio.criar(1000, c_barra_predio.CIMA, "Acessando o quarto.");
		_global['setTimeout'](_root.terrenoQuarto, 'definirPermissaoMovimentoMp', 1000, true);
	}
	
	/*
	* Sai do quarto, indo para o terreno pelo qual o quarto foi acessado.
	*/
	public static function sairDoQuarto(){
		var imagemPersonagem:c_personagem_bd = _root.planeta.getTerrenoEmQuePersonagemEstah().mp.getImagemBancoDeDados();
		_root.planeta.getTerrenoEmQuePersonagemEstah().definirPermissaoMovimentoMp(false);
		_root.terrenoQuarto.definirPermissaoMovimentoMp(false);
		_root.menuEdicaoMC._alpha = 100;
		_root.terrenoQuarto._alpha = 0;
		_root.terrenoQuarto._y -= 10000;
		
		if(_root.planeta_status.tipo == c_planeta.TURMA){
			if(_root.usuario_status.getPermissao() == c_conta.getNivelAluno()){
				_root.comunicador.setPermissaoChatTerreno(_root.turma_status.habilitado_chatTerrenoParaAlunos);
			} else if(_root.usuario_status.getPermissao() == c_conta.getNivelMonitor()){
				_root.comunicador.setPermissaoChatTerreno(_root.turma_status.habilitado_chatTerrenoParaMonitores); 
			} else {
				_root.comunicador.setPermissaoChatTerreno(true); 
			}
		} else {
			_root.comunicador.setPermissaoChatTerreno(true); 
		}
		if(_root.planeta.getTerrenoEmQuePersonagemEstah() == undefined){
			c_aviso_com_ok.mostrar("c_terreno.sairDoQuarto precisa ser implementada.");
		} else {
			_root.planeta.getTerrenoEmQuePersonagemEstah()._alpha = 100;
			_root.planeta.getTerrenoEmQuePersonagemEstah()._y += 10000;
			_root.planeta.getTerrenoEmQuePersonagemEstah().mp.retomarPosicaoSalva();
			_root.planeta.getTerrenoEmQuePersonagemEstah().mp._y += 50;
			_root.terrenoQuarto.mp.salvarPosicao();
			_root.planeta.getTerrenoEmQuePersonagemEstah().centralizarTelaEm(new Point(_root.planeta.getTerrenoEmQuePersonagemEstah().mp._x, _root.planeta.getTerrenoEmQuePersonagemEstah().mp._y));
		}
		c_barra_predio.criar(1000, c_barra_predio.BAIXO, "Saindo do quarto.");
		_global['setTimeout'](_root.planeta.getTerrenoEmQuePersonagemEstah(), 'definirPermissaoMovimentoMp', 1000, true);
	}
	
	/*
	* Sincroniza este terreno com os dados do banco de dados passados.
	* ATENÇÃO: ela NÃO destruirá o que já existe neste terreno.
	*/
	public function carregarDados(imagem_bd_param:c_terreno_bd):Void{
		var frame:Number;
		var terreno_x:Number;
		var terreno_y:Number;
		var link:String;
		var id:String;
		var objeto:c_objeto_editavel;
		var matriz_parede:Array = imagem_bd_param.getDadosArvores();
		var matriz_objeto_link:Array = imagem_bd_param.getDadosCasas();
		var matriz_predios:Array = imagem_bd_param.getDadosPredios();
		var tamanhoMatrizParede:Number = matriz_parede.length;
		var tamanhoMatrizObjetoLink:Number = matriz_objeto_link.length;
		var tamanhoMatrizPredios:Number = matriz_predios.length;
	
		var objetosParaCarregar = tamanhoMatrizParede+tamanhoMatrizObjetoLink+tamanhoMatrizPredios;
		var objetosCarregados:Number=0;
		
		for (var i = 0; i < tamanhoMatrizParede; i++){
			frame = Number(matriz_parede[i][c_terreno_bd.MATRIZ_FRAME]);
			terreno_x = Number(matriz_parede[i][c_terreno_bd.MATRIZ_TERRENO_X]);
			terreno_y = Number(matriz_parede[i][c_terreno_bd.MATRIZ_TERRENO_Y]);
			//link = matriz_parede[i][c_terreno_bd.MATRIZ_LINK];
			id = String(matriz_parede[i][c_terreno_bd.MATRIZ_ID]);
		
			if(estaNaAreaUtilObjeto(_root.parede, new Point(terreno_x, terreno_y)) 
			   		and !teleporte.colideComDeslocamento(_root.parede, new Point(terreno_x, terreno_y))){
				adicionarArvore(frame, terreno_x, terreno_y, id);
			}
			objetosCarregados++;
		}
	
		for (var i = 0; i < tamanhoMatrizObjetoLink; i++){
			frame = Number(matriz_objeto_link[i][c_terreno_bd.MATRIZ_FRAME]);
			terreno_x = Number(matriz_objeto_link[i][c_terreno_bd.MATRIZ_TERRENO_X]);
			terreno_y = Number(matriz_objeto_link[i][c_terreno_bd.MATRIZ_TERRENO_Y]);
			link = matriz_objeto_link[i][c_terreno_bd.MATRIZ_LINK];
			id = String(matriz_objeto_link[i][c_terreno_bd.MATRIZ_ID]);
		
			if(estaNaAreaUtilObjeto(_root.objeto_link, new Point(terreno_x, terreno_y))
			   		and !teleporte.colideComDeslocamento(_root.objeto_link, new Point(terreno_x, terreno_y))){
				adicionarCasa(frame, terreno_x, terreno_y, id);
			}
			objetosCarregados++;
		}
	
		for (var i = 0; i < tamanhoMatrizPredios; i++){
			frame = Number(matriz_predios[i][c_terreno_bd.MATRIZ_FRAME]);
			terreno_x = Number(matriz_predios[i][c_terreno_bd.MATRIZ_TERRENO_X]);
			terreno_y = Number(matriz_predios[i][c_terreno_bd.MATRIZ_TERRENO_Y]);
			link = matriz_predios[i][c_terreno_bd.MATRIZ_LINK];
			id = String(matriz_predios[i][c_terreno_bd.MATRIZ_ID]);
		
			if(estaNaAreaUtilObjeto(_root.predio_alunos, new Point(terreno_x, terreno_y))
			   		and !teleporte.colideComDeslocamento(_root.predio_alunos, new Point(terreno_x, terreno_y))){
				adicionarPredioQuartos(_root.usuario_status.quarto_id, terreno_x, terreno_y, id);
			}
			objetosCarregados++;
		}
		
		matriz_parede = [];
		matriz_objeto_link = [];
		matriz_predios = [];
		
		//if(sistemaColisao.objetoEstahDentroDeOutro(this, this['mp'], _root.objeto_link)){
			this['mp']._x = teleporte.getPosicaoCentro().x;
			this['mp']._y = teleporte.getPosicaoCentro().y;
			centralizarTelaEm(new Point(this['mp']._x, this['mp']._y));
		//}
	}

	/*
	* Atualizações da mudança de quadro do terreno.
	*/
	private function atualizar():Void{
		if(this['mp'].estaParadoHa(5*1000)){ //milisegundos
			//centralizarTelaEm(new Point(this['mp']._x, this['mp']._y));
		} 
		if(mousePress){
			tempo_mouseClick++;
		} else {
			tempo_mouseClick = 0;
		}
		//função a seguir chamada toda vez que um novo frame é desenhado. tentar otimizar. - diogo
		//moveOp();		//Movimenta os ops pelo cenário 
		movimentaMp(); // funcao de movimento do mp pelo mouse e pelo teclado - Roger - 15/07/2009
	}

	/*
	* Decide o movimento do personagem, priorizando entrada pelo teclado.
	*/
	private function movimentaMp(){
		var direcaoMovimentoTeclado:String = getDirecaoTeclado();
	
		var direcaoMovimentoMouse:String = this['mp'].getDirecao(new Point(_xmouse, _ymouse),
																 new Point(this['mp']._x, this['mp']._y),
																 this['mp'].getMedidaVelocidade());
		var direcaoMovimentoAtual:String = this['mp'].getDirecao(pontoDestinoClickMp,
																 new Point(this['mp']._x, this['mp']._y),
																 this['mp'].getMedidaVelocidade());
		if(direcaoMovimentoTeclado != c_personagem.DIRECAO_INDEFINIDA){
			moveMp(direcaoMovimentoTeclado);
			mouseClick = false;
		} else if((mouseClick and !mousePress)
			 	 or (mouseClick and tempo_mouseClick < 120000)){
			moveMp(direcaoMovimentoAtual);
			mouseClick = false;
			tempo_mouseClick = 0;
		} else if(mousePress){
			moveMp(direcaoMovimentoMouse);
		} else {
			mouseClick = false;
		}
	}

	/*
	* Retorna a direção em que o personagem deve se mover, considerando somente o teclado.
	*/
	private function getDirecaoTeclado():String{
		if(Key.isDown(Key.UP) and Key.isDown(Key.RIGHT)){
			return c_personagem.DIRECAO_NORDESTE;
		} else if(Key.isDown(Key.UP) and Key.isDown(Key.LEFT)){
			return c_personagem.DIRECAO_NOROESTE;
		} else if(Key.isDown(Key.DOWN) and Key.isDown(Key.RIGHT)){
			return c_personagem.DIRECAO_SUDESTE;
		} else if(Key.isDown(Key.DOWN) and Key.isDown(Key.LEFT)){
			return c_personagem.DIRECAO_SUDOESTE;
		} else if(Key.isDown(Key.UP)){
			return c_personagem.DIRECAO_NORTE;
		} else if(Key.isDown(Key.DOWN)){
			return c_personagem.DIRECAO_SUL;
		} else if(Key.isDown(Key.RIGHT)){
			return c_personagem.DIRECAO_LESTE;
		} else if(Key.isDown(Key.LEFT)){
			return c_personagem.DIRECAO_OESTE;
		} else {
			return c_personagem.DIRECAO_INDEFINIDA;
		}
	}	

	/*
	* Move o terreno de forma que o ponto de parâmetro fique no centro da tela.
	* @param ponto_param Um ponto em coordenadas de terreno.
	*/
	public function centralizarTelaEm(ponto_param:Point){
		var posTelaPonto:Point = new Point(getTelaX(ponto_param.x) - Stage.width/2,
										   getTelaY(ponto_param.y) - Stage.height/2);
		moverTerreno(-posTelaPonto.x, -posTelaPonto.y);
	}

	/*
	* @return Sua imagem no banco de dados.
	*/
	public function getImagemBancoDeDados():c_terreno_bd{
		return imagemBancoDeDados;
	}
	
	/*
	* Retorna o personagem correspondente ao usuário logado.
	*/
	public function getPersonagem():c_personagem{
		return this['mp'];
	}
	
	/***/
	public function fecharLink():Void{
		centralizarTelaEm(new Point(this['mp']._x, this['mp']._y));
		definirPermissaoMovimentoMp(true);
	}
	
	/*
	* Chama um link.
	* @param link_param Link a ser chamado.
	*/
	private function chamarLink(link_param:String){
		var link:String = link_param;
		this['mp']._y += 50;
		definirPermissaoMovimentoMp(false);
		ExternalInterface.call("chamaLink", link_param);
		c_banco_de_dados.informaAcessoFuncionalidade(link_param, getImagemBancoDeDados().getIdentificacao());
		esperarLinkFechar();
	}
	
	/*
	* Deixa o processamento continuar, mas confere a cada segundo se o link aberto foi fechado.
	* Caso o link tenha sido fechado, pára de executar e manda executar as ações necessárias.
	*/
	public function esperarLinkFechar(){	
		if(ExternalInterface.call("colorBoxEstaAberta")){
			fecharLink();
		} else {
			setTimeout(esperarLinkFechar, 1000);
		}
	}
	
	/*
	* Retorna a profundidade ideal para o objeto ser colocado.
	* Deve ser utilizada para uso de attachMovieClip.
	* Retorna um depth ainda não usado. Não informa o depth atual do objeto.
	* Recebe o y do objeto utilizado como referência.
	*/
	private function getDepthObjeto(objeto_terreno_y_param:Number):Number{
		var depth:Number = objeto_terreno_y_param;
		depth += 10000;
		while(getInstanceAtDepth(depth) != undefined) {			//Evita que já exista algum objeto neste nível - Guto - 19.12.08 	
			depth++;
		}
		return depth;
	}
	
	/*
	* Retorna a menor profundidade ainda não ocupada.
	*/
	private function getNextLowestDepth():Number{
		var depth:Number = 1;
		while(getInstanceAtDepth(depth) != undefined){
			depth--;
		}
		return depth;
	}
	
	/*
	* @return Array de referências a todos os personagens neste terreno.
	*/
	public function getPersonagensOnline():Array{
		var personagensOnline:Array = new Array();
		var tamanhoIdsPersonagensOnline:Number = idsPersonagensOnline.length;
		for(var indice:Number=0; indice < tamanhoIdsPersonagensOnline; indice++){
			personagensOnline.push(this["personagem"+idsPersonagensOnline[indice]]);
		}
		return personagensOnline;
	}
	
	/*
	* Adiciona uma árvore com os dados passados de parâmetro.
	* Assume que os dados recebidos estão em coordenadas de terreno.
	* Retorna o MovieClip da árvore criada.
	* É necessária a identificação deste objeto no banco de dados para que mantenha-se sincronizado com sua imagem.
	*/
	public function adicionarArvore(tipo_aparencia_param:Number, x_param:Number, y_param:Number, identificacao_param:String):String{
		var nome_objeto:String = c_arvore.criarNome(id_corrente);
		var depth:Number;
		
		depth = getDepthObjeto(y_param + _root.parede.getSombra()._y + _root.parede.getSombra()._height/2) //Faço com que a referência esteja no meio da sobra. Isso evita problemas com objetos muito grandes e de base elipsoidal - Guto - 04.05.09 ;
		attachMovie(c_arvore.LINK_BIBLIOTECA, nome_objeto, depth);
		this[nome_objeto].inicializar(identificacao_param);
		this[nome_objeto].definirTipoTerreno(getImagemBancoDeDados().getPlaneta().getAparencia());
		this[nome_objeto].definirTipoAparencia(tipo_aparencia_param);
		this[nome_objeto]._x = x_param;
		this[nome_objeto]._y = y_param;
		sistemaColisao.forcarRegistroColisaoObjeto(this[nome_objeto]);
		atualizarProfundidadeObjetos();
		
		id_corrente++;
		return nome_objeto;
	}
	
	/*
	* Adiciona uma casa com os dados passados de parâmetro.
	* Assume que os dados recebidos estão em coordenadas de terreno.
	* Retorna o MovieClip da casa criada.
	* É necessária a identificação deste objeto no banco de dados para que mantenha-se sincronizado com sua imagem.
	*/
	public function adicionarCasa(tipo_aparencia_param:Number, x_param:Number, y_param:Number, identificacao_param:String):String{
		var nome_objeto:String = c_casa.criarNome(id_corrente);
		var nome_indicador:String;
		var nome_objeto_acesso:String = c_objeto_acesso.criarNome(nome_objeto);
		var depth:Number;
	
		depth = getDepthObjeto(y_param + _root.objeto_link.getSombra()._y + _root.objeto_link.getSombra()._height/2); //Tirar 50 para não ter o problema da getSombra().
		attachMovie(c_casa.LINK_BIBLIOTECA, nome_objeto, depth);
		this[nome_objeto].inicializar(identificacao_param);
		this[nome_objeto].definirTipoLink(tipo_aparencia_param);
		this[nome_objeto]._x = x_param;
		this[nome_objeto]._y = y_param;
		this[nome_objeto].definirTipoTerreno(getImagemBancoDeDados().getPlaneta().getAparencia());
		sistemaColisao.forcarRegistroColisaoObjeto(this[nome_objeto]);
		attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_CASA, nome_objeto_acesso, getNextLowestDepth());
		this[nome_objeto_acesso]._x = this[nome_objeto]._x + this[nome_objeto].getPosicaoAcesso().x;
		this[nome_objeto_acesso]._y = this[nome_objeto]._y + this[nome_objeto].getPosicaoAcesso().y;
		adicionarObjetoAcesso(this[nome_objeto_acesso], this[nome_objeto].getLink());
		this['mapa'].adicionarIndicador(x_param + this[nome_objeto].getSombra()._x, 
									    y_param + this[nome_objeto].getSombra()._y, 
										nome_objeto, c_indicador.TIPO_CASA, this[nome_objeto].getNomeIndicador());
		atualizarProfundidadeObjetos();
		
		id_corrente++;
		return nome_objeto;
	}
	
	/*
	* Adiciona uma casa com os dados passados de parâmetro.
	* Assume que os dados recebidos estão em coordenadas de terreno.
	*/
	public function adicionarObjetoAcesso(objeto_acesso_param:c_objeto_acesso, link_param:String){
		objeto_acesso_param._alpha = 0;
		objeto_acesso_param.swapDepths(getNextLowestDepth()); //para ficar embaixo dos outros
		objeto_acesso_param.definirLink(link_param);
		sistemaColisao.forcarRegistroColisaoObjeto(objeto_acesso_param);
	}

	/*
	* Adiciona um prédio de quartos de alunos com os dados passados de parâmetro.
	* Assume que os dados recebidos estão em coordenadas de terreno.
	* Retorna o MovieClip do prédio criado.
	* É necessária a identificação deste objeto no banco de dados para que mantenha-se sincronizado com sua imagem.
	*/
	public function adicionarPredioQuartos(id_quarto_param:String, x_param:Number, y_param:Number, identificacao_param:String):String{
		var nome_objeto:String = c_predio_alunos.criarNome(id_corrente);
		var nome_objeto_acesso:String = c_objeto_acesso.criarNome(nome_objeto);
		var depth:Number;
		
		depth = getDepthObjeto(y_param + _root.predio_alunos.getSombra()._y + _root.predio_alunos.getSombra()._height/2);
		attachMovie(c_predio_alunos.LINK_BIBLIOTECA, nome_objeto, depth);
		this[nome_objeto].inicializar(identificacao_param);
		this[nome_objeto]._x = x_param;
		this[nome_objeto]._y = y_param;
		sistemaColisao.forcarRegistroColisaoObjeto(this[nome_objeto]);
		attachMovie(c_objeto_acesso.LINK_BIBLIOTECA_PREDIO, nome_objeto_acesso, getNextLowestDepth());
		this[nome_objeto_acesso]._x = this[nome_objeto]._x + this[nome_objeto].getPosicaoAcesso().x;
		this[nome_objeto_acesso]._y = this[nome_objeto]._y + this[nome_objeto].getPosicaoAcesso().y;
		this[nome_objeto_acesso].definirTipo(c_objeto_acesso.TIPO_PREDIO);
			
		adicionarObjetoAcesso(this[nome_objeto_acesso], c_objeto_acesso.getLinkAcessoQuarto(id_quarto_param));
		this['mapa'].adicionarIndicador(x_param + this[nome_objeto].getSombra()._x, 
									    y_param + this[nome_objeto].getSombra()._y, 
										nome_objeto, c_indicador.TIPO_PREDIO, "Prédio");
		
		atualizarProfundidadeObjetos();
		
		id_corrente++;
		return nome_objeto;
	}
	
	/*
	* Adiciona um personagem com os dados passados de parâmetro.
	* Assume que os dados recebidos estão em coordenadas de terreno.
	* Retorna o MovieClip do personagem criado.
	* É necessária a identificação deste objeto no banco de dados para que mantenha-se sincronizado com sua imagem.
	*/
	public function adicionarPersonagem(dados_personagem_param:c_personagem_bd):MovieClip{
		var nome_objeto:String = "personagem"+dados_personagem_param.getIdentificacaoBancoDeDados();
		var depth:Number = getDepthObjeto(dados_personagem_param.getPosicaoAtual().y + _root.op.getSombra()._height/2);

		attachMovie(c_personagem.LINK_BIBLIOTECA, nome_objeto, getNextLowestDepth());
		this[nome_objeto].inicializar(dados_personagem_param, false);

		//Escreve as tiles que o op ocupa na matriz de tiles - 15.07.09
		sistemaColisao.registrarColisaoObjeto(this[nome_objeto]);
		idsPersonagensOnline.push(dados_personagem_param.getIdentificacaoBancoDeDados());

		//Cria o indicador, no mapa, correspondente ao op que acabou de ser criado
		this['mapa'].adicionarIndicador(this[nome_objeto]._x, this[nome_objeto]._y, nome_objeto, c_indicador.TIPO_OP, dados_personagem_param.getNome());
		return this[nome_objeto];
	}
	
	/*
	* @param id_procurado_param Id do personagem a ser procurado.
	* @return Booleano indicando se o terreno possui personagem com a id.
	*/
	public function personagemOnline(id_procurado_param:String):Boolean{
		if(this["personagem"+id_procurado_param] != undefined){
			return true;
		} else {
			return false;
		}
	}

	/*
	* Atualiza os dados dos personagens que estão online.
	* Qualquer personagem online que não seja passado como parâmetro é considerado offline e removido.
	* @param personagensOline_param Array de c_personagem_bd com dados de todos os personagens online.
	*/
	public function atualizarPersonagens(personagensOnline_param:Array):Void{
		var personagemRecebido:c_personagem_bd;
		var personagensQueEstavamOnline:Array = idsPersonagensOnline;
		var aindaEstahOnline:Boolean = false;
		var tamanhoPersonagensOnlineParametro:Number = personagensOnline_param.length;
		var tamanhoPersonagensQueEstavamOnline:Number = personagensQueEstavamOnline.length;
		var tamanhoIdsPersonagensOnline:Number;
		idsPersonagensOnline = new Array(); //Dados de todos personagens online serão atualizados.
		for(var indice:Number = 0; indice < tamanhoPersonagensOnlineParametro; indice++){
			personagemRecebido = personagensOnline_param[indice];
			if(!personagemOnline(personagemRecebido.getIdentificacaoBancoDeDados())) { //se não existe, cria Op
				adicionarPersonagem(personagemRecebido);
			} else { //se existe
				idsPersonagensOnline.push(personagemRecebido.getIdentificacaoBancoDeDados());
				this["personagem"+personagemRecebido.getIdentificacaoBancoDeDados()].sincronizar(personagemRecebido);
				
				var depth:Number = -(-this["personagem"+personagemRecebido.getIdentificacaoBancoDeDados()].getImagemBancoDeDados().getPosicaoAtual().y).valueOf();
				depth -= (-_root.op.getSombra()._height/2).valueOf();
				this["personagem"+personagemRecebido.getIdentificacaoBancoDeDados()].swapDepths(getDepthObjeto(depth.valueOf()));
			}
		}

		for(var indiceTemp:Number = 0; indiceTemp < tamanhoPersonagensQueEstavamOnline; indiceTemp++){
			aindaEstahOnline = false;
			tamanhoIdsPersonagensOnline = idsPersonagensOnline.length;
			for(var indiceAtual:Number = 0; indiceAtual < tamanhoIdsPersonagensOnline; indiceAtual++){
				if(idsPersonagensOnline[indiceAtual] == personagensQueEstavamOnline[indiceTemp]){
					aindaEstahOnline = true;
				}
			}
			if(!aindaEstahOnline){
				removerPersonagem(personagensQueEstavamOnline[indiceTemp]);
			}
		}
	}

	/*
	* Remove o personagem que possui a id passada como parâmetro.
	*/
	public function removerPersonagem(id_personagem_param:String){
		var nome_personagem:String = "personagem" + id_personagem_param;
		sistemaColisao.limparColisaoObjeto(this[nome_personagem]);
		this[nome_personagem].removeMovieClip();
		this['mapa'].deletarIndicador(nome_personagem);
	}

	/*
	* Remove o objeto.
	* @param id_param A id do objeto a ser removido.
	*/
	public function removerObjeto(id_param:String){
		sistemaColisao.limparColisaoObjeto(this[id_param]);
		sistemaColisao.limparColisaoObjeto(this[c_objeto_acesso.criarNome(id_param)]);
		this[id_param]._y -= 5000;
		this[c_objeto_acesso.criarNome(id_param)].removeMovieClip();
		this['mapa'].deletarIndicador(id_param);
	}

	/*
	* @param permissao_param Determina se o personagem do usuário logado pode (true) ou não (false) se movimentar.
	*/
	public function definirPermissaoMovimentoMp(permissao_param:Boolean):Void{
		permissaoMovimento = permissao_param;
	}
	
	/*---------------------------------------------------
	*	Função que desenvolve o movimento e compara limites do mp (meu personagem)
	* Quando sua tile não varia, a segunda chamada a esta função retornará false, mesmo que seja um tile ocupada.
	* Ao tornar a variável "colisao" local, causei este erro de lógica.
	* É necessário trocar a função "houveVariacaoTilesMp" por "tileMpEstáOcupadaEDáColisao"
		
		
	* É necessário estabelecer um velocidade por segundo para o personagem.
	* Esta velocidade vai garantir um número máximo k de vezes que esta função será chamada por segundo.
	* À partir de então, fazer com que o terreno movimente-se de forma suave movendo-se 1 unidade em x e 1 unidade em y
	* a cada 1/(k*total_que_o_terreno_precisa_movimentar-se_por_chamada) segundos.
	---------------------------------------------------*/
	public var contagem:Number;
	public function moveMp(direcao_param:String):Boolean {
		var nomeObjetoMesmaTile:String;
		var objetosMesmaTile:Array = new Array();
		var nomeObjetoAtingido:String;
		var objetosAtingidos:Array = new Array();
		var colisao:Boolean = false;
		var posicaoDestino:Point = this['mp'].getPosicaoDestino(direcao_param, new Point(this['mp']._x, this['mp']._y));
		var deslocamento_x:Number;
		var deslocamento_y:Number;
		var tamanhoObjetosMesmaTile:Number;
		var tamanhoCasasMesmaTile:Number;
		var ehLink:Boolean = false;
		var ehPonte:Boolean = false;
		var ehQuarto:Boolean = false;
		var mudouTerreno:Boolean = false;
		
		deslocamento_x = posicaoDestino.x - this['mp']._x;
		deslocamento_y = posicaoDestino.y - this['mp']._y;
		
		if(!permissaoMovimento){
			colisao = true;
		} else if(!estaNaAreaUtil(posicaoDestino.x, posicaoDestino.y)){
			colisao = true;
		} else { //Caso não tenha atingido os limites do terreno, testa as tiles para identificar colisões - Guto - 10.07.09
			objetosMesmaTile = sistemaColisao.getObjetosMesmaTile(this['mp'], deslocamento_x, deslocamento_y);
			
			tamanhoObjetosMesmaTile = objetosMesmaTile.length;

			var casasQueRecemAbriramPorta:Array = new Array();
			for(var indice:Number = 0; indice < tamanhoObjetosMesmaTile; indice++){
				nomeObjetoMesmaTile = objetosMesmaTile[indice];
				
				if(this[nomeObjetoMesmaTile].abrirPorta != undefined){
					if(!arrayContem(casasComPortaAberta, nomeObjetoMesmaTile)){
						this[nomeObjetoMesmaTile].abrirPorta();
					}
					casasQueRecemAbriramPorta.push(nomeObjetoMesmaTile);
				}
				
				ehLink = false;
				ehPonte = false;
	
				if(sistemaColisao.houveColisao(this['mp'], this[nomeObjetoMesmaTile], deslocamento_x, deslocamento_y)
				   and !mudouTerreno and nomeObjetoMesmaTile != "mp"){
			
					colisao = true;
					ehLink = (this[nomeObjetoMesmaTile].recuperarLink != undefined);
					ehPonte = (ehLink and this[nomeObjetoMesmaTile].recuperarLink() == undefined);
					ehQuarto = (this['cama'] != undefined);
		
					if(ehLink and !ehPonte){
						if(this[nomeObjetoMesmaTile].recuperarTipo(c_objeto_acesso.TIPO_PREDIO)){
							c_terreno.irParaQuarto();
						} else if(ehQuarto){
							c_terreno.sairDoQuarto();
						} else {
							chamarLink(this[nomeObjetoMesmaTile].recuperarLink());
						}
					} else if(ehLink and ehPonte){
						if(!this.ponteHabilitada){
							contagem=0;
							colisao = false;
							ehLink = false; 
							ehPonte = false;
						} else {
							mudouTerreno = true;
						}
					}
				}
			}
			
			tamanhoCasasMesmaTile = casasComPortaAberta.length;
			for(var indice:Number=0; indice < tamanhoCasasMesmaTile ; indice++){
				var nomeCasa:String = casasComPortaAberta[indice];
				if(!arrayContem(casasQueRecemAbriramPorta, nomeCasa)){
					this[nomeCasa].fecharPorta();
				}
			}
			
			casasComPortaAberta = casasQueRecemAbriramPorta;
		}
	
		if (!colisao) {
			contagem++;
			if(contagem==5){
				habilitarPontes(true);
			}
			this['mp'].moverDirecao(direcao_param, true);
			
			if(atingiuLimiteAreaMovimentacao(deslocamento_x, 0) and atingiuLimiteAreaMovimentacao(0, deslocamento_y)){
				moverTerreno(-deslocamento_x, -deslocamento_y); 
			} else if(atingiuLimiteAreaMovimentacao(deslocamento_x, 0)) {	//A movimentação do terreno precisa ser mais suave.
				moverTerreno(-deslocamento_x, 0); 
			} else if(atingiuLimiteAreaMovimentacao(0, deslocamento_y)) {	//A movimentação do terreno precisa ser mais suave.
				moverTerreno(0, -deslocamento_y); 
			}
			
			ajustarProfundidadeMp();
		} else {
			if(!mudouTerreno){
				deslizaMp(direcao_param);
			}
		}
		
		if(mudouTerreno){
			trocarTerreno();
		}

		return colisao;
	}
	
	/*
	* Dado o vetor de variação do movimento do personagem que falha por colisão, 
	* esta função tenta encontrar de forma eficiente um vetor parecido que não falhe
	* e realizar o movimento.
	* Caso realmente não seja possível encontrar eficientemente um vetor assim, 
	* cessa o movimento do personagem.
	*/
	private function deslizaMp(direcao_param:String){
		var naoMoveu:Boolean = false;
		switch(direcao_param){
			case c_personagem.DIRECAO_NOROESTE:
			case c_personagem.DIRECAO_SUDOESTE: naoMoveu = moveMp(c_personagem.DIRECAO_OESTE);
				break;
			case c_personagem.DIRECAO_NORDESTE:
			case c_personagem.DIRECAO_SUDESTE: naoMoveu = moveMp(c_personagem.DIRECAO_LESTE);
				break;
		}
		if(naoMoveu){
			switch(direcao_param){
				case c_personagem.DIRECAO_NOROESTE:
				case c_personagem.DIRECAO_NORDESTE: naoMoveu = moveMp(c_personagem.DIRECAO_NORTE);
					break;
				case c_personagem.DIRECAO_SUDOESTE:
				case c_personagem.DIRECAO_SUDESTE: naoMoveu = moveMp(c_personagem.DIRECAO_SUL);
					break;
			}
		}
	}
	
	/*
	* Pára a movimentação do personagem do usuário logado.
	*/
	public function paraMp():Void{
		this['mp'].parar();
	}
	
	/*
	* Ajusta a profundidade do personagem do usuário logado.
	*/
	private function ajustarProfundidadeMp(){
		var depth:Number;
		depth = getDepthObjeto(this['mp'].getSombra()._y + this['mp'].getSombra()._height/2 + this['mp']._y);
		this['mp'].swapDepths(depth);
	}
	
	/*
	* Indica se o personagem do usuário logado atingiu o limite em que a tela começa a movimentar-se.
	*/
	public function atingiuLimiteAreaMovimentacao(valueX:Number, valueY:Number):Boolean{
		if(((Math.abs(getTelaX(this['mp']._x + valueX) - Stage.width/2) > LIMITE_MOVIMENTACAO_COMPRIMENTO) or 
			(Math.abs(getTelaY(this['mp']._y + valueY) - Stage.height/2) > LIMITE_MOVIMENTACAO_LARGURA)) ){
			return true;
		} else {
			return false;
		}
	}
	
	/*
	* Converte as coordenadas passadas para coordenada de tela.
	*/
	public function getTelaX(coordenadas_terreno_param:Number):Number{
		return _parent._x + _x + coordenadas_terreno_param;
	}
	
	/*
	* Converte as coordenadas passadas para coordenada de tela.
	*/
	public function getTelaY(coordenadas_terreno_param:Number):Number{
		return _parent._y + _y + coordenadas_terreno_param;
	}
	
	/*
	* Desloca o terreno com todos seus objetos.
	* Não move o personagem!
	*/
	public function moverTerreno(deslocamento_x_param:Number, deslocamento_y_param:Number){
		_parent._x += deslocamento_x_param;
		_parent._y += deslocamento_y_param;
		colocarMapaNoLugar();
	}
	
	/**
	* Ajusta a posição do mapa no terreno, fazendo-o aparecer no canto direito inferior da tela.
	*/
	public function colocarMapaNoLugar():Void{
		this['mapa'].mover(new Point(Stage.width - this['mapa']._width - _parent._x - _x, 
									 Stage.height - this['mapa']._height - _parent._y - _y));
	}
	
	/*
	* Atualiza a profundidade dos objetos que devem ficar acima de todos os outros.
	*/
	private function atualizarProfundidadeObjetos():Void{
		this['mapa'].swapDepths(getNextHighestDepth());
	}
	
	//---- Colisão ~~ Estas funções devem ser implementadas nas classes que especializem esta.
	/*
	* Deve indicar se o ponto (x,y) do parâmetro pertence à área útil do terreno.
	* Recebe coordenadas de terreno.
	*/
	public function estaNaAreaUtil(x_param:Number, y_param:Number):Boolean{
		return false;
	}
	/*
	* Indica se o objeto de colisão do parâmetro pertence à área útil do terreno.
	* A posição do objeto é dada como parâmetro também.
	* @param objeto_param Um modelo, indicando o tipo do objeto.
	* @param posicao_param A posição do objeto. Caso não esteja definida, é considerada a posição do objeto.
	*/
	public function estaNaAreaUtilObjeto(objeto_param:c_objeto_colisao, posicao_param:Point):Boolean{
		return false;
	}
	/*
	* Para o dado y, retorna o x que é o maior possível relacionado com o y dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteLeste(y_param:Number):Number{
		return 0;
	}
	/*
	* Para o dado y, retorna o x que é o menor possível relacionado com o y dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteOeste(y_param:Number):Number{
		return 0;
	}
	/*
	* Para o dado x, retorna o y que é o menor possível relacionado com o x dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteNorte(y_param:Number):Number{
		return 0;
	}
	/*
	* Para o dado x, retorna o y que é o maior possível relacionado com o x dentro da área útil.
	* Recebe coordenadas de terreno.
	*/
	public function getLimiteSul(y_param:Number):Number{
		return 0;
	}
	
	/*
	* Pontos de entrada são pontos em que o personagem pode ficar e que são o mais próximo possível de pontes.
	* Devem ser sobrecarregadas em cada classe que use este template.
	*/
	public function getPontoEntradaNoroeste():Point{
		return new Point(0,0);
	}
	public function getPontoEntradaNordeste():Point{
		return new Point(0,0);
	}
	public function getPontoEntradaSudeste():Point{
		return new Point(0,0);
	}
	public function getPontoEntradaSudoeste():Point{
		return new Point(0,0);
	}
	
	/*
	* @param ponto_param Ponto que será comparado com as posições das pontes deste terreno.
	* @return Um número (conforme definidos neste classe) que representa a ponte que está mais próxima de ponto_param.
	*/
	public function getPonteMaisProxima(ponto_param:Point):Number{
		var ponteMaisProxima:Number;
		var menorDistancia:Number;
		
		ponteMaisProxima = c_terreno.PONTE_NOROESTE;
		menorDistancia = Point.distance(ponto_param, getPontoEntradaNoroeste());
		if(Point.distance(ponto_param, getPontoEntradaNordeste()) < menorDistancia){
			ponteMaisProxima = c_terreno.PONTE_NORDESTE;
			menorDistancia = Point.distance(ponto_param, getPontoEntradaNordeste());
		}
		if(Point.distance(ponto_param, getPontoEntradaSudeste()) < menorDistancia){
			ponteMaisProxima = c_terreno.PONTE_SUDESTE;
			menorDistancia = Point.distance(ponto_param, getPontoEntradaSudeste());
		}
		if(Point.distance(ponto_param, getPontoEntradaSudoeste()) < menorDistancia){
			ponteMaisProxima = c_terreno.PONTE_SUDOESTE;
			menorDistancia = Point.distance(ponto_param, getPontoEntradaSudoeste());
		}
		
		return ponteMaisProxima;
	}
	
	//---- Auxiliares
	/*
	* Indica se o array contém o elemento procurado.
	*/
	public static function arrayContem(array_param:Array, procura_param:String):Boolean{
		var indice:Number=0;
		var encontrado:Boolean = false;
		var tamanhoArrayParametro:Number = array_param.length;
		while(indice < tamanhoArrayParametro and !encontrado){
			if(array_param[indice] == procura_param){
				encontrado = true;
			}
			indice++;
		}
		return encontrado;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
