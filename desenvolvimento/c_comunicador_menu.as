import mx.utils.Delegate;
import mx.events.EventDispatcher;
import flash.geom.Point;

/*
* Menu do comunicador, que permite escolher chat visível, esconder balões e gerenciar chats ativos.
*/
dynamic class c_comunicador_menu extends MovieClip{
//dados	
	/*
	* Link para símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "comunicadorMenu";

	/*
	* Eventos.
	*/
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;

	/*
	* Botões que abrem as abas.
	*/
	private var btTerreno:MovieClip; //Aba que dá acesso a informações de personagens no mesmo terreno e ao chat do terreno.
	private var btTurma:MovieClip; //Aba que dá acesso a informações de personagens das turmas do usuário e aos chats destas.
	private var btAmigo:MovieClip; //Aba que dá acesso aos contatos do usuário e a chats com estes.

	/*
	* Botão para habilitar/desabilitar a visão de balões de conversa.
	*/
	private var btToggleVisibilidadeBaloes:MovieClip;

	/*
	* Aba de gerência do chat amigo.
	*/
	public var abaAmigo:MovieClip;
	
	/*
	* Variáveis de conexão com o servidor.
	*/
	private var enviaTerreno:LoadVars;
	private var recebeTerreno:LoadVars;
	private var enviaDadosTurmas:LoadVars;
	private var recebeDadosTurmas:LoadVars;
	
	/*
	* Abas que possui. Cada número determina uma aba.
	*/
	public static var ABA_TERRENO:Number = 1;
	public static var ABA_TURMA:Number = 2;
	public static var ABA_AMIGO:Number = 3;
	public static var ABA_VAZIA:Number = 4;
	
	/*
	* Frames em que encontram-se as abas.
	*/
	public static var FRAME_ABA_TERRENO:Number = 1;
	public static var FRAME_ABA_TURMA:Number = 2;
	public static var FRAME_ABA_AMIGO:Number = 3;
	public static var FRAME_ABA_VAZIA:Number = 4;
	
	/*
	* Menus do tipo c_select.
	*/
	private var menu_usuarios_terreno:c_select = undefined; //Menu da aba Terreno com os usuários logados no terreno em que está o usuário logado.
	private var menu_turmas:c_select = undefined; //Menu da aba Turma com as turmas do usuário logado.
	private var menu_usuarios_turmas:c_select = undefined; //Menu da aba Turma com os usuários pertencentes as turmas do usuário logado que estão online.
	
	/*
	* Nomes de usuários no mesmo terreno do usuário logado.
	*/
	private var usuarios_terreno_recebidos:Array = new Array();
	
	/*
	* Nomes de turmas recebidas do BD.
	* É um array com nomes de turmas em que cada índice corresponde ao índice do array de nomes de alunos do array de alunos.
	*/
	private var turmas_recebidas:Array = new Array();
	
	/*
	* Nomes de usuários que pertencem a turmas recebidas do BD.
	* É um array de arrays em que cada índice corresponde a um array de usuários que estão na turma de mesmo índice no array de turmas.
	*/
	private var usuarios_turmas_recebidas:Array = new Array();
	
//métodos
	/*
	* Inicializa e permite configurar os chats que terão no terreno.
	* @param ha_chatTerreno_param Booleano que determina se o chat de terreno está habilitado.
	* @param ha_chatTurma_param Booleano que determina se o chat de turmas está habilitado.
	* @param ha_chatAmigo_param Booleano que determina se o chat de amigos está habilitado.
	*/
	public function inicializar(ha_chatTerreno_param:Boolean, ha_chatTurma_param:Boolean, ha_chatAmigo_param:Boolean):Void{
		mx.events.EventDispatcher.initialize(this);
		
		this['btToggleVisibilidadeBaloes'].onPress = function(){
			if(_currentframe == 1){
				gotoAndStop(2);
			} else if(_currentframe == 2){
				gotoAndStop(1);
			}
			_parent.dispatchEvent({target:this, type:"toggleVisibilidadeBaloes"});
		};
		
		btTerreno.onPress = function(){ _parent.abrirAba(c_comunicador_menu.ABA_TERRENO);
										_parent.dispatchEvent({target:this, type:"botaoPressionado", tipoAcao: c_fala_comunicador.ATALHO_VER_CHAT_TERRENO});}
		btTurma.onPress = function(){ _parent.abrirAba(c_comunicador_menu.ABA_TURMA);
									  _parent.dispatchEvent({target:this, type:"botaoPressionado", tipoAcao: c_fala_comunicador.ATALHO_VER_CHAT_TURMA});}
		btAmigo.onPress = function(){ _parent.abrirAba(c_comunicador_menu.ABA_AMIGO);
									  _parent.dispatchEvent({target:this, type:"botaoPressionado", tipoAcao: c_fala_comunicador.ATALHO_VER_CHAT_AMIGO});}
		
		if(!ha_chatTerreno_param){
			btTerreno._visible = false;
		}
		if(!ha_chatTurma_param){
			btTurma._visible = false;
		}
		if(!ha_chatAmigo_param){
			btAmigo._visible = false;
		}
		
		inicializarAbas();
		
		abrirAba(ABA_VAZIA);
	}
	
	/*
	* Inicializações das abas.
	*/
	private function inicializarAbas():Void{
		abrirAba(ABA_TERRENO);
		attachMovie(c_select.LINK_BIBLIOTECA, "menu_usuarios_terreno_dummy", getNextHighestDepth(), {_x:this['espacoDireita']._x, _y:this['espacoDireita']._y});
		menu_usuarios_terreno = this['menu_usuarios_terreno_dummy'];
		menu_usuarios_terreno.inicializar(6, new Array(), "Planetários neste terreno");
		menu_usuarios_terreno.setTipoInvisivel(true);
		menu_usuarios_terreno.redimensionar(this['espacoDireita']._width, this['espacoDireita']._height);
		menu_usuarios_terreno.addEventListener("botaoPressionado", Delegate.create(this, botaoDeSelectFoiPressionado));	
		
		abrirAba(ABA_TURMA);
		attachMovie(c_select.LINK_BIBLIOTECA, "menu_usuarios_turmas_dummy", getNextHighestDepth(), {_x:this['espacoEsquerda']._x, _y:this['espacoEsquerda']._y});
		menu_usuarios_turmas = this['menu_usuarios_turmas_dummy'];
		menu_usuarios_turmas.inicializar(6, new Array(), "Meus colegas online");
		menu_usuarios_turmas.setTipoInvisivel(true);
		menu_usuarios_turmas.redimensionar(this['espacoEsquerda']._width, this['espacoEsquerda']._height);
		menu_usuarios_turmas.addEventListener("botaoPressionado", Delegate.create(this, botaoDeSelectFoiPressionado));	
		attachMovie(c_select.LINK_BIBLIOTECA, "menu_turmas_dummy", getNextHighestDepth(), {_x:this['espacoDireita']._x, _y:this['espacoDireita']._y});
		menu_turmas = this['menu_turmas_dummy'];
		menu_turmas.inicializar(6, new Array(), "Minhas turmas");
		menu_turmas.setTipoInvisivel(true);
		menu_turmas.redimensionar(this['espacoDireita']._width, this['espacoDireita']._height);
		menu_turmas.addEventListener("botaoPressionado", Delegate.create(this, botaoDeSelectFoiPressionado));	
		
		abrirAba(ABA_AMIGO);
		attachMovie(c_aba_amigo.LINK_BIBLIOTECA, "abaAmigo", getNextHighestDepth(), {_x:3});
		abaAmigo = this['abaAmigo'];
		abaAmigo.inicializar();
		abaAmigo.addEventListener("botaoPressionado", Delegate.create(this, botaoFoiPressionado));
	}
	
	/*
	* Função que abre determinada aba, mostrando e escondendo a aba aberta, se houver.
	* @param aba_param A aba a ser aberta, conforme definido nos dados desta classe.
	*/
	public function abrirAba(aba_param:Number):Void{
		switch(_currentframe){
			case FRAME_ABA_TERRENO: 
						btTerreno.gotoAndStop(1);
						if(menu_usuarios_terreno != undefined){
							menu_usuarios_terreno._visible = false;
						}
				break;
			case FRAME_ABA_TURMA: 
						btTurma.gotoAndStop(1);
						this['espacoEsquerda']._visible = false;
						if(menu_turmas != undefined){
							menu_turmas._visible = false;
						}
						if(menu_usuarios_turmas != undefined){
							menu_usuarios_turmas._visible = false;
						}
				break;
			case FRAME_ABA_AMIGO: 
						this['espacoEsquerda']._visible = false;
						btAmigo.gotoAndStop(1);
						if(abaAmigo != undefined){
							abaAmigo.fechar();
						}		
				break;
			default: this['espacoEsquerda']._visible = false;
				break;
		}
		switch(aba_param){
			case ABA_TERRENO: 
					btTerreno.gotoAndStop(2);
					gotoAndStop(FRAME_ABA_TERRENO);
					this['espacoEsquerda']._visible = false;
					if(menu_usuarios_terreno != undefined){
						menu_usuarios_terreno._visible = true;
						atualizarUsuariosTerreno();
					}
				break;
			case ABA_TURMA: 
					btTurma.gotoAndStop(2);
					this['espacoEsquerda']._visible = true;
					gotoAndStop(FRAME_ABA_TURMA);
					if(menu_turmas != undefined){
						menu_turmas._visible = true;
					}
					if(menu_usuarios_turmas != undefined){
						menu_usuarios_turmas._visible = true;
						atualizarDadosTurmas();
					}
				break;
			case ABA_AMIGO: 
					this['espacoEsquerda']._visible = false;
					this['espacoDireita']._visible = false;
					btAmigo.gotoAndStop(2);
					gotoAndStop(FRAME_ABA_AMIGO);
					if(abaAmigo != undefined){
						abaAmigo.abrir();
					}
				break;
			case ABA_VAZIA:
					this['espacoEsquerda']._visible = false;
					this['espacoDireita']._visible = false;
					gotoAndStop(FRAME_ABA_VAZIA);
			default: fazNada();
				break;
		}
	}
	
	/*
	* Pede ao banco de dados os usuários que estão no terreno do usuário logado.
	* Não é necessário enviar dados.
	*/
	private function atualizarUsuariosTerreno():Void{
		enviaTerreno = new LoadVars();
		recebeTerreno = new LoadVars();
		recebeTerreno.onLoad = Delegate.create(this, usuariosTerrenoRecebidos);
		enviaTerreno.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_PROCURAR_USUARIOS_TERRENO, recebeTerreno, "POST");
	}
	/*
	* Executada ao receber os usuários com uma chamada de atualizarUsuariosTerreno .
	*/
	private function usuariosTerrenoRecebidos(success):Void{
		if(success and recebeTerreno.erro == c_banco_de_dados.SEM_ERRO){
			usuarios_terreno_recebidos = new Array();
			for(var indice:Number=0; indice<recebeTerreno.numeroUsuariosRecebidos; indice++){
				usuarios_terreno_recebidos.push(recebeTerreno['nomeUsuario'+indice]);
			}
			atualizarMenuTerreno();
		}
	}
	
	/*
	* Pede ao banco de dados nomes de turmas do usuário logado e de usuários pertencentes a estas.
	*/
	private function atualizarDadosTurmas():Void{
		enviaDadosTurmas = new LoadVars();
		recebeDadosTurmas = new LoadVars();
		recebeDadosTurmas.onLoad = Delegate.create(this, dadosTurmasRecebidos);
		enviaDadosTurmas.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_PROCURAR_DADOS_TURMAS, recebeDadosTurmas, "POST");
	}
	/*
	* Executada ao receber os usuários com uma chamada de atualizarUsuariosTurmasUsuario.
	*/
	private function dadosTurmasRecebidos(success):Void{
		var usuarios_turma:Array;
		if(success and recebeDadosTurmas.erro == c_banco_de_dados.SEM_ERRO){
			turmas_recebidas = new Array();
			usuarios_turmas_recebidas = new Array();
			for(var indiceTurma:Number=0; indiceTurma < recebeDadosTurmas.numeroTurmas; indiceTurma++){
				turmas_recebidas.push(recebeDadosTurmas['nomeTurma'+indiceTurma]);
				usuarios_turma = new Array();
				for(var indiceUsuario:Number=0; indiceUsuario < recebeDadosTurmas['numeroUsuariosTurma'+indiceTurma]-1; indiceUsuario++){
					usuarios_turma.push(recebeDadosTurmas['nomeUsuario'+indiceTurma+','+indiceUsuario]);
				}
				usuarios_turmas_recebidas.push(usuarios_turma);
			}
			atualizarMenuTurmas();
			atualizarMenuUsuariosTurmas();
		}
	}
	
	/*
	* As atualizações dos menus são feitas com base nos conteúdos dos arrays a seguir:
	*	usuarios_terreno_recebidos	-	menu_usuarios_terreno
	*	turmas_recebidas			-	menu_turmas
	*	usuarios_turmas_recebidas	-	menu_usuarios_turmas
	* Contanto que os arrays estejam atualizados, bastada chamar as atualizações e tudo dará certo!
	*/
	private function atualizarMenuTerreno():Void{
		var tamanhoUsuariosTerrenoRecebidos:Number = usuarios_terreno_recebidos.length;
		menu_usuarios_terreno.limparOpcoes();
		for(var indice:Number=0; indice < tamanhoUsuariosTerrenoRecebidos; indice++){
			menu_usuarios_terreno.inserirOpcao(usuarios_terreno_recebidos[indice]);
		}
	}
	private function atualizarMenuTurmas():Void{
		var tamanhoTurmasRecebidas:Number = turmas_recebidas.length;
		menu_turmas.limparOpcoes();
		for(var indiceTurma:Number=0; indiceTurma<tamanhoTurmasRecebidas; indiceTurma++){
			menu_turmas.inserirOpcao(turmas_recebidas[indiceTurma]);
		}
	}
	private function atualizarMenuUsuariosTurmas():Void{
		var indiceArrayUsuarios:Number = menu_turmas.getIndiceOpcaoSelecionada();
		var tamanhoUsuariosTurmasRecebidasNoIndiceArrayUsuarios:Number;
		if(indiceArrayUsuarios == undefined){
			indiceArrayUsuarios = 0;
		}
		tamanhoUsuariosTurmasRecebidasNoIndiceArrayUsuarios = usuarios_turmas_recebidas[indiceArrayUsuarios].length;
		menu_usuarios_turmas.limparOpcoes();
		for(var indiceUsuario:Number=0; indiceUsuario<tamanhoUsuariosTurmasRecebidasNoIndiceArrayUsuarios ; indiceUsuario++){
			menu_usuarios_turmas.inserirOpcao(usuarios_turmas_recebidas[indiceArrayUsuarios][indiceUsuario]);
		}
	}
	
	/*
	* Executada toda vez que um botão de algum select é pressionado.
	* Recebe um evento com os seguintes atributos.
	*	- posicaoTexto A posição na lista ordenada de opções da opção selecionada.
	*	- nomeSelect O nome do menu que teve um botão pressionado.
	* Dispara um evento com os seguintes atributos:
	*	- tipoAcao O tipo de ação que se espera ao pressionar o botão.
	*	- opcao O nome da opção que foi selecionada.
	*/
	private function botaoDeSelectFoiPressionado(evento_botao_pressionado:Object):Void{
		var opcaoSelecionada:String
		var tipoDeAcao:String;
		if(evento_botao_pressionado.nomeSelect == menu_turmas._name){ atualizarMenuUsuariosTurmas(); }
		
		opcaoSelecionada = this[evento_botao_pressionado.nomeSelect].getOpcaoSelecionada();
		switch(evento_botao_pressionado.nomeSelect){
			case menu_usuarios_terreno._name: 
			case menu_usuarios_turmas._name: 
				tipoDeAcao = c_fala_comunicador.ATALHO_CHAT_PRIVADO;
				break;
			case menu_turmas._name: tipoDeAcao = c_fala_comunicador.ATALHO_CHAT_TURMA;
				break;
			default: tipoDeAcao = c_fala_comunicador.ATALHO_CHAT_PRIVADO;
				break;
		}
		dispatchEvent({target:this, type:"botaoPressionado", tipoAcao: tipoDeAcao, opcao: opcaoSelecionada});
	}
	
	/*
	* Não faz nada mesmo...
	*/
	private function fazNada():Void{}
}