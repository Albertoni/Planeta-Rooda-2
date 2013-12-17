import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;

class c_interface_edicao_turmas_conta extends ac_interface_menu {
//dados
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

	//---- Select
	private var NUMERO_OPCOES_VISIVEIS_SELECT:Number = 5;
	private var menu_select_turmas:c_select = null;
	private var POSX_SELECT_TURMAS:Number = 0;
	private var POSY_SELECT_TURMAS:Number = 0 + 45;
	private var menu_select_turmas_convidado:c_select = null;
	private var POSX_SELECT_TURMAS_CONVIDADO:Number = POSX_SELECT_TURMAS;
	private var POSY_SELECT_TURMAS_CONVIDADO:Number = POSY_SELECT_TURMAS + 135 + 30;
	private var menu_select_todas_turmas:c_select = null;
	private var NUMERO_OPCOES_VISIVEIS_SELECT_TODAS_TURMAS:Number = 17;
	private var POSX_SELECT_TODAS_TURMAS:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_SELECT_TODAS_TURMAS:Number = POSY_SELECT_TURMAS;
	
	//---- Botões
	private var btInscrever:c_btInscrever;
	private var POSX_BT_INSCREVER:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_BT_INSCREVER:Number = 400;
	private var btConvidar:c_btConvidar;
	private var POSX_BT_CONVIDAR:Number = POSX_SELECT_TURMAS + 420 + 140;
	private var POSY_BT_CONVIDAR:Number = POSY_BT_INSCREVER;
	private var btCancelarInscricao:c_btCancelarInscricao;
	private var POSX_BT_CANCELAR_INSCRICAO:Number = POSX_SELECT_TURMAS;
	private var POSY_BT_CANCELAR_INSCRICAO:Number = POSY_SELECT_TURMAS + 115;
	private var btCancelarConvite:c_btCancelarConvite;
	private var POSX_BT_CANCELAR_CONVITE:Number = POSX_SELECT_TURMAS;
	private var POSY_BT_CANCELAR_CONVITE:Number = POSY_SELECT_TURMAS + 135 + 135 + 10;

//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		attachMovie("btInscrever", "btInscrever", getNextHighestDepth());
		btInscrever.inicializar();
		btInscrever._x = POSX_BT_INSCREVER;
		btInscrever._y = POSY_BT_INSCREVER;
		btInscrever.addEventListener("btInscreverPress", Delegate.create(this, inscrever));	
		btInscrever._visible = true;
		
		attachMovie("btConvidar", "btConvidar", getNextHighestDepth());
		btConvidar.inicializar();
		btConvidar._x = POSX_BT_CONVIDAR;
		btConvidar._y = POSY_BT_CONVIDAR;
		btConvidar.addEventListener("btConvidarPress", Delegate.create(this, convidar));	
		btConvidar._visible = true;
		
		attachMovie("btCancelarInscricao", "btCancelarInscricao", getNextHighestDepth());
		btCancelarInscricao.inicializar();
		btCancelarInscricao._x = POSX_BT_CANCELAR_INSCRICAO;
		btCancelarInscricao._y = POSY_BT_CANCELAR_INSCRICAO;
		btCancelarInscricao.addEventListener("btCancelarInscricaoPress", Delegate.create(this, cancelarInscricao));	
		btCancelarInscricao._visible = true;
		
		attachMovie("btCancelarConvite", "btCancelarConvite", getNextHighestDepth());
		btCancelarConvite.inicializar();
		btCancelarConvite._x = POSX_BT_CANCELAR_CONVITE;
		btCancelarConvite._y = POSY_BT_CANCELAR_CONVITE;
		btCancelarConvite.addEventListener("btCancelarConvitePress", Delegate.create(this, cancelarConvite));	
		btCancelarConvite._visible = true;
		
	}
	
	public function inicializarSelectTurmas(textoOpcoes_param:Array){	
		if(menu_select_turmas != null){//Se já foi inicializado.
			menu_select_turmas.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_turmas", getNextHighestDepth(), {_x:POSX_SELECT_TURMAS, _y:POSY_SELECT_TURMAS});
		menu_select_turmas.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Turmas Inscrito");
		menu_select_turmas._visible = true;
	}
	public function inicializarSelectTurmasConvidado(textoOpcoes_param:Array){	
		if(menu_select_turmas_convidado != null){//Se já foi inicializado.
			menu_select_turmas_convidado.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_turmas_convidado", getNextHighestDepth(), {_x:POSX_SELECT_TURMAS_CONVIDADO, _y:POSY_SELECT_TURMAS_CONVIDADO});	
		menu_select_turmas_convidado.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Turmas Convidado");
		menu_select_turmas_convidado._visible = true;
	}
	public function inicializarSelectTodasTurmas(textoOpcoes_param:Array){	
		if(menu_select_todas_turmas != null){//Se já foi inicializado.
			menu_select_todas_turmas.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_todas_turmas", getNextHighestDepth(), {_x:POSX_SELECT_TODAS_TURMAS, _y:POSY_SELECT_TODAS_TURMAS});	
		menu_select_todas_turmas.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT_TODAS_TURMAS, textoOpcoes_param, "Turmas no Colégio");
		menu_select_todas_turmas._visible = true;
	}
	
	//---- Editar Turmas
	private function inscrever():Void{
		var turmaInscricao:String = new String();
		if(menu_select_todas_turmas.haBotaoPressionado()){
			turmaInscricao = menu_select_todas_turmas.getOpcaoSelecionada();
			if(!menu_select_turmas.existeOpcao(turmaInscricao)){
				menu_select_turmas.inserirOpcao(turmaInscricao);
			}
		}
	}
	private function convidar():Void{
		var turmaConvite:String = new String();
		if(menu_select_todas_turmas.haBotaoPressionado()){
			turmaConvite = menu_select_todas_turmas.getOpcaoSelecionada();
			if(!menu_select_turmas_convidado.existeOpcao(turmaConvite)){
				menu_select_turmas_convidado.inserirOpcao(turmaConvite);
			}
		}
	}
	private function cancelarInscricao():Void{
		if(menu_select_turmas.haBotaoPressionado()){
			var turmaInscricaoCancelada:String = menu_select_turmas.getOpcaoSelecionada();
			menu_select_turmas.retirarOpcao(turmaInscricaoCancelada);
		}
	}
	private function cancelarConvite():Void{
		if(menu_select_turmas_convidado.haBotaoPressionado()){
			var turmaConviteCancelado:String = menu_select_turmas_convidado.getOpcaoSelecionada();
			menu_select_turmas_convidado.retirarOpcao(turmaConviteCancelado);
		}
	}
	
	//---- Getters
	public function getListaTurmasInscricao():Array{
		return menu_select_turmas.getListaOpcoes();
	}
	public function getListaTurmasConvite():Array{
		return menu_select_turmas_convidado.getListaOpcoes();
	}
	
	
	
	
	
}
