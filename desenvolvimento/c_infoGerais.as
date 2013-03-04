import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;

class c_infoGerais extends MovieClip {
//dados		
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	//---- Botões
	public var abreInfoGerais:c_btAbreInfoGerais;
	private var POSX_BT_ABRE_INFO_GERAIS:Number = 0;
	private var POSY_BT_ABRE_INFO_GERAIS:Number = 0;
	
//métodos
	public function inicializar() {
		mx.events.EventDispatcher.initialize(this);
		
		this['campos'].nome.multiline = false;
		this['campos'].nomeMae.multiline = false;
		this['campos'].apelido.multiline = false;
		this['campos'].login.multiline = false;
		this['campos'].diaAniversario.multiline = false;
		this['campos'].mesAniversario.multiline = false;
		this['campos'].anoAniversario.multiline = false;
		this['campos'].diaAniversario.restrict = "0-9";
		this['campos'].mesAniversario.restrict = "0-9";
		this['campos'].anoAniversario.restrict = "0-9";
		this['campos']._visible = false;
		
		/*Listeners*/
		attachMovie("abre_info_gerais", "abreInfoGerais", getNextHighestDepth());
		abreInfoGerais.inicializar();
		abreInfoGerais._x = POSX_BT_ABRE_INFO_GERAIS;
		abreInfoGerais._y = POSY_BT_ABRE_INFO_GERAIS;
		abreInfoGerais.addEventListener("btAbreInfoGeraisPress", Delegate.create(this, abrir));	
	}
	
	//---- Inteface
	public function abrirInterface(){
		this['campos']._visible = true;
	}
	public function fecharInterface(){
		this['campos']._visible = false;
	}

	//---- Teclado
	private function abrir(){
		dispatchEvent({target:this, type:"abrirInfoGerais"});
	}
		
	//---- Getters
	public function getNome():String{
		return this['campos'].nome.text;
	}
	public function getNomeMae():String{
		return this['campos'].nomeMae.text;
	}
	public function getApelido():String{
		return this['campos'].apelido.text;
	}
	public function getLogin():String{
		return this['campos'].login.text;
	}
	public function getDiaAniversario():String{
		return this['campos'].diaAniversario.text;
	}
	public function getMesAniversario():String{
		return this['campos'].mesAniversario.text;
	}
	public function getAnoAniversario():String{
		return this['campos'].anoAniversario.text;
	}
	public function getNivel():String{
		var nivel:Number = 0;
		
		if(this['campos'].administrador.selected){
			nivel = nivel | c_conta.getNivelAdministrador();
		}
		if(this['campos'].coordenador.selected){
			nivel = nivel | c_conta.getNivelCoordenador();
		}
		if(this['campos'].professor.selected){
			nivel = nivel | c_conta.getNivelProfessor();
		}
		if(this['campos'].monitor.selected){
			nivel = nivel | c_conta.getNivelMonitor();
		}
		if(this['campos'].aluno.selected){
			nivel = nivel | c_conta.getNivelAluno();
		}
		if(this['campos'].visitante.selected){
			nivel = c_conta.getNivelVisitante();
		}
		
		return c_conta.criarNivel(nivel);
	}
	
	//---- Setters
	public function setNome(nome_param:String):Void{
		this['campos'].nome.text = nome_param;
	}
	public function setNomeMae(nome_param:String):Void{
		this['campos'].nomeMae.text = nome_param;
	}
	public function setApelido(apelido_param:String):Void{
		this['campos'].apelido.text = apelido_param;
	}
	public function setLogin(login_param:String):Void{
		this['campos'].login.text = login_param;
	}
	public function setDiaAniversario(diaAniversario_param:String):Void{
		this['campos'].diaAniversario.text = diaAniversario_param;
	}
	public function setMesAniversario(mesAniversario_param:String):Void{
		this['campos'].mesAniversario.text = mesAniversario_param;
	}
	public function setAnoAniversario(anoAniversario_param:String):Void{
		this['campos'].anoAniversario.text = anoAniversario_param;
	}
	public function setNivel(nivel_param:String):Void{
		var nivel = parseInt(nivel_param);
		
		if(c_conta.nivelPossuiPermissaoDe(nivel, c_conta.getNivelAdministrador())){
			this['campos'].administrador.selected = true;
		}
		else{
			this['campos'].administrador.selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(nivel, c_conta.getNivelCoordenador())){
			this['campos'].coordenador.selected = true;
		}
		else{
			this['campos'].coordenador.selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(nivel, c_conta.getNivelProfessor())){
			this['campos'].professor.selected = true;
		}
		else{
			this['campos'].professor.selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(nivel, c_conta.getNivelMonitor())){
			this['campos'].monitor.selected = true;
		}
		else{
			this['campos'].monitor.selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(nivel, c_conta.getNivelAluno())){
			this['campos'].aluno.selected = true;
		}
		else{
			this['campos'].aluno.selected = false;
		}
		if(c_conta.nivelPossuiPermissaoDe(nivel, c_conta.getNivelVisitante())){
			//this['campos'].administrador.selected = false;
			//this['campos'].coordenador.selected = false;
			//this['campos'].professor.selected = false;
			//this['campos'].monitor.selected = false;
			//this['campos'].aluno.selected = false;
			this['campos'].visitante.selected = true;
		}
		else{
			this['campos'].visitante.selected = false;
		}
	}
	public function setPossibilidadeModificarParaAdmin(visivel:Boolean):Void{
		this['campos'].administrador._visible = visivel;
	}
	public function setPossibilidadeModificarParaCoordenador(visivel:Boolean):Void{
		this['campos'].coordenador._visible = visivel;
	}
	public function setPossibilidadeModificarParaProfessor(visivel:Boolean):Void{
		this['campos'].professor._visible = visivel;
	}
	public function setPossibilidadeModificarParaMonitor(visivel:Boolean):Void{
		this['campos'].monitor._visible = visivel;
	}
	public function setPossibilidadeModificarParaAluno(visivel:Boolean):Void{
		this['campos'].aluno._visible = visivel;
	}
	public function setPossibilidadeModificarParaVisitante(visivel:Boolean):Void{
		this['campos'].visitante._visible = visivel;
	}
	
	
	
	
}
