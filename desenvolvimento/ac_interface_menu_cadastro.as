import mx.utils.Delegate;

class ac_interface_menu_cadastro extends ac_interface_menu{
//Dados
	//---- Servidor
	private var endereco_arquivo_pesquisa_php:String = new String(); //Este precisa ser incializado no construtor da classe que use este template.
	private var endereco_arquivo_gravacao_php:String = new String(); //Este precisa ser incializado no construtor da classe que use este template.

	//---- Aviso
	private var mensagemSucessoCadastro:String = "Cadastro efetuado com sucesso!";
	private var mensagemErroCadastro:String = "Erro ao efetuar o cadastro.";
	private var mensagemErroPesquisa:String = new String();

//Métodos
	public function inicializacoes():Void{
		super.inicializacoes();
	}
	
	//---- Interface
	public function preencherCampos(dados_param:ac_dados):Void{} //Deve ser implementada em cada classe que use este template.
	
	//---- Cadastro
	private function cadastrar():Void{
		if(dadosValidosParaCadastro()){
			armazenarDadosParaCadastro();
			cadastrarNoBD();
		}
		else{
			c_aviso_com_ok.mostrar("Erro de validação.");
		}
	}
	private function armazenarDadosParaCadastro():Void{} //Deve ser implementada em cada classe que use este template.
	private function armazenarDadosAtualizadosDoCadastro():Void{
		mensagemErroCadastro = this.recebe.mensagemDeErro;
	}
	
	//---- Pesquisa
	private function armazenarDadosPesquisa():Void{} //Deve ser implementada em cada classe que use este template.
	private function getDadosPesquisa():ac_dados{ return new ac_dados; } //Deve ser implementada em cada classe que use este template.
	private function pesquisaNoBD():Void{
		recebe = new LoadVars();
		envia = new LoadVars();
		
		criaEnviaPesquisa();
		
		recebe.onLoad = Delegate.create(this, receberDadosPesquisaPHP);
		envia.sendAndLoad(this.endereco_arquivo_pesquisa_php, recebe, "POST");
	}
	private function criaEnviaPesquisa(){} // Deve ser implementada em cada classe que use este template.
	private function receberDadosPesquisaPHP(success){
		if(success){
			armazenarDadosPesquisa();
			preencherCampos(getDadosPesquisa());
		}
	}
	
	//---- Cadastro
	private function cadastrarNoBD():Void{
		recebe = new LoadVars();
		envia = new LoadVars();

		criaEnvia();
		  
		recebe.onLoad = Delegate.create(this, receberDadosCadastroPHP);
		envia.sendAndLoad(this.endereco_arquivo_gravacao_php, recebe, "POST");
	}
	private function criaEnvia():Void{} //Deve ser implementada em cada classe que use este template.
	private function receberDadosCadastroPHP(success):Void{
		if(success){
			armazenarDadosAtualizadosDoCadastro();
			
			c_aviso_com_ok.mostrar(recebe.mensagemDeErro);
				
			if(this.recebe.operacaoRealizadaComSucesso == true){ comunicarSucessoCadastro(); }
			else{                                      			 comunicarErroCadastro(); }
		}
	}
	
	//---- Aviso
	private function comunicarSucessoCadastro():Void{
		c_aviso_com_ok.mostrar(mensagemSucessoCadastro);
	}
	private function comunicarErroCadastro():Void{
		c_aviso_com_ok.mostrar(mensagemErroCadastro);
	}
	
	//---- Dados
	private function dadosValidosParaCadastro():Boolean{return false;} //Deve ser implementada em cada classe que use este template.
	
	//---- Auxiliares
	private function fazNada(){}
	
	
	
	
}