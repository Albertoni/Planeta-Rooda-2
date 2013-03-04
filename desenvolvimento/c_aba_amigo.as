import mx.utils.Delegate;
import mx.events.EventDispatcher;
import flash.geom.Point;

/*
* Classe para a aba "amigo" do comunicador do menu.
* Esta aba é capaz de alternar entre dois estados:
*	- Quando o usuário está em chat Amigo, permite convidar novos usuários para o chat e exibe os usuários que já estão nele.
*	- Quando o usuário não está em chat Amigo, exibe os chats Amigo para os quais o usuário foi convidado. Também dá a opção de criar novo chat Amigo.
*/
class c_aba_amigo extends MovieClip{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "abaAmigo";
	
	/*
	* Eventos.
	*/
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;

	/*
	* Botões de gerência do chat Amigo.
	*/
	private var btAddContato:MovieClip;
	private var btSairChatAmigo:MovieClip;
	private var btEntrarChatAmigo:MovieClip;
	private var btCriarChatAmigo:MovieClip;
	
	/*
	* O campo de texto que permite convidar um contato para o chat Amigo.
	*/
	private var labelContato:TextField;
	private var campoTextoAddContato:TextField;
	private var espacoAddContato:TextField;
	
	/*
	* O campo de texto que permite criar um chat Amigo.
	*/
	private var labelCriarChat:TextField;
	private var campoTextoCriarChat:TextField;
	private var espacoCriarChat:TextField;
	
	/*
	* Menus do tipo c_select.
	*/
	private var menu_contatos:c_select = undefined; //Menu da aba Amigo com os usuários que são contatos do usuário logado. São os usuários que estão no mesmo chat Amigo.
	private var menu_chats:c_select = undefined; //Menu de chats para os quais o usuário logado foi convidado.
	
	/*
	* Variáveis de conexão com o servidor.
	*/
	private var enviaContatos:LoadVars;
	private var recebeContatos:LoadVars;
	private var enviaChats:LoadVars;
	private var recebeChats:LoadVars;

	/*
	* Nomes de contatos do usuário logado. São os usuários que estão no mesmo chat Amigo.
	*/
	private var contatos_recebidos:Array = new Array();
	
	/*
	* Nomes de chats Amigo para os quais o usuário foi convidado.
	*/
	private var chats_recebidos:Array = new Array();
	
	/*
	* Elementos gráficos.
	*/
	private var espacoEsquerda:MovieClip;
	private var espacoDireita:MovieClip;
	
	/*
	* Interfaces que possui esta aba.
	*/
	private static var INTERFACE_CONTATOS:Number=1;
	private static var INTERFACE_CHATS:Number=2;
	private var interface_atual:Number = INTERFACE_CONTATOS;
	
	/*
	* Id do chat amigo da última atualização.
	*/
	private var id_chat_amigo:Number;
	
	/*
	* Intervalo de atualização dos usuários que estão no mesmo chat.
	*/
	private static var TEMPO_ATUALIZACAO_CONTATOS_MILISEGUNDOS:Number = 1500;
	private var id_intervalo_atualizacao_contatos:Number;
	
	/*
	* Intervalo de atualização dos chats para os quais foi convidado.
	*/
	private static var TEMPO_ATUALIZACAO_CHATS_MILISEGUNDOS:Number = 1500;
	private var id_intervalo_atualizacao_chats:Number;
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		id_intervalo_atualizacao_contatos = undefined;
		
		attachMovie(c_select.LINK_BIBLIOTECA, "menu_contatos_dummy", getNextHighestDepth(), {_x:this['espacoDireita']._x, _y:this['espacoDireita']._y});
		menu_contatos = this['menu_contatos_dummy'];
		menu_contatos.inicializar(6, new Array(), "Usuários no chat");
		menu_contatos.setTipoInvisivel(true);
		menu_contatos.redimensionar(this['espacoDireita']._width, this['espacoDireita']._height);
		menu_contatos.addEventListener("botaoPressionado", Delegate.create(botaoSelectFoiPressionado));
		
		attachMovie(c_select.LINK_BIBLIOTECA, "menu_chats_dummy", getNextHighestDepth(), {_x:this['espacoDireita']._x, _y:this['espacoDireita']._y});
		menu_chats = this['menu_chats_dummy'];
		menu_chats.inicializar(6, new Array(), "Convites para chats");
		menu_chats.setTipoInvisivel(true);
		menu_chats.redimensionar(this['espacoDireita']._width, this['espacoDireita']._height);
		menu_chats.addEventListener("botaoPressionado", Delegate.create(botaoSelectFoiPressionado));
		
		attachMovie("btAddContato", "btAddContato", getNextHighestDepth(), {_x:250.8, _y:63.65});
		btAddContato = this['btAddContato'];
		btAddContato.inicializar();
		btAddContato.addEventListener("btPressionado", Delegate.create(this, botaoFoiPressionado));
		
		attachMovie("btSairChatAmigo", "btSairChatAmigo", getNextHighestDepth(), {_x:this['espacoEsquerda']._x, _y:this['espacoEsquerda']._y + this['espacoEsquerda']._height});
		btSairChatAmigo = this['btSairChatAmigo'];
		btSairChatAmigo._y -= btSairChatAmigo._height;
		btSairChatAmigo.inicializar();
		btSairChatAmigo.addEventListener("btPressionado", Delegate.create(this, botaoFoiPressionado));
		
		attachMovie("btEntrarChatAmigo", "btEntrarChatAmigo", getNextHighestDepth(), {_x:this['espacoDireita']._x, _y:this['espacoDireita']._y + this['espacoDireita']._height});
		btEntrarChatAmigo = this['btEntrarChatAmigo'];
		btEntrarChatAmigo._y -= btEntrarChatAmigo._height;
		btEntrarChatAmigo.inicializar();
		btEntrarChatAmigo.addEventListener("btPressionado", Delegate.create(this, botaoFoiPressionado));
		
		attachMovie("btCriarChatAmigo", "btCriarChatAmigo", getNextHighestDepth(), {_x:this['espacoEsquerda']._x, _y:this['espacoEsquerda']._y + this['espacoEsquerda']._height});
		btCriarChatAmigo = this['btCriarChatAmigo'];
		btCriarChatAmigo._y -= btCriarChatAmigo._height;
		btCriarChatAmigo.inicializar();
		btCriarChatAmigo.addEventListener("btPressionado", Delegate.create(this, botaoFoiPressionado));
		
		interface_atual = INTERFACE_CONTATOS;
		alternarInterfaceChatAmigo();
	}
	
	/*
	* @param id_chat_amigo Id usada para atualizar os selects.
	*/
	public function definirIdChatAmigo(id_chat_amigo_param:Number):Void{
		id_chat_amigo = id_chat_amigo_param;
	}
	
	/*
	* Função executada toda vez que algum botão foi pressionado.
	* @param evento_botao_param Um evento com a seguinte propriedade:
	*	- nome O nome do botão que gerou o evento, que foi pressionado.
	*/
	private function botaoFoiPressionado(evento_botao_param:Object):Void{
		var opcaoSelecionada:String
		var tipoDeAcao:String;
		var botaoRelevante:Boolean = false;
		switch(evento_botao_param.nome){
			case btCriarChatAmigo._name:
				tipoDeAcao = c_fala_comunicador.ATALHO_CRIAR_CHAT_AMIGO;
				opcaoSelecionada = campoTextoCriarChat.text;
				botaoRelevante = true;
				break;
			case btEntrarChatAmigo._name:
				if(menu_chats.haBotaoPressionado()){
					tipoDeAcao = c_fala_comunicador.ATALHO_CHAT_AMIGO;
					opcaoSelecionada = menu_chats.getOpcaoSelecionada();
				}
				botaoRelevante = true;
				break;
			case btSairChatAmigo._name: 
				tipoDeAcao = c_fala_comunicador.ATALHO_SAIR_CHAT_AMIGO;
				opcaoSelecionada = new String();
				botaoRelevante = true;
				break;
			case btAddContato._name: 
				tipoDeAcao = c_fala_comunicador.ATALHO_CHAT_AMIGO_CONVIDAR;
				opcaoSelecionada = campoTextoAddContato.text;
				botaoRelevante = true;
				break;
		}
		if(botaoRelevante){
			_parent.dispatchEvent({target:_parent, type:"botaoPressionado", tipoAcao: tipoDeAcao, opcao: opcaoSelecionada});
		}
	}
	
	/*
	* Função executada toda vez que algum botão de um select foi pressionado.
	* @param evento_select_param Um evento com a seguinte propriedade:
	*	- nomeSelect O nome do select que gerou o evento, que teve um botão pressionado.
	*/
	private function botaoSelectFoiPressionado(evento_select_param:Object):Void{
		var opcaoSelecionada:String
		var tipoDeAcao:String;
		var botaoRelevante:Boolean = false;
		switch(evento_select_param.nomeSelect){
			case menu_contatos._name: 
				tipoDeAcao = c_fala_comunicador.ATALHO_CHAT_PRIVADO;
				opcaoSelecionada = menu_contatos.getOpcaoSelecionada();
				botaoRelevante = true;
				break;
		}
		if(botaoRelevante){
			_parent.dispatchEvent({target:_parent, type:"botaoPressionado", tipoAcao: tipoDeAcao, opcao: opcaoSelecionada});
		}
	}

	/*
	* Mostra a interface de gerência do chat.
	*/
	public function abrirInterfaceChats():Void{
		interface_atual = INTERFACE_CONTATOS;
		alternarInterfaceChatAmigo();
	}
	
	/*
	* Mostra a interface de gerência dos contatos.
	*/
	public function abrirInterfaceContatos():Void{
		interface_atual = INTERFACE_CHATS;
		alternarInterfaceChatAmigo();
	}

	/*
	* Alterna a interface Amigo, que possui dois estados:
	*	- Quando o usuário está em um chat Amigo, possibilita ver os integrantes e convidar novos.
	*	- Quando o usuário não está em um chat Amigo, possibilita entrar em um.
	* @param chat_id_param A id do chat do qual vão ser recebidos os usuários.
	*/
	public function alternarInterfaceChatAmigo():Void{
		var deveFicarVisivel:Boolean;
		
		if(interface_atual == INTERFACE_CONTATOS){
			interface_atual = INTERFACE_CHATS;
			if(id_intervalo_atualizacao_contatos != undefined){
				clearInterval(id_intervalo_atualizacao_contatos);
			}
			id_intervalo_atualizacao_chats = setInterval(this, "atualizarChatsConvidados", TEMPO_ATUALIZACAO_CHATS_MILISEGUNDOS);
		} else {
			interface_atual = INTERFACE_CONTATOS;
			if(id_intervalo_atualizacao_chats != undefined){
				clearInterval(id_intervalo_atualizacao_chats);
			}
			id_intervalo_atualizacao_contatos = setInterval(this, "atualizarUsuariosChatAmigo", TEMPO_ATUALIZACAO_CONTATOS_MILISEGUNDOS);
		}
		
		deveFicarVisivel = (interface_atual == INTERFACE_CHATS); //Truquezinho para economia de código!
		
		this['espacoEsquerda']._visible = false;
		this['espacoDireita']._visible = true;
		
		menu_chats._visible = deveFicarVisivel;
		btEntrarChatAmigo._visible = deveFicarVisivel;
		btCriarChatAmigo._visible = deveFicarVisivel;
		labelCriarChat._visible = deveFicarVisivel;
		campoTextoCriarChat._visible = deveFicarVisivel;
		espacoCriarChat._visible = deveFicarVisivel;
			
		menu_contatos._visible = !deveFicarVisivel;
		btAddContato._visible = !deveFicarVisivel;
		btSairChatAmigo._visible = !deveFicarVisivel;
		labelContato._visible = !deveFicarVisivel;
		campoTextoAddContato._visible = !deveFicarVisivel;
		espacoAddContato._visible = !deveFicarVisivel;
	}
	
	/*
	* Se estiver na aba do chat Amigo, pega o que estiver escrito no campo de texto e o procura no banco de dados para tentar junta-lo ao chat Amigo.
	*/
	private function adicionarChatAmigo():Void{
		var nomeAmigoConvidado:String = campoTextoAddContato.text;
		_parent.dispatchEvent({target:this, type:"botaoPressionado", tipoAcao: c_fala_comunicador.ATALHO_CHAT_AMIGO_CONVIDAR, opcao: nomeAmigoConvidado});
	}

	/*
	* Realiza as operações necessárias para abrir esta aba.
	*/
	public function abrir(){
		_visible = true;
	}
	
	/*
	* Realiza as operações necessárias para fechar esta aba.
	*/
	public function fechar(){
		_visible = false;
	}
	
	/*
	* Pede ao banco de dados os chats para os quais este usuário foi convidado.
	*/
	public function atualizarChatsConvidados():Void{
		enviaChats = new LoadVars();
		recebeChats = new LoadVars();
		recebeChats.onLoad = Delegate.create(this, chatsRecebidos);
		enviaChats.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_PROCURAR_CHATS_CONVIDADO, recebeChats, "POST");
	}
	/*
	* Executada ao receber os usuários do chat amigo com uma chamada de atualizarUsuariosChatAmigo.
	*/
	private function chatsRecebidos(success):Void{
		if(success and recebeChats.erro == c_banco_de_dados.SEM_ERRO){
			chats_recebidos = new Array();
			for(var indice:Number=0; indice < recebeChats.numeroChatsRecebidos; indice++){
				chats_recebidos.push(recebeChats['nomeChat'+indice]);
			}
			atualizarMenuChats();
		}
	}

	/*
	* Pede ao banco de dados os usuários no chat amigo do usuário logado.
	* @param chat_id_param A id do chat do qual vão ser recebidos os usuários.
	*/
	public function atualizarUsuariosChatAmigo():Void{
		enviaContatos = new LoadVars();
		recebeContatos = new LoadVars();
		enviaContatos.chat_id = id_chat_amigo;
		recebeContatos.onLoad = Delegate.create(this, contatosRecebidos);
		enviaContatos.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_PROCURAR_CONTATOS_USUARIO, recebeContatos, "POST");
	}
	/*
	* Executada ao receber os usuários do chat amigo com uma chamada de atualizarUsuariosChatAmigo.
	*/
	private function contatosRecebidos(success):Void{
		if(success and recebeContatos.erro == c_banco_de_dados.SEM_ERRO){
			contatos_recebidos = new Array();
			for(var indice:Number=0; indice < recebeContatos.numeroContatosRecebidos; indice++){
				contatos_recebidos.push(recebeContatos['nomeContatos'+indice]);
			}
			atualizarMenuContatos();
		}
	}

	/*
	* As atualizações dos menus são feitas com base nos conteúdos dos arrays a seguir:
	*	chats_recebidos				-	menu_chats
	*	contatos_recebidos			-	menu_contatos
	* Contanto que os arrays estejam atualizados, bastada chamar as atualizações e tudo dará certo!
	*/
	private function atualizarMenuContatos():Void{
		menu_contatos.limparOpcoes();
		for(var indice:Number=0; indice < contatos_recebidos.length; indice++){
			menu_contatos.inserirOpcao(contatos_recebidos[indice]);
		}
	}
	private function atualizarMenuChats():Void{
		menu_chats.limparOpcoes();
		for(var indice:Number=0; indice < chats_recebidos.length; indice++){
			menu_chats.inserirOpcao(chats_recebidos[indice]);
		}
	}








}
