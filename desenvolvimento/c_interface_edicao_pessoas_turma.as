import flash.geom.Point;
import mx.utils.Delegate;

/*
* Na interface de edição de turmas, é a interface genérica que permite edição de usuário de um nível específico
* que estão na turma. Permite inserção e deleção de usuários com aquele nível na turma.
*/
class c_interface_edicao_pessoas_turma extends c_interface_pesquisa_conta{
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_edicao_pessoas_turma";

	/*
	* Botão para inserir uma pessoa na lista de pessoas.
	*/
	private var btInserirPessoa:c_btGrande = undefined;
	private var POSICAO_BT_INSERIR_PESSOA:Point;
	
	/*
	* Botão para remover uma pessoa na lista de pessoas.
	*/
	private var btRemoverPessoa:c_btGrande = undefined;
	private var POSICAO_BT_REMOVER_PESSOA:Point;

	/*
	* Menu que contém as pessoas da turma com o nível escolhido para esta interface.
	*/
	private var menu_pessoas:c_select = undefined;
	private var POSICAO_MENU_PESSOAS:Point;
	
	/*
	* Se feita pesquisa por parte de nome de pessoa, este menu conterá resultados da busca feita.
	*/
	private var menu_resultados_pesquisa:c_select = undefined;
	private var POSICAO_MENU_RESULTADOS_PESQUISA:Point;
	
	/*
	* Array de contas recebidas da pesquisa.
	*/
	private var contasRecebidas:Array;
	
//métodos
	/*
	* Inicializa esta interface, preparando-a para ser usada.
	* @param dados_param Dados que devem ser exibidos no menu. Deve ser um array de Strings, em que cada elemento será uma opção.
	* @param nome_nivel_param Nome do nível (na hierarquia) que esta interface edita, para inserção nos labels.
	*/
	public function inicializar(dados_param:Array, nome_nivel_singular_param:String, nome_nivel_plural_param:String){
		super.inicializacoes();
		
		conta_pesquisa = new c_conta();
		contasRecebidas = new Array();
		endereco_arquivo_pesquisa_php = "phps_do_menu/pesquisa_todas_contas.php";
		this['fundoBarraPesquisa'].removeMovieClip();
		this['textoResultado'].removeMovieClip();
		this['btAnterior'].removeMovieClip();
		this['btProximo'].removeMovieClip();
		this['barraPesquisa'].removeMovieClip();
		this['barraPesquisa'] = this['nomePessoa'];
		
		POSICAO_BT_INSERIR_PESSOA = new Point(this['nomePessoa']._x,this['nomePessoa']._y + this['nomePessoa']._height + 10);
		
		var formatoLabelNomePessoa:TextFormat = this['labelNomePessoa'].getTextFormat();
		formatoLabelNomePessoa.bold = true;
		this['labelNomePessoa'].setTextFormat(formatoLabelNomePessoa);
		this['labelNomePessoa'].text = "Nome do "+nome_nivel_singular_param;
		
		if(btInserirPessoa == undefined){
			attachMovie(c_btGrande.LINK_BIBLIOTECA, "btInserirPessoa_dummy", getNextHighestDepth(), {_x:POSICAO_BT_INSERIR_PESSOA.x, _y:POSICAO_BT_INSERIR_PESSOA.y});
			btInserirPessoa = this['btInserirPessoa_dummy'];
			btInserirPessoa.inicializar("Cadastrar na Turma");
			btInserirPessoa.addEventListener("btPressionado", Delegate.create(this,inserirDadoDaCaixa));
		}
		
		POSX_BT_PESQUISAR = btInserirPessoa._x + btInserirPessoa._width + 10;
		POSY_BT_PESQUISAR = btInserirPessoa._y;
		btPesquisar._x = POSX_BT_PESQUISAR;
		btPesquisar._y = POSY_BT_PESQUISAR;
		
		POSICAO_MENU_RESULTADOS_PESQUISA = new Point(btInserirPessoa._x, btInserirPessoa._y + btInserirPessoa._height + 30);
		
		if(menu_resultados_pesquisa == undefined){
			attachMovie(c_select.LINK_BIBLIOTECA, "menu_resultado_pesquisa_dummy", getNextHighestDepth(), {_x:POSICAO_MENU_RESULTADOS_PESQUISA.x, _y:POSICAO_MENU_RESULTADOS_PESQUISA.y});
			menu_resultados_pesquisa = this['menu_resultado_pesquisa_dummy'];
			menu_resultados_pesquisa.inicializar(5, new Array(), "Resultados da Pesquisa");
			menu_resultados_pesquisa.setTipoInvisivel(false);
			menu_resultados_pesquisa._visible = true;
			menu_resultados_pesquisa.addEventListener("botaoPressionado", Delegate.create(this, adicionarPessoaDeResultado));
			menu_resultados_pesquisa.limparOpcoes();
			mx.events.EventDispatcher.initialize(menu_resultados_pesquisa);
		}
		
		POSICAO_MENU_PESSOAS = new Point(btInserirPessoa._x + menu_resultados_pesquisa._width + 10, this['labelNomePessoa']._y + this['labelNomePessoa']._height);
		
		if(menu_pessoas == undefined){
			attachMovie(c_select.LINK_BIBLIOTECA, "menu_pessoas_dummy", getNextHighestDepth(), {_x:POSICAO_MENU_PESSOAS.x, _y:POSICAO_MENU_PESSOAS.y});
			menu_pessoas = this['menu_pessoas_dummy'];
			menu_pessoas.inicializar(5, dados_param, nome_nivel_plural_param+" nesta turma");
			menu_pessoas.setTipoInvisivel(false);
			menu_pessoas._visible = true;
			menu_pessoas.addEventListener("botaoPressionado", Delegate.create(this, mostrarBotaoRemoverPessoa));
		} else {
			carregarNovosDados(dados_param);
		}
		
		menu_pessoas.redimensionar(0.95*menu_pessoas._width, menu_pessoas._height);
		POSICAO_BT_REMOVER_PESSOA = new Point(menu_pessoas._x,menu_pessoas._y + menu_pessoas._height + 10);
		
		if(btRemoverPessoa == undefined){
			attachMovie(c_btGrande.LINK_BIBLIOTECA, "btRemoverPessoa_dummy", getNextHighestDepth(), {_x:POSICAO_BT_REMOVER_PESSOA.x, _y:POSICAO_BT_REMOVER_PESSOA.y});
			btRemoverPessoa = this['btRemoverPessoa_dummy'];
			btRemoverPessoa.inicializar("Remover da Turma");
			btRemoverPessoa.addEventListener("btPressionado", Delegate.create(this,removerObjetoMenu));
		}
		btRemoverPessoa._visible = false;
	}
	
	/*
	* Override no método de armazenamento para guardar todas as contas, não somente uma.
	*/
	private function armazenarResultadosPesquisa():Void{
		var contaAtual:Number=0;
		
		numero_de_resultados_ultima_pesquisa = recebe.numDadosEncontrados;
		dado_pesquisado = recebe.dado_pesquisado;
		
		contasRecebidas = new Array();
		for(contaAtual=0; contaAtual<numero_de_resultados_ultima_pesquisa; contaAtual++){
			conta_pesquisa = new c_conta();
			conta_pesquisa.nome = recebe['usuario_nome'+contaAtual];
			contasRecebidas.push(conta_pesquisa);
		}
	}
	
	/*
	* Override, pois recebe array.
	*/
	private function dadosRecebidos():Boolean{
		if(recebe.usuario_id0 != undefined 
		   or recebe.usuario_login0 != undefined 
		   or recebe.usuario_senha0 != undefined
		   or recebe.usuario_data_aniversario0 != undefined
		   or recebe.usuario_nome0 != undefined
		   or recebe.usuario_nome_mae0 != undefined
		   or recebe.usuario_email0 != undefined 
		   or recebe.usuario_nivel0 != undefined
		   or recebe.usuario_apelido0 != undefined
		   or recebe.usuario_sexo0 != undefined){
			return true;
		}
		else{
			return false;
		}
	}
	
	/*
	* Override nesta função para que exiba os resultados da forma que o faz esta interface: em um select.
	*/
	private function comunicarResultadosDaPesquisa():Void{
		menu_resultados_pesquisa.limparOpcoes();
		for(var contaAtual:Number=0; contaAtual<contasRecebidas.length; contaAtual++){
			menu_resultados_pesquisa.inserirOpcao(contasRecebidas[contaAtual].nome);
		}
	}
	
	/*
	* Passa o nome que estiver selecionado no menu de resultados da pesquisa para o select de pessoas cadastradas.
	*/
	private function adicionarPessoaDeResultado():Void{
		if(menu_resultados_pesquisa.getOpcaoSelecionada() != undefined){
			if(!menu_pessoas.existeOpcao(menu_resultados_pesquisa.getOpcaoSelecionada()) and menu_resultados_pesquisa.getOpcaoSelecionada() != new String()){
				menu_pessoas.inserirOpcao(menu_resultados_pesquisa.getOpcaoSelecionada());
			} else {
				c_aviso_com_ok.mostrar("Desculpe. Esta pessoa já foi inserida.");
			}
		}
	}

	/*
	* Simplesmente mostra o botão de remover pessoa.
	*/
	private function mostrarBotaoRemoverPessoa():Void{
		btRemoverPessoa._visible = true;
	}

	/*
	* Remove do menu o dado que estiver selecionado.
	*/
	private function removerObjetoMenu():Void{
		menu_pessoas.retirarOpcao(menu_pessoas.getOpcaoSelecionada());
		if(menu_pessoas.getListaOpcoes().length == 0){
			btRemoverPessoa._visible = false;
		}
	}

	/*
	* @return Todos os dados do menu em um array. 
	*		  Cada elemento do Array é uma String e corresponde a uma opção do menu.
	*/
	public function getDadosMenu():Array{
		return menu_pessoas.getListaOpcoes();
	}
	
	/*
	* Insere no menu o conteudo da caixa de texto nomePessoa e a limpa.
	*/
	public function inserirDadoDaCaixa():Void{
		if(!menu_pessoas.existeOpcao(this['nomePessoa'].text) and this['nomePessoa'].text != new String()){
			menu_pessoas.inserirOpcao(this['nomePessoa'].text);
		} else {
			c_aviso_com_ok.mostrar("Desculpe. Esta pessoa já foi inserida.");
		}
		this['nomePessoa'].text = new String();
	}
	
	/*
	* Carrega dados novos no menu, deletando os dados existentes.
	* @param dados_param Array em que cada elemento é uma String e corresponde a uma opção do menu.
	*/
	public function carregarNovosDados(dados_param:Array):Void{
		var tamanhoArrayDados:Number = dados_param.length;
		menu_pessoas.limparOpcoes();
		for(var indice:Number=0; indice<tamanhoArrayDados; indice++){
			menu_pessoas.inserirOpcao(dados_param[indice]);
		}
	}

	
	
	
}