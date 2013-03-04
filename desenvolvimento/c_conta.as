class c_conta{
//dados
	//---- Constantes
	private static var ADMINISTRADOR:Number = 1 << 0;
	private static var COORDENADOR:Number = 1 << 1;
	private static var PROFESSOR:Number = 1 << 2;
	private static var MONITOR:Number = 1 << 3;
	private static var ALUNO:Number = 1 << 4;
	private static var VISITANTE:Number = 256;
	
	//---- Conversões
	private static var ADMINISTRADOR_STRING:String = "Administrador";
	private static var COORDENADOR_STRING:String = "Coordenador";
	private static var PROFESSOR_STRING:String = "Professor";
	private static var MONITOR_STRING:String = "Monitor";
	private static var ALUNO_STRING:String = "Aluno";
	private static var VISITANTE_STRING:String = "Visitante";
	
	//---- Dados
	public var identificacao:String = new String();
	public var login:String = new String();
	public var senha:String = new String();
	public var diaAniversario:String = new String();
	public var mesAniversario:String = new String();
	public var anoAniversario:String = new String();
	public var nome:String = new String();
	public var nomeMae:String = new String();
	public var email:String = new String();
	public var nivel:String = new String();
	public var apelido:String = new String();
	public var turmasProfessor:Array = new Array();
	public var turmasConvidadoProfessor:Array = new Array();
	public var turmasHabilitadoProfessor:Array = new Array();
	public var turmasMonitor:Array = new Array();
	public var turmasConvidadoMonitor:Array = new Array();
	public var turmasHabilitadoMonitor:Array = new Array();
	public var turmasAluno:Array = new Array();
	public var turmasConvidadoAluno:Array = new Array();
	public var turmasHabilitadoAluno:Array = new Array();
	
	//---- Erro
	private var mensagemDeErro:String = new String();
	
//métodos	
	public function c_conta(){

	}
	
	//---- Getters
	public static function getNivelAdministrador():Number{return 1 << 0;}
	public static function getNivelCoordenador():Number{return 1 << 1;}
	public static function getNivelProfessor():Number{return 1 << 2;}
	public static function getNivelMonitor():Number{return 1 << 4;}
	public static function getNivelAluno():Number{return 1 << 3;}
	public static function getNivelVisitante():Number{return 256;}
	
	//---- Setters
	public function adicionarNivel(nivel_param:Number){
		var nivelNumber = parseInt(nivel);
		nivelNumber = nivelNumber | nivel_param;
		nivel = nivelNumber.toString();
	}
	
	//---- Estáticos
	public static function criarNivel(nivel_param:Number):String{
		var nivelString:String = new String();
		nivelString = nivel_param.toString();
		return nivelString;
	}
	public function getPermissao():Number{
		var nivelNumber:Number = parseInt(nivel);
		
		if(c_conta.nivelCompativel(nivelNumber, c_conta.getNivelAdministrador())){
			return c_conta.getNivelAdministrador();
		} else if(c_conta.nivelCompativel(nivelNumber, c_conta.getNivelCoordenador())){
			return c_conta.getNivelCoordenador();
		} else if(c_conta.nivelCompativel(nivelNumber, c_conta.getNivelProfessor())){
			return c_conta.getNivelProfessor();
		} else if(c_conta.nivelCompativel(nivelNumber, c_conta.getNivelMonitor())){
			return c_conta.getNivelMonitor();
		} else if(c_conta.nivelCompativel(nivelNumber, c_conta.getNivelAluno())){
			return c_conta.getNivelAluno();
		} else if(c_conta.nivelCompativel(nivelNumber, c_conta.getNivelVisitante())){
			return c_conta.getNivelVisitante();
		}
	}
	public static function nivelPossuiPermissaoDe(nivel_param:Number, permissao_param:Number):Boolean{
		if((nivel_param & permissao_param) != 0){
			return true;
		} else {
			return false;
		}
	}
	public static function nivelCompativel(nivel_param:Number, nivel_base_param:Number):Boolean{
		var possuiPermissaoAdministrador:Boolean = false;
		var possuiPermissaoCoordenador:Boolean = false;
		var possuiPermissaoProfessor:Boolean = false;
		var possuiPermissaoMonitor:Boolean = false;
		var possuiPermissaoAluno:Boolean = false;
		var possuiPermissaoVisitante:Boolean = false;
		var testeContraAdministrador:Boolean = false;
		var testeContraCoordenador:Boolean = false;
		var testeContraProfessor:Boolean = false;
		var testeContraMonitor:Boolean = false;
		var testeContraAluno:Boolean = false;
		var testeContraVisitante:Boolean = false;
		
		if(nivel_base_param & getNivelAdministrador()){
			testeContraAdministrador = true;
		}
		if(nivel_base_param & getNivelCoordenador()){
			testeContraCoordenador = true;
		}
		if(nivel_base_param & getNivelProfessor()){
			testeContraProfessor = true;
		}
		if(nivel_base_param & getNivelMonitor()){
			testeContraMonitor = true;
		}
		if(nivel_base_param & getNivelAluno()){
			testeContraAluno = true;
		}
		if(nivel_base_param & getNivelVisitante()){
			testeContraVisitante = true;
		}
		
		if(nivel_param & getNivelAdministrador()){
			possuiPermissaoAdministrador = true;
		}
		if(nivel_param & getNivelCoordenador()){
			possuiPermissaoCoordenador = true;
		}
		if(nivel_param & getNivelProfessor()){
			possuiPermissaoProfessor = true;
		}
		if(nivel_param & getNivelMonitor()){
			possuiPermissaoMonitor = true;
		}
		if(nivel_param & getNivelAluno()){
			possuiPermissaoAluno = true;
		}
		if(nivel_param & getNivelVisitante()){
			possuiPermissaoVisitante = true;
		}
	
		if(testeContraAdministrador){
			if(possuiPermissaoAdministrador){
				return true;
			} else {
				return false;
			}
		} else if(testeContraCoordenador){
			if(possuiPermissaoAdministrador
			   || possuiPermissaoCoordenador){
				return true;
			} else {
				return false;
			}
		} else if(testeContraProfessor){
			if(possuiPermissaoAdministrador
			   || possuiPermissaoCoordenador
			   || possuiPermissaoProfessor){
				return true;
			} else {
				return false;
			}
		} else if(testeContraMonitor){
			if(possuiPermissaoAdministrador
			   || possuiPermissaoCoordenador
			   || possuiPermissaoProfessor
			   || possuiPermissaoMonitor){
				return true;
			} else {
				return false;
			}
		} else if(testeContraAluno){
			if(possuiPermissaoAdministrador
			   || possuiPermissaoCoordenador
			   || possuiPermissaoProfessor
			   || possuiPermissaoMonitor
			   || possuiPermissaoAluno){
				return true;
			} else {
				return false;
			}
		} else if(testeContraVisitante){
			if(possuiPermissaoAdministrador
			   || possuiPermissaoCoordenador
			   || possuiPermissaoProfessor
			   || possuiPermissaoMonitor
			   || possuiPermissaoAluno
			   || possuiPermissaoVisitante){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function possuiPermissaoDe(permissao_param:Number):Boolean{
		var nivelNumber:Number = parseInt(nivel);
		if((nivelNumber & permissao_param) != 0){
			return true;
		} else {
			return false;
		}
	}
	
	//---- Conversões para String
	public function getDescricaoNivel(){
		var descricaoNivel:String = new String();
		var nivelNumber:Number = parseInt(nivel);
		
		if(possuiPermissaoDe(getNivelAdministrador())){
			descricaoNivel = descricaoNivel.concat(ADMINISTRADOR_STRING.concat(", "));
		}
		if(possuiPermissaoDe(getNivelCoordenador())){
			descricaoNivel = descricaoNivel.concat(COORDENADOR_STRING.concat(", "));
		}
		if(possuiPermissaoDe(getNivelProfessor())){
			descricaoNivel = descricaoNivel.concat(PROFESSOR_STRING.concat(", "));
		}
		if(possuiPermissaoDe(getNivelMonitor())){
			descricaoNivel = descricaoNivel.concat(MONITOR_STRING.concat(", "));
		}
		if(possuiPermissaoDe(getNivelAluno())){
			descricaoNivel = descricaoNivel.concat(ALUNO_STRING.concat(", "));
		}
		if(possuiPermissaoDe(getNivelVisitante())){
			descricaoNivel = descricaoNivel.concat(VISITANTE_STRING);
		}
		
		return descricaoNivel;
	}
	
		
	//---- Formato
	public function validar():Boolean{
		if(parseInt(identificacao) == 0){
			mensagemDeErro = "Erro no Flash.";
			return false;
		}
		//login
		//senha
		if(!(diaAniversario=="00" and mesAniversario=="00" and anoAniversario=="0000")){
			if(parseInt(diaAniversario)<1 or parseInt(diaAniversario)>31){
				mensagemDeErro = "Por favor, verifique o dia do aniversário.";
				return false;
			}
			if(parseInt(mesAniversario)<1 or parseInt(mesAniversario)>12){
				mensagemDeErro = "Por favor, verifique o mês do aniversário.";
				return false;
			}
			if(parseInt(anoAniversario)<1900 or parseInt(anoAniversario)>2100){
				mensagemDeErro = "Por favor, verifique o ano do aniversário.";
				return false;
			}
		}
		//nome
		//nomeMae
		//email
		var nivelNumber:Number = parseInt(nivel);
		if(!nivelCompativel(nivelNumber, ADMINISTRADOR) and
		   !nivelCompativel(nivelNumber, COORDENADOR) and
		   !nivelCompativel(nivelNumber, PROFESSOR) and
		   !nivelCompativel(nivelNumber, MONITOR) and
		   !nivelCompativel(nivelNumber, ALUNO) and
		   !nivelCompativel(nivelNumber, VISITANTE)){
			mensagemDeErro = "Por favor, verifique seu nível.";
			return false;
		}
		//apelido	
		//turmas
		//turmasConvidado
		//turmasHabilitado
		mensagemDeErro = new String();
		return true;
	}
	
	//---- Saída (debug)
	public function paraString():String{
		var conta_string:String = new String;
		
		conta_string += "identificacao: "+identificacao+"\n";
		conta_string += "login: "+login+"\n";
		conta_string += "senha: "+senha+"\n";
		conta_string += "diaAniversario: "+diaAniversario+"\n";
		conta_string += "mesAniversario: "+mesAniversario+"\n";
		conta_string += "anoAniversario: "+anoAniversario+"\n";
		conta_string += "nome: "+nome+"\n";
		conta_string += "nomeMae: "+nomeMae+"\n";
		conta_string += "email: "+email+"\n";
		conta_string += "nivel: "+nivel+"\n";
		conta_string += "apelido: "+apelido+"\n";
		conta_string += "turmasProfessor: "+turmasProfessor.toString()+"\n";
		conta_string += "turmasConvidadoProfessor: "+turmasConvidadoProfessor.toString()+"\n";
		conta_string += "turmasHabilitadoProfessor: "+turmasHabilitadoProfessor.toString()+"\n";
		conta_string += "turmasMonitor: "+turmasMonitor.toString()+"\n";
		conta_string += "turmasConvidadoMonitor: "+turmasConvidadoMonitor.toString()+"\n";
		conta_string += "turmasHabilitadoMonitor: "+turmasHabilitadoMonitor.toString()+"\n";
		conta_string += "turmasAluno: "+turmasAluno.toString()+"\n";
		conta_string += "turmasConvidadoAluno: "+turmasConvidadoAluno.toString()+"\n";
		conta_string += "turmasHabilitadoAluno: "+turmasHabilitadoAluno.toString()+"\n";
		
		return conta_string;
	}
	
	//---- Erro
	public function getMensagemErro(){
		return mensagemDeErro;
	}
}

