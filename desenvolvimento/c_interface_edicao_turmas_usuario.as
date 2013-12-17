import flash.geom.Point;
import mx.utils.Delegate;

/*
*
*/
class c_interface_edicao_turmas_usuario extends ac_interface_menu{
//dados	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_edicao_turmas_usuario";
	
	/*
	* Posição em que as interfaces filhas devem ser colocadas.
	*/
	private var POSICAO_INTERFACES:Point;
	
	/*
	* Interface para lidar com as turmas em que o usuário é professor.
	*/
	private var interface_turmas_professor:c_trocaTurmas;
	
	/*
	* Interface para lidar com as turmas em que o usuário é monitor.
	*/
	private var interface_turmas_monitor:c_trocaTurmas;
	
	/*
	* Interface para lidar com as turmas em que o usuário é aluno.
	*/
	private var interface_turmas_aluno:c_trocaTurmas;
	
	/*
	* Botões para alternar entre as três interfaces filhas.
	*/
	private var btEditarTurmasProfessor:c_btGrande;
	private var btEditarTurmasMonitor:c_btGrande;
	private var btEditarTurmasAluno:c_btGrande;
	private var POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE:Point;
	private static var ESPACO_ENTRE_BOTOES:Number = 9.7;
	
//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		POSICAO_INTERFACES = new Point(0,0);
		POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE = new Point(0,280);
		
		//Botões
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarTurmasProfessor_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarTurmasProfessor = this['btEditarTurmasProfessor_dummy'];
		btEditarTurmasProfessor.inicializar("Professor");
		btEditarTurmasProfessor.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarTurmasMonitor_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarTurmasMonitor = this['btEditarTurmasMonitor_dummy'];
		btEditarTurmasMonitor.inicializar("Monitor");
		btEditarTurmasMonitor.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarTurmasAluno_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarTurmasAluno = this['btEditarTurmasAluno_dummy'];
		btEditarTurmasAluno.inicializar("Aluno");
		btEditarTurmasAluno.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		//Interfaces
		attachMovie(c_trocaTurmas.LINK_BIBLIOTECA, "interface_turmas_professor_dummy", getNextHighestDepth(), {_x:POSICAO_INTERFACES.x, _y:POSICAO_INTERFACES.y});
		interface_turmas_professor = this['interface_turmas_professor_dummy'];
		interface_turmas_professor.inicializar(new Array(), new Array(), new Array(), "Professor");
		
		attachMovie(c_trocaTurmas.LINK_BIBLIOTECA, "interface_turmas_monitor_dummy", getNextHighestDepth(), {_x:POSICAO_INTERFACES.x, _y:POSICAO_INTERFACES.y});
		interface_turmas_monitor = this['interface_turmas_monitor_dummy'];
		interface_turmas_monitor.inicializar(new Array(), new Array(), new Array(), "Monitor");
		
		attachMovie(c_trocaTurmas.LINK_BIBLIOTECA, "interface_turmas_aluno_dummy", getNextHighestDepth(), {_x:POSICAO_INTERFACES.x, _y:POSICAO_INTERFACES.y});
		interface_turmas_aluno = this['interface_turmas_aluno_dummy'];
		interface_turmas_aluno.inicializar(new Array(), new Array(), new Array(), "Aluno");
		
		var trocaInterface:Object = new Object();
		trocaInterface.nome = btEditarTurmasProfessor._name;
		trocarInterface(trocaInterface);
	}
	
	/*
	* Mostra esta interface, garantido que alguma de suas subinterfaces (professor, aluno ou monitor) estará aberta.
	*/
	public function mostrar(){
		if(interface_turmas_professor._visible == false
		   and interface_turmas_monitor._visible == false
		   and interface_turmas_aluno._visible == false){
			var trocaInterface:Object = new Object();
			trocaInterface.nome = btEditarTurmasProfessor._name;
			trocarInterface(trocaInterface);
		} else {
			switch(true){
				case interface_turmas_professor._visible: interface_turmas_professor.abrirInterface();
					break;
				case interface_turmas_monitor._visible: interface_turmas_monitor.abrirInterface();
					break;
				case interface_turmas_aluno._visible: interface_turmas_aluno.abrirInterface();
					break;
			}
		}
		_visible = true;
	}
	
	/*
	* Esta interface dá acesso a 3 outras via botões. São elas:
	*	- Edição de turmas em que é professor
	*	- Edição de turmas em que é monitor
	*	- Edição de turmas em que é aluno
	* @param evento_botao_param O evento que gerou a chamada a esta função, com o nome do botão no atributo nome.
	*/
	private function trocarInterface(evento_botao_param:Object):Void{
		btEditarTurmasProfessor._visible = true;
		btEditarTurmasMonitor._visible = true;
		btEditarTurmasAluno._visible = true;

		interface_turmas_professor.fecharInterface();
		interface_turmas_monitor.fecharInterface();
		interface_turmas_aluno.fecharInterface();
		
		switch(evento_botao_param.nome){
			case btEditarTurmasProfessor._name: btEditarTurmasProfessor._visible = false;
									  			btEditarTurmasMonitor._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									  			btEditarTurmasAluno._x = btEditarTurmasMonitor._x+btEditarTurmasMonitor._width+ESPACO_ENTRE_BOTOES;
									  			interface_turmas_professor.abrirInterface();
				break;
			case btEditarTurmasMonitor._name: btEditarTurmasMonitor._visible = false;
											  btEditarTurmasProfessor._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									  		  btEditarTurmasAluno._x = btEditarTurmasProfessor._x+btEditarTurmasProfessor._width+ESPACO_ENTRE_BOTOES;
									  		  interface_turmas_monitor.abrirInterface();
				break;
			case btEditarTurmasAluno._name: btEditarTurmasAluno._visible = false;
										    btEditarTurmasProfessor._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									  	    btEditarTurmasMonitor._x = btEditarTurmasProfessor._x+btEditarTurmasProfessor._width+ESPACO_ENTRE_BOTOES;
									  	    interface_turmas_aluno.abrirInterface();
				break;
		}
	}
	
	/*
	* Preenche os campos com os dados da conta passada.
	* @param conta_param Conta da qual serão tirados os nomes de turmas.
	*/
	public function definirTurmas(conta_param:c_conta):Void{
		interface_turmas_professor.setTurmasInscrito(conta_param.turmasProfessor, "Professor");
		interface_turmas_professor.setTurmasConvidado(conta_param.turmasConvidadoProfessor, "Professor");
		interface_turmas_professor.setTurmasHabilitado(conta_param.turmasHabilitadoProfessor, "Professor");
		
		interface_turmas_monitor.setTurmasInscrito(conta_param.turmasMonitor, "Monitor");
		interface_turmas_monitor.setTurmasConvidado(conta_param.turmasConvidadoMonitor, "Monitor");
		interface_turmas_monitor.setTurmasHabilitado(conta_param.turmasHabilitadoMonitor, "Monitor");
		
		interface_turmas_aluno.setTurmasInscrito(conta_param.turmasAluno, "Aluno");
		interface_turmas_aluno.setTurmasConvidado(conta_param.turmasConvidadoAluno, "Aluno");
		interface_turmas_aluno.setTurmasHabilitado(conta_param.turmasHabilitadoAluno, "Aluno");
		
	}
	
	
	/*
	* @return Conta com os dados nos campos desta interface.
	*/
	public function getDadosPreenchidos():c_conta{
		var conta_com_dados:c_conta = new c_conta();
		
		conta_com_dados.turmasProfessor = interface_turmas_professor.getTurmasInscrito();
		conta_com_dados.turmasConvidadoProfessor = interface_turmas_professor.getTurmasConvidado();
		
		conta_com_dados.turmasMonitor = interface_turmas_monitor.getTurmasInscrito();
		conta_com_dados.turmasConvidadoMonitor = interface_turmas_monitor.getTurmasConvidado();
		
		conta_com_dados.turmasAluno = interface_turmas_aluno.getTurmasInscrito();
		conta_com_dados.turmasConvidadoAluno = interface_turmas_aluno.getTurmasConvidado();
		
		return conta_com_dados;
	}
	
	
	public function esconder_label():Void{
		interface_turmas_professor.esconder_label();
		interface_turmas_monitor.esconder_label();
		interface_turmas_aluno.esconder_label();
	}
}