class c_planeta{
//dados
	//---- Tipo
	public static var ESCOLA:String = "0";
	public static var ANO:String = "1";
	public static var TURMA:String = "2";
	private var ESCOLA_STRING:String = "Escola";
	private var ANO_STRING:String = "Ano";
	private var TURMA_STRING:String = "Turma";

	//---- Acesso
	public var ACESSO_NENHUM:Number = 0;//binário ...00000000 
	public var ACESSO_PROFESSORES:Number = 1 << 0;//binário ...00000001
	public var ACESSO_ALUNOS:Number = 1 << 1;//binário ...00000010
	public var ACESSO_VISITANTES:Number = 1 << 2;//binário ...00000100
	public var ACESSO_TODOS:Number = 1 << 6;//binário ...01000000
	private var DESCRICAO_ACESSO_DEFAULT:String = "Administrador, Dono";
	private var DESCRICAO_ACESSO_NENHUM:String = "Nenhum.";
	private var DESCRICAO_ACESSO_PROFESSORES:String = "Professores";
	private var DESCRICAO_ACESSO_ALUNOS:String = "Alunos";
	private var DESCRICAO_ACESSO_VISITANTES:String = "Visitantes";
	private var DESCRICAO_ACESSO_TODOS:String = "Todos";

	//---- Edição
	public var EDICAO_ALUNOS_PERMITIDA:String = "1";//binário ...00000001 
	public var EDICAO_ALUNOS_NAO_PERMITIDA:String = "0";//binário ...00000010
	private var DESCRICAO_EDICAO_DEFAULT:String = "Administrador, Dono";
	private var DESCRICAO_EDICAO_ALUNOS:String = "Alunos podem";
	private var DESCRICAO_EDICAO_ALUNOS_NAO:String = "Alunos não podem";

	//---- Dados
	public var identificacao:String = new String();
	public var nome:String = new String();
	public var tipo:String = new String();
	public var dono:String = new String();
	private var terrenos:Array = new Array();
	public var niveisAcessoPermitido:Number;
	public var niveisEdicaoPermitida:String;
	
	/*
	* Identificação do chat deste planeta no banco de dados.
	*/
	private var id_chat:String = new String();
	
	/*
	* A aparência do terreno. Deve conter um dos seguintes valores:
	* TIPO_VERDE, TIPO_GRAMA, TIPO_LAVA, TIPO_NEVE ou TIPO_URBANO.
	* Estes valores estão declarados em c_terreno_bd.
	*/
	private var aparencia:String;

	//---- Erro
	private var DESCRICAO_ERRO:String = "Erro";
	private var mensagemDeErro:String = new String();

//métodos
	public function c_planeta(){
		identificacao = new String();
		nome = new String();
		tipo = new String();
		dono = new String();
		terrenos = new Array();
		//niveisAcessoPermitido;
		//niveisEdicaoPermitida;
	}
	
	
	public function paraString():String{
		return "identificacao:"+identificacao+"\n"
			+"nome:"+nome+"\n"
			+"tipo:"+tipo+"\n"
			+"dono:"+dono+"\n"
			+"terrenos:"+terrenos.toString()+"\n"
			+"niveisAcessoPermitido:"+niveisAcessoPermitido+"\n"
			+"niveisEdicaoPermitida:"+niveisEdicaoPermitida+"\n";
	}
	
	/*
	* @param id_chat_param Id do chat.
	*/
	public function setIdChat(id_chat_param:String):Void{
		id_chat = id_chat_param;
	}
	/*
	* @return Id do chat.
	*/
	public function getIdChat():String{
		return id_chat;
	}

	//---- Terrenos
	public function getTerrenos():Array{
		return terrenos;
	}
	public function getIdsTerrenos():Array{
		var terreno:c_terreno;
		var indice:Number = 0;
		var ids:Array = new Array();
		while (indice < terrenos.length){
			ids.push(terrenos[indice].getIdentificacao());
			indice++;
		}
		return ids;
	}
	public function getNomesTerrenos():Array{
		var terreno:c_terreno;
		var indice:Number = 0;
		var nomes:Array = new Array();
		while (indice < terrenos.length){
			nomes.push(terrenos[indice].getNome());
			indice++;
		}
		return nomes;
	}
	public function setTerrenos(terrenos_param:Array):Void{
		terrenos = terrenos_param;
	}
	public function adicionarTerreno(terreno_param:c_terreno_bd){
		terrenos.push(terreno_param);
	}
	public function removerTerreno(terreno_param:c_terreno_bd){
		var terreno:c_terreno_bd;
		var encontrou:Boolean = false;
		var indice:Number = 0;
		while (indice < terrenos.length and !encontrou){
			if (terreno_param.igual(terrenos[indice])){
				terrenos.splice(indice,1);
				encontrou = true;
			}
			indice++;
		}
	}
	public function getDescricaoTerrenos():String{
		var descricao:String = new String();
		var indice:Number = 0;

		if (1 <= terrenos.length){
			descricao = terrenos[indice].getNome();
			indice++;
		}
		while (indice < terrenos.length){
			descricao = descricao.concat(", ");
			descricao = descricao.concat(terrenos[indice].getNome());
			indice++;
		}
		descricao = descricao.concat(".");
		return descricao;
	}

	//---- Conversões para String
	public function converterTipoParaString(){
		switch (tipo){
			case c_planeta.ESCOLA :
				tipo = ESCOLA_STRING;
				break;
			case c_planeta.ANO :
				tipo = ANO_STRING;
				break;
			case c_planeta.TURMA :
				tipo = TURMA_STRING;
				break;
		}
	}

	//---- Conversões para número
	public function converterTipoParaNumber(){
		switch (tipo){
			case ESCOLA_STRING :
				tipo = ESCOLA;
				break;
			case ANO_STRING :
				tipo = ANO;
				break;
			case TURMA_STRING :
				tipo = TURMA;
				break;
		}
	}

	//---- Formato
	public function validar():Boolean{
		if (identificacao == undefined or identificacao == new String()){
			mensagemDeErro = "Erro no flash ao validar o planeta.";
			return false;
		}
		if (nome == undefined or nome == new String()){
			mensagemDeErro = "Por favor, verifique o nome do planeta.";
		}
		if (tipo != c_planeta.ESCOLA and tipo != c_planeta.ANO and tipo != c_planeta.TURMA){
			mensagemDeErro = "Por favor, verifique o tipo do planeta.";
			return false;
		}
		if (aparencia != c_terreno_bd.TIPO_VERDE and aparencia != c_terreno_bd.TIPO_GRAMA 
				and aparencia != c_terreno_bd.TIPO_LAVA and aparencia != c_terreno_bd.TIPO_GELO and aparencia != c_terreno_bd.TIPO_URBANO){
			mensagemDeErro = "Por favor, verifique a aparência do planeta.";
			return false;
		}
		//dono 
		mensagemDeErro = new String();
		return true;
	}
	public function getMensagemErro():String{
		return mensagemDeErro;
	}

	//---- Descrições
	public function getDescricaoPermissaoAcesso():String{
		if(tipo == c_planeta.TURMA){
			return getDescricaoPermissaoAcessoTurma();
		} else {
			return DESCRICAO_ACESSO_TODOS.concat(".");
		}
	}
	private function getDescricaoConfigurada(professores_param:Boolean, alunos_param:Boolean, visitantes_param:Boolean):String{
		var descricao:String = new String();
		if(professores_param){
			descricao = descricao.concat(DESCRICAO_ACESSO_PROFESSORES.concat(","));
		}
		if(alunos_param){
			descricao = descricao.concat(DESCRICAO_ACESSO_ALUNOS.concat(","));
		}
		if(visitantes_param){
			descricao = descricao.concat(DESCRICAO_ACESSO_VISITANTES.concat(","));
		}
		descricao = DESCRICAO_ACESSO_DEFAULT.concat(", ".concat(descricao.slice(0, descricao.length - 1).concat(".")));
		return descricao;
	}
	private function getDescricaoPermissaoAcessoTurma():String{
		var descricao:String = new String();
		var todosPermitidos:Boolean = ((niveisAcessoPermitido & ACESSO_TODOS) != 0);
		var professoresPermitidos:Boolean = ((niveisAcessoPermitido & ACESSO_PROFESSORES) != 0);
		var alunosPermitidos:Boolean = ((niveisAcessoPermitido & ACESSO_ALUNOS) != 0);
		var visitantesPermitidos:Boolean = ((niveisAcessoPermitido & ACESSO_VISITANTES) != 0);
		var nenhumPermitido:Boolean = ((niveisAcessoPermitido & ACESSO_NENHUM) != 0);

		switch (true){
			case (todosPermitidos) :
				descricao = DESCRICAO_ACESSO_TODOS.concat(".");
				break;
			case (professoresPermitidos || alunosPermitidos || visitantesPermitidos) :
				descricao = getDescricaoConfigurada(professoresPermitidos, alunosPermitidos, visitantesPermitidos);
				break;
			case (nenhumPermitido) :
				descricao = DESCRICAO_ACESSO_DEFAULT.concat(".");
				break;
			default :
				descricao = DESCRICAO_ERRO.concat(".");
		}
		return descricao;
	}
	public function getDescricaoPermissaoEdicao():String{
		if(tipo == TURMA){
			return getDescricaoPermissaoEdicaoTurma();
		} else {
			return DESCRICAO_EDICAO_ALUNOS_NAO.concat(".");
		}
	}
	private function getDescricaoPermissaoEdicaoTurma():String{
		var descricao:String = new String();
		var alunosPermitidos:Boolean = ((niveisEdicaoPermitida & EDICAO_ALUNOS_PERMITIDA) != 0);
		var alunosNaoPermitidos:Boolean = ((niveisEdicaoPermitida & EDICAO_ALUNOS_NAO_PERMITIDA) != 0);
		
		if (alunosPermitidos){
			descricao = DESCRICAO_EDICAO_ALUNOS.concat(".");
		}
		else if (alunosNaoPermitidos){
			descricao = DESCRICAO_EDICAO_ALUNOS_NAO.concat(".");
		}
		else{
			descricao = DESCRICAO_ERRO.concat(".");
		}
		return descricao;
	}
	
	
	/*
	* Atribui valor à aparência.
	* O valor da aparência deve ser um daqueles definidos em c_terreno_bd (uma constante).
	*/
	public function setAparencia(aparencia_param:String):Void{
		aparencia = aparencia_param;
	}
	/*
	* Retorna a aparência deste planeta.
	*/
	public function getAparencia():String{
		return aparencia;
	}
	/*
	* Uma string com o nome da aparência que o planeta.
	*/
	public function getNomeAparencia():String{
		switch(getAparencia()){
			case c_terreno_bd.TIPO_VERDE:  return "Grama";
				break;
			case c_terreno_bd.TIPO_GRAMA: return "Grama";
				break;
			case c_terreno_bd.TIPO_LAVA: return "Lava";
				break;
			case c_terreno_bd.TIPO_GELO: return "Gelo";
				break;
			case c_terreno_bd.TIPO_URBANO: return "Urbano";
				break;		
			case c_terreno_bd.TIPO_QUARTO: return "Quarto";
				break;
			default: return "Sem aparência definida.";
				break;
		}
	}
	
	
}