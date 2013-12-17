import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_interface_trocar_planeta extends ac_interface_menu {
//dados	
	//---- Pesquisa
	private var numerosSistemas:Array = new Array();
	private var nomesSistemas:Array = new Array();
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Constantes
	private static var NUMERO_OPCOES_VISIVEIS_SELECT:Number = 17;

	//---- Terreno
	private var opcaoEscolhida:Number;
	
	//---- Select
	private var menu_select:c_select = null;
	private static var POSX_SELECT:Number = 2.8;
	private static var POSY_SELECT:Number = 65;
	
	//---- Botões
	private var botaoTrocarSistema:c_btTrocarSistema = null;
	private static var POSX_BOTAO_TROCAR_SISTEMA:Number = 338;
	private static var POSY_BOTAO_TROCAR_SISTEMA:Number = 446;
	
	//---- Arquivos
	private static var ARQUIVO_BUSCA_TERRENO_PRINCIPAL:String = "terrenoprincipal.php";
	private static var ARQUIVO_BUSCA_SISTEMAS_PERMISSAO_ACESSAR:String = "planetapermitido.php";
	
	//---- Erros
	private static var SUCESSO:String = "0";
	private static var ERRO_BANCO_DE_DADOS:String = "1";
	private static var ERRO_SOLICITACAO:String = "2";
	private static var ERRO_SEM_TERRENO:String = "4";
	
	private static var MENSAGEM_ERRO_PERMISSAO:String = "Você não pode trocar de sistema!";
	private static var MENSAGEM_ERRO_BANCO_DE_DADOS:String = "Erro! Não conectou no banco de dados";
	private static var MENSAGEM_ERRO_SOLICITACAO:String = "Erro! Não conseguiu fazer a solicitação";
	private static var MENSAGEM_ERRO_DESCONHECIDO:String = "Erro Desconhecido!!!";
	private static var MENSAGEM_ERRO_SERVIDOR:String = "Erro! Não conectou no servidor";
	private static var MENSAGEM_ERRO_SEM_TERRENO:String = "Desculpe, o planeta não possui terrenos.";
	
//métodos	
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
	
		/*Inicializações*/
		inicializarBotaoTrocarSistema();
		
		esconder();
	
		selecionaTrocaSistema();
	}
	
	public function mostrar():Void{
		super.mostrar();
		menu_select._visible = true;
		botaoTrocarSistema._visible = true;
		selecionaTrocaSistema();
		_visible = true;
	}
	public function esconder():Void{
		super.mostrar();
		menu_select._visible = false;
		botaoTrocarSistema._visible = false;
		_visible = false;
	}
	
	//---- Select
	private function inicializarSelect(textoOpcoes_param:Array){	
		if(menu_select != null){//Se já foi inicializado.
			menu_select.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select", getNextHighestDepth(), {_x:POSX_SELECT, _y:POSY_SELECT});
		menu_select.addEventListener("botaoPressionado", Delegate.create(this, atualizarBotaoEscolhido));	
		menu_select.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Planetas");
		menu_select._visible = true;
	}
	private function atualizarBotaoEscolhido(_evento:Object):Void{
		opcaoEscolhida = _evento.posicaoTexto;
	}
	
	//---- Botões
	private function inicializarBotaoTrocarSistema(){
		attachMovie("btTrocarSistema", "botaoTrocarSistema", getNextHighestDepth());
		botaoTrocarSistema._x = POSX_BOTAO_TROCAR_SISTEMA;
		botaoTrocarSistema._y = POSY_BOTAO_TROCAR_SISTEMA;
		botaoTrocarSistema.addEventListener("btTrocarSistemaPress", Delegate.create(this, trocarTerreno));	

		botaoTrocarSistema.inicializar();
	}

	//---- Listeners
	private function trocarTerreno():Void{
		var posicao_sistema_array:Number = opcaoEscolhida;
		var id_terreno_destino = numerosSistemas[posicao_sistema_array];
		if(id_terreno_destino != undefined){
			buscaTerreno(id_terreno_destino);
		}
	}
	
	/*---------------------------------------------------
	*	Troca o sistema que o usuário está logado - Guto - 21.05.09
	---------------------------------------------------*/
	public function selecionaTrocaSistema():Void {
		envia = new LoadVars();
		recebe = new LoadVars();
		
		envia.usuario_id = _root.usuario_status.identificacao;
		envia.usuario_grupo_base = _root.usuario_status.usuario_grupo_base;

		recebe.onLoad = Delegate.create(this, carregaSistemas);
		envia.sendAndLoad(ARQUIVO_BUSCA_SISTEMAS_PERMISSAO_ACESSAR, recebe, "POST");
	}
	
	/*---------------------------------------------------
	*	Carrega e manipula dados recebidos do banco - Guto - 06.05.10 - No Futuro Juntar essa função com a admConfigBd - Guto - 21.05.10
	---------------------------------------------------*/
	private function carregaSistemas(success):Void {
		//_root.debug.text+="carregando "+this.recebe.numero_planetas_encontrados+" sistemas!!";
		if (success) {
			switch(this.recebe.erroAdm) {
				case SUCESSO:
					numerosSistemas = new Array();
					nomesSistemas = new Array();
					for (var n:Number = 0; n < this.recebe.numero_planetas_encontrados; n++) {
						//_root.debug.text+="nome="+eval("this.recebe.nome_planeta" + n)+", id="+eval("this.recebe.id_planeta" + n)+"\n";
						numerosSistemas.push(eval("this.recebe.id_planeta" + n));
						nomesSistemas.push(eval("this.recebe.nome_planeta" + n));
					}
					inicializarSelect(nomesSistemas);
					if (this.recebe.nLoop == 0) {
						c_aviso_com_ok.mostrar(MENSAGEM_ERRO_PERMISSAO);//<<< a interface está invisível quando é chamada
					}
					break;
				case ERRO_BANCO_DE_DADOS:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_BANCO_DE_DADOS);//<<< a interface está invisível quando é chamada
					break;
				case ERRO_SOLICITACAO:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_SOLICITACAO);//<<< a interface está invisível quando é chamada
					break;
				default:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_DESCONHECIDO);//<<< a interface está invisível quando é chamada
					break;	
			}
		}
		else{
			c_aviso_com_ok.mostrar(MENSAGEM_ERRO_SERVIDOR);
		}
	}

	/*---------------------------------------------------
	*	Carrega Id do terreno principal do sistema para o qual usuário deseja se trasnportar - Guto - 21.05.10
	---------------------------------------------------*/
	private function buscaTerreno(sistemaId:Number):Void {
		envia = new LoadVars;
		recebe = new LoadVars;
		envia.sistema_id = sistemaId;
		envia.sendAndLoad(ARQUIVO_BUSCA_TERRENO_PRINCIPAL, recebe, "POST");
		recebe.onLoad = Delegate.create(this, tratarErrosBuscaTerreno);
	}
	private function tratarErrosBuscaTerreno(success:Boolean){
		var terrenoId:Number = 0;
		
		if (success) {
			switch(this.recebe.erroAdm) {
				case SUCESSO:
					terrenoId = recebe.terreno_id;
					trocaTerreno(terrenoId);
					break;
				case ERRO_BANCO_DE_DADOS:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_BANCO_DE_DADOS);
					break;
				case ERRO_SOLICITACAO:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_SOLICITACAO);
					break;
				case ERRO_SEM_TERRENO:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_SEM_TERRENO);
					break;
				default:
					c_aviso_com_ok.mostrar(MENSAGEM_ERRO_DESCONHECIDO);
					break;	
			}
		}
	}

	/*---------------------------------------------------
	*	Carrega Id do terreno principal do sistema para o qual usuário deseja se trasnportar - Guto - 21.05.10
	---------------------------------------------------*/
	private function trocaTerreno(terrenoDefinitivo:Number):Void {
		//terreno_status.terreno_id = terrenoDefinitivo;
		
		//gotoAndPlay("carregando1",1); //Assim que estiver tudo configurado, deve voltar para a cena 1 - Guto - 21.05.10
		//getURL("index.php?terreno_id="+terreno_status.terreno_id, "_self");
		_root.getURL("index.php?terreno_id="+terrenoDefinitivo, "_self");
	}



}