import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.net.FileReference;
import flash.external.*;

/**
 * Comunicador.
 * 
 */
dynamic class c_comunicador extends MovieClip {	
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLITECA:String = "comunicador";
	
	/*
	* Barra de rolagem do chat ativo.
	*/
    private var barra_rolagem:c_barra_rolagem;
	
	/*
	* Campo de texto em que o usuário digita o que quer falar.
	* As falas podem ser enviadas tanto via [Enter] como por um botão.
	*/
	public var fala:MovieClip;
	
	/*
	* Botão que envia falas escritas pelo usuário no campo de texto "fala".
	*/
	private var botaoEnviar:MovieClip;
	
	/*
	* Botão que salva as conversas de chats.
	*/
	private var botaoSalvar:MovieClip;
	
	/*
	* Elementos de aparência.
	*/
	private var fundoFala:MovieClip;
	private var fundo:MovieClip;
	private var borda:MovieClip;
	
	/*
	* Caixa de texto principal, que contém falas de todos os chats ativos.
	* Sempre contém todas as mensagens recebidas.
	*/
	private var texto_principal:TextField;
	
	/*
	* menu aonde seleciona-se chat terreno, contato ou grupo
	*/
	public var header_:MovieClip;
	
	/*
	* Barra que permite abrir/fechar o menu.
	*/
	public var redimensionador:MovieClip;
	
	/*
	* Chats do comunicador.
	*/
	private var chat_terreno:c_chat; //Chat do terreno em que está.
	private var chat_turma:c_chat; //Chat do planeta em que está.
	private var chat_amigo:c_chat; //Chat criado ou em que o usuário optou por estar.
	//Não pode existir. Recebe todas as mensagens privadas enviadas a outro personagem.
	private var chat_privado_envia:c_chat; //Chat do usuário para enviar mensagens privadas. 
	private var chat_privado_recebe:c_chat; //Chat do usuário para receber mensagens privadas. Nunca deve ser chat_destino, pois serve só para receber.

	/*
	* Tipos de chats.
	*/
	private static var TIPO_AMIGO:Number = 1;
	private static var TIPO_TURMA:Number = 2;

	/*
	* O chat que é único no momento, o único que pode ser visto.
	* Se undefined, todos chats podem ser vistos.
	*/
	private var chat_unico:c_chat = undefined;

	/*
	* O chat que recebe falas no momento, não confundir com o chat único.
	* Deve sempre estar definido.
	*/
	private var chat_destino:c_chat;

	/*
	* Número visível de linhas do texto principal deste comunicador.
	*/
	public static var NUMERO_VISIVEL_LINHAS:Number = 5;

	/*
	* Variáveis para comunicação com o servidor.
	*/
	private var enviaConvidarAmigo:LoadVars;
	private var recebeConvidarAmigo:LoadVars;
	private var enviaCriarChat:LoadVars;
	private var recebeCriarChat:LoadVars;
	private var enviaChat:LoadVars;
	private var recebeChat:LoadVars;
	private var enviaChatUsuario:LoadVars;
	private var recebeChatUsuario:LoadVars;
	private var enviaMensagem:LoadVars;
	private var recebeMensagem:LoadVars;
	private var enviaNotificacao:LoadVars;
	private var recebeNotificacao:LoadVars;
	private var enviaLogChat:LoadVars;
	private var recebeLogChat:LoadVars;
	
	/*
	* Indica se o usuário possui permissão para enviar/receber mensagens privadas.
	*/
	private var ha_chatPrivado:Boolean;
	
	/*
	* Eventos.
	*/
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;
	
//métodos
	function c_comunicador(Void){
		EventDispatcher.initialize(this);
		
		texto_principal.html = true;
		
		attachMovie("barraRolagem", "barra_rolagem", this.getNextHighestDepth());		
		barra_rolagem._y = texto_principal._y;
		barra_rolagem._x = 528.3;
		barra_rolagem.addEventListener("scrollMoveu", Delegate.create(this, barra_scroll_moveu));
		
		botaoEnviar.onRelease = Delegate.create(this, enviarMensagemDigitada);
		botaoEnviar.inicializar();
		
		botaoSalvar.onRelease = Delegate.create(this, salvarChatAtual);
		botaoSalvar.inicializar();
		
		chat_terreno = undefined;
		chat_turma = undefined;
		chat_amigo = undefined;
		
		ha_chatPrivado = true;
	}
	
	/*
	* Abre uma janela de escolha de diretórios e salva o conteúdo do chat que estiver ativo em um novo arquivo .txt.
	* Dado que o nem flash, nem javascript é capaz de criar arquivos externos, é necessário faze-lo via php.
	* salvarChatAtual delegará a criação do arquivo .txt para um script, o qual criará o arquivo com um nome padrão: logChat.txt.
	* Após criação do arquivo via php, o actionscript em fazerDownloadLogChat perguntará onde o usuário deseja salvar este arquivo.
	*/
	private function salvarChatAtual():Void{
		var logChat:String = new String();
		if(chat_unico != undefined){
			c_aviso_com_ok.mostrar("Salvando conteúdo do chat '"+chat_unico.getNome()+"'.");
			logChat = chat_unico.getLog();
		} else {
			c_aviso_com_ok.mostrar("Salvando conteúdo de todos os chats.");
			logChat = texto_principal.text;
		}
		
		getURL(c_banco_de_dados.ARQUIVO_PHP_CRIACAO_LOG+"?nomeArquivo=logChat.txt&conteudoArquivo="+logChat, "_self");
	}
	
	/*
	* As funções à seguir habilitam/desabilitam chats do comunicador permanentemente.
	* Ao desabilitar um chat, os botões que correspondem a ele somem.
	* @param habilitar_param Booleano que indica se o chat deve ser habilitado.
	*/
	public function setPermissaoChatTerreno(habilitar_param:Boolean):Void{
		if(!habilitar_param){
			chat_terreno.setAtivo(false);
			header_.comunicador_menu.btTerreno._visible = false;
			setChatDestino(procurarChatExistente());
		} else {
			chat_terreno.setAtivo(true);
			header_.comunicador_menu.btTerreno._visible = true;
		}
	}
	public function setPermissaoChatTurma(habilitar_param:Boolean):Void{
		if(!habilitar_param){
			chat_turma.setAtivo(false);
			header_.comunicador_menu.btTurma._visible = false;
			setChatDestino(procurarChatExistente());
		} else {
			chat_turma.setAtivo(true);
			header_.comunicador_menu.btTurma._visible = true;
		}
	}
	public function setPermissaoChatAmigo(habilitar_param:Boolean):Void{
		if(!habilitar_param){
			chat_amigo.setAtivo(false);
			header_.comunicador_menu.btAmigo._visible = false;
		} else {
			if(chat_amigo != undefined){
				chat_amigo.setAtivo(true);
				header_.comunicador_menu.btAmigo._visible = true;
			}
		}
	}
	public function setPermissaoChatPrivado(habilitar_param:Boolean):Void{
		if(!habilitar_param){
			chat_privado_envia.setAtivo(false);
			chat_privado_recebe.setAtivo(false);
			ha_chatPrivado = false;
		} else {
			chat_privado_envia.setAtivo(true);
			chat_privado_recebe.setAtivo(true);
			ha_chatPrivado = true;
		}
	}
	
	/*
	* Procura um chat que possa ser colocado como chat destino.
	*/
	private function procurarChatExistente(){
		if(chat_terreno.ativo()){
			return chat_terreno;
		} else {
			return undefined;
		}
	}
	
	/*
	* O chat da turma do planeta só existirá quando o planeta for do tipo turma.
	* Esta função desabilita este chat, delegando-o à primeira turma a qual pertence o personagem.
	*/
	public function desabilitarChatTurmaDoPlaneta():Void{
		
	}
	
	/*
	* Reinicializa o chat de terreno.
	* @param id_param Identificação deste chat no banco de dados, caso haja.
	* @see c_chat.inicializar
	*/
	public function inicializarChatTerrreno(id_param:String){
		var chat_terrreno_visivel:Boolean = chat_terreno._visible;
		chat_terreno.inicializar("Terreno", id_param, c_chat.COR_PRETA);
		chat_terreno._visible = chat_terrreno_visivel;
	}
	
	/*
	* Inicializações em geral, como criação dos chats.
	* @param terreno_param O terreno em que o comunicador está.
	* @param id_chat_terreno Id no banco de dados do chat do terreno.
	* @param id_chat_turma Id no banco de dados do chat da turma.
	* @param id_chat_privado Id no banco de dados do que recebe falas para o usuário logado.
	*/
	public function inicializar(terreno_param:c_terreno_bd, id_chat_terreno:String, id_chat_privado:String){
		var ha_chatTerreno:Boolean = (terreno_param.getPlaneta().getAparencia() == c_terreno_bd.TIPO_QUARTO? false : true);
		var ha_chatTurma:Boolean = true;
		var ha_chatAmigo:Boolean = true;
		fala.inicializar(falaDigitada, this);
		
		if(ha_chatTerreno){
			attachMovie(c_chat.LINK_BIBLIOTECA, "chatTerreno", getNextHighestDepth(), {_x:texto_principal._x, _y:texto_principal._y});
			chat_terreno = this['chatTerreno'];
			chat_terreno.inicializar("Terreno", id_chat_terreno, c_chat.COR_PRETA);
			chat_terreno.addEventListener("dadosRecebidos", Delegate.create(this, falaRecebida));
			chat_terreno._visible = false;
		}
		
		if(ha_chatTurma){
			attachMovie(c_chat.LINK_BIBLIOTECA, "chatPrivadoRecebe", getNextHighestDepth()); //Não deve ser visto.
			chat_privado_recebe = this['chatPrivadoRecebe'];
			chat_privado_recebe.inicializar("Privado", id_chat_privado, c_chat.COR_ROXO_FRACO);
			chat_privado_recebe.addEventListener("dadosRecebidos", Delegate.create(this, falaRecebida));
			chat_privado_recebe._visible = false;
		}
		
		inicializar_header_(ha_chatTerreno, ha_chatTurma, ha_chatAmigo);
		
		setChatDestino(procurarChatExistente());
	}
	
	/*
	* Faz com o chat de parâmetro passe a ser o chat_destino.
	*/
	public function setChatDestino(chat_param:c_chat):Void{
		if(chat_param != chat_privado_recebe and chat_param != undefined){
			chat_destino = chat_param;
			header_.botaoPrincipal.destinoChat.html = true;
			header_.botaoPrincipal.destinoChat.htmlText = "<b>"+chat_param.getNome()+"</b>";
		} else if(chat_param == undefined){
			chat_destino = undefined;
			header_.botaoPrincipal.destinoChat.html = true;
			header_.botaoPrincipal.destinoChat.htmlText = "<b> - </b>";
		}
	}
	
	/*
	* Função executada toda vez que "fala" recebe uma fala do usuário logado.
	* Caso algum atalho tenha sido digitado, recebe-o como parâmetro.
	*/
	public function falaDigitada(){
		/*switch(fala.detectarAtalhos()){
			case c_fala_comunicador.ATALHO_CHAT_TERRENO: setChatDestino(chat_terreno);
				break;
			case c_fala_comunicador.ATALHO_CHAT_TURMA: setChatDestino(chat_turma);
				break;
			case c_fala_comunicador.ATALHO_CHAT_PRIVADO: procurarChatAmigoUsuario(fala.getPrimeiraOpcaoAtalho());
				break;
			case c_fala_comunicador.ATALHO_CHAT_AMIGO: procurarChatAmigo(fala.getPrimeiraOpcaoAtalho());
				break;
			case c_fala_comunicador.ATALHO_CRIAR_CHAT_AMIGO: criarChat(fala.getPrimeiraOpcaoAtalho());
				break;
			case c_fala_comunicador.ATALHO_CHAT_AMIGO_CONVIDAR: convidarParaAmigo(fala.getPrimeiraOpcaoAtalho());
				break;
			case c_fala_comunicador.ATALHO_VER_TODOS_CHATS: definirChatUnico(undefined);
				break;
			case c_fala_comunicador.ATALHO_VER_CHAT_TERRENO: definirChatUnico(chat_terreno);
				break;
			case c_fala_comunicador.ATALHO_VER_CHAT_TURMA: definirChatUnico(chat_turma);
				break;
			case c_fala_comunicador.ATALHO_VER_CHAT_AMIGO: definirChatUnico(chat_amigo);
				break;
			case c_fala_comunicador.ATALHO_SAIR_CHAT_AMIGO: sairDoChat(chat_amigo);
				break;
			case c_fala_comunicador.ATALHO_AJUDA: exibirMensagemAjuda();
				break;
			default: enviarMensagemDigitada();
		}
		fala.limparTexto();*/
		if(chat_destino == undefined){
			c_aviso_com_ok.mostrar("Favor escolher um chat com o qual falar.");
		} else {
			enviarMensagemDigitada();
			fala.limparTexto();
		}
	}
	
	/*
	* Exibe uma mensagem simples explicando os atalhos do comunicador.
	*/
	private function exibirMensagemAjuda():Void{
		enviarMensagemSucesso("Esta é a ajuda do comunicador\nPara falar com o chat do terreno em que está, digite \"-t\"\nPara falar com o chat do planeta em que está, digite \"-p\"\nPara falar com um usuário online, digite \"-a 'nome do usuário'\"\nPara juntar-se a um chat grupo, digite \"-g 'nome do chat'\"\nPara criar um chat grupo, digite \"-cg 'nome do chat'\"\nPara sair de um chat grupo, digite \"-sg\"\nPara convidar um usuário ao seu chat grupo, digite \"-c 'nome do usuário'\"\nPara ver todos chats, digite \"-v\"\nPara ver somente o chat do terreno em que está, digite \"-vt\"\nPara ver somente o chat do planeta em que está, digite \"-vp\"\nPara ver somente o chat de grupo em que está, digite \"-vg\"\n");
	}
	
	/*
	* Dado um chat, desfaz conexão com ele.
	*/
	private function sairDoChat(chat_param:c_chat):Void{
		if(chat_destino.getIdentificacaoBancoDeDados() == chat_param.getIdentificacaoBancoDeDados()){
			if(chat_terreno.ativo()){
				setChatDestino(chat_terreno);
			} else {
				setChatDestino(chat_turma);
			}
			
		}
		if(chat_unico.getIdentificacaoBancoDeDados() == chat_param.getIdentificacaoBancoDeDados()){
			definirChatUnico(undefined);
		}
		
		if(chat_param.getIdentificacaoBancoDeDados() == chat_amigo.getIdentificacaoBancoDeDados()){
			notificarServidorSaidaChatAmigo();
			enviarMensagem("O usuário "+_root.personagem_status.getNome()+" deixou o chat "+chat_amigo.getNome()+".", 
							chat_amigo.getIdentificacaoBancoDeDados(), false);
			chat_amigo.removeMovieClip();
			chat_amigo = undefined;
			header_.comunicador_menu.abaAmigo.alternarInterfaceChatAmigo(chat_amigo.getIdentificacaoBancoDeDados());
		}
	}
	
	/*
	* Se estiver saindo de um chat amigo, esta função aviso o servidor e verifica se o chat já deve ser fechado.
	*/
	private function notificarServidorSaidaChatAmigo():Void{
		if(chat_amigo != undefined){
			enviaNotificacao = new LoadVars();
			recebeNotificacao = new LoadVars();
			enviaNotificacao.chat_id = chat_amigo.getIdentificacaoBancoDeDados();
			recebeNotificacao.onLoad = fazNada;
			enviaNotificacao.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_NOTIFICAR_SAIDA_CHAT_AMIGO, recebeNotificacao, "POST");
		}
	}
	
	/*
	* Chat único é aquele que tem suas falas mostradas.
	* Se for passado undefined, mostrará falas de todos chats.
	* Ao definir um chat único válido, só será possível ver falas deste chat.
	* @param chat_unico_param O chat que será mostrado.
	*/
	private function definirChatUnico(chat_unico_param:c_chat){
		if(chat_unico_param != undefined){
			if(chat_unico != undefined){
				chat_unico._visible = false;
			}
			chat_unico = chat_unico_param;
			texto_principal._visible = false;
			chat_unico._visible = true;
			setChatDestino(chat_unico);
		} else {
			chat_unico._visible = false;
			chat_unico = undefined;
			texto_principal._visible = true;
			//setChatDestino(chat_terreno);
		}
	}
	
	/*
	* Função executada sempre que a barra de rolagem é movida.
	* O evento passado possui duas propriedades:
	*	inicioSubLista:Number - O início da sublista visível.
	*	fimSubLista:Number - O fim da sublista visível.
	*/
	private function barra_scroll_moveu(eventoScroll:Object):Void{
		if(chat_unico != undefined){ //Mostrar somente o chat que está ativo.
			chat_unico.scrollPara(eventoScroll.inicioSubLista+1);
		} else { //Todos os chats estão ativos.
			texto_principal.scroll = eventoScroll.inicioSubLista+1;
		}
	}
	
	/*
	* Inicializa e permite configurar os chats que terão no terreno.
	* @param ha_chatTerreno_param Booleano que determina se o chat de terreno está habilitado.
	* @param ha_chatTurma_param Booleano que determina se o chat de turmas está habilitado.
	* @param ha_chatAmigo_param Booleano que determina se o chat de amigos está habilitado.
	*/
	private function inicializar_header_(ha_chatTerreno_param:Boolean, ha_chatTurma_param:Boolean, ha_chatAmigo_param:Boolean):Void{
		redimensionador.toggleAbrirFechar = function(){
			if(_currentframe == 1){ //Abrir
				gotoAndStop(2);
				this['redimSeta'].gotoAndStop(2);
			} else { //Fechar
				gotoAndStop(1);
				this['redimSeta'].gotoAndStop(1);
			}
		}
		
		header_.attachMovie(c_comunicador_menu.LINK_BIBLIOTECA, "comunicador_menu", this.header_.getNextHighestDepth());		
		header_.comunicador_menu._visible = false;
		header_.comunicador_menu._x = this.header_.botaoPrincipal._x;
		header_.comunicador_menu._y = (this.header_.botaoPrincipal._y + this.header_.botaoPrincipal._height);
		header_.comunicador_menu.inicializar(ha_chatTerreno_param, ha_chatTurma_param, ha_chatAmigo_param);
		header_.toggleAbrirFechar = function(){
			if(_currentframe == 5){ //Frame em que um play o faz fechar.
				_parent.definirChatUnico(undefined);
			}
			play();
			_parent.redimensionador.toggleAbrirFechar();
		}
		header_.fechar = function(){
			if(_currentframe == 5){
				_parent['header_'].toggleAbrirFechar(); //É necessária a redundância.
			} 
		}
		header_.btAlternaEstado.gotoAndStop(2);
		header_.btAlternaEstado.onPress = Delegate.create(this, alternarMinimizadoRestaurado);
		c_localizacao.criarPara(header_.btAlternaEstado, "Minimizar");
		header_.botaoPrincipal.comunicadorMinimizado._visible = false;
		header_.comunicador_menu.addEventListener("toggleVisibilidadeBaloes", Delegate.create(this, toggleVisibilidadeBaloes));	
		header_.comunicador_menu.addEventListener("botaoPressionado", Delegate.create(this, botaoPressionadoMenu));
		
		redimensionador.onPress = function(){
			_parent['header_'].toggleAbrirFechar();
		}
	}
	
	/*
	* Executada toda vez que algum botão é pressionado no menu.
	* Recebe um evento com os seguintes atributos:
	*	- tipoMenu O tipo do menu que foi selecionado. É como definido no início de c_comunicador_menu.
	*	- opcaoSelecionada O nome da opção que foi selecionada.
	* Cuidado! O segundo atributo somente aparecerá em alguns casos! Há botões que não o necessitam e não o passam.
	*/
	private function botaoPressionadoMenu(evento_botao_pressionado:Object):Void{
		//_root.outroTerreno.mp.debug2.text+="tipo("+evento_botao_pressionado.tipoAcao+")compl("+evento_botao_pressionado.opcao+")\n";
		switch(evento_botao_pressionado.tipoAcao){
			case c_fala_comunicador.ATALHO_CHAT_PRIVADO: 
				definirChatUnico(undefined);
				if(ha_chatPrivado){
					procurarChatAmigoUsuario(evento_botao_pressionado.opcao);
				}
				break;
			case c_fala_comunicador.ATALHO_CHAT_TURMA: procurarChat(evento_botao_pressionado.opcao, TIPO_TURMA);
				break;
			case c_fala_comunicador.ATALHO_VER_CHAT_TERRENO: definirChatUnico(chat_terreno);
				break;
			case c_fala_comunicador.ATALHO_VER_CHAT_TURMA: definirChatUnico(chat_turma);
				break;
			case c_fala_comunicador.ATALHO_VER_CHAT_AMIGO: definirChatUnico(chat_amigo);
				break;
			case c_fala_comunicador.ATALHO_CHAT_AMIGO_CONVIDAR: convidarParaAmigo(evento_botao_pressionado.opcao);
				break;
			case c_fala_comunicador.ATALHO_CRIAR_CHAT_AMIGO: criarChat(evento_botao_pressionado.opcao);
				break;
			case c_fala_comunicador.ATALHO_SAIR_CHAT_AMIGO: sairDoChat(chat_amigo);
				break;
			case c_fala_comunicador.ATALHO_CHAT_AMIGO: procurarChat(evento_botao_pressionado.opcao, TIPO_AMIGO);
				break;
		}
	}

	/*
	* Dispara evento com mensagem que o usuário deseja enviar.
	*/
	private function enviarMensagemDigitada():Void{
		var mensagem:String;
		mensagem = fala.getTexto();
		fala.limparTexto();
		enviarMensagem(mensagem, chat_destino.getIdentificacaoBancoDeDados(), true);
		dispatchEvent({target:this, type:"mensagemEnviada", mensagem: mensagem});
	}

	/*
	* Função executada toda vez que algum chat recebe falas do servidor.
	* O evento passado possui uma propriedade:
	* 	falas - Array de falas recebidas formatadas.
	*/
	private function falaRecebida(evento_fala_param:Object):Void{
		var numeroTotalLinhas:Number;
		var tamanhoFalasDoEventosFalaParam:Number = evento_fala_param.falas.length;
		for(var indice:Number = 0; indice<tamanhoFalasDoEventosFalaParam; indice++){
			texto_principal.htmlText += evento_fala_param.falas[indice];
			texto_principal.scroll = texto_principal.maxscroll;
		}
		
		if(chat_unico != undefined){ //Mostrar somente o chat que está ativo.
			numeroTotalLinhas = chat_unico.getNumeroLinhas();
		} else {
			numeroTotalLinhas = texto_principal.maxscroll + texto_principal.bottomScroll - texto_principal.scroll;
		}
		
		barra_rolagem.init_barra_rolagem(numeroTotalLinhas, NUMERO_VISIVEL_LINHAS);
	}

	/*
	* Função executada toda vez que o botão "toggleVisibilidadeBalões" do menu for pressionado
	*/
	private function toggleVisibilidadeBaloes(){
		dispatchEvent({target:this, type:"toggleVisibilidadeBaloes"});
	}

	/*
	* Envia uma mensagem a algum chat, para que seja salva no banco de dados.
	* @param fala_param A mensagem a ser enviada.
	* @param id_chat_destino_param A id do chat ao qual a mensagem será enviada.
	* @param ha_autor Determina se há autor da mensagem. Caso haja, o autor é sempre o usuário online.
	*/
	private function enviarMensagem(mensagem_param:String, id_chat_destino_param:String, ha_autor:Boolean):Void{
		enviaMensagem = new LoadVars();
		recebeMensagem = new LoadVars();
		
		enviaMensagem.id_chat = id_chat_destino_param;
		enviaMensagem.ha_autor = ha_autor;
		enviaMensagem.mensagem = mensagem_param;
		
		recebeMensagem.onLoad = Delegate.create(this, mensagemEnviada);
		enviaMensagem.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_ENVIAR_MENSAGEM_CHAT, recebeMensagem, "POST");
	}
	/*
	* Função executada após enviar uma fala com enviarMensagem.
	*/
	private function mensagemEnviada(success):Void{
		if(recebeMensagem.erro != c_banco_de_dados.SEM_ERRO){
			enviarMensagemErro(c_banco_de_dados.getMensagemErro(recebeMensagem.erro));
		}
	}
	
	/*
	* Procura o chat do usuário com o nome passado como parâmetro.
	*/
	private function procurarChatAmigoUsuario(nome_usuario_param:String):Void{
		enviaChatUsuario = new LoadVars();
		recebeChatUsuario = new LoadVars();
		
		enviaChatUsuario.nomeUsuarioChat = nome_usuario_param;
		
		recebeChatUsuario.onLoad = Delegate.create(this, chatUsuarioEncontrado);
		enviaChatUsuario.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_PROCURAR_CHAT_USUARIO, recebeChatUsuario, "POST");
	}
	/*
	* Função executada após procurar um chat de usuário com procurarChatAmigoUsuario.
	*/
	private function chatUsuarioEncontrado(success):Void{
		if(success and recebeChatUsuario.erro == c_banco_de_dados.SEM_ERRO){
			if(chat_privado_envia == undefined){
				attachMovie(c_chat.LINK_BIBLIOTECA, "chatPrivadoEnvia", getNextHighestDepth(), {_x:texto_principal._x, _y:texto_principal._y});
				chat_privado_envia = this['chatPrivadoEnvia'];
				chat_privado_envia.inicializar(recebeChatUsuario.nomeUsuarioDestino, recebeChatUsuario.idChatUsuarioDestino, c_chat.COR_ROXO_FORTE);
				chat_privado_envia.definirFiltragemMensagens(true);
				chat_privado_envia.addEventListener("dadosRecebidos", Delegate.create(this, falaRecebida));
			} else {
				chat_privado_envia.definirNome(recebeChatUsuario.nomeUsuarioDestino);
				chat_privado_envia.definirIdentificacaoBancoDeDados(recebeChatUsuario.idChatUsuarioDestino);
			}
			chat_privado_envia._visible = false;
			setChatDestino(chat_privado_envia);
		} else {
			if(recebeChatUsuario.erro != undefined){
				enviarMensagemErro(c_banco_de_dados.getMensagemErro(recebeChatUsuario.erro));
			}
		}
	}
	
	/*
	* Procura o chat de nome passado como parâmetro.
	* Deve ser usada para setar chat_amigo com o chat cujo nome é argumento.
	* @param tipo_chat_param É o chat, que pode ser amigo, usuário, turma...
	*/
	private function procurarChat(nome_chat_param:String, tipo_chat_param:Number):Void{
		if(chat_amigo == undefined or chat_amigo.getNome() != nome_chat_param){
			enviaChat = new LoadVars();
			recebeChat = new LoadVars();
		
			enviaChat.nomeChat = nome_chat_param;
			enviaChat.tipoChat = tipo_chat_param;
		
			if(tipo_chat_param == TIPO_AMIGO){
				recebeChat.onLoad = Delegate.create(this, chatAmigoEncontrado);
			} else if(tipo_chat_param == TIPO_TURMA){
				recebeChat.onLoad = Delegate.create(this, chatTurmaEncontrado);
			}
			enviaChat.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_PROCURAR_CHAT, recebeChat, "POST");
		} else {
			setChatDestino(chat_amigo);
		}
	}
	/*
	* Função executada após procurar um chat com procurarChatAmigo.
	*/
	private function chatAmigoEncontrado(success):Void{
		if(success and recebeChat.erro == c_banco_de_dados.SEM_ERRO){
			if(chat_amigo == undefined){
				attachMovie(c_chat.LINK_BIBLIOTECA, "chatAmigo", getNextHighestDepth(), {_x:texto_principal._x, _y:texto_principal._y});
				chat_amigo = this['chatAmigo'];
				chat_amigo.inicializar(recebeChat.nomeChat, recebeChat.idChat, c_chat.COR_VERDE);
				chat_amigo.addEventListener("dadosRecebidos", Delegate.create(this, falaRecebida));
			} else {
				chat_amigo.definirNome(recebeChat.nomeChat);
				chat_amigo.definirIdentificacaoBancoDeDados(recebeChat.idChat);
			}
			header_.comunicador_menu.abaAmigo.definirIdChatAmigo(chat_amigo.getIdentificacaoBancoDeDados());
			header_.comunicador_menu.abaAmigo.abrirInterfaceContatos();
			chat_amigo._visible = false;
			setChatDestino(chat_amigo);
		} else {
			if(recebeChat.erro != undefined){
				enviarMensagemErro(c_banco_de_dados.getMensagemErro(recebeChat.erro));
			}
		}
	}
	/*
	* Função executada após procurar um chat com procurarChatAmigo.
	*/
	private function chatTurmaEncontrado(success):Void{
		if(success and recebeChat.erro == c_banco_de_dados.SEM_ERRO){
			if(chat_turma == undefined){
				attachMovie(c_chat.LINK_BIBLIOTECA, "chatTurma", getNextHighestDepth(), {_x:texto_principal._x, _y:texto_principal._y});
				chat_turma = this['chatTurma'];
				chat_turma.inicializar(recebeChat.nomeChat, recebeChat.idChat, c_chat.COR_TURQUESA);
				chat_turma.addEventListener("dadosRecebidos", Delegate.create(this, falaRecebida));
			} else {
				chat_turma.definirNome(recebeChat.nomeChat);
				chat_turma.definirIdentificacaoBancoDeDados(recebeChat.idChat);
			}
			chat_turma._visible = false;
			setChatDestino(chat_turma);
		} else {
			if(recebeChat.erro != undefined){
				enviarMensagemErro(c_banco_de_dados.getMensagemErro(recebeChat.erro));
			}
		}
	}
	
	/*
	* Tenta criar um chat com o chat passado como parâmetro.
	*/
	private function criarChat(nome_chat:String):Void{
		enviaCriarChat = new LoadVars();
		recebeCriarChat = new LoadVars();
		
		enviaCriarChat.nomeChat = nome_chat;
		
		recebeCriarChat.onLoad = Delegate.create(this, chatCriado);
		enviaCriarChat.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_CRIAR_CHAT, recebeCriarChat, "POST");
	}
	/*
	* Função executada após tentar criar um chat com procurarChatAmigo.
	*/
	private function chatCriado(success):Void{
		if(success and recebeCriarChat.erro == c_banco_de_dados.SEM_ERRO){
			if(chat_amigo == undefined){
				attachMovie(c_chat.LINK_BIBLIOTECA, "chatAmigo", getNextHighestDepth(), {_x:texto_principal._x, _y:texto_principal._y});
				chat_amigo = this['chatAmigo'];
				chat_amigo.inicializar(recebeCriarChat.nomeChat, recebeCriarChat.idChat, c_chat.COR_VERDE);
				chat_amigo.addEventListener("dadosRecebidos", Delegate.create(this, falaRecebida));
			} else {
				chat_amigo.definirNome(recebeCriarChat.nomeChat);
				chat_amigo.definirIdentificacaoBancoDeDados(recebeCriarChat.idChat);
			}
			chat_amigo._visible = false;
			header_.comunicador_menu.abaAmigo.definirIdChatAmigo(chat_amigo.getIdentificacaoBancoDeDados());
			setChatDestino(chat_amigo);
			header_.comunicador_menu.abaAmigo.abrirInterfaceContatos();
		} else {
			if(recebeCriarChat.erro != undefined){
				enviarMensagemErro(c_banco_de_dados.getMensagemErro(recebeCriarChat.erro));
			}
		}
	}
	
	/*
	* Tenta convidar o usuário com o nome passado como parâmetro para entrar no mesmo grupo de chat deste usuário.
	* Caso este usuário não esteja em grupo de chat, manda mensagem de erro.
	*/
	private function convidarParaAmigo(nome_usuario_param:String):Void{
		if(chat_amigo != undefined){
			enviaConvidarAmigo = new LoadVars();
			recebeConvidarAmigo = new LoadVars();
			
			enviaConvidarAmigo.nomeUsuarioDestino = nome_usuario_param;
			enviaConvidarAmigo.idChat = chat_amigo.getIdentificacaoBancoDeDados();
			enviaConvidarAmigo.nomeChat = chat_amigo.getNome();
			
			recebeConvidarAmigo.onLoad = Delegate.create(this, amigoConvidado);
			enviaConvidarAmigo.sendAndLoad(c_banco_de_dados.ARQUIVO_PHP_CONVIDAR_PARA_CHAT, recebeConvidarAmigo, "POST");
		} else {
			enviarMensagemErro(c_banco_de_dados.getMensagemErro(c_banco_de_dados.ERRO_NAO_ESTAH_EM_CHAT_AMIGO));
		}
	}
	/*
	* Função executada após tentar convidar um amigo com convidarParaAmigo.
	*/
	private function amigoConvidado(success){
		if(success and recebeConvidarAmigo.erro == c_banco_de_dados.SEM_ERRO){
			enviarMensagemSucesso("O usuário "+recebeConvidarAmigo.nomeUsuario+" foi convidado para o chat "+recebeConvidarAmigo.nomeChat+" com sucesso.");
		} else {
			if(recebeConvidarAmigo.erro != undefined){
				enviarMensagemErro(c_banco_de_dados.getMensagemErro(recebeConvidarAmigo.erro));
				if(recebeConvidarAmigo.erro == c_banco_de_dados.ERRO_USUARIO_NAO_ENCONTRADO){
					c_aviso_usuario_nao_encontrado.criarPara(this, new Point(300, 300), 3000);
				}
			}
		}
	}
	
	/*
	* Envia uma mensagem de erro para o chat que estiver sendo visto pelo personagem.
	*/
	public function enviarMensagemErro(mensagem_erro:String):Void{
		var mensagem_formatada:String = c_chat.formatarFalaParaHTML("Aviso", mensagem_erro, "", c_chat.COR_VERMELHA_AVISO);
		var numeroTotalLinhas:Number;
		texto_principal.htmlText += mensagem_formatada;
		texto_principal.scroll = texto_principal.maxscroll;
		if(chat_unico != undefined){
			chat_unico.adicionarFala(mensagem_formatada);
			numeroTotalLinhas = chat_unico.getNumeroLinhas();
		} else {
			numeroTotalLinhas = texto_principal.maxscroll + texto_principal.bottomScroll - texto_principal.scroll;
		}
		barra_rolagem.init_barra_rolagem(numeroTotalLinhas, NUMERO_VISIVEL_LINHAS);
	}
	
	/*
	* Envia uma mensagem de sucesso para o chat que estiver sendo visto pelo personagem.
	*/
	public function enviarMensagemSucesso(mensagem_sucesso:String):Void{
		var mensagem_formatada:String = c_chat.formatarFalaParaHTML("Aviso", mensagem_sucesso, "", c_chat.COR_VERDE_AVISO);
		var numeroTotalLinhas:Number;
		texto_principal.htmlText += mensagem_formatada;
		texto_principal.scroll = texto_principal.maxscroll;
		if(chat_unico != undefined){
			chat_unico.adicionarFala(mensagem_formatada);
			numeroTotalLinhas = chat_unico.getNumeroLinhas();
		} else {
			numeroTotalLinhas = texto_principal.maxscroll + texto_principal.bottomScroll - texto_principal.scroll;
		}
		barra_rolagem.init_barra_rolagem(numeroTotalLinhas, NUMERO_VISIVEL_LINHAS);
	}

	/*
	* Alterna entre os estados de minimizado e restaurado.
	* 	Restaurado - Permite comunicação/minimização - é o chat em si.
	*	Minimizado - Torna-se uma pequena barrinha, cuja única função é a restauração.
	*/
	public function alternarMinimizadoRestaurado():Void{
		var minimizado:Boolean = (header_.btAlternaEstado._currentframe == 1);
		if(minimizado){ //Restaurar
			c_localizacao.destruirDe(header_.btAlternaEstado);
			c_localizacao.criarPara(header_.btAlternaEstado, "Minimizar");
			header_.botaoPrincipal.comunicadorMinimizado._visible = false;
			header_.botaoPrincipal.falandoCom._visible = true;
			header_.botaoPrincipal.destinoChat._visible = true;
			if(chat_unico != undefined){
				chat_unico._visible = true;
			}
			barra_rolagem._visible = true;
			fala._visible = true;
			redimensionador._visible = true;
			texto_principal._visible = true;
			fundo._visible = true;
			botaoEnviar._visible = true;
			fundoFala._visible = true;
			borda._visible = true;
			header_.btAlternaEstado.gotoAndStop(2);
			header_._y = -33.55;
		} else { //Minimizar
			c_localizacao.destruirDe(header_.btAlternaEstado);
			c_localizacao.criarPara(header_.btAlternaEstado, "Restaurar");
			header_.fechar();
			header_.botaoPrincipal.comunicadorMinimizado._visible = true;
			header_.botaoPrincipal.falandoCom._visible = false;
			header_.botaoPrincipal.destinoChat._visible = false;
			if(chat_unico != undefined){
				chat_unico._visible = false;
			}
			barra_rolagem._visible = false;
			fala._visible = false;
			redimensionador._visible = false;
			texto_principal._visible = false;
			fundo._visible = false;
			botaoEnviar._visible = false;
			fundoFala._visible = false;
			borda._visible = false;
			header_.btAlternaEstado.gotoAndStop(1);
			header_._y = 112.45;
		}
	}
	
	
	
	

	/*
	* Não faz nada mesmo...
	*/
	private function fazNada():Void{}
}


