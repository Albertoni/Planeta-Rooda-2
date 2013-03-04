import mx.utils.Delegate;

class ac_interface_edicao extends ac_interface_menu{
//Dados
	//---- Servidor
	private var endereco_arquivo_pesquisa_php:String = new String(); //Este precisa ser incializado no construtor da classe que use este template.
	private var endereco_arquivo_gravacao_php:String = new String(); //Este precisa ser incializado no construtor da classe que use este template.
	private var endereco_arquivo_delecao_php:String = new String(); //Este precisa ser incializado no construtor da classe que use este template.
	
	//---- Aviso
	private var mensagemSucesso:String = new String();
	private var mensagemErro:String = new String();
	private var mensagemErroPesquisa:String = new String();
	
	//---- Mensagens de Erro
	private var MENSAGEM_DADOS_SALVOS:String = "Dados salvos com sucesso!";
	private var MENSAGEM_ERRO_PESQUISA:String = "Houve um erro ao pesquisar seus dados.";
	private var MENSAGEM_ERRO_SALVAR:String = "Houve um erro ao salvar os dados.";
	private var MENSAGEM_ERRO_VALIDACAO_DESCONHECIDO:String = "Erro de validação desconhecido.";
	
	//---- Botões
	private var btSalvar:c_btSalvar;
	private var POSX_BT_SALVAR:Number = 140;
	private var POSY_BT_SALVAR:Number = 325;
	
	private var btDeletar:c_btDeletar;
	private var POSX_BT_DELETAR:Number = POSX_BT_SALVAR + 140;
	private var POSY_BT_DELETAR:Number = POSY_BT_SALVAR;
	
//Métodos
	public function inicializacoes(){
		super.inicializacoes();
		mensagemSucesso = MENSAGEM_DADOS_SALVOS;//Mensagem default. Pode ser sobrescrita.
		mensagemErroPesquisa = MENSAGEM_ERRO_PESQUISA;//Mensagem default. Pode ser sobrescrita.
		mensagemErro = MENSAGEM_ERRO_SALVAR;//Mensagem default. Pode ser sobrescrita.
		
		attachMovie("btSalvar", "btSalvar", getNextHighestDepth());
		btSalvar.inicializar();
		btSalvar._x = POSX_BT_SALVAR;
		btSalvar._y = POSY_BT_SALVAR;
		btSalvar.addEventListener("btSalvarPress", Delegate.create(this, salvar));	
		
		attachMovie("btDeletar", "btDeletar", getNextHighestDepth());
		btDeletar.inicializar();
		btDeletar._x = POSX_BT_DELETAR;
		btDeletar._y = POSY_BT_DELETAR;
		btDeletar.addEventListener("btDeletarPress", Delegate.create(this, deletar));	
	}
	
	//---- Interface
	public function mostrar():Void{
		_visible = true;
	}
	public function esconder():Void{
		_visible = false;
	}
	private function restringirCamposDeTexto():Void{} //Deve ser implementada em cada classe que use este template.
	public function preencherCampos(dados_param:ac_dados):Void{} //Deve ser implementada em cada classe que use este template.
	
	//---- Botões
	private function deletar(){
		deletarNoBD();
	}
	private function salvar(){
		armazenarDadosEditados();
		if(validarDados()){
			salvarNoBD();
		}
		else{
			mensagemErro = getErroValidacao();
			comunicarErro();
		}
			
		//A tupla que foi editada pode não mais fazer parte dos resultados da última pesquisa.
		//No entanto, a função de pesquisar também meche com o BD e não pode ser usada concorrentemente à de salvar.
		//Portanto, movi a chamada à função de pesquisa para a função receberDadosEdicaoPHP.
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

			if(recebe.operacaoRealizadaComSucesso == true){ preencherCampos(getDadosPesquisa()); }
			else{                                           comunicarErroPesquisa(); }
		}
	}
	
	//---- Edição
	private function armazenarDadosEditados():Void{} //Deve ser implementada em cada classe que use este template.
	private function armazenarDadosAtualizadosDaEdicao():Void{
		 mensagemErro = recebe.mensagemDeErro;
	}
	private function salvarNoBD():Void{
		recebe = new LoadVars();
		envia = new LoadVars();

		criarEnvia();
		
		recebe.onLoad = Delegate.create(this, receberDadosEdicaoPHP);
		envia.sendAndLoad(endereco_arquivo_gravacao_php, recebe, "POST");
	}
	private function criarEnvia(){}//Deve ser implementada em cada classe que use este template.
	private function receberDadosEdicaoPHP(success):Void{
		if(success){
			//c_aviso_com_ok.mostrar("DEU SUCCESS O erro é:"+recebe.toString());
			armazenarDadosAtualizadosDaEdicao();
			//c_aviso_com_ok.mostrar("mensagemDeErro=\n"+recebe.mensagemDeErro);
			if(recebe.operacaoRealizadaComSucesso == true){ comunicarSucesso();
															/*this.pesquisar(this.dado_pesquisado, this.pos_tupla_resultado_pesquisa);*/ }
			else{                                           comunicarErro();   }
		} else {
			c_aviso_com_ok.mostrar("DEU ERRO!!! O erro é:"+recebe.toString());
		}
	}
	
	//---- Deleção
	private function deletarNoBD():Void{
		recebe = new LoadVars();
		envia = new LoadVars();

		criarEnvia();
		
		recebe.onLoad = Delegate.create(this, receberDadosDelecaoPHP);
		envia.sendAndLoad(this.endereco_arquivo_delecao_php, recebe, "POST");
	}
	private function receberDadosDelecaoPHP(success):Void{
		if(success){
			armazenarDadosAtualizadosDaEdicao();
			if(recebe.operacaoRealizadaComSucesso == true){ comunicarSucesso(); }
			else{                                           comunicarErro();    }
		}
	}
	
	//---- Aviso
	private function comunicarSucesso():Void{
		c_aviso_com_ok.mostrar(mensagemSucesso);
	}
	private function comunicarErro():Void{
		c_aviso_com_ok.mostrar(mensagemErro);
	}
	private function comunicarErroPesquisa():Void{
		c_aviso_com_ok.mostrar(mensagemErroPesquisa);
	}
	
	//---- Dados
	public function validarDados():Boolean{
		return false;
	} //Deve ser implementada em cada classe que use este template.
	public function getErroValidacao():String{
		return MENSAGEM_ERRO_VALIDACAO_DESCONHECIDO;
	} //Deve ser implementada em cada classe que use este template.
	
	
}