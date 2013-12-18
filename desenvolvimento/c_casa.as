import flash.geom.Point;

/*
* Casas são implementadas no símbolo "casa" da biblioteca.
* Este símbolo é constituído de outros, um frame para cada tipo de casa (segundo seu link).
* Cada frame possui um símbolo diferente de casa. No entanto, cada um destes símbolos possui vários frames.
* Os diferentes frames desses símbolos possuem as mesmas casas, que diferem somente em complementos.
*/
class c_casa extends c_objeto_editavel{
//dados
	/*
	* Tipos de casas quanto aos links.
	* Estes dados são usados para comunicação com o BD.
	* Assim, se no BD um casa for do tipo 4, ela é uma CASA_PORTFOLIO.
	*/
	public static var TIPO_BIBLIOTECA:Number = 1;
	public static var TIPO_BLOG:Number = 2;
	public static var TIPO_FORUM:Number = 3;
	public static var TIPO_PORTFOLIO:Number = 4;
	public static var TIPO_APARENCIA:Number = 5;
	public static var TIPO_ARTE:Number = 6;
	public static var TIPO_PERGUNTA:Number = 7;
	public static var TIPO_AULA:Number = 8;
	public static var TIPO_PLAYER:Number = 9;

	/*
	* Link para o símbolo da biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "casa";

	/*
	* Atualmente, as casas são implementadas em um único movieclip, em que cada frame corresponde a um tipo de casa diferente.
	*/
	private static var FRAME_CASA_BIBLIOTECA:Number = 1;
	private static var FRAME_CASA_BLOG:Number = 2;
	private static var FRAME_CASA_FORUM:Number = 3;
	private static var FRAME_CASA_PORTFOLIO:Number = 4;
	private static var FRAME_CASA_APARENCIA:Number = 5;
	private static var FRAME_CASA_ARTE:Number = 6;
	private static var FRAME_CASA_PERGUNTA:Number = 7;
	private static var FRAME_CASA_AULA:Number = 8;
	private static var FRAME_CASA_PLAYER:Number = 9;
	
	/*
	* Tipos de casas quanto ao terreno a que se destinam.
	* Estes dados são usados para comunicação com o BD.
	*/
	public static var TIPO_TERRENO_DEFAULT:Number = 1;
	public static var TIPO_TERRENO_NEVE:Number = 2;
	
	/*
	* Posição do acesso.
	*/
	private var posicaoAcesso:Point = undefined;
	
	/*
	* Cada frame do movieclip que possui todas as casas possui por sua vez dois frames.
	* O primeiro é uma aparência default, e o segundo é a aparência para o terreno neve.
	*/
	private static var FRAME_TIPO_TERRENO_DEFAULT:Number = 1;
	private static var FRAME_TIPO_TERRENO_NEVE:Number = 2;

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
		if(this['acessoQueVaiSerDeletado'] != undefined){
			posicaoAcesso = new Point(this['acessoQueVaiSerDeletado']._x, this['acessoQueVaiSerDeletado']._y);
			this['acessoQueVaiSerDeletado'].removeMovieClip();
			this['acessoQueVaiSerDeletado'] = undefined;
		}
	 }
	
	/*
	* @return Posição indicada para um objeto de acesso.
	*/
	public function getPosicaoAcesso():Point{
		return posicaoAcesso;
	}
	
	/*
	* Cria o nome de uma casa a partir de seu id.
	*/
	public static function criarNome(id_param:Number):String{
		return "objeto_link"+id_param;
	}

	/*
	* Dado um frame de casa, retorna o tipo que a casa naquele frame representa.
	* @param frame_param Um número, que representa o frame de uma casa.
	* @return Um número, o tipo de casa que corresponde ao frame dado.
	*/
	public static function frameParaTipo(frame_param:Number):Number{
		var tipo:Number = TIPO_BIBLIOTECA;
			
		if(frame_param == c_casa.FRAME_CASA_BIBLIOTECA){
			tipo = TIPO_BIBLIOTECA;
		} else if(frame_param == c_casa.FRAME_CASA_BLOG){
			tipo = TIPO_BLOG;
		} else if(frame_param == c_casa.FRAME_CASA_FORUM){
			tipo = TIPO_FORUM;
		} else if(frame_param == c_casa.FRAME_CASA_PORTFOLIO){
			tipo = TIPO_PORTFOLIO;
		} else if(frame_param == c_casa.FRAME_CASA_APARENCIA){
			tipo = TIPO_APARENCIA;
		} else if(frame_param == c_casa.FRAME_CASA_ARTE){
			tipo = TIPO_ARTE;
		} else if(frame_param == c_casa.FRAME_CASA_PERGUNTA){
			tipo = TIPO_PERGUNTA;
		} else if(frame_param == c_casa.FRAME_CASA_AULA){
			tipo = TIPO_AULA;
		} else if(frame_param == c_casa.FRAME_CASA_PLAYER){
			tipo = TIPO_PLAYER;
		}
			
/*			
		var aviso:String="frame_param="+frame_param+"\n";
			//Acredite. Um switch não funciona aqui.
			//Ok, você não acredita, certo? Descomente e execute o código abaixo...
			
		switch(frame_param){
			case c_casa.FRAME_CASA_BIBLIOTECA: tipo = TIPO_BIBLIOTECA; aviso="Entrei em 1 \n";
				break;
			case c_casa.FRAME_CASA_BLOG: tipo = TIPO_BLOG;aviso="Entrei em 2 \n";
				break;
			case c_casa.FRAME_CASA_FORUM: tipo = TIPO_FORUM;aviso="Entrei em 3 \n";
				break;
			case c_casa.FRAME_CASA_PORTFOLIO: tipo = TIPO_PORTFOLIO;aviso="Entrei em 4 \n";
				break;
			case c_casa.FRAME_CASA_APARENCIA: tipo = TIPO_APARENCIA;aviso="Entrei em 5 \n";
				break;
			case c_casa.FRAME_CASA_ARTE: tipo = TIPO_ARTE;aviso="Entrei em 6 \n";
				break;
			case c_casa.FRAME_CASA_PERGUNTA: tipo = TIPO_PERGUNTA;aviso="Entrei em 7 \n";
				break;
			case c_casa.FRAME_CASA_AULA: tipo = TIPO_AULA;aviso="Entrei em 8 \n";
				break;
			default : aviso="Entrei em default \n";
		}
			aviso+= "Recebi="+frame_param+", Conclui="+tipo;
			aviso+= "\n"+c_casa.FRAME_CASA_BIBLIOTECA+","+TIPO_BIBLIOTECA;
			aviso+= "\n"+c_casa.FRAME_CASA_BLOG+","+TIPO_BLOG;
			aviso+= "\n==1?"+(frame_param==1);
			aviso+= "\n==2?"+(frame_param==2);
			aviso+= "\n==3?"+(frame_param==3);
			aviso+= "\n==4?"+(frame_param==4);
			aviso+= "\n==5?"+(frame_param==5);
			aviso+= "\n==6?"+(frame_param==6);
			aviso+= "\n==7?"+(frame_param==7);
			aviso+= "\n==8?"+(frame_param==8);
			aviso+= "\n==1FRAME?"+(frame_param==c_casa.FRAME_CASA_BIBLIOTECA);
			aviso+= "\n==2FRAME?"+(frame_param==c_casa.FRAME_CASA_BLOG);
			aviso+= "\n==3FRAME?"+(frame_param==c_casa.FRAME_CASA_FORUM);
			aviso+= "\n==4FRAME?"+(frame_param==c_casa.FRAME_CASA_PORTFOLIO);
			aviso+= "\n==5FRAME?"+(frame_param==c_casa.FRAME_CASA_APARENCIA);
			aviso+= "\n==6FRAME?"+(frame_param==c_casa.FRAME_CASA_ARTE);
			aviso+= "\n==7FRAME?"+(frame_param==c_casa.FRAME_CASA_PERGUNTA);
			aviso+= "\n==8FRAME?"+(frame_param==c_casa.FRAME_CASA_AULA);
			c_aviso_com_ok.mostrar(aviso);*/
		return tipo;
	}

	/*
	* Define o tipo do desenho da casa.
	* Deve receber como parâmetro uma das constantes definidas no início deste arquivo.
	* Caso receba um dado não esperado, definirá a casa como biblioteca.
	*/
	public function definirTipoLink(tipo_param:Number):Void{
		switch(tipo_param){
			case TIPO_BIBLIOTECA: gotoAndStop(FRAME_CASA_BIBLIOTECA);
				break;
			case TIPO_BLOG: gotoAndStop(FRAME_CASA_BLOG);
				break;
			case TIPO_FORUM: gotoAndStop(FRAME_CASA_FORUM);
				break;
			case TIPO_PORTFOLIO: gotoAndStop(FRAME_CASA_PORTFOLIO);
				break;
			case TIPO_APARENCIA: gotoAndStop(FRAME_CASA_APARENCIA);
				break;
			case TIPO_ARTE: gotoAndStop(FRAME_CASA_ARTE);
				break;
			case TIPO_PERGUNTA: gotoAndStop(FRAME_CASA_PERGUNTA);
				break;
			case TIPO_AULA: gotoAndStop(FRAME_CASA_AULA);
				break;
			case TIPO_PLAYER: gotoAndStop(FRAME_CASA_PLAYER);
				break;
			default: gotoAndStop(FRAME_CASA_BIBLIOTECA);
				break;
		}
	}
	
	/*
	* Define o tipo do desenho da casa segundo o terreno (isto é, se possui neve, etc).
	* Deve receber como parâmetro uma das constantes definidas na classe c_terreno.
	* Caso receba um dado não esperado, definirá a casa default.
	*/
	public function definirTipoTerreno(tipo_terreno_param:String){
		switch(tipo_terreno_param){
			case c_terreno_bd.TIPO_VERDE: definirTipoComplemento(TIPO_TERRENO_DEFAULT);
				break;
			case c_terreno_bd.TIPO_GRAMA: definirTipoComplemento(TIPO_TERRENO_DEFAULT);
				break;
			case c_terreno_bd.TIPO_LAVA: definirTipoComplemento(TIPO_TERRENO_DEFAULT);
				break;
			case c_terreno_bd.TIPO_GELO: definirTipoComplemento(TIPO_TERRENO_NEVE);
				break;
			case c_terreno_bd.TIPO_URBANO: definirTipoComplemento(TIPO_TERRENO_DEFAULT);
				break;
			default: definirTipoComplemento(TIPO_TERRENO_DEFAULT);
				break;
		}
	}
	
	/*
	* Define o tipo do desenho da casa adicionando complementos (isto é, se possui neve, etc).
	* Deve receber como parâmetro uma das constantes definidas no início deste arquivo.
	* Caso receba um dado não esperado, definirá a casa default.
	*/
	private function definirTipoComplemento(tipo_param:Number):Void{
		switch(tipo_param){
			case TIPO_TERRENO_DEFAULT: this['casa'].gotoAndStop(FRAME_TIPO_TERRENO_DEFAULT);
				break;
			case TIPO_TERRENO_NEVE: this['casa'].gotoAndStop(FRAME_TIPO_TERRENO_NEVE);
				break;
			default: this['casa'].gotoAndStop(FRAME_TIPO_TERRENO_DEFAULT);
				break;
		}
	}
	
	/*
	* Retorna o link desta casa, dependendo de seu tipo.
	* Caso haja inconsistência nos dados desta classe, retornará uma nova string.
	*/
	public function getLink():String{
		switch(_currentframe){
			case FRAME_CASA_BIBLIOTECA: return c_objeto_acesso.LINK_CASA_BIBLIOTECA + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_BLOG: return c_objeto_acesso.LINK_CASA_BLOG + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_FORUM: return c_objeto_acesso.LINK_CASA_FORUM + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_PORTFOLIO: return c_objeto_acesso.LINK_CASA_PORTFOLIO + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_APARENCIA: return c_objeto_acesso.LINK_CASA_APARENCIA + _root.personagem_status.getIdentificacaoBancoDeDados();
				break;
			case FRAME_CASA_ARTE: return c_objeto_acesso.LINK_CASA_ARTE + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_PERGUNTA: return c_objeto_acesso.LINK_CASA_PERGUNTA + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_AULA: return c_objeto_acesso.LINK_CASA_AULAS + getArgumentoPlanetaURL();
				break;
			case FRAME_CASA_PLAYER: return c_objeto_acesso.LINK_CASA_PLAYER + getArgumentoPlanetaURL();
				break;
			default: return new String();
				break;
		}
	}

	/*
	* Para um planeta do tipo turma, retorna o argumento necessário para a chamada de uma funcionalidade, especificando a turma.
	* Caso o planeta não seja de turma, retorna um string vazia.
	*/
	private function getArgumentoPlanetaURL():String{
		if(_root.planeta_status.tipo == c_planeta.TURMA){
			return "turma="+_root.turma_status.identificacao;
		} else {
			return new String();
		}
	}

	/*
	* Abre a porta desta casa e a mantém aberta.
	*/
	public function abrirPorta(){
		if (this['portaLink']._currentframe == 1) {
			this['portaLink'].play();			//Faz a porta subir quando o mp se aproxima - Guto - 24.03.10
		}
	}
	
	/*
	* Fecha a porta desta casa e a mantém fechada.
	*/
	public function fecharPorta(){
		if (this['portaLink']._currentframe != 1) {
			this['portaLink'].gotoAndPlay(12);			//Faz a porta subir quando o mp se aproxima - Guto - 24.03.10
		}
	}
	
	/*
	* Deve ser definida em casa classe filha.
	*/
	public function definirTipoAparencia(tipo_aparencia_param:Number):Void{
		definirTipoLink(tipo_aparencia_param);
	}
	
	/*
	* Altera a aparência para a adjacente à direita do mesmo tipo de terreno.
	* No limite, volta para o início (primeira da esquerda para a direita).
	*/
	public function aparencia_seguinte():Void{
		if(_currentframe < FRAME_CASA_PLAYER){
			definirTipoLink(_currentframe + 1);
		} else {
			definirTipoLink(FRAME_CASA_BIBLIOTECA);
		}
	}

	/*
	* Altera a aparência para a adjacente à esquerda do mesmo tipo de terreno.
	* No limite, vai para o fim (última da esquerda para a direita).
	*/
	public function aparencia_anterior():Void{
		if(FRAME_CASA_BIBLIOTECA < _currentframe){
			definirTipoLink(_currentframe - 1);
		} else {
			definirTipoLink(FRAME_CASA_PLAYER);
		}
	}
	
	/*
	* Retorna o nome de indicador desta casa.
	*/
	public function getNomeIndicador():String{
		switch(_currentframe){
			case FRAME_CASA_BIBLIOTECA: return "Biblioteca";
				break;
			case FRAME_CASA_BLOG: return "Blog";
				break;
			case FRAME_CASA_FORUM: return "Fórum";
				break;
			case FRAME_CASA_PORTFOLIO: return "Portfólio";
				break;
			case FRAME_CASA_APARENCIA: return "Aparência";
				break;
			case FRAME_CASA_ARTE: return "Arte";
				break;
			case FRAME_CASA_PERGUNTA: return "Pergunta";
				break;
			case FRAME_CASA_AULA: return "Aulas";
				break;
			case FRAME_CASA_PLAYER: return "Player";
				break;
			default: return "Casa";
				break;
		}
	}
	
	/*
	* @param tipo_param Algum dos tipos definidos nesta classe.
	* @return Descrição sucinta do tipo.
	*/
	public static function tipoParaString(tipo_param:Number):String{
		switch(tipo_param){
			case TIPO_BIBLIOTECA: return "Biblioteca";
				break;
			case TIPO_BLOG: return "Blog";
				break;
			case TIPO_FORUM: return "Fórum";
				break;
			case TIPO_PORTFOLIO: return "Portfólio";
				break;
			case TIPO_APARENCIA: return "Aparência";
				break;
			case TIPO_ARTE: return "Arte";
				break;
			case TIPO_PERGUNTA: return "Pergunta";
				break;
			case TIPO_AULA: return "Aulas";
				break;
			case TIPO_PLAYER: return "Player";
				break;
			default: return "Casa";
				break;
		}
	}
	
}

