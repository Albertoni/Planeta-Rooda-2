import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;


class c_camposEditarPermissoesPlaneta extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Planeta - Acesso
	private var acessoConfiguravel:Boolean = false; //Se é possível configurar o acesso a este planeta.
	
	//---- Planeta - Edição
	private var edicaoConfiguravel:Boolean = false; //Se é possível configurar a edição deste planeta.

//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);

		this['dono'].multiline = false;

		mx.events.EventDispatcher.initialize(this['todosAcesso']);
		mx.events.EventDispatcher.initialize(this['configurarAcesso']);
		mx.events.EventDispatcher.initialize(this['nenhumAcesso']);
		this['todosAcesso'].addEventListener("click", Delegate.create(this, esconderOpcoesConfiguracao));
		this['configurarAcesso'].addEventListener("click", Delegate.create(this, mostrarOpcoesConfiguracao));
		this['nenhumAcesso'].addEventListener("click", Delegate.create(this, esconderOpcoesConfiguracao));
	}

	//---- Inteface
	public function abrirInterface(){
		if(acessoConfiguravel){
			this['labelAcesso']._visible = true;
			this['todosAcesso']._visible = true;
			this['configurarAcesso']._visible = true;
			if(this['configurarAcesso'].selected){
				setVisibilidadeOpcoesConfiguracao(true);
			} else {
				setVisibilidadeOpcoesConfiguracao(false);
			}
			this['nenhumAcesso']._visible = true;
		}
		else{
			this['labelAcesso']._visible = false;
			this['todosAcesso']._visible = false;
			this['configurarAcesso']._visible = false;
			this['professoresAcesso']._visible = false;
			this['alunosAcesso']._visible = false;
			this['visitantesAcesso']._visible = false;
			this['nenhumAcesso']._visible = false;
		}
		
		if(edicaoConfiguravel){
			this['labelEdicao']._visible = true;
			this['alunosPodem']._visible = true;
			this['alunosNaoPodem']._visible = true;
		}
		else{
			this['labelEdicao']._visible = false;
			this['alunosPodem']._visible = false;
			this['alunosNaoPodem']._visible = false;
		}
		_visible = true;
	}
	public function fecharInterface(){
		_visible = false;
	}
	private function mostrarOpcoesConfiguracao():Void{
		setVisibilidadeOpcoesConfiguracao(true);
	}
	private function esconderOpcoesConfiguracao():Void{
		setVisibilidadeOpcoesConfiguracao(false);
	}
	private function setVisibilidadeOpcoesConfiguracao(visivel_param:Boolean):Void{
		if(visivel_param){
			this['professoresAcesso']._visible = true;
			this['alunosAcesso']._visible = true;
			this['visitantesAcesso']._visible = true;
		} else {
			this['professoresAcesso']._visible = false;
			this['alunosAcesso']._visible = false;
			this['visitantesAcesso']._visible = false;
		}
	}

	//---- Getters
	public function getDono():String{
		return this['dono'].text;
	}
	public function getPermissaoAcesso():Number{
		var planeta:c_planeta = new c_planeta();
		var permissao:Number = planeta.ACESSO_NENHUM;
		switch (true){
			case this['todosAcesso'].selected: permissao = planeta.ACESSO_TODOS;
				break;
			case this['configurarAcesso'].selected: permissao = getPermissaoAcessoConfigurada();
				break;
			case this['nenhumAcesso'].selected: permissao = planeta.ACESSO_NENHUM;
				break;
			default: permissao = planeta.ACESSO_NENHUM;
				break;
		}
		return permissao;
	}
	private function getPermissaoAcessoConfigurada():Number{
		var planeta:c_planeta = new c_planeta();
		var permissao:Number = planeta.ACESSO_NENHUM;
		switch (true){
			case this['professoresAcesso'].selected: permissao = permissao | planeta.ACESSO_PROFESSORES;
				break;
			case this['alunosAcesso'].selected:  permissao = permissao | planeta.ACESSO_ALUNOS;
				break;
			case this['visitantesAcesso'].selected:  permissao = permissao | planeta.ACESSO_VISITANTES;
				break;
			default: permissao = planeta.ACESSO_NENHUM;
				break;
		}
		return permissao;
	}
	public function getPermissaoEdicao():String{
		var planeta:c_planeta = new c_planeta();
		var permissao:String;
		switch (true){
			case this['alunosPodem'].selected: permissao = planeta.EDICAO_ALUNOS_PERMITIDA;
				break;
			case this['alunosNaoPodem'].selected: permissao =  planeta.EDICAO_ALUNOS_NAO_PERMITIDA;
				break;
			default: permissao = planeta.EDICAO_ALUNOS_NAO_PERMITIDA;
				break;
		}
		return permissao;
	}
	
	//---- Setters
	public function setAcessoConfiguravel(configuravel_param:Boolean){
		acessoConfiguravel = configuravel_param;
	}
	public function setEdicaoConfiguravel(configuravel_param:Boolean){
		edicaoConfiguravel = configuravel_param;
	}
	public function setDono(dono_param:String):Void{
		this['dono'].text = dono_param;
	}
	public function setPermissaoAcesso(permissao_acesso_param:Number):Void{
		var planeta:c_planeta = new c_planeta();
		var todosPermitidos:Boolean = ((planeta.ACESSO_TODOS & permissao_acesso_param) != 0);
		var professoresPermitidos:Boolean = ((planeta.ACESSO_PROFESSORES & permissao_acesso_param) != 0);
		var alunosPermitidos:Boolean = ((planeta.ACESSO_ALUNOS & permissao_acesso_param) != 0);
		var visitantesPermitidos:Boolean = ((planeta.ACESSO_VISITANTES & permissao_acesso_param) != 0);
				
		switch (true){
			case (todosPermitidos):
				setVisibilidadeOpcoesConfiguracao(false);
				this['todosAcesso'].selected = true;
				break;
			case (professoresPermitidos || alunosPermitidos || visitantesPermitidos): 
				setPermissaoAcessoConfigurada(professoresPermitidos, alunosPermitidos, visitantesPermitidos);
				break;
			default:
				setVisibilidadeOpcoesConfiguracao(false);
				this['nenhumAcesso'].selected = true;
				break;
		}
	}
	private function setPermissaoAcessoConfigurada(professores_param:Boolean, alunos_param:Boolean, visitantes_param:Boolean):Void{
		setVisibilidadeOpcoesConfiguracao(true);
		if(professores_param){
			this['professoresAcesso'].selected = true;
		} else {
			this['professoresAcesso'].selected = false;
		}
		if(alunos_param){
			this['alunosAcesso'].selected = true;
		} else {
			this['alunosAcesso'].selected = false;
		}
		if(visitantes_param){
			this['visitantesAcesso'].selected = true;
		} else {
			this['visitantesAcesso'].selected = false;
		}
	}
	public function setPermissaoEdicao(permissao_edicao_param:String):Void{
		var planeta:c_planeta = new c_planeta();
		var alunosPermitidos:Boolean = (planeta.EDICAO_ALUNOS_PERMITIDA == permissao_edicao_param);
		var alunosNaoPermitidos:Boolean = (planeta.EDICAO_ALUNOS_NAO_PERMITIDA == permissao_edicao_param);
		
		switch (true){
			case (alunosPermitidos): this['alunosPodem'].selected = true;
				break;
			case (alunosNaoPermitidos): this['alunosNaoPodem'].selected = true;
				break;
		}
	}

}
