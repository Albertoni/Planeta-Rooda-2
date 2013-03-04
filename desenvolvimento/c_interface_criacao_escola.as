import mx.data.types.Obj;
import mx.events.EventDispatcher;
import mx.utils.Delegate;
import flash.geom.Point;

class c_interface_criacao_escola extends ac_interface_menu_cadastro {
//dados	
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_criacao_escola";
	
	/*
	* Escola a ser criada.
	*/
	private var escola_cadastro:c_escola_bd;
	
	/*
	* Nome de escola recebido.
	*/
	private var nomeRecebido:String = new String();
	
	/*
	* Botão para efetuar o cadastro.
	*/
	private var btCadastrar:c_btGrande;
	
	//---- Eventos
	public var addEventListener:Function;
	public var removeEventListener:Function;
	public var dispatchEvent:Function;	
	
	
//métodos
	public function inicializar():Void{
		mx.events.EventDispatcher.initialize(this);
		super.inicializacoes();
		
		escola_cadastro = new c_escola_bd();
		
		attachMovie(c_btGrande.LINK_BIBLIOTECA, "btCadastrar_dummy", getNextHighestDepth(), {_x: 168.95, _y: 110});
		btCadastrar = this['btCadastrar_dummy'];
		btCadastrar.inicializar("Cadastrar/Modificar");
		btCadastrar.addEventListener("btPressionado", Delegate.create(this, cadastrar));
		
		endereco_arquivo_pesquisa_php = c_banco_de_dados.ARQUIVO_PHP_PESQUISA_ESCOLA;
		endereco_arquivo_gravacao_php = c_banco_de_dados.ARQUIVO_PHP_CRIACAO_ESCOLA;
	}
	
	private function armazenarDadosAtualizadosDoCadastro():Void{
		mensagemErroCadastro = this.recebe.mensagemDeErro;
		mensagemSucessoCadastro = this.recebe.mensagemDeErro;
	}
	
	//---- Interface
	public function mostrar():Void{
		pesquisaNoBD();
		_visible = true;
	}
	public function esconder():Void{
		_visible = false;
	}
	private function preencherCampos(dados_param:ac_dados):Void{
		this['nome'].text = nomeRecebido;
	}
	
	//---- Pesquisa
	private function armazenarDadosPesquisa():Void{
		nomeRecebido = recebe.nome;
	}
	private function criaEnviaPesquisa(){
		envia = new LoadVars();
		envia.dado_pesquisado = null;
	}
	
	//---- Servidor
	private function criaEnvia():Void{
		envia.nome = escola_cadastro.getNome();
	}
	
	//---- Cadastro
	private function armazenarDadosParaCadastro():Void{
		escola_cadastro = new c_escola_bd();
		
		escola_cadastro.definirNome(this['nome'].text.split("\r").join("").split("\n").join(""));
		
		if(!informado(escola_cadastro.getNome())){
			escola_cadastro.definirNome("");
		}
	}
	private function comunicarSucessoCadastro():Void{
		this['nome'].text = "";
		
		c_aviso_com_ok.mostrar(mensagemSucessoCadastro);
	}
	private function comunicarErroCadastro():Void{
		c_aviso_com_ok.mostrar(mensagemErroCadastro);
	}
	
	//---- Dados
	private function dadosValidosParaCadastro():Boolean{
		return escola_cadastro.validarSemId();
	}
	
}