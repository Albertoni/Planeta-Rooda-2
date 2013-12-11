class c_interface_pesquisa_edicao_conta extends ac_interface_menu_pesquisa_edicao {
//dados
	/*
	* Link para este símbolo na biblioteca.
	*/
	public static var LINK_BIBLIOTECA:String = "interface_pesquisa_edicao_conta";

	//---- Interfaces (para inicializacao)
	private var pesquisa:c_interface_pesquisa_conta;
	private var edicao:c_interface_edicao_conta;
		
//métodos
	public function inicializar() {
		attachMovie("camposEditarContas", "edicao", getNextHighestDepth());
		attachMovie("camposPesquisarContas", "pesquisa", getNextHighestDepth());
			
		pesquisa.inicializar();
		edicao.inicializar();
		
		super.inicializacoes(pesquisa, edicao);
	}
	
	
}
