import flash.display.BitmapData;
import mx.utils.Delegate;

/*
* Classe para comunicações com o banco de dados.
* Não precisa ser inicializada nem criada.
* Todos seus métodos são estáticos e acessíveis sem criação.
* Por este motivo, esta classe não possui dados não estáticos.
*/
class c_banco_de_dados{
//dados
	/*
	* Valor da identificação de um dado que ainda não foi salvo no BD.
	*/
	public static var NAO_SALVO:String = new String();
	
	/*
	* Arquivos php utilizados.
	*/
	public static var ARQUIVO_PHP_ENVIAR_MENSAGEM_CHAT:String = "enviar_mensagem_chat.php";
	public static var ARQUIVO_PHP_ATUALIZAR_CHAT:String = "atualizar_chat.php";
	public static var ARQUIVO_PHP_CONVIDAR_PARA_CHAT:String = "convidar_para_chat.php";
	public static var ARQUIVO_PHP_CRIAR_CHAT:String = "criar_chat.php";
	public static var ARQUIVO_PHP_PROCURAR_CHAT:String = "procurar_chat.php";
	public static var ARQUIVO_PHP_PROCURAR_CHAT_USUARIO:String = "procurar_chat_usuario.php";
	public static var ARQUIVO_PHP_PROCURAR_USUARIOS_TERRENO:String = "procurar_usuarios_terreno.php";
	public static var ARQUIVO_PHP_PROCURAR_TURMAS_USUARIO:String = "procurar_turmas_usuario.php";
	public static var ARQUIVO_PHP_PROCURAR_DADOS_TURMAS:String = "procurar_dados_turmas.php";
	public static var ARQUIVO_PHP_PROCURAR_CONTATOS_USUARIO:String = "procurar_contatos_usuario.php";
	public static var ARQUIVO_PHP_PROCURAR_CHATS_CONVIDADO:String = "procurar_chats_convidado.php";
	public static var ARQUIVO_PHP_NOTIFICAR_SAIDA_CHAT_AMIGO:String = "notificar_saida_chat_amigo.php";
	public static var ARQUIVO_PHP_INSERIR_OBJETO:String = "inserir_objeto.php";
	public static var ARQUIVO_PHP_ATUALIZAR_OBJETO:String = "atualizar_objeto.php";
	public static var ARQUIVO_PHP_DELETAR_OBJETO:String = "deletar_objeto.php";
	public static var ARQUIVO_PHP_SCREENSHOTS:String = "interface_bd_SS.php";
	public static var ARQUIVO_PHP_DIVERSOS:String = "interface_bd_personagem.php";
	public static var ARQUIVO_PHP_CRIACAO_ESCOLA:String = "phps_do_menu/cadastrar_escola.php";
	public static var ARQUIVO_PHP_PESQUISA_ESCOLA:String = "phps_do_menu/pesquisa_escola.php";
	public static var ARQUIVO_PHP_CRIACAO_LOG:String = "phps_gerais/criar_log.php";
	public static var ARQUIVO_PHP_REGISTRO_INTER_ROODA:String = "registra_interRooda.php";

	/*
	* Tipos de erro.
	*/
	public static var SEM_ERRO:String = "0";
	public static var ERRO_USUARIO_NAO_ENCONTRADO:String = "1";
	public static var ERRO_USUARIO_OFFLINE:String = "2";
	public static var ERRO_CHAT_NAO_ENCONTRADO:String = "3";
	public static var ERRO_NOME_CHAT_EXISTENTE:String = "4";
	public static var ERRO_AMIGO_ESTAH_EM_OUTRO_CHAT:String = "5";
	public static var ERRO_NAO_ESTAH_EM_CHAT_AMIGO:String = "6";
	public static var ERRO_AMIGO_JA_ESTAH_NO_CHAT:String = "7";
	public static var ERRO_OBJETO_NAO_INSERIDO:String = "8";
	public static var ERRO_OBJETO_NAO_ATUALIZADO:String = "9";
	public static var ERRO_OBJETO_NAO_DELETADO:String = "10";
	
	/*
	* Mensagem de erro utilizada para erros desconhecidos.
	*/
	private static var MENSAGEM_ERRO_DESCONHECIDO:String = "Desculpe. Houve um erro desconhecido.";
	
	
//métodos
	/*
	* Processa e salva uma BitmapData no BD.
	* @param imagem_param a imagem a ser salva.
	* @param usuario_id_param a identificação na tabela de personagens do usuário que terá seu avatar salvo.
	*/
	public static function salvarImagemAvatar(imagem_param:BitmapData, usuario_id_param:String):Void{
		var envia:LoadVars = new LoadVars();
		var recebe:LoadVars = new LoadVars();
		var tmp:String = new String();
		var pixels:Array = new Array();
	
		//construção do array de pixels
		for(var a=0; a<=192; a++){
			for(var b=0; b<=192; b++){
				tmp = imagem_param.getPixel32(a, b).toString(16);
				pixels.push(tmp.substr(1));
			}
		}
	
		envia.usuario_id = usuario_id_param;
		envia.img = pixels.toString();
		envia.height_img = 192;
		envia.width_img = 192;
	
		/*
		* Este trecho antes era assim:
		* 				envia.send("interface_bd_SS.php", "_blank", "POST");//responsável pela abertura de nova aba.
		* No entanto não há meio de fazer o método "send" ser "silencioso".
		* Ele sempre abrirá uma nova janela ou modificará a janela do flash, o que não é desejado.
		* Por este motivo, usou-se um sendAndLoad com um onLoad que não faz nada.
		*/
		envia.sendAndLoad(ARQUIVO_PHP_SCREENSHOTS, recebe, "POST");//responsável pela abertura de nova aba.
		recebe.onLoad = fazNada;
	}
	
	/*
	* Dado um erro, retorna se é conhecido.
	*/
	/*public static function erroConhecido(erro_param:String):Boolean{
		if(){
			
		}
	}*/
	
	/*
	* Dado um erro, retorna sua mensagem de erro.
	* Erros aqui são referentes a dados inexistentes ou coisas do tipo.
	* Erros de lógica na programação não devem estar aqui.
	*/
	public static function getMensagemErro(erro_param:String):String{
		switch(erro_param){
			case SEM_ERRO: return "Não houve erro."; //Não deve acontecer... Indica erro de lógica na programação.
				break;
			case ERRO_USUARIO_NAO_ENCONTRADO: return "Desculpe. O usuário não foi encontrado.";
				break;
			case ERRO_USUARIO_OFFLINE: return "Desculpe. O usuário está offline."; 
				break;
			case ERRO_CHAT_NAO_ENCONTRADO: return "Desculpe. Não foi possível encontrar este chat.";
				break;
			case ERRO_NOME_CHAT_EXISTENTE: return "Desculpe. Já existe um chat com este nome. Tente um nome diferente.";
				break;
			case ERRO_AMIGO_ESTAH_EM_OUTRO_CHAT: return "Desculpe. O usuário já pertence a um chat de grupo. Só é possível estar em um chat de grupo por vez.";
				break;
			case ERRO_NAO_ESTAH_EM_CHAT_AMIGO: return "Desculpe. É necessário estar em um chat de grupo para executar a ação.";
				break;
			case ERRO_AMIGO_JA_ESTAH_NO_CHAT: return "Desculpe. O usuário já encontra-se neste chat.";
				break;
			case ERRO_OBJETO_NAO_INSERIDO: return "Desculpe. O objeto não pôde ser inserido.";
				break;
			case ERRO_OBJETO_NAO_ATUALIZADO: return "Desculpe. O objeto não pôde ser atualizado.";
				break;
			case ERRO_OBJETO_NAO_DELETADO: return "Desculpe. O objeto não pôde ser deletado.";
				break;
			default: return MENSAGEM_ERRO_DESCONHECIDO;
				break;
		}
	}
	
	/*
	* Informa ao banco de dados o acesso a uma funcionalidade.
	* @param linkFuncionalidade_param O link utilizado para acesso da funcionalidade.
	* @param terrenoId_param ID no banco de dados do terreno de qual a funcionalidade está sendo acessada.
	*/
	public static function informaAcessoFuncionalidade(linkFuncionalidade_param:String, terrenoId_param:String){
		var envia:LoadVars = new LoadVars();
		var recebe:LoadVars = new LoadVars();
			
		envia.numeroCaracteresIgnorados = c_objeto_acesso.LINK_BASE.length;
		envia.linkFuncionalidade = linkFuncionalidade_param;
		envia.idTerrenoAtual = terrenoId_param;
		
		recebe.onLoad = function(){};
		envia.sendAndLoad(ARQUIVO_PHP_REGISTRO_INTER_ROODA, recebe, "POST");
	}

	/*
	* Insere um objeto no banco de dados.
	* Até o presente momento, objetos são: casas, árvores e prédios.
	* Falta criar um evento para retornar a id do objeto salvo... Ou talvez um ponteiro como parâmetro?
	* @param acao_param Ação a ser feita quando o objeto for carregado.
	* @param escopo_acao_param Escopo para a ação a ser executada.
	*/
	public static function inserirObjeto(obj:Object, terreno_id:String, acao_param:Function, escopo_acao_param:Object):Void {
		var envia:LoadVars = new LoadVars();
		var recebe:LoadVars = new LoadVars();
			
		envia.autor = _root.personagem_status.getIdentificacaoBancoDeDados();
		envia.obj_terrreno_id = _root.planeta.getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados().getIdentificacao();
		
		envia.obj_frame = obj._currentframe;
		if(obj.abrirPorta != undefined){
			envia.obj_movieclip = "objeto_link";
		} else if(obj.getFrameTipoAparencia == undefined){
			envia.obj_movieclip = "predio";
		} else {
			envia.obj_movieclip = "parede";
		}
		envia.obj_terreno_pos_x = obj._x;
		envia.obj_terreno_pos_y = obj._y;
		
		recebe['acao'] = acao_param;
		recebe['escopo_acao'] = escopo_acao_param;
		recebe['nome_objeto'] = obj._name;
		recebe.onLoad = function(){
			this['acao'].call(this.escopo_acao, this);
		};
		envia.sendAndLoad(ARQUIVO_PHP_INSERIR_OBJETO, recebe, "POST");
	}
	/*
	* Atualiza um objeto no banco de dados.
	* Até o presente momento, objetos são: casas, árvores e prédios.
	* @param acao_param Ação a ser feita quando o objeto for carregado.
	* @param escopo_acao_param Escopo para a ação a ser executada.
	*/
	public static function atualizarObjeto(obj:Object, acao_param:Function, escopo_acao_param:Object):Void {
		var envia:LoadVars = new LoadVars();
		var recebe:LoadVars = new LoadVars();

		envia.autor = _root.usuario_status.personagem_id;
		envia.terreno_id = _root.planeta.getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados().getIdentificacao();
		envia.ident = obj.getIdentificacaoBancoDeDados();
		if(obj.getFrameTipoAparencia() != undefined){
			envia.numFrame = obj.getFrameTipoAparencia();
		} else {
			envia.numFrame = obj._currentframe;
		}
		envia.terreno_posicao_x = obj._x;
		envia.terreno_posicao_y = obj._y;
		
		recebe['acao'] = acao_param;
		recebe['escopo_acao'] = escopo_acao_param;
		recebe['nome_objeto'] = obj._name;
		recebe.onLoad = function(){
			this['acao'].call(this.escopo_acao, this);
		};		//A definição do evento onLoad deve vir antes da chamada sendAndLoad, visto que o evento onLoad deve estar definido quando a instância recebe é atribuida aos dados recebidos do php - Guto - 08.07.10
		envia.sendAndLoad(ARQUIVO_PHP_ATUALIZAR_OBJETO, recebe, "POST");
	}
	/*
	* Apaga um objeto no banco de dados.
	* Até o presente momento, objetos são: casas, árvores e prédios.
	* @param acao_param Ação a ser feita quando o objeto for carregado.
	* @param escopo_acao_param Escopo para a ação a ser executada.
	*/
	public static function apagarObjeto(obj:Object, acao_param:Function, escopo_acao_param:Object):Void {
		var envia:LoadVars = new LoadVars();
		var recebe:LoadVars = new LoadVars();

		envia.autor = _root.usuario_status.personagem_id;
		envia.terreno_id = _root.planeta.getTerrenoEmQuePersonagemEstah().getImagemBancoDeDados().getIdentificacao()
		envia.ident = obj.getIdentificacaoBancoDeDados();
		
		recebe['acao'] = acao_param;
		recebe['escopo_acao'] = escopo_acao_param;
		recebe['nome_objeto'] = obj._name;
		recebe.onLoad = function(){
			this['acao'].call(this.escopo_acao, this);
		};//A definição do evento onLoad deve vir antes da chamada sendAndLoad, visto que o evento onLoad deve estar definido quando a instância recebe é atribuida aos dados recebidos do php - Guto - 08.07.10
		envia.sendAndLoad(ARQUIVO_PHP_DELETAR_OBJETO, recebe, "POST");
	}















	/*
	* Necessária em alguns métodos desta classe. A função não faz nada absolutamente.
	*/
	private static function fazNada(success):Void{
	
	}
}
