import mx.utils.Delegate;
import mx.events.EventDispatcher;
import mx.data.types.Obj;
/*
* Edição de usuário para a opção EDITAR CONTAS.
* Para EDITAR USUARIO, ver c_interface_edicao_proprios_dados
*/
class c_interface_edicao_dados_usuario extends ac_interface_menu {
//dados
	//---- Edição
	private var conta_pesquisa:c_conta;
	private var conta_edicao:c_conta;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		conta_pesquisa = new c_conta();
		conta_edicao = new c_conta();
	}
	public function restringirCamposDeTexto():Void{
		this['login'].multiline = false;
		this['senha'].multiline = false;
		this['senha'].password = true;
		this['diaAniversario'].multiline = false;
		this['mesAniversario'].multiline = false;
		this['anoAniversario'].multiline = false;
		this['nome'].multiline = false;
		this['nomeMae'].multiline = false;
		this['email'].multiline = false;
		this['apelido'].multiline = false;
		
		this['diaAniversario'].restrict = "0-9";
		this['mesAniversario'].restrict = "0-9";
		this['anoAniversario'].restrict = "0-9";
	}
	public function armazenarDadosEditados():c_conta{
		conta_edicao.identificacao = conta_pesquisa.identificacao;
		conta_edicao.login = this['login'].text;
		conta_edicao.senha = this['senha'].text;
		conta_edicao.diaAniversario = this['diaAniversario'].text;
		conta_edicao.mesAniversario = this['mesAniversario'].text;
		conta_edicao.anoAniversario = this['anoAniversario'].text;
		conta_edicao.nome = this['nome'].text;
		conta_edicao.nomeMae = this['nomeMae'].text;
		conta_edicao.email = this['email'].text;
		if(this['administrador'].selected){
			conta_edicao.adicionarNivel(c_conta.getNivelAdministrador());
		}
		if(this['coordenador'].selected){
			conta_edicao.adicionarNivel(c_conta.getNivelCoordenador());
		}
		if(this['professor'].selected){
			conta_edicao.adicionarNivel(c_conta.getNivelProfessor());
		}
		if(this['monitor'].selected){
			conta_edicao.adicionarNivel(c_conta.getNivelMonitor());
		}
		if(this['aluno'].selected){
			conta_edicao.adicionarNivel(c_conta.getNivelAluno());
		}
		if(this['visitante'].selected){
			conta_edicao.adicionarNivel(c_conta.getNivelVisitante());
		}
		conta_edicao.apelido = this['apelido'].text;
		
		if(!informado(conta_edicao.login)){
			conta_edicao.login = "";
		}
		if(!informado(conta_edicao.senha)){
			conta_edicao.senha = "";
		}
		if(!informado(conta_edicao.diaAniversario)
			or !informado(conta_edicao.mesAniversario)
			or !informado(conta_edicao.anoAniversario)){
			
			conta_edicao.diaAniversario = "";
			conta_edicao.mesAniversario = "";
			conta_edicao.anoAniversario = "";
		}
		if(!informado(conta_edicao.nome)){
			conta_edicao.nome = "";
		}
		if(!informado(conta_edicao.nomeMae)){
			conta_edicao.nomeMae = "";
		}
		if(!informado(conta_edicao.email)){
			conta_edicao.email = "";
		}
		//nível sempre estará informado, pois é checkbox
		if(!informado(conta_edicao.apelido)){
			conta_edicao.apelido = "";
		}
		//sexo sempre estará informado, pois é radiobutton
		return conta_edicao;
	}
	public function preencherCampos(dados_param:ac_dados):Void{
		conta_pesquisa = dados_param.conta;
		preencherCamposEdicaoConta();
	}
	public function preencherCamposEdicaoConta():Void{

		this['login'].text = conta_pesquisa.login;
		this['senha'].text = new String();
		this['diaAniversario'].text = conta_pesquisa.diaAniversario;
		this['mesAniversario'].text = conta_pesquisa.mesAniversario;
		this['anoAniversario'].text = conta_pesquisa.anoAniversario;
		this['nome'].text = conta_pesquisa.nome;
		this['nomeMae'].text = conta_pesquisa.nomeMae;
		this['email'].text = conta_pesquisa.email;
		if(c_conta.nivelPossuiPermissaoDe(parseInt(conta_pesquisa.nivel), c_conta.getNivelAdministrador())){
			this['administrador'].selected = true;
		}
		else{
			this['administrador'].selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(parseInt(conta_pesquisa.nivel), c_conta.getNivelCoordenador())){
			this['coordenador'].selected = true;
		}
		else{
			this['coordenador'].selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(parseInt(conta_pesquisa.nivel), c_conta.getNivelProfessor())){
			this['professor'].selected = true;
		}
		else{
			this['professor'].selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(parseInt(conta_pesquisa.nivel), c_conta.getNivelMonitor())){
			this['monitor'].selected = true;
		}
		else{
			this['monitor'].selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(parseInt(conta_pesquisa.nivel), c_conta.getNivelAluno())){
			this['aluno'].selected = true;
		}
		else{
			this['aluno'].selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(parseInt(conta_pesquisa.nivel), c_conta.getNivelVisitante())){
			this['visitante'].selected = true;
		}
		else{
			this['visitante'].selected = false;
		}
		this['apelido'].text = conta_pesquisa.apelido;
	}
	public function setPossibilidadeModificarParaAdmin(visivel:Boolean):Void{
		this['administrador']._visible = visivel;
	}
	public function setPossibilidadeModificarParaCoordenador(visivel:Boolean):Void{
		this['coordenador']._visible = visivel;
	}
	public function setPossibilidadeModificarParaProfessor(visivel:Boolean):Void{
		this['professor']._visible = visivel;
	}
	public function setPossibilidadeModificarParaMonitor(visivel:Boolean):Void{
		this['monitor']._visible = visivel;
	}
	public function setPossibilidadeModificarParaAluno(visivel:Boolean):Void{
		this['aluno']._visible = visivel;
	}
	public function setPossibilidadeModificarParaVisitante(visivel:Boolean):Void{
		this['visitante']._visible = visivel;
	}
	
	
	//---- Dados
	public function validarDados():Boolean{
		return conta_edicao.validar();
	}
	
}
