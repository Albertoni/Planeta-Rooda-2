import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_menu extends MovieClip {
//dados:
	//movieclip
	private var fecharMenu:Boolean = false;
	
	private var FRAME_ESTADO_FECHADO:Number = 1;
	private var FRAME_ESTADO_BOTOES:Number = 15;	//Frame que mostra os botoes.
	private var FRAME_ESTADO_REINICIAR:Number = 29; //Quando o menu deve voltar para o estado fechado.
	private var FRAME_ESTADO_INTERFACE:Number = 44; //Frame que abre o espaco para uma interface.
	private var FRAME_ESTADO_FINAL:Number = 58;		//Último frame, menu aberto mostrando botoes.
	
	private var TEMPO_ESPERA_MOSTRAR_BOTOES:Number = 600;
	private var TEMPO_ESPERA_ESCONDER_BOTOES:Number = 600;
	private var TEMPO_ESPERA_ABRIR_FUNCIONALIDADE:Number = 600;
	
	//funcionalidades ~~ estao centradas nas existentes... ainda nao e necessário torná-las personalizáveis.
					//deste modo, a criacao de uma nova funcionalidade implica criacao de novas constantes e modificacoes de codigo.
	public var NENHUMA:Number = 0;                //binário ...00000000 
	public var EDITAR_TURMA:Number = 1 << 0;      //binário ...00000001
	public var EDITAR_CONTA:Number = 1 << 1;      //binário ...00000010
	public var EDITAR_PLANETA:Number = 1 << 2;    //binário ...00000100
	public var EDITAR_USUARIO:Number = 1 << 3;    //binário ...00001000
	public var CRIAR_TURMA:Number = 1 << 4;       //binário ...00010000
	public var TROCAR_DE_PLANETA:Number = 1 << 5; //binário ...00100000
	public var CRIAR_ESCOLA:Number = 1 << 6;      //binário ...01000000
	public var TODAS:Number = 255;				  //binário ...01111111
		
	//---- Botoes
	private var caixa_de_botoes:c_caixa_botoes;
	private var btEditarTurma:c_bt_simples;
	private var btEditarConta:c_bt_simples;
	private var btEditarPlaneta:c_bt_simples;
	private var btEditarUsuario:c_bt_simples;
	private var btCriarTurma:c_bt_simples;
	private var btTrocarPlaneta:c_bt_simples;
	private var btEditar:c_bt_simples;
	private var btAcessoRapido:c_bt_simples;
	private var btCriarEscola:c_bt_simples;
	private var btMenu:c_btMenu;
	private var btFechar:c_btFecharMenu;
	
	//---- Interfaces
	private var interfacePesquisarEditarTurma:c_interface_pesquisa_edicao_turma;
	private var interfacePesquisarEditarConta:c_interface_pesquisa_edicao_conta;
	private var interfacePesquisarEditarPlaneta:c_interface_pesquisa_edicao_planeta;
	private var interfaceEditarUsuario:c_interface_edicao_usuario;
	private var interfaceCadastrarTurma:c_interface_criar_turma;
	private var interfaceTrocarPlaneta:c_interface_trocar_planeta;
	private var interfaceAcessoRapido:c_interface_acesso_rapido;
	private var interfaceCriacaoEscola:c_interface_criacao_escola;
	private var interfaceEdicoes:c_interface_edicoes;
	private var interfaceAtiva:ac_interface_menu;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

//metodos:	
	public function inicializar(){	
		mx.events.EventDispatcher.initialize(this);
		
		caixa_de_botoes = this['caixa_de_botoes'];
		caixa_de_botoes.inicializar();

		btMenu = this['btMenu'];
		btMenu.inicializar();
		btMenu.addEventListener("btMenuPress", Delegate.create(this, toggleAbrirFechar));	
		btMenu.addEventListener("btMenuOver", Delegate.create(this, escurecerBtMenu));	
		btMenu.addEventListener("btMenuOut", Delegate.create(this, clarearBtMenu));	
		
		btFechar = this['btFecharMenu'];
		btFechar.inicializar();
		btFechar.addEventListener("btFecharMenuPress", Delegate.create(this, botaoFecharPressionado));	
		
		onEnterFrame = atualizacoesEnterFrame;
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
	
	//---- Menu
	private function abrirInterfaceEdicoes(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfaceEdicoes){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfaceEdicoes;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfacePesquisarEditarTurma(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfacePesquisarEditarTurma){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfacePesquisarEditarTurma;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfacePesquisarEditarConta(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfacePesquisarEditarConta){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfacePesquisarEditarConta;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfacePesquisarEditarPlaneta(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfacePesquisarEditarPlaneta){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfacePesquisarEditarPlaneta;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfaceEditarUsuario(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfaceEditarUsuario){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfaceEditarUsuario;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfaceCadastrarTurma(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfaceCadastrarTurma){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfaceCadastrarTurma;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfaceTrocarPlaneta(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva == interfaceTrocarPlaneta){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfaceTrocarPlaneta;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfaceAcessoRapido(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva != undefined and interfaceAtiva == interfaceAcessoRapido){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfaceAcessoRapido;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	private function abrirInterfaceCriacaoEscola(){
		ficarAcimaDosOutrosMenus();
		if(interfaceAtiva != undefined and interfaceAtiva == interfaceCriacaoEscola){
			interfaceAtiva.esconder();
			fecharMenuInterfaceComAnimacao();
			interfaceAtiva = null;
		} else {
			if(!menuEstadoInterface()){ abrirMenuEstadoInterfaceComAnimacao(); }
			if(interfaceAtiva != null){ interfaceAtiva.esconder(); }
			interfaceAtiva = interfaceCriacaoEscola;
			iniciar_espera_abrir_funcionalidade();
		}
	}
	public function configurarFuncionalidades(configuracao_funcionalidades_param:Number):Void{
		var posx_interface:Number; //a posicao em que deve ser jogado o movieclip da interface aberta.
		var posy_interface:Number;
		
		//procedimento para descobrir posicao das interfaces.
		_visible = false;
		gotoAndStop(FRAME_ESTADO_INTERFACE);
		posx_interface = this['menuCampos']._x;
		posy_interface = this['menuCampos']._y;
		gotoAndStop(FRAME_ESTADO_FECHADO);
		_visible = true;
		
		/*attachMovie("btMenuEditar", "btEditar", getNextHighestDepth());
		caixa_de_botoes.adicionar_botao( btEditar );
		btEditar.inicializar();
		btEditar.addEventListener("btPressionado", Delegate.create(this, abrirInterfaceEdicoes));	*/
		
		attachMovie(c_interface_edicoes.LINK_BIBLIOTECA, "interfaceEdicoes", getNextHighestDepth());
		interfaceEdicoes.inicializar();
		interfaceEdicoes.esconder();
		interfaceEdicoes._x = posx_interface;
		interfaceEdicoes._y = posy_interface;

		//inicializacoes de interfaces e botoes.
		if(configuracaoDesejada(CRIAR_ESCOLA, configuracao_funcionalidades_param)){
			attachMovie(c_interface_criacao_escola.LINK_BIBLIOTECA, "interfaceCriacaoEscola", getNextHighestDepth());
			interfaceCriacaoEscola.inicializar();
			interfaceCriacaoEscola.esconder();
			interfaceCriacaoEscola._x = posx_interface;
			interfaceCriacaoEscola._y = posy_interface;
			
			attachMovie("btMenuCriarEscola", "btCriarEscola", getNextHighestDepth());
			caixa_de_botoes.adicionar_botao( btCriarEscola );
			btCriarEscola.inicializar();
			btCriarEscola.addEventListener("btPressionado", Delegate.create(this, abrirInterfaceCriacaoEscola));	
		}
		
		if(configuracaoDesejada(EDITAR_USUARIO, configuracao_funcionalidades_param)){
			attachMovie("camposEditarUsuario", "interfaceEditarUsuario", getNextHighestDepth());
			interfaceEditarUsuario.inicializar();
			interfaceEditarUsuario.esconder();
			interfaceEditarUsuario._x = posx_interface;
			interfaceEditarUsuario._y = posy_interface;
			
			interfaceEdicoes.adicionarInterface(interfaceEditarUsuario, "CONTA");
		} 
		
		if(configuracaoDesejada(EDITAR_TURMA, configuracao_funcionalidades_param)){
			attachMovie("interface_pesquisa_edicao_turma", "interfacePesquisarEditarTurma", getNextHighestDepth());
			interfacePesquisarEditarTurma.inicializar();
			interfacePesquisarEditarTurma.esconder();
			interfacePesquisarEditarTurma._x = posx_interface;
			interfacePesquisarEditarTurma._y = posy_interface;
			
			interfaceEdicoes.adicionarInterface(interfacePesquisarEditarTurma, "TURMA");
		}
		
		if(configuracaoDesejada(EDITAR_CONTA, configuracao_funcionalidades_param)){
			attachMovie(c_interface_pesquisa_edicao_conta.LINK_BIBLIOTECA, "interfacePesquisarEditarConta", getNextHighestDepth());
			interfacePesquisarEditarConta.inicializar();
			interfacePesquisarEditarConta.esconder();
			interfacePesquisarEditarConta._x = posx_interface;
			interfacePesquisarEditarConta._y = posy_interface;
			
			interfaceEditarUsuario.permitirEdicaoTodosUsuarios();
		}

		if(configuracaoDesejada(EDITAR_PLANETA, configuracao_funcionalidades_param)){
			attachMovie("interface_pesquisa_edicao_planeta", "interfacePesquisarEditarPlaneta", getNextHighestDepth());
			interfacePesquisarEditarPlaneta.inicializar();
			interfacePesquisarEditarPlaneta.esconder();
			interfacePesquisarEditarPlaneta._x = posx_interface;
			interfacePesquisarEditarPlaneta._y = posy_interface;
			
			interfaceEdicoes.adicionarInterface(interfacePesquisarEditarPlaneta, "PLANETA");
		} 
		
		if(configuracaoDesejada(CRIAR_TURMA, configuracao_funcionalidades_param)){
			attachMovie("camposCriarTurma", "interfaceCadastrarTurma", getNextHighestDepth());
			interfaceCadastrarTurma.inicializar();
			interfaceCadastrarTurma.esconder();
			interfaceCadastrarTurma._x = posx_interface;
			interfaceCadastrarTurma._y = posy_interface;
			
			attachMovie("btMenuCriarTurma", "btCriarTurma", getNextHighestDepth());
			caixa_de_botoes.adicionar_botao( btCriarTurma );
			btCriarTurma.inicializar();
			btCriarTurma.addEventListener("btPressionado", Delegate.create(this, abrirInterfaceCadastrarTurma));	
		} 
		
		if(configuracaoDesejada(TROCAR_DE_PLANETA, configuracao_funcionalidades_param)){
			attachMovie("camposTrocaSist", "interfaceTrocarPlaneta", getNextHighestDepth());
			interfaceTrocarPlaneta.inicializar();
			interfaceTrocarPlaneta.esconder();
			interfaceTrocarPlaneta._x = posx_interface;
			interfaceTrocarPlaneta._y = posy_interface;
			
			attachMovie("btMenuTrocarPlaneta", "btTrocarPlaneta", getNextHighestDepth());
			caixa_de_botoes.adicionar_botao( btTrocarPlaneta );
			btTrocarPlaneta.inicializar();
			btTrocarPlaneta.addEventListener("btPressionado", Delegate.create(this, abrirInterfaceTrocarPlaneta));	
		} 
		
		attachMovie("interface_acesso_rapido", "interfaceAcessoRapido", getNextHighestDepth());
		interfaceAcessoRapido.inicializar();
		interfaceAcessoRapido.esconder();
		interfaceAcessoRapido._x = posx_interface;
		interfaceAcessoRapido._y = posy_interface;
			
		attachMovie("btMenuAcessoRapido", "btAcessoRapido", getNextHighestDepth());
		caixa_de_botoes.adicionar_botao( btAcessoRapido );
		btAcessoRapido.inicializar();
		btAcessoRapido.addEventListener("btPressionado", Delegate.create(this, abrirInterfaceAcessoRapido));	
		
		caixa_de_botoes.reposicionarBotoes(); //Organiza automaticamente os botoes na caixa.
		caixa_de_botoes.esconderTodosBotoes();
	}
	
	private function iniciar_espera_mostrar_botoes():Void{
		//Mágica encontrada no google. URL: "http://www.actionscript.org/forums/showthread.php3?t=171425".
		_global['setTimeout'](this, 'mostrar_botoes', TEMPO_ESPERA_MOSTRAR_BOTOES); 
	}
	private function iniciar_espera_esconder_botoes():Void{
		//Mágica encontrada no google. URL: "http://www.actionscript.org/forums/showthread.php3?t=171425".
		_global['setTimeout'](this, 'esconder_botoes', TEMPO_ESPERA_ESCONDER_BOTOES);
	}
	private function iniciar_espera_abrir_funcionalidade():Void{
		//Mágica encontrada no google. URL: "http://www.actionscript.org/forums/showthread.php3?t=171425".
		_global['setTimeout'](this, 'abrir_funcionalidade', TEMPO_ESPERA_ABRIR_FUNCIONALIDADE); 
	}
	
	private function mostrar_botoes(){
		caixa_de_botoes.mostrarTodosBotoes();
	}
	private function esconder_botoes(){
		caixa_de_botoes.esconderTodosBotoes();
	}
	private function abrir_funcionalidade():Void{
		interfaceAtiva.mostrar();
	}
	
	/*
	* Faz com que este menu fique acima de outros.
	*/
	private function ficarAcimaDosOutrosMenus(){
		if(this.getDepth() < _root.menuEdicaoMC.getDepth()){
			this.swapDepths(_root.menuEdicaoMC);
		}
	}
	
	//---- Interface
	private function toggleAbrirFechar():Void{
		switch(true){
			case menuEstadoFechado():	abrirMenuEstadoBotoesComAnimacao();
										iniciar_espera_mostrar_botoes();
										ficarAcimaDosOutrosMenus();
			break;
			
			case menuEstadoBotoes(): fecharMenuBotoesComAnimacao();
									 caixa_de_botoes.esconderTodosBotoes();
			break;
					
			case menuEstadoReiniciar(): abrirMenuEstadoBotoesComAnimacao();
			break;
					
			case menuEstadoInterface(): interfaceAtiva.esconder();
										fecharMenuInterfaceComAnimacao();
			break;
					
			case menuEstadoFinal(): fecharMenuBotoesComAnimacao();
								    iniciar_espera_esconder_botoes();
			break;
		}
		clarearBtMenu();
		interfaceAtiva = null;
	}
	private function botaoFecharPressionado():Void{
		switch(true){
			case menuEstadoFechado():	//impossível
			break;
					
			case menuEstadoBotoes(): fecharMenuBotoesComAnimacao();
									 caixa_de_botoes.esconderTodosBotoes();
			break;
					
			case menuEstadoReiniciar(): //impossível
			break;
					
			case menuEstadoInterface(): fecharMenuInterfaceComAnimacao();
										fecharMenu = true;
										iniciar_espera_esconder_botoes();
			break;
				
			case menuEstadoFinal(): caixa_de_botoes.esconderTodosBotoes();
			break;
		}
		interfaceAtiva.esconder();
		interfaceAtiva = null;
	}
	
	//---- Movieclip
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
	private function menuEstadoInterface():Boolean{
		if(_currentframe == FRAME_ESTADO_INTERFACE){
			return true;
		}
		else{
			return false;
		}
	}
	private function menuEstadoFinal():Boolean{
		if(_currentframe == FRAME_ESTADO_FINAL){
			return true;
		}
		else{
			return false;
		}
	}
	
	private function abrirMenuEstadoBotoesComAnimacao():Void{
		_root.usuario_status.personagem.parar();
		gotoAndPlay(2);
	}
	private function abrirMenuEstadoInterfaceComAnimacao():Void{
		_root.usuario_status.personagem.parar();
		gotoAndPlay(30);
	}
	private function fecharMenuBotoesComAnimacao():Void{
		_root.usuario_status.personagem.parar();
		gotoAndPlay(16);
		caixa_de_botoes.mostrarTodosBotoes();
	}
	private function fecharMenuInterfaceComAnimacao():Void{
		_root.usuario_status.personagem.parar();
		gotoAndPlay(45);
		caixa_de_botoes.esconderTodosBotoes();
	}
	
	private function abrirMenuEstadoBotoesInstantaneamente():Void{
		_root.usuario_status.personagem.parar();
		gotoAndStop(FRAME_ESTADO_BOTOES);
		caixa_de_botoes.mostrarTodosBotoes();
	}
	private function fecharMenuInstantaneamente():Void{
		gotoAndStop(FRAME_ESTADO_FECHADO);
		caixa_de_botoes.esconderTodosBotoes();
	}

	//---- Atualizacoes
	public function atualizacoesEnterFrame():Void{
		switch(true){
			case menuEstadoFechado(): fecharMenu = false;
			break;
			
			case menuEstadoBotoes(): //nada...
			break;
			
			case menuEstadoReiniciar(): fecharMenuInstantaneamente();
			break;
			
			case menuEstadoInterface(): _root.usuario_status.personagem.parar();
			break;
			
			case menuEstadoFinal(): if(fecharMenu == true){ fecharMenuBotoesComAnimacao();
														    caixa_de_botoes.esconderTodosBotoes(); }
								    else{                   abrirMenuEstadoBotoesInstantaneamente(); }	
			break;
		}
	}
	
	//---- Auxiliares
	private function configuracaoDesejada(bin_padrao_param:Number, bin_cfg_param:Number):Boolean{
		if((bin_cfg_param & bin_padrao_param) != 0){
			return true;
		}
		else{
			return false;
		}
	} //compara a configuracao passada com o padrao e retorna true se o padrao está na configuracao.
}