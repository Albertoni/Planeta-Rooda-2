/*
* Imagem de uma escola no banco de dados.
*/
class c_escola_bd{
//dados	
	/*
	* Nome da escola.
	*/
	private var nome:String = new String();
	
//métodos	
	public function c_escola_bd(){
		
	}
	
	/*
	* @param nome_param O nome da escola.
	*/
	public function definirNome(nome_param:String):Void{
		nome = nome_param;
	}
	/*
	* @return O nome da escola.
	*/
	public function getNome():String{
		return nome;
	}
	
	/*
	* Valida os dados desta escola.
	* @return true se estiverem válidos.
	*/
	public function validarSemId():Boolean{
		if(nome != undefined){
			return true;
		} else {
			return false;
		}
	}
}