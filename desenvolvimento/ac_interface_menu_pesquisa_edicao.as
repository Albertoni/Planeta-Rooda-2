import mx.utils.Delegate;

/*
		Template para classes de interfaces do menu centradas em pesquisa.
		
		Toda comunicação com o servidor retornará somente uma tupla.
*/
class ac_interface_menu_pesquisa_edicao extends ac_interface_menu{
//Dados
	//---- Interface
	private var POSX_INTERFACE:Number = 0;
	private var POSY_INTERFACE:Number = 0;
	
	private var interfacePesquisa:ac_interface_pesquisa;
	private var interfaceEdicao:ac_interface_edicao;
	private var interfaceAtiva:ac_interface_menu;
	
	//---- Botões
	private var btEditar:c_btEditarCampos;
	private var POSX_BT_EDITAR:Number = 0;
	private var POSY_BT_EDITAR:Number = 325;
	
	private var btVoltar:c_btVoltarCampos;
	private var POSX_BT_VOLTAR:Number = POSX_BT_EDITAR;
	private var POSY_BT_VOLTAR:Number = POSY_BT_EDITAR;
	
//Métodos
	//A função a seguir deve ser chamada nos construtores de todas as classes filhas.
	private function inicializacoes(_interfacePesquisa:ac_interface_pesquisa, _interfaceEdicao:ac_interface_edicao):Void{
		super.inicializacoes();
		
		//Agregados...
		interfacePesquisa = _interfacePesquisa;
		interfacePesquisa._x = POSX_INTERFACE;
		interfacePesquisa._y = POSY_INTERFACE;
		interfaceEdicao = _interfaceEdicao;
		interfaceEdicao._x = POSX_INTERFACE;
		interfaceEdicao._y = POSY_INTERFACE;
		interfaceAtiva = interfacePesquisa;
		
		attachMovie("btEditarCampos", "btEditar", getNextHighestDepth());
		btEditar.inicializar();
		btEditar._x = POSX_BT_EDITAR;
		btEditar._y = POSY_BT_EDITAR;
		btEditar.addEventListener("btEditarCamposPress", Delegate.create(this, editar));	
		btEditar._visible = true;
		
		attachMovie("btVoltarCampos", "btVoltar", getNextHighestDepth());
		btVoltar.inicializar();
		btVoltar._x = POSX_BT_VOLTAR;
		btVoltar._y = POSY_BT_VOLTAR;
		btVoltar.addEventListener("btVoltarCamposPress", Delegate.create(this, voltar));	
		btVoltar._visible = false;
	}
	
	//---- Interface
	public function mostrar():Void{
		_visible = true;
		esconderInterfaces();
		interfaceAtiva.mostrar();
	}
	public function esconder():Void{
		esconderInterfaces();
		_visible = false;
	}
	private function esconderInterfaces():Void{
		interfacePesquisa.esconder();
		interfaceEdicao.esconder();
	}
	
	//---- Botões
	private function editar(){
		if(interfacePesquisa.haResultadoDePesquisaSendoExibido()){
			interfacePesquisa.esconder();
			interfaceAtiva = interfaceEdicao;
			interfaceEdicao.preencherCampos(interfacePesquisa.dados());
			interfaceEdicao.mostrar();
			btEditar._visible = false;
			btVoltar._visible = true;
		}
	}
	private function voltar(){
		interfaceEdicao.esconder();
		interfaceAtiva = interfacePesquisa;
		interfacePesquisa.mostrar();
		interfacePesquisa.mostrarResultado();
		btEditar._visible = true;
		btVoltar._visible = false;
	}


	


}