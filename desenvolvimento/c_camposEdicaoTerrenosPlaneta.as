import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_camposEdicaoTerrenosPlaneta extends ac_interface_menu {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

	//---- Interface
	private var camposEdicaoTerrenos:c_interface_edicao_terreno;
	private var POSX_EDICAO_TERRENOS:Number = 420;
	private var POSY_EDICAO_TERRENOS:Number = 0;

	private var camposCriarTerrenos:c_interface_edicao_terreno;
	private var POSX_CRIACAO_TERRENOS:Number = 420;
	private var POSY_CRIACAO_TERRENOS:Number = 0;
	
	//---- Select
	private var NUMERO_OPCOES_VISIVEIS_SELECT:Number = 10;
	
	private var menu_select_terrenos:c_select;
	private var POSX_SELECT_TERRENOS:Number = 0;
	private var POSY_SELECT_TERRENOS:Number = 30 + 20;
	
	//---- Botões
	private var btCriarTerreno:c_btCriarTerreno;
	private var POSX_BT_CRIAR_TERRENO:Number = 0;
	private var POSY_BT_CRIAR_TERRENO:Number = 260;
	
	private var btEditarTerreno:c_btEditarTerreno;
	private var POSX_BT_EDITAR_TERRENO:Number = 175 + 5;
	private var POSY_BT_EDITAR_TERRENO:Number = POSY_BT_CRIAR_TERRENO;
	
	//---- Mensagens
	private var MENSAGEM_TERRENO_ADICIONADO:String = "O terreno foi adicionado com sucesso!";
	private var MENSAGEM_TERRENO_EXISTENTE:String = "Desculpe, este terreno já existe.";
	private var MENSAGEM_TERRENO_EDITADO:String = "O terreno foi editado com sucesso!";
	
	//---- Terrenos
	private var terrenos:Array = new Array();
	private var indiceTerrenoSelecionado:Number = undefined;
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();

		attachMovie("btCriarTerreno", "btCriarTerreno", getNextHighestDepth());
		btCriarTerreno.inicializar();
		btCriarTerreno._x = POSX_BT_CRIAR_TERRENO;
		btCriarTerreno._y = POSY_BT_CRIAR_TERRENO;
		btCriarTerreno.addEventListener("btCriarTerrenoPress", Delegate.create(this, criarTerreno));	
		btCriarTerreno._visible = true;
		
		attachMovie("btEditarTerreno", "btEditarTerreno", getNextHighestDepth());
		btEditarTerreno.inicializar();
		btEditarTerreno._x = POSX_BT_EDITAR_TERRENO;
		btEditarTerreno._y = POSY_BT_EDITAR_TERRENO;
		btEditarTerreno.addEventListener("btEditarTerrenoPress", Delegate.create(this, editarTerreno));	
		btEditarTerreno._visible = false;
		
		attachMovie("camposEditarTerreno", "camposEdicaoTerrenos", getNextHighestDepth());
		camposEdicaoTerrenos.inicializar();
		camposEdicaoTerrenos._x = POSX_EDICAO_TERRENOS;
		camposEdicaoTerrenos._y = POSY_EDICAO_TERRENOS;
		camposEdicaoTerrenos.addEventListener("confirmar", Delegate.create(this, carregarTerrenoEditadoUsuario));	
		camposEdicaoTerrenos.addEventListener("cancelar", Delegate.create(this, cancelarEdicao));	
		camposEdicaoTerrenos.esconder();
		
		attachMovie("camposEditarTerreno", "camposCriarTerrenos", getNextHighestDepth());
		camposCriarTerrenos.inicializar();
		camposCriarTerrenos._x = POSX_CRIACAO_TERRENOS;
		camposCriarTerrenos._y = POSY_CRIACAO_TERRENOS;
		camposCriarTerrenos.addEventListener("confirmar", Delegate.create(this, carregarTerrenoCriadoUsuario));	
		camposCriarTerrenos.addEventListener("cancelar", Delegate.create(this, cancelarEdicao));	
		camposCriarTerrenos.esconder();
	}
	
	//---- Inteface
	public function abrirInterface(){
		menu_select_terrenos._visible = true;
		_visible = true;
	}
	public function fecharInterface(){
		_visible = false;
		menu_select_terrenos._visible = false;
		camposEdicaoTerrenos._visible = false;
	}
	public function exibirOpcoesTerrenos():Void{
		btCriarTerreno._visible = true;
		btEditarTerreno._visible = true;
	}
	private function cancelarEdicao():Void{
		camposEdicaoTerrenos.esconder();
	}
	private function carregarTerrenoCriadoUsuario():Void{
		var terreno_novo:c_terreno_bd = new c_terreno_bd();
		
		terreno_novo.setTerrenoNovo(true);
		terreno_novo.setNome(camposCriarTerrenos.getNome());
		
		if(!terreno_novo.validarSemId()){
			c_aviso_com_ok.mostrar(terreno_novo.getMensagemErro());
		} else {
			if(!menu_select_terrenos.existeOpcao(terreno_novo.getNome())){
				inserirTerreno(terreno_novo);
			} else{
				c_aviso_com_ok.mostrar(MENSAGEM_TERRENO_EXISTENTE);
			}
		}
	}
	private function inserirTerreno(terreno_param:c_terreno_bd):Void{
		terrenos.push(terreno_param);
		menu_select_terrenos.inserirOpcao(terreno_param.getNome());
		c_aviso_com_ok.mostrar(MENSAGEM_TERRENO_ADICIONADO);
		camposCriarTerrenos.esconder();
	}
	private function carregarTerrenoEditadoUsuario():Void{
		var terreno_edicao:c_terreno_bd = new c_terreno_bd();
		
		terreno_edicao.setTerrenoNovo(true);
		terreno_edicao.setNome(camposEdicaoTerrenos.getNome());
		
		if(!terreno_edicao.validar()){
			c_aviso_com_ok.mostrar(terreno_edicao.getMensagemErro());
		} else {
			modificarTerreno(camposEdicaoTerrenos.getTerrenoSendoEditado(), terreno_edicao);
			camposEdicaoTerrenos.esconder();
		}
	}
	private function modificarTerreno(terreno_editado_param:c_terreno_bd, novo_terreno_param:c_terreno_bd):Void{
		var indice:Number = 0;
		
		menu_select_terrenos.substituirOpcao(terreno_editado_param.getNome(), 
											 novo_terreno_param.getNome());
		while(indice < terrenos.length){
			if(terrenos[indice].igual(terreno_editado_param)){
				terrenos[indice].setNome(novo_terreno_param.getNome());
			}
			indice++;
		}
		c_aviso_com_ok.mostrar(MENSAGEM_TERRENO_EDITADO);
		camposCriarTerrenos.esconder();
	}

	//---- Getters
	public function getTerrenos():Array{
		return terrenos;
	}
	
	//---- Setters
	public function setTerrenos(terrenos_param:Array):Void{
		var textoOpcoes:Array = new Array();
		var nomesTerrenos:Array = new Array();
		var indice:Number = 0;
		terrenos = terrenos_param;
		while(indice < terrenos_param.length){
			nomesTerrenos.push(terrenos_param[indice].getNome());
			indice++;
		}
		textoOpcoes = nomesTerrenos;
		if(menu_select_terrenos != null){//Se já foi inicializado.
			menu_select_terrenos.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_terrenos", getNextHighestDepth(), {_x:POSX_SELECT_TERRENOS, _y:POSY_SELECT_TERRENOS});	
		menu_select_terrenos.addEventListener("botaoPressionado", Delegate.create(this, exibirOpcoesTerrenos));	
		menu_select_terrenos.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes, "Terrenos do Planeta");
		menu_select_terrenos._visible = true;
	}
	
	//---- Editar Terrenos
	private function criarTerreno(){
		var terreno:c_terreno_bd = new c_terreno_bd();
		btEditarTerreno._visible = false;
		camposCriarTerrenos.setLabel(camposCriarTerrenos.LABEL_CRIAR_TERRENO);
		camposCriarTerrenos.setNome(new String());
		camposEdicaoTerrenos.esconder();
		camposCriarTerrenos.mostrar();
	}
	private function editarTerreno(){
		var terreno:c_terreno_bd = new c_terreno_bd();

		//selecionar novo
		indiceTerrenoSelecionado = menu_select_terrenos.getIndiceOpcaoSelecionada();
		terreno = terrenos[menu_select_terrenos.getIndiceOpcaoSelecionada()];
		
		btEditarTerreno._visible = false;
		camposEdicaoTerrenos.setTerreno(terreno);
		camposEdicaoTerrenos.setLabel(camposEdicaoTerrenos.LABEL_EDITAR_TERRENO);
		camposEdicaoTerrenos.setNome(terreno.getNome());
		camposCriarTerrenos.esconder();
		camposEdicaoTerrenos.mostrar();
	}
}
