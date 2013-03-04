class c_interface_pesquisa_edicao_turma extends ac_interface_menu_pesquisa_edicao {
//dados		
	//---- Interfaces (para inicializacao)
	private var pesquisa:c_interface_pesquisa_turma;
	private var edicao:c_interface_edicao_turma;
		
//métodos
	public function inicializar() {
		attachMovie("camposEditarTurmas", "edicao", getNextHighestDepth());
		attachMovie("camposPesquisarTurmas", "pesquisa", getNextHighestDepth());
		
		pesquisa.inicializar();
		edicao.inicializar();
		
		super.inicializacoes(pesquisa, edicao);
	}
	
	
	
}
