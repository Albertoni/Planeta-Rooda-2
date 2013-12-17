import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;
import flash.geom.Point;

class c_interface_edicao_turma extends ac_interface_edicao {
//dados
	//---- Edição
	private var turma_pesquisa:c_turma;
	private var turma_edicao:c_turma;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Selects
	private var NUMERO_OPCOES_VISIVEIS_SELECT:Number = 5;
	
	/*
	* Escolha do ano da turma.
	*/
	private var menu_dropdown_anos:c_dropdown;
	private static var NOME_DROPDOWN_ANOS:String = "menu_dropdown_anos";
	private static var POSICAO_DROPDOWN_ANOS:Point = new Point(421.35, 70.3);
	
	/*
	* Escolha da mãe de um professor, para caso de homônimos.
	*/
	private var menu_select_maes_professores:c_select;
	private var POSX_SELECT_PROFESSORES:Number = 8.1 + 420;
	private var POSY_SELECT_PROFESSORES:Number = 300;
	
	//---- Anos
	private var anos:Array = new Array();
	private var maesProfessores:Array = new Array();
	
	/*
	* Endereço do arquivo php para gerenciamento das funcionalidades da turma.
	*/
	private var endereco_arquivo_gerenciamento_funcionalidades_php:String;
	
	/*
	* Botões que dão acesso às interfaces de edição dos dados, dos professores, dos monitores e dos alunos.
	* Destes quatro botões, apenas 3 aparecem por vez, dependendo da interface que estiver aberta.
	*/
	private var btEditarProfessores:c_btGrande;
	private var btEditarMonitores:c_btGrande;
	private var btEditarAlunos:c_btGrande;
	private var btEditarDados:c_btGrande;
	private var btEditarGerenciamentoFuncionalidades:c_btGrande;
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

		POSICAO_PRIMEIRO_BOTAO_TROCA_INTERFACE = new Point(btDeletar._x+btDeletar._width+ESPACO_ENTRE_BOTOES,btDeletar._y);

		turma_pesquisa = new c_turma();
		turma_edicao = new c_turma();
		
		//Botões
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btEditarGerenciamentoFuncionalidades_dummy", getNextHighestDepth(), {_x:0, _y:btDeletar._y - btDeletar._height - 15});
		btEditarGerenciamentoFuncionalidades = this['btEditarGerenciamentoFuncionalidades_dummy'];
		btEditarGerenciamentoFuncionalidades.inicializar("Gerenciar Funcionalidades");
		btEditarGerenciamentoFuncionalidades.addEventListener("btPressionado", Delegate.create(this,abrirTelaGerenciamentoFuncionalidades));
		
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
		endereco_arquivo_gravacao_php = "phps_do_menu/edicao_turmas.php";		
		endereco_arquivo_delecao_php = "phps_do_menu/deletar_turma.php";		
		endereco_arquivo_gerenciamento_funcionalidades_php = c_objeto_acesso.LINK_BASE+"funcionalidades/gerenciamento_funcionalidades_turmas/gerenciamento_funcionalidades_turmas.php";
		
		var objetoSimulandoClickbtEditarDados:Object = new Object();
		objetoSimulandoClickbtEditarDados.nome = btEditarDados._name;
		trocarInterface(objetoSimulandoClickbtEditarDados);
	}
	
	/*
	* Abre a tela de gerenciamento das funcionalidades da turma que está sendo editada.
	*/
	private function abrirTelaGerenciamentoFuncionalidades(){
		 getURL(endereco_arquivo_gerenciamento_funcionalidades_php+"?idTurma="+turma_pesquisa.identificacao, "_self");
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
	
	//---- Interface
	public function mostrar():Void{
		pesquisaNoBD();
		_visible = true;
	}
	private function restringirCamposDeTexto():Void{
		this['interface_dados'].nome.multiline = false;
		this['interface_dados'].professor.multiline = false;
		this['interface_dados'].nomeMaeProfessor.multiline = false;
		this['interface_dados'].descricao.multiline = false;
	}
	private function atualizarCampoAno(){
		this['interface_dados'].ano.text = this['interface_dados'].menu_dropdown_anos.getOpcaoSelecionada();
	}
	private function atualizarCampoProfessor(){
		this['interface_dados'].nomeMaeProfessor.text = this['interface_dados'].menu_select_maes_professores.getOpcaoSelecionada();
	}
	private function armazenarDadosEditados():Void{
		turma_edicao.identificacao = turma_pesquisa.identificacao;
		
		turma_edicao.nome = this['interface_dados'].nome.text.split("\r").join("").split("\n").join(""); 
		turma_edicao.professor = this['interface_dados'].professor.text.split("\r").join("").split("\n").join(""); 
		turma_edicao.nomeMaeProfessor = this['interface_dados'].nomeMaeProfessor.text.split("\r").join("").split("\n").join(""); 
		turma_edicao.descricao = this['interface_dados'].descricao.text.split("\r").join("").split("\n").join(""); 
		turma_edicao.ano = this['interface_dados'].menu_dropdown_anos.getOpcaoSelecionada(); 
		
		turma_edicao.definirProfessores(intefaceProfessores.getDadosMenu());
		turma_edicao.definirMonitores(intefaceMonitores.getDadosMenu());
		turma_edicao.definirAlunos(intefaceAlunos.getDadosMenu());
		
		if(!informado(turma_edicao.nome)){
			turma_edicao.nome = "";
		}
		if(!informado(turma_edicao.professor)){
			turma_edicao.professor = "";
		}
		if(!informado(turma_edicao.nomeMaeProfessor)){
			turma_edicao.nomeMaeProfessor = "";
		}
		if(!informado(turma_edicao.descricao)){
			turma_edicao.descricao = "";
		}
		if(!informado(turma_edicao.ano)){
			turma_edicao.ano = "";
		}
	}
	private function preencherCampos(dados_param:ac_dados):Void{
		turma_pesquisa = dados_param.turma;
		
		turma_edicao.identificacao = turma_pesquisa.identificacao;
		this['interface_dados'].nome.text = turma_pesquisa.nome;
		this['interface_dados'].professor.text = turma_pesquisa.professor;
		this['interface_dados'].nomeMaeProfessor.text = turma_pesquisa.nomeMaeProfessor;
		this['interface_dados'].descricao.text = turma_pesquisa.descricao;
		this['interface_dados'].menu_dropdown_anos.mudarTextoBarraAbertura(turma_pesquisa.ano);
		
		intefaceProfessores.carregarNovosDados(turma_pesquisa.getProfessores());
		intefaceMonitores.carregarNovosDados(turma_pesquisa.getMonitores());
		intefaceAlunos.carregarNovosDados(turma_pesquisa.getAlunos());
		
		inicializarDropdownAnos(anos);
	}
	
	//---- Select
	public function inicializarDropdownAnos(textoOpcoes_param:Array){	
		if(this['interface_dados'].menu_dropdown_anos != null){//Se já foi inicializado.
			this['interface_dados'].menu_dropdown_anos.removeMovieClip();
		}
		this['interface_dados'].attachMovie(c_dropdown.LINK_BIBLIOTECA, NOME_DROPDOWN_ANOS, getNextHighestDepth(), {_x:POSICAO_DROPDOWN_ANOS.x - this['interface_dados']._x, _y:POSICAO_DROPDOWN_ANOS.y - this['interface_dados']._y});	
		this['interface_dados'].menu_dropdown_anos.addEventListener("botaoPressionado", Delegate.create(this, atualizarCampoAno));	
		textoOpcoes_param.push("Outro");
		this['interface_dados'].menu_dropdown_anos.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Anos Cadastrados");
		this['interface_dados'].menu_dropdown_anos._visible = true;
	}
	public function inicializarSelectMaesProfessores(textoOpcoes_param:Array){	
		if(this['interface_dados'].menu_select_maes_professores != null){//Se já foi inicializado.
			this['interface_dados'].menu_select_maes_professores.removeMovieClip();
		}
		this['interface_dados'].attachMovie("select_mc", "menu_select_maes_professores", getNextHighestDepth(), {_x:POSX_SELECT_PROFESSORES - this['interface_dados']._x, _y:POSY_SELECT_PROFESSORES - this['interface_dados']._y});	
		this['interface_dados'].menu_select_maes_professores.addEventListener("botaoPressionado", Delegate.create(this, atualizarCampoProfessor));	
		this['interface_dados'].menu_select_maes_professores.inicializar(NUMERO_OPCOES_VISIVEIS_SELECT, textoOpcoes_param, "Mães de Professores Encontrados");
		this['interface_dados'].menu_select_maes_professores._visible = true;
	}
	
	//---- Servidor
	private function criarEnvia():Void{
		var professores:Array = turma_edicao.getProfessores();
		var monitores:Array = turma_edicao.getMonitores();
		var alunos:Array = turma_edicao.getAlunos();
		var numeroProfessores:Number = professores.length;
		var numeroMonitores:Number = monitores.length;
		var numeroAlunos:Number = alunos.length;
		
		envia.identificacao     = turma_edicao.identificacao;
		envia.nome              = turma_edicao.nome;
		envia.professor         = turma_edicao.professor;
		envia.nomeMaeProfessor  = turma_edicao.nomeMaeProfessor;
		envia.descricao         = turma_edicao.descricao;
		envia.ano               = turma_edicao.ano;
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
	
	//---- Dados
	public function validarDados():Boolean{
		return turma_edicao.validar();
	}
	public function getErroValidacao():String{
		return turma_edicao.getMensagemErro();
	}
	
	//---- Pesquisa
	private function armazenarDadosPesquisa():Void{
		anos = new Array();
		for(var i:Number = 1; i<=recebe.numDadosEncontrados; i++){
			anos.push(recebe["ano_nome"+i]);
		}
		mensagemErroPesquisa = recebe.mensagemDeErro;
	}
	private function getDadosPesquisa():ac_dados{ 
		var dados:ac_dados = new ac_dados();
		var turma_atualmente_nos_campos:c_turma = new c_turma();
		
		turma_atualmente_nos_campos.identificacao = turma_pesquisa.identificacao;
		
		turma_atualmente_nos_campos.nome = this['interface_dados'].nome.text.split("\r").join("").split("\n").join(""); 
		turma_atualmente_nos_campos.professor = this['interface_dados'].professor.text.split("\r").join("").split("\n").join(""); 
		turma_atualmente_nos_campos.nomeMaeProfessor = this['interface_dados'].nomeMaeProfessor.text.split("\r").join("").split("\n").join(""); 
		turma_atualmente_nos_campos.descricao = this['interface_dados'].descricao.text.split("\r").join("").split("\n").join(""); 
		turma_atualmente_nos_campos.ano = this['interface_dados'].menu_dropdown_anos.getOpcaoSelecionada();
		
		turma_atualmente_nos_campos.definirProfessores(intefaceProfessores.getDadosMenu());
		turma_atualmente_nos_campos.definirMonitores(intefaceMonitores.getDadosMenu());
		turma_atualmente_nos_campos.definirAlunos(intefaceAlunos.getDadosMenu());
		
		dados.turma = turma_atualmente_nos_campos;
		
		return dados;
	}
	private function criaEnviaPesquisa(){
		envia = new LoadVars();
		envia.dado_pesquisado = null;
	}
	
	//---- Aviso
	private function comunicarErro():Void{
		if(1 < recebe.numProfessoresEncontrados){
			maesProfessores = new Array();
			for(var i:Number = 1; i<=recebe.numProfessoresEncontrados; i++){
				maesProfessores.push(recebe["mae_professor_nome"+i]);
			}
			inicializarSelectMaesProfessores(maesProfessores);
		} else {
			this['interface_dados'].menu_select_maes_professores._visible = false;
		}
		
		c_aviso_com_ok.mostrar(mensagemErro);
	}
	
	
	
	
	
	
	
}