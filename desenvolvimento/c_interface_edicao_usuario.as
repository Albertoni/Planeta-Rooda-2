import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;
import flash.geom.Point;

class c_interface_edicao_usuario extends ac_interface_edicao {
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_edicao_usuario";

	/*
	* Indica se esta interface permite editar outros usuários.
	*/
	private var permite_editarOutrosUsuarios:Boolean = false;

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Interfaces
	private var interfaceAtiva:MovieClip;

	private var interfaceEdicaoOutrosUsuarios:c_interface_pesquisa_edicao_conta;
	private var interfaceEdicaoPropriosDados:c_interface_edicao_proprios_dados;
	private var interfaceEdicaoTurmas:c_interface_edicao_turmas_usuario;
	private var POSICAO_INTERFACES:Point;
	
	/*
	* Dados de pesquisa (vindos do banco de dados e inseridos nos campos) e de edição (inseridos pelo usuário).
	*/
	private var conta_pesquisa:c_conta;
	private var conta_edicao:c_conta;
	
	/*
	* Botões para alternar entre as duas interfaces:
	*	- de edição de dados do usuário
	*	- de edição das turmas do usuário
	*/
	private var POSICAO_BOTOES_TROCA_INTERFACE:Point;
	private var btEditarDados:c_btGrande;
	private var btEditarTurmas:c_btGrande;
	private var btEditarOutrosUsuarios:c_btGrande;
	
	//---- Botões
	private var POSX_BT_SALVAR:Number = 0;
	private var POSY_BT_SALVAR:Number = 375;
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		btDeletar._visible = false;
		btSalvar._x = POSX_BT_SALVAR;
		btSalvar._y = POSY_BT_SALVAR;
		
		permite_editarOutrosUsuarios = false;
		
		conta_pesquisa = new c_conta();
		conta_edicao = new c_conta();

		POSICAO_INTERFACES = new Point(0,0);
		attachMovie(c_interface_edicao_turmas_usuario.LINK_BIBLIOTECA, "interfaceEdicaoTurmas_dummy", getNextHighestDepth(), {_x:POSICAO_INTERFACES.x, _y:POSICAO_INTERFACES.y});
		interfaceEdicaoTurmas = this['interfaceEdicaoTurmas_dummy'];
		interfaceEdicaoTurmas.inicializar();
		
		attachMovie(c_interface_edicao_proprios_dados.LINK_BIBLIOTECA, "interfaceEdicaoPropriosDados_dummy", getNextHighestDepth(), {_x:POSICAO_INTERFACES.x, _y:POSICAO_INTERFACES.y});
		interfaceEdicaoPropriosDados = this['interfaceEdicaoPropriosDados_dummy'];
		interfaceEdicaoPropriosDados.inicializar();
		
		attachMovie(c_interface_pesquisa_edicao_conta.LINK_BIBLIOTECA, "interfaceEdicaoOutrosUsuarios_dummy", getNextHighestDepth(), {_x:POSICAO_INTERFACES.x, _y:POSICAO_INTERFACES.y});
		interfaceEdicaoOutrosUsuarios = this['interfaceEdicaoOutrosUsuarios_dummy'];
		interfaceEdicaoOutrosUsuarios.inicializar();
		
		POSICAO_BOTOES_TROCA_INTERFACE = new Point(140, POSY_BT_SALVAR);
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarDados_dummy", getNextHighestDepth(), {_x:POSICAO_BOTOES_TROCA_INTERFACE.x, _y:POSICAO_BOTOES_TROCA_INTERFACE.y});
		btEditarDados = this['btEditarDados_dummy'];
		btEditarDados.inicializar("Editar Meus Dados");
		btEditarDados.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarTurmas_dummy", getNextHighestDepth(), {_x:POSICAO_BOTOES_TROCA_INTERFACE.x, _y:POSICAO_BOTOES_TROCA_INTERFACE.y});
		btEditarTurmas = this['btEditarTurmas_dummy'];
		btEditarTurmas.inicializar("Editar Minhas Turmas");
		btEditarTurmas.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarOutrosUsuarios_dummy", getNextHighestDepth(), {_x:POSICAO_BOTOES_TROCA_INTERFACE.x, _y:POSICAO_BOTOES_TROCA_INTERFACE.y});
		btEditarOutrosUsuarios = this['btEditarOutrosUsuarios_dummy'];
		btEditarOutrosUsuarios.inicializar("Editar Outros Usuários");
		btEditarOutrosUsuarios.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		btEditarOutrosUsuarios._visible = false;

		var trocaInterface:Object = new Object();
		trocaInterface.nome = btEditarDados._name;
		trocarInterface(trocaInterface);
		
		endereco_arquivo_pesquisa_php = "phps_do_menu/pesquisa_conta_usuario.php";
		endereco_arquivo_gravacao_php = "phps_do_menu/edicao_contas.php";
	}
	
	//---- Interface
	public function mostrar():Void{
		pesquisaNoBD();
		_visible = true;
	}
	
	/*
	* Esta interface dá acesso a 2 outras via botões. São elas:
	*	- Edição de dados do usuário
	*	- Edição de turmas do usuário
	* @param evento_botao_param O evento que gerou a chamada a esta função, com o nome do botão no atributo nome.
	*/
	private function trocarInterface(evento_botao_param:Object):Void{
		btSalvar._visible = true;
		btEditarDados._visible = true;
		btEditarTurmas._visible = true;
		if(permite_editarOutrosUsuarios){
			btEditarOutrosUsuarios._visible = true;
		}

		interfaceEdicaoPropriosDados.esconder();
		interfaceEdicaoTurmas._visible = false;
		interfaceEdicaoOutrosUsuarios.esconder();
		switch(evento_botao_param.nome){
			case btEditarDados._name: btEditarDados._visible = false;
									  interfaceEdicaoPropriosDados.mostrar();
									  btEditarTurmas._x = POSICAO_BOTOES_TROCA_INTERFACE.x;
									  btEditarOutrosUsuarios._x = btEditarTurmas._x+btEditarTurmas._width+15;
				break;
			case btEditarTurmas._name: btEditarTurmas._visible = false;
									   interfaceEdicaoTurmas.mostrar();
									   btEditarDados._x = POSICAO_BOTOES_TROCA_INTERFACE.x;
									   btEditarOutrosUsuarios._x = btEditarDados._x+btEditarDados._width+15;
				break;
			case btEditarOutrosUsuarios._name: btEditarOutrosUsuarios._visible = false;
									   		   interfaceEdicaoOutrosUsuarios.mostrar();
											   btEditarDados._x = btSalvar._x;
											   btEditarTurmas._x = btEditarDados._x+btEditarDados._width+15;
											   btSalvar._visible = false;
				break;
		}
	}
	
	//---- Interface
	private function restringirCamposDeTexto():Void{}
	private function armazenarDadosEditados():Void{
		var conta_turmas:c_conta = new c_conta();
		conta_turmas = interfaceEdicaoTurmas.getDadosPreenchidos();
		conta_edicao = interfaceEdicaoPropriosDados.getContaEdicao();
		conta_edicao.identificacao = conta_pesquisa.identificacao;
		conta_edicao.turmasProfessor = conta_turmas.turmasProfessor;
		conta_edicao.turmasConvidadoProfessor = conta_turmas.turmasConvidadoProfessor;
		conta_edicao.turmasMonitor = conta_turmas.turmasMonitor;
		conta_edicao.turmasConvidadoMonitor = conta_turmas.turmasConvidadoMonitor;
		conta_edicao.turmasAluno = conta_turmas.turmasAluno;
		conta_edicao.turmasConvidadoAluno = conta_turmas.turmasConvidadoAluno;
	}
	private function preencherCampos(dados_param:ac_dados):Void{
		interfaceEdicaoPropriosDados.preencherCampos(dados_param.conta);
		interfaceEdicaoTurmas.definirTurmas(conta_pesquisa);
	}
	
	//---- Servidor
	private function getDadosPesquisa():ac_dados{
		var objDados:ac_dados = new ac_dados();
		objDados.conta = conta_pesquisa;
		return objDados;
	}
	private function criaEnviaPesquisa():Void{
		envia.dado_pesquisado = _root.usuario_status.identificacao;
	}
	private function criarEnvia():Void{
		envia.identificacao = conta_edicao.identificacao;

		envia.nome = conta_edicao.nome;
		envia.apelido = conta_edicao.apelido;
		envia.login = conta_edicao.login;
		envia.diaAniversario = conta_edicao.diaAniversario;
		envia.mesAniversario = conta_edicao.mesAniversario;
		envia.anoAniversario = conta_edicao.anoAniversario;
		envia.nivel = conta_edicao.nivel;
		envia.senha = conta_edicao.senha;
		envia.email = conta_edicao.email;
		envia.turmasProfessor = conta_edicao.turmasProfessor.toString();
		envia.turmasProfessorConvidado = conta_edicao.turmasConvidadoProfessor.toString();
		envia.turmasMonitor = conta_edicao.turmasMonitor.toString();
		envia.turmasMonitorConvidado = conta_edicao.turmasConvidadoMonitor.toString();
		envia.turmasAluno = conta_edicao.turmasAluno.toString();
		envia.turmasAlunoConvidado = conta_edicao.turmasConvidadoAluno.toString();
	}
	
	//---- Dados
	public function validarDados():Boolean{
		return conta_edicao.validar();
	}
	public function getErroValidacao():String{
		return conta_edicao.getMensagemErro();
	}
	
	public function armazenarDadosPesquisa():Void{
		mensagemErroPesquisa = recebe.mensagemDeErro;
		
		conta_pesquisa.identificacao = recebe.usuario_id;
		conta_pesquisa.login = recebe.usuario_login;
		conta_pesquisa.senha = recebe.usuario_senha;
		conta_pesquisa.diaAniversario = recebe.usuario_dia_aniversario;
		conta_pesquisa.mesAniversario = recebe.usuario_mes_aniversario;
		conta_pesquisa.anoAniversario = recebe.usuario_ano_aniversario;
		conta_pesquisa.nome = recebe.usuario_nome;
		conta_pesquisa.nomeMae = recebe.usuario_nome_mae;
		conta_pesquisa.email = recebe.usuario_email;
		conta_pesquisa.nivel = recebe.usuario_nivel;
		conta_pesquisa.apelido = recebe.usuario_apelido;
		
		conta_pesquisa.turmasProfessor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_professor; i++){
			conta_pesquisa.turmasProfessor.push(recebe["turmasProfessor"+i]);
		}
		conta_pesquisa.turmasConvidadoProfessor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_convidado_professor; i++){
			conta_pesquisa.turmasConvidadoProfessor.push(recebe["turmasConvidadoProfessor"+i]);
		}
		conta_pesquisa.turmasHabilitadoProfessor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_habilitado_professor; i++){
			conta_pesquisa.turmasHabilitadoProfessor.push(recebe["turmasHabilitadoProfessor"+i]);
		}
		
		conta_pesquisa.turmasMonitor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_monitor; i++){
			conta_pesquisa.turmasMonitor.push(recebe["turmasMonitor"+i]);
		}
		conta_pesquisa.turmasConvidadoMonitor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_convidado_monitor; i++){
			conta_pesquisa.turmasConvidadoMonitor.push(recebe["turmasConvidadoMonitor"+i]);
		}
		conta_pesquisa.turmasHabilitadoMonitor = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_habilitado_monitor; i++){
			conta_pesquisa.turmasHabilitadoMonitor.push(recebe["turmasHabilitadoMonitor"+i]);
		}
		
		conta_pesquisa.turmasAluno = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_aluno; i++){
			conta_pesquisa.turmasAluno.push(recebe["turmasAluno"+i]);
		}
		conta_pesquisa.turmasConvidadoAluno = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_convidado_aluno; i++){
			conta_pesquisa.turmasConvidadoAluno.push(recebe["turmasConvidadoAluno"+i]);
		}
		conta_pesquisa.turmasHabilitadoAluno = new Array();
		for(var i:Number = 0; i<recebe.num_turmas_habilitado_aluno; i++){
			conta_pesquisa.turmasHabilitadoAluno.push(recebe["turmasHabilitadoAluno"+i]);
		}
	}
	
	/*
	* Dá a esta interface o poder de abrir uma interface de pesquisa e edição de quaisquer usuários.
	*/
	public function permitirEdicaoTodosUsuarios(){
		permite_editarOutrosUsuarios = true;
		btEditarOutrosUsuarios._visible = true;
	}
	
	
	
}