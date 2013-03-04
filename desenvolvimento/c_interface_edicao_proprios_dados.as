import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;
import flash.geom.Point;

/*
* Edição de usuário para a opção EDITAR USUARIO.
* Para EDITAR CONTAS, ver c_interface_edicao_conta
*/
class c_interface_edicao_proprios_dados extends ac_interface_menu{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_edicao_proprios_dados";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Interfaces
	private var interfaceAtiva:MovieClip;
	
	private var infoGerais:c_infoGerais;
	private var POSX_INFO_GERAIS:Number = 0;
	private var POSY_INFO_GERAIS:Number = 0;
	
	private var trocaSenha:c_trocaSenha;
	private var POSX_TROCA_SENHA:Number = 0;
	private var POSY_TROCA_SENHA:Number = 35.7;
	
	private var trocaEmail:c_trocaEmail;
	private var POSX_TROCA_EMAIL:Number = 0;
	private var POSY_TROCA_EMAIL:Number = 71.4;
	
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();

		attachMovie("info_gerais", "infoGerais", getNextHighestDepth());
		infoGerais._x = POSX_INFO_GERAIS;
		infoGerais._y = POSY_INFO_GERAIS;
		infoGerais.inicializar();
		infoGerais.addEventListener("abrirInfoGerais", Delegate.create(this, informacoesGerais));	
		
		attachMovie("troca_senha", "trocaSenha", getNextHighestDepth());
		trocaSenha._x = POSX_TROCA_SENHA;
		trocaSenha._y = POSY_TROCA_SENHA;
		trocaSenha.inicializar();
		trocaSenha.addEventListener("abrirTrocaSenha", Delegate.create(this, trocarSenha));	
		
		attachMovie("trocar_email", "trocaEmail", getNextHighestDepth());
		trocaEmail._x = POSX_TROCA_EMAIL;
		trocaEmail._y = POSY_TROCA_EMAIL;
		trocaEmail.inicializar();
		trocaEmail.addEventListener("abrirTrocaEmail", Delegate.create(this, trocarEmail));	
		
		switch(_root.usuario_status.getPermissao()){
			case c_conta.getNivelVisitante(): 
				infoGerais.setPossibilidadeModificarParaAdmin(false);
				infoGerais.setPossibilidadeModificarParaCoordenador(false);
				infoGerais.setPossibilidadeModificarParaProfessor(false);
				infoGerais.setPossibilidadeModificarParaMonitor(false);
				infoGerais.setPossibilidadeModificarParaAluno(false);
				infoGerais.setPossibilidadeModificarParaVisitante(false);
				break;
	
			case c_conta.getNivelAluno(): 
				infoGerais.setPossibilidadeModificarParaAdmin(false);
				infoGerais.setPossibilidadeModificarParaCoordenador(false);
				infoGerais.setPossibilidadeModificarParaProfessor(false);
				infoGerais.setPossibilidadeModificarParaMonitor(false);
				infoGerais.setPossibilidadeModificarParaAluno(false);
				infoGerais.setPossibilidadeModificarParaVisitante(false);
				break;
	
			case c_conta.getNivelMonitor(): 
				infoGerais.setPossibilidadeModificarParaAdmin(false);
				infoGerais.setPossibilidadeModificarParaCoordenador(false);
				infoGerais.setPossibilidadeModificarParaProfessor(false);
				infoGerais.setPossibilidadeModificarParaMonitor(false);
				infoGerais.setPossibilidadeModificarParaAluno(false);
				infoGerais.setPossibilidadeModificarParaVisitante(false);
				break;
	
			case c_conta.getNivelProfessor(): 
				infoGerais.setPossibilidadeModificarParaAdmin(false);
				infoGerais.setPossibilidadeModificarParaCoordenador(false);
				infoGerais.setPossibilidadeModificarParaProfessor(true);
				infoGerais.setPossibilidadeModificarParaMonitor(true);
				infoGerais.setPossibilidadeModificarParaAluno(true);
				infoGerais.setPossibilidadeModificarParaVisitante(true);
				break;	

			case c_conta.getNivelCoordenador():
				infoGerais.setPossibilidadeModificarParaAdmin(false);
				infoGerais.setPossibilidadeModificarParaCoordenador(true);
				infoGerais.setPossibilidadeModificarParaProfessor(true);
				infoGerais.setPossibilidadeModificarParaMonitor(true);
				infoGerais.setPossibilidadeModificarParaAluno(true);
				infoGerais.setPossibilidadeModificarParaVisitante(true);
				break;
	
			case c_conta.getNivelAdministrador(): 
				infoGerais.setPossibilidadeModificarParaAdmin(true);
				infoGerais.setPossibilidadeModificarParaCoordenador(true);
				infoGerais.setPossibilidadeModificarParaProfessor(true);
				infoGerais.setPossibilidadeModificarParaMonitor(true);
				infoGerais.setPossibilidadeModificarParaAluno(true);
				infoGerais.setPossibilidadeModificarParaVisitante(true);
				break;
	
			default: 
				infoGerais.setPossibilidadeModificarParaAdmin(false);
				infoGerais.setPossibilidadeModificarParaCoordenador(false);
				infoGerais.setPossibilidadeModificarParaProfessor(false);
				infoGerais.setPossibilidadeModificarParaMonitor(false);
				infoGerais.setPossibilidadeModificarParaAluno(false);
				infoGerais.setPossibilidadeModificarParaVisitante(false);
				break;
		}
		
		informacoesGerais();
	}

	//---- Botões
	private function informacoesGerais():Void{
		interfaceAtiva = infoGerais;
		infoGerais.abrirInterface();
		trocaSenha.fecharInterface();
		trocaEmail.fecharInterface();
		
		//pesquisaNoBD();
		trocaSenha._y = infoGerais._y + infoGerais._height + 10; 
		trocaEmail._y = trocaSenha._y + 27.5 + 10; 
	}
	private function trocarSenha():Void{
		interfaceAtiva = trocaSenha;
		trocaSenha.abrirInterface();
		trocaEmail.fecharInterface();
		infoGerais.fecharInterface();
		
		//pesquisaNoBD();
		trocaSenha._y = infoGerais._y + 27.5 + 10; 
		trocaEmail._y = trocaSenha._y + trocaSenha._height + 10; 
	}
	private function trocarEmail():Void{
		interfaceAtiva = trocaEmail;
		trocaEmail.abrirInterface();
		infoGerais.fecharInterface();
		trocaSenha.fecharInterface();
		
		//pesquisaNoBD();
		trocaSenha._y = infoGerais._y + 27.5 + 10; 
		trocaEmail._y = trocaSenha._y + 27.5 + 10; 
	}
	

	/*
	* Devolvem e setam as contas de edição (dados que foram inseridos pelo usuário) e de pesquisa (para exibir os dados).
	*/
	public function getContaEdicao():c_conta{
		var conta_edicao:c_conta = new c_conta();
		conta_edicao.nome = infoGerais.getNome();
		conta_edicao.nomeMae = infoGerais.getNomeMae();
		conta_edicao.apelido = infoGerais.getApelido();
		conta_edicao.login = infoGerais.getLogin();
		conta_edicao.diaAniversario = infoGerais.getDiaAniversario();
		conta_edicao.mesAniversario = infoGerais.getMesAniversario();
		conta_edicao.anoAniversario = infoGerais.getAnoAniversario();
		conta_edicao.nivel = infoGerais.getNivel();
		conta_edicao.senha = trocaSenha.getNovaSenha();

		conta_edicao.email = trocaEmail.getNovoEmail();
	
		if(!informado(conta_edicao.nome)){
			conta_edicao.nome = "";
		}
		if(!informado(conta_edicao.apelido)){
			conta_edicao.apelido = "";
		}
		if(!informado(conta_edicao.login)){
			conta_edicao.login = "";
		}
		if(!informado(conta_edicao.diaAniversario)){
			conta_edicao.diaAniversario = "";
		}
		if(!informado(conta_edicao.mesAniversario)){
			conta_edicao.mesAniversario = "";
		}
		if(!informado(conta_edicao.anoAniversario)){
			conta_edicao.anoAniversario = "";
		}
		//nivel e sexo são checkboxes..
		if(!informado(conta_edicao.senha)){
			conta_edicao.senha = "";
		}
		if(!informado(conta_edicao.email)){
			conta_edicao.email = "";
		}
		return conta_edicao;
	}
	
	public function preencherCampos(conta_param:c_conta):Void{
		infoGerais.setNome(conta_param.nome);
		infoGerais.setNomeMae(conta_param.nomeMae);
		infoGerais.setApelido(conta_param.apelido);
		infoGerais.setLogin(conta_param.login);
		infoGerais.setDiaAniversario(conta_param.diaAniversario);
		infoGerais.setMesAniversario(conta_param.mesAniversario);
		infoGerais.setAnoAniversario(conta_param.anoAniversario);
		infoGerais.setNivel(conta_param.nivel);
		
		interfaceAtiva.abrirInterface();
	}

	
	

}