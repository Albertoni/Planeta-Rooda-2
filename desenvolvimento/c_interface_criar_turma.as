import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

class c_interface_criar_turma extends ac_interface_menu_cadastro {
//dados	
	//---- Dados
	private var turma_cadastro:c_turma;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	private var botaoCriarTurma:c_btCadastrarTurma = null;
	private var POSX_BOTAO_CRIAR_TURMA:Number = 0;
	private var POSY_BOTAO_CRIAR_TURMA:Number = 480;
	
	//---- Selects
	private var NUMERO_OPCOES_VISIVEIS_SELECT:Number = 5;
	
	/*
	* Dropdown de seleção do ano da turma.
	*/
	private var menu_dropdown_anos:c_dropdown;
	private static var POSICAO_DROPDOWN_ANOS:Point = new Point(421.35, 105);//(8.1, 300);
	private static var NOME_DROPDOWN_ANOS:String = "menu_dropdown_anos";
	
	/*
	* Select de seleção da mãe do professor, em caso de homônimos.
	*/
	private var menu_select_maes_professores:c_select;
	private var POSX_SELECT_PROFESSORES:Number = 8.1 + 420;
	private var POSY_SELECT_PROFESSORES:Number = 300;
	
	//---- Anos
	private var anos:Array = new Array();
	private var maesProfessores:Array = new Array();
	
	/*
	* Botões que dão acesso às interfaces de edição dos dados, dos professores, dos monitores e dos alunos.
	* Destes quatro botões, apenas 3 aparecem por vez, dependendo da interface que estiver aberta.
	*/
	private var btEditarProfessores:c_btGrande;
	private var btEditarMonitores:c_btGrande;
	private var btEditarAlunos:c_btGrande;
	private var btEditarDados:c_btGrande;
	private var POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE:Point;
	private static var ESPACO_ENTRE_BOTOES:Number = 9.7;
	
	/*
	* Interfaces que são abertas ao clicar nos botões.
	*/
	private var intefaceProfessores:c_interface_edicao_pessoas_turma;
	private var intefaceMonitores:c_interface_edicao_pessoas_turma;
	private var intefaceAlunos:c_interface_edicao_pessoas_turma;
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		inicializarTextFields();
		inicializarBotaoCadastrarTurma();
		
		turma_cadastro = new c_turma();
		
		POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE = new Point(botaoCriarTurma._width+ESPACO_ENTRE_BOTOES, POSY_BOTAO_CRIAR_TURMA);
		
		//Botões
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarDados_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarDados = this['btEditarDados_dummy'];
		btEditarDados.inicializar("Editar Dados");
		btEditarDados.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarProfessores_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarProfessores = this['btEditarProfessores_dummy'];
		btEditarProfessores.inicializar("Editar Professores");
		btEditarProfessores.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarMonitores_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarMonitores = this['btEditarMonitores_dummy'];
		btEditarMonitores.inicializar("Editar Monitores");
		btEditarMonitores.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarAlunos_dummy", getNextHighestDepth(), {_x:0, _y:POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.y});
		btEditarAlunos = this['btEditarAlunos_dummy'];
		btEditarAlunos.inicializar("Editar Alunos");
		btEditarAlunos.addEventListener("btPressionado", Delegate.create(this,trocarInterface));
		
		//Interfaces
		attachMovie(c_interface_edicao_pessoas_turma.LINK_BIBLIOTECA, "intefaceProfessores_dummy", getNextHighestDepth(), {_x:this['interface_dados']._x, _y:this['interface_dados']._y});
		intefaceProfessores = this['intefaceProfessores_dummy'];
		intefaceProfessores.inicializar(new Array(), "Professor", "Professores");
		
		attachMovie(c_interface_edicao_pessoas_turma.LINK_BIBLIOTECA, "intefaceMonitores_dummy", getNextHighestDepth(), {_x:this['interface_dados']._x, _y:this['interface_dados']._y});
		intefaceMonitores = this['intefaceMonitores_dummy'];
		intefaceMonitores.inicializar(new Array(), "Monitor", "Monitores");
		
		attachMovie(c_interface_edicao_pessoas_turma.LINK_BIBLIOTECA, "intefaceAlunos_dummy", getNextHighestDepth(), {_x:this['interface_dados']._x, _y:this['interface_dados']._y});
		intefaceAlunos = this['intefaceAlunos_dummy'];
		intefaceAlunos.inicializar(new Array(), "Aluno", "Alunos");
		
		endereco_arquivo_pesquisa_php = "phps_do_menu/pesquisa_anos.php";
		endereco_arquivo_gravacao_php = "phps_do_menu/cadastrar_turma.php";
		
		var objetoSimulandoClickbtEditarDados:Object = new Object();
		objetoSimulandoClickbtEditarDados.nome = btEditarDados._name;
		trocarInterface(objetoSimulandoClickbtEditarDados);
	}
	
	//---- Interface
	public function mostrar():Void{
		pesquisaNoBD();
		_visible = true;
	}
	public function esconder():Void{
		_visible = false;
	}
	
	/*
	* Esta interface dá acesso a 4 outras via botões. São elas:
	*	- Edição de dados
	*	- Edição de professores
	*	- Edição de monitores
	*	- Edição de alunos
	* @param evento_botao_param O evento que gerou a chamada a esta função, com o nome do botão no atributo nome.
	*/
	private function trocarInterface(evento_botao_param:Object):Void{
		btEditarDados._visible = true;
		btEditarProfessores._visible = true;
		btEditarMonitores._visible = true;
		btEditarAlunos._visible = true;
		intefaceProfessores._visible = false;
		intefaceMonitores._visible = false;
		intefaceAlunos._visible = false;
		this['interface_dados']._visible = false;
		switch(evento_botao_param.nome){
			case btEditarDados._name: btEditarDados._visible = false;
									  btEditarProfessores._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									  btEditarMonitores._x = btEditarProfessores._x+btEditarProfessores._width+ESPACO_ENTRE_BOTOES;
									  btEditarAlunos._x = btEditarMonitores._x+btEditarMonitores._width+ESPACO_ENTRE_BOTOES;
									  this['interface_dados']._visible = true;
				break;
			case btEditarProfessores._name: btEditarProfessores._visible = false;
											btEditarDados._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									  		btEditarMonitores._x = btEditarDados._x+btEditarDados._width+ESPACO_ENTRE_BOTOES;
									  		btEditarAlunos._x = btEditarMonitores._x+btEditarMonitores._width+ESPACO_ENTRE_BOTOES;
											intefaceProfessores._visible = true;
				break;
			case btEditarMonitores._name: btEditarMonitores._visible = false;
										  btEditarDados._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									  	  btEditarProfessores._x = btEditarDados._x+btEditarDados._width+ESPACO_ENTRE_BOTOES;
									  	  btEditarAlunos._x = btEditarProfessores._x+btEditarProfessores._width+ESPACO_ENTRE_BOTOES;
										  intefaceMonitores._visible = true;
				break;
			case btEditarAlunos._name: btEditarAlunos._visible = false;
									   btEditarDados._x = POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE.x;
									   btEditarProfessores._x = btEditarDados._x+btEditarDados._width+ESPACO_ENTRE_BOTOES;
									   btEditarMonitores._x = btEditarProfessores._x+btEditarProfessores._width+ESPACO_ENTRE_BOTOES;
									   intefaceAlunos._visible = true;
				break;
		}
	}
	
	private function preencherCampos(dados_param:ac_dados):Void{
		//this['professor'].text = new String();
		//this['nomeMaeProfessor'].text = new String();
		//this['nome'].text = new String();
		//this['descricao'].text = new String();
		inicializarDropdownAnos(anos);
	}
	
	//---- Botões
	private function inicializarBotaoCadastrarTurma(){
		attachMovie("btCadastrarTurma", "botaoCriarTurma", getNextHighestDepth());//Não tirar o _root sob hipótese alguma. Clonará objetos ligados.
		botaoCriarTurma._x = POSX_BOTAO_CRIAR_TURMA;
		botaoCriarTurma._y = POSY_BOTAO_CRIAR_TURMA;
		botaoCriarTurma.inicializar();
		botaoCriarTurma.addEventListener("btCriarTurmaPressionado", Delegate.create(this, cadastrar));	
	}
	
	//---- TextFields
	private function inicializarTextFields(){
		this['interface_dados'].professor.multiline = false;
		this['interface_dados'].nomeMaeProfessor.multiline = false;
		this['interface_dados'].nome.multiline = false;
		this['interface_dados'].descricao.multiline = true;
	}
	private function atualizarCampoProfessor(){
		this['interface_dados'].nomeMaeProfessor.text = menu_select_maes_professores.getOpcaoSelecionada();
	}
	
	//---- Select
	public function inicializarDropdownAnos(textoOpcoes_param:Array){	
		if(this['interface_dados'][NOME_DROPDOWN_ANOS] != null){//Se já foi inicializado.
			this['interface_dados'][NOME_DROPDOWN_ANOS].removeMovieClip();
		}
		this['interface_dados'].attachMovie(c_dropdown.LINK_BIBLIOTECA, NOME_DROPDOWN_ANOS, getNextHighestDepth(), {_x:POSICAO_DROPDOWN_ANOS.x-this['interface_dados']._x, 
																													_y:POSICAO_DROPDOWN_ANOS.y-this['interface_dados']._y});	
		menu_dropdown_anos = this['interface_dados'][NOME_DROPDOWN_ANOS];
		textoOpcoes_param.push("Outro");
		menu_dropdown_anos.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Anos Cadastrados");
		menu_dropdown_anos._visible = true;
	}
	public function inicializarSelectMaesProfessores(textoOpcoes_param:Array){	
		if(menu_select_maes_professores != null){//Se já foi inicializado.
			menu_select_maes_professores.removeMovieClip();
		}
		this['interface_dados'].attachMovie(c_select.LINK_BIBLIOTECA, "menu_select_maes_professores", getNextHighestDepth()+1, {_x:POSX_SELECT_PROFESSORES-this['interface_dados']._x, 
																												 _y:POSY_SELECT_PROFESSORES-this['interface_dados']._y});	
		menu_select_maes_professores = this['interface_dados'].menu_select_maes_professores;
		menu_select_maes_professores.addEventListener("botaoPressionado", Delegate.create(this, atualizarCampoProfessor));	
		menu_select_maes_professores.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Mães de Professores Encontrados");
		menu_select_maes_professores._visible = true;
	}
	
	//---- Pesquisa
	private function armazenarDadosPesquisa():Void{
		anos = new Array();
		for(var i:Number = 1; i<=recebe.numDadosEncontrados; i++){
			anos.push(recebe["ano_nome"+i]);
		}
	}
	private function criaEnviaPesquisa(){
		envia = new LoadVars();
		envia.dado_pesquisado = null;
	}
	
	//---- Servidor
	private function criaEnvia():Void{
		var professores:Array = turma_cadastro.getProfessores();
		var monitores:Array = turma_cadastro.getMonitores();
		var alunos:Array = turma_cadastro.getAlunos();
		var numeroProfessores:Number = professores.length;
		var numeroMonitores:Number = monitores.length;
		var numeroAlunos:Number = alunos.length;
		
		envia.nome              = turma_cadastro.nome;
		envia.professor         = turma_cadastro.professor; 
		envia.nomeMaeProfessor  = turma_cadastro.nomeMaeProfessor;
		envia.descricao         = turma_cadastro.descricao; 
		envia.ano               = (turma_cadastro.ano == undefined? 'Outro' : turma_cadastro.ano);
		envia.numeroProfessores = numeroProfessores;
		envia.numeroMonitores   = numeroMonitores;
		envia.numeroAlunos      = numeroAlunos;
		
		for(var i:Number=0; i<numeroProfessores; i++){
			envia['professor'+i] = professores[i];
		}
		for(var i:Number=0; i<numeroMonitores; i++){
			envia['monitor'+i] = monitores[i];
		}
		for(var i:Number=0; i<numeroAlunos; i++){
			envia['aluno'+i] = alunos[i];
		}
	}
	
	//---- Cadastro
	private function armazenarDadosParaCadastro():Void{
		turma_cadastro = new c_turma();
		
		turma_cadastro.nome = this['interface_dados'].nome.text.split("\r").join("").split("\n").join("");
		turma_cadastro.professor = this['interface_dados'].professor.text.split("\r").join("").split("\n").join(""); 
		turma_cadastro.nomeMaeProfessor = this['interface_dados'].nomeMaeProfessor.text.split("\r").join("").split("\n").join(""); 
		turma_cadastro.descricao = this['interface_dados'].descricao.text.split("\r").join("").split("\n").join(""); 
		turma_cadastro.ano = this['interface_dados'].menu_dropdown_anos.getOpcaoSelecionada();
		
		turma_cadastro.definirProfessores(intefaceProfessores.getDadosMenu());
		turma_cadastro.definirMonitores(intefaceMonitores.getDadosMenu());
		turma_cadastro.definirAlunos(intefaceAlunos.getDadosMenu());
		
		if(!informado(turma_cadastro.nome)){
			turma_cadastro.nome = "";
		}
		if(!informado(turma_cadastro.professor)){
			turma_cadastro.professor = "";
		}
		if(!informado(turma_cadastro.nomeMaeProfessor)){
			turma_cadastro.nomeMaeProfessor = "";
		}
		if(!informado(turma_cadastro.descricao)){
			turma_cadastro.descricao = "";
		}
		if(!informado(turma_cadastro.ano)){
			turma_cadastro.ano = "";
		}
	}
	private function comunicarSucessoCadastro():Void{
		menu_select_maes_professores._visible = false;
		this['interface_dados'].professor.text = "";
		this['interface_dados'].nomeMaeProfessor.text = "";
		this['interface_dados'].nome.text = "";
		this['interface_dados'].descricao.text = "";
		
		intefaceProfessores.carregarNovosDados(new Array());
		intefaceMonitores.carregarNovosDados(new Array());
		intefaceAlunos.carregarNovosDados(new Array());
		
		c_aviso_com_ok.mostrar(mensagemSucessoCadastro);
	}
	private function comunicarErroCadastro():Void{
		if(1 < recebe.numProfessoresEncontrados){
			maesProfessores = new Array();
			for(var i:Number = 1; i<=recebe.numProfessoresEncontrados; i++){
				maesProfessores.push(recebe["mae_professor_nome"+i]);
			}
			inicializarSelectMaesProfessores(maesProfessores);
		} else {
			menu_select_maes_professores._visible = false;
		}
		
		c_aviso_com_ok.mostrar(mensagemErroCadastro);
	}
	
	//---- Dados
	private function dadosValidosParaCadastro():Boolean{
		return turma_cadastro.validarSemId();
	}
	
}