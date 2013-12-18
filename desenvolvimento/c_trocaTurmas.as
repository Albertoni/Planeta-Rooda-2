import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_trocaTurmas extends MovieClip {
//dados		
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "troca_turmas";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	

	//---- Select
	private var NUMERO_OPCOES_VISIVEIS_SELECT:Number = 5;
	
	private var menu_select_turmas:c_select = null;
	private var POSX_SELECT_TURMAS:Number = 0;
	private var POSY_SELECT_TURMAS:Number = 0 + 45 + 5;
	
	private var menu_select_turmas_convidado:c_select = null;
	private var POSX_SELECT_TURMAS_CONVIDADO:Number = POSX_SELECT_TURMAS;
	private var POSY_SELECT_TURMAS_CONVIDADO:Number = POSY_SELECT_TURMAS + 135 + 5;
	
	private var menu_select_todas_turmas:c_select = null;
	private var NUMERO_OPCOES_VISIVEIS_SELECT_TODAS_TURMAS:Number = 5;
	private var POSX_SELECT_TODAS_TURMAS:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_SELECT_TODAS_TURMAS:Number = POSY_SELECT_TURMAS;
	
	//---- Botões
	private var abreTrocaTurmas:c_btAbreTrocaTurmas;
	private var POSX_BT_ABRE_TROCA_TURMAS:Number = 0;
	private var POSY_BT_ABRE_TROCA_TURMAS:Number = 0;
	
	private var btInscrever:c_btInscrever;
	private var POSX_BT_INSCREVER:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_BT_INSCREVER:Number = POSY_SELECT_TURMAS + 135 + 5;
	
	private var btCancelarInscricao:c_btCancelarInscricao;
	private var POSX_BT_CANCELAR_INSCRICAO:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_BT_CANCELAR_INSCRICAO:Number = POSY_SELECT_TURMAS + 135 + 5;
	
	private var btCancelarConvite:c_btCancelarConvite;
	private var POSX_BT_CANCELAR_CONVITE:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_BT_CANCELAR_CONVITE:Number = POSY_SELECT_TURMAS + 135 + 5;
	
	private var btAceitarConvite:c_btAceitarConvite;
	private var POSX_BT_ACEITAR_CONVITE:Number = POSX_SELECT_TURMAS + 420;
	private var POSY_BT_ACEITAR_CONVITE:Number = POSY_SELECT_TURMAS + 135 + 5 + 50;
	
//métodos
	/*
	* Inicializa esta interface, preparando-a para ser usada.
	* @param turmas_em_que_estah_param Array de turmas em que o usuário está.
	*								   Deve ser um array de Strings, em que cada elemento será o nome de uma turma.
	* @param turmas_foi_convidado_param Array de turmas para as quais o usuário foi convidado.
	*								    Deve ser um array de Strings, em que cada elemento será o nome de uma turma.
	* @param turmas_habilitado_param Array de turmas nas quais o usuário pode se inscrever.
	*						   		 Deve ser um array de Strings, em que cada elemento será o nome de uma turma.
	* @param nome_nivel_param Nome do nível (na hierarquia) que esta interface edita, para inserção nos labels.
	*/
	public function inicializar(turmas_em_que_estah_param:Array, turmas_foi_convidado_param:Array, turmas_habilitado_param:Array, nome_nivel_param:String) {
		mx.events.EventDispatcher.initialize(this);

		/*Listeners*/
		attachMovie("abre_troca_turmas", "abreTrocaTurmas", getNextHighestDepth());
		abreTrocaTurmas.inicializar();
		abreTrocaTurmas._x = POSX_BT_ABRE_TROCA_TURMAS;
		abreTrocaTurmas._y = POSY_BT_ABRE_TROCA_TURMAS;
		abreTrocaTurmas.addEventListener("btAbreTrocaTurmasPress", Delegate.create(this, abrir));	
		
		attachMovie("btInscrever", "btInscrever", getNextHighestDepth());
		btInscrever.inicializar();
		btInscrever._x = POSX_BT_INSCREVER;
		btInscrever._y = POSY_BT_INSCREVER;
		btInscrever.addEventListener("btInscreverPress", Delegate.create(this, inscrever));	
		btInscrever._visible = false;
		
		attachMovie("btCancelarInscricao", "btCancelarInscricao", getNextHighestDepth());
		btCancelarInscricao.inicializar();
		btCancelarInscricao._x = POSX_BT_CANCELAR_INSCRICAO;
		btCancelarInscricao._y = POSY_BT_CANCELAR_INSCRICAO;
		btCancelarInscricao.addEventListener("btCancelarInscricaoPress", Delegate.create(this, cancelarInscricao));	
		btCancelarInscricao._visible = false;
		
		attachMovie("btCancelarConvite", "btCancelarConvite", getNextHighestDepth());
		btCancelarConvite.inicializar();
		btCancelarConvite._x = POSX_BT_CANCELAR_CONVITE;
		btCancelarConvite._y = POSY_BT_CANCELAR_CONVITE;
		btCancelarConvite.addEventListener("btCancelarConvitePress", Delegate.create(this, cancelarConvite));	
		btCancelarConvite._visible = false;
		
		attachMovie("btAceitarConvite", "btAceitarConvite", getNextHighestDepth());
		btAceitarConvite.inicializar();
		btAceitarConvite._x = POSX_BT_ACEITAR_CONVITE;
		btAceitarConvite._y = POSY_BT_ACEITAR_CONVITE;
		btAceitarConvite.addEventListener("btAceitarConvitePress", Delegate.create(this, aceitarConvite));	
		btAceitarConvite._visible = false;
		
		setTurmasInscrito(turmas_em_que_estah_param, nome_nivel_param);
		setTurmasConvidado(turmas_foi_convidado_param, nome_nivel_param);
		setTurmasHabilitado(turmas_habilitado_param, nome_nivel_param);
		
		esconder_label();
	}
	
	//---- Inteface
	public function abrirInterface(){
		menu_select_turmas._visible = true;
		menu_select_turmas_convidado._visible = true;
		menu_select_todas_turmas._visible = true;
		_visible = true;
	}
	public function fecharInterface(){
		_visible = false;
		btInscrever._visible = false;
		btCancelarInscricao._visible = false;
		btCancelarConvite._visible = false;
		btAceitarConvite._visible = false;
		menu_select_turmas._visible = false;
		menu_select_turmas_convidado._visible = false;
		menu_select_todas_turmas._visible = false;
	}
	public function exibirOpcoesTurmas():Void{
		btInscrever._visible = false;
		btCancelarInscricao._visible = true;
		btCancelarConvite._visible = false;
		btAceitarConvite._visible = false;
	}
	public function exibirOpcoesTurmasConvidado():Void{
		btInscrever._visible = false;
		btCancelarInscricao._visible = false;
		btCancelarConvite._visible = true;
		btAceitarConvite._visible = true;
	}
	public function exibirOpcoesTodasTurmas():Void{
		btInscrever._visible = true;
		btCancelarInscricao._visible = false;
		btCancelarConvite._visible = false;
		btAceitarConvite._visible = false;
	}
	
	//---- Teclado
	private function abrir(){
		dispatchEvent({target:this, type:"abrirTrocaTurmas"});
	}

	//---- Getters
	public function getTurmasInscrito():Array{
		return menu_select_turmas.getListaOpcoes();
	}
	public function getTurmasConvidado():Array{
		return menu_select_turmas_convidado.getListaOpcoes();
	}
	
	//---- Setters
	/*
	* Para os setters à seguir:
	*	O Array é constituído de String, que são nomes de turmas.
	*	A String é um nome que aparecerá no rótulo do select.
	*/
	public function setTurmasInscrito(turmas_inscrito:Array, nome_nivel_param:String):Void{
		if(menu_select_turmas != null){//Se já foi inicializado.
			menu_select_turmas.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_turmas", getNextHighestDepth(), {_x:POSX_SELECT_TURMAS, _y:POSY_SELECT_TURMAS});
		menu_select_turmas.addEventListener("botaoPressionado", Delegate.create(this, exibirOpcoesTurmas));	
		menu_select_turmas.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, turmas_inscrito, "Turmas Inscrito como "+nome_nivel_param);
		menu_select_turmas._visible = false;
	}
	public function setTurmasConvidado(turmas_convidado:Array, nome_nivel_param:String):Void{
		if(menu_select_turmas_convidado != null){//Se já foi inicializado.
			menu_select_turmas_convidado.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_turmas_convidado", getNextHighestDepth(), {_x:POSX_SELECT_TURMAS_CONVIDADO, _y:POSY_SELECT_TURMAS_CONVIDADO});	
		menu_select_turmas_convidado.addEventListener("botaoPressionado", Delegate.create(this, exibirOpcoesTurmasConvidado));	
		menu_select_turmas_convidado.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, turmas_convidado, "Turmas Convidado como "+nome_nivel_param);
		menu_select_turmas_convidado._visible = false;
	}
	public function setTurmasHabilitado(turmas_habilitado:Array, nome_nivel_param:String):Void{
		if(menu_select_todas_turmas != null){//Se já foi inicializado.
			menu_select_todas_turmas.removeMovieClip();
		}
		attachMovie("select_mc", "menu_select_todas_turmas", getNextHighestDepth(), {_x:POSX_SELECT_TODAS_TURMAS, _y:POSY_SELECT_TODAS_TURMAS});	
		menu_select_todas_turmas.addEventListener("botaoPressionado", Delegate.create(this, exibirOpcoesTodasTurmas));	
		menu_select_todas_turmas.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT_TODAS_TURMAS, turmas_habilitado, "Turmas em que pode ser "+nome_nivel_param);
		menu_select_todas_turmas._visible = false;
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
	private function aceitarConvite():Void{
		if(menu_select_turmas_convidado.haBotaoPressionado()){
			var turmaConviteAceito:String = menu_select_turmas_convidado.getOpcaoSelecionada();
			menu_select_turmas_convidado.retirarOpcao(turmaConviteAceito);
			menu_select_turmas.inserirOpcao(turmaConviteAceito);
		}
	}
	
	public function esconder_label():Void{
		if(abreTrocaTurmas._visible){
			abreTrocaTurmas._visible = false;
			_y -= abreTrocaTurmas._height + abreTrocaTurmas._y;
		}
	}
}
