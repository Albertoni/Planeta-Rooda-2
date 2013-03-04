class c_interface_pesquisa_edicao_planeta extends ac_interface_menu_pesquisa_edicao {
//dados		
	//---- Interfaces (para inicializacao)
	private var pesquisa:c_interface_pesquisa_planeta;
	private var edicao:c_interface_edicao_planeta;
		
//métodos		
	public function inicializar() {
		attachMovie("camposEditarPlanetas", "edicao", getNextHighestDepth());
		attachMovie("camposPesquisarPlanetas", "pesquisa", getNextHighestDepth());
			
		pesquisa.inicializar();
		edicao.inicializar();

		super.inicializacoes(pesquisa, edicao);
	}
	
}
