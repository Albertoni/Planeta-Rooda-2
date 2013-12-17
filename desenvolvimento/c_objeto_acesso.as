/*
* Classe que representa um objeto de acesso.
* Estes objetos são aqueles que direcionam o usuário a um link quando colidem com o personagem.
*/
class c_objeto_acesso extends c_objeto_colisao{
//dados	
	/*
	* O link deste símbolo na biblioteca, segundo o tipo de acesso.
	*/
	public static var LINK_BIBLIOTECA_CASA:String = "objeto_link_acesso";
	public static var LINK_BIBLIOTECA_PREDIO:String = "acesso_predio";
	public static var LINK_BIBLIOTECA_PONTE_TERRENO:String = "acesso_ponte_terreno";
	public static var LINK_BIBLIOTECA_PORTA_TERRENO:String = "acesso_porta_terreno";
	public static var LINK_BIBLIOTECA_PONTE_TERRENO_GELO:String = "acesso_ponte_terreno_gelo";
	
	/*
	* Tipo deste objeto de acesso, possibilidades enumeradas abaixo.
	*/
	private var tipo:String;
	
	/*
	* Tipos de objetos de acesso.
	*/
	public static var TIPO_CASA:String = "1";
	public static var TIPO_PREDIO:String = "2";
	public static var TIPO_PONTE_TERRENO:String = "3";
	public static var TIPO_PORTA_TERRENO:String = "4";
	public static var TIPO_PONTE_TERRENO_GELO:String = "5";
	
	/***/
	public static var LINK_BASE:String = "";
	
	/*
	* Toda casa possui um link para uma página web.
	* Aqui ficam os links das casas.
	*/
	public static var LINK_CASA_BIBLIOTECA:String;
	public static var LINK_CASA_BLOG:String;
	public static var LINK_CASA_FORUM:String;
	public static var LINK_CASA_PORTFOLIO:String;
	public static var LINK_CASA_APARENCIA:String;
	public static var LINK_CASA_ARTE:String;
	public static var LINK_CASA_PERGUNTA:String;
	public static var LINK_CASA_AULAS:String;
	public static var LINK_CASA_PLAYER:String;
	public static var LINK_CASA_GERENCIAMENTO_FUNCIONALIDADES_TURMAS:String;

	/*
	* Links de páginas do usuário.
	*/
	public static var LINK_TELA_PC:String;
	public static var LINK_BLOG_USUARIO:String;
	public static var LINK_BIBLIOTECA_USUARIO:String;
	public static var LINK_APARENCIA_USUARIO:String;

	/*
	* Link acessado no momento em que o personagem muda de terreno.
	*/
	public static var LINK_MUDANCA_TERRENO:String = "index.php?terreno_id=";

	/*
	* O link para o qual o usuário é redirecionado quando há colisão.
	*/
	private var link:String = new String();
	
//métodos	
	public static function inicializar(){
		c_objeto_acesso.LINK_BASE					= "../";
		c_objeto_acesso.LINK_CASA_BIBLIOTECA 		= LINK_BASE+"funcionalidades/biblioteca/biblioteca.php?";
		c_objeto_acesso.LINK_CASA_BLOG 				= LINK_BASE+"funcionalidades/blog/blog_inicio.php?";
		c_objeto_acesso.LINK_CASA_FORUM 			= LINK_BASE+"funcionalidades/forum/forum.php?";
		c_objeto_acesso.LINK_CASA_PORTFOLIO 		= LINK_BASE+"funcionalidades/portfolio/portfolio.php?";
		c_objeto_acesso.LINK_CASA_APARENCIA 		= LINK_BASE+"funcionalidades/criar_personagem/criar_personagem.php?id_char_as=";
		c_objeto_acesso.LINK_CASA_ARTE 				= LINK_BASE+"funcionalidades/arte/planeta_arte2.php?";
		c_objeto_acesso.LINK_CASA_PERGUNTA 			= LINK_BASE+"funcionalidades/pergunta/planeta_pergunta.php?";
		c_objeto_acesso.LINK_CASA_AULAS 			= LINK_BASE+"funcionalidades/aulas/planeta_aulas.php?";
		c_objeto_acesso.LINK_CASA_PLAYER 			= LINK_BASE+"funcionalidades/roodaplayer/index.php?";
		c_objeto_acesso.LINK_CASA_GERENCIAMENTO_FUNCIONALIDADES_TURMAS = LINK_BASE+"funcionalidades/gerenciamento_funcionalidades_turmas/index.php?";
		c_objeto_acesso.LINK_TELA_PC 				= LINK_BASE+"desenvolvimento/tela_pc/tela_pcaluno.php?";
		c_objeto_acesso.LINK_BLOG_USUARIO 			= LINK_BASE+"funcionalidades/blog/blog.php?blog_id=meu_blog";
		c_objeto_acesso.LINK_BIBLIOTECA_USUARIO 	= LINK_BASE+"funcionalidades/biblioteca/biblioteca.php?minha_biblioteca=1";
		c_objeto_acesso.LINK_APARENCIA_USUARIO		= LINK_CASA_APARENCIA;
	}
	
	/*
	* Dado um id de quarto, retorna o link para acessa-lo.
	* 			Deve ser retirada. Suas chamadas devem ser substituídas por getLinkAcessoTerreno.
	*/
	public static function getLinkAcessoQuarto(id_quarto_param:String):String{
		return getLinkAcessoTerreno(id_quarto_param);
	}
	
	/*
	* Dado um id de terreno (não quarto), retorna o link para acessa-lo.
	* @param id_terreno_param Id do terreno que será acessado.
	*/
	public static function getLinkAcessoTerreno(id_terreno_param:String):String{
		return LINK_MUDANCA_TERRENO.concat(id_terreno_param);
	}
	
	/*
	* Dado um tipo, retorna uma ID de biblioteca apropriada.
	*/
	public static function getLinkBiblioteca(tipo_param:String):String{
		switch(tipo_param){
			case TIPO_CASA: return LINK_BIBLIOTECA_CASA;
				break;
			case TIPO_PREDIO: return LINK_BIBLIOTECA_PREDIO;
				break;
			case TIPO_PONTE_TERRENO: return LINK_BIBLIOTECA_PONTE_TERRENO;
				break;
			case TIPO_PORTA_TERRENO: return LINK_BIBLIOTECA_PORTA_TERRENO;
				break;
			case TIPO_PONTE_TERRENO_GELO: return LINK_BIBLIOTECA_PONTE_TERRENO_GELO;
				break;
			default: return LINK_BIBLIOTECA_CASA;
				break;
		} 
	}
	
	/*
	* Dado um MovieClip, encontra uma id de c_objeto_acesso que ainda não esteja sendo usada nele.
	* Uso um nome base para diferencia-lo de outros objetos, como casas, predios, árvores e personagens.
	*/
	public static function criarNome(id_param:String):String{
		return LINK_BIBLIOTECA_CASA+id_param;
	}
	
	/*
	* Define o link.
	*/
	public function definirLink(link_param:String):Void{
		link = link_param;
	}
	
	/*
	* Retorna o link.
	*/
	public function recuperarLink():String{
		return link;
	}
	
	/*
	* Define o tipo.
	*/
	public function definirTipo(tipo_param:String):Void{
		tipo = tipo_param;
	}
	
	/*
	* Retorna o tipo.
	*/
	public function recuperarTipo():String{
		return tipo;
	}
	
	public function getNome():String{
		return _name;
	}
	
}