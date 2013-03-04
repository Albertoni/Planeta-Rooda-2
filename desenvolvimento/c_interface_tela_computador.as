import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

/*
* A tela do computador do quarto do aluno.
*
*
*/
class c_interface_tela_computador extends MovieClip{
//dados
	/*
	* Link na biblioteca deste objeto.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_tela_computador";

	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;

	/*
	* Botão que leva ao blog.
	*/
	private var botaoBlog:c_bt_simples;
	private static var NOME_BOTAO_BLOG:String = "btBlog";
	private static var LINK_PARA_BLOG:String = c_objeto_acesso.LINK_BLOG_USUARIO;
	private static var POSICAO_BOTAO_BLOG:Point = new Point(161.6, 29);

	/*
	* Botão que leva à biblioteca.
	*/
	private var botaoBiblioteca:c_bt_simples;
	private static var NOME_BOTAO_BIBLIOTECA:String = "btBiblioteca";
	private static var LINK_PARA_BIBLIOTECA:String = c_objeto_acesso.LINK_BIBLIOTECA_USUARIO;
	private static var POSICAO_BOTAO_BIBLIOTECA:Point = new Point(162.55, 58.5);

	/*
	* Botão que leva à troca de aparência.
	*/
	private var botaoAparencia:c_bt_simples;
	private static var NOME_BOTAO_APARENCIA:String = "btAparencia";
	private static var LINK_PARA_APARENCIA:String = c_objeto_acesso.LINK_APARENCIA_USUARIO;
	private static var POSICAO_BOTAO_APARENCIA:Point = new Point(158.95, 94.4);
	
	/*
	* Botão para sair.
	*/
	private var botaoSair:c_bt_simples;
	private static var NOME_BOTAO_SAIR:String = "btSair";
	private static var POSICAO_BOTAO_SAIR:Point = new Point(378.15, 0.95);

//métodos
	public function inicializar(){
		mx.events.EventDispatcher.initialize(this);
		
		attachMovie("btBlog", NOME_BOTAO_BLOG, getNextHighestDepth());
		botaoBlog = this[NOME_BOTAO_BLOG];
		botaoBlog.inicializar();
		botaoBlog._x = POSICAO_BOTAO_BLOG.x;
		botaoBlog._y = POSICAO_BOTAO_BLOG.y;
		botaoBlog.addEventListener("btPressionado", Delegate.create(this, chamarLink));

		attachMovie("btBiblioteca", NOME_BOTAO_BIBLIOTECA, getNextHighestDepth());
		botaoBiblioteca = this[NOME_BOTAO_BIBLIOTECA];
		botaoBiblioteca.inicializar();
		botaoBiblioteca._x = POSICAO_BOTAO_BIBLIOTECA.x;
		botaoBiblioteca._y = POSICAO_BOTAO_BIBLIOTECA.y;
		botaoBiblioteca.addEventListener("btPressionado", Delegate.create(this, chamarLink));

		attachMovie("btAparencia", NOME_BOTAO_APARENCIA, getNextHighestDepth());
		botaoAparencia = this[NOME_BOTAO_APARENCIA];
		botaoAparencia.inicializar();
		botaoAparencia._x = POSICAO_BOTAO_APARENCIA.x;
		botaoAparencia._y = POSICAO_BOTAO_APARENCIA.y;
		botaoAparencia.addEventListener("btPressionado", Delegate.create(this, chamarLink));

		attachMovie("btSairInterface", NOME_BOTAO_SAIR, getNextHighestDepth());
		botaoSair = this[NOME_BOTAO_SAIR];
		botaoSair.inicializar();
		botaoSair._x = POSICAO_BOTAO_SAIR.x;
		botaoSair._y = POSICAO_BOTAO_SAIR.y;
		botaoSair.addEventListener("btPressionado", Delegate.create(this, fechar));
		
		_width = _width * 2;
		_height = _height * 2;
	}

	/*
	* Dispara um evento com o link que o usuário deseja acessar.
	*/
	private function chamarLink(evento_botao:Object){
		switch(evento_botao.nome){
			case NOME_BOTAO_BLOG: dispatchEvent({target:this, type:"chamarLink", link: LINK_PARA_BLOG});
				break;
			case NOME_BOTAO_BIBLIOTECA: dispatchEvent({target:this, type:"chamarLink", link: LINK_PARA_BIBLIOTECA});
				break;
			case NOME_BOTAO_APARENCIA: dispatchEvent({target:this, type:"chamarLink", link: LINK_PARA_APARENCIA + _root.personagem_status.getIdentificacaoBancoDeDados()});
				break;
		}
	}
	
	/*
	* Fecha esta interface e dispara um evento avisando que foi fechada.
	*/
	private function fechar(){
		dispatchEvent({target:this, type:"fechou"});
	}
	
	/*
	* Mostra este objeto.
	*/
	public function aparecer(){
		_y += 5000;
		_visible = true;
	}
	
	/*
	* Esconde este objeto.
	*/
	public function desaparecer(){
		_y -= 5000;
		_visible = false;
	}







}
