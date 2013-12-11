import flash.geom.Point;

/*
* O prédio que é o meio de acesso dos alunos aos seus quartos.
* Ao acessar este prédio, um usuário acessa seu próprio quarto.
* Não é possível acessar quartos de outros usuários.
*/
class c_predio_alunos extends c_objeto_editavel{
//dados
	/*
	* Link para o símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "predio_alunos";

	/*
	* Posição do acesso.
	*/
	private var posicaoAcesso:Point;
	
//métodos
	/*
	* Ao inicializar este objeto, é necessário definir sua identificação, para que possa ter seus dados 
	* sincronizados com sua imagem no banco de dados.
	*/
	public function inicializar(identificacao_param:String){
		super.inicializar(identificacao_param);
		
		/*
			O motivo do trecho a seguir é que o flash não é capaz de reconhecer o objeto "acessoQueVaiSerDeletado",
		filho deste objeto, como sendo da classe que é. O flah o vê como um MovieClip. 
			Isso acontece só quando este objeto é inserido com attachMovie, o que é necessário.
		*/
		posicaoAcesso = new Point(this['acessoQueVaiSerDeletado']._x, this['acessoQueVaiSerDeletado']._y);
		this['acessoQueVaiSerDeletado'].removeMovieClip();
	}
	
	/*
	* Cria o nome de um prédio a partir de seu id.
	*/
	public static function criarNome(id_param:Number):String{
		return c_predio_alunos.LINK_BIBLIOTECA+id_param;
	}
	
	/*
	* @return Posição indicada para um objeto de acesso.
	*/
	public function getPosicaoAcesso():Point{
		return posicaoAcesso;
	}


}
