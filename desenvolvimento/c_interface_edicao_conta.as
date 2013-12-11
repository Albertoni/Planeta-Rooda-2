import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;
class c_interface_edicao_conta extends ac_interface_edicao{
//dados	
	//---- Edição
	private var conta_pesquisa:c_conta;
	private var conta_edicao:c_conta;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	private var btEditarTurmas:c_btEditarTurmasUsuario;
	private var POSX_BT_EDITAR_TURMAS:Number = 3*140;
	private var POSY_BT_EDITAR_TURMAS:Number = 325;
	private var btEditarConta:c_btEditarConta;
	private var POSX_BT_EDITAR_CONTA:Number = POSX_BT_EDITAR_TURMAS;
	private var POSY_BT_EDITAR_CONTA:Number = POSY_BT_EDITAR_TURMAS;
	
	//---- Subinterfaces
	private var interfaceEdicaoTurmas:c_interface_edicao_turmas_usuario;
	private var POSX_INTERFACE_EDICAO_TURMAS:Number = 0;
	private var POSY_INTERFACE_EDICAO_TURMAS:Number = 0;
	private var interfaceEdicaoDados:c_interface_edicao_dados_usuario;
	private var POSX_INTERFACE_EDICAO_DADOS:Number = 0;
	private var POSY_INTERFACE_EDICAO_DADOS:Number = 0;

//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();

		conta_pesquisa = new c_conta();
		conta_edicao = new c_conta();
		
		attachMovie("btEditarTurmasUsuario", "btEditarTurmas", getNextHighestDepth());
		btEditarTurmas.inicializar();
		btEditarTurmas._x = POSX_BT_EDITAR_TURMAS;
		btEditarTurmas._y = POSY_BT_EDITAR_TURMAS;
		btEditarTurmas.addEventListener("btEditarTurmasUsuarioPress", Delegate.create(this, abrirInterfaceEditarTurmas));	
		btEditarTurmas._visible = true;
		
		attachMovie("btEditarConta", "btEditarConta", getNextHighestDepth());
		btEditarConta.inicializar();
		btEditarConta._x = POSX_BT_EDITAR_CONTA;
		btEditarConta._y = POSY_BT_EDITAR_CONTA;
		btEditarConta.addEventListener("btEditarContaPress", Delegate.create(this, abrirInterfacePrincipal));	
		btEditarConta._visible = false;
		
		attachMovie(c_interface_edicao_turmas_usuario.LINK_BIBLIOTECA, "interfaceEdicaoTurmas_dummy", getNextHighestDepth());
		interfaceEdicaoTurmas = this['interfaceEdicaoTurmas_dummy'];
		interfaceEdicaoTurmas.inicializar();
		interfaceEdicaoTurmas._x = POSX_INTERFACE_EDICAO_TURMAS;
		interfaceEdicaoTurmas._y = POSY_INTERFACE_EDICAO_TURMAS;
		interfaceEdicaoTurmas._visible = false;
		interfaceEdicaoTurmas.esconder_label();
		
		attachMovie("interface_edicao_dados_usuario", "interfaceEdicaoDados", getNextHighestDepth());
		interfaceEdicaoDados.inicializar();
		interfaceEdicaoDados._x = POSX_INTERFACE_EDICAO_DADOS;
		interfaceEdicaoDados._y = POSY_INTERFACE_EDICAO_DADOS;
		interfaceEdicaoDados._visible = true;
		
		endereco_arquivo_gravacao_php = "phps_do_menu/edicao_contas.php";
		endereco_arquivo_delecao_php = "phps_do_menu/deletar_conta.php";
		
		switch(_root.usuario_status.getPermissao()){
			case c_conta.getNivelVisitante(): 
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(true);
				break;
	
			case c_conta.getNivelAluno(): 
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(false);
				break;
	
			case c_conta.getNivelMonitor(): 
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(false);
				break;
	
			case c_conta.getNivelProfessor(): 
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(true);
				break;	

			case c_conta.getNivelCoordenador():
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(true);
				break;
	
			case c_conta.getNivelAdministrador(): 
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(true);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(true);
				break;
	
			default: 
				interfaceEdicaoDados.setPossibilidadeModificarParaAdmin(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaCoordenador(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaProfessor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaMonitor(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaAluno(false);
				interfaceEdicaoDados.setPossibilidadeModificarParaVisitante(false);
				break;
		}
		
	}
	
	//---- Interface
	public function mostrar():Void{
		abrirInterfacePrincipal();
		_visible = true;
	}
	private function abrirInterfacePrincipal():Void{
		btEditarConta._visible = false;
		btEditarTurmas._visible = true;
		interfaceEdicaoDados._visible = true;
		interfaceEdicaoTurmas._visible = false;
		preencherCamposEdicaoConta();
	}
	private function abrirInterfaceEditarTurmas():Void{
		btEditarConta._visible = true;  
		btEditarTurmas._visible = false;
		interfaceEdicaoDados._visible = false;
		interfaceEdicaoTurmas.mostrar();
		armazenarDadosEditados();
	}
	public function restringirCamposDeTexto():Void{
		interfaceEdicaoDados.restringirCamposDeTexto();
	}
	public function armazenarDadosEditados():Void{
		var conta_turmas:c_conta;
		conta_edicao = interfaceEdicaoDados.armazenarDadosEditados();
		conta_turmas = interfaceEdicaoTurmas.getDadosPreenchidos();
		conta_edicao.turmasProfessor = conta_turmas.turmasProfessor;
		conta_edicao.turmasConvidadoProfessor = conta_turmas.turmasConvidadoProfessor;
		conta_edicao.turmasMonitor = conta_turmas.turmasMonitor;
		conta_edicao.turmasConvidadoMonitor = conta_turmas.turmasConvidadoMonitor;
		conta_edicao.turmasAluno = conta_turmas.turmasAluno;
		conta_edicao.turmasConvidadoAluno = conta_turmas.turmasConvidadoAluno;
	}
	public function preencherCampos(dados_param:ac_dados):Void{
		conta_pesquisa = dados_param.conta;
		interfaceEdicaoDados.preencherCampos(dados_param);
	}
	public function preencherCamposEdicaoConta():Void{		
		interfaceEdicaoTurmas.definirTurmas(conta_pesquisa);
		interfaceEdicaoDados.preencherCamposEdicaoConta();
	}
	
	//---- Servidor
	public function criarEnvia():Void{
		armazenarDadosEditados();
		envia.identificacao   = conta_edicao.identificacao; //Necessário para encontrar a tupla.
		envia.login           = conta_edicao.login;
		envia.senha           = conta_edicao.senha;
		envia.diaAniversario  = conta_edicao.diaAniversario;
		envia.mesAniversario  = conta_edicao.mesAniversario;
		envia.anoAniversario  = conta_edicao.anoAniversario;
		envia.nome   	      = conta_edicao.nome;
		envia.nomeMae  	      = conta_edicao.nomeMae;
		envia.email    	      = conta_edicao.email;
		envia.nivel           = conta_edicao.nivel;
		envia.apelido         = conta_edicao.apelido;
		envia.turmasProfessor = conta_edicao.turmasProfessor.toString();
		envia.turmasProfessorConvidado = conta_edicao.turmasConvidadoProfessor.toString();
		envia.turmasMonitor = conta_edicao.turmasMonitor.toString();
		envia.turmasMonitorConvidado = conta_edicao.turmasConvidadoMonitor.toString();
		envia.turmasAluno = conta_edicao.turmasAluno.toString();
		envia.turmasAlunoConvidado = conta_edicao.turmasConvidadoAluno.toString();
	}
	
	//---- Dados
	public function validarDados():Boolean{
		armazenarDadosEditados();
		return conta_edicao.validar();
	}
	public function getErroValidacao():String{
		return conta_edicao.getMensagemErro();
	}
	

}