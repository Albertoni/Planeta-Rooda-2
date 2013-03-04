class c_turma{
//dados
	/*
	* Definem se as funcionalidades estão habilitadas para esta turma e podem ser acessadas.
	*/
	public var permissao_batePapo:Boolean;
	public var permissao_biblioteca:Boolean;
	public var permissao_blog:Boolean;
	public var permissao_portfolio:Boolean;
	public var permissao_forum:Boolean;
	public var permissao_planetaArte:Boolean;
	public var permissao_planetaPergunta:Boolean;
	public var permissao_aulas:Boolean;
	
	/*
	* Permissões sobre as subfuncionalidades do comunicador.
	*/
	public var habilitado_chatTerrenoParaAlunos:Boolean;
	public var habilitado_chatTerrenoParaMonitores:Boolean;
	public var habilitado_chatTurmaParaAlunos:Boolean;
	public var habilitado_chatTurmaParaMonitores:Boolean;
	public var habilitado_chatAmigoParaAlunos:Boolean;
	public var habilitado_chatAmigoParaMonitores:Boolean;
	public var habilitado_chatPrivadoParaAlunos:Boolean;
	public var habilitado_chatPrivadoParaMonitores:Boolean;

	//---- Dados
	public var identificacao:String = new String();
	public var nome:String = new String();
	public var professor:String = new String();
	public var nomeMaeProfessor:String = new String();
	public var descricao:String = new String();
	public var ano:String = new String();
	public var id_chat:String = new String();
	
	//---- Erro
	private var mensagemDeErro:String = new String();

	/*
	* Professores desta turma. Array em que cada elemento é uma String com um nome de usuário.
	*/
	private var professores:Array;
	
	/*
	* Monitoes desta turma. Array em que cada elemento é uma String com um nome de usuário.
	*/
	private var monitores:Array;
	
	/*
	* Alunos desta turma. Array em que cada elemento é uma String com um nome de usuário.
	*/
	private var alunos:Array;

//métodos
	public function c_turma(){

	}
	
	public function toString():String{
		return "permissao_batePapo "+permissao_batePapo+"\n"
			+"permissao_biblioteca "+permissao_biblioteca+"\n"
			+"permissao_blog "+permissao_blog+"\n"
			+"permissao_portfolio "+permissao_portfolio+"\n"
			+"permissao_forum "+permissao_forum+"\n"
			+"permissao_planetaArte "+permissao_planetaArte+"\n"
			+"permissao_planetaPergunta "+permissao_planetaPergunta+"\n"
			+"permissao_aulas "+permissao_aulas+"\n"
			+"habilitado_chatTerrenoParaAlunos "+habilitado_chatTerrenoParaAlunos+"\n"
			+"habilitado_chatTerrenoParaMonitores "+habilitado_chatTerrenoParaMonitores+"\n"
			+"habilitado_chatTurmaParaAlunos "+habilitado_chatTurmaParaAlunos+"\n"
			+"habilitado_chatTurmaParaMonitores "+habilitado_chatTurmaParaMonitores+"\n"
			+"habilitado_chatAmigoParaAlunos "+habilitado_chatAmigoParaAlunos+"\n"
			+"habilitado_chatAmigoParaMonitores "+habilitado_chatAmigoParaMonitores+"\n"
			+"habilitado_chatPrivadoParaAlunos "+habilitado_chatPrivadoParaAlunos+"\n"
			+"habilitado_chatPrivadoParaMonitores "+habilitado_chatPrivadoParaMonitores+"\n";
	}
	
	/*
	* Definição de relações com pessoas.
	* Recebem um array em que cada elemento é uma String com um nome de usuário.
	*/
	public function definirProfessores(professores_param:Array):Void{
		professores = professores_param;
	}
	public function definirMonitores(monitores_param:Array):Void{
		monitores = monitores_param;
	}
	public function definirAlunos(alunos_param:Array):Void{
		alunos = alunos_param;
	}
	
	/*
	* Getters para pessoas envolvidas nesta turma.
	*/
	public function getProfessores():Array{
		return professores;
	}
	public function getMonitores():Array{
		return monitores;
	}
	public function getAlunos():Array{
		return alunos;
	}
	
	//---- Formato
	public function validar():Boolean{
		if(parseInt(identificacao) == 0){
			mensagemDeErro = "Erro no flash.";
			return false;
		}
		//nome
		//professor
		//nomeMaeProfessor
		//descricao
		//ano
		mensagemDeErro = new String();
		return this.validarSemId();
	}
	public function validarSemId():Boolean{
		//nome
		//professor
		//nomeMaeProfessor
		//descricao
		//ano
		mensagemDeErro = new String();
		return true;
	}
	public function getMensagemErro():String{
		return mensagemDeErro;
	}
	
	
}

