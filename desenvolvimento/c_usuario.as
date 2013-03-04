class c_usuario {
	//público, evitará criação de funções extras de acoplamento.
	public var personagem						: c_personagem = null;
	
	//valores default setados, mas seu verdadeiro valor é recebido do BD.
	
//eD - funcionando a partir de 18/12/08
	public var personagem_id 					: Number = 0;
	public var identificacao					: Number = 0;
	//public var personagem_avatar_1 				: Number = 0;
	public var ultima_atualizacao 				: Number = 0;
	public var usuario_nivel 					: Number = 0;
	public var usuario_grupo_base		 		: Number = 0;
	public var personagem_login 				: String = "";
	public var personagem_aniver				: String = "";
    public var personagem_linha_chat        	: Number = 0;    
    public var personagem_fala 					: String = "";
    public var private_chat                 	: String = "";
	public var pos_private_chat             	: Number = 0;
	public var lista_contatos               	: String = "";
	public var personagem_animacao				: String = "nenhuma";
	//public var grupo_chat                   	: String = "";
	//public var tamanho_grupo_chat           	: Number = 0;
	public var nome_escola:String = undefined;
	
	private var personagemDefinido				: Boolean = false;
	
	/*
	* Indica o sexo do usuário.
	*/
	private var sexo							: String = new String();
	
	/*
	* ID no BD do terreno visitado pelo personagem antes do atual.
	*/
	public var ultimo_terreno_id:Number;
	
	/*
	* Esta é a ID no BD do terreno que é o quarto do personagem.
	*/
	public var quarto_id:String;
	
	/*
	* Turmas deste usuário.
	*/
	private var turmas:Array;
	
	/*
	* Imagem no banco de dados do terreno do quarto deste personagem.
	*/
	private var imagemTerrenoQuarto:c_terreno_bd;
	
//métodos
	public function c_usuario(){
	}

	/*
	* Somente um personagem pode ser definido por usuário. este será o primeiro e único. 
	* Não é possível substituir o personagem definido.
	*/
	public function definirPersonagem(personagem_param:c_personagem):Boolean{
		if(!personagemDefinido){
			personagem = personagem_param;
			personagemDefinido = true;
			return true;
		}
		else{
			return false;
		}
	}

	public function getPermissao():Number{
		var conta_exemplo = new c_conta();
		
		if(c_conta.nivelCompativel(usuario_nivel, c_conta.getNivelAdministrador())){
			return c_conta.getNivelAdministrador();
		} else if(c_conta.nivelCompativel(usuario_nivel, c_conta.getNivelCoordenador())){
			return c_conta.getNivelCoordenador();
		} else if(c_conta.nivelCompativel(usuario_nivel, c_conta.getNivelProfessor())){
			return c_conta.getNivelProfessor();
		} else if(c_conta.nivelCompativel(usuario_nivel, c_conta.getNivelMonitor())){
			return c_conta.getNivelMonitor();
		} else if(c_conta.nivelCompativel(usuario_nivel, c_conta.getNivelAluno())){
			return c_conta.getNivelAluno();
		} else if(c_conta.nivelCompativel(usuario_nivel, c_conta.getNivelVisitante())){
			return c_conta.getNivelVisitante();
		}
	}
	public function possuiPermissaoDe(permissao_param:Number):Boolean{
		if(c_conta.nivelCompativel(usuario_nivel, permissao_param)){
			return true;
		} else {
			return false;
		}
	}
	
	/*
	* Define as turmas deste usuário.
	* @param turmas_param Array de objetos do tipo c_turma.
	*/
	public function setTurmas(turmas_param:Array):Void{
		turmas = turmas_param;
	}
	/*
	* @return Array de objetos de tipo c_turma, as turmas deste usuário.
	*/
	public function getTurmas():Array{
		return turmas;
	}
	
	public function setImagemTerrenoQuarto(imagemTerreno_param:c_terreno_bd){
		imagemTerrenoQuarto = imagemTerreno_param;
	}
	public function getImagemTerrenoQuarto():c_terreno_bd{
		return imagemTerrenoQuarto;
	}
	
	
	
	
    
}