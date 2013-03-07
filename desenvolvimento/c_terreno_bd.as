import flash.geom.Point;
/*
* Imagem de um terreno no banco de dados.
*
*/
class c_terreno_bd {
//dados
	/*
	* A ID de um terreno que acaba de ser criado.
	*/
	public static var ID_NOVO:String = "-1";
	
	/*
	* Constantes para acessos às matrizes que guardam os dados dos objetos carregados do BD.
	*/
	public static var MATRIZ_FRAME:Number = 0;
	public static var MATRIZ_TERRENO_X:Number = 1;
	public static var MATRIZ_TERRENO_Y:Number = 2;
	public static var MATRIZ_LINK:Number = 3;
	public static var MATRIZ_ID:Number = 4;
	
	/*
	* Objetos que estão neste terreno. Cada array é acessível com os índices MATRIZ_* acima.
	*/
	private var matriz_parede:Array = new Array();
	private var matriz_objeto_link:Array = new Array();
	private var matriz_predios:Array = new Array();
	
	/*
	* Constantes que indicam o valor ao atribuído ao campo de tipo do terreno.
	* Úteis para comunicações com o banco de dados.
	* Não devem ser alteradas, a menos em caso de mudanças de seus valores no BD.
	*/
	public static var TIPO_VERDE:String = "1";
	public static var TIPO_GRAMA:String = "2";
	public static var TIPO_LAVA:String = "3";
	public static var TIPO_GELO:String = "4";
	public static var TIPO_URBANO:String = "5";
	public static var TIPO_QUARTO:String = "6";

	/*
	* Constantes que indicam o valor atribuído ao campo que define se alunos podem editar este terreno.
	* Úteis para comunicações com o banco de dados.
	* Não devem ser alteradas, a menos em caso de mudanças de seus valores no BD.
	*/
	public static var EDICAO_ALUNOS_PERMITIDA:String = "1";
	public static var EDICAO_ALUNOS_NAO_PERMITIDA:String = "0";

	//---- Dados Antigos...
	public var mensagemLocalizacao      : String = new String();
	public var turma					: String = new String();
	public var colegio					: String = new String();
	public var terreno_solo 			: String = "";
	
	//---- Novos Dados
	/*
	* A identificação (id) deste terreno no BD.
	*/
	private var identificacao:String = new String();
	/*
	* O nome do terreno.
	*/
	private var nome:String = new String();
	/*
	* Planeta ao qual o terreno pertence.
	*/
	private var planeta:c_planeta;
	/*
	* Indica permissões de edição deste terreno.
	* Por enquanto, podem editar um terreno os seguintes usuários:
	*  - Administrador e Dono do terreno sempre;
	*  - Coordenadores e Professores da mesma escola sempre;
	*  - Alunos e Monitores somente se permitido;
	* Deve conter um dos seguintes valores:
	* EDICAO_ALUNOS_PERMITIDA ou EDICAO_ALUNOS_NAO_PERMITIDA
	*/
	private var permissaoParaEditar:String;
	
	/*
	* Esta variável conterá uma mensagem de erro sugerida sempre que algum erro ocorrer em operações desta classe.
	* Um exemplo é durante a operação de validação dos dados.
	*/
	private var mensagemDeErro:String = new String();
	
	/*
	* Id do chat deste terreno banco de dados.
	*/
	private var terreno_chat:String;
	
	
//métodos
	public function c_terreno_bd(){
		matriz_parede = new Array();
		matriz_objeto_link = new Array();
		matriz_predios = new Array();
	}
	
	/*
	* Insere um objeto com o frame, id e a posição passada.
	* O tipo do objeto depende da função utilizada.
	* @param id_param Id que o objeto terá.
	* @param frame_param Frame do objeto.
	* @param posicao_param Posição do objeto no terreno.
	*/
	private function criarObjeto(id_param:String, frame_param:Number, posicao_param:Point):Array{
		var objeto:Array = new Array();
		objeto[MATRIZ_FRAME] = frame_param;
		objeto[MATRIZ_TERRENO_X] = posicao_param.x;
		objeto[MATRIZ_TERRENO_Y] = posicao_param.y;
		objeto[MATRIZ_ID] = id_param;
		return objeto;
	}
	public function inserirArvore(id_param:String, frame_param:Number, posicao_param:Point):Void{
		matriz_parede.push(criarObjeto(id_param, frame_param, posicao_param));
	}
	public function inserirCasa(id_param:String, frame_param:Number, posicao_param:Point):Void{
		matriz_objeto_link.push(criarObjeto(id_param, frame_param, posicao_param));
	}
	public function inserirPredio(id_param:String, frame_param:Number, posicao_param:Point):Void{
		matriz_predios.push(criarObjeto(id_param, frame_param, posicao_param));
	}

	/*
	* Modifica o objeto que possua a id passada.
	* @param id_param Id, no banco de dados, do objeto que será modificado.
	* @param frame_param Novo frame, que o objeto terá depois da modificação.
	* @param posicao_param Nova posição, que o objeto terá depois da modificação.
	*/
	public function modificarObjeto(id_param:String, frame_param:Number, posicao_param:Point):Void{
		var i:Number;
		var encontrado:Boolean = false;
		
		i=0;
		while(!encontrado and i<matriz_parede.length){
			if(id_param == matriz_parede[i][MATRIZ_ID]){
				matriz_parede[i][MATRIZ_FRAME] = frame_param;
				matriz_parede[i][MATRIZ_TERRENO_X] = posicao_param.x;
				matriz_parede[i][MATRIZ_TERRENO_Y] = posicao_param.y;
				encontrado = true;
			}
			i++;
		}
		i=0;
		while(!encontrado and i<matriz_objeto_link.length){
			if(id_param == matriz_objeto_link[i][MATRIZ_ID]){
				matriz_objeto_link[i][MATRIZ_FRAME] = frame_param;
				matriz_objeto_link[i][MATRIZ_TERRENO_X] = posicao_param.x;
				matriz_objeto_link[i][MATRIZ_TERRENO_Y] = posicao_param.y;
				encontrado = true;
			}
			i++;
		}
		i=0;
		while(!encontrado and i<matriz_predios.length){
			if(id_param == matriz_predios[i][MATRIZ_ID]){
				matriz_predios[i][MATRIZ_FRAME] = frame_param;
				matriz_predios[i][MATRIZ_TERRENO_X] = posicao_param.x;
				matriz_predios[i][MATRIZ_TERRENO_Y] = posicao_param.y;
				encontrado = true;
			}
			i++;
		}
	}
	
	/*
	* Deleta o objeto que possua a id passada como parâmetro.
	* @param id_param Id, no banco de dados, do objeto que será deletado.
	*/
	public function deletarObjeto(id_param:String):Void{
		var i:Number;
		var encontrado:Boolean = false;
		
		i=0;
		while(!encontrado and i<matriz_parede.length){
			if(id_param == matriz_parede[i][MATRIZ_ID]){
				matriz_parede.splice(i,1);
				encontrado = true;
			}
			i++;
		}
		i=0;
		while(!encontrado and i<matriz_objeto_link.length){
			if(id_param == matriz_objeto_link[i][MATRIZ_ID]){
				matriz_objeto_link.splice(i,1);
				encontrado = true;
			}
			i++;
		}
		i=0;
		while(!encontrado and i<matriz_predios.length){
			if(id_param == matriz_predios[i][MATRIZ_ID]){
				matriz_predios.splice(i,1);
				encontrado = true;
			}
			i++;
		}
	}
	
	/*
	* Planeta ao qual pertence.
	*/
	public function getPlaneta():c_planeta{
		return planeta;
	}
	public function setPlaneta(planeta_param:c_planeta):Void{
		planeta = planeta_param;
	}
	
	/*
	* Dados de objetos que ficam no terreno. Utilizados para inicialização de terrenos.
	*/
	public function getDadosArvores():Array{
		return matriz_parede;
	}
	public function getDadosCasas():Array{
		return matriz_objeto_link;
	}
	public function getDadosPredios():Array{
		return matriz_predios;
	}
	public function setDadosArvores(dadosArvores_param:Array){
		matriz_parede = dadosArvores_param;
	}
	public function setDadosCasas(dadosCasas_param:Array){
		matriz_objeto_link = dadosCasas_param;
	}
	public function setDadosPredios(dadosPredios_param:Array){
		matriz_predios = dadosPredios_param;
	}
	
	/*
	* @return Todos os dados que o objeto possui em forma de String.
	*/
	public function paraString():String{
		return "mensagemLocalizacao:" + mensagemLocalizacao + "\n" +
			   "turma:" + turma + "\n" +
			   "colegio:" + colegio + "\n" +
			   "identificacao:" + identificacao + "\n" +
			   "terreno_solo:" + terreno_solo + "\n" +
			   "terreno_chat:" + terreno_chat + "\n" +
			   "identificacao:" + identificacao + "\n" +
			   "nome:" + nome + "\n" +
			   "permissaoParaEditar:" + permissaoParaEditar + "\n" +
			   "mensagemDeErro:" + mensagemDeErro + "\n";
	}
	
	/*
	* @param tipo_param Tipo de terreno. Um dos declarados nesta classe.
	* @return Link na biblioteca do tipo de terreno parâmetro.
	*/
	public static function getLinkBibliotecaTipo(tipo_param:String):String{
		switch(tipo_param){
			case TIPO_VERDE:  return "terreno_grama_mc";
				break;
			case TIPO_GRAMA: return "terreno_grama_mc";
				break;
			case TIPO_LAVA: return "terreno_lava_mc";
				break;
			case TIPO_GELO: return "terreno_gelo_mc";
				break;
			case TIPO_URBANO: return "terreno_urbano_mc";
				break;		
			case TIPO_QUARTO: return "terreno_quarto_aluno_mc";
				break;
			default: return "terreno_grama_mc";
				break;
		}
	}

	//---- Identificacao
	/*
	* Getter e Setter para identificação (id no BD).
	* Devem ser usadas para comunicação com o BD.
	*/
	public function getIdentificacao():String{
		return identificacao;
	}
	public function setIdentificacao(identificacao_param:String):Void{
		identificacao = identificacao_param;
	}
	
	//---- Identificação
	/*
	* Se true, fará este terreno ser interpretado como um terreno recém criado (modificando sua id do BD aqui no flash).
	* Se false, não fará nada.
	*/
	public function setTerrenoNovo(novo_param:Boolean):Void{
		if(novo_param){
			identificacao = ID_NOVO;
		}
	}
	
	//---- Nome
	/*
	* Getter e Setter para o nome deste terreno.
	*/
	public function getNome():String{
		return nome;
	}
	public function setNome(nome_param:String):Void{
		nome = nome_param;
	}
	/*
	* Retorna a variável que indica as permissões de edição do terreno.
	*/
	public function getPermissaoNecessariaParaEditar():String{
		return permissaoParaEditar;
	}
	/*
	* Indica se alunos (e monitores) podem editar este terreno.
	*/
	public function getPermissaoAlunosEdicao():Boolean{
		if(permissaoParaEditar == EDICAO_ALUNOS_PERMITIDA){
		 	return true;
		} else {
			return false;
		}
	}
	
	//---- Tipo
	
	/*
	* Atribui valor à permissão de edição do terreno.
	* Deve-se utilizar da seguinte forma:
	* setPermissaoParaEditar(terreno.EDICAO_ALUNOS_PERMITIDA)
	*/
	public function setPermissaoParaEditar(permissao_param:String):Void{
		permissaoParaEditar = permissao_param;
	}
	
	//---- Comparação
	/*
	* Define se dois terrenos são iguais, comparando-os por nome e identificação.
	* Dois terrenos não recém criados só serão diferentes se tiverem identificação diferente.
	* Dois terrenos recém criados devem ser distinguidos por seu nome, pois a identificação será definida pelo banco de dados quando forem salvos (se o forem).
	*/
	public function igual(terreno_param:c_terreno_bd):Boolean{
		if(identificacao == terreno_param.getIdentificacao() and nome == terreno_param.getNome()){
			return true;
		}
		else{
			return false;
		}
	}
	
	//---- Formato
	/*
	* Checa os dados de acordo com as possibilidades de valores esperados para seus campos.
	* Enche o atributo mensagemDeErro caso a validação retorne falso.
	*/
	public function validar():Boolean{
		if (identificacao == undefined or identificacao == new String()){
			mensagemDeErro = "Erro no flash ao validar o terreno.";
			return false;
		}
		if (nome == undefined or nome == new String()){
			mensagemDeErro = "Por favor, verifique o nome do terreno.";
			return false;
		}
		//dono 
		mensagemDeErro = new String();
		return true;
	}
	/*
	* Terrenos recém criados não devem ter sua ID testada na validação.
	* Em todos os outros aspectos, esta função é tal qual validar().
	*/
	public function validarSemId():Boolean{
		if (nome == undefined or nome == new String()){
			mensagemDeErro = "Por favor, verifique o nome do terreno.";
			return false;
		}
		//dono 
		mensagemDeErro = new String();
		return true;
	}
	/*
	* Retorna a mensagem do último erro ocorrido nesta classe.
	* Caso não tenha ocorrido erros, retorna uma nova string.
	*/
	public function getMensagemErro():String{
		return mensagemDeErro;
	}
	
	/*
	* @param id_chat_param Id do chat deste terreno.
	*/
	public function setIdChat(id_chat_param:String):Void{
		terreno_chat = id_chat_param;
	}
	/*
	* @return Id do chat deste terreno.
	*/
	public function getIdChat():String{
		return terreno_chat;
	}
	
	
}